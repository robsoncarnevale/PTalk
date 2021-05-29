<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events_address', function (Blueprint $table) {
            $table->id();
            $table->string("club_code", 20)->nullable(false);
            $table->unsignedBigInteger('event_id')->nullable(false);
            $table->string('zip_code', 10)->nullable(false);
            $table->string('state', 2)->nullable(false);
            $table->string('city', 30)->nullable(false);
            $table->string('neighborhood', 30)->nullable(false);
            $table->string('street_address', 50)->nullable(false);
            $table->string('number', 5)->nullable(true);
            $table->string('complement', 20)->nullable(true);

            $table->timestamps();

            $table->foreign('club_code')->references('code')->on('clubs');
            $table->foreign('event_id')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events_address');
    }
}
