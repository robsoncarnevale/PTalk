<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\CarModel;
use App\Models\CarBrand;
use App\Models\Club;

// use DB;

/** 
 * Seeder Create Car Model
 *
 * @author Davi Souto
 * @since 13/06/2020
 */
class CreateCarModel extends Seeder
{
    /**
     * @var string
     */
    private $club_code;

    /**
     * @var array
     */
    private $brands = [];

    /**
     * @var array
     */
    private $models = [];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->club_code = DatabaseSeeder::$club_code;

        DatabaseSeeder::runClubFunction($this);

        $this->getCarBrands();
        $this->addCarModels();
    }

    ///////////////////////////

    /**
     * Get car brands data
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    private function getCarBrands()
    {
        foreach($this->models as $brand_name => $model_name)
        {
            if (! in_array($brand_name, $this->brands))
            {
                $this->brands[$brand_name] = CarBrand::select('id', 'name')
                    ->where('name', $brand_name)
                    ->where('club_code', $this->club_code)
                    ->first();

                if (! $this->brands[$brand_name])
                    throw new Exception('Car brand ' . $brand_name . ' not found');

                $this->brands[$brand_name] = $this->brands[$brand_name]->toArray();
            }
        }
    }

    /**
     * Add car models
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    private function addCarModels()
    {
        foreach($this->models as $car_brand_name => $car_models)
        {
            foreach($car_models as $model)
            {
                $car_brand = $this->brands[$car_brand_name];
                $car_model = new CarModel();

                $car_model->name = $model;
                $car_model->club_code = $this->club_code;
                $car_model->car_brand_id = $car_brand['id'];

                $car_model->save();
            }
        }
    }

    ///////////////////////////

    /**
     * PORSCHE TALK
     */
    public function PorscheTalk()
    {
        $this->models = [
            'Porsche'    =>  [
                '718 Cayman',
                '718 Cayman S',
                '718 Boxster',
                '718 Boxster S',
                '718 Cayman GTS',
                '718 Boxster GTS',
                '718 Cayman GT4',
                '718 Spyder',
                '911 Carrera',
                '911 Carrera S',
                '911 Carrera S Cabriolet',
                '911 Carrera 4S',
                '911 Carrera 4S Cabriolet',
                '911 Turbo S',
                '911 Speedster',
                '911 GT3',
                '911 GT3 RS',
                'Taycan 4S',
                'Taycan Turbo',
                'Taycan Turbo S',
                'Panamera',
                'Panamera 10 Years Edition',
                'Panamera 4',
                'Panamera 4 Sport Turismo', 
                'Panamera 4 Executive',
                'Panamera GTS',
                'Panamera GTS Sport Turismo',
                'Panamera Turbo',
                'Panamera Turbo Sport Turismo',
                'Panamera Turbo Executive',
                'Panamera 4 E-Hybrid',
                'Panamera 4 E-Hybrid 10 Years Edition',
                'Panamera 4 E-Hybrid Sport Turismo',
                'Panamera 4 E-Hybrid Executive',
                'Panamera Turbo S E-Hybrid',
                'Macan',
                'Macan S',
                'Macan GTS',
                'Cayenne',
                'Cayenne E-Hybrid',
                'Cayenne S',
                'Cayenne Turbo',
                'Cayenne Turbo S E-Hybrid',
                'Cayenne Coupé',
                'Cayenne Turbo S E-Hybrid Coupé',
            ],
        ];
    }
}
