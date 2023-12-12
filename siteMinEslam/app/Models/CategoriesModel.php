<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesModel extends Model
{
    use HasFactory;
    protected $table = 'categories';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'business_id',
        'name',
        'slug',
        'type',
        'parent_id',
        'model_name',
        'model_number',
        'added_by'
    ];
}
