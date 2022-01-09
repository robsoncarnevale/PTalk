<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionStatus extends Model
{
    use HasFactory;

    const APPROVED = 1;
    const DENIED = 2;
    const NO_REPLY = 3;

    protected $fillable = ['id', 'description'];
}
