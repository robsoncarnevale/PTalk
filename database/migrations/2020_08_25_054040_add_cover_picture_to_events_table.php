<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoverPictureToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('cover_picture')->nullable(true)->default(null);

            $table->string('start_time', 5)->nullable(false)->change();
            $table->string('end_time', 5)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $this->dropColumn('cover_picture');

            $table->string('start_time', 4)->nullable(false)->change();
            $table->string('end_time', 4)->nullable(true)->change();
        });
    }
}
