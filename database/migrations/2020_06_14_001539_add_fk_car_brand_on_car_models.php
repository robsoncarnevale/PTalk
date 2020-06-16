<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkCarBrandOnCarModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_models', function (Blueprint $table) {
            $table->bigInteger('car_brand_id')->unsigned()->nullable(false);

            $table->foreign('car_brand_id')
                ->references('id')
                ->on('car_brands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_models', function (Blueprint $table) {
            $table->dropForeign('car_brand_id');
            $table->dropColumn('car_brand_id');
        });
    }
}
