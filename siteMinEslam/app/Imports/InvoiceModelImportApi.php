<?php

namespace App\Imports;

use App\Models\UsersModel;
use App\Models\InvoiceModel;
use App\Models\BusinessModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvoiceModelImportApi implements ToModel, WithChunkReading, ShouldQueue,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use Importable ;
    public function model(array $row)
    {
        $user_auth =  auth('sanctum')->user()->id;
        $business_default = BusinessModel::where('enable_stock' , 1)->first();
        $user = UsersModel::where('user_name' , $row['username'])->first();
        $business = BusinessModel::where('name' , $row['business'])->first();
        return new InvoiceModel([
            'title' => $row['title'],
            'user_id' => $user->id ?? $user_auth,
            'business_id' => $business->uid ?? $business_default->uid,
            'type' => $row['type'],
            'number' =>  $row['number'] ,
            'date' => $row['date'] ?? date('Y-m-d-h-m-s'),
            'discount' => $row['discount'] ?? 0,
            'payment_due' => $row['payment_deu'] ?? "",
            'sub_total' => $row['sub_total'] ?? "",
            'grand_total' => $row['grand_total'] ?? "",
            'convert_total' => $row['convert_total'] ?? "",
            'status' => $row['status'] ?? "0",
            'footer_note' => $row['footer_note'] ?? "",
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

    public function chunkSize(): int
    {
        return 1000;
    }
}
