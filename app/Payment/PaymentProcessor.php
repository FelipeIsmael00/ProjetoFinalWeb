<?php

namespace App\Payment;

use App\Payment\Contracts\PaymentStrategyInterface;

/**
 * Contexto do padrão Strategy
 * Permite trocar dinamicamente a estratégia de pagamento
 * Aplicando:
 * - Strategy Pattern: Permite selecionar algoritmo em tempo de execução
 * - Dependency Inversion Principle (SOLID): Depende de abstração, não de implementação
 * - Open/Closed Principle (SOLID): Aberto para extensão (novas estratégias), fechado para modificação
 */
class PaymentProcessor
{
    private ?PaymentStrategyInterface $strategy = null;

    /**
     * Injeção de Dependência via construtor (opcional)
     * Aplicando Dependency Inversion Principle (SOLID)
     */
    public function __construct(?PaymentStrategyInterface $strategy = null)
    {
        if ($strategy) {
            $this->strategy = $strategy;
        }
    }

    /**
     * Permite alterar a estratégia em tempo de execução
     * 
     * @param PaymentStrategyInterface $strategy
     * @return void
     */
    public function setStrategy(PaymentStrategyInterface $strategy): void
    {
        $this->strategy = $strategy;
    }

    /**
     * Processa o pagamento usando a estratégia atual
     * 
     * @param float $amount
     * @param array $data
     * @return array
     * @throws \RuntimeException
     */
    public function process(float $amount, array $data = []): array
    {
        if (!$this->strategy) {
            throw new \RuntimeException('Estratégia de pagamento não definida. Use setStrategy() primeiro.');
        }
        
        return $this->strategy->processPayment($amount, $data);
    }
}


