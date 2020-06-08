<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldClubCodeToPrivilegesGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('privileges_groups', function (Blueprint $table) {
            $table->string("club_code", 20)
                ->after("id")
                ->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('privileges_groups', function (Blueprint $table) {
            $table->dropColumn("club_code");
        });
    }
}
