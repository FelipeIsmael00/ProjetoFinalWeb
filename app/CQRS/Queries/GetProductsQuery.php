<?php

namespace App\CQRS\Queries;

/**
 * Query para listagem de produtos (CQRS - Read Side)
 * Aplicando:
 * - CQRS Pattern: Separação de responsabilidades entre comandos (escrita) e queries (leitura)
 * - Single Responsibility Principle (SOLID): Responsabilidade única de representar uma consulta
 * - Query Object Pattern: Encapsula parâmetros de consulta
 */
class GetProductsQuery
{
    public function __construct(
        private ?string $category = null,
        private ?float $minPrice = null,
        private ?float $maxPrice = null,
        private ?int $minStock = null,
        private int $limit = 50,
        private int $offset = 0
    ) {
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function getMinPrice(): ?float
    {
        return $this->minPrice;
    }

    public function getMaxPrice(): ?float
    {
        return $this->maxPrice;
    }

    public function getMinStock(): ?int
    {
        return $this->minStock;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }
}


