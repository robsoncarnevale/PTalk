<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPasswordTokensToUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('new_password_token')->nullable(true);
            $table->dateTime('new_password_token_duration')->nullable(true);
            $table->string('forget_password_token')->nullable(true);
            $table->dateTime('forget_password_token_duration')->nullable(true);
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
            $table->dropColumn('new_password_token');
            $table->dropColumn('new_password_token_duration');
            $table->dropColumn('forget_password_token');
            $table->dropColumn('forget_password_token_duration');
        });
    }
}
