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
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->string('short_code', 255);
            $table->string('type', 20);
            $table->string('tagline', 255)->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('original_price_usd')->nullable();
            $table->string('discounted_price_usd')->nullable();
            $table->string('display_order')->nullable();
            $table->string('youtube_link')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('location')->nullable();
            $table->string('average_rating')->nullable();
              $table->string('total_rating')->nullable();
            $table->string('total_comment')->nullable();
            $table->string('status');
            $table->string('cancellation_policy')->nullable();
            $table->string('how_to_get')->nullable();
            $table->string('cornerstone')->default(0);
            $table->string('region')->nullable();
            $table->boolean('is_occupied')->default(0);
            $table->string('max_occupant')->default(0);
            $table->string('display_homepage')->default(0);
            $table->string('impact')->nullable();
            $table->timestamps();
            $table->index('latitude');
            $table->index('longitude');
            $table->foreign('manager_id')->references('id')->on('managers')->onDelete('cascade');
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
