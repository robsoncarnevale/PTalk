<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNullableEventClassDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_class_data', function (Blueprint $table) {
            $table->dateTime('start_subscription_date')->default(null)->nullable(true)->change();
            $table->string('vehicle_value', 12, 2)->default(null)->nullable(true)->change();
            $table->string('participant_value', 12, 2)->default(null)->nullable(true)->change();
            $table->string('companion_value', 12, 2)->default(null)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_class_data', function (Blueprint $table) {
            $table->dateTime('start_subscription_date')->default(null)->change();
            $table->string('vehicle_value', 12, 2)->default(0.00)->change();
            $table->string('participant_value', 12, 2)->default(0.00)->change();
            $table->string('companion_value', 12, 2)->default(0.00)->change();
        });
    }
}
