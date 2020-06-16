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
}
