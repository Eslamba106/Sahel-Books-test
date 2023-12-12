<?php

namespace App\Imports;

use App\Models\InvoiceModel;
use Maatwebsite\Excel\Concerns\ToModel;

class InvoiceModelImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new InvoiceModel([
            'user_id' => $row[0],
            'business_id'=> $row[1],
            'title' => $row[2],
            'type' => $row[3],
            'recurring' => $row[4],
            'parent_id' => $row[5],
            'summary' => $row[6],
            'number' => $row[7],
            'poso_number' => $row[8],
            'customer' => $row[9],
            'date' => $row[10],
            'discount' => $row[11],
            'payment_due' => $row[12],
            'expire_on' => $row[13],
            'due_limit' => $row[14],
            'footer_note' => $row[15],
            'sub_total' => $row[16],
            'grand_total' => $row[17],
            'convert_total'=> $row[18],
            'status' => $row[19],
            'reject_reason' => $row[20],
            'client_action_date' => $row[21],
            'is_sent'=> $row[22],
            'is_completed'=> $row[23],
            'sent_date' => $row[24],
            'recurring_start' => $row[25],
            'recurring_end' => $row[26],
            'frequency' => $row[27],
            'next_payment' => $row[28],
            'frequency_count' => $row[29],
            'auto_send' => $row[30],
            'send_myself' => $row[31],
            'created_at' => $row[32],
            'total_taxes' => $row[33],
            'modified_at' => $row[34],
            'send_status'=> $row[35],
            'send_id'=> $row[36],
            'paymentToken'=> $row[37],
            'puid' => $row[38],
            'total_tax_items' => $row[39],
            'to_invoice_id' => $row[40],
            'credits_used' => $row[41],
            'remaining_amount' => $row[42],
            'to_invoice_number'  => $row[43],
            'invoice_type' => $row[44],
            'model_id' => $row[45],
            'model_number' => $row[46],
            'total_discounts' => $row[47],
            'convert_sub_total' => $row[48],
            'shipping_cost' => $row[49],
            'shipping_tax' => $row[50],
            'shipping_tax_percent' => $row[51],
            'inv_tax'  => $row[52],
            'inv_discount'  => $row[53],
            'tax_type' => $row[54],
            'added_by' => $row[55],
            'shipping_company' => $row[56],
            'api_payment_method_name' => $row[57],
            'api_status_name' => $row[58],
            'api_status_code' => $row[59],
            'api_payment_method' => $row[60],
            'change_return' => $row[61]
        ]);
    }
}
