<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_account_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->unsignedBigInteger('brand_id');
            $table->integer('installments');
            $table->string('card_name');
            $table->string('card_number');
            $table->string('order_number');
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->string('authorization')->nullable();
            $table->string('nsu')->nullable();
            $table->string('response_code')->nullable();
            $table->string('payment_token')->nullable();
            $table->unsignedBigInteger('transaction_type_id');
            $table->unsignedBigInteger('transaction_status_id');
            $table->timestamps();

            $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types');
            $table->foreign('transaction_status_id')->references('id')->on('transaction_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
