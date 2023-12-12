<div class="content-wrapper">
    <?php $settings = get_settings(); ?>
    <?php $taxes_item_data = get_by_user('tax')->all();?>
{{-- @foreach ($taxes_item_data as $item)
    {{$item->id}}
@endforeach --}}
    <!-- Main content -->
    {{-- {{$taxes = get_taxes($invoice[0])}}
{{$taxes}} --}}
    <section class="content">
        <div class="container">
            <div class="col-md-10 m-auto">

                <?php if (empty(helper_get_business()->logo)) : ?>
                @include('admin.user.include.setup_alert')
                <?php endif ?>

                <form id="invoice_form" method="post" enctype="multipart/form-data" class="validate-form leave_con"
                    action="<?php echo url('admin/invoice/preview'); ?>" role="form" novalidate>
                    @csrf

                    @include('admin.user.include.invoice_header')

                    <input type="hidden" class="add_val" name="add_val" value="">

                    <div class="alert alert-danger mb-20 error_area" style="display: none;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <h4><?php echo helper_trans('invoice-error-create'); ?>.</h4>
                        <div id="load_error"></div>
                    </div>

                    <!-- load preview data -->
                    <div id="load_data"> </div>

                    <div class="invoice_area mt-20">

                        <?php if (isset($page_title) && $page_title == 'Edit Invoice' && $invoice[0]['recurring'] == 1) : ?>
                        <div class="row mb-20">
                            <div class="col-md-12">
                                <h4 class="mb-20"><?php echo helper_trans('invoice-schedule'); ?></h4>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?php echo helper_trans('start-date'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control datepicker" value="<?php echo html_escape($invoice[0]['recurring_start']); ?>"
                                        name="recurring_start" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?php echo helper_trans('repeat-this-invoice'); ?> <span class="text-danger">*</span></label>
                                    <select class="form-control" name="frequency">
                                        <option value=""><?php echo helper_trans('select'); ?></option>
                                        <option <?php if ($invoice[0]['frequency'] == 7) {
                                            echo 'selected';
                                        } ?> value="7"><?php echo helper_trans('weekly'); ?></option>
                                        <option <?php if ($invoice[0]['frequency'] == 14) {
                                            echo 'selected';
                                        } ?> value="14">2 <?php echo helper_trans('weeks'); ?></option>
                                        <option <?php if ($invoice[0]['frequency'] == 21) {
                                            echo 'selected';
                                        } ?> value="21">3 <?php echo helper_trans('weeks'); ?></option>
                                        <option <?php if ($invoice[0]['frequency'] == 30) {
                                            echo 'selected';
                                        } ?> value="30"><?php echo helper_trans('monthly'); ?></option>
                                        <option <?php if ($invoice[0]['frequency'] == 60) {
                                            echo 'selected';
                                        } ?> value="60">2 <?php echo helper_trans('months'); ?></option>
                                        <option <?php if ($invoice[0]['frequency'] == 120) {
                                            echo 'selected';
                                        } ?> value="120">4 <?php echo helper_trans('months'); ?></option>
                                        <option <?php if ($invoice[0]['frequency'] == 180) {
                                            echo 'selected';
                                        } ?> value="180">6 <?php echo helper_trans('months'); ?></option>
                                        <option <?php if ($invoice[0]['frequency'] == 365) {
                                            echo 'selected';
                                        } ?> value="365"><?php echo helper_trans('yearly'); ?></option>
                                        <option <?php if ($invoice[0]['frequency'] == 730) {
                                            echo 'selected';
                                        } ?> value="730">2 <?php echo helper_trans('years'); ?></option>
                                        <option <?php if ($invoice[0]['frequency'] == 1095) {
                                            echo 'selected';
                                        } ?> value="1095">3 <?php echo helper_trans('years'); ?></option>
                                        <option <?php if ($invoice[0]['frequency'] == 1825) {
                                            echo 'selected';
                                        } ?> value="1825">5 <?php echo helper_trans('years'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?php echo helper_trans('end-date'); ?> </label>
                                    <input type="text" class="form-control datepicker" value="<?php if ($invoice[0]['recurring_end'] != '0000-00-00') {
                                        echo html_escape($invoice[0]['recurring_end']);
                                    } ?>"
                                        name="recurring_end" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <?php endif ?>

                        <!-- invoice header -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default inv exh">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                                        href="#collapse8" aria-expanded="false" aria-controls="collapse8">
                                        <div class="panel-heading inv" role="tab" id="heading8">
                                            <h4 class="panel-title inv">
                                                <span class="style_border"><?php echo helper_trans('invoice-title'); ?></span>
                                                <i class="fa fa-angle-down pull-right"></i>
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="collapse8" class="panel-collapse data_collaps_border collapse"
                                        role="tabpanel" aria-labelledby="heading8" aria-expanded="false"
                                        style="height: 1px;">
                                        <div class="panel-body inv">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <?php if (empty(helper_get_business()->logo)) : ?>
                                                    <span class="alterlogo"><i class="flaticon-close"></i></span>
                                                    <?php else : ?>
                                                    <img width="130px" src="<?php echo url(helper_get_business()->logo); ?>" alt="Logo">
                                                    <?php endif ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <?php if (isset($page_title) && $page_title == 'Edit Invoice') : ?>
                                                    <input type="text" id="example-input-large" name="title"
                                                        class="form-control form-control-lg text-right"
                                                        placeholder="<?php echo helper_trans('invoice-title-placeholder'); ?>" value="<?php echo html_escape($invoice[0]['title']); ?>">
                                                    <?php else : ?>
                                                    <input type="text" class="form-control text-right" name="title"
                                                        value="Invoice title <?php echo get_invoice_number(1); ?>">
                                                    <?php endif ?>
                                                    <input type="text" id="example-input-large" name="summary"
                                                        class="form-control form-control-md text-right"
                                                        placeholder="<?php echo helper_trans('invoice-title-placeholder'); ?>"
                                                        value="<?php echo html_escape($invoice[0]['summary']); ?>">

                                                    <p class="mb-0 pull-right"><strong><?php echo html_escape(helper_get_business()->name); ?></strong>
                                                    </p><br>
                                                    <p class="pull-right"><?php echo html_escape(helper_get_business()->country); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- invoice body -->
                        <div class="box shadow-lg">

                            <div class="box-body inv">
                                <div class="container">

                                    <div class="row mb-20">

                                        <div class="col-xs-12 col-md-12">

                                            <div class="row inv-info">
                                                <div class="col-xs-6 col-md-4 text-left">
                                                    <h5><?php echo helper_trans('bill-to'); ?></h5>

                                                    <div id="load_customers">
                                                        @include('admin.user.include.invoice_load_customers')
                                                    </div>

                                                    <a data-toggle="modal" href="#customerModal" title="Add a row"
                                                        class="add-new-item btn btn-block btn-default btn-sm p-10"><i
                                                            class="icon-plus"></i> <?php echo helper_trans('add-a-customer'); ?></a>

                                                    <div class="mt-20" id="load_info"></div>
                                                </div>

                                                <div class="col-xs-6 col-md-8 text-right">
                                                    <div class="form-group row">
                                                        <label for="inputEmail3"
                                                            class="col-sm-8 text-right control-label col-form-label"><?php echo helper_trans('invoice-number'); ?></label>
                                                        <div class="col-sm-4">
                                                            <?php if (isset($page_title) && $page_title == 'Edit Invoice') : ?>
                                                            <input type="text" class="form-control" name="number"
                                                                value="<?php echo html_escape($invoice[0]['number']); ?>"
                                                                placeholder="Invoice number">
                                                            <?php else : ?>
                                                            <input type="text" class="form-control" name="number"
                                                                value="<?php echo date('Y') . '-' . str_pad(get_invoice_number(1), 2, '0', STR_PAD_LEFT); ?>"
                                                                placeholder="Invoice number">
                                                            <?php endif ?>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="inputEmail3"
                                                            class="col-sm-8 text-right control-label col-form-label"><?php echo helper_trans('p.o.s.o.-number'); ?></label>
                                                        <div class="col-sm-4">
                                                            <input type="text" value="<?php echo html_escape($invoice[0]['poso_number']); ?>"
                                                                class="form-control" name="poso_number">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="inputEmail3"
                                                            class="col-sm-8 text-right control-label col-form-label"><?php echo helper_trans('invoice-date'); ?></label>
                                                        <div class="col-sm-4">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control datepicker"
                                                                    placeholder="yyyy/mm/dd" name="date"
                                                                    value="<?php echo date('Y-m-d'); ?>">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="fa fa-calender"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mt-10">
                                                        <label for="inputEmail3"
                                                            class="col-sm-8 text-right control-label col-form-label"><?php echo helper_trans('payment-due-date'); ?></label>
                                                        <div class="col-sm-4">
                                                            <div class="input-group">
                                                                <input type="text" id="payment_due"
                                                                    class="form-control datepicker payment_due"
                                                                    name="payment_due" value="<?php if (!empty($invoice[0]['payment_due'])) {
                                                                        echo $invoice[0]['payment_due'];
                                                                    } else {
                                                                        echo date('Y-m-d');
                                                                    } ?>">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="fa fa-calender"></i>
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <select class="form-control mt-5 due_limit"
                                                                name="due_limit">
                                                                <option <?php if ($invoice[0]['due_limit'] == 1) {
                                                                    echo 'selected';
                                                                } ?> value="1">
                                                                    <?php echo helper_trans('on-receipt'); ?></option>
                                                                <option <?php if ($invoice[0]['due_limit'] == 15) {
                                                                    echo 'selected';
                                                                } ?> value="15">
                                                                    <?php echo helper_trans('within'); ?> 15 <?php echo helper_trans('days'); ?>
                                                                </option>
                                                                <option <?php if ($invoice[0]['due_limit'] == 30) {
                                                                    echo 'selected';
                                                                } ?> value="30">
                                                                    <?php echo helper_trans('within'); ?> 30 <?php echo helper_trans('days'); ?>
                                                                </option>
                                                                <option <?php if ($invoice[0]['due_limit'] == 45) {
                                                                    echo 'selected';
                                                                } ?> value="45">
                                                                    <?php echo helper_trans('within'); ?> 45 <?php echo helper_trans('days'); ?>
                                                                </option>
                                                                <option <?php if ($invoice[0]['due_limit'] == 60) {
                                                                    echo 'selected';
                                                                } ?> value="60">
                                                                    <?php echo helper_trans('within'); ?> 60 <?php echo helper_trans('days'); ?>
                                                                </option>
                                                                <option <?php if ($invoice[0]['due_limit'] == 75) {
                                                                    echo 'selected';
                                                                } ?> value="75">
                                                                    <?php echo helper_trans('within'); ?> 75 <?php echo helper_trans('days'); ?>
                                                                </option>
                                                                <option <?php if ($invoice[0]['due_limit'] == 90) {
                                                                    echo 'selected';
                                                                } ?> value="90">
                                                                    <?php echo helper_trans('within'); ?> 90 <?php echo helper_trans('days'); ?>
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr class="item-row">
                                                            <th><?php echo helper_trans('item'); ?></th>
                                                            <th><?php echo helper_trans('description'); ?></th>
                                                            <th><?php echo helper_trans('price'); ?></th>
                                                            <th><?php echo helper_trans('quantity'); ?></th>
                                                            <th id="th_tax"><?php echo helper_trans('tax'); ?></th>
                                                            <th id="th_discount"><?php echo helper_trans('discount'); ?></th>
                                                            <th><?php echo helper_trans('total'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php if (isset($page_title) && $page_title == 'Edit Invoice') : ?>
                                                        <?php $items = helper_get_invoice_items($invoice[0]['id']); 
                                                        // $items = ($items[0]);
                                                        // dd($items)
                                                        ?>
                                                        @for ($i = 0; $i < count($items); $i++)
                                                        {{-- {{dd($items[$i]->item_name)}} --}}
                                                        
                                                        
                                                        <tr class="item-row">
                                                            <td>
                                                                <input type="text" class="form-control item"
                                                                    placeholder="Item" type="text"
                                                                    name="items_val[]" value="<?php echo html_escape($items[$i]->item); ?>">
                                                                <input type="hidden" class="form-control item"
                                                                    placeholder="Item" type="text" name="items[]"
                                                                    value="<?php echo html_escape($items[$i]->id); ?>">
                                                            </td>
                                                            <td>
                                                                <input class="form-control"
                                                                    placeholder="Enter item description"
                                                                    type="text" name="details[]"
                                                                    {{-- value="<?php echo html_escape($items[$i]->details); ?>" --}}
                                                                    >
                                                            </td>
                                                            <td>
                                                                <input class="form-control price invo"
                                                                    placeholder="Price" type="text" name="price[]"
                                                                    value="<?php echo html_escape($items[$i]->price); ?>">
                                                            </td>
                                                            <td>
                                                                <input class="form-control qty" placeholder="Quantity"
                                                                    type="text" name="quantity[]"
                                                                    value="<?php echo html_escape($items[$i]->qty); ?>">
                                                            </td>
                                                            <td>
                                                                {{-- {{   $taxes_item_data = get_by_user('tax')->all(); dd($taxes_item_data) }} --}}
                                                                <select name="taxes[]" id="tax" class="form-control taxes_product">
                                                                    <option value="{{$items[$i]->tax_rate}}">{{$items[$i]->tax_name}}</option>
                                                                    
                                
                                                                    @foreach ($taxes_item_data as $item)
                                                                        <option value="{{$item->rate}}">{{$item->name}}</option>
                                                                    @endforeach
                                                                    

                                                                </select>
                                                            </td>
                                                            <td>
                                                                <?php 
                                                                    $discounts = [0 ,0.1 , 0.2 , 0.3 , 0.4 , 0.5 , 0.6 , 0.7 , 0.8 , 0.9 , 1];     
                                                                ?>
                                                                <select name="Discount[]" id="Discount" class="form-control Discount">
                                                                    <option value="{{$items[$i]->discount}}">{{$items[$i]->discount}}</option>
                                                                    @foreach ($discounts as $item)
                                                                        <option value="{{$item}}">{{ $item * 100 }}%</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <div class="delete-btn">
                                                                    <span class="currency_wrapper"></span>
                                                                    <span class="total"><?php echo html_escape($items[$i]->price); ?></span>
                                                                    <a class="delete" href="javascript:;"
                                                                        title="Remove row">&times;</a>
                                                                    <input type="hidden" class="total"
                                                                        name="total_price[]"
                                                                        value="<?php echo html_escape($items[$i]->price); ?>">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endfor

                                                            <?php endif ?>
                                                        <thead id="add_item">
                                                        </thead>


                                                        <tr>
                                                            <td colspan="5" class="p-0">
                                                                <a href="#"
                                                                    class="btn btn-default add_item_btn"><i
                                                                        class="icon-plus"></i>
                                                                    <?php echo helper_trans('add-an-item'); ?></a>
                                                            </td>
                                                        </tr>
                                                        

                                                            <tr id="products_list_inv" style="display: none;">
                                                                <td colspan="5" class="p-0">
                                                                    <div class="inv-product br-10 dshadow">
                                                                        <div class="form-group has-search">
                                                                            <span
                                                                                class="icon-magnifier form-control-feedback"></span>
                                                                            <input type="text"
                                                                                class="form-control search_product"
                                                                                placeholder="Type product">
                                                                        </div>
    
                                                                        <div class="loaderp text-center p-10"></div>
    
                                                                        <!-- load ajax data -->
                                                                        {{-- <select name="" id=""> --}}
                                                                            

                                                                                <div id="load_product" class="pro-scroll">
                                                                                    @include('admin.user.include.invoice_product_list')
                                                                                </div>
                                                                        {{-- </select> --}}
                                                                        <div class="col-md-12 p-0 text-center">
                                                                            <a data-toggle="modal" href="#productModal"
                                                                                title="Add a row"
                                                                                class="add-new-item btn btn-block btn-info p-10"><i
                                                                                    class="icon-plus"></i>
                                                                                <?php echo helper_trans('add-new-item'); ?></a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        

                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-right">
                                                                <strong><?php echo helper_trans('sub-total'); ?></strong>
                                                            </td>
                                                            <td>
                                                                <span class="currency_wrapper"></span>
                                                                <span id="subtotal">0.00</span>
                                                                <input type="hidden" class="subtotal"
                                                                    name="sub_total" value="">
                                                            </td>
                                                        </tr>
                                                        {{-- <form action="/tax" method="POST"  autocomplete="off"> --}}
                                                            {{-- @csrf --}}
                                                            <select name="price_include_tax" style="width:25%" id="invoice_include_tax" class="form-control invoice_include_tax" onclick="td_taxes()">
                                                                <option value="ordinary"><?php echo helper_trans('tax invoice')?></option>
                                                                <option value="simple"><?php echo helper_trans('simple invoice') ?></option>
                                                            </select>
                                                            <select name="price_include_tax" style="width:25%" id="price_include_tax" class="form-control price_include_tax">
                                                                <option value="with"><?php echo helper_trans('Price With Tax')?></option>
                                                                <option value="without"><?php echo helper_trans('Price Without Tax')?></option>
                                                            </select>
                                                        {{-- </form> --}}

                                                        <?php foreach ($gsts as $gst) : ?>

                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-right">
                                                                <strong><?php echo helper_trans($gst->name); ?></strong>
                                                            </td>
                                                            <td class="inv-width">
                                                                <select class="form-control tax_id"
                                                                    data-id="<?php echo $gst->id; ?>"
                                                                    id="tax_id_" name="total_taxes">
                                                                    <option value=""><?php echo helper_trans('select-tax'); ?>
                                                                    </option>
                                                                    <?php foreach ($taxes as $taxes) : ?>
                                                                    <option value="{{$taxes->rate}}">{{$taxes->name}} {{$taxes->rate*100}} %</option>
                                                                    <?php endforeach ?>
                                                                       
                                                                    <?php foreach ($gst->taxes as $tax) : ?>

                                                                    <?php $selected = ''; ?>
                                                                    <?php foreach ($asign_taxs as $asign_tax) : ?>
                                                                    <?php if ($asign_tax->tax_id == $tax->id) : ?>
                                                                    <?php $selected = 'selected';
                                                                    break; ?>
                                                                    <?php else : ?>
                                                                    <?php $selected = ''; ?>
                                                                    <?php endif ?>
                                                                    <?php endforeach ?>

                                                                    <option <?php echo $selected; ?>
                                                                        value="<?php echo html_escape($tax->id); ?>">
                                                                        <?php echo html_escape($tax->name); ?> - <?php echo html_escape($tax->rate); ?>%
                                                                    </option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                                <input type="hidden" class="tax"
                                                                    id="tax_<?php echo $gst->id; ?>" value="0">
                                                                    <span id="total_tax"></span>

                                                            </td>
                                                        </tr>
                                                        <?php endforeach ?>
                                                        <input type="hidden" class="total_tax" id="total_tax"
                                                            value="<?php if (isset($total_tax)) {
                                                                echo $total_tax->total;
                                                            } ?>">
                                                        {{-- <tr id="td_subtotal_invoice">
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                                    <td class="text-right">
                                                                        <strong id="tax_tax"><?php echo helper_trans('tax'); ?></strong>
                                                                    </td>
                                                                    <td>
                                                                        <span class="currency_wrapper"></span>
                                                                        <span id="total_tax"></span>
                                                                        <input type="hidden" class="total_tax"
                                                                            name="total_tax" value="">
                                                                    </td>
                                                        </tr> --}}
                                                            
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-right">
                                                                <strong><?php echo helper_trans('discount'); ?></strong>
                                                            </td>
                                                            <td width="15%">
                                                                <div class="input-group">
                                                                    <input type="text" id="discount"
                                                                        name="discount" value="<?php echo html_escape($invoice[0]['discount']); ?>"
                                                                        class="form-control"
                                                                        aria-describedby="basic-addon2">
                                                                    <div class="input-group-append discount">
                                                                        <span class="input-group-text"
                                                                            id="basic-addon1">%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-right"><span
                                                                    class="currency_name mr-10"></span> <strong>
                                                                    <?php echo helper_trans('grand-total'); ?></strong></td>
                                                            <td>
                                                                <span class="currency_wrapper"></span>
                                                                <span id="grandTotal">0</span>
                                                                <input type="hidden" class="grandtotal"
                                                                    name="grand_total" value="">
                                                                <input type="hidden" class="convert_total"
                                                                    name="convert_total" value="">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="box-footer text-right">
                                <input type="hidden" class="currency_code" name="currency_code" value="">
                                <strong><span class="conversion_currency"> </span></strong>
                            </div>
                        </div>

                        <!-- invoice footer -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default inv">
                                    <a class="collapsed" role="button" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapse2" aria-expanded="false"
                                        aria-controls="collapse2">
                                        <div class="panel-heading inv" role="tab" id="heading8">
                                            <h4 class="panel-title inv">
                                                <span class="style_border"><?php echo helper_trans('footer'); ?></span>
                                                <i class="fa fa-angle-down pull-right fa-1x"></i>
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="collapse2" class="panel-collapse data_collaps_border collapse"
                                        role="tabpanel" aria-labelledby="heading2" aria-expanded="false"
                                        style="height: 1px;">
                                        <div class="panel-body inv">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php if (isset($page_title) && $page_title == 'Edit Invoice') : ?>
                                                    <textarea id="summernote" class="form-control" rows="8" name="footer_note"><?php echo $invoice[0]['footer_note']; ?></textarea>
                                                    <?php else : ?>
                                                    <textarea id="summernote" class="form-control" rows="8" name="footer_note"
                                                        placeholder="<?php echo helper_trans('invoice-footer-placeholder'); ?>"><?php echo strip_tags(helper_get_business()->footer_note); ?></textarea>
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-10">
                            <div class="col-md-12">
                                <div class="inv-top-btn mb-10">
                                    <?php if (isset($page_title) && $page_title == 'Edit Invoice') : ?>
                                    <a href="<?php echo url('admin/invoice/details/' . md5($invoice[0]['id'])); ?>"
                                        class="btn btn-default btn-rounded pull-right ml-10"><i
                                            class="fa fa-long-arrow-left"></i> <?php echo helper_trans('back'); ?></a>
                                    <?php endif ?>

                                    <button type="submit"
                                        class="btn btn-info btn-rounded save_invoice_btn pull-right ml-5"><?php if (isset($page_title) && $page_title == 'Edit Invoice') {
                                            echo helper_trans('update');
                                        } else {
                                            echo helper_trans('save-and-continue');
                                        } ?></button>

                                    <button id="edit_invoice" type="button"
                                        class="btn waves-effect waves-light btn-rounded btn-outline-info pull-right mr-10 edit_invoice_btn"
                                        style="display: none;"><?php echo helper_trans('edit'); ?></button>
                                    <button type="submit"
                                        class="btn waves-effect waves-light btn-outline-info btn-rounded pull-right mr-10 preview_invoice_btn"><?php echo helper_trans('preview'); ?></button>
                                </div>
                                <input type="hidden" class="set_value" name="check_value">
                            </div>
                        </div>

                    </div>



                    <input type="hidden" name="id" value="<?php echo html_escape($invoice[0]['id']); ?>">
                    <input type="hidden" name="is_recurring" value="<?php echo html_escape($type); ?>">

                </form>

            </div>
        </div>
    </section>
</div>


<!-- product list modal -->
<div id="productModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="vcenter"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom modal-md">
        <form id="product-form" method="post" enctype="multipart/form-data" class="validate-form"
            action="<?php echo url('admin/invoice/ajax_add_product'); ?>" role="form" novalidate>
            @csrf
            <div class="modal-content modal-md">
                <div class="modal-header">
                    <h4 class="modal-title" id="vcenter"><?php echo helper_trans('add-new-product'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">

                    <div class="form-group row">
                        <label class="col-sm-4 text-right control-label col-form-label"><?php echo helper_trans('product-name'); ?></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="name" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 text-right control-label col-form-label"><?php echo helper_trans('price'); ?></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="price" required>
                        </div>
                    </div>

                    <?php if (helper_get_business()->enable_stock == 1) : ?>
                    <div class="form-group row">
                        <label class="col-sm-4 text-right control-label col-form-label"><?php echo helper_trans('stock-quantity'); ?></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="quantity" required>
                        </div>
                    </div>
                    <?php else : ?>
                    <input type="hidden" class="form-control" name="quantity" value="0">
                    <?php endif; ?>

                    <div class="form-group row">
                        <label class="col-sm-4 text-right control-label col-form-label"><?php echo helper_trans('details'); ?></label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="details"> </textarea>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">


                    <button type="submit" class="btn btn-info waves-effect pull-right"><?php echo helper_trans('add-product'); ?></button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<!-- customer modal -->
<div id="customerModal" class="modal fade" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom modal-md">
        <form id="customer-form" method="post" enctype="multipart/form-data" class="validate-form"
            action="<?php echo url('admin/invoice/ajax_add_customer'); ?>" role="form" novalidate>
            @csrf
            <div class="modal-content modal-md">
                <div class="modal-header">
                    <h4 class="modal-title" id="vcenter"><?php echo helper_trans('add-new-customer'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label><?php echo helper_trans('customer-name'); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" required name="name">
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label p-0" for="example-input-normal"><?php echo helper_trans('country'); ?>
                        </label>
                        <select class="form-control single_select col-sm-12" id="country" name="country"
                            style="width: 100%">
                            <option value=""><?php echo helper_trans('select'); ?></option>
                            <?php foreach ($countries as $country) : ?>
                            <?php if (!empty($country->currency_name)) : ?>
                            <option value="<?php echo html_escape($country->id); ?>">
                                <?php echo html_escape($country->name); ?>
                            </option>
                            <?php endif ?>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label p-0" for="example-input-normal"><?php echo helper_trans('currency'); ?>
                        </label>
                        <select class="form-control col-sm-12 wd-100" id="currency" name="currency" disabled>
                            <option value=""><?php echo helper_trans('select'); ?></option>
                            <?php foreach ($countries as $currency) : ?>>
                            <?php echo html_escape($currency->currency_code . ' - ' . $currency->currency_name); ?>
                            </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">


                    <button type="submit" class="btn btn-info waves-effect pull-right"><?php echo helper_trans('add-customer'); ?></button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
