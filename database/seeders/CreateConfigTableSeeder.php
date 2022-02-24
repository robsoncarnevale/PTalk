<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Config;

class CreateConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!Config::first())
            Config::create([
                'club_code' => getClubCode()
            ]);
    }
}
