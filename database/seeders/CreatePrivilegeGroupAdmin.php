<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Club;
use App\Models\PrivilegeGroup;

/** 
 * Seeder Create Privilege Group Admin
 *
 * @author Davi Souto
 * @since 15/06/2020
 */
class CreatePrivilegeGroupAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $privilege_group = new PrivilegeGroup();

        $privilege_group->name = "Administrador";
        $privilege_group->club_code = Club::select('code')->first()['code'];
        $privilege_group->type = 'admin';

        $privilege_group->save();
    }
}
