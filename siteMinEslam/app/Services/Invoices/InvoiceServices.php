<?php 

namespace App\Services\Invoices;

use Stripe\Invoice;
use App\Models\InvoiceModel;
use App\Services\AbstractService;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InvoiceItemsImportApi;
use App\Imports\InvoiceModelImportApi;
use App\Imports\InvoiceItemsTaxesImportApi;
use App\Repositories\Invoices\InvoicesRepository;

class InvoiceServices extends AbstractService
{
    protected $repo;
    public function __construct(InvoicesRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }
    public function get_all_with_parameter($business_id , $status){
        return $this->repo->getAll()->where('business_id' , $business_id)->where('status' , $status);
    }

    public function update_invoice_items($invoice_items , $request_items ){
        if (!$request_items == null) {
            for($i=0 ; $i < count($request_items) ; $i++){
                if($request_items[$i]['id'] == $invoice_items[$i]->id){
                    $item_data[$i] = array(
                    'item' =>     $request_items[$i]['id'],
                    'price' =>    $request_items[$i]['price'] ?? $invoice_items[$i]->price ,
                    'qty' =>      $request_items[$i]['quantity'] ?? $invoice_items[$i]->qty ,
                    'discount' => $request_items[$i]['discount'] ?? $invoice_items[$i]->discount ?? null,
                    );
                    DB::table('invoice_items')->where('id' , $request_items[$i]['id'])->update($item_data[$i]);
                }
            }
            $data_updated = $item_data;
            return $data_updated;
            }
            // else
            // {
            //     return "eslam";
            //     // return response()->apiSuccess($data_updated);
            // }
    }
    public function import_invoice($filename , $user_auth){
        Excel::queueImport(new InvoiceModelImportApi($user_auth->id), $filename);

    }
    public function import_invoice_items($filename){
        Excel::queueImport(new InvoiceItemsImportApi(), $filename);
    }
    public function import_invoice_item_taxes($filename){
        Excel::queueImport(new InvoiceItemsTaxesImportApi(), $filename);
    }

}




