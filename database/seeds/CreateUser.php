<?php

/** 
 * Seeder Create User
 *
 * @author Davi Souto
 * @since 18/05/2020
 */

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\PrivilegeGroup;

use Illuminate\Support\Facades\Hash;

class CreateUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($type = )
    {
        $user = new User();

        $user->club_code = "porsche_talk";
        $user->name = "Administrador";
        $user->email = "admin@porsche.com";
        $user->password = Hash::make('123456');
        $user->type = 'admin';

        $user->privilege_id = PrivilegeGroup::select('id')->first()->id;
        $user->document_cpf = "12345678900";
        $user->cell_phone = "1122223333";

        $user->save();
    }
}
