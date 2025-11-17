<?php

namespace App\CQRS\Commands\Handlers;

use App\CQRS\Commands\CreateOrderCommand;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Handler para o comando de criação de pedido
 * Aplicando:
 * - CQRS Pattern: Handler responsável por processar comandos de escrita
 * - Single Responsibility Principle (SOLID): Responsabilidade única de processar criação de pedidos
 * - Dependency Inversion Principle (SOLID): Depende de abstração (Command), não de implementação concreta
 */
class CreateOrderCommandHandler
{
    /**
     * Processa o comando de criação de pedido
     * 
     * @param CreateOrderCommand $command
     * @return Order
     * @throws \Exception
     */
    public function handle(CreateOrderCommand $command): Order
    {
        return DB::transaction(function () use ($command) {
            $totalAmount = 0;
            $orderItems = [];

            // Valida e calcula o total
            foreach ($command->getItems() as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if (!$product->isAvailable($item['quantity'])) {
                    throw new \Exception("Produto {$product->name} não possui estoque suficiente");
                }

                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ];
            }

            // Cria o pedido
            $order = Order::create([
                'user_id' => $command->getUserId(),
                'total_amount' => $totalAmount,
                'payment_method' => $command->getPaymentMethod(),
                'status' => Order::STATUS_PENDING,
            ]);

            // Cria os itens do pedido e reduz o estoque
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Reduz o estoque
                $product = Product::find($item['product_id']);
                $product->reduceStock($item['quantity']);
            }

            Log::info("Pedido criado via CQRS Command", [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'total_amount' => $order->total_amount,
            ]);

            return $order->load('items.product');
        });
    }
}

