<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    use HasFactory;

    const FINANCIAL = 1;
    const CANCEL = 2;
    const REVERSAL = 3;

    protected $fillable = ['id', 'description'];
}
