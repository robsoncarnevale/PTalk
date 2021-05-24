<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToAccountLaunchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_launch', function (Blueprint $table) {
            $table->string('description', 20)->nullable(false)->default(\App\Models\AccountLaunch::DEBIT_DESCRIPTION);
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
            $table->dropColumn('description');
        });
    }
}
