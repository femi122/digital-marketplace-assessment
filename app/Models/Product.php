<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory, SoftDeletes; // Enables 'Undo' functionality for deletions

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'file_path'
    ];

    /**
     * 'price' => 'integer' ensures I always get a number, not a string
     */
    protected $casts = [
        'price' => 'integer',
    ];
    /**
     * A product belongs to creator user.
     */
    public function creator(): BelongsTo
    {
        // We explicitly say 'user_id' is the foreign key
        return $this->belongsTo(User::class, 'user_id');
    }
}