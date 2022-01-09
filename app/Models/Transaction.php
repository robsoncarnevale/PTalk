<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_account_id',
        'payment_method_id',
        'brand_id',
        'installments',
        'card_name',
        'card_number',
        'order_number',
        'amount',
        'authorization',
        'nsu',
        'transaction_type_id',
        'transaction_status_id'
    ];

    public function account()
    {
        return $this->hasOne(BankAccount::class, 'id', 'bank_account_id');
    }

    public function method()
    {
        return $this->hasOne(PaymentMethod::class, 'id', 'payment_method_id');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function type()
    {
        return $this->hasOne(TransactionType::class, 'id', 'transaction_type_id');
    }

    public function status()
    {
        return $this->hasOne(TransactionStatus::class, 'id', 'transaction_status_id');
    }

    public static function order()
    {
        $club = Club::first();

        if(!$club)
            throw new \Exception(__('club.not-found'));

        $prefix = strtoupper(substr($club->name, 0, 3));

        $date = \Carbon\Carbon::now()->format('dmYHis');

        return $prefix . $date . rand(000000000, 999999999) ;
    }
}