<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aqui estão definidas as rotas da API RESTful que demonstram
| a aplicação dos padrões de projeto implementados.
|
*/

// Rotas de Produtos (CQRS)
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']); // Query
    Route::post('/', [ProductController::class, 'store']); // Command
});

// Rotas de Pagamento (Strategy Pattern)
Route::prefix('payments')->group(function () {
    Route::post('/process', [PaymentController::class, 'process']);
});

// Rotas de Notificação (Factory Method Pattern)
Route::prefix('notifications')->group(function () {
    Route::post('/send', [NotificationController::class, 'send']);
});

// Rotas de Carrinho de Compras
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'show']);
    Route::post('/items', [CartController::class, 'addItem']);
    Route::put('/items/{cartItemId}', [CartController::class, 'updateItem']);
    Route::delete('/items/{cartItemId}', [CartController::class, 'removeItem']);
    Route::delete('/', [CartController::class, 'clear']);
});

// Rotas de Pedidos (CQRS)
Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']); // Query
    Route::post('/', [OrderController::class, 'store']); // Command
    Route::post('/from-cart', [OrderController::class, 'createFromCart']); // Command a partir do carrinho
});


