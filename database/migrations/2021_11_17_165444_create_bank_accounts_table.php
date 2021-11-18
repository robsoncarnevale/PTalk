<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('bank_account');

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique()->default(Uuid::uuid4());
            $table->string('account_number')->unique();
            $table->decimal('balance', 12, 2)->default(0.00);
            $table->unsignedBigInteger('bank_account_type_id');
            $table->unsignedBigInteger('status_id');
            $table->timestamps();

            $table->foreign('bank_account_type_id')->references('id')->on('bank_account_types');
            $table->foreign('status_id')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_accounts');
    }
}
