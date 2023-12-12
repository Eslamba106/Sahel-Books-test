<?php

namespace App\Exports;

use App\Models\InvoiceModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Stripe\Invoice;

class InvoiceModelExport implements FromArray
{
    /**
    * @return \Illuminate\Support\array
    */
    public function array(): array
    {
        
        $list = [];
        // $invoices = InvoiceModel::all();
        // $invoices = DB::table('invoice')->get();
        $invoices = InvoiceModel::all();
        // foreach($invoices as $invoice){
        //     $invoice->item= [] ;
        // }
        // dd($invoices);
        // dd($invoices);
        // $invoices = DB::table('invoice as i')
        // ->join('invoice_items as ii' , 'ii.invoice_id' , '=' , 'i.id')
        // ->join('products as p' , 'p.id' , '=' ,'ii.item')
        // ->select('i.*' , 'ii.item' , 'p.name as name')
        // ->get();
        // $invoices->item = "eslam";
           
        // for($j = 0; $j<count($invoices);$j++){
            $itemm = [];
            foreach($invoices as $invoice){  
            $invoice->item = [];          
            $invoice->item = (array)DB::table('invoice_items as ii')->where('invoice_id' , $invoice->id)
            ->join('invoice as i' , 'i.id' , '=' , 'ii.invoice_id')
            ->join('products as p' , 'p.id' , '=' ,'ii.item')
            ->select('i.*' , 'ii.*' , 'p.*')->get();

            // dd($itemm);
            // $invoice->item =[]; 
            // $itemss = $invoice->item ; 
            // for($i=0 ; $i < count($itemm) ; $i++){

            //     $invoice->item = $itemm[$i];
            // }
            $list[] =
            [
                $invoice->title,
                $invoice->user_id,
                $invoice->business_id,
                $invoice->type,
                $invoice->recurring,
                $invoice->parent_id,
                $invoice->summary,
                $invoice->number,
                $invoice->poso_number,
                $invoice->customer,
                $invoice->date,
                $invoice->discount,
                $invoice->payment_due,
                $invoice->expire_on,
                $invoice->due_limit,
                $invoice->footer_note,
                $invoice->sub_total,
                $invoice->grand_total,
                $invoice->convert_total,
                $invoice->status,
                $invoice->reject_reason,
                $invoice->client_action_date,
                $invoice->is_sent,
                $invoice->is_completed,
                $invoice->sent_date,
                $invoice->recurring_start,
                $invoice->recurring_end,
                $invoice->frequency,
                $invoice->next_payment,
                $invoice->frequency_count,
                $invoice->auto_send,
                $invoice->send_myself,
                $invoice->created_at,
                $invoice->total_taxes,
                $invoice->modified_at,
                $invoice->send_status,
                $invoice->send_id,
                $invoice->paymentToken,
                $invoice->puid,
                $invoice->total_tax_items,
                $invoice->to_invoice_id,
                $invoice->credits_used,
                $invoice->remaining_amount,
                $invoice->to_invoice_number,
                $invoice->invoice_type,
                $invoice->model_id,
                $invoice->model_number,
                $invoice->total_discounts,
                $invoice->convert_sub_total,
                $invoice->shipping_cost,
                $invoice->shipping_tax,
                $invoice->shipping_tax_percent,
                $invoice->inv_tax,
                $invoice->inv_discount,
                $invoice->tax_type,
                $invoice->added_by,
                $invoice->shipping_company,
                $invoice->api_payment_method_name,
                $invoice->api_status_name,
                $invoice->api_status_code,
                $invoice->api_payment_method,
                $invoice->change_return,

                $invoice->item,
            ];

            dd($list);
        }
        // dd($list);
        return ($list) ;
    
}
}
