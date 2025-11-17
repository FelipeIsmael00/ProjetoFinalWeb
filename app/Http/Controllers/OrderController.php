<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Controller para gerenciar pedidos
 * Demonstra uso de CQRS através do OrderService
 */
class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {
    }

    /**
     * Lista pedidos usando CQRS Query
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['user_id', 'status', 'payment_method', 'limit', 'offset']);
        
        // Se não especificado, filtra pelo usuário autenticado
        if (!$filters['user_id'] && $request->user()) {
            $filters['user_id'] = $request->user()->id;
        }
        
        $orders = $this->orderService->getOrders($filters);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * Cria um novo pedido usando CQRS Command
     * 
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string|in:credit_card,pix,boleto',
            'payment_data' => 'sometimes|array',
        ]);

        // Usa o usuário autenticado ou um usuário padrão para testes
        $userId = $request->user()?->id ?? 1;

        $order = $this->orderService->createOrder([
            'user_id' => $userId,
            'items' => $validated['items'],
            'payment_method' => $validated['payment_method'],
            'payment_data' => $validated['payment_data'] ?? [],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pedido criado com sucesso',
            'data' => $order,
        ], 201);
    }

    /**
     * Cria um pedido a partir do carrinho
     * 
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function createFromCart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'payment_method' => 'required|string|in:credit_card,pix,boleto',
            'payment_data' => 'sometimes|array',
        ]);

        $cart = \App\Models\Cart::with('items.product')->findOrFail($validated['cart_id']);

        if ($cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Carrinho está vazio',
            ], 400);
        }

        // Converte itens do carrinho para formato de pedido
        $items = $cart->items->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
            ];
        })->toArray();

        $userId = $request->user()?->id ?? $cart->user_id ?? 1;

        $order = $this->orderService->createOrder([
            'user_id' => $userId,
            'items' => $items,
            'payment_method' => $validated['payment_method'],
            'payment_data' => $validated['payment_data'] ?? [],
        ]);

        // Limpa o carrinho após criar o pedido
        $cart->items()->delete();
        $cart->total_amount = 0;
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Pedido criado a partir do carrinho',
            'data' => $order,
        ], 201);
    }
}

