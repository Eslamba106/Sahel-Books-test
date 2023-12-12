<?php 

namespace App\Services\Invoices;

use App\Models\InvoiceModel;
use Illuminate\Support\Facades\DB;



class InvoiceServices
{
    // public function get_invoices(){
    //     return InvoiceModel::all();
    // }
    public function get_invoices($business_id , $status){
        $data = array();
        $data['invoices'] = InvoiceModel::where('business_id', $business_id)->where('status' , $status)->get();
        // dd($data['invoices']);
        $data['customers'] = DB::table('customers')
        ->where('business_id', $business_id)
        ->where('user_id', $data['invoices'][0]['user_id'])
        ->orderBy('id', 'desc')
        ->get();
        return $data ;
    }

}
