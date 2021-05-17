<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events_history', function (Blueprint $table) {
            $table->id();
            $table->string("club_code", 20)->nullable(false);
            $table->unsignedBigInteger('event_id')->nullable(false);
            $table->unsignedBigInteger('effected_by')->nullable(true);
            $table->text('resume')->nullable(true);
            $table->string('status', 20)->nullable(false);

            $table->timestamps();

            $table->foreign('club_code')->references('code')->on('clubs');
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('effected_by')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events_history');
    }
}
