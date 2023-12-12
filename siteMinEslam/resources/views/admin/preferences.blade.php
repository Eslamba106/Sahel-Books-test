<div class="content-wrapper">

  <!-- Main content -->
  <section class="content container">
    <div class="col-md-8">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><?php echo helper_trans('preferences') ?></h3>
        </div>

        <div class="box-body">

          <form method="post" enctype="multipart/form-data" action="<?php echo url('admin/settings/update_preferences') ?>" role="form" class="form-horizontal">
            @csrf

            <div class="form-group flex-parent-between mb-20">
              <label class="mt-5"><?php echo helper_trans('enable-frontend') ?>
                <br><small class="fs-14 text-muted"><i class="fa fa-info-circle"></i> <?php echo helper_trans('enable-frontend-info') ?> </small>
              </label>
              <div class="switch">
                <input type="checkbox" name="enable_frontend" value="1" <?php if (settings()->enable_frontend == 1) {
                                                                          echo 'checked';
                                                                        } ?> data-toggle="toggle" data-onstyle="info" data-width="100">
              </div>

            </div>

            <div class="form-group flex-parent-between mb-20">
              <label class="mt-5"><?php echo helper_trans('enable-multilingual') ?>
                <br><small class="fs-14 text-muted"><i class="fa fa-info-circle"></i> <?php echo helper_trans('enable-multilingual-info') ?></small>
              </label>
              <div class="switch">
                <input type="checkbox" name="enable_multilingual" value="1" <?php if (settings()->enable_multilingual == 1) {
                                                                              echo 'checked';
                                                                            } ?> data-toggle="toggle" data-onstyle="info" data-width="100">
              </div>
            </div>

            <div class="form-group flex-parent-between mb-20">
              <label class="mt-5">Google reCaptcha
                <br><small class="fs-14 text-muted"><i class="fa fa-info-circle"></i> <?php echo helper_trans('enable-captcha-info') ?></small>
              </label>
              <div class="switch">
                <input type="checkbox" name="enable_captcha" value="1" <?php if (settings()->enable_captcha == 1) {
                                                                          echo 'checked';
                                                                        } ?> data-toggle="toggle" data-onstyle="info" data-width="100">
              </div>
            </div>

            <div class="form-group flex-parent-between mb-20">
              <label class="mt-5"><?php echo helper_trans('registration-system') ?>
                <br><small class="fs-14 text-muted"><i class="fa fa-info-circle"></i> <?php echo helper_trans('registration-system-info') ?></small>
              </label>
              <div class="switch">
                <input type="checkbox" name="enable_registration" value="1" <?php if (settings()->enable_registration == 1) {
                                                                              echo 'checked';
                                                                            } ?> data-toggle="toggle" data-onstyle="info" data-width="100">
              </div>
            </div>

            <div class="form-group flex-parent-between mb-20">
              <label class="mt-5"><?php echo helper_trans('email-verification') ?>
                <br><small class="fs-14 text-muted"><i class="fa fa-info-circle"></i> <?php echo helper_trans('email-verification-info') ?></small>
              </label>
              <div class="switch">
                <input type="checkbox" name="enable_email_verify" value="1" <?php if (settings()->enable_email_verify == 1) {
                                                                              echo 'checked';
                                                                            } ?> data-toggle="toggle" data-onstyle="info" data-width="100">
              </div>
            </div>

            <div class="form-group flex-parent-between mb-20">
              <label class="mt-5"><?php echo helper_trans('enable-payment') ?>
                <br><small class="fs-14 text-muted"><i class="fa fa-info-circle"></i> <?php echo helper_trans('enable-payment-info') ?></small>
              </label>
              <div class="switch">
                <input type="checkbox" name="enable_paypal" value="1" <?php if (settings()->enable_paypal == 1) {
                                                                        echo 'checked';
                                                                      } ?> data-toggle="toggle" data-onstyle="info" data-width="100">
              </div>
            </div>

            <div class="form-group flex-parent-between mb-20">
              <label class="mt-5"><?php echo helper_trans('delete') . ' ' . helper_trans('invoice') ?>
                <br><small class="fs-14 text-muted"><i class="fa fa-info-circle"></i> <?php echo helper_trans('delete-invoice-info') ?></small>
              </label>
              <div class="switch">
                <input type="checkbox" name="enable_delete_invoice" value="1" <?php if (settings()->enable_delete_invoice == 1) {
                                                                                echo 'checked';
                                                                              } ?> data-toggle="toggle" data-onstyle="info" data-width="100">
              </div>
            </div>

            <div class="form-group flex-parent-between mb-20">
              <label class="mt-5"><?php echo helper_trans('discount') ?>
                <br><small class="fs-14 text-muted"><i class="fa fa-info-circle"></i> <?php echo helper_trans('discount-info') ?></small>
              </label>
              <div class="switch">
                <input type="checkbox" name="enable_discount" value="1" <?php if (settings()->enable_discount == 1) {
                                                                          echo 'checked';
                                                                        } ?> data-toggle="toggle" data-onstyle="info" data-width="100">
              </div>
            </div>

            <div class="form-group flex-parent-between mb-20">
              <label class="mt-5"><?php echo helper_trans('blogs') ?>
                <br><small class="fs-14 text-muted"><i class="fa fa-info-circle"></i> <?php echo helper_trans('blogs-info') ?></small>
              </label>
              <div class="switch">
                <input type="checkbox" name="enable_blog" value="1" <?php if (settings()->enable_blog == 1) {
                                                                      echo 'checked';
                                                                    } ?> data-toggle="toggle" data-onstyle="info" data-width="100">
              </div>
            </div>

            <div class="form-group flex-parent-between mb-20">
              <label class="mt-5"><?php echo helper_trans('faqs') ?>
                <br><small class="fs-14 text-muted"><i class="fa fa-info-circle"></i> <?php echo helper_trans('faqs-info') ?></small>
              </label>
              <div class="switch">
                <input type="checkbox" name="enable_faq" value="1" <?php if (settings()->enable_faq == 1) {
                                                                      echo 'checked';
                                                                    } ?> data-toggle="toggle" data-onstyle="info" data-width="100">
              </div>
            </div>



            <button type="submit" class="btn btn-info btn-md btn-block mt-20 waves-effect w-md waves-light m-b-5"><i class="fa fa-check"></i> <?php echo helper_trans('save-changes') ?></button>
          </form>

        </div>
      </div>
    </div>
  </section>
</div>