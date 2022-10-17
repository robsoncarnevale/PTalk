<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'date_begin',
        'date_finish',
        'quantity',
        'name',
        'description',
        'value',
        'img_url',
        'user_id'
    ];
}
