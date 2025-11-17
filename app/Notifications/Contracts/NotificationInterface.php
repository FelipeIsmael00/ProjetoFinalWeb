<?php

namespace App\Notifications\Contracts;

/**
 * Interface comum para todas as notificações
 * Aplicando Dependency Inversion Principle (SOLID)
 */
interface NotificationInterface
{
    public function send(string $recipient, string $message): bool;
}


