<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * VehiclePhoto Model
 *
 * @author Davi Souto
 * @since 08/09/2020
 */
class VehiclePhoto extends Model
{
    protected $table = 'vehicles_photos';

    protected $fillable = [];
    protected $hidden = [ 'club_code' ];

    public function vehicle()
    {
      return $this->belongsTo('App\Models\Vehicle');
    }
}
