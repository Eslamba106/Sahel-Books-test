<?php 

namespace App\Services\Invoices;

use Stripe\Invoice;
use App\Models\InvoiceModel;
use Illuminate\Support\Facades\DB;
use App\Repositories\Invoices\InvoicesRepository;
use App\Services\AbstractService;

class InvoiceServices extends AbstractService
{
    protected $repo;
    public function __construct(InvoicesRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }
    public function getAllwithparameter($business_id , $status){
        return $this->repo->getAll()->where('business_id' , $business_id)->where('status' , $status);
    }

    // public function store($data){

    // };
    // public function get_invoices($business_id = 24305 , $status){
    //     $data = array();
    //     if($status != null){

    //         $data = InvoiceModel::where('business_id', $business_id)->where('status' , $status)->get();
    //     }else{
    //         $data = InvoiceModel::where('business_id', $business_id)->get();

    //     }
    //     return $data ;
    // }

}




