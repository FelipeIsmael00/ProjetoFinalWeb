<?php

namespace App\Payment\Strategies;

use App\Payment\Contracts\PaymentStrategyInterface;

/**
 * Strategy concreta para pagamento com cartão de crédito
 * Aplicando Single Responsibility Principle (SOLID)
 */
class CreditCardPayment implements PaymentStrategyInterface
{
    public function processPayment(float $amount, array $data = []): array
    {
        // Simulação de processamento de pagamento com cartão de crédito
        $cardNumber = $data['card_number'] ?? '';
        $cvv = $data['cvv'] ?? '';
        
        // Validação básica
        if (empty($cardNumber) || empty($cvv)) {
            return [
                'success' => false,
                'message' => 'Dados do cartão inválidos',
                'transaction_id' => null,
            ];
        }
        
        // Simulação de processamento
        $transactionId = 'CC-' . uniqid();
        
        \Log::info("Pagamento com cartão de crédito processado: {$transactionId} - Valor: {$amount}");
        
        return [
            'success' => true,
            'message' => 'Pagamento com cartão de crédito processado com sucesso',
            'transaction_id' => $transactionId,
        ];
    }
}


