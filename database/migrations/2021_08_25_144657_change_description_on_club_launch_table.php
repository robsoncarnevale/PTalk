<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDescriptionOnClubLaunchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('club_launch', function (Blueprint $table) {
            $table->string('description', 30)->nullable(false)->default(\App\Models\ClubLaunch::DEBIT_DESCRIPTION)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('club_launch', function (Blueprint $table) {
            $table->string('description', 20)->nullable(false)->default(\App\Models\ClubLaunch::DEBIT_DESCRIPTION)->change();
        });
    }
}
