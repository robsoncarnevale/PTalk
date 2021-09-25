<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Club;
use App\Models\PrivilegeGroup;

/** 
 * Seeder Create Privilege Group Member
 *
 * @author Davi Souto
 * @since 15/06/2020
 */
class CreatePrivilegeGroupMember extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $privilege_group = new PrivilegeGroup();

        $privilege_group->name = "Membro";
        $privilege_group->club_code = Club::select('code')->first()['code'];
        $privilege_group->type = 'member';

        $privilege_group->save();
    }
}
