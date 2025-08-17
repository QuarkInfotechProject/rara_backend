<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('how_to_get')->change();
            $table->text('impact')->change();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('how_to_get')->change();
            $table->string('impact')->change();
        });
    }
};
