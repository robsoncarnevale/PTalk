<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToAccountLaunch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_launch', function (Blueprint $table) {
            $table->index([ 'account_number', 'created_at' ]);
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
            $table->dropIndex([ 'account_number', 'created_at' ]);
        });
    }
}
