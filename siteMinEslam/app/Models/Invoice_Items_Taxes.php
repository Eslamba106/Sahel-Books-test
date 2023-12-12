<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice_Items_Taxes extends Model
{
    use HasFactory;
    protected $table = 'invoice_items_taxes';
    protected $fillabel = ['id', 'invoice_id', 'invoice_item_id', 'tax_id', 'tax_type', 'tax_rate', 'tax_value'];
}
