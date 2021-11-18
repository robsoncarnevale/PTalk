<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankAccountType;

class CreateBankAccountTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            [
                'id' => 1,
                'description' => 'club'
            ],
            [
                'id' => 2,
                'description' => 'member'
            ]
        ];

        $create = BankAccountType::insert($array);

        if(!$create)
            throw new \Exception('Falha ao criar tipos de contas!');
    }
}
