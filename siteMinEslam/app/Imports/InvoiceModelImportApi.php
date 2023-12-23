<?php

namespace App\Imports;

use App\Models\UsersModel;
use App\Models\InvoiceModel;
use App\Models\BusinessModel;
use Maatwebsite\Excel\Concerns\ToModel;

class InvoiceModelImportApi implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user_auth =  auth('sanctum')->user()->id  ;
        $business_default = BusinessModel::where('enable_stock' , 1)->first();
        $user = UsersModel::where('user_name' , $row[0])->first();
        $business = BusinessModel::where('name' , $row[1])->first();
        return new InvoiceModel([
            'user_id' => $user->id ?? $user_auth,
            'business_id' => $business->uid ?? $business_default->uid,
            'title' => $row[2],
            'type' => $row[3],
            'number' =>  $row[4] ,
            'date' => $row[5] ?? date('Y-m-d-h-m-s'),
            'discount' => $row[6] ?? 0,
            'payment_due' => $row[7] ?? "",
            'sub_total' => $row[8] ?? "",
            'grand_total' => $row[9] ?? "",
            'convert_total' => $row[10] ?? "",
            'status' => $row[11] ?? "0",
            'footer_note' => $row[12] ?? "",
            'reject_reason' => '',
            'client_action_date' => '',
            'parent_id' => '',
            'expire_on' => '',
            'due_limit' => '',
            'is_sent' => '',
            'is_completed' => '',
            'sent_date' => '',
            'recurring_start' => '',
            'recurring_end' => '',
            'frequency' => '',
            'next_payment' => '',
            'frequency_count' => '',
            'auto_send' => '',
            'send_myself' => '',
            'recurring' => "",
            'summary' =>"",
            'poso_number' => "",
            'customer' => "",
            'created_at' => date('Y-m-d-h-m-s'),
        ]);
    }
}
