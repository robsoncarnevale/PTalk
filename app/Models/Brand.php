<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'description', 'image', 'status_id'];

    public function status()
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }
}
