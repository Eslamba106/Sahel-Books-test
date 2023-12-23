<?php

namespace App\Requests\Invoice;

use App\Requests\BaseRequestFormApi;

class ImportInvoiceValidator extends BaseRequestFormApi
{
    public function rules():array
    {
        return [
            'file' => 'required|mimes:csv,xlsx|max:8162',
            // 'title'  => 'required|max:50',
            // 'user_id'  => 'required|number|max:50',
            // 'business_id'  => 'required|number|max:50',
            // 'discount'  => 'number|max:50',
            // 'sub_total'  => 'required|number|max:50',
            // 'grand_total'  => 'required|number|max:50',

        ];
    }

    public function authorized(): bool
    {
        return true;
    }
}