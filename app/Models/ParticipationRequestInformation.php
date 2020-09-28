<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ParticipationRequestInformation Model
 *
 * @author Davi Souto
 * @since 28/09/2020
 */
class ParticipationRequestInformation extends Model
{
    protected $table = 'participation_request_information';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vehicle_carplate',
    ];
}
