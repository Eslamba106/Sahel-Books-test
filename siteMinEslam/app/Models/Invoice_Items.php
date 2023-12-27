<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice_Items extends Model
{
    use HasFactory;

    protected $table = 'invoice_items';
    protected $fillable = ['id', 'invoice_id', 'item', 'qty', 'price', 'discount', 'total', 'type'];
    public $timestamps = false;
}
