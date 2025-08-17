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
        Schema::create('page_vaults', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index()->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('header')->nullable();
            $table->longText('content1')->nullable();
            $table->longText('content2')->nullable();
            $table->longText('content3')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_vault');
    }
};
