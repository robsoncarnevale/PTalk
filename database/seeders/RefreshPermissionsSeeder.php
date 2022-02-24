<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Privilege;
use Illuminate\Support\Facades\DB;

class RefreshPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::where('type', 'member')->get();
        $privileges = Privilege::whereIn('action', (new User())->privileges_member)->get()->toArray();

        foreach($users as $user)
        {
            DB::table('user_privileges')->where('user_id', $user->id)->delete();

            $data = array_map(function($privilege) use ($user){

                return [
                    'user_id' => $user->id,
                    'privilege_id' => $privilege['id'],
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ];

            }, $privileges);

            DB::table('user_privileges')->insert($data);
        }
    }
}
