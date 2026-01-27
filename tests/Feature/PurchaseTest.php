<?php

use App\Models\Product;
use App\Models\User;
use App\Jobs\ProcessPurchaseJob;
use Illuminate\Support\Facades\Queue;

test('customer can buy a product and job is dispatched', function () {
    Queue::fake(); // Don't actually run Redis, just pretend

    $creator = User::factory()->create(['role' => 'creator']);
    $customer = User::factory()->create(['role' => 'customer']);
    $product = Product::factory()->create(['user_id' => $creator->id, 'price' => 1000]);

    $response = $this->actingAs($customer)
                     ->postJson("/api/products/{$product->id}/purchase");

    $response->assertStatus(202); 
    
    Queue::assertPushed(ProcessPurchaseJob::class);
});

test('creator cannot buy their own product', function () {
    $creator = User::factory()->create(['role' => 'creator']);
    $product = Product::factory()->create(['user_id' => $creator->id]);

    $response = $this->actingAs($creator)
                     ->postJson("/api/products/{$product->id}/purchase");

    $response->assertStatus(403);
});