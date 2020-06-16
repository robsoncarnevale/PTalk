<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * CarModel Model
 *
 * @author Davi Souto
 * @since 13/06/2020
 */
class CarModel extends Model
{
    protected $table = 'car_models';

    /**
     * car_models.id => car_model_id
     */
    public function car_brand()
    {
        return $this->belongsTo('App\Models\CarBrand');
    }
}
