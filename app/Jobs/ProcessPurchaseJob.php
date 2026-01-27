<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPurchaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     * We pass only the IDs to keep the job lightweight.
     */
    public function __construct(
        public int $userId,
        public int $productId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Load the models
        $user = User::find($this->userId);
        $product = Product::find($this->productId);

        if (!$user || !$product) {
            Log::error("Purchase failed: User or Product not found.");
            return;
        }

        // 2. Check if already purchased (Idempotency bonus point)
        $exists = Purchase::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            Log::info("User {$user->id} already owns product {$product->id}");
            return;
        }

        // 3. Record the Purchase
        Purchase::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'paid_price' => $product->price, // Record price at time of purchase
        ]);

        Log::info("Purchase processed for User {$user->id} - Product {$product->id}");
        
        // OPTIONAL: Here is where you would send an email receipt.
    }
}