<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveNotNullFromEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('description')->nullable(true)->change();
            $table->string('address')->nullable(true)->change();
           
            $table->dateTime('date')->nullable(true)->change();
            $table->string('start_time', 5)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('description')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
           
            $table->dateTime('date')->nullable(false)->change();
            $table->string('start_time', 5)->nullable(false)->change();
        });
    }
}
