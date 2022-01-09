<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransactionStatus;

class CreateTransactionStatusesTableSeeder extends Seeder
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
                'description' => 'approved'
            ],
            [
                'id' => 2,
                'description' => 'denied'
            ],
            [
                'id' => 3,
                'description' => 'no_reply'
            ]
        ];

        foreach($registers as $register)
        {
            if(!TransactionStatus::find($register['id']))
                TransactionStatus::create($register);
        }
    }
}
