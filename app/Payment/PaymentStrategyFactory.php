<?php

namespace App\Payment;

use App\Payment\Contracts\PaymentStrategyInterface;
use App\Payment\Strategies\BoletoPayment;
use App\Payment\Strategies\CreditCardPayment;
use App\Payment\Strategies\PixPayment;
use InvalidArgumentException;

/**
 * Factory para criar estratégias de pagamento
 * Aplicando Factory Method Pattern em conjunto com Strategy
 */
class PaymentStrategyFactory
{
    public static function create(string $paymentMethod): PaymentStrategyInterface
    {
        return match (strtolower($paymentMethod)) {
            'credit_card', 'cartao', 'card' => new CreditCardPayment(),
            'pix' => new PixPayment(),
            'boleto' => new BoletoPayment(),
            default => throw new InvalidArgumentException("Método de pagamento '{$paymentMethod}' não suportado"),
        };
    }
}


