<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClubLaunchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('club_launch', function (Blueprint $table) {
            $table->id();
            $table->string("club_code", 20)->nullable(false);
            $table->unsignedBigInteger('created_by')->nullable(false);
            $table->decimal('amount', 12, 2)->nullable(false);
            $table->string('type', 12)->nullable(false)->default(\App\Models\AccountLaunch::CREDIT_TYPE);
            $table->string('mode', 20)->nullable(false)->default(\App\Models\ClubLaunch::MANUAL_MODE);
            $table->string('description', 20)->nullable(false)->default(\App\Models\ClubLaunch::DEBIT_DESCRIPTION);
            $table->string('user_description', 40)->nullable(true)->default(null);
            $table->timestamps();

            $table->foreign('club_code')->references('code')->on('clubs');
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
        Schema::dropIfExists('club_launch');
    }
}
