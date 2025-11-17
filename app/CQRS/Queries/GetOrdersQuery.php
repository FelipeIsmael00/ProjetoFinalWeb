<?php

namespace App\CQRS\Queries;

/**
 * Query para listagem de pedidos (CQRS - Read Side)
 * Aplicando:
 * - CQRS Pattern: Separação de responsabilidades entre comandos (escrita) e queries (leitura)
 * - Single Responsibility Principle (SOLID): Responsabilidade única de representar uma consulta
 * - Query Object Pattern: Encapsula parâmetros de consulta
 */
class GetOrdersQuery
{
    public function __construct(
        private ?int $userId = null,
        private ?string $status = null,
        private ?string $paymentMethod = null,
        private int $limit = 50,
        private int $offset = 0
    ) {
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
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

