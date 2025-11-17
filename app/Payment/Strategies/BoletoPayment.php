<?php

namespace App\Payment\Strategies;

use App\Payment\Contracts\PaymentStrategyInterface;

/**
 * Strategy concreta para pagamento com boleto bancário
 * Aplicando Single Responsibility Principle (SOLID)
 */
class BoletoPayment implements PaymentStrategyInterface
{
    public function processPayment(float $amount, array $data = []): array
    {
        // Simulação de geração de boleto
        $transactionId = 'BOL-' . uniqid();
        $barcode = $this->generateBarcode();
        $dueDate = now()->addDays(3)->format('Y-m-d');
        
        \Log::info("Boleto gerado: {$transactionId} - Valor: {$amount} - Vencimento: {$dueDate}");
        
        return [
            'success' => true,
            'message' => 'Boleto gerado com sucesso',
            'transaction_id' => $transactionId,
            'barcode' => $barcode,
            'due_date' => $dueDate,
        ];
    }
    
    private function generateBarcode(): string
    {
        // Simulação de geração de código de barras
        return str_pad(rand(0, 99999999999999999999), 44, '0', STR_PAD_LEFT);
    }
}


