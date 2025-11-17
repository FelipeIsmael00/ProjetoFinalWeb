<?php

namespace App\Providers;

use App\CQRS\Commands\Handlers\CreateOrderCommandHandler;
use App\CQRS\Commands\Handlers\CreateProductCommandHandler;
use App\CQRS\Queries\Handlers\GetOrdersQueryHandler;
use App\CQRS\Queries\Handlers\GetProductsQueryHandler;
use App\Payment\PaymentProcessor;
use App\Services\CartService;
use App\Services\NotificationService;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * 
     * Demonstra Injeção de Dependência e Dependency Inversion Principle
     */
    public function register(): void
    {
        // Registra os handlers CQRS de produtos
        $this->app->singleton(CreateProductCommandHandler::class);
        $this->app->singleton(GetProductsQueryHandler::class);

        // Registra os handlers CQRS de pedidos
        $this->app->singleton(CreateOrderCommandHandler::class);
        $this->app->singleton(GetOrdersQueryHandler::class);

        // Registra ProductService com injeção de dependência
        $this->app->singleton(ProductService::class, function ($app) {
            return new ProductService(
                $app->make(CreateProductCommandHandler::class),
                $app->make(GetProductsQueryHandler::class)
            );
        });

        // Registra OrderService com injeção de dependência
        $this->app->singleton(OrderService::class, function ($app) {
            return new OrderService(
                $app->make(CreateOrderCommandHandler::class),
                $app->make(GetOrdersQueryHandler::class),
                $app->make(PaymentProcessor::class),
                $app->make(NotificationService::class)
            );
        });

        // Registra CartService
        $this->app->singleton(CartService::class);

        // Registra NotificationService
        $this->app->singleton(NotificationService::class);

        // Registra PaymentProcessor
        $this->app->singleton(PaymentProcessor::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}


