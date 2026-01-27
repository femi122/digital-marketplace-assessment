<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a specific Creator (so the reviewer can login)
        $creator = User::factory()->create([
            'name' => 'Test Creator',
            'email' => 'creator@example.com',
            'password' => bcrypt('password'), // Easy password
            'role' => 'creator',
        ]);

        // 2. Create a specific Customer
        $customer = User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        // 3. Create Sample Products for the Creator
        Product::factory()->count(5)->create([
            'user_id' => $creator->id,
            'price' => 5000, // $50.00
        ]);

        // 4. Create one product for the Customer (so they have something in their library)
        $boughtProduct = Product::factory()->create([
            'user_id' => $creator->id,
            'title' => 'Product Bought by Customer',
        ]);

        \App\Models\Purchase::create([
            'user_id' => $customer->id,
            'product_id' => $boughtProduct->id,
            'paid_price' => $boughtProduct->price,
        ]);
    }
}