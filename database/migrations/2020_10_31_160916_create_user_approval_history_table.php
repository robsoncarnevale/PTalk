<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserApprovalHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_approval_history', function (Blueprint $table) {
            $table->id();
            $table->string("club_code", 20)->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->string('approval_status')->nullable(false);
            $table->text('reason')->nullable(true);
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
        Schema::dropIfExists('user_approval_history');
    }
}
