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
        //Caso precise gerar os privilegios para o admin, basta colocar o id do usuario
        //e rodar o comando php artisan db:seed --class=UserPrivilegesSeeder
        $user_id = 1;
        DB::table('user_privileges')->where('user_id', $user_id)->delete();
        $privileges = DB::table('privileges')->get();
        foreach($privileges as $item) {
            DB::table('user_privileges')->insert([
                            'user_id' => $user_id,
                            'privilege_id' => $item->id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
        }
    }
}
