<?php

namespace App\Notifications\Factory;

use App\Notifications\Contracts\NotificationInterface;
use App\Notifications\EmailNotification;
use App\Notifications\PushNotification;
use App\Notifications\SmsNotification;
use InvalidArgumentException;

/**
 * Factory Method Pattern
 * 
 * Responsável por criar instâncias de notificações baseado no tipo solicitado.
 * Aplicando:
 * - Factory Method Pattern: Encapsula a lógica de criação de objetos
 * - Open/Closed Principle (SOLID): Aberto para extensão, fechado para modificação
 * - Single Responsibility Principle (SOLID): Responsabilidade única de criar notificações
 */
class NotificationFactory
{
    /**
     * Cria uma instância de notificação baseada no tipo
     * 
     * @param string $type Tipo de notificação (email, sms, push)
     * @return NotificationInterface
     * @throws InvalidArgumentException
     */
    public static function create(string $type): NotificationInterface
    {
        return match (strtolower($type)) {
            'email' => new EmailNotification(),
            'sms' => new SmsNotification(),
            'push' => new PushNotification(),
            default => throw new InvalidArgumentException("Tipo de notificação '{$type}' não suportado"),
        };
    }
}


