<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax_typeModel extends Model
{
    use HasFactory;
    protected $table = 'tax_type';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'business_id',
        'name',
        'added_by',
        'tax_type_code'
    ];
}
