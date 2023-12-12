<?php $settings = get_settings(); ?>
<div class="content-wrapper">
    <!-- Start content -->
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-md-12" style="display: block;">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Language Values</h3>
                        </div>
                        <div class="box-body">
                            <form method="post" class="validate-form" action="<?php echo url('admin/language/add_value'); ?>" role="form"
                                novalidate>
                                @csrf

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo helper_trans('label'); ?>label</label>
                                            <input type="text" class="form-control"
                                                placeholder="Example: Home / Register User" name="label" required>
                                        </div><br>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo helper_trans('keyword'); ?>keyword</label>
                                            <input type="text" class="form-control"
                                                placeholder="Example: home / register_user" name="keyword" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label p-0"
                                                for="example-input-normal">Type</label>
                                            <select class="form-control" name="type" required>
                                                <option value="">Select</option>
                                                <option value="front">Frontend</option>
                                                <option value="admin">Admin</option>
                                                <option value="user">User</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <input type="hidden" name="lang" value="<?php echo html_escape($value); ?>">

                                <div class="m-t-15">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-info pull-right"><i
                                                class="fa fa-check"></i> <?php echo helper_trans('save'); ?></button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 add_area">
                    <div class="box input_area">
                        <div class="box-header">

                            <h5 class="box-title pull-<?php echo $settings->dir == 'rtl' ? 'right' : 'left'; ?>"><?php echo helper_trans('update-language-for'); ?> ( <?php echo ucfirst($value); ?> )
                            </h5>

                            <div class="ml-10 dropdown pull-<?php echo $settings->dir == 'rtl' ? 'left' : 'right'; ?>">
                                <button type="button" class="btn btn-default dropdown-toggle btn-sm"
                                    data-toggle="dropdown">
                                    <?php echo ucfirst($type); ?>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="<?php echo url('admin/language/values/front/' . $value); ?>"><?php echo helper_trans('frontend'); ?></a>
                                    <a class="dropdown-item" href="<?php echo url('admin/language/values/admin/' . $value); ?>"><?php echo helper_trans('admin'); ?></a>
                                    <a class="dropdown-item" href="<?php echo url('admin/language/values/user/' . $value); ?>"><?php echo helper_trans('user'); ?></a>
                                </div>
                            </div>

                            <div class="dropdown pull-<?php echo $settings->dir == 'rtl' ? 'left' : 'right'; ?>">
                                <button type="button" class="btn btn-default dropdown-toggle btn-sm"
                                    data-toggle="dropdown">
                                    <i class="fa fa-language"></i> <?php echo ucfirst($value); ?>
                                </button>
                                <div class="dropdown-menu">
                                    <?php $lang_list = get_language(); ?>
                                    <?php foreach ($lang_list as $lg) : ?>
                                    <a class="dropdown-item" href="<?php echo url('admin/language/values/' . $type . '/' . $lg->slug); ?>"><?php echo ucfirst($lg->name); ?></a>
                                    <?php endforeach ?>
                                </div>
                            </div>


                        </div>
                        <div class="box-body">

                            <form method="post" class="validate-form" action="<?php echo url('admin/language/update_values/' . $type); ?>" role="form"
                                novalidate>
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit"
                                            class="mb-10 btn btn-info pull-<?php echo $settings->dir == 'rtl' ? 'right' : 'left'; ?> m-t-20"><i
                                                class="fa fa-check"></i> <?php echo helper_trans('update-values'); ?></button>
                                    </div>
                                    <div class="col-sm-6">

                                    </div>
                                </div>

                                <table class="table table-bordered table-striped dataTable">
                                    <thead>
                                        <tr role="row">
                                            <th>#</th>
                                            <th><?php echo helper_trans('type'); ?></th>
                                            <th><?php echo helper_trans('label'); ?></th>
                                            <th><?php echo helper_trans('keyword'); ?></th>
                                            <th><?php echo helper_trans('value'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1;
                    foreach ($language as $lang) : ?>
                                        <tr class="tr-phrase">
                                            <td style="width: 50px;"><?php echo $i; ?></td>
                                            <td style="width: 30%;"><span
                                                    class="label label-inverse"><?php echo ucfirst($lang->type); ?></span></td>
                                            <td style="width: 30%;"><input type="text" class="form-control bg-g"
                                                    value="<?php echo html_escape($lang->label); ?>" readonly></td>
                                            <td style="width: 15%;"><input type="text" class="form-control bg-g"
                                                    value="<?php echo html_escape($lang->keyword); ?>" readonly></td>
                                            <td style="width: 65%;" <?php if ($value == 'arabic') {
                                                echo "dir='rtl'";
                                            } ?>><input type="text"
                                                    name="value<?php echo html_escape($lang->id); ?>" class="form-control"
                                                    value="<?php echo html_escape($lang->$value); ?>"></td>
                                        </tr>
                                        <?php $i++;
                    endforeach ?>

                                    </tbody>
                                </table>

                                <input type="hidden" name="lang_type" value="<?php echo html_escape($value); ?>">

                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit"
                                            class="btn btn-info pull-<?php echo $settings->dir == 'rtl' ? 'right' : 'left'; ?> m-t-20"><i
                                                class="fa fa-check"></i><?php echo helper_trans('update-values'); ?></button>
                                    </div>
                                    <div class="col-sm-6">

                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
