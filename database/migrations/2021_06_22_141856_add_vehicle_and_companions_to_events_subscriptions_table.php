<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVehicleAndCompanionsToEventsSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events_subscriptions', function (Blueprint $table) {
            $table->boolean('vehicle')->nullable(false)->default(false);
            $table->integer('companions')->nullable(false)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events_subscriptions', function (Blueprint $table) {
            $table->dropColumn('vehicle');
            $table->dropColumn('companions');
        });
    }
}
