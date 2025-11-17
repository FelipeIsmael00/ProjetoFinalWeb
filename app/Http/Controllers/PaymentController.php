<?php

namespace App\Http\Controllers;

use App\Payment\PaymentProcessor;
use App\Payment\PaymentStrategyFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Controller para processar pagamentos
 * Demonstra uso do padrão Strategy
 */
class PaymentController extends Controller
{
    /**
     * Processa um pagamento usando Strategy Pattern
     * 
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function process(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:credit_card,pix,boleto',
            'payment_data' => 'required|array',
        ]);

        // Cria a estratégia de pagamento usando Factory
        $strategy = PaymentStrategyFactory::create($validated['payment_method']);
        
        // Cria o processador com a estratégia (Injeção de Dependência)
        $processor = new PaymentProcessor($strategy);
        
        // Processa o pagamento
        $result = $processor->process($validated['amount'], $validated['payment_data']);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'transaction_id' => $result['transaction_id'],
                'data' => $result,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 400);
    }
}


