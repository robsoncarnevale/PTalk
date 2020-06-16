<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            $table->string('carplate', 7)->nullable(false);
            $table->bigInteger('user_id')->unsigned()->nullable(false);
            // $table->bigInteger('car_brand_id')->unsigned()->nullable(false);
            $table->bigInteger('car_model_id')->unsigned()->nullable(false);
            $table->bigInteger('car_color_id')->unsigned()->nullable(true);
            $table->integer('year_manufacture');
            $table->integer('model_year');
            $table->string('document_renavam')->nullable(true);
            $table->string('chassis')->nullable(true);
            $table->string("club_code", 20);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('car_brand_id')->references('id')->on('car_brands');
            $table->foreign('car_model_id')->references('id')->on('car_models');
            $table->foreign('car_color_id')->references('id')->on('car_colors');

            $table->foreign('club_code')->references('code')->on('clubs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
