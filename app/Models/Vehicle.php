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

    public function photos()
    {
        return $this->hasMany('App\Models\VehiclePhoto');
    }

    /**
     * Returns carplate on uppercase
     * @since 14/09/2020
     */
    public function getCarplateAttribute($carplate)
    {
        if ($carplate) {
            return strtoupper($carplate);
        }
    }

    /**
     * Returns formatted carplate
     * @since 14/09/2020
     */
    public function getCarplateFormattedAttribute()
    {
        if (strlen($this->carplate) <= 7) {
            $formatted_carplate = preg_replace('#[^0-9a-zA-Z]#is', '', $this->carplate);
            $formatted_carplate = substr($formatted_carplate, 0, 3) . '-' . substr($formatted_carplate, 3);

            return $formatted_carplate;
        }

        return $this->carplate;
    }
}
