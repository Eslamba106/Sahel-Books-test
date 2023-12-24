<?php

namespace App\Jobs;

use App\Models\InvoiceModel;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use App\Imports\InvoiceModelImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InvoiceModelImportApi;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ImportExcelInvoices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels ,Batchable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    // public $data ;
    // public $header ;
    public function __construct()
    {
        // $this->data = $data ;$data , $header
        // $this->header =$header ;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // foreach($this->data as $inovice)
        // $invoice_input = array_combine($this->header , $inovice);
        // InvoiceModel::create($inovice);
        // $request_file = $this->importInvoiceValidator->store('files') ;
        // Excel::queueImport(new InvoiceModelImportApi() , $request_file);
    }
}
