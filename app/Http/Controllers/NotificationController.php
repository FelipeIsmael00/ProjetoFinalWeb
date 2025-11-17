<?php

namespace App\Http\Controllers;

use App\Notifications\Factory\NotificationFactory;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Controller para gerenciar notificações
 * Demonstra uso do Factory Method Pattern
 */
class NotificationController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    ) {
    }

    /**
     * Envia uma notificação usando Factory Method
     * 
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|in:email,sms,push',
            'recipient' => 'required|string',
            'message' => 'required|string',
        ]);

        $sent = $this->notificationService->sendNotification(
            $validated['type'],
            $validated['recipient'],
            $validated['message']
        );

        return response()->json([
            'success' => $sent,
            'message' => $sent ? 'Notificação enviada com sucesso' : 'Falha ao enviar notificação',
        ]);
    }
}


