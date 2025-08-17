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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('title');
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('mediaName')->nullable();
            $table->string('publish_date')->nullable();
            $table->string('status')->default('draft');
            $table->string('read_time')->nullable();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('blog_category_id')->nullable();
            $table->unsignedBigInteger('admin_user_id')->nullable();
            $table->foreign('blog_category_id')->references('id')->on('blog_categories');
            $table->foreign('admin_user_id')->references('id')->on('admin_users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog');
    }
};
