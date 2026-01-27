<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            
            // Who bought it?
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // What did they buy?
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            
            // Historical Price: The product price might change later, 
            // so we record EXACTLY what they paid at that moment.
            $table->unsignedInteger('paid_price');
            
            $table->timestamps();
            
            // Indexing: Makes searching faster. 
            // We will often look up "What did this user buy?"
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
