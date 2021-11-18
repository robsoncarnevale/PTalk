<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    const ACTIVE = 1;
    const BLOCKED = 2;
    const INACTIVE = 3;

    protected $fillable = ['id', 'description'];
}
