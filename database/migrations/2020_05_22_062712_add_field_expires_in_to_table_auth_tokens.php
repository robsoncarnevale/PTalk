<?php

/**
 * Migration Add field "expires_in" to table auth_tokens
 *
 * @author Davi Souto
 * @since 22/05/2020
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldExpiresInToTableAuthTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auth_tokens', function (Blueprint $table) {
            $table->dateTime('expires_in');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auth_tokens', function (Blueprint $table) {
            $table->dropColumn();
        });
    }
}
