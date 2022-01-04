<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'description', 'status_id'];

    const CASH_DEBIT = 1;
    const CREDIT_CASH = 2;
    const STORE_CREDIT_INSTALLMENTS = 3;
    const ISSUER_INSTALLMENT_CREDIT = 4;

    public function status()
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }
}
