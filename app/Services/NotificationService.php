<?php

namespace App\Services;

use App\Notifications\Contracts\NotificationInterface;
use App\Notifications\Factory\NotificationFactory;

/**
 * Service para gerenciar notificações
 * Aplicando:
 * - Factory Method Pattern: Utiliza a factory para criar notificações
 * - Dependency Inversion Principle (SOLID): Depende de abstração (NotificationInterface)
 * - Single Responsibility Principle (SOLID): Responsabilidade única de gerenciar notificações
 */
class NotificationService
{
    /**
     * Envia uma notificação usando Factory Method
     * 
     * @param string $type Tipo de notificação (email, sms, push)
     * @param string $recipient Destinatário
     * @param string $message Mensagem
     * @return bool
     */
    public function sendNotification(string $type, string $recipient, string $message): bool
    {
        $notification = NotificationFactory::create($type);
        
        return $notification->send($recipient, $message);
    }

    /**
     * Envia notificação usando injeção de dependência
     * Demonstra Dependency Inversion Principle
     * 
     * @param NotificationInterface $notification
     * @param string $recipient
     * @param string $message
     * @return bool
     */
    public function send(NotificationInterface $notification, string $recipient, string $message): bool
    {
        return $notification->send($recipient, $message);
    }
}


