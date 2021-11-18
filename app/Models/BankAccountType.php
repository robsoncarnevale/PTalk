<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccountType extends Model
{
    use HasFactory;

    const CLUB = 1;
    const MEMBER = 2;

    protected $fillable = ['id', 'description'];
}
