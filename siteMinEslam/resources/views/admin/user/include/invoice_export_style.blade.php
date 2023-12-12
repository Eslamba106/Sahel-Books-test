<?php if (isset($page_title) && $page_title == 'Invoice Preview') {
    $status = 1;
    $logo = helper_get_business()->logo;
    $color = helper_get_business()->color;
    $business_name = helper_get_business()->name;
    $business_address = helper_get_business()->address;
    $country = helper_get_business()->country;

    $cus_number = '';
    $cus_vat_code = '';
    if (empty(session('customer'))) {
        $currency_symbol = helper_get_business()->currency_symbol;
    } else {
        if (!empty(helper_get_customer(session('customer')))) {
            $currency_symbol = helper_get_customer(session('customer'))->currency_symbol;
            $cus_number = helper_get_customer(session('customer'))->cus_number;
            $cus_vat_code = helper_get_customer(session('customer'))->vat_code;
        } else {
            $currency_symbol = helper_get_business()->currency_symbol;
        }
    }

    $biz_number = helper_get_business()->biz_number;
    $biz_vat_code = helper_get_business()->vat_code;

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
        $currency_symbol = helper_get_business()->currency_symbol;
        $cus_number = '';
        $cus_vat_code = '';
    } else {
        $currency_symbol = helper_get_customer($invoice->customer)->currency_symbol;
        $cus_number = helper_get_customer($invoice->customer)->cus_number;
        $cus_vat_code = helper_get_customer($invoice->customer)->vat_code;
    }

    if (isset($currency_symbol)) {
        $currency_symbol = $currency_symbol;
    } else {
        $currency_symbol = helper_get_business()->currency_symbol;
    }

    $biz_number = $invoice->biz_number;
    $biz_vat_code = $invoice->vat_code;

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
?>

<div class="card-body p-0">
    <div class="flex-parent-between">
        <div class="col-md-6s text-left">
            <img width="130px" src="<?php echo url($logo ?? ''); ?>" alt="" style="padding-top: 50px;">
        </div>
        <div class="col-md-6s text-right" style="margin-top: -25px">
            <p class="font-weight-bold mb-0"><?php echo html_escape($title); ?></p>
            <p class="text-muted mb-0"><?= strip_tags($summary) ?></p>
            <p class="mb-0"><?= $business_name ?></p>
            <p class="mt-0 mb-0 redup"><?= strip_tags($business_address) ?></p>
            <?php if (!empty($biz_number)) : ?>
            <p class="mb-0"><?php echo helper_trans('business') . ' ' . helper_trans('number'); ?>: <?php echo html_escape($biz_number); ?></p>
            <?php endif ?>
            <?php if (!empty($biz_vat_code)) : ?>
            <p class="mb-0"><?php echo helper_trans('tax') . '/vat ' . helper_trans('number'); ?>: <?php echo html_escape($biz_vat_code); ?></p>
            <?php endif ?>
            <p class="mb-0"><?php echo html_escape($country); ?></p>
        </div>
    </div>



    <div class="row bill_area" style="margin-top:40px; margin-bottom: 30px">
        <div class="col-md-8" style="width: 75%; float: left;">

            <?php if (isset($page) && $page == 'Bill') : ?>
            <h5 class="font-weight-bold"><?php echo helper_trans('purchase-from'); ?></h5>
            <?php else : ?>
            <h5 class="font-weight-bold"><?php echo helper_trans('bill-to'); ?></h5>
            <?php endif ?>

            <?php if (empty($customer_id)) : ?>
            <p class="mb-1"><?php echo helper_trans('empty-customer'); ?></p>
            <?php else : ?>
            <p class="mb-1">
                <?php if (isset($page) && $page == 'Bill') : ?>
                <?php if (!empty(helper_get_vendor($customer_id))) : ?>
            <p class="mb-0"><strong><?php echo helper_get_vendor($customer_id)->name; ?></strong></p>
            <p class="mt-0 mb-0"><?php echo helper_get_vendor($customer_id)->address; ?></p>
            <p class="mt-0 mb-0"><?php echo helper_get_vendor($customer_id)->phone; ?></p>
            <p class="mt-0 mb-0"><?php echo helper_get_vendor($customer_id)->email; ?></p>
            <?php endif ?>
            <?php else : ?>

            <?php if (!empty(helper_get_customer($customer_id))) : ?>
            <p class="mb-0"><strong><?php echo helper_get_customer($customer_id)->name; ?></strong></p>
            <p class="mt-0 mb-0"><?php echo helper_get_customer($customer_id)->address; ?> <?php echo helper_get_customer($customer_id)->country; ?></p>
            <p class="mt-0 mb-0"><?php echo helper_get_customer($customer_id)->phone; ?></p>

            <?php if (!empty($cus_number)) : ?>
            <p class="mt-0 mb-0"><?php echo helper_trans('business') . ' ' . helper_trans('number'); ?>: <?php echo html_escape($cus_number); ?></p>
            <?php endif ?>

            <?php if (!empty($cus_vat_code)) : ?>
            <p class="mt-0"><?php echo helper_trans('tax') . '/vat ' . helper_trans('number'); ?>: <?php echo html_escape($cus_vat_code); ?></p>
            <?php endif ?>

            <?php endif ?>
            <?php endif ?>
            </p>
            <?php endif ?>
        </div>

        <div class="col-md-4 text-right" style="width: 25%; float: right; ">
            <table class="tables pull-right" style="margin-top: -70px;">
                <tr>
                    <td class="text-right"><b><?php if (isset($page) && $page == 'Invoice') {
                        echo helper_trans('invoice-number');
                    } elseif ($page == 'Estimate') {
                        echo helper_trans('estimate-number');
                    } else {
                        echo helper_trans('bill-number');
                    } ?>:</b></td>
                    <td class="text-right" colspan="1"><?php echo html_escape($number); ?></td>
                </tr>
                <tr>
                    <td><b><?php if (isset($page) && $page == 'Invoice') {
                        echo helper_trans('invoice-date');
                    } else {
                        echo helper_trans('date');
                    } ?>:</b></td>
                    <td class="text-right" colspan="1"><?php echo my_date_show($date); ?></td>
                </tr>

                <?php if (!empty($payment_due)) : ?>
                <tr>
                    <td class="text-right"><b><?php echo helper_trans('due-date'); ?>:</b></td>
                    <td class="text-right">
                        <?php echo my_date_show($payment_due); ?>
                    </td>
                </tr>
                <?php endif ?>

                <tr>
                    <td></td>
                    <td class="text-right">
                        <?php if ($due_limit == 1) : ?>
                        <p><?php echo helper_trans('on-receipt'); ?></p>
                        <?php else : ?>
                        <p><?php echo helper_trans('within'); ?> <?php echo html_escape($due_limit); ?> <?php echo helper_trans('days'); ?></p>
                        <?php endif ?>
                    </td>
                </tr>
            </table>

        </div>
    </div>

    <div class="row p-0 table_area">
        <div class="col-md-12">
            <table class="table">
                <thead class="pre_head">
                    <tr class="pre_head_tr" style="background: #f0f4fa !important">
                        <th class="border-0" style="color: #444 !important; background: #f0f4fa; font-weight: bold;">
                            <?php echo helper_trans('items'); ?></th>
                        <th class="border-0" style="color: #444 !important; background: #f0f4fa; font-weight: bold;">
                            <?php echo helper_trans('price'); ?></th>
                        <th class="border-0" style="color: #444 !important; background: #f0f4fa; font-weight: bold;">
                            <?php echo helper_trans('quantity'); ?></th>
                        <th class="border-0" style="color: #444 !important; background: #f0f4fa; font-weight: bold;">
                            <?php echo helper_trans('amount'); ?></th>
                    </tr>
                </thead>
                <tbody>

                    <?php if (isset($page_title) && $page_title == 'Invoice Preview') : ?>
                    <?php if (!empty(session('item'))) : ?>
                    <?php $total_items = count(session('item')); ?>
                    <?php else : ?>
                    <?php $total_items = 0; ?>
                    <?php endif ?>

                    <?php if (empty($total_items)) : ?>
                    <tr>
                        <td colspan="4" class="text-center"><?php echo helper_trans('empty-items'); ?></td>
                    </tr>
                    <?php else : ?>
                    <?php for ($i = 0; $i < $total_items; $i++) { ?>
                    <tr>
                        <td width="50%">
                            <?php $product_id = session('item')[$i]; ?>

                            <?php if (is_numeric($product_id)) {
                                echo helper_get_product($product_id)->name;
                            } else {
                                echo html_escape($product_id);
                            } ?>
                        </td>
                        <td><?php if (!empty($currency_symbol)) {
                            echo html_escape($currency_symbol);
                        } ?> <?php echo session('price')[$i]; ?></td>
                        <td><?php echo session('quantity')[$i]; ?></td>
                        <td><?php if (!empty($currency_symbol)) {
                            echo html_escape($currency_symbol);
                        } ?> <?php echo number_format(session('total_price')[$i], 2); ?></td>
                    </tr>
                    <?php } ?>
                    <?php endif ?>
                    <?php else : ?>
                    <?php $items = helper_get_invoice_items($invoice->id); ?>
                    <?php if (empty($items)) : ?>
                    <tr>
                        <td colspan="4" class="text-center"><?php echo helper_trans('empty-items'); ?></td>
                    </tr>
                    <?php else : ?>
                    <?php foreach ($items as $item) : ?>
                    <tr>
                        <td width="50%"><?php echo html_escape($item->item_name); ?></td>
                        <td><?php if (!empty($currency_symbol)) {
                            echo html_escape($currency_symbol);
                        } ?> <?php echo html_escape($item->price); ?></td>
                        <td><?php echo html_escape($item->qty); ?></td>
                        <td><?php if (!empty($currency_symbol)) {
                            echo html_escape($currency_symbol);
                        } ?> <?php echo number_format($item->total, 2); ?></td>
                    </tr>
                    <?php endforeach ?>
                    <?php endif ?>
                    <?php endif ?>

                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-right"><strong><?php echo helper_trans('sub-total'); ?></strong></td>
                        <td><span><?php if (!empty($currency_symbol)) {
                            echo html_escape($currency_symbol);
                        } ?> <?php echo html_escape($sub_total); ?></span></td>
                    </tr>
                    <?php if (!empty($tax)) : ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-right"><strong><?php echo helper_trans('tax'); ?> <?php echo html_escape($tax); ?>%</strong></td>
                        <td><span><?php if (!empty($currency_symbol)) {
                            echo html_escape($currency_symbol);
                        } ?><?php echo number_format($sub_total * ($tax / 100), 2); ?></span></td>
                    </tr>
                    <?php endif ?>
                    <?php if (!empty($discount)) : ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-right"><strong><?php echo helper_trans('discount'); ?> <?php echo html_escape($discount); ?>%</strong></td>
                        <td><span><?php if (!empty($currency_symbol)) {
                            echo html_escape($currency_symbol);
                        } ?><?php echo number_format($sub_total * ($discount / 100), 2); ?></span></td>
                    </tr>
                    <?php endif ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-right"><strong><?php echo helper_trans('grand-total'); ?></strong></td>
                        <td><span><?php if (!empty($currency_symbol)) {
                            echo html_escape($currency_symbol);
                        } ?> <?php echo html_escape($grand_total); ?></span></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-right" style="background: #f0f4fa; color: #333; font-weight: bold">
                            <strong><?php echo helper_trans('amount-due'); ?></strong>
                        </td>
                        <td class="" style="background: #f0f4fa; color: #333; font-weight: bold">
                            <span>
                                <?php if ($status == 2) : ?>
                                <?php if (!empty($currency_symbol)) {
                                    echo html_escape($currency_symbol);
                                } ?>0.00
                                <?php else : ?>
                                <?php if (!empty($currency_symbol)) {
                                    echo html_escape($currency_symbol);
                                } ?><?php echo $grand_total - get_total_invoice_payments($invoice->id, $invoice->parent_id); ?>
                                <?php endif ?>
                            </span>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    <div class="p-30">
        <p class="text-center"><?= $footer_note ?></p>
    </div>
</div>
