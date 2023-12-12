<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    use HasFactory;
    protected $table = 'payment';
    public $timestamps = false;
    protected $fillable = [
        'user_id' ,
        'puid' ,
        'package' ,
        'amount' ,
        'billing_type' ,
        'payment_type' ,
        'status' ,
        'created_at' ,
        'expire_on' 
    ];
}
