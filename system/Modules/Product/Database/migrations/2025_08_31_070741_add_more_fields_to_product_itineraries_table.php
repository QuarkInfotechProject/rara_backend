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
        Schema::table('product_itineraries', function (Blueprint $table) {
            $table->string('duration', 255)->nullable()->after('order');
            $table->string('location', 255)->nullable()->after('duration');
            $table->string('max_altitude', 255)->nullable()->after('location');
            $table->text('activities')->nullable()->after('max_altitude');
            $table->string('accommodation', 255)->nullable()->after('activities');
            $table->string('meal', 255)->nullable()->after('accommodation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_itineraries', function (Blueprint $table) {
            $table->dropColumn([
                'duration',
                'location',
                'max_altitude',
                'activities',
                'accommodation',
                'meal',
            ]);
        });

    }
};
