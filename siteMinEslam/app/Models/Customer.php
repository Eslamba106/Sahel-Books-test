<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $fillable = ['id', 'user_id', 'business_id', 'name', 'email', 'phone', 'thumb', 'address', 'country', 'currency', 'cus_number', 'vat_code', 'city', 'postal_code', 'address1', 'address2', 'status', 'created_at'];
}
