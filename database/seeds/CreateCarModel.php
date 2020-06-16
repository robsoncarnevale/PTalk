<?php

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
        foreach($this->models as $car_brand_name => $model)
        {
            $car_brand = $this->brands[$car_brand_name];
            $car_model = new CarModel();

            $car_model->name = $model;
            $car_model->club_code = $this->club_code;
            $car_model->car_brand_id = $car_brand['id'];

            $car_model->save();
        }
    }

    ///////////////////////////

    /**
     * PORSCHE TALK
     */
    public function PorscheTalk()
    {
        $this->models = [
            'Porsche' => '718 Cayman',
            'Porsche' => '718 Cayman S',
            'Porsche' => '718 Boxter',
            'Porsche' => '718 Boxter S',
            'Porsche' => '718 Cayman GTS',
            'Porsche' => '718 Boxter GTS',
            'Porsche' => '718 Cayman GT4',
            'Porsche' => '718 Spider',
            'Porsche' => '911 Carrera',
            'Porsche' => '911 Carrera S',
            'Porsche' => '911 Carrera S Cabriolet',
            'Porsche' => '911 Carrera 4S',
            'Porsche' => '911 Carrera 4S Cabriolet',
            'Porsche' => '911 Turbo S',
            'Porsche' => '911 Speedster',
            'Porsche' => '911 GT3',
            'Porsche' => '911 GT3 RS',
            'Porsche' => 'Taycan 4S',
            'Porsche' => 'Taycan Turbo',
            'Porsche' => 'Taycan Turbo S',
            'Porsche' => 'Panamera',
            'Porsche' => 'Panamera 10 Years Edition',
            'Porsche' => 'Panamera 4',
            'Porsche' => 'Panamera 4 Sport Turismo', 
            'Porsche' => 'Panamera 4 Executive',
            'Porsche' => 'Panamera GTS',
            'Porsche' => 'Panamera GTS Sport Turismo',
            'Porsche' => 'Panamera Turbo',
            'Porsche' => 'Panamera Turbo Sport Turismo',
            'Porsche' => 'Panamera Turbo Executive',
            'Porsche' => 'Panamera 4 E-Hybrid',
            'Porsche' => 'Panamera 4 E-Hybrid 10 Years Edition',
            'Porsche' => 'Panamera 4 E-Hybrid Sport Turismo',
            'Porsche' => 'Panamera 4 E-Hybrid Executive',
            'Porsche' => 'Panamera Turbo S E-Hybrid',
            'Porsche' => 'Macan',
            'Porsche' => 'Macan S',
            'Porsche' => 'Macan GTS',
            'Porsche' => 'Cayenne',
            'Porsche' => 'Cayenne E-Hybrid',
            'Porsche' => 'Cayenne S',
            'Porsche' => 'Cayenne Turbo',
            'Porsche' => 'Cayenne Turbo S E-Hybrid',
            'Porsche' => 'Cayenne Coupé',
            'Porsche' => 'Cayenne Turbo S E-Hybrid Coupé',
        ];
    }
}
