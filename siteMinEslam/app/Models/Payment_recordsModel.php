<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment_recordsModel extends Model
{
    use HasFactory;
    protected $table = 'payment_records';
    public $timestamps = false;
    protected $fillable = [
        'invoice_id',
        'business_id',
        'customer_id',
        'amount',
        'convert_amount',
        'payment_date',
        'payment_method',
        'note',
        'type',
        'created_at',
        'to_credit_id'
    ];
}
