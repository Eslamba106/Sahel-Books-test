<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessModel extends Model
{
    use HasFactory;
    protected $table = 'business';
    public $timestamps = false;
    protected $fillable = [
        'address',
        'biz_number',
        'business_tax_type',
        'business_type',
        'category',
        'color',
        'country',
        'created_at',
        'currency',
        'enable_stock',
        'enable_negative_stock',
        'footer_note',
        'is_autoload_amount',
        'is_primary',
        'logo',
        'name',
        'shipping_include_tax',
        'slug',
        'status',
        'template_style',
        'title',
        'type',
        'uid',
        'user_id',
        'vat_code',
    ];
}
