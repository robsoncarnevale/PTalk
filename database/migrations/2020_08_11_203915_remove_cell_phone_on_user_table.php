<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCellPhoneOnUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = \App\Models\User::all();

        foreach($users as $user)
        {
            if (empty($user->phone))
                $user->phone = $user->cell_phone;

            $user->save();
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('cell_phone');
            $table->string('phone', 11)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->string('cell_phone', 11)->nullable(false);
    }
}
