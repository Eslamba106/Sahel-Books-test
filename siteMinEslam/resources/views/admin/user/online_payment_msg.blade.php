<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <div class="pay-box pt-200">
                        <?php if (isset($success_msg) && $success_msg == 'Success') : ?>
                            <h1 class="success"><i class="icon-check"></i><br> <?php echo helper_trans('done') ?></h1>
                            <h5 class="success"><?php echo helper_trans('payment-success-msg') ?></h5><br>

                            <a href="<?php echo url('admin/invoice/type/3') ?>" class="btn btn-md btn-info"><i class="fa fa-long-arrow-left"></i> <?php echo helper_trans('back') ?></a>
                        <?php endif; ?>
                        <?php if (isset($error_msg) && $error_msg == 'Error') : ?>
                            <h1 class="text-danger"><i class="icon-close"></i><br> <?php echo helper_trans('failed') ?>!</h1>
                            <h5 class="text-danger"><?php echo helper_trans('payment-error-msg') ?></h5><br>

                            <a href="<?php echo url('admin/invoice/type/1') ?>" class="btn btn-md btn-info"><i class="fa fa-long-arrow-left"></i> <?php echo helper_trans('back') ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>