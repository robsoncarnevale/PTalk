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
        //e rodar o comando 
        //php artisan db:seed --class=UserPrivilegesSeeder
        $email = 'admin@4clubes.com.br';
        $user = DB::table('users')->where('email',$email)->get();
        if ($user) {
            $user_id = $user[0]->id;
            DB::table('user_privileges')->where('user_id', $user_id)->delete();
            $privileges = DB::table('privileges')->get();
            foreach($privileges as $item) {
                $this->insertPrivillege($user_id,$item->id);
            }
        }

        $email = 'member@demonstration';
        $user = DB::table('users')->where('email',$email)->get();
        if ($user) {
            $this->createPrivillege($user[0]->id,102); // Dar o privilegio de ver a loja - product.list
            $this->createPrivillege($user[0]->id,131); // Dar o privilegio de buscar shop_cart - shopcart.getall
            $this->createPrivillege($user[0]->id,132); // Dar o privilegio de buscar os carrinhos abertos - shopcart.getopenedcart
            $this->createPrivillege($user[0]->id,133); // Dar o privilegio de buscar adicionar o produto ao carrinho - product.addtocart
        }
    }

    private function createPrivillege($user_id,$privilege_id) {
        $privileges = DB::table('user_privileges')
                            ->where('user_id', $user_id)
                            ->where('privilege_id', $privilege_id)
                            ->get();
        if ($privileges->count() < 1) {
            $this->insertPrivillege($user_id,$privilege_id);
        }
    }

    private function insertPrivillege($user_id,$privilege_id) {
        DB::table('user_privileges')->insert([
            'user_id' => $user_id,
            'privilege_id' => $privilege_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
