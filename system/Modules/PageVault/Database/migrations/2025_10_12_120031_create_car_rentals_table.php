<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_rentals', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('email')->nullable();
            $table->string('contact')->nullable();
            $table->integer('max_people')->nullable();
            $table->string('pickup_address')->nullable();
            $table->string('destination_address')->nullable();
            $table->dateTime('pickup_time')->nullable();
            $table->text('message')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_rentals');
    }
};
