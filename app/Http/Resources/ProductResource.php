<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            // Convert cents back to dollars for display, to make sure it account for decimalls
            'price_formatted' => number_format($this->price / 100, 2),
            'creator_id' => $this->user_id,
            'created_at' => $this->created_at->toIso8601String(),
            // NEVER return the 'file_path' here. That is a secret!
        ];
    }
}
