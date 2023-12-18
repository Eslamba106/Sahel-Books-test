<?php

namespace App\Http\Controllers\Api\admin\invoice;

use App\Models\InvoiceModel;
use Illuminate\Http\Request;
use App\Models\ProductsModel;
use Illuminate\Support\Facades\DB;
use App\Exports\InvoiceModelExport;
use App\Models\Invoice_Items_Taxes;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Services\Invoices\InvoiceServices;
use Predis\Command\Traits\BloomFilters\Items;
use App\Http\Controllers\Api\Base\BaseController;

class InvoiceApiController extends Controller
{

    public $invoice_service;

    public function __construct(InvoiceServices $invoice_service)
    {
        $this->invoice_service = $invoice_service;
    }
    public function index(Request $request)
    {
        return response()->apiSuccess($this->invoice_service->getAllwithparameter($request->business_id ?? 24305 , $request->status));
    }

    public function show($id){
        return response()->apiSuccess($this->invoice_service->show($id));
    }
    public function store(Request $request){
        $user =  auth('sanctum')->user()->id  ;
        $items = $request->input('items');

        $data['invoices'] = $request->only([
            'user_id' => $user,
            'business_id' => $request->business_id,
            'title' => $request->title,
            'type' => $request->type,
            'recurring' => $request->recurring,
            'summary' => $request->summary,
            'number' => $request->number,
            'poso_number' => $request->poso_number,
            'customer' => $request->customer,
            'date' => $request->date,
            'discount' => $request->discount,
            'payment_due' => $request->payment_due,
            'footer_note' => $request->footer_note,
            'sub_total' => $request->sub_total,
            'grand_total' => $request->grand_total,
            'convert_total' => $request->convert_total,
            'status' => $request->status,
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
            'created_at' => date('Y-m-d-h-m-s'),
        ]);
        $this->invoice_service->store($data['invoices']);
        $invoice_id = InvoiceModel::latest()->first()->id;
        if (!empty($items)) {
            for($i=0 ; $i < count($items) ; $i++){
                $product[] = ProductsModel::where('id' , $items[$i]['id'])->first(); 
            }
            $item_data = [];
            for ($i = 0; $i < count($product); $i++) {
                if(empty($product[$i]->id)){
                    continue;
                }else{
                    $item_data[$i] = array(
                        'invoice_id' => $invoice_id,
                        'item' => $product[$i]->id,
                        'price' => $items[$i]['price'] ?? $product[$i]->price,
                        'qty' =>$items[$i]['qty'] ?? 1 ,
                        'discount' => $items[$i]['discount'] ?? $product[$i]->discount_item ?? null,
                        'total' => $product[$i]->total_price
                    );
                    helper_insert($item_data[$i], 'invoice_items');
                }
        }}

        $data['items'] = $item_data;
        if(empty($data['invoices'])){
            return response()->apiFail();
        }
        elseif($data['invoices']){
            return response()->apiSuccess($data);
        }
        
    }
    public function store_invoice_items(Request $request){
        $invoice_id = InvoiceModel::latest()->first()->id;
        // return $invoice_id;
        // $invoice_item_taxes_data = array(
        //     'tax_id' => $taxes_item_data->id ,
        //     'invoice_id' => $id,
        //     'invoice_item_id' => $items[$j],
        //     'tax_rate' => $tax_rate[$j]*100,
        //     'tax_type' => $taxes_item_data->type,
        //     'tax_value' => $tax_rate[$j] * $price[$j],
        // );
    }
    public function update(Request $request){
        //get invoice and invoice items and user id
        $invoice = InvoiceModel::find($request->id);
        $request_items = $request->input('items');
        $user = auth('sanctum')->user()->id  ;

        // if invoice exists
        if($invoice){
            $data = $request->only([
                'user_id' => $user,
                'business_id' => $request->business_id ,
                'title' => $request->title,
                'type' => $request->type,
                'recurring' => $request->recurring,
                'summary' => $request->summary,
                'number' => $request->number,
                'poso_number' => $request->poso_number,
                'customer' => $request->customer,
                'date' => $request->date,
                'discount' => $request->discount,
                'payment_due' => $request->payment_due,
                'footer_note' => $request->footer_note,
                'sub_total' => $request->sub_total,
                'grand_total' => $request->grand_total,
                'convert_total' => $request->convert_total,
                'status' => $request->status,
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
                'created_at' => date('Y-m-d-h-m-s'),
                'updated_at' => date('Y-m-d-h-m-s'),
            ]);
            // function update invoice in invoice table
            $this->invoice_service->update($data, $invoice);  
            // get invoice items from invoice_items table
            $invoice_items = DB::table('invoice_items')->where('invoice_id' , $invoice->id)->get();
            // send request->item and invoice items to update in function update_invoice_items
            $invoice_items_updated = $this->invoice_service->update_invoice_items($invoice_items , $request_items);
            // send response
            $data_invoice_update['invoice'] = $invoice ;
            $data_invoice_update['invoice']['items'] = $invoice_items_updated ;
            return response()->apiSuccess($data_invoice_update , "success");
        }
        return response()->apiSuccess("invoice not found");
    }
    public function destroy($id)
    {
        $invoice = InvoiceModel::find($id);
        // dd($invoice);
        if($invoice){
            InvoiceModel::destroy($id);
            return response()->apiSuccess(null , 'the invoice deleted' , 200);
        }
        return response()->apiFail('the invoice is not exists' , 404);

    }
    public function archive(){
        $invoices = InvoiceModel::onlyTrashed()->get();
        if($invoices->count() != 0){
            return response()->apiSuccess( $invoices);
        }
        return response()->apiFail('there is no invoice in archive' , 404);
    }
    public function restore_invoice($id){
        $invoice = InvoiceModel::withTrashed()->where('id' , $id)->get();
        // dd($invoice->count());
        if($invoice->count() == 0){
            return response()->apiFail('the invoice is not exists' , 404);
        }
        else{
            $invoice = InvoiceModel::withTrashed()->where('id' , $id)->restore();
            return response()->apiSuccess( null , 'the invoice restore' , 200);
        }
    }

}
