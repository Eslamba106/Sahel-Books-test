<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $table = 'country';
    protected $fillable = ['id', 'name', 'code', 'dial_code', 'currency_name', 'currency_symbol', 'currency_code'];
}
