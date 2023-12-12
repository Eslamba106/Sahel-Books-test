<?php $settings = get_settings(); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2><?php echo helper_trans('invoices'); ?>
                         <a href="<?php echo url('admin/invoice/create'); ?>" class="btn btn-info btn-rounded pull-right"><i
                                class="fa fa-plus"></i> <?php echo helper_trans('create-new-invoices'); ?>
                        </a>
                         <a href="<?php echo url('admin/invoice/export'); ?>" class="btn btn-info btn-rounded pull-right"><i class="fas fa-file-download"></i><?php echo "  ".helper_trans('export excel sheet'); ?>
                        </a>
                         <a href="<?php echo url('admin/invoice/import'); ?>" class="btn btn-info btn-rounded pull-right"><i class="fas fa-file-download"></i><?php echo "  ".helper_trans('import excel sheet'); ?>
                        </a>
                    </h2>
                    <form method="GET" class="sort_invoice_form" action="<?php echo url('admin/invoice/type/' . $status); ?>">
                        <div class="row p-15 mt-20 mb-20">
                            <div class="col-md-4 col-xs-12 mt-5 pl-0">
                                <select class="form-control single_select sort" name="customer">
                                    <option value=""><?php echo helper_trans('all-customers'); ?></option>
                                    <?php foreach ($customers as $customer) : ?>
                                    <option value="<?php echo html_escape($customer->id); ?>" <?php echo isset($_GET['customer']) && $_GET['customer'] == $customer->id ? 'selected' : ''; ?>><?php echo html_escape($customer->name); ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="col-md-3 col-xs-12 mt-5 pl-0">
                                <select class="form-control single_select sort" name="status">
                                    <option value=""><?php echo helper_trans('all-status'); ?></option>
                                    <option value="2" <?php echo isset($_GET['status']) && $_GET['status'] == 2 ? 'selected' : ''; ?>><?php echo helper_trans('paid'); ?></option>
                                    <option value="1" <?php echo isset($_GET['status']) && $_GET['status'] == 1 ? 'selected' : ''; ?>><?php echo helper_trans('unpaid'); ?></option>
                                    <option value="0" <?php echo isset($_GET['status']) && $_GET['status'] == 0 ? 'selected' : ''; ?>><?php echo helper_trans('draft'); ?></option>
                                    <option value="3" <?php echo isset($_GET['status']) && $_GET['status'] == 3 ? 'selected' : ''; ?>><?php echo helper_trans('sent'); ?></option>
                                </select>
                            </div>

                            <div class="col-md-2 col-xs-12 mt-5 pl-0">
                                <div class="input-group">
                                    <input type="text" class="inv-dpick form-control datepicker"
                                        placeholder="<?php echo helper_trans('from'); ?>" name="start_date"
                                        value="<?php if (isset($_GET['start_date'])) {
                                            echo $_GET['start_date'];
                                        } ?>" autocomplete="off">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>

                            <div class="col-md-2 col-xs-12 mt-5 pl-0">
                                <div class="input-group">
                                    <input type="text" class="inv-dpick form-control datepicker"
                                        placeholder="<?php echo helper_trans('to'); ?>" name="end_date" value="<?php if (isset($_GET['end_date'])) {
                                            echo $_GET['end_date'];
                                        } ?>"
                                        autocomplete="off">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>

                            <div class="col-md-1 col-xs-12 mt-5 pl-0">
                                <button type="submit" class="btn btn-info btn-report btn-block custom_search"><i
                                        class="flaticon-magnifying-glass"></i></button>
                            </div>
                        </div>
                    </form>

                    <ul class="nav nav-tabs custab" role="tablist">
                        <li class="nav-item"> <a class="nav-link <?php if ($status == 1) {
                            echo 'active';
                        } ?>" href="<?php echo url('admin/invoice/type/1'); ?>"
                                role="tab" aria-selected="true"> <span class="hidden-xs-downs"><?php echo helper_trans('unpaid'); ?>
                                    <span class="label-count"><?php echo helper_count_invoices($istatus = 1, $type = 1); ?></span></span></a> </li>
                        <li class="nav-item"> <a class="nav-link <?php if ($status == 0) {
                            echo 'active';
                        } ?>" href="<?php echo url('admin/invoice/type/0'); ?>"
                                role="tab" aria-selected="false"> <span class="hidden-xs-downs"><?php echo helper_trans('draft'); ?>
                                    <span class="label-count"><?php echo helper_count_invoices($istatus = 0, $type = 1); ?></span></span></a> </li>
                        <li class="nav-item"> <a class="nav-link <?php if ($status == 3 && empty($_GET['recurring'])) {
                            echo 'active';
                        } ?>" href="<?php echo url('admin/invoice/type/3'); ?>"
                                role="tab" aria-selected="false"> <span class="hidden-xs-downs"><?php echo helper_trans('all-invoices'); ?>
                                    <span class="label-count"><?php echo helper_count_invoices($istatus = 3, $type = 1); ?></span></span></a> </li>

                        <li class="nav-item"> <a class="nav-link <?php if (isset($_GET['recurring']) && $_GET['recurring'] == 1) {
                            echo 'active';
                        } ?>" href="<?php echo url('admin/invoice/type/3?recurring=1'); ?>"
                                role="tab" aria-selected="false"> <span class="hidden-xs-downs"><?php echo helper_trans('recurring-invoice'); ?>
                                    <span class="label-count"><?php echo count_recurring_invoices(); ?></span></span></a> </li>
                    </ul>


                    <div class="tab-content">
                        <!-- All -->
                        <div class="tab-pane active" id="messages2" role="tabpanel">

                            <?php if (!empty($invoices)) : ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr class="item-row">
                                            <th><?php echo helper_trans('status'); ?></th>
                                            <th><?php echo helper_trans('date'); ?></th>
                                            <th><?php echo helper_trans('number'); ?></th>
                                            <th><?php echo helper_trans('customer'); ?></th>
                                            <th><?php echo helper_trans('total'); ?></th>
                                            <th><?php echo helper_trans('amount-due'); ?></th>
                                            <th class="text-right"><?php echo helper_trans('actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php foreach ($invoices as $invoice) : ?>
                                        <tr id="row_<?php echo html_escape($invoice->id); ?>">
                                            <td>
                                                <?php if ($invoice->status == 0) : ?>
                                                <span data-toggle="tooltip" data-placement="right"
                                                    title="<?php echo helper_trans('draft-tooltip'); ?>"
                                                    class="custom-label-sm label-light-default"><?php echo helper_trans('draft'); ?></span>
                                                <?php elseif ($invoice->status == 2) : ?>
                                                <span data-toggle="tooltip" data-placement="right"
                                                    title="<?php echo helper_trans('paid-tooltip'); ?>"
                                                    class="custom-label-sm label-light-success"><?php echo helper_trans('paid'); ?></span>
                                                <?php elseif ($invoice->status == 1) : ?>
                                                <?php if (check_paid_status($invoice->id) == 1) : ?>
                                                <span data-toggle="tooltip" data-placement="right"
                                                    title="<?php echo helper_trans('partial-payment'); ?>"
                                                    class="custom-label-sm label-light-info"><?php echo helper_trans('partial'); ?></span>
                                                <?php else : ?>
                                                <span data-toggle="tooltip" data-placement="right"
                                                    title="<?php echo helper_trans('unpaid-tooltip'); ?>"
                                                    class="custom-label-sm label-light-danger"><?php echo helper_trans('unpaid'); ?></span>
                                                <?php endif ?>

                                                <?php endif ?>

                                                <?php if ($invoice->recurring == 1) : ?>
                                                <?php if ($invoice->is_completed == 0) : ?>
                                                <span
                                                    class="custom-label-sm label-light-success mt-5"><?php echo helper_trans('active'); ?></span>
                                                <?php elseif ($invoice->is_completed == 1) : ?>
                                                <span data-toggle="tooltip" data-placement="right"
                                                    title="<?php echo helper_trans('complete-tooltip'); ?>"
                                                    class="custom-label-sm label-light-danger mt-5"><?php echo helper_trans('completed'); ?></span>
                                                <?php endif ?>
                                                <?php endif ?>
                                            </td>

                                            <td><?php echo my_date_show($invoice->created_at); ?></td>
                                            <td>
                                                <p class="mb-0"> <?php echo html_escape($invoice->number); ?> </p>
                                                <?php if ($invoice->recurring == 1) : ?>
                                                <strong><?php echo helper_trans('recurring'); ?></strong>
                                                <?php endif ?>
                                            </td>
                                            <td>
                                                <?php if (!empty(helper_get_customer($invoice->customer))) : ?>
                                                <?php echo helper_get_customer($invoice->customer)->name; ?>
                                                <?php
                                                $currency_symbol = helper_get_customer($invoice->customer)->currency_symbol;
                                                if (isset($currency_symbol)) {
                                                    $currency_symbol = $currency_symbol;
                                                } else {
                                                    $currency_symbol = helper_get_business()->currency_symbol;
                                                }
                                                ?>
                                                <?php $currency_code = helper_get_customer($invoice->customer)->currency; ?>
                                                <?php endif ?>
                                            </td>

                                            <?php if ($invoice->status == 2) : ?>
                                            <td>
                                                <span class="total-price"><?php if (!empty($currency_symbol)) {
                                                    echo html_escape($currency_symbol);
                                                } ?> <?php echo html_escape($invoice->grand_total); ?>
                                                    <?php if (!empty($currency_code)) {
                                                        echo html_escape($currency_code);
                                                    } ?></span><br>
                                                <span class="conver-total"><?php echo helper_get_business()->currency_symbol . '' . $invoice->convert_total . ' ' . user()->currency_code; ?></span>
                                            </td>
                                            <td>
                                                <span class="total-price"><?php if (!empty($currency_symbol)) {
                                                    echo html_escape($currency_symbol);
                                                } ?>0.00
                                                    <?php if (!empty($currency_code)) {
                                                        echo html_escape($currency_code);
                                                    } ?></span>
                                                <br>
                                                <span class="conver-total"><?php echo helper_get_business()->currency_symbol . '0.00' . user()->currency_code; ?></span>
                                            </td>
                                            <?php else : ?>
                                            <td>
                                                <span class="total-price"><?php if (!empty($currency_symbol)) {
                                                    echo html_escape($currency_symbol);
                                                } ?><?php echo html_escape($invoice->grand_total); ?>
                                                    <?php if (!empty($currency_code)) {
                                                        echo html_escape($currency_code);
                                                    } ?></span><br>
                                                <span class="conver-total"><?php echo helper_get_business()->currency_symbol . '' . $invoice->convert_total . ' ' . user()->currency_code; ?></span>
                                            </td>

                                            <td class="text-danger">
                                                <span class="total-price"><?php if (!empty($currency_symbol)) {
                                                    echo html_escape($currency_symbol);
                                                } ?><?php echo $invoice->grand_total - get_total_invoice_payments($invoice->id, $invoice->parent_id); ?>
                                                    <?php if (!empty($currency_code)) {
                                                        echo html_escape($currency_code);
                                                    } ?></span>
                                                <br>
                                                <?php if ($invoice->status != 1) : ?>

                                                <span class="conver-total"><?php echo helper_get_business()->currency_symbol . '' . $invoice->convert_total . ' ' . user()->currency_code; ?></span>
                                                <?php endif ?>
                                            </td>
                                            <?php endif ?>

                                            <td class="text-right">
                                                <?php if ($invoice->status == 2) : ?>
                                                <a target="_blank" class="mr-5"
                                                    href="<?php echo url('admin/invoice/details/' . md5($invoice->id)); ?>"><?php echo helper_trans('view'); ?></a>
                                                <?php elseif ($invoice->status == 0) : ?>
                                                <a class="mr-5 approve_item"
                                                    href="<?php echo url('admin/invoice/approve_invoice/' . md5($invoice->id)); ?>"><?php echo helper_trans('approve'); ?></a>
                                                <?php else : ?>
                                                <a class="mr-5" href="#recordPayment_<?php echo html_escape($invoice->id); ?>"
                                                    data-toggle="modal"><?php echo helper_trans('record-a-payment'); ?></a>
                                                <?php endif ?>

                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-default rounded btn-sm dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <?php echo helper_trans('more'); ?>
                                                    </button>
                                                    <div class="dropdown-menu st" x-placement="bottom-start">
                                                        <?php if ($invoice->status != 2) : ?>
                                                        <a class="dropdown-item"
                                                            href="<?php echo url('admin/invoice/details/' . md5($invoice->id)); ?>"><?php echo helper_trans('view'); ?>
                                                        </a>
                                                        <?php endif ?>
                                                        <a class="dropdown-item" data-toggle="modal"
                                                            href="#sendInvoiceModal_<?php echo html_escape($invoice->id); ?>"><?php echo helper_trans('send'); ?></a>
                                                        <a class="dropdown-item"
                                                            href="<?php echo url('admin/invoice/details/' . md5($invoice->id)); ?>"><?php echo helper_trans('print'); ?></a>

                                                        <?php if ($invoice->recurring == 1 && $invoice->is_completed == 0) : ?>
                                                        <a class="dropdown-item stop_recurring"
                                                            href="<?php echo url('admin/invoice/stop_recurring/' . $invoice->id); ?>"><?php echo helper_trans('stop-recurring'); ?></a>
                                                        <?php endif ?>

                                                        <?php if ($invoice->recurring == 0) : ?>
                                                        <a class="dropdown-item convert_to_recurring"
                                                            href="<?php echo url('admin/invoice/convert_recurring/' . md5($invoice->id)); ?>"><?php echo helper_trans('convert-recurring'); ?></a>
                                                        <?php endif ?>

                                                        <a class="dropdown-item"
                                                            href="<?php echo url('readonly/export_pdf/' . md5($invoice->id)); ?>"><?php echo helper_trans('export-as-pdf'); ?></a>

                                                        <a target="_blank" class="dropdown-item"
                                                            href="<?php echo url('readonly/invoice/preview/' . md5($invoice->id)); ?>"><?php echo helper_trans('preview-as-a-customer'); ?></a>

                                                        <a target="_blank" class="dropdown-item"
                                                            href="<?php echo url('readonly/invoice/view/' . md5($invoice->id)); ?>"><?php echo helper_trans('share-link'); ?></a>

                                                        <div class="dropdown-divider"></div>

                                                        <a class="dropdown-item"
                                                            href="<?php echo url('admin/invoice/edit/' . md5($invoice->id)); ?>"><?php echo helper_trans('edit'); ?> </a>

                                                        <?php if ($settings->enable_delete_invoice == 1) : ?>
                                                        <a class="dropdown-item delete_item"
                                                            data-id="<?php echo html_escape($invoice->id); ?>"
                                                            href="<?php echo url('admin/invoice/delete/' . $invoice->id); ?>"><?php echo helper_trans('delete'); ?></a>
                                                        <?php endif ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach ?>

                                    </tbody>
                                </table>
                            </div>
                            <?php else : ?>
                            <div class="pt-30"><strong><?php echo helper_trans('no-data-founds'); ?></strong></div>
                            <?php endif ?>
                        </div>

                    </div>
                </div>

                <div class="col-md-12 text-center mt-50">
                    <?php echo isset($pagination) ? helper_pagination($pagination) : ''; ?>
                </div>
            </div>
        </div>
    </section>
</div>


<?php foreach ($invoices as $invoice) : ?>
@include('admin.user.include.send_invoice_modal')
<?php endforeach; ?>


<?php foreach ($invoices as $invoice) : ?>
<div id="recordPayment_<?php echo html_escape($invoice->id); ?>" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom modal-md">
        <form method="post" enctype="multipart/form-data" class="validate-form" action="<?php echo url('admin/invoice/record_payment'); ?>"
            role="form" novalidate>
            @csrf
            <div class="modal-content modal-md">
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

                    <div class="form-group m-t-30" style="display: none">
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
                            class="col-sm-4 text-right control-label col-form-label"><?php echo helper_trans('due-date'); ?></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" class="form-control datepicker"
                                    placeholder="Enter the due date for partial payment" name="due_date"
                                    value="" autocomplete="off">
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
                            <input type="text" class="form-control" name="amount" value="<?php echo html_escape($invoice->grand_total - get_total_invoice_payments($invoice->id, $invoice->parent_id)); ?>"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3"
                            class="col-sm-4 text-right control-label col-form-label"><?php echo helper_trans('payment-method'); ?></label>
                        <div class="col-sm-8">
                            <select class="form-control" id="tax" name="payment_method" required>
                                <option value=""><?php echo helper_trans('select-payment-method'); ?></option>
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
<?php endforeach; ?>
