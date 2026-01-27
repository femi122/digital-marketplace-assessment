<?php

namespace App\Http\Controllers;

use App\Actions\CreateProductAction;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * List all products (Public).
     * Supports filtering and sorting.
     */
    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        // 1. Start the query
        $query = \App\Models\Product::query();

        // 2. Search by title (optional)
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        // 3. Sort by price (optional)
        // Usage: ?sort=price_asc or ?sort=price_desc
        if ($request->has('sort')) {
            $direction = $request->input('sort') === 'price_desc' ? 'desc' : 'asc';
            $query->orderBy('price', $direction);
        } else {
            // Default sort: Newest first
            $query->latest();
        }

        // 4. Pagination (Requirement: "Pagination where applicable")
        // We return 10 products per page.
        return ProductResource::collection($query->paginate(10));
    }

    public function store(StoreProductRequest $request, CreateProductAction $action): JsonResponse
    {
        // 1. Check if user is a Creator
        if (! $request->user()->isCreator()) {
            abort(403, 'Only creators can sell products.');
        }

        // 2. Execute Action
        // We separate the file from the rest of the data
        $product = $action->execute(
            $request->user(),
            $request->safe()->except(['file']),
            $request->file('file')
        );

        return response()->json([
            'message' => 'Product created successfully',
            'data' => new ProductResource($product),
        ], 201);
    }
}