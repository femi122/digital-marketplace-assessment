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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // Foreign Key: Links to the 'users' table.
            // If the user (creator) is deleted, delete their products too (cascade).
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('title');
            $table->text('description');
            
            // Money: Store as cents (e.g., 5000 = $50.00)
            // Unsigned means it cannot be negative.
            $table->unsignedInteger('price');
            
            // Path to the file (e.g., 'private/books/ebook.pdf')
            // nullable() because we might create the product draft before uploading the file
            $table->string('file_path')->nullable();
            
            $table->timestamps(); // Creates created_at and updated_at
            $table->softDeletes(); // Requirement: "Soft-delete products"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
