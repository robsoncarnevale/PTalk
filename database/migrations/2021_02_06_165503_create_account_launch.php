<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Ramsey\Uuid\Uuid;

class CreateAccountLaunch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_launch', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->default(Uuid::uuid4())->nullable(false);
            $table->string("club_code", 20)->nullable(false);
            $table->string('account_number')->nullable(false);
            $table->unsignedBigInteger('created_by')->nullable(false);
            $table->decimal('amount', 12, 2)->nullable(false);

            $table->timestamps();

            $table->foreign('club_code')->references('code')->on('clubs');
            $table->foreign('account_number')->references('account_number')->on('bank_account');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_launch');
    }
}
