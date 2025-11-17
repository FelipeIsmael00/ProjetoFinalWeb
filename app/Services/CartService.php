<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

/**
 * Service para gerenciar carrinho de compras
 * Aplicando:
 * - Service Layer Pattern: Camada de serviço que orquestra operações do carrinho
 * - Single Responsibility Principle (SOLID): Responsabilidade única de gerenciar carrinho
 */
class CartService
{
    /**
     * Obtém ou cria um carrinho para o usuário/sessão
     * 
     * @param int|null $userId
     * @param string|null $sessionId
     * @return Cart
     */
    public function getOrCreateCart(?int $userId = null, ?string $sessionId = null): Cart
    {
        if ($userId) {
            return Cart::firstOrCreate(
                ['user_id' => $userId],
                ['total_amount' => 0]
            );
        }

        if ($sessionId) {
            return Cart::firstOrCreate(
                ['session_id' => $sessionId],
                ['total_amount' => 0]
            );
        }

        // Se não tem userId nem sessionId, cria uma nova sessão
        $sessionId = session()->getId();
        return Cart::create([
            'session_id' => $sessionId,
            'total_amount' => 0,
        ]);
    }

    /**
     * Adiciona um produto ao carrinho
     * 
     * @param Cart $cart
     * @param int $productId
     * @param int $quantity
     * @return CartItem
     */
    public function addItem(Cart $cart, int $productId, int $quantity): CartItem
    {
        return DB::transaction(function () use ($cart, $productId, $quantity) {
            $product = Product::findOrFail($productId);

            if (!$product->isAvailable($quantity)) {
                throw new \Exception("Produto {$product->name} não possui estoque suficiente");
            }

            // Verifica se o item já existe no carrinho
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                // Atualiza a quantidade
                $cartItem->quantity += $quantity;
                $cartItem->subtotal = $cartItem->quantity * $product->price;
                $cartItem->save();
            } else {
                // Cria novo item
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'subtotal' => $product->price * $quantity,
                ]);
            }

            // Atualiza o total do carrinho
            $cart->total_amount = $cart->calculateTotal();
            $cart->save();

            return $cartItem->load('product');
        });
    }

    /**
     * Remove um item do carrinho
     * 
     * @param Cart $cart
     * @param int $cartItemId
     * @return bool
     */
    public function removeItem(Cart $cart, int $cartItemId): bool
    {
        return DB::transaction(function () use ($cart, $cartItemId) {
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('id', $cartItemId)
                ->firstOrFail();

            $cartItem->delete();

            // Atualiza o total do carrinho
            $cart->total_amount = $cart->calculateTotal();
            $cart->save();

            return true;
        });
    }

    /**
     * Atualiza a quantidade de um item
     * 
     * @param Cart $cart
     * @param int $cartItemId
     * @param int $quantity
     * @return CartItem
     */
    public function updateItemQuantity(Cart $cart, int $cartItemId, int $quantity): CartItem
    {
        return DB::transaction(function () use ($cart, $cartItemId, $quantity) {
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('id', $cartItemId)
                ->firstOrFail();

            $product = $cartItem->product;

            if (!$product->isAvailable($quantity)) {
                throw new \Exception("Produto {$product->name} não possui estoque suficiente");
            }

            $cartItem->quantity = $quantity;
            $cartItem->subtotal = $quantity * $cartItem->unit_price;
            $cartItem->save();

            // Atualiza o total do carrinho
            $cart->total_amount = $cart->calculateTotal();
            $cart->save();

            return $cartItem->load('product');
        });
    }

    /**
     * Limpa o carrinho
     * 
     * @param Cart $cart
     * @return bool
     */
    public function clearCart(Cart $cart): bool
    {
        return DB::transaction(function () use ($cart) {
            $cart->items()->delete();
            $cart->total_amount = 0;
            $cart->save();

            return true;
        });
    }
}

