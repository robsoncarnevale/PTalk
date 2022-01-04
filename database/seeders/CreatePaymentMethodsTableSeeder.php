<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;
use App\Models\PaymentMethod;

class CreatePaymentMethodsTableSeeder extends Seeder
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
                'description' => 'cash_debit',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 2,
                'description' => 'credit_cash',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 3,
                'description' => 'store_credit_installments',
                'status_id' => Status::INACTIVE
            ],
            [
                'id' => 4,
                'description' => 'issuer_installment_credit',
                'status_id' => Status::INACTIVE
            ]
        ];

        foreach($registers as $register)
        {
            if(!PaymentMethod::find($register['id']))
                PaymentMethod::create($register);
        }
    }
}
