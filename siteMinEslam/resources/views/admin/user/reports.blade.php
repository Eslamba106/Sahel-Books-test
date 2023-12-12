<div class="content-wrapper">
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2>
            <i class="flaticon-bar-chart"></i> <?php echo helper_trans('reports') ?>
            <span class="pull-right"></span>
          </h2>
          <form method="GET" class="sort_report_form validate-form" action="<?php echo url('admin/reports/generate') ?>">
            <div class="reprt-box">
              <div class="row mb-10 pl-15">
                <div class="col-md-4 col-xs-12 mt-5 pl-0">
                  <select class="form-control single_select report_types" required name="report_types">
                    <option value=""><?php echo helper_trans('report-types') ?></option>
                    <option <?php echo (isset($_GET['report_types']) && $_GET['report_types'] == 1) ? 'selected' : ''; ?> value="1"><?php echo helper_trans('invoices') ?></option>
                    <option <?php echo (isset($_GET['report_types']) && $_GET['report_types'] == 2) ? 'selected' : ''; ?> value="2"><?php echo helper_trans('estimates') ?></option>
                    <option <?php echo (isset($_GET['report_types']) && $_GET['report_types'] == 3) ? 'selected' : ''; ?> value="3"><?php echo helper_trans('expenses') ?></option>
                    <option <?php echo (isset($_GET['report_types']) && $_GET['report_types'] == 4) ? 'selected' : ''; ?> value="4"><?php echo helper_trans('trial-balance') ?></option>
                  </select>
                </div>

                <div class="col-md-4 col-xs-12 mt-5 pl-0">
                  <div class="input-group">
                    <input type="text" class="inv-dpick form-control datepicker" placeholder="From" name="start_date" value="<?php if (isset($_GET['start_date'])) {
                                                                                                                                echo $_GET['start_date'];
                                                                                                                              } ?>" autocomplete="off">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>

                <div class="col-md-4 col-xs-12 mt-5 pl-0">
                  <div class="input-group">
                    <input type="text" class="inv-dpick form-control datepicker" placeholder="To" name="end_date" value="<?php if (isset($_GET['end_date'])) {
                                                                                                                            echo $_GET['end_date'];
                                                                                                                          } ?>" autocomplete="off">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>

              <div class="row mt-10 mb-10 pl-15">
                <div class="col-md-3 col-xs-12 mt-5 pl-0">
                  <select class="form-control single_select" name="tax_info">
                    <option value=""><?php echo helper_trans('tax-info') ?></option>
                    <option <?php echo (isset($_GET['tax_info']) && $_GET['tax_info'] == 0) ? 'selected' : ''; ?> value="0"><?php echo helper_trans('all') ?></option>
                    <option <?php echo (isset($_GET['tax_info']) && $_GET['tax_info'] == 1) ? 'selected' : ''; ?> value="1"><?php echo helper_trans('with-tax') ?></option>
                    <option <?php echo (isset($_GET['tax_info']) && $_GET['tax_info'] == 2) ? 'selected' : ''; ?> value="2"><?php echo helper_trans('without-tax') ?></option>
                  </select>
                </div>

                <div class="col-md-3 col-xs-12 mt-5 pl-0">
                  <select class="form-control single_select income_items" name="status">
                    <option value="0"><?php echo helper_trans('all-status') ?></option>
                    <option value="2" <?php echo (isset($_GET['status']) && $_GET['status'] == 2) ? 'selected' : ''; ?>><?php echo helper_trans('paid') ?></option>
                    <option value="1" <?php echo (isset($_GET['status']) && $_GET['status'] == 1) ? 'selected' : ''; ?>><?php echo helper_trans('unpaid') ?></option>
                  </select>
                </div>

                <div class="col-md-3 col-xs-12 mt-5 pl-0 expense_items" style="display: <?php echo (isset($_GET['report_types']) && $_GET['report_types'] == 3) ? 'block' : 'none'; ?>;">
                  <select class="form-control single_select" name="vendor">
                    <option value="0"><?php echo helper_trans('vendors') ?></option>
                    <?php foreach ($vendors as $vendor) : ?>
                      <option value="<?php echo html_escape($vendor->id) ?>" <?php echo (isset($_GET['vendor']) && $_GET['vendor'] == $vendor->id) ? 'selected' : ''; ?>><?php echo html_escape($vendor->name) ?></option>
                    <?php endforeach ?>
                  </select>
                </div>

                <div class="col-md-3 col-xs-12 mt-5 pl-0 income_items" style="display: <?php echo (isset($_GET['report_types']) && $_GET['report_types'] != 3) ? 'block' : 'none'; ?>;">
                  <select class="form-control single_select" name="customer">
                    <option value="0"><?php echo helper_trans('all-customers') ?></option>
                    <?php foreach ($customers as $customer) : ?>
                      <option value="<?php echo html_escape($customer->id) ?>" <?php echo (isset($_GET['customer']) && $_GET['customer'] == $customer->id) ? 'selected' : ''; ?>><?php echo html_escape($customer->name) ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>

              <div class="col-md-12 col-xs-12 mt-15 pl-0">
                <button type="submit" class="btn btn-info btn-report"><i class="fa fa-search"></i> <?php echo helper_trans('show-report') ?></button>
                <a href="<?php echo url('admin/reports') ?>" class="btn btn-default reset-report"><i class="flaticon-reload"></i> <?php echo helper_trans('reset-filter') ?></a>
              </div>
            </div>
          </form>
        </div>

        <?php if (isset($page_title) && $page_title == 'Income Reports') : ?>
          <div class="col-md-12 mt-50">
            <div class="table-responsive">
              <table class="table table-bordered table-hover dt_btn datatable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th><?php echo helper_trans('status') ?></th>
                    <th><?php echo helper_trans('invoice-number') ?></th>
                    <th><?php echo helper_trans('customer') ?></th>
                    <th><?php echo helper_trans('tax') ?></th>
                    <th><?php echo helper_trans('amount') ?></th>
                    <th><?php echo helper_trans('net-amount') ?></th>
                    <th><?php echo helper_trans('date') ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php $total_ex_tax = 0;
                  $total = 0;
                  $r = 1;
                  foreach ($reports as $report) : ?>
                    <tr id="row_<?php echo html_escape($report->id); ?>">
                      <td><?php echo $r; ?></td>
                      <td>
                        <?php if ($report->status == 0) : ?>
                          <span class="custom-label-sm label-light-default"><?php echo helper_trans('draft') ?></span>
                        <?php elseif ($report->status == 2) : ?>
                          <span class="custom-label-sm label-light-success"><?php echo helper_trans('paid') ?></span>
                        <?php elseif ($report->status == 1) : ?>
                          <span class="custom-label-sm label-light-danger"><?php echo helper_trans('unpaid') ?></span>
                        <?php endif ?>
                      </td>
                      <td><?php echo html_escape($report->number); ?></td>
                      <td>
                        <?php if (!empty(helper_get_customer($report->customer))) : ?>
                          <?php echo helper_get_customer($report->customer)->name ?>
                        <?php endif ?>
                      </td>
                      <td><?php echo html_escape($report->tax); ?>%</td>
                      <td><?php echo html_escape(helper_get_business()->currency_symbol . '' . $report->sub_total); ?></td>
                      <td><?php echo html_escape(helper_get_business()->currency_symbol . '' . $report->convert_total); ?></td>
                      <td><span class="label label-default"> <?php echo my_date_show($report->date); ?> </span></td>
                      <?php $total += $report->convert_total; ?>
                      <?php $total_ex_tax += $report->sub_total; ?>
                    </tr>
                  <?php $r++;
                  endforeach; ?>


                  <div class="row report_info">
                    <div class="col-md-6">

                      <p>
                        <span><?php echo helper_trans('report-types') ?>:</span>
                        <?php if (isset($_GET['report_types']) && $_GET['report_types'] == 1) : ?>
                          <?php echo helper_trans('invoices') ?>
                        <?php elseif (isset($_GET['report_types']) && $_GET['report_types'] == 2) : ?>
                          <?php echo helper_trans('estimates') ?>
                        <?php else : ?>
                          <?php echo helper_trans('expenses') ?>
                        <?php endif ?>
                      </p>

                      <p><span><?php echo helper_trans('tax-info') ?>:</span>
                        <?php if (isset($_GET['tax_info']) && $_GET['tax_info'] == 1) : ?>
                          <?php echo helper_trans('with-tax') ?>
                        <?php elseif (isset($_GET['tax_info']) && $_GET['tax_info'] == 2) : ?>
                          <?php echo helper_trans('without-tax') ?>
                        <?php else : ?>
                          <?php echo helper_trans('all') ?>
                        <?php endif ?>
                      </p>

                      <p><span><?php echo helper_trans('status') ?>:</span>
                        <?php if (isset($_GET['status']) && $_GET['status'] == 1) : ?>
                          <?php echo helper_trans('unpaid') ?>
                        <?php elseif (isset($_GET['status']) && $_GET['status'] == 2) : ?>
                          <?php echo helper_trans('paid') ?>
                        <?php else : ?>
                          <?php echo helper_trans('all') ?>
                        <?php endif ?>
                      </p>

                      <p><span><?php echo helper_trans('customers') ?>:</span>
                        <?php if (isset($_GET['customer']) && $_GET['customer'] != 0) : ?>
                          <?php if (!empty(helper_get_customer($_GET['customer']))) : ?>
                            <?php echo helper_get_customer($_GET['customer'])->name ?>
                          <?php endif ?>
                        <?php else : ?>
                          <?php echo helper_trans('all') ?>
                        <?php endif ?>
                      </p>

                      <?php if (isset($_GET['start_date']) && $_GET['start_date'] != '') : ?>
                        <p><span><?php echo helper_trans('start-date') ?>:</span> <?php echo html_escape($_GET['start_date']); ?></p>
                      <?php endif ?>

                      <?php if (isset($_GET['end_date']) && $_GET['end_date'] != '') : ?>
                        <p><span><?php echo helper_trans('end-date') ?>:</span> <?php echo html_escape($_GET['end_date']); ?></p>
                      <?php endif ?>

                    </div>

                    <div class="col-md-6 text-right">
                      <?php if (isset($_GET['tax_info']) && $_GET['tax_info'] == 1) : ?>
                        <p><span><?php echo helper_trans('total') ?> (<?php echo helper_trans('with-tax') ?>):</span> <?php echo helper_get_business()->currency_symbol . money_formats($total); ?></p>
                      <?php elseif (isset($_GET['tax_info']) && $_GET['tax_info'] == 2) : ?>
                        <p><span><?php echo helper_trans('total') ?> (<?php echo helper_trans('without-tax') ?>):</span> <?php echo helper_get_business()->currency_symbol . money_formats($total_ex_tax); ?></p>
                      <?php else : ?>
                        <p><span><?php echo helper_trans('total') ?>:</span> <?php echo helper_get_business()->currency_symbol . money_formats($total); ?></p>
                      <?php endif ?>
                    </div>
                  </div>

                </tbody>
              </table>
            </div>
          </div>

        <?php endif ?>

        <?php if (isset($page_title) && $page_title == 'Expense Reports') : ?>
          <div class="col-md-12 mt-50">
            <div class="table-responsive">
              <table class="table table-bordered table-hover dt_btn datatable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th><?php echo helper_trans('vendors') ?></th>
                    <th><?php echo helper_trans('tax') ?></th>
                    <th><?php echo helper_trans('amount') ?></th>
                    <th><?php echo helper_trans('net-amount') ?></th>
                    <th><?php echo helper_trans('date') ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php $total_ex_tax = 0;
                  $total = 0;
                  $e = 1;
                  foreach ($reports as $report) : ?>
                    <tr id="row_<?php echo html_escape($report->id); ?>">
                      <td><?php echo $e; ?></td>
                      <td>
                        <?php if (!empty(helper_get_vendor($report->vendor))) : ?>
                          <?php echo helper_get_vendor($report->vendor)->name ?>
                        <?php endif ?>
                      </td>
                      <td><?php echo html_escape($report->tax); ?>%</td>
                      <td><?php echo html_escape(helper_get_business()->currency_symbol . '' . $report->amount); ?></td>
                      <td><?php echo html_escape(helper_get_business()->currency_symbol . '' . $report->net_amount); ?></td>
                      <td><span class="label label-default"> <?php echo my_date_show($report->date); ?> </span></td>
                      <?php $total += $report->net_amount; ?>
                      <?php $total_ex_tax += $report->amount; ?>
                    </tr>
                  <?php $e++;
                  endforeach; ?>

                  <div class="row report_info">
                    <div class="col-md-6">

                      <p>
                        <span><?php echo helper_trans('report-types') ?>:</span>
                        <?php if (isset($_GET['report_types']) && $_GET['report_types'] == 1) : ?>
                          <?php echo helper_trans('invoices') ?>
                        <?php elseif (isset($_GET['report_types']) && $_GET['report_types'] == 2) : ?>
                          <?php echo helper_trans('estimates') ?>
                        <?php else : ?>
                          <?php echo helper_trans('expenses') ?>
                        <?php endif ?>
                      </p>

                      <p><span><?php echo helper_trans('tax-info') ?>:</span>
                        <?php if (isset($_GET['tax_info']) && $_GET['tax_info'] == 1) : ?>
                          <?php echo helper_trans('with-tax') ?>
                        <?php elseif (isset($_GET['tax_info']) && $_GET['tax_info'] == 2) : ?>
                          <?php echo helper_trans('without-tax') ?>
                        <?php else : ?>
                          <?php echo helper_trans('all') ?>
                        <?php endif ?>
                      </p>

                      <p><span><?php echo helper_trans('vendors') ?>:</span>
                        <?php if (isset($_GET['vendor']) && $_GET['vendor'] != 0) : ?>
                          <?php if (!empty(helper_get_vendor($_GET['vendor']))) : ?>
                            <?php echo helper_get_vendor($_GET['vendor'])->name ?>
                          <?php endif ?>
                        <?php else : ?>
                          <?php echo helper_trans('all') ?>
                        <?php endif ?>
                      </p>

                      <?php if (isset($_GET['start_date']) && $_GET['start_date'] != '') : ?>
                        <p><span><?php echo helper_trans('start-date') ?>:</span> <?php echo html_escape($_GET['start_date']); ?></p>
                      <?php endif ?>

                      <?php if (isset($_GET['end_date']) && $_GET['end_date'] != '') : ?>
                        <p><span><?php echo helper_trans('end-date') ?>:</span> <?php echo html_escape($_GET['end_date']); ?></p>
                      <?php endif ?>

                    </div>

                    <div class="col-md-6 text-right">
                      <?php if (isset($_GET['tax_info']) && $_GET['tax_info'] == 1) : ?>
                        <p><span><?php echo helper_trans('total') ?> (<?php echo helper_trans('with-tax') ?>):</span> <?php echo helper_get_business()->currency_symbol . money_formats($total); ?></p>
                      <?php elseif (isset($_GET['tax_info']) && $_GET['tax_info'] == 2) : ?>
                        <p><span><?php echo helper_trans('total') ?> (<?php echo helper_trans('without-tax') ?>):</span> <?php echo helper_get_business()->currency_symbol . money_formats($total_ex_tax); ?></p>
                      <?php else : ?>
                        <p><span><?php echo helper_trans('total') ?>:</span> <?php echo helper_get_business()->currency_symbol . money_formats($total); ?></p>
                      <?php endif ?>
                    </div>
                  </div>
                </tbody>
              </table>
            </div>
          </div>
        <?php endif ?>
        <?php if (isset($page_title) && $page_title == 'Trial Balance Reports') : ?>
          <?php $this->load->view('admin/user/reports/trial_balance_report') ?>
        <?php endif ?>

      </div>
    </div>
  </section>
</div>