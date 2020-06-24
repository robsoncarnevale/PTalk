<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

use App\Models\CarModel;

class AddCarModelPicture extends Seeder
{
    /**
     * @var string
     */
    private $club_code;

    /**
     * @var array[App\Model\CarModel]
     */
    private $car_models;


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->club_code = DatabaseSeeder::$club_code;
        $this->car_models = $this->getCarModels();

        $this->getPictures($this->car_models);
    }

    private function getCarModels()
    {
        return CarModel::select()
            ->with('car_brand:id,name')
            ->where('club_code', $this->club_code)
            ->where('picture', null)
            ->get();
    }

    /**
     * Get pictures for car models
     *
     * @param array[App\Model\CarModel] $car_models
     * @author Davi Souto
     * @since 23/06/2020
     */
    private function getPictures($car_models)
    {
        foreach($car_models as $car_model)
        {
            $car_brand_name = strtolower(preg_replace("#[^0-9A-Z]#is", "_", $car_model['car_brand']['name']));
            $car_model_name = strtolower(preg_replace("#[^0-9A-Z]#is", "_", $car_model['name']));
            $car_picture_filename = 'car_model/' . $car_brand_name . "_" . $car_model_name;
            $exists = false;

            // echo $car_picture_filename . PHP_EOL;

            if (Storage::disk('images')->exists($car_picture_filename . ".png"))
            {
                $exists = "png";
                $car_model->picture = $car_picture_filename . ".png";
            }

            if (Storage::disk('images')->exists($car_picture_filename . ".jpg"))
            {
                $exists = "jpg";
                $car_model->picture = $car_picture_filename . ".jpg";
            }

            if (Storage::disk('images')->exists($car_picture_filename . ".jpeg"))
            {
                $exists = "jpeg";
                $car_model->picture = $car_picture_filename . ".jpeg";
            }

            if ($exists)
            {
                echo "Car model \033[35m" . $car_model['car_brand']['name'] . " " . $car_model['name'] . "\033[0m picture added" . PHP_EOL;

                $car_model->save();
            }

        }
    }
}
