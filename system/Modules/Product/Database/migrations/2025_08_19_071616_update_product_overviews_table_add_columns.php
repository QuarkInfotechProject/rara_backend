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
        Schema::table('product_overviews', function (Blueprint $table) {
            // Make existing columns nullable
            $table->text('name')->nullable()->change();
            $table->text('description')->nullable()->change();

            // Add new columns after `order`
            $table->string('duration', 255)->nullable()->after('order');
            $table->string('overview_location', 255)->nullable()->after('duration');
            $table->string('trip_grade', 255)->nullable()->after('overview_location');
            $table->integer('max_altitude')->nullable()->after('trip_grade');
            $table->integer('group_size')->nullable()->after('max_altitude');
            $table->text('activities')->nullable()->after('group_size');
            $table->string('best_time', 255)->nullable()->after('activities');
            $table->string('starts', 255)->nullable()->after('best_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_overviews', function (Blueprint $table) {
            // Drop the new columns
            $table->dropColumn([
                'duration',
                'overview_location',
                'trip_grade',
                'max_altitude',
                'group_size',
                'activities',
                'best_time',
                'starts',
            ]);
        });
    }
};
