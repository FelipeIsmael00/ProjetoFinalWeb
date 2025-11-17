<?php

namespace App\Notifications;

use App\Notifications\Contracts\NotificationInterface;

/**
 * Implementação concreta de notificação por email
 * Aplicando Single Responsibility Principle (SOLID)
 */
class EmailNotification implements NotificationInterface
{
    public function send(string $recipient, string $message): bool
    {
        // Simulação de envio de email
        \Log::info("Email enviado para {$recipient}: {$message}");
        
        return true;
    }
}


