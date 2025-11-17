<?php

namespace App\CQRS\Queries\Handlers;

use App\CQRS\Queries\GetProductsQuery;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

/**
 * Handler para a query de listagem de produtos
 * Aplicando:
 * - CQRS Pattern: Handler responsável por processar queries de leitura
 * - Single Responsibility Principle (SOLID): Responsabilidade única de processar consultas de produtos
 * - Dependency Inversion Principle (SOLID): Depende de abstração (Query), não de implementação concreta
 * - Separation of Concerns: Separação clara entre leitura e escrita
 */
class GetProductsQueryHandler
{
    /**
     * Processa a query de listagem de produtos
     * 
     * @param GetProductsQuery $query
     * @return LengthAwarePaginator
     */
    public function handle(GetProductsQuery $query): LengthAwarePaginator
    {
        $builder = Product::query();

        // Aplicar filtros baseados nos parâmetros da query
        if ($query->getCategory()) {
            $builder->where('category', $query->getCategory());
        }

        if ($query->getMinPrice() !== null) {
            $builder->where('price', '>=', $query->getMinPrice());
        }

        if ($query->getMaxPrice() !== null) {
            $builder->where('price', '<=', $query->getMaxPrice());
        }

        if ($query->getMinStock() !== null) {
            $builder->where('stock', '>=', $query->getMinStock());
        }

        // Otimização: selecionar apenas campos necessários para leitura
        $builder->select(['id', 'name', 'description', 'price', 'stock', 'category', 'created_at']);

        Log::info("Produtos consultados via CQRS Query", [
            'filters' => [
                'category' => $query->getCategory(),
                'min_price' => $query->getMinPrice(),
                'max_price' => $query->getMaxPrice(),
                'min_stock' => $query->getMinStock(),
            ],
        ]);

        return $builder->paginate($query->getLimit());
    }
}


