<?php

namespace App\CQRS\Commands;

/**
 * Command para criação de pedido (CQRS - Write Side)
 * Aplicando:
 * - CQRS Pattern: Separação de responsabilidades entre comandos (escrita) e queries (leitura)
 * - Single Responsibility Principle (SOLID): Responsabilidade única de criar pedidos
 * - Command Pattern: Encapsula uma operação como um objeto
 */
class CreateOrderCommand
{
    public function __construct(
        private int $userId,
        private array $items, // [['product_id' => int, 'quantity' => int], ...]
        private string $paymentMethod,
        private array $paymentData = []
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getPaymentData(): array
    {
        return $this->paymentData;
    }
}

