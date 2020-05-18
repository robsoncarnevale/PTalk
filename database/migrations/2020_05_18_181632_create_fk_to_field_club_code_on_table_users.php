<?php

/**
 * Migration Create foreign key for field "club_code" on table users
 *
 * @author Davi Souto
 * @since 18/05/2020
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFkToFieldClubCodeOnTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('club_code')
                ->references('code')
                ->on('clubs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign("club_code");
    }
}
