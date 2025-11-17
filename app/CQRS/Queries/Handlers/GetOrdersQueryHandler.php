<?php

namespace App\CQRS\Queries\Handlers;

use App\CQRS\Queries\GetOrdersQuery;
use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

/**
 * Handler para a query de listagem de pedidos
 * Aplicando:
 * - CQRS Pattern: Handler responsável por processar queries de leitura
 * - Single Responsibility Principle (SOLID): Responsabilidade única de processar consultas de pedidos
 * - Dependency Inversion Principle (SOLID): Depende de abstração (Query), não de implementação concreta
 * - Separation of Concerns: Separação clara entre leitura e escrita
 */
class GetOrdersQueryHandler
{
    /**
     * Processa a query de listagem de pedidos
     * 
     * @param GetOrdersQuery $query
     * @return LengthAwarePaginator
     */
    public function handle(GetOrdersQuery $query): LengthAwarePaginator
    {
        $builder = Order::with(['items.product', 'user']);

        // Aplicar filtros baseados nos parâmetros da query
        if ($query->getUserId()) {
            $builder->where('user_id', $query->getUserId());
        }

        if ($query->getStatus()) {
            $builder->where('status', $query->getStatus());
        }

        if ($query->getPaymentMethod()) {
            $builder->where('payment_method', $query->getPaymentMethod());
        }

        // Ordenar por mais recente
        $builder->orderBy('created_at', 'desc');

        Log::info("Pedidos consultados via CQRS Query", [
            'filters' => [
                'user_id' => $query->getUserId(),
                'status' => $query->getStatus(),
                'payment_method' => $query->getPaymentMethod(),
            ],
        ]);

        return $builder->paginate($query->getLimit());
    }
}

