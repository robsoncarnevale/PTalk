<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * CarColor Model
 *
 * @author Davi Souto
 * @since 13/06/2020
 */
class CarColor extends Model
{
    protected $table = 'car_colors';

    protected $fillable = [
        'name',
        'value',
    ];

    public function vehicles()
    {
        return $this->hasMany('App\Models\Vehicle');
    }
}
