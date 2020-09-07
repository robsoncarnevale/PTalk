<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string("club_code", 20)->nullable(false);
            $table->unsignedBigInteger('event_id')->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->string('status')->nullable(false)->default(\App\Models\EventSubscription::ACTIVE_STATUS);
            $table->timestamps();

            $table->foreign('club_code')->references('code')->on('clubs');
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events_subscriptions');
    }
}
