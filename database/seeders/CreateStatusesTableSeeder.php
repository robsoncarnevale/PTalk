<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class CreateStatusesTableSeeder extends Seeder
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
                'description' => 'active'
            ],
            [
                'id' => 2,
                'description' => 'blocked'
            ],
            [
                'id' => 3,
                'description' => 'inactive'
            ]
        ];

        foreach($array as $register)
        {
            if(!Status::find($register['id']))
                Status::create($register);
        }
    }
}
