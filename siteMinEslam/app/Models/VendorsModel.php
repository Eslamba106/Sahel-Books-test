<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorsModel extends Model
{
    use HasFactory;
    protected $table = 'vendors';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'business_id',
        'name',
        'phone',
        'email',
        'address',
        'tax_number',
        'created_at',
        'added_by'
    ];
}
