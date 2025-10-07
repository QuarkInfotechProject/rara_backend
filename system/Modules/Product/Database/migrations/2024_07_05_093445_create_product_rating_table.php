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
        Schema::create('product_rating_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('email')->nullable();
            $table->string('full_name')->nullable();
            $table->decimal('cleanliness', 3, 2)->nullable();
            $table->decimal('hospitality', 3, 2)->nullable();
            $table->decimal('value_for_money', 3, 2)->nullable();
            $table->decimal('communication', 3, 2)->nullable();
            $table->decimal('overall_rating', 3, 2)->nullable();
            $table->text('public_review')->nullable();
            $table->text('private_review')->nullable();
            $table->text('reply_to_public_review')->nullable();
            $table->boolean('approved')->default(0); // Default to approved
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade'); // Delete review if product is deleted

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->nullOnDelete(); // Set user_id to NULL if user is deleted

            // Index for better query performance
            $table->index(['product_id', 'approved']);
            $table->index(['user_id', 'product_id']);
            $table->index(['email', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_rating_reviews');
    }
};
