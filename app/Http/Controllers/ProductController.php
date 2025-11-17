<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Controller para gerenciar produtos
 * Demonstra uso de CQRS através do ProductService
 */
class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {
    }

    /**
     * Lista produtos usando CQRS Query
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['category', 'min_price', 'max_price', 'min_stock', 'limit', 'offset']);
        
        $products = $this->productService->getProducts($filters);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Cria um novo produto usando CQRS Command
     * 
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
        ]);

        $product = $this->productService->createProduct($validated);

        return response()->json([
            'success' => true,
            'message' => 'Produto criado com sucesso',
            'data' => $product,
        ], 201);
    }
}


