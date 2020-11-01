<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlacklistHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blacklist_history', function (Blueprint $table) {
            $table->id();
            $table->string("club_code", 20)->nullable(false);
            $table->unsignedBigInteger('blacklist_id')->nullable(false);
            $table->string('status')->nullable(false);
            $table->text('description')->nullable(true);
            $table->unsignedBigInteger('changed_by')->nullable(false);
            $table->timestamps();

            $table->foreign('club_code')->references('code')->on('clubs');
            $table->foreign('blacklist_id')->references('id')->on('blacklist');
            $table->foreign('changed_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blacklist_history');
    }
}
