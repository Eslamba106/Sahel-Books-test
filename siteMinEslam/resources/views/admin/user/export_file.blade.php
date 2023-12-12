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

    <?php
    
    if ($invoice->type == 3) {
        $currency_symbol = helper_get_business()->currency_symbol;
        // $cus_number = '';
        // $cus_vat_code = '';
    } else {
        $currency_symbol = helper_get_customer($invoice->customer)->currency_symbol;
        // $cus_number = helper_get_customer($invoice->customer)->cus_number;
        // $cus_vat_code = helper_get_customer($invoice->customer)->vat_code;
    }
    
    ?>
    <div class="content-wrappers">
        <section class="content p-0">
            <?php if (isset($mode) && $mode == 'preview') : ?>
            <div class="preview-mood-top p-20 text-center readonly-title">
                <a href="#" class="btn btn-default btn-rounded mr-5 disabled"><i class="fa fa-eye"></i>
                    <?php echo helper_trans('preview-mode'); ?> </a>

                <?php if (isset($link) && $link != '') : ?>
                <a href="<?php echo $link; ?>" class="btn btn-default btn-rounded mr-5"><i
                        class="fa fa-long-arrow-left"></i> <?php echo helper_trans('back'); ?> </a>
                <?php endif ?>
                <p class="mt-10 c-1038"><i class="fa fa-info-circle"></i> <?php echo helper_trans('preview-mode-msg'); ?></p>
            </div>
            <?php endif ?>

            <div class="container">
                <div class="col-md-10" style="margin: 20px auto">
                    <div id="invoice_save_area mt-0" class="card inv save_area print_area">
                        @include('admin.user.include.invoice_export_style')
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
</body>

</html>
