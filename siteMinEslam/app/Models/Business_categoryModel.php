<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business_categoryModel extends Model
{
    use HasFactory;
    protected $table = 'business_category';
    public $timestamps = false;
}
