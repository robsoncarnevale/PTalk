<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 * Vehicle Model
 *
 * @author Davi Souto
 * @since 15/06/2020
 */
class Vehicle extends Model
{
    protected $table = 'vehicles';

    protected $fillable = [
        'carplate', 
        'user_id', 
        'car_model_id', 
        'car_color_id', 
        'year_manufacture', 
        'model_year', 
        'document_renavam', 
        'chassis', 
        'club_code',
    ];

    protected $hidden = [
        'club_code',
    ];

    /**
     * vehicle.user_id => users.id
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    /**
     * vehicle.car_model_id => car_models.id
     */
    public function car_model()
    {
        return $this->hasOne('App\Models\CarModel', 'id', 'car_model_id');
    }

    /**
     * vehicle.car_model_id => car_models.id
     */
    public function car_color()
    {
        return $this->hasOne('App\Models\CarColor', 'id', 'car_color_id');
    }
}
