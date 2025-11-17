<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category',
        'image_url',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Relacionamento: Produto tem muitos itens de pedido
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relacionamento: Produto tem muitos itens de carrinho
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Verifica se o produto está disponível em estoque
     */
    public function isAvailable(int $quantity = 1): bool
    {
        return $this->stock >= $quantity;
    }

    /**
     * Reduz o estoque do produto
     */
    public function reduceStock(int $quantity): bool
    {
        if (!$this->isAvailable($quantity)) {
            return false;
        }

        $this->stock -= $quantity;
        return $this->save();
    }
}


