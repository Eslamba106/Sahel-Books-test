<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsModel extends Model
{
    use HasFactory;
    protected $table = 'products';
    public $timestamps = false;
    protected $fillable = [
        'added_by',
        'barcode',
        'business_id',
        'code',
        'cost_price',
        'created_at',
        'details',
        'expense_category',
        'image',
        'income_category',
        'is_buy',
        'is_sell',
        'model_id',
        'model_name',
        'model_number',
        'name',
        'price',
        'qrcode',
        'quantity',
        'slug',
        'user_id',
        'variant_id',
        'business_id',
        'code',
        'created_at',
        'details',
        'expense_category',
        'income_category',
        'is_buy',
        'is_sell',
        'model_name',
        'model_number',
        'name',
        'price',
        'quantity',
        'slug',
        'user_id',
        'added_by',
        'barcode',
        'business_id',
        'code',
        'cost_price',
        'created_at',
        'details',
        'expense_category',
        'expire_date',
        'image',
        'income_category',
        'is_buy',
        'is_sell',
        'model_id',
        'model_name',
        'model_number',
        'name',
        'price',
        'qrcode',
        'quantity',
        'slug',
        'unit_type',
        'user_id',
        'variant_id'
    ];
}
