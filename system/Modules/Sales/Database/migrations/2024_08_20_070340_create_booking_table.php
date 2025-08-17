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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('new-inquiry');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('product_name');
            $table->string('product_type');
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->integer('adult');
            $table->integer('children')->default(0);
            $table->integer('infant')->default(0);
            $table->string('type')->default('inquiry');
            $table->string('fullname');
            $table->string('mobile_number')->nullable();
            $table->string('email');
            $table->string('country');
            $table->text('note')->nullable();
            $table->integer('has_responded')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
