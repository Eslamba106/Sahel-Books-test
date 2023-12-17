<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceModel extends Model
{
    use HasFactory, HasApiTokens ,Notifiable ,SoftDeletes;
    protected $table = 'invoice';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'business_id',
        'title',
        'type',
        'recurring',
        'parent_id',
        'summary',
        'number',
        'poso_number',
        'customer',
        'date',
        'discount',
        'payment_due',
        'expire_on',
        'due_limit',
        'footer_note',
        'sub_total',
        'grand_total',
        'convert_total',
        'status',
        'reject_reason',
        'client_action_date',
        'is_sent',
        'is_completed',
        'sent_date',
        'recurring_start',
        'recurring_end',
        'frequency',
        'next_payment',
        'frequency_count',
        'auto_send',
        'send_myself',
        'created_at',
        'total_taxes',
        'modified_at',
        'send_status',
        'send_id',
        'paymentToken',
        'puid',
        'total_tax_items',
        'to_invoice_id',
        'credits_used',
        'remaining_amount',
        'to_invoice_number',
        'invoice_type',
        'model_id',
        'model_number',
        'total_discounts',
        'convert_sub_total',
        'shipping_cost',
        'shipping_tax',
        'shipping_tax_percent',
        'inv_tax',
        'inv_discount',
        'tax_type',
        'added_by',
        'shipping_company',
        'api_payment_method_name',
        'api_status_name',
        'api_status_code',
        'api_payment_method',
        'change_return',
        'updated_at',
        'deleted_at'
    ];
}
