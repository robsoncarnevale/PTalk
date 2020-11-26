<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Ramsey\Uuid\Uuid;

class CreateBankAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_account', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->default(Uuid::uuid4())->nullable(false);
            $table->string("club_code", 20)->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->string('account_number')->nullable(false);
            $table->string('account_holder')->nullable(false);
            $table->decimal('balance', 12, 2)->nullable(false)->default(0.00);
            $table->string('status')->nullable(false)->default(\App\Models\BankAccount::ACTIVE_STATUS);
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
        Schema::dropIfExists('bank_account');
    }
}
