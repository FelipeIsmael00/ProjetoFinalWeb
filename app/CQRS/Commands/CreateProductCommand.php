<?php

namespace App\CQRS\Commands;

use App\Models\Product;

/**
 * Command para criação de produto (CQRS - Write Side)
 * Aplicando:
 * - CQRS Pattern: Separação de responsabilidades entre comandos (escrita) e queries (leitura)
 * - Single Responsibility Principle (SOLID): Responsabilidade única de criar produtos
 * - Command Pattern: Encapsula uma operação como um objeto
 */
class CreateProductCommand
{
    public function __construct(
        private string $name,
        private string $description,
        private float $price,
        private int $stock,
        private string $category
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function getCategory(): string
    {
        return $this->category;
    }
}


