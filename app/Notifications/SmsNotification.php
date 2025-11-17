<?php

namespace App\Notifications;

use App\Notifications\Contracts\NotificationInterface;

/**
 * Implementação concreta de notificação por SMS
 * Aplicando Single Responsibility Principle (SOLID)
 */
class SmsNotification implements NotificationInterface
{
    public function send(string $recipient, string $message): bool
    {
        // Simulação de envio de SMS
        \Log::info("SMS enviado para {$recipient}: {$message}");
        
        return true;
    }
}


