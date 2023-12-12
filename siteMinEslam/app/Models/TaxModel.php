<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxModel extends Model
{
    use HasFactory;
    protected $table = 'tax';

    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'business_id',
        'type',
        'name',
        'rate',
        'number',
        'details',
        'is_invoices',
        'is_recoverable',
        'tax_kind',
        'tax_sub_kind',
        'tax_code',
        'added_by'
    ];
}
