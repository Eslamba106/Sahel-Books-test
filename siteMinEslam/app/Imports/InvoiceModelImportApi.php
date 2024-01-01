<?php

namespace App\Imports;

use App\Models\UsersModel;
use App\Models\InvoiceModel;
use App\Models\BusinessModel;
use App\Models\Invoice_Items;
use App\Models\ProductsModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Predis\Command\Traits\BloomFilters\Items;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class InvoiceModelImportApi implements ToModel, WithChunkReading, ShouldQueue,WithHeadingRow
{
    public $user_auth;
    public function __construct($user_auth)
    {
        $this->user_auth = $user_auth;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use Importable ;
    public function model(array $row)
    {
        // $user_auth =  auth('sanctum')->user();
        // dd($user_auth);
        $business_default = BusinessModel::where('enable_stock' , 1)->first();
        $user = UsersModel::where('user_name' , $row['username'])->first();
        $business = BusinessModel::where('name' , $row['business'])->first();
        $invoice = InvoiceModel::where('model_number' , $row['model_number'])->first();
        $customer = DB::table('customers')->where('name' , $row['customer'])->first();
        if(empty($invoice))
        {    
        return new InvoiceModel([
                'title' => $row['title'],
                'model_number' => $row['model_number'],
                'user_id' => $user->id ?? $this->user_auth,
                'business_id' => $business->uid ?? $business_default->uid,
                'type' => $row['type'],
                'number' =>  $row['number'] ?? date('Y'). str_pad(get_invoice_number(1), 2, '0', STR_PAD_LEFT),
                'date' => $row['date'] ?? date('Y-m-d-h-m-s'),
                'discount' => $row['discount'] ?? 0,
                'payment_due' => $row['payment_deu'] ?? "",
                'sub_total' => $row['sub_total'] ?? "",
                'customer' => $customer->id,
                'grand_total' => $row['grand_total'] ?? "",
                'convert_total' => $row['convert_total'] ?? "",
                'status' => $row['status'] ?? "1",
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
                'created_at' => date('Y-m-d-h-m-s'),
            ]);
        }
        //     if($item){
        //         // $invoice_id = InvoiceModel::where('model_number' , $row['model_number'])->first();
        //         $invoice_id = InvoiceModel::latest()->where('model_number' , $row['model_number'])->first();
                
        //         new Invoice_Items([
        //             'invoice_id' => $invoice_id->id,
        //             'item' => $item->id,
        //             'qty' => $row['quantity'],
        //             'price' => ($item->price), 
        //             'discount' => $row['discount'] ?? 0, 
        //             'total' => ($item->price * $row['quantity']) - $row['discount'], 
        //             'type' => $row['type']
        //         ]);
        // }
    // }

    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
