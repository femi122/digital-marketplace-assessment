<?php

namespace App\Actions;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CreateProductAction
{
    public function execute(User $creator, array $data, UploadedFile $file): Product
    {
        // Store the file securely
        // We use the 'local' disk but inside a private folder.
        // It returns the path like: "products/abcdef12345.pdf"
        $path = $file->store('products', 'local');

        // Create the Product Record
        // We use the relationship $creator->products()->create(...)
        // This automatically sets 'user_id' for us.
        $product = $creator->products()->create([
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price'],
            'file_path' => $path,
        ]);

        return $product;
    }
}