<?php

namespace App\Notifications;

use App\Notifications\Contracts\NotificationInterface;

/**
 * Implementação concreta de notificação push
 * Aplicando Single Responsibility Principle (SOLID)
 */
class PushNotification implements NotificationInterface
{
    public function send(string $recipient, string $message): bool
    {
        // Simulação de notificação push
        \Log::info("Push notification enviado para {$recipient}: {$message}");
        
        return true;
    }
}


