<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCart extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'product_id', 'quantity', 'value', 'state' ];

    const OPENED = 'opened';
    const CLOSED = 'closed';
    const SELLED = 'selled';
}
