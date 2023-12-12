<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="p-10"><?php echo helper_trans('bills'); ?> <a href="<?php echo url('admin/bills/create'); ?>"
                            class="btn btn-info btn-rounded pull-right"><i class="fa fa-plus"></i> <?php echo helper_trans('create-new-bill'); ?></a>
                    </h2>

                    <div class="tab-content">
                        <!-- unpaid -->
                        <div class="tab-pane active" id="home2" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr class="item-row">
                                            <th><?php echo helper_trans('status'); ?></th>
                                            <th><?php echo helper_trans('date'); ?></th>
                                            <th><?php echo helper_trans('number'); ?></th>
                                            <th>Vendors</th>
                                            <th><?php echo helper_trans('amount'); ?></th>
                                            <th><?php echo helper_trans('amount-due'); ?></th>
                                            <th class="text-right"><?php echo helper_trans('actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($bills)) : ?>
                                        <tr>
                                            <td colspan="6" class="text-center p-30">
                                                <strong><?php echo helper_trans('no-data-founds'); ?></strong>
                                            </td>
                                        </tr>
                                        <?php else : ?>

                                        <?php foreach ($bills as $bill) : ?>
                                        <tr id="row_<?php echo html_escape($bill->id); ?>">
                                            <td>
                                                <?php if ($bill->status == 0) : ?>
                                                <span
                                                    class="custom-label-sm label-light-danger"><?php echo helper_trans('unpaid'); ?></span>
                                                <?php elseif ($bill->status == 1) : ?>
                                                <span
                                                    class="custom-label-sm label-light-info"><?php echo helper_trans('partial'); ?></span>
                                                <?php else : ?>
                                                <span
                                                    class="custom-label-sm label-light-success"><?php echo helper_trans('paid'); ?></span>
                                                <?php endif; ?>
                                            </td>

                                            <td><?php echo my_date_show($bill->created_at); ?></td>
                                            <td><?php echo html_escape($bill->number); ?></td>
                                            <td>
                                                <?php if (!empty(helper_get_vendor($bill->customer))) : ?>
                                                <?php echo helper_get_vendor($bill->customer)->name; ?>
                                                <?php $currency_symbol = helper_get_business()->currency_symbol; ?>
                                                <?php endif ?>
                                            </td>
                                            <td><?php if (!empty($currency_symbol)) {
                                                echo html_escape($currency_symbol);
                                            } ?><?php echo html_escape($bill->grand_total); ?></td>
                                            <td>
                                                <?php if (!empty($currency_symbol)) {
                                                    echo html_escape($currency_symbol);
                                                } ?><?php echo $bill->grand_total - get_total_invoice_payments($bill->id, $bill->parent_id); ?>
                                            </td>
                                            <td class="text-right">
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-default rounded btn-sm dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <?php echo helper_trans('actions'); ?>
                                                    </button>
                                                    <div class="dropdown-menu st" x-placement="bottom-start">
                                                        <a class="dropdown-item"
                                                            href="<?php echo url('admin/bills/details/' . md5($bill->id)); ?>"><?php echo helper_trans('view'); ?></a>

                                                        <a class="dropdown-item mr-5"
                                                            href="#recordPayment_<?php echo html_escape($bill->id); ?>"
                                                            data-toggle="modal"><?php echo helper_trans('record-a-payment'); ?></a>

                                                        <a target="_blank" class="dropdown-item"
                                                            href="<?php echo url('readonly/bill/preview/' . md5($bill->id)); ?>"><?php echo helper_trans('preview-as-a-customer'); ?></a>

                                                        <a class="dropdown-item"
                                                            href="<?php echo url('readonly/export_pdf/' . md5($bill->id)); ?>"><?php echo helper_trans('export-as-pdf'); ?></a>
                                                        <div class="dropdown-divider"></div>

                                                        <a class="dropdown-item"
                                                            href="<?php echo url('admin/bills/edit/' . md5($bill->id)); ?>"><?php echo helper_trans('edit'); ?> </a>

                                                        <a class="dropdown-item delete_item"
                                                            data-id="<?php echo html_escape($bill->id); ?>"
                                                            href="<?php echo url('admin/bills/delete/' . $bill->id); ?>"><?php echo helper_trans('delete'); ?></a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach ?>

                                        <?php endif ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>


<?php foreach ($bills as $bill) : ?>
<div id="sendEstimateModal_<?php echo html_escape($bill->id); ?>" class="modal fade estimate_modal" tabindex="-1" role="dialog"
    aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom modal-md">
        <form id="send-estimate-form" method="post" enctype="multipart/form-data"
            class="validate-form send-estimate-form" action="<?php echo url('admin/estimate/send'); ?>" role="form" novalidate>
            @csrf
            <div class="modal-content modal-md">
                <div class="modal-header">
                    <h4 class="modal-title" id="vcenter"><?php echo helper_trans('send-estimate'); ?> <?php echo html_escape($bill->id); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">

                    <div class="form-group row">
                        <label for="inputEmail3"
                            class="col-sm-2 text-right control-label col-form-label"><?php echo helper_trans('to'); ?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="email_to" value="<?php echo !empty(helper_get_vendor($bill->customer)) ? helper_get_vendor($bill->customer)->email : ''; ?>"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3"
                            class="col-sm-2 text-right control-label col-form-label"><?php echo helper_trans('message'); ?></label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="message"> </textarea>
                        </div>
                    </div>

                    <div class="form-group row mt-10">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                            <input type="checkbox" id="md_checkbox_1" class="filled-in chk-col-blue" value="1"
                                name="is_myself" aria-invalid="false">
                            <label for="md_checkbox_1"> <?php echo helper_trans('send-a-copy-to-myself-at'); ?> <b><?php echo user()->email; ?></b></label>
                            <input type="hidden" class="form-control" value="<?php echo user()->email; ?>" name="email_myself">
                        </div>
                    </div>

                </div>

                <div class="modal-footer">


                    <input type="hidden" name="send_estimate_id" class="send_estimate_id"
                        value="<?php echo md5($bill->id); ?>">
                    <input type="hidden" name="customer_id" value="<?php echo html_escape($bill->customer); ?>">
                    <button type="submit"
                        class="btn btn-info rounded waves-effect pull-right submit_btn"><?php echo helper_trans('send'); ?></button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="recordPayment_<?php echo html_escape($bill->id); ?>" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom modal-md">
        <form method="post" enctype="multipart/form-data" class="validate-form" action="<?php echo url('admin/bills/record_payment'); ?>"
            role="form" novalidate>
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="vcenter">Record a payment for this order</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
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
                            class="col-sm-4 text-right control-label col-form-label"><?php echo helper_trans('amount'); ?></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="amount" value="<?php echo html_escape($bill->grand_total - get_total_invoice_payments($bill->id, $bill->parent_id)); ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3"
                            class="col-sm-4 text-right control-label col-form-label"><?php echo helper_trans('payment-method'); ?></label>
                        <div class="col-sm-8">
                            <select class="form-control" id="tax" name="payment_method">
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
                    <input type="hidden" name="invoice_id" value="<?php echo html_escape(md5($bill->id)); ?>">


                    <button type="submit" class="btn btn-info waves-effect pull-right"><?php echo helper_trans('add-payment'); ?></button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php endforeach; ?>