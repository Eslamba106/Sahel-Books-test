<?php

namespace App\Requests\Invoice;

use App\Requests\BaseRequestFormApi;

class CreateInvoiceValidator extends BaseRequestFormApi
{
    public function rules():array
    {
        return [
            'title'  => 'required|max:50',

        ];
    }

    public function authorized(): bool
    {
        return true;
    }
}