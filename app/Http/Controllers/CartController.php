<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Controller para gerenciar carrinho de compras
 */
class CartController extends Controller
{
    public function __construct(
        private CartService $cartService
    ) {
    }

    /**
     * Obtém o carrinho do usuário/sessão
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $cart = $this->cartService->getOrCreateCart(
            $request->user()?->id,
            $request->header('X-Session-ID') ?? session()->getId()
        );

        $cart->load('items.product');

        return response()->json([
            'success' => true,
            'data' => $cart,
        ]);
    }

    /**
     * Adiciona um item ao carrinho
     * 
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function addItem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->cartService->getOrCreateCart(
            $request->user()?->id,
            $request->header('X-Session-ID') ?? session()->getId()
        );

        $cartItem = $this->cartService->addItem(
            $cart,
            $validated['product_id'],
            $validated['quantity']
        );

        return response()->json([
            'success' => true,
            'message' => 'Item adicionado ao carrinho',
            'data' => $cartItem,
        ], 201);
    }

    /**
     * Remove um item do carrinho
     * 
     * @param Request $request
     * @param int $cartItemId
     * @return JsonResponse
     */
    public function removeItem(Request $request, int $cartItemId): JsonResponse
    {
        $cart = $this->cartService->getOrCreateCart(
            $request->user()?->id,
            $request->header('X-Session-ID') ?? session()->getId()
        );

        $this->cartService->removeItem($cart, $cartItemId);

        return response()->json([
            'success' => true,
            'message' => 'Item removido do carrinho',
        ]);
    }

    /**
     * Atualiza a quantidade de um item
     * 
     * @param Request $request
     * @param int $cartItemId
     * @return JsonResponse
     * @throws ValidationException
     */
    public function updateItem(Request $request, int $cartItemId): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->cartService->getOrCreateCart(
            $request->user()?->id,
            $request->header('X-Session-ID') ?? session()->getId()
        );

        $cartItem = $this->cartService->updateItemQuantity(
            $cart,
            $cartItemId,
            $validated['quantity']
        );

        return response()->json([
            'success' => true,
            'message' => 'Item atualizado',
            'data' => $cartItem,
        ]);
    }

    /**
     * Limpa o carrinho
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function clear(Request $request): JsonResponse
    {
        $cart = $this->cartService->getOrCreateCart(
            $request->user()?->id,
            $request->header('X-Session-ID') ?? session()->getId()
        );

        $this->cartService->clearCart($cart);

        return response()->json([
            'success' => true,
            'message' => 'Carrinho limpo',
        ]);
    }
}

