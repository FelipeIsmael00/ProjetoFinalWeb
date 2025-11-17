<?php

namespace App\Payment\Contracts;

/**
 * Interface Strategy para métodos de pagamento
 * Aplicando Dependency Inversion Principle (SOLID)
 */
interface PaymentStrategyInterface
{
    /**
     * Processa o pagamento
     * 
     * @param float $amount Valor do pagamento
     * @param array $data Dados adicionais do pagamento
     * @return array Resultado do pagamento ['success' => bool, 'message' => string, 'transaction_id' => string|null]
     */
    public function processPayment(float $amount, array $data = []): array;
}


