<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'API RESTful - Projeto Final Laravel',
        'version' => '1.0.0',
        'patterns' => [
            'Factory Method' => 'Notificações',
            'Strategy' => 'Pagamentos',
            'CQRS' => 'Produtos (Commands e Queries)',
        ],
    ]);
});


