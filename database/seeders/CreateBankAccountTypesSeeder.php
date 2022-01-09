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

        foreach($array as $register)
        {
            if(!BankAccountType::find($register['id']))
                BankAccountType::create($register);
        }
    }
}
