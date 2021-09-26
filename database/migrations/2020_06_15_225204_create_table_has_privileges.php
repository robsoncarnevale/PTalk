<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableHasPrivileges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('has_privileges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('privilege_group_id')->nullable(false);
            $table->string('privilege_action')->nullable(false);
            $table->timestamps();

            $table->foreign('privilege_group_id')
                ->references('id')
                ->on('privileges_groups');

            $table->foreign('privilege_action')
                ->references('action')
                ->on('privileges');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('has_privileges');
    }
}
