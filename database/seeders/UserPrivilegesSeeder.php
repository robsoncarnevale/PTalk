<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UserPrivilegesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $id = (DB::table('privileges')->latest('id')->first()->id)-7;
        for($i = 1; $i < 8; $i++) {
            DB::table('user_privileges')
                        ->insert([
                            'user_id' => 1,
                            'privilege_id' => $id+$i
                        ]);
        }
    }
}
