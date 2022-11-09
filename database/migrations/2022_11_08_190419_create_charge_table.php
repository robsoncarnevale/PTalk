<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->unsignedBigInteger('monthly_payment_id')->nullable(true);
            $table->boolean('done');
            $table->decimal('value', 12, 2)->default(0.00);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('monthly_payment_id')->references('id')->on('monthly_payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charges');
    }
}
