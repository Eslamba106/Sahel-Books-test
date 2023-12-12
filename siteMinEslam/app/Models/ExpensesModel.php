<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensesModel extends Model
{
    use HasFactory;
    protected $table = 'expenses';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'business_id',
        'category',
        'vendor',
        'amount',
        'net_amount',
        'date',
        'notes',
        'tax',
        'file',
        'created_at',
        'payment_method',
        'status',
        'model_name',
        'model_number',
        'number',
        'tax_id',
        'added_by'
    ];
}
