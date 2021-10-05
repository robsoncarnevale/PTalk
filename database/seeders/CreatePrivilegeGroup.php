<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Club;
use App\Models\PrivilegeGroup;

/** 
 * Seeder Create Privilege Group
 *
 * @author Davi Souto
 * @since 07/06/2020
 */
class CreatePrivilegeGroup extends Seeder
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

        $privilege_group->save();
    }
}
