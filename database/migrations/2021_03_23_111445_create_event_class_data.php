<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventClassData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_class_data', function (Blueprint $table) {
            $table->id();
            $table->string("club_code", 20)->nullable(false);
            $table->unsignedBigInteger('event_id')->nullable(false);
            $table->unsignedBigInteger('member_class_id')->nullable(true);

            $table->dateTime('start_subscription_date')->default(null);
            $table->string('vehicle_value', 12, 2)->default(0.00);
            $table->string('participant_value', 12, 2)->default(0.00);
            $table->string('companion_value', 12, 2)->default(0.00);

            $table->timestamps();

            $table->foreign('club_code')->references('code')->on('clubs');
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('member_class_id')->references('id')->on('members_classes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_class_data');
    }
}
