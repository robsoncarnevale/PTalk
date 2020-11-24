<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_address', function (Blueprint $table) {
            $table->id();
            $table->string("club_code", 20)->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->string('address_type')->nullable(false)->default(\App\Models\UserAddress::RESIDENTIAL_ADDRESS_TYPE);
            $table->string('zip_code', 10)->nullable(false);
            $table->string('state', 2)->nullable(false);
            $table->string('city', 30)->nullable(false);
            $table->string('neighborhood', 30)->nullable(false);
            $table->string('street_address', 50)->nullable(false);
            $table->string('number', 5)->nullable(true);
            $table->string('complement', 20)->nullable(true);

            $table->timestamps();

            $table->foreign('club_code')->references('code')->on('clubs');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_address');
    }
}
