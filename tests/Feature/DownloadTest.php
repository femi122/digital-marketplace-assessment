<?php

use App\Models\Product;
use App\Models\User;
use App\Models\Purchase;

test('customer cannot download product without purchasing', function () {
    $creator = User::factory()->create(['role' => 'creator']);
    $customer = User::factory()->create(['role' => 'customer']);
    $product = Product::factory()->create(['user_id' => $creator->id]);

    $response = $this->actingAs($customer)
                     ->getJson("/api/products/{$product->id}/download");

    $response->assertStatus(403);
});

test('customer receives signed url after purchase', function () {
    $creator = User::factory()->create(['role' => 'creator']);
    $customer = User::factory()->create(['role' => 'customer']);
    $product = Product::factory()->create(['user_id' => $creator->id]);

    // Manually create the purchase record
    Purchase::create([
        'user_id' => $customer->id,
        'product_id' => $product->id,
        'paid_price' => $product->price
    ]);

    $response = $this->actingAs($customer)
                     ->getJson("/api/products/{$product->id}/download");

    $response->assertStatus(200)
             ->assertJsonStructure(['download_url']);
});