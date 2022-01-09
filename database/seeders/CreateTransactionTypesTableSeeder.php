<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransactionType;

class CreateTransactionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $registers = [
            [
                'id' => 1,
                'description' => 'financial'
            ],
            [
                'id' => 2,
                'description' => 'cancel'
            ],
            [
                'id' => 3,
                'description' => 'reversal'
            ]
        ];

        foreach($registers as $register)
        {
            if(!TransactionType::find($register['id']))
                TransactionType::create($register);
        }
    }
}
