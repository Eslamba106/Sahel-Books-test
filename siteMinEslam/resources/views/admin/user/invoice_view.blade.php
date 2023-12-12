<?php $settings = get_settings(); ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="icon" href="<?php echo url($settings->favicon); ?>">
    <title><?php echo html_escape($settings->site_name); ?> - <?php if (isset($page_title)) {
        echo html_escape($page_title);
    } else {
        echo 'Dashboard';
    } ?></title>
    <!-- Bootstrap 4.0-->
    <link rel="stylesheet" href="<?php echo asset(''); ?>admin/css/bootstrap.min.css">
    <!-- Bootstrap 4.0-->
    <link rel="stylesheet" href="<?php echo asset(''); ?>admin/css/bootstrap-extend.css">
    <link rel="stylesheet" href="<?php echo asset(''); ?>admin/css/master_style.css">
    <link rel="stylesheet" href="<?php echo asset(''); ?>admin/css/skins/_all-skins.css">
    <link rel="stylesheet" href="<?php echo asset(''); ?>admin/css/font-awesome.min.css">
</head>

<body>

    <?php $currency_symbol = helper_get_customer($invoice->customer)->currency_symbol; ?>
    <div class="content-wrappers">
        <section class="content p-0">

            <?php if (isset($mode) && $mode == 'preview') : ?>
            <div class="preview-mood-top p-20 text-center">
                <a href="#" class="btn btn-default btn-rounded mr-5 disabled"><i class="fa fa-eye"></i>
                    <?php echo helper_trans('preview-mode'); ?> </a>

                <?php if (isset($link) && $link != '') : ?>
                <a href="<?php echo html_escape($link); ?>" class="btn btn-default btn-rounded mr-5"><i
                        class="fa fa-long-arrow-left"></i> <?php echo helper_trans('back'); ?> </a>
                <?php endif ?>
                <p class="mt-10 c-1038"><i class="fa fa-info-circle"></i> <?php echo helper_trans('preview-mode-msg'); ?> <?php echo html_escape($page); ?></p>
            </div>
            <?php endif ?>

            <div class="readonly-title">
                <h2><?php echo helper_trans('invoice'); ?> <?php echo html_escape($invoice->number); ?> <?php echo helper_trans('from'); ?> - <?php echo html_escape($invoice->business_name); ?></h2>
            </div>

            <div class="readonly-top-bar">
                <?php echo helper_trans('invoice'); ?> <?php echo html_escape($invoice->number); ?> <i class="fa fa-circle"></i>

                <?php if ($invoice->status == 2) : ?>
                <?php echo helper_trans('amount-due'); ?>: <?php if (!empty($currency_symbol)) {
                    echo html_escape($currency_symbol);
                } ?>0.00
                <?php else : ?>
                <?php echo helper_trans('amount-due'); ?>: <?php if (!empty($currency_symbol)) {
                    echo html_escape($currency_symbol);
                } ?><?php echo number_format($invoice->grand_total - get_total_invoice_payments($invoice->id, $invoice->parent_id), 2); ?>
                <?php endif ?> <i class="fa fa-circle"></i>
                <?php echo helper_trans('due-on'); ?>: <?php echo my_date_show($invoice->created_at); ?>
            </div>

            <div class="container">
                <div class="col-md-10 m-auto">

                    <div class="row mb-10">
                        <div class="col-md-6">
                            <a href="#" class="btn btn-default btn-rounded mr-5 print_invoice"><i
                                    class="fa fa-print"></i> <?php echo helper_trans('print'); ?> </a>

                            <a href="<?php echo url('readonly/export_pdf/' . md5($invoice->id)); ?>" class="btn btn-default btn-rounded mr-5"><i
                                    class="fa fa-download"></i> <?php echo helper_trans('download-pdf'); ?> </a>

                            <?php if (check_package_limit('invoice-payments') == -1) : ?>
                            <a href="<?php echo url('admin/payment/online/1/' . md5($invoice->id)); ?>" class="btn btn-info btn-rounded mr-5"><i
                                    class="fa fa-dollar"></i> <?php echo helper_trans('pay-online'); ?> </a>
                            <?php endif ?>
                        </div>

                        <div class="col-md-6 text-right mt-15">
                            <?php if ($invoice->status == 0) : ?>
                            <span data-toggle="tooltip" data-placement="right" title="<?php echo helper_trans('approve-info'); ?>"
                                class="custom-label-sm label-light-default pull-right"><?php echo helper_trans('draft'); ?></span>
                            <?php elseif ($invoice->status == 2) : ?>
                            <span data-toggle="tooltip" data-placement="right" title="<?php echo helper_trans('paid-info'); ?>"
                                class="custom-label-sm label-light-success pull-right"><?php echo helper_trans('paid'); ?></span>
                            <?php elseif ($invoice->status == 1) : ?>
                            <span data-toggle="tooltip" data-placement="right" title="<?php echo helper_trans('unpaid-info'); ?>"
                                class="custom-label-sm label-light-danger pull-right"><?php echo helper_trans('unpaid'); ?></span>
                            <?php endif ?>
                        </div>
                    </div>

                    <div id="invoice_save_area mt-0" class="card inv save_area print_area">
                        @include('admin.user.include.invoice_style_1')
                    </div>
                </div>
            </div>
        </section>
    </div>


    <footer></footer>
    <!-- jQuery 3 -->
    <script src="<?php echo asset(''); ?>admin/js/jquery3.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo asset(''); ?>admin/js/bootstrap.min.js"></script>
    <script src="<?php echo asset(''); ?>admin/js/printThis.js"></script>
    <script>
        $('.print_invoice').on("click", function() {
            $('.print_area').printThis({
                base: "https://jasonday.github.io/printThis/"
            });
        });
    </script>
</body>

</html>
