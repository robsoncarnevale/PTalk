<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * CarBrand Model
 *
 * @author Davi Souto
 * @since 13/06/2020
 */
class CarBrand extends Model
{
    protected $table = 'car_brands';

    /**
     * car_model_id => car_models.id
     */
    public function car_models()
    {
        return $this->hasMany('App\Models\CarModel');
    }
}
