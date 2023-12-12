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
    <div class="row p-35">
        <div class="col-6">
            <?php if (empty($logo)) : ?>
            <span class="alterlogo"><?php echo $business_name; ?></span>
            <?php else : ?>
            <img width="130px" src="<?php echo url($logo); ?>" alt="Logo">
            <?php endif ?>
        </div>
        <div class="col-6 text-right">
            <p class="font-weight-bold mb-0"><?php echo html_escape($title); ?> <br><?php echo html_escape($summary); ?></p>
            <span class="mb-0 invbiz"><?php echo $business_address; ?></span>

            <?php if (!empty($biz_number)) : ?>
            <p class="mb-0"><?php echo helper_trans('business') . ' ' . helper_trans('number'); ?>: <?php echo html_escape($biz_number); ?></p>
            <?php endif ?>

            <?php if (!empty($biz_vat_code)) : ?>
            <p class="mb-0"><?php echo helper_trans('tax') . '/vat ' . helper_trans('number'); ?>: <?php echo html_escape($biz_vat_code); ?></p>
            <?php endif ?>
            <?php if (empty($taxes)) : ?>
            <?php foreach ($taxes as $tax) : ?>
            <?php if ($tax != 0) : ?>
            <p class="mb-0"><?php echo get_tax_id($tax)->name . '-' . get_tax_id($tax)->number; ?></p>
            <?php endif ?>
            <?php endforeach ?>
            <?php endif ?>
            <p class=""><?php echo html_escape($country); ?></p>

        </div>
    </div>
    {{-- <p>{{$invoice_items_data}}</p> --}}

    <hr class="my-5">
    {{-- @foreach ($invoice_items_data as $item) --}}
    {{-- @if ($item->name)
        @continue
    @else --}}
        {{-- {{$item->name}} --}}
    {{-- @endif --}}
    
    {{-- @endforeach --}}
    <div class="flex-parent-between bill_area">
        <div class="col-4s py-4 pl-30 pr-30">

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

        <div class="col-8s text-right py-4 pl-30 pr-30">
            <table class="tables">
                <tr>
                    <td><b class="mr-10"><?php if (isset($page) && $page == 'Invoice') {
                        echo helper_trans('invoice-number');
                    } elseif ($page == 'Estimate') {
                        echo helper_trans('estimate-number');
                    } else {
                        echo helper_trans('bill-number');
                    } ?>:</b></td>
                    <td class="text-left" colspan="1"><?php echo html_escape($number); ?></td>
                </tr>

                <tr>
                    <td><b class="mr-10"><?php if (isset($page) && $page == 'Invoice') {
                        echo helper_trans('invoice-date');
                    } else {
                        echo helper_trans('date');
                    } ?>:</b></td>
                    <td class="text-left" colspan="1"><?php echo my_date_show($date); ?></td>
                </tr>

                <?php if (!empty($poso_number)) : ?>
                <tr>
                    <td><b class="mr-10"><?php echo helper_trans('p.o.s.o.-number'); ?>:</b></td>
                    <td class="text-left" colspan="1"><?php echo html_escape($poso_number); ?></td>
                </tr>
                <?php endif ?>

                <?php if (isset($page) && $page == 'Invoice') : ?>
                <tr>
                    <td><b class="mr-10"><?php echo helper_trans('due-date'); ?>:</b></td>
                    <td class="text-left">
                        <?php echo my_date_show($payment_due); ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-left">
                        <?php if ($due_limit == 1) : ?>
                        <p><?php echo helper_trans('on-receipt'); ?></p>
                        <?php else : ?>
                        <p><?php echo helper_trans('within'); ?> <?php echo html_escape($due_limit); ?> <?php echo helper_trans('days'); ?></p>
                        <?php endif ?>
                    </td>
                </tr>
                <?php else : ?>
                <?php if ($invoice->expire_on != '0000-00-00') : ?>
                <tr>
                    <td><b class="mr-10"><?php echo helper_trans('expires-on'); ?>:</b></td>
                    <td class="text-left">
                        <?php echo my_date_show($invoice->expire_on); ?>
                    </td>
                </tr>
                <?php endif; ?>
                <?php endif; ?>

            </table>
        </div>
    </div>

    <div class="row p-0 table_area">
        <div class="col-12 table-responsive">
            <table class="table">
                <thead class="pre_head">
                    <tr class="pre_head_tr inv-pl30" style="background: <?php echo html_escape($color); ?>">
                        <?php $items = helper_get_invoice_items($invoice->id);?>
                        <th class="border-0"><?php echo helper_trans('items'); ?></th>
                        <th class="border-0"><?php echo helper_trans('price'); ?></th>
                        <th class="border-0"><?php echo helper_trans('quantity'); ?></th>
                        <th class="border-0 th_preview_tax"><?php echo helper_trans('tax'); ?></th>
                        <th class="border-0 th_preview_discount"><?php echo helper_trans('discount'); ?></th>
                        <th class="border-0"><?php echo helper_trans('amount'); ?></th>
                    </tr>
                </thead>
                <tbody>

                    <?php if (isset($page_title) && $page_title == 'Invoice Preview') : ?>
                    <?php if (!empty(session('item'))) : ?>
                    <?php $total_items = count(session('item')); ?>
                    <?php else : ?>
                    <?php $total_items = 0; ?>
                    <?php endif ?>

                    <?php if (empty($invoice_items_data)) : ?>
                    <tr>
                        <td colspan="5" class="text-center"><?php echo helper_trans('empty-items'); ?></td>
                    </tr>
                    <?php else : ?>
                    <?php for ($i = 0; $i < $total_items; $i++) { ?>
                    <tr class="inv-pl30">
                        <td width="50%">
                            <?php $product_id = session('item')[$i]; ?>

                            <?php 
                            if (is_numeric($product_id)) {
                                echo helper_get_product($product_id)->name . '<br> <small>' . helper_get_product($product_id)->details . '</small>';
                            } else {
                                echo html_escape($product_id);
                            } ?>
                        </td>
                        <td><?php if (!empty($currency_symbol)) {
                            echo html_escape($currency_symbol);
                        } ?><?php echo session('price')[$i]; ?></td>
                        <td><?php echo session('quantity')[$i]; ?></td>
                        <td>
                            <?php if (!empty($currency_symbol)) {
                                echo html_escape($currency_symbol);
                            } ?>
                            <?php echo session('total_price')[$i]; ?>

                        </td>
                    </tr>
                    <?php } ?>
                    <?php endif ?>
                    <?php else : ?>
                    <?php $items = helper_get_invoice_items($invoice->id);?>
                    <?php if (empty($invoice_items_data)) : ?>
                    <tr>
                        <td colspan="5" class="text-center"><?php echo helper_trans('empty-items'); ?></td>
                    </tr>
                    <?php else : ?>
                    <?php for($i = 0 ; $i < count($items) ; $i++) : ?>
                    <tr class="inv-pl30">
                        <td width="25%"><?php echo html_escape($items[$i]->item_name); ?> <br> <small>{{$items[$i]->details}}</small></td>
                        <td><?php if (!empty($currency_symbol)) {
                            echo html_escape($currency_symbol);
                        } ?><?php echo number_format($items[$i]->price, 2); ?></td>
                        <td><?php echo html_escape($items[$i]->qty); ?></td>
                        {{-- <td>{{html_escape($items[$i]->tax_value)}} </td> --}}
                        <td><?php if(isset($items[$i]->tax_value))echo html_escape($items[$i]->tax_value*100) ;?></td>
                        <td><?php if($items[$i]->discount != null)echo html_escape($items[$i]->discount*100).'%' ;else {
                            '';
                        }?></td>
                        <td><?php if (!empty($currency_symbol)) {
                            echo html_escape($currency_symbol);
                        } ?><?php echo number_format($items[$i]->total, 2); ?></td>
                    </tr>
                    <?php endfor ?>
                    <?php endif ?>
                    <?php endif ?>

                    <tr class="inv-pl30">
                        <td></td>
                        <td></td>
                        <td class="text-right"><strong><?php echo helper_trans('sub-total'); ?></strong></td>
                        <td><span><?php if (!empty($currency_symbol)) {
                            echo html_escape($currency_symbol);
                        } ?><?php echo number_format($sub_total, 2); ?></span></td>
                    </tr>

                    <?php if (!empty($taxes)) : ?>
                    {{-- <?php foreach ($taxes as $tax) : ?> --}}
                    {{-- <?php if ($tax != 0) : ?> --}}
                    <tr class="inv-pl30">
                        <td></td>
                        <td></td>
                        <td></td>
                        {{-- <td><?php echo $taxes ;?></td> --}}

                    </tr>
                    {{-- <?php endif ?> --}}
                    {{-- <?php endforeach ?> --}}
                    <?php endif ?>

                    <?php if (!empty($discount)) : ?>
                    <tr class="inv-pl30">
                        <td></td>
                        <td></td>
                        <td class="text-right"><strong><?php echo helper_trans('discount'); ?> <?php echo html_escape($discount); ?>%</strong></td>
                        <td><span><?php if (!empty($currency_symbol)) {
                            echo html_escape($currency_symbol);
                        } ?><?php echo number_format($sub_total * ($discount / 100), 2); ?></span></td>
                    </tr>
                    <?php endif ?>

                    <tr class="inv-pl30">
                        <td></td>
                        <td></td>
                        <td class="text-right"><strong><?php echo helper_trans('grand-total'); ?></strong></td>
                        <td><span><?php if (!empty($currency_symbol)) {
                            echo html_escape($currency_symbol);
                        } ?><?php echo number_format($grand_total, 2); ?></span></td>
                    </tr>
                    <tr class="inv-pl30">
                        <td></td>
                        <td></td>
                        <td class="text-right bg-mlight"><strong><?php echo helper_trans('amount-due'); ?></strong></td>
                        <td class="bg-mlight">
                            <span>
                                <?php if ($status == 2) : ?>
                                <?php if (!empty($currency_symbol)) {
                                    echo html_escape($currency_symbol);
                                } ?>0.00
                                <?php else : ?>
                                <?php if (isset($page_title) && $page_title == 'Invoice Preview') : ?>
                                <?php if (!empty($currency_symbol)) {
                                    echo html_escape($currency_symbol);
                                } ?><?php echo $grand_total; ?>
                                <?php else : ?>
                                <?php if (!empty($currency_symbol)) {
                                    echo html_escape($currency_symbol);
                                } ?><?php echo number_format($grand_total - get_total_invoice_payments($invoice->id, $invoice->parent_id), 2); ?>
                                <?php endif ?>
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
