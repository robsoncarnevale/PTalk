<?php

use Illuminate\Database\Seeder;

use App\Models\CarBrand;
use App\Models\Club;

/** 
 * Seeder Create Car Brand
 *
 * @author Davi Souto
 * @since 13/06/2020
 */
class CreateCarBrand extends Seeder
{
    private $club_code;
    private $car_brands = [];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->club_code = DatabaseSeeder::$club_code;

        DatabaseSeeder::runClubFunction($this);

        foreach($this->car_brands as $add_car_brand)
        {
            $car_brand = new CarBrand();

            $car_brand->name = $add_car_brand;
            $car_brand->club_code = $this->club_code;

            $car_brand->save();
        }
    }

    ///////////////////////////

    /**
     * PORSCHE TALK
     */
    public function PorscheTalk()
    {
        $this->car_brands = [
            'Porsche'
        ];
    }
}
