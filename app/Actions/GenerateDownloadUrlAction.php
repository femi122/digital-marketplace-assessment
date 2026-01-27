<?php

namespace App\Actions;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class GenerateDownloadUrlAction
{
    public function execute(User $user, Product $product): string
    {
        // 1. Logic Check: Can they download this?
        // They must be the creator OR have purchased it.
        $hasPurchased = $user->purchases()->where('product_id', $product->id)->exists();
        $isCreator = $product->user_id === $user->id;

        if (! $hasPurchased && ! $isCreator) {
            abort(403, 'You must purchase this product to download it.');
        }

        // 2. Generate Signed URL
        // If using 'local' driver (which we are), we use a temporary signed route.
        // If using S3, we would use Storage::temporaryUrl().
        
        // Since we stored it in 'products/filename.pdf', we need to serve it safely.
        // We will generate a URL to a special controller route that handles the serving.
        return URL::temporarySignedRoute(
            'file.download', // We need to name this route
            now()->addMinutes(60), // Valid for 1 hour
            ['path' => $product->file_path]
        );
    }
}