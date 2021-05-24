<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModeToAccountLaunchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_launch', function (Blueprint $table) {
            $table->string('mode', 20)->nullable(false)->default(\App\Models\AccountLaunch::MANUAL_MODE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_launch', function (Blueprint $table) {
            $table->dropColumn('mode');
        });
    }
}
