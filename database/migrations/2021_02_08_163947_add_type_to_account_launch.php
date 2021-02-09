<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToAccountLaunch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_launch', function (Blueprint $table) {
            $table->string('type', 12)->nullable(false)->default(\App\Models\AccountLaunch::CREDIT_TYPE);
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
            $table->dropColumn('type');
        });
    }
}
