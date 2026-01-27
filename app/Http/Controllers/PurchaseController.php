<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPurchaseJob;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PurchaseController extends Controller
{
    public function store(Request $request, $productId): JsonResponse
    {
        $product = Product::findOrFail($productId);
        $user = $request->user();

        // 1. Validation: Creators can't buy their own stuff
        if ($product->user_id === $user->id) {
            return response()->json(['message' => 'You cannot buy your own product.'], 403);
        }

        // 2. Dispatch the Job to the Queue
        // This puts the task into Redis. It will run in the background.
        ProcessPurchaseJob::dispatch($user->id, $product->id);

        // 3. Return immediate response
        return response()->json([
            'message' => 'Purchase is being processed.',
            'status' => 'pending'
        ], 202); // 202 Accepted
    }
}