<?php

namespace App\Payment\Strategies;

use App\Payment\Contracts\PaymentStrategyInterface;

/**
 * Strategy concreta para pagamento via PIX
 * Aplicando Single Responsibility Principle (SOLID)
 */
class PixPayment implements PaymentStrategyInterface
{
    public function processPayment(float $amount, array $data = []): array
    {
        // Simulação de processamento de pagamento PIX
        $pixKey = $data['pix_key'] ?? '';
        
        if (empty($pixKey)) {
            return [
                'success' => false,
                'message' => 'Chave PIX não informada',
                'transaction_id' => null,
            ];
        }
        
        // Simulação de processamento PIX (geralmente instantâneo)
        $transactionId = 'PIX-' . uniqid();
        
        \Log::info("Pagamento PIX processado: {$transactionId} - Valor: {$amount}");
        
        return [
            'success' => true,
            'message' => 'Pagamento PIX processado com sucesso',
            'transaction_id' => $transactionId,
        ];
    }
}


