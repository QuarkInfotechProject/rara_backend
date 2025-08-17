<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('product_included', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('amenity_id');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('amenity_id')->references('id')->on('amenities')->onDelete('cascade');

            $table->unique(['product_id', 'amenity_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_included');
    }
};
