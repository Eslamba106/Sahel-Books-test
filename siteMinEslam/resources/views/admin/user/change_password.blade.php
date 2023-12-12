<div class="content-wrapper">
    <section class="content container">

        <div class="nav-tabs-custom">

            @include('admin.user.include.profile_menu')

            <div class="row m-5 mt-20">
                <div class="col-md-8 box">

                    <div class="box-header">
                        <h3 class="box-title"><?php echo helper_trans('change-password'); ?></h3>
                    </div>

                    <div class="box-body p-10">

                        <form method="post" id="cahage_pass_form" action="<?php echo url('admin/dashboard/change'); ?>">
                            @csrf

                            <div class="col-md-12 mt-20">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label><?php echo helper_trans('old-password'); ?></label>
                                            <input type="password" class="form-control" name="old_pass" />
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label><?php echo helper_trans('new-password'); ?></label>
                                            <input type="password" class="form-control" name="new_pass" />
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label><?php echo helper_trans('confirm-new-password'); ?></label>
                                            <input type="password" class="form-control" name="confirm_pass" />
                                        </div>
                                    </div>




                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-info"><?php echo helper_trans('change'); ?></button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>

    </section>
</div>
