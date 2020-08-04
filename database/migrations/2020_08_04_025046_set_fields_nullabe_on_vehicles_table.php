<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetFieldsNullabeOnVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->integer('year_manufacture')->nullable(true)->change();
            $table->integer('model_year')->nullable(true)->change();
            $table->string('carplate', 7)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->integer('year_manufacture')->nullable(false)->change();
            $table->integer('model_year')->nullable(false)->change();
            $table->string('carplate', 7)->nullable(false)->change();
        });
    }
}
