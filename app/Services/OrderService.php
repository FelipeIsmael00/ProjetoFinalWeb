<?php

namespace App\Services;

use App\CQRS\Commands\CreateOrderCommand;
use App\CQRS\Commands\Handlers\CreateOrderCommandHandler;
use App\CQRS\Queries\GetOrdersQuery;
use App\CQRS\Queries\Handlers\GetOrdersQueryHandler;
use App\Models\Order;
use App\Payment\PaymentProcessor;
use App\Payment\PaymentStrategyFactory;
use App\Services\NotificationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Service para gerenciar pedidos
 * Aplicando:
 * - Service Layer Pattern: Camada de serviço que orquestra operações complexas
 * - Dependency Inversion Principle (SOLID): Injeção de dependência via construtor
 * - Single Responsibility Principle (SOLID): Responsabilidade única de orquestrar operações de pedidos
 */
class OrderService
{
    public function __construct(
        private CreateOrderCommandHandler $createOrderHandler,
        private GetOrdersQueryHandler $getOrdersHandler,
        private PaymentProcessor $paymentProcessor,
        private NotificationService $notificationService
    ) {
    }

    /**
     * Cria um novo pedido usando CQRS Command e processa pagamento
     * 
     * @param array $data
     * @return Order
     */
    public function createOrder(array $data): Order
    {
        $command = new CreateOrderCommand(
            userId: $data['user_id'],
            items: $data['items'],
            paymentMethod: $data['payment_method'],
            paymentData: $data['payment_data'] ?? []
        );

        // Cria o pedido
        $order = $this->createOrderHandler->handle($command);

        // Processa o pagamento usando Strategy Pattern
        $strategy = PaymentStrategyFactory::create($data['payment_method']);
        $this->paymentProcessor->setStrategy($strategy);
        
        $paymentResult = $this->paymentProcessor->process(
            $order->total_amount,
            $data['payment_data'] ?? []
        );

        // Atualiza o pedido com o resultado do pagamento
        if ($paymentResult['success']) {
            $order->update([
                'status' => Order::STATUS_PAID,
                'transaction_id' => $paymentResult['transaction_id'],
            ]);

            // Envia notificação usando Factory Method
            $userEmail = $order->user ? $order->user->email : 'cliente@example.com';
            $this->notificationService->sendNotification(
                'email',
                $userEmail,
                "Seu pedido #{$order->id} foi confirmado! Total: R$ " . number_format($order->total_amount, 2, ',', '.')
            );
        }

        return $order->fresh(['items.product', 'user']);
    }

    /**
     * Lista pedidos usando CQRS Query
     * 
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getOrders(array $filters = []): LengthAwarePaginator
    {
        $query = new GetOrdersQuery(
            userId: $filters['user_id'] ?? null,
            status: $filters['status'] ?? null,
            paymentMethod: $filters['payment_method'] ?? null,
            limit: $filters['limit'] ?? 50,
            offset: $filters['offset'] ?? 0
        );

        return $this->getOrdersHandler->handle($query);
    }
}

