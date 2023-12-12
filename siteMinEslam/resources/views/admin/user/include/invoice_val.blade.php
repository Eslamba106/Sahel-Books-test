<?php if (isset($page_title) && $page_title == 'Invoice Preview') {
    $status = 1;
    $logo = helper_get_business()->logo;
    $color = helper_get_business()->color;
    $business_name = helper_get_business()->name;
    $business_address = helper_get_business()->address;
    $country = helper_get_business()->country;

    if (empty(session('customer'))) {
        $currency_symbol = helper_get_business()->currency_symbol;
    } else {
        $currency_symbol = helper_get_customer(session('customer'))->currency_symbol;
        if (isset($currency_symbol)) {
            $currency_symbol = $currency_symbol;
        } else {
            $currency_symbol = helper_get_business()->currency_symbol;
        }
    }

    $biz_number = helper_get_business()->biz_number;
    $biz_vat_code = helper_get_business()->vat_code;

    $cus_number = helper_get_customer(session('customer'))->cus_number;
    $cus_vat_code = helper_get_customer(session('customer'))->vat_code;

    $title = session('title');
    $summary = session('summary');
    $customer_id = session('customer');
    $number = session('number');
    $date = session('date');
    $poso_number = session('poso_number');
    $payment_due = session('payment_due');
    $due_limit = session('due_limit');
    $sub_total = session('sub_total');
    $taxes = session('taxes');
    $discount = session('discount');
    $grand_total = session('grand_total');
    $footer_note = session('footer_note');
    $amount_due = '';
    $view_type = 'preview';
} else {

    $taxes = get_invoice_taxes($invoice->id);

    if (isset(helper_get_business()->color)) {
        $color = helper_get_business()->color;
    } else {
        $color = '#2568ef';
    }

    $status = $invoice->status;
    $logo = $invoice->logo;
    $color = $color;
    $business_name = $invoice->business_name;
    $business_address = $invoice->business_address;
    $country = $invoice->country;

    if ($invoice->type == 3) {
        $currency_symbol = $invoice->currency_symbol;
    } else {
        $currency_symbol = helper_get_customer($invoice->customer)->currency_symbol;
    }


    if (isset($currency_symbol)) {
        $currency_symbol = $currency_symbol;
    } else {
        $currency_symbol = $invoice->currency_symbol;
    }

    $biz_number = $invoice->biz_number;
    $biz_vat_code = $invoice->vat_code;

    $cus_number = helper_get_customer($invoice->customer)->cus_number;
    $cus_vat_code = helper_get_customer($invoice->customer)->vat_code;

    $title = $invoice->title;
    $summary = $invoice->summary;
    $customer_id = $invoice->customer;
    $number = $invoice->number;
    $date = $invoice->date;
    $poso_number = $invoice->poso_number;
    $payment_due = $invoice->payment_due;
    $due_limit = $invoice->due_limit;
    $sub_total = $invoice->sub_total;
    $taxes = $taxes;
    $discount = $invoice->discount;
    $grand_total = $invoice->grand_total;
    $footer_note = $invoice->footer_note;
    $view_type = 'live';
    $amount_due = $invoice->grand_total - get_total_invoice_payments($invoice->id, $invoice->parent_id);
}
