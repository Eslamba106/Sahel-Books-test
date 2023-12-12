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
                    <div class="row mb-10">
                        <div class="col-md-6">
                            <a href="#" class="btn btn-default btn-rounded mr-5 print_invoice"><i
                                    class="fa fa-print"></i> <?php echo helper_trans('print'); ?> </a>

                            <a href="<?php echo url('readonly/export_pdf/' . md5($invoice->id)); ?>" class="btn btn-default btn-rounded mr-5"><i
                                    class="fa fa-download"></i> <?php echo helper_trans('download-pdf'); ?> </a>
                        </div>

                        <div class="col-md-6">
                            <?php if ($invoice->status == 0) : ?>
                            <a href="<?php echo url('readonly/approve/1/' . md5($invoice->id)); ?>" class="btn btn-info btn-rounded mr-5 pull-right"><i
                                    class="fa fa-check"></i> <?php echo helper_trans('approve'); ?> </a>

                            <a href="#rejectModal" data-toggle="modal"
                                class="btn btn-danger btn-rounded mr-5 pull-right"><i class="fa fa-times"></i>
                                Reject</a>
                            <?php elseif ($invoice->status == 1) : ?>
                            <label class="label label-success pull-right mt-10"><?php echo helper_trans('approved'); ?></label>
                            <?php elseif ($invoice->status == 2) : ?>
                            <label class="label label-danger pull-right mt-10"><?php echo helper_trans('rejected'); ?></label>
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




    <div id="rejectModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo helper_trans('reject-reason'); ?></h4>
                </div>
                <form method="get" class="validate-form" action="<?php echo url('readonly/approve/2/' . md5($invoice->id)); ?>" role="form" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label><?php echo helper_trans('describe-reject-reason'); ?></label>
                            <textarea class="form-control" name="reject_reason"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">


                        <button type="submit" class="btn btn-info pull-left"><i class="fa fa-check"></i>
                            <?php echo helper_trans('submit'); ?></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <footer></footer>
    <!-- jQuery 3 -->
    <script src="<?php echo asset(''); ?>admin/js/jquery3.min.js"></script>
    <!-- popper -->
    <script src="<?php echo asset(''); ?>admin/js/popper.min.js"></script>
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

    <script type="text/javascript">
        $(window).on('load', function() {
            $('#editModal').modal('show');
        });
    </script>

</body>

</html>
