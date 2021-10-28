<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration Create fields on users table
 *
 * @author Davi Souto
 * @since 07/06/2020
 */
class CreateFieldsOnTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('photo')->nullable(true);
            $table->string('document_cpf', 11)->nullable(false);
            $table->string('document_rg', 20)->nullable(true);

            $table->string('cell_phone', 11)->nullable(false);
            $table->string('phone', 11)->nullable(true);
            $table->string('home_address')->nullable(true);
            $table->string('comercial_address')->nullable(true);
            $table->string('company')->nullable(true);
            $table->text('company_activities')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn('photo');
            $table->dropColumn('document_cpf');
            $table->dropColumn('rg');

            $table->dropColumn('cell_phone');
            $table->dropColumn('phone');
            $table->dropColumn('home_address');
            $table->dropColumn('comercial_address');
            $table->dropColumn('company');
            $table->dropColumn('company_activities');
        });
    }
}
