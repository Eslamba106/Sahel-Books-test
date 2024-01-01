<?php

namespace App\Imports;

use App\Models\TaxModel;
use App\Models\InvoiceModel;
use App\Models\ProductsModel;
use App\Models\Invoice_Items_Taxes;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class InvoiceItemsTaxesImportApi implements ToModel,WithChunkReading, ShouldQueue ,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    { 
        $invoice = InvoiceModel::where('model_number' , $row['model_number'])->first();
        $item = ProductsModel::where('name' , $row['item_name'])->first();
        $tax = TaxModel::where('name' , $row['tax_name'])->first();
        if($invoice)
        {
            if($tax){
                return new Invoice_Items_Taxes([
                    'invoice_id' => $invoice->id,
                    'invoice_item_id' =>$item->id, 
                    'tax_id' => $tax->id, 
                    'tax_type' => $tax->type, 
                    'tax_rate' => $tax->rate, 
                    'tax_value' => $tax->rate * ($item->price * $item->qty)
            ]);
    }}
    }
    public function chunkSize(): int
    {
        return 1000;
    }
}
