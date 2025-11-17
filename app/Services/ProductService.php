<?php

namespace App\Services;

use App\CQRS\Commands\CreateProductCommand;
use App\CQRS\Commands\Handlers\CreateProductCommandHandler;
use App\CQRS\Queries\GetProductsQuery;
use App\CQRS\Queries\Handlers\GetProductsQueryHandler;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Service para gerenciar produtos
 * Aplicando:
 * - Service Layer Pattern: Camada de serviço que orquestra operações
 * - Dependency Inversion Principle (SOLID): Injeção de dependência via construtor
 * - Single Responsibility Principle (SOLID): Responsabilidade única de orquestrar operações de produtos
 */
class ProductService
{
    public function __construct(
        private CreateProductCommandHandler $createProductHandler,
        private GetProductsQueryHandler $getProductsHandler
    ) {
    }

    /**
     * Cria um novo produto usando CQRS Command
     * 
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        $command = new CreateProductCommand(
            name: $data['name'],
            description: $data['description'],
            price: $data['price'],
            stock: $data['stock'],
            category: $data['category']
        );

        return $this->createProductHandler->handle($command);
    }

    /**
     * Lista produtos usando CQRS Query
     * 
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getProducts(array $filters = []): LengthAwarePaginator
    {
        $query = new GetProductsQuery(
            category: $filters['category'] ?? null,
            minPrice: $filters['min_price'] ?? null,
            maxPrice: $filters['max_price'] ?? null,
            minStock: $filters['min_stock'] ?? null,
            limit: $filters['limit'] ?? 50,
            offset: $filters['offset'] ?? 0
        );

        return $this->getProductsHandler->handle($query);
    }
}


