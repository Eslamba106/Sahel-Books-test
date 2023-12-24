<?php

namespace App\Http\Controllers\Api\admin\invoice;

use App\Models\InvoiceModel;
use Illuminate\Http\Request;
use App\Models\BusinessModel;
use App\Models\ProductsModel;
use App\Jobs\ImportExcelInvoices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InvoiceModelImportApi;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Services\Invoices\InvoiceServices;
use App\Requests\Invoice\ImportInvoiceValidator;
// use Illuminate\Bus\Batchable;


class InvoiceApiController extends Controller
{

    public $invoice_service;

    public function __construct(InvoiceServices $invoice_service)
    {
        $this->invoice_service = $invoice_service;
    }
    public function index(Request $request)
    {
        $user =  auth('sanctum')->user()->id  ;
        $business = BusinessModel::where('user_id' , $user)->first();
        return response()->apiSuccess($this->invoice_service->get_all_with_parameter($request->business_id ?? $business->uid , $request->status));
    }

    public function show($id){
        return response()->apiSuccess($this->invoice_service->show($id));
    }
    public function store(Request $request){
        $user =  auth('sanctum')->user()->id  ;
        $business = BusinessModel::where('user_id' , $user)->first();

        foreach($request->invoices as $invoice){
            $invoices_item = [
                'user_id' => $user,
                'business_id' => $invoice['business_id'] ?? $business->uid,
                'title' => $invoice['title'],
                'type' => $invoice['type'] ?? 1,
                'recurring' => $invoice['recurring'] ?? "",
                'summary' => $invoice['summary'] ?? "",
                'number' => $invoice['number'] ?? "",
                'poso_number' => $invoice['poso_number'] ?? "",
                'customer' => $invoice['customer'] ?? "",
                'date' => $invoice['date'] ?? date('Y-m-d-h-m-s'),
                'discount' => $invoice['discount'] ?? 0,
                'payment_due' => $invoice['payment_due'] ?? "",
                'footer_note' => $invoice['footer_note'] ?? "",
                'sub_total' => $invoice['sub_total'] ?? "",
                'grand_total' => $invoice['grand_total'] ?? "",
                'convert_total' => $invoice['convert_total'] ?? "",
                'status' => $invoice['status'] ?? "",
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
            ];
            $invoices[] = $invoices_item ;
            $invoice_store = $this->invoice_service->store($invoices_item);
            if (!empty($invoice['items'])) {
                for($i=0 ; $i < count($invoice['items']) ; $i++){
                    $product[] = ProductsModel::where('id' , $invoice['items'][$i]['id'])->first(); 
                }
                $item_data = [];
                for ($i = 0; $i < count($invoice['items']); $i++) {
                    if(empty($product[$i]->id)){
                        continue;
                    }else{
                        $item_data[$i] = array(
                            'invoice_id' => $invoice_store->id,
                            'item' => $product[$i]->id,
                            'price' => $invoice['items'][$i]['price'] ?? $product[$i]->price,
                            'qty' =>$invoice['items'][$i]['qty'] ?? 1 ,
                            'discount' => $invoice['items'][$i]['discount'] ?? $product[$i]->discount_item ?? null,
                            'total' => $invoice['items'][$i]->total_price ?? $invoice['items'][$i]['price']
                        );
                        helper_insert($item_data[$i], 'invoice_items');
                    }
            }
        }
        }
        return response()->apiSuccess($invoices);

        
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
        if($invoice->count() == 0){
            return response()->apiFail('the invoice is not exists' , 404);
        }
        else
        {
            $invoice = InvoiceModel::withTrashed()->where('id' , $id)->restore();
            return response()->apiSuccess( null , 'the invoice restore' , 200);
        }
    }
    public function import(Request $request){
        // if(!empty($importInvoiceValidator->getErrors())){
        //     return response()->json($importInvoiceValidator->getErrors() , 406);
        // }
        // return auth('sanctum')->user()->id;
        $filename = $request->file('file');
        // dd($filename);
        Excel::queueImport(new InvoiceModelImportApi(), $filename);
        // Excel::import(new InvoiceModelImportApi(), $filename);
        // (new InvoiceModelImportApi())->queue($filename);
        // Excel::import(new InvoiceModelImportApi() , $importInvoiceValidator->request()->file('file')->store('files'));
        return response()->apiSuccess(null , "Invoices Saved");
    }
    // public function import(ImportInvoiceValidator $importInvoiceValidator){
    //     if(!empty($importInvoiceValidator->getErrors())){
    //         return response()->json($importInvoiceValidator->getErrors() , 406);
    //     }
    //     $filename = $importInvoiceValidator->request()->file('file');
    //     Excel::queueImport(new InvoiceModelImportApi(), $filename);

    //     // Excel::import(new InvoiceModelImportApi() , $importInvoiceValidator->request()->file('file')->store('files'));
    //     return response()->apiSuccess(null , "Invoices Saved");
    // }
    // public function import(Request $request){
    //     // if(!empty($importInvoiceValidator->getErrors())){
    //     //     return response()->json($importInvoiceValidator->getErrors() , 406);
    //     // }
    //     // dd($request->file) ;
    //     if($request->has('file')){
    //         $xlsx = $request->file('file');
    //         // dd(gettype($xlsx));
    //     // $chunks = array_chunk((array)$xlsx , 500);
    //     // dd($chunks);        }
    //     // return "error";
    //     $header = [];
    //     $xlsx = file($request->xlsx);
    //     Excel::queueImport(new ImportExcelInvoices($xlsx , $header), $xlsx);
    //     }
    //     // $filename = $xlsx->getClientOriginalName();
    //     // $chunks = array_chunk($xlsx , 500);
    //     // dd($chunks);
    //     // $header = [];
    //     // $batch = Bus::batch([])->dispatch();
    //     // foreach($chunks as $chunk){
    //     //     $data = array_map('str_getcsv' , $chunk);
    //     //     $batch->add(new ImportExcelInvoices($data ,$header));
    //     // }
    //     // return "finish";
    //     // if($request->has('xlsx')){
    //     //     $xlsx = file($request->xlsx);
    //     //     return $xlsx ;
    //     // }
    //     // $data_imported = $importInvoiceValidator->request()->file('file');
    //     // ImportExcelInvoices::dispatch($data_imported);
    //     // Excel::import(new InvoiceModelImportApi() , $importInvoiceValidator->request()->file('file')->store('files'));
    //     // return response()->apiSuccess(null , "Invoices Saved");
    // }
    public function export(){}

}
