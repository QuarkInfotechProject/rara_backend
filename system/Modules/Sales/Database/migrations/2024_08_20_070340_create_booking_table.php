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
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_type')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->integer('adult')->default(0);
            $table->integer('children')->default(0);
            $table->integer('infant')->default(0);
            $table->string('type')->default('inquiry');
            $table->string('fullname');
            $table->string('mobile_number')->nullable();
            $table->string('email');
            $table->string('country');
            $table->text('note')->nullable();
            $table->integer('has_responded')->default(0);
            $table->text('group_size')->nullable();
            $table->date('preferred_date')->nullable();
            $table->integer('duration')->nullable();
            $table->string('budget_range')->nullable();
            $table->text('accommodation_preference')->nullable();
            $table->text('transportation_preference')->nullable();
            $table->json('preference_activities')->nullable();
            $table->text('special_message')->nullable();
            $table->text('special_requirement')->nullable();
            $table->text('desired_destination')->nullable();
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
