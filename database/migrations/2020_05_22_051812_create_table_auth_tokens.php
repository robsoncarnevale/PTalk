<?php

/**
 * Migration Create table "auth_tokens"
 *
 * @author Davi Souto
 * @since 22/05/2020
 */


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAuthTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_tokens', function (Blueprint $table) {
            $table->id();

            $table->string('club_code', 20)->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->string('email')->nullable(false);
            $table->string('token')->string('string', 60);

            $table->timestamps();


            $table->foreign('club_code')->references('code')->on('clubs');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('email')->references('email')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_tokens');
    }
}
