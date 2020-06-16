<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Privilege Model
 *
 * @author Davi Souto
 * @since 15/06/2020
 */
class Privilege extends Model
{
    protected $table = 'privileges';

    // protected $primaryKey = null;
    protected $primaryKey = 'action';
    public $incrementing = false;
}
