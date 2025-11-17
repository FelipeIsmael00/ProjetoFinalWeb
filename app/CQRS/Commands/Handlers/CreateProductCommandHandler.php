<?php

namespace App\CQRS\Commands\Handlers;

use App\CQRS\Commands\CreateProductCommand;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Handler para o comando de criação de produto
 * Aplicando:
 * - CQRS Pattern: Handler responsável por processar comandos de escrita
 * - Single Responsibility Principle (SOLID): Responsabilidade única de processar criação de produtos
 * - Dependency Inversion Principle (SOLID): Depende de abstração (Command), não de implementação concreta
 */
class CreateProductCommandHandler
{
    /**
     * Processa o comando de criação de produto
     * 
     * @param CreateProductCommand $command
     * @return Product
     */
    public function handle(CreateProductCommand $command): Product
    {
        return DB::transaction(function () use ($command) {
            $product = Product::create([
                'name' => $command->getName(),
                'description' => $command->getDescription(),
                'price' => $command->getPrice(),
                'stock' => $command->getStock(),
                'category' => $command->getCategory(),
            ]);

            Log::info("Produto criado via CQRS Command", [
                'product_id' => $product->id,
                'name' => $product->name,
            ]);

            return $product;
        });
    }
}


