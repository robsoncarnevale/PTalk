<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Status;

class CreateBrandsTableSeeder extends Seeder
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
                'description' => 'mastercard',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 2,
                'description' => 'maestro',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 3,
                'description' => 'visa',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 4,
                'description' => 'visa_electron',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 5,
                'description' => 'elo_credit',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 6,
                'description' => 'elo_debit',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 7,
                'description' => 'hipercard',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 8,
                'description' => 'amex',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 9,
                'description' => 'banricompras',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 10,
                'description' => 'cabal',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 11,
                'description' => 'jcb',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 12,
                'description' => 'credsystem_credit',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 13,
                'description' => 'diners_credit',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 14,
                'description' => 'discover',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 15,
                'description' => 'aura',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 16,
                'description' => 'sorocred_credit',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 17,
                'description' => 'agiplan_credit',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 18,
                'description' => 'banescard_credit',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 19,
                'description' => 'credz_credit',
                'image' => '',
                'status_id' => Status::ACTIVE
            ],
            [
                'id' => 20,
                'description' => 'banescard_debit',
                'image' => '',
                'status_id' => Status::ACTIVE
            ]
        ];

        foreach($registers as $register)
        {
            if(!Brand::find($register['id']))
                Brand::create($register);
        }
    }
}
