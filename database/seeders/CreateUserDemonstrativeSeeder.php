<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class CreateUserDemonstrativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $code = \App\Models\Club::first();

        $user = User::where('email', 'member@demonstrative')->first();

        if(!$user)
        {
            $user = User::create([
                'club_code' => $code->code ? $code->code : env('CLUB_CODE') ,
                'phone' => '00111111111',
                'email' => 'member@demonstrative',
                'document_cpf' => '18539192098',
                'name' => 'Usuário Demonstrativo',
                'document_rg' => '0000000000',
                'nickname' => 'Usuário Demo',
                'member_class_id' => 1,
                'password' => bcrypt('1asdf8481bc'),
                'status' => 'blocked',
                'approval_status' => 'approved'
            ]);

            $user->createBankAccount();
            $user->applyPrivilegesMember();
        }
    }
}
