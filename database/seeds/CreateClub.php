<?php

use Illuminate\Database\Seeder;

use App\Models\Club;

/** 
 * Seeder Create Club
 *
 * @author Davi Souto
 * @since 15/06/2020
 */
class CreateClub extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $club = new Club();

        $club->name = 'Porsche Talk';
        $club->code = 'porsche_talk';
        $club->primary_color = '#E63D39';
        $club->contact_mail = 'contact@porschetalk.com.br';
        $club->url = 'http://porschetalk.bitnary.com.br';

        $club->save();
    }
}
