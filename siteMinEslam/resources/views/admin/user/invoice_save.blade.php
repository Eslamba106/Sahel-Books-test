<?php $settings = get_settings(); ?>

<?php
$currency_symbol = helper_get_customer($invoice->customer)->currency_symbol;
if (isset($currency_symbol)) {
    $currency_symbol = $currency_symbol;
} else {
    $currency_symbol = helper_get_business()->currency_symbol;
}
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="col-md-10 col-xs-12 m-auto">

                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <h2 class="mt-0"><?php echo helper_trans('invoice'); ?> #<?php echo html_escape($invoice->number); ?></h2>
                        <p><?php echo helper_trans('created'); ?>: <?php echo my_date_show($invoice->created_at); ?></p>
                    </div>

                    <div class="col-md-8 col-xs-12 text-right">
                        <a href="<?php echo url('admin/invoice/edit/' . md5($invoice->id)); ?>" class="btn btn-default btn-rounded mr-5"><i class="icon-pencil"></i>
                            <?php echo helper_trans('edit'); ?> </a>

                        <a href="<?php echo url('admin/invoice/create'); ?>" class="btn btn-default btn-rounded"><i class="fa fa-plus"></i>
                            <?php echo helper_trans('new-invoice'); ?></a>

                        <div class="btn-group mr-5">
                            <button type="button" class="btn btn-default btn-rounded dropdown-toggle btn_trigger"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-settings"></i> <?php echo helper_trans('more-actions'); ?>
                                <div class="circle circle-blue"></div>
                            </button>
                            <div class="dropdown-menu st" x-placement="bottom-start">
                                <a class="dropdown-item print_invoice" href="#"><?php echo helper_trans('print'); ?></a>
                                <a target="_blank" class="dropdown-item"
                                    href="<?php echo url('readonly/export_pdf/' . md5($invoice->id)); ?>"><?php echo helper_trans('export-as-pdf'); ?></a>
                                <?php if ($invoice->recurring == 1 && $invoice->is_completed == 0) : ?>
                                <a class="dropdown-item stop_recurring"
                                    href="<?php echo url('admin/invoice/stop_recurring/' . $invoice->id); ?>"><?php echo helper_trans('stop-recurring'); ?></a>
                                <?php endif ?>

                                <?php if ($invoice->recurring == 0) : ?>
                                <a class="dropdown-item convert_to_recurring"
                                    href="<?php echo url('admin/invoice/convert_recurring/' . md5($invoice->id)); ?>"><?php echo helper_trans('convert-recurring'); ?></a>
                                <?php endif ?>

                                <a class="dropdown-item" data-toggle="modal"
                                    href="#sendInvoiceModal_<?php echo html_escape($invoice->id); ?>"><?php echo helper_trans('send'); ?></a>
                                <a target="_blank" class="dropdown-item"
                                    href="<?php echo url('readonly/invoice/preview/' . md5($invoice->id)); ?>"><?php echo helper_trans('preview-as-a-customer'); ?></a>
                                <?php if ($settings->enable_delete_invoice == 1) : ?>
                                <a class="dropdown-item delete_item"
                                    href="<?php echo url('admin/invoice/delete/' . $invoice->id); ?>"><?php echo helper_trans('delete'); ?></a>
                                <?php endif ?>
                            </div>

                        </div>

                    </div>
                    <input type="hidden" class="set_value" name="check_value">
                </div><br>



                <div class="row mb-0 p-0">
                    <div class="col-md-12 p-0 table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="border-0"><?php echo helper_trans('status'); ?></th>
                                    <th class="border-0"><?php echo helper_trans('customer'); ?></th>
                                    <th class="border-0"><?php echo helper_trans('amount-due'); ?> </th>
                                    <th class="border-0"><?php echo helper_trans('total'); ?></th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>
                                        <?php if ($invoice->status == 0) : ?>
                                        <span class="custom-label-sm label-light-default"><?php echo helper_trans('draft'); ?></span>
                                        <?php elseif ($invoice->status == 1) : ?>
                                        <span class="custom-label-sm label-light-info"><?php echo helper_trans('approved'); ?></span>
                                        <?php elseif ($invoice->status == 2) : ?>
                                        <span class="custom-label-sm label-light-success"><?php echo helper_trans('paid'); ?></span>
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <?php if (!empty(helper_get_customer($invoice->customer))) : ?>
                                        <?php echo helper_get_customer($invoice->customer)->name; ?>
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($currency_symbol)) {
                                            echo html_escape($currency_symbol);
                                        } ?><?php echo number_format($invoice->grand_total - get_total_invoice_payments($invoice->id, $invoice->parent_id), 2); ?>
                                    </td>
                                    <td><?php if (!empty($currency_symbol)) {
                                        echo html_escape($currency_symbol);
                                    } ?><?php echo number_format($invoice->grand_total, 2); ?></td>
                                </tr>

                            </tbody>
                        </table>

                    </div>


                    <div class="col-md-12 p-0">

                        <!-- for regular invoices -->
                        <?php if ($invoice->recurring == 0) : ?>

                        <?php if ($invoice->status == 0) : ?>
                        <div class="row mt-20">
                            <div class="col-md-12">
                                <div class="card br-10 b-warning bg-pending <?php if ($invoice->status == 0) {
                                    echo 'dshadow';
                                } ?>">
                                    <div class="card-body bg-muted br-10">
                                        <div class="m-l-10 align-self-center">
                                            <h3 class="mt-10 m-b-0"><?php echo helper_trans('draft-invoice'); ?>
                                                <button data-id="<?php echo html_escape(md5($invoice->id)); ?>" type="button"
                                                    class="pull-right btn btn-info btn-sm rounded approve_invoice"><i
                                                        class="ti-check"></i> <?php echo helper_trans('approve'); ?> </button>
                                            </h3>
                                            <h5 class="text-muteds m-b-0"><i class="icon-info"></i> <?php echo helper_trans('draft-inv-info'); ?>
                                            </h5>
                                            <p><strong><?php echo helper_trans('created-on'); ?>:</strong> <?php echo my_date_show($invoice->created_at); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="invoice-vertical-line"></div>
                        </div>
                        <?php endif ?>

                        <div class="row mt-10">
                            <div class="col-md-12">
                                <div class="card br-10 <?php if ($invoice->status == 0) {
                                    echo 'is_disable';
                                } else {
                                    if ($invoice->is_sent == 0) {
                                        echo 'dshadow';
                                    } else {
                                        echo 'nshadow';
                                    }
                                } ?>">
                                    <div class="card-body br-10">
                                        <div class="m-l-10 align-self-center">

                                            <h4 class="mt-10 m-b-0"><?php echo helper_trans('send-invoice'); ?>
                                                <?php if ($invoice->is_sent == 1) : ?>
                                                <button data-toggle="modal"
                                                    data-target="#sendInvoiceModal_<?php echo html_escape($invoice->id); ?>" type="button"
                                                    class="pull-right btn btn-info btn-sm rounded mr-5"
                                                    <?php if ($invoice->status == 0) {
                                                        echo 'disabled';
                                                    } ?>> <?php echo helper_trans('send-again'); ?> </button>
                                                <?php else : ?>
                                                <button data-toggle="modal"
                                                    data-target="#sendInvoiceModal_<?php echo html_escape($invoice->id); ?>" type="button"
                                                    class="pull-right btn btn-info btn-sm rounded mr-5"
                                                    <?php if ($invoice->status == 0) {
                                                        echo 'disabled';
                                                    } ?>> <?php echo helper_trans('send-invoice'); ?> </button>
                                                <?php endif ?>
                                            </h4>

                                            <h5 class="text-muteds m-b-0">
                                                <p><strong><?php echo helper_trans('last-sent'); ?>:</strong>
                                                    <?php if ($invoice->is_sent == 1) : ?>
                                                    <?php echo my_date_show_time($invoice->sent_date); ?>
                                                    <?php else : ?>
                                                    None
                                                    <?php endif ?>
                                                </p>
                                            </h5>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="invoice-vertical-line"></div>
                        </div>

                        <div class="row mt-10">
                            <div class="col-md-12">
                                <div class="card br-10 <?php if ($invoice->status == 0) {
                                    echo 'is_disable';
                                } elseif ($invoice->status == 1) {
                                    echo 'dshadow';
                                } else {
                                    echo 'nshadow';
                                } ?>">
                                    <div class="card-body br-10">
                                        <div class="m-l-10 align-self-center">
                                            <h4 class="mt-10 m-b-0">

                                                <?php if ($invoice->status == 1) : ?>
                                                <?php echo helper_trans('paid-partial'); ?>
                                                <?php elseif ($invoice->status == 2) : ?>
                                                <?php echo helper_trans('paid-info'); ?>
                                                <?php endif ?>

                                                <?php if ($invoice->status != 2) : ?>
                                                <button data-toggle="modal" data-target="#recordPayment" type="button"
                                                    class="pull-right btn btn-info btn-sm rounded" <?php if ($invoice->status == 0) {
                                                        echo 'disabled';
                                                    } ?>>
                                                    <?php echo helper_trans('record-a-payment'); ?> </button>
                                                <?php endif ?>
                                            </h4>

                                            <h5 class="text-muteds m-b-0">
                                                <strong>
                                                    <?php echo helper_trans('amount-due'); ?>: <?php if (!empty($currency_symbol)) {
                                                        echo html_escape($currency_symbol);
                                                    } ?>
                                                    <?php if ($invoice->status == 2) {
                                                        echo '0.00';
                                                    } else {
                                                        echo number_format($invoice->grand_total - get_total_invoice_payments($invoice->id, $invoice->parent_id), 2);
                                                    } ?>
                                                </strong>
                                            </h5>

                                            <?php if ($invoice->status == 1) : ?>
                                            <p class="text-warning"><strong>Status:</strong> <?php echo helper_trans('awaiting-payment'); ?></p>
                                            <?php endif; ?>

                                            <?php if ($invoice->status == 2) : ?>
                                            <p class="text-success"><strong>Status:</strong> <?php echo helper_trans('paid-in-full'); ?></p>
                                            <hr>
                                            <h5><strong><?php echo helper_trans('payments-received'); ?>:</strong></h5>
                                            <p><?php echo my_date_show($payment->payment_date); ?> - <?php echo helper_trans('a-payment-for'); ?>
                                                <?php if (!empty($currency_symbol)) {
                                                    echo $currency_symbol;
                                                } ?><?php echo html_escape($invoice->grand_total); ?> </p>
                                            <?php endif ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- for recurring invoices -->
                        <?php else : ?>
                        <div class="row mt-20">
                            <div class="col-md-12">
                                <div class="card br-10 nshadow">
                                    <div class="card-body bg-muted br-10">
                                        <div class="m-l-10 align-self-center">
                                            <h3 class="mt-10 m-b-0"><?php echo helper_trans('create-invoice'); ?>
                                                <a href="<?php echo url('admin/invoice/edit/' . md5($invoice->id) . '/1'); ?>"
                                                    class="btn btn-default btn-rounded mr-5 pull-right"><i
                                                        class="icon-pencil"></i> <?php echo helper_trans('edit'); ?> </a>
                                            </h3>
                                            <p class="pt-10"><strong><?php echo helper_trans('created-on'); ?>:</strong>
                                                <?php echo my_date_show($invoice->created_at); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="invoice-vertical-line"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card br-10 <?php if ($invoice->status == 0) {
                                    echo 'dshadow';
                                } else {
                                    echo 'nshadow';
                                } ?>">
                                    <div class="card-body bg-muted br-10">
                                        <div class="m-l-10 align-self-center">

                                            <?php if (!empty($invoice->frequency)) : ?>
                                            <h3 class="mt-10 mb-10"><?php echo helper_trans('invoice-schedule'); ?></h3>
                                            <p class="mb-0"><strong><?php echo helper_trans('recurring-start'); ?>:</strong>
                                                <?php echo my_date_show($invoice->recurring_start); ?></p>
                                            <p class="mb-0"><strong><?php echo helper_trans('recurring-end'); ?>:</strong>
                                                <?php if ($invoice->recurring_end == '0000-00-00') : ?>
                                                <?php echo helper_trans('unlimited'); ?>
                                                <?php else : ?>
                                                <?php echo my_date_show($invoice->recurring_end); ?>
                                                <?php endif ?>
                                            </p>
                                            <p class="mb-0"><strong><?php echo helper_trans('repeat-invoice'); ?>:</strong>
                                                <?php echo html_escape($invoice->frequency); ?> days</p>
                                            <?php else : ?>

                                            <h3 class="mt-10 mb-10"><?php echo helper_trans('set-schedule'); ?></h3>
                                            <form id="recurring_form" method="post" class="validate-form"
                                                action="<?php echo url('admin/invoice/set_recurring/' . $invoice->id); ?>" role="form" novalidate>
                                                @csrf

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label><?php echo helper_trans('start-date'); ?> <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control datepicker"
                                                                required name="recurring_start" autocomplete="off"
                                                                value="<?php echo date('Y-m-d'); ?>">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label><?php echo helper_trans('repeat-this-invoice'); ?> <span
                                                                    class="text-danger">*</span></label>
                                                            <select class="form-control" name="frequency">
                                                                <option value=""><?php echo helper_trans('select'); ?></option>
                                                                <option value="7"><?php echo helper_trans('weekly'); ?></option>
                                                                <option value="14">2 <?php echo helper_trans('weeks'); ?></option>
                                                                <option value="21">3 <?php echo helper_trans('weeks'); ?></option>
                                                                <option value="30"><?php echo helper_trans('monthly'); ?></option>
                                                                <option value="60">2 <?php echo helper_trans('months'); ?></option>
                                                                <option value="120">4 <?php echo helper_trans('months'); ?></option>
                                                                <option value="180">6 <?php echo helper_trans('months'); ?></option>
                                                                <option value="365"><?php echo helper_trans('yearly'); ?></option>
                                                                <option value="730">2 <?php echo helper_trans('years'); ?></option>
                                                                <option value="1095">3 <?php echo helper_trans('years'); ?></option>
                                                                <option value="1825">5 <?php echo helper_trans('years'); ?></option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label><?php echo helper_trans('end-date'); ?> </label>
                                                            <input type="text" class="form-control datepicker"
                                                                name="recurring_end" autocomplete="off">
                                                            <p class="fs-12"><i
                                                                    class="fa fa-info-circle text-info"></i>
                                                                <?php echo helper_trans('recurring-end-info'); ?></p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <!-- <div class="form-group mb-0">
                                                                    <input type="checkbox" id="md_checkbox_1" class="filled-in chk-col-blue" value="1" name="auto_send">
                                                                    <label for="md_checkbox_1"><?php //echo helper_trans('auto-send-invoice-by-e-mail')
                                                                    ?></label>
                                                                </div> -->

                                                        <!-- <div class="form-group mt-0">
                                                                    <input type="checkbox" id="md_checkbox_2" class="filled-in chk-col-blue" value="1" name="send_myself">
                                                                    <label for="md_checkbox_2"> <?php //echo helper_trans('email-a-copy')
                                                                    ?></label>
                                                                </div> -->
                                                    </div>

                                                    <button type="submit"
                                                        class=" btn btn-info btn-sm rounded ml-15"><i
                                                            class="ti-check"></i> <?php echo helper_trans('finish-setup'); ?></button>

                                                </div>



                                            </form>

                                            <?php endif ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif ?>

                    </div>
                </div>


                <div id="invoice_save_area mt-0 p-0" class="card inv save_area print_area">
                    @include('admin.user.include.invoice_style_1')
                </div>

            </div>
        </div>
    </section>
</div>


@include('admin.user.include.send_invoice_modal')


<div id="recordPayment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="vcenter"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom modal-md">
        <form id="record_payment_form" method="post" enctype="multipart/form-data" class="validate-form"
            action="<?php echo url('admin/invoice/record_payment'); ?>" role="form" novalidate>
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="vcenter"><?php echo helper_trans('record-a-payment-for-this-invoice'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">

                    <?php $records = get_customer_advanced_record($invoice->customer); ?>
                    <?php if (!empty($records) && $records->customer_id == $invoice->customer) : ?>
                    <?php if ($records->amount != 0) : ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> <strong>You have reserve amount for this customer:
                            <?php echo $records->amount . ' ' . $records->currency; ?></strong>
                    </div>
                    <?php endif ?>
                    <?php endif ?>

                    <div class="form-group m-t-30" style="display: none;">
                        <input type="checkbox" name="is_autoload_amount" value="1" <?php if (helper_get_business()->is_autoload_amount == 1) {
                            echo 'checked';
                        } ?>
                            data-toggle="toggle" data-onstyle="info" data-width="100"></span>

                        <label> is autoload advance amount in your next invoice?</label>
                    </div>

                    <p class="text-muted"><?php echo helper_trans('record-payment-info'); ?></p><br>

                    <div class="form-group row">
                        <label for="inputEmail3"
                            class="col-sm-4 text-right control-label col-form-label"><?php echo helper_trans('payment-date'); ?></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" placeholder="yyyy/mm/dd"
                                    name="payment_date" value="<?php echo date('Y-m-d'); ?>">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa fa-calender"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3"
                            class="col-sm-4 text-right control-label col-form-label"><?php echo helper_trans('payment-date'); ?></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" class="form-control datepicker"
                                    placeholder="Enter the due date for partial payment" name="due_date"
                                    value="">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa fa-calender"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3"
                            class="col-sm-4 text-right control-label col-form-label"><?php echo helper_trans('amount'); ?></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="amount" value="<?php echo html_escape($invoice->grand_total - get_total_invoice_payments($invoice->id, $invoice->parent_id)); ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3"
                            class="col-sm-4 text-right control-label col-form-label"><?php echo helper_trans('payment-method'); ?></label>
                        <div class="col-sm-8">
                            <select class="form-control" id="tax" name="payment_method">
                                <option value="0"><?php echo helper_trans('select-payment-method'); ?></option>
                                <?php $i = 1;
                                foreach (get_payment_methods() as $payment) : ?>
                                <option value="<?php echo $i; ?>"><?php echo html_escape($payment); ?></option>
                                <?php $i++;
                                endforeach ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3"
                            class="col-sm-4 text-right control-label col-form-label"><?php echo helper_trans('memo-notes'); ?></label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="note"> </textarea>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="invoice_id" value="<?php echo html_escape(md5($invoice->id)); ?>">


                    <button type="submit" class="btn btn-info waves-effect pull-right"><?php echo helper_trans('add-payment'); ?></button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
