<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // We will handle the "Is Creator" check in a Policy later.
        // For now, allow logged-in users.
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:1'], // Price in cents!
            // 'file' must be a real file, max 50MB (51200 KB)
            'file' => ['required', 'file', 'max:51200'], 
        ];
    }
}