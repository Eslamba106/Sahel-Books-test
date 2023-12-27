<?php

namespace App\Imports;

use App\Models\TaxModel;
use App\Models\InvoiceModel;
use App\Models\Invoice_Items;
use App\Models\ProductsModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class InvoiceItemsImportApi implements ToModel,WithChunkReading, ShouldQueue ,WithHeadingRow
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
        $tax_value = 0 ;

        if($tax){
            $tax_value = (($item->price * $row['quantity']) * $tax->rate);
        }

        return new Invoice_Items([
            'invoice_id' => $invoice->id,
            'item' => $item->id,
            'qty' => $row['quantity'],
            'price' => ($item->price), 
            'discount' => $row['item_discount'] ?? 0, 
            'total' => (($item->price * $row['quantity']) + $tax_value) - $row['item_discount'], 
            'type' => $row['type']
        ]);
    
}
    public function chunkSize(): int
    {
        return 1000;
    }
}
