<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'paid_price'
    ];

    /**
     * A purchase belongs to 1 buyer.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A purchase is for 1 product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}