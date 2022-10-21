<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Config;

use DB;

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
                'club_code' => DB::table('clubs')->where('id', 1)->get()->toArray()[0]->code//getClubCode()
            ]);
    }
}
