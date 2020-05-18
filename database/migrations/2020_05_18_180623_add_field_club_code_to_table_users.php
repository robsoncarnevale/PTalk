<?php

/**
 * Migration Add field "club_code to" table users
 *
 * @author Davi Souto
 * @since 18/05/2020
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldClubCodeToTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("club_code", 20)
                ->after("id")
                ->nullable(false);

            // $table->foreign('club_code')
            //     ->references('code')
            //     ->on('clubs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("club_code");
        });
    }
}
