<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 text-center">
                <h2><?php echo helper_trans('get-in-touch') ?></h2>
                <form method="post" action="<?php echo url('home/send_message'); ?>">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="input-wrp">
                                <input class="textfield textfield--grey" placeholder="<?php echo helper_trans('full-name') ?>" name="name" type="text" />
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="input-wrp">
                                <input class="textfield textfield--grey" placeholder="<?php echo helper_trans('email') ?>" name="email" type="email" inputmode="email" x-inputmode="email" required />
                            </div>
                        </div>
                    </div>

                    <label class="input-wrp">
                        <textarea class="textfield textfield--grey" placeholder="<?php echo helper_trans('write-your-message') ?>" name="message" required></textarea>
                    </label>

                    <div class="input-wrp">
                        <?php if ($settings->enable_captcha == 1 && $settings->captcha_site_key != '') : ?>
                            <div class="g-recaptcha pull-left" data-sitekey="<?php echo html_escape($settings->captcha_site_key); ?>"></div>
                        <?php endif ?>
                    </div>



                    <button class="custom-btn custom-btn--medium custom-btn--style-3" type="submit" role="button"><?php echo helper_trans('send') ?></button>

                    <div class="form__note"></div>
                </form>
            </div>
            <div class="spacer py-4 d-lg-none"></div>
        </div>
    </div>
</section>