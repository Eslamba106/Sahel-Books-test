<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice_taxesModel extends Model
{
    use HasFactory;
    protected $table = 'invoice_taxes';
    public $timestamps = false;
    protected $fillable = [
        'invoice_id',
        'tax_id',
        'tax_type'
    ];
}
