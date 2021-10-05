<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\CarColor;
use App\Models\Club;

/** 
 * Seeder Create Car Color
 *
 * @author Davi Souto
 * @since 13/06/2020
 */
class CreateCarColor extends Seeder
{
    /**
     * @var string
     */
    private $club_code;

    /**
     * @var array
     */
    private $colors = [];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->club_code = DatabaseSeeder::$club_code;

        DatabaseSeeder::runClubFunction($this);

        foreach($this->colors as $name => $value)
        {
            $car_color = new CarColor();

            $car_color->name = $name;
            $car_color->value = $value;
            $car_color->club_code = $this->club_code;

            $car_color->save();
        }
    }

    //////////////////////

    public function PorscheTalk()
    {
        $this->colors = [
            'White' =>  '#FFFFFF',
            'Black' =>  '#000000',

            'Azure Metallic' =>  '#6B93C0',
            'Lapis BLue'    =>  '#091D66',
            'Green'         =>  '#5ED58D',
            'Terra Cotta'   =>  '#DFA686',
            'Violet Blue Metallic'  =>  '#A478C6',
            'State Gray'    =>  '#707070',
            'Night Blue Metallic'   =>  '#0B3753',
            'Moonlight Blue Pearl'  =>  '#282A47',
            'Guards Red'    =>  '#FF1D00',
            'Carmine Red'   =>  '#940C00',
            'Blue Turquoise'    =>  '#2BA4F1',
            'Lava Orange'   =>  '#FF4200',
            'Lizard Green'  =>  '#7EE411',

            // 'Miami Blue'  =>  '#267e9b',
            'Miami Blue'    =>  '#1BBFE8',
            'Speed Yellow'  =>  '#FFDE01',
        ];
    }
}
