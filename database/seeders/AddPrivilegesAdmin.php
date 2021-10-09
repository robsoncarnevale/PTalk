<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Privilege;
use App\Models\User;

class AddPrivilegesAdmin extends Seeder
{
    /**
     * @var string
     */
    private $club_code;

    /**
     * @var collection
     */
    private $privilege_group;

    /**
     * @var array
     */
    private $privileges = [];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->club_code = DatabaseSeeder::$club_code;
        $this->privileges = Privilege::all();

        $user = User::where('email', 'admin@i4motors.com.br')
                    ->where('type', 'admin')
                    ->first();

        if(!$user)
            return;

        DB::table('user_privileges')
            ->where('user_id', $user->id)
            ->delete();

        foreach($this->privileges as $privilege)
        {
            DB::table('user_privileges')->insert([
                'user_id' => $user->id,
                'privilege_id' => $privilege->id,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
        }
    }
}
