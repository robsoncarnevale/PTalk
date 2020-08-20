<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string("club_code", 20)->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('description')->nullable(false);
            $table->string('address')->nullable(false);
            $table->string('meeting_point')->nullable(true);
           
            $table->dateTime('date')->nullable(false);
            $table->string('start_time', 4)->nullable(false);
            $table->string('end_time', 4)->nullable(true);
           
           
            $table->integer('max_vehicles')->nullable(true);
            $table->integer('max_participants')->nullable(true);
            $table->integer('max_companions')->nullable(true);
           
            $table->unsignedBigInteger('created_by')->nullable(false);
            $table->string('status')->nullable(false)->default(\App\Models\Event::ACTIVE_STATUS);
            $table->timestamps();

            $table->foreign('club_code')->references('code')->on('clubs');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
