<div class="content-wrapper">

  <!-- Main content -->
  <section class="content">

    <?php if (isset($page_title) && $page_title != "Edit") : ?>
      <div class="list_area container">

        <?php if (isset($page_title) && $page_title == "Edit") : ?>
          <h3 class="box-title"><?php echo helper_trans('edit-package') ?> <a href="<?php echo url('admin/package') ?>" class="pull-right btn btn-primary btn-sm"><i class="fa fa-angle-left"></i> <?php echo helper_trans('back') ?></a></h3>
        <?php else : ?>
        <?php endif; ?>

        <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
          <div class="col-md-12 col-sm-12 col-xs-12 scroll">
            <table class="table table-hover table-stripe">
              <tbody>
                <thead class="thead-dark">
                  <tr>
                    <td width="30%">
                      <h3 class="font-weight-normal"><?php echo helper_trans('manage-packages') ?></h3>
                    </td>
                    <?php $i = 1;
                    foreach ($packages as $package) : ?>
                      <td class="text-center">
                        <h2 class="mt-10"><span class="label label-info brd-30"><?php echo html_escape($package->name); ?></span> </h2>
                        <p class="mb-15 fs-20"><?php echo currency_to_symbol(settings()->currency); ?><?php echo round($package->price); ?> <span class="fs-14">per year</span> <br> <?php echo currency_to_symbol(settings()->currency); ?><?php echo round($package->monthly_price); ?> <span class="fs-14"> <?php echo helper_trans('per-month') ?></p>

                        <a href="#packageModal_<?php echo html_escape($package->id); ?>" data-toggle="modal" class="btn btn-default" data-placement="top" title="Edit"><i class="fa fa-pencil"></i> <?php echo helper_trans('edit-package') ?></a>
                      </td>
                    <?php $i++;
                    endforeach; ?>
                    <td></td>
                  </tr>
                </thead>

                <?php foreach ($features as $feature) : ?>
                  <tr>
                    <td width="30%"><?php echo html_escape($feature->name); ?> <br><?php if (!empty($feature->text)) {
                                                                                      echo html_escape('(' . $feature->text . ')');
                                                                                    } ?></td>
                    <td class="text-center">
                      <?php if ($feature->basic == 0) : ?>
                        <p class="mb-0 feature-item"><i class="fa fa-times text-danger"></i></p>
                      <?php elseif ($feature->basic == -1) : ?>
                        <p class="mb-0 feature-item"><i class="fa fa-check text-success"></i></p>
                      <?php elseif ($feature->basic == -2) : ?>
                        <p class="mb-0 feature-item"><?php echo helper_trans('unlimited') ?></p>
                      <?php else : ?>
                        <?php echo helper_trans('monthly') ?> <strong><?php echo html_escape($feature->basic); ?></strong> <span class="vr"></span>
                        <?php echo helper_trans('yearly') ?> <strong><?php echo html_escape($feature->year_basic); ?></strong>
                      <?php endif ?>
                    </td>
                    <td class="text-center">
                      <?php if ($feature->standared == 0) : ?>
                        <p class="mb-0 feature-item"><i class="fa fa-times text-danger"></i></p>
                      <?php elseif ($feature->standared == -1) : ?>
                        <p class="mb-0 feature-item"><i class="fa fa-check text-success"></i></p>
                      <?php elseif ($feature->standared == -2) : ?>
                        <p class="mb-0 feature-item"><?php echo helper_trans('unlimited') ?></p>
                      <?php else : ?>
                        <?php echo helper_trans('monthly') ?> <strong><?php echo html_escape($feature->standared); ?></strong><span class="vr"></span>
                        <?php echo helper_trans('yearly') ?> <strong><?php echo html_escape($feature->year_standared); ?></strong>
                      <?php endif ?>
                    </td>
                    <td class="text-center">
                      <?php if ($feature->premium == 0) : ?>
                        <p class="mb-0 feature-item"><i class="fa fa-times text-danger"></i></p>
                      <?php elseif ($feature->premium == -1) : ?>
                        <p class="mb-0 feature-item"><i class="fa fa-check text-success"></i></p>
                      <?php elseif ($feature->premium == -2) : ?>
                        <p class="mb-0 feature-item"><?php echo helper_trans('unlimited') ?></p>
                      <?php else : ?>
                        <?php echo helper_trans('monthly') ?> <strong><?php echo html_escape($feature->premium); ?></strong><span class="vr"></span>
                        <?php echo helper_trans('yearly') ?> <strong><?php echo html_escape($feature->year_premium); ?></strong>
                      <?php endif ?>
                    </td>
                    <td width="5%"><a href="#featureModal_<?php echo html_escape($feature->id); ?>" data-toggle="modal" class="btn btn-default" data-placement="top" title="Edit"><i class="fa fa-pencil"></i> <?php echo helper_trans('edit-features') ?></a></td>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    <?php endif; ?>
  </section>
</div>



<?php foreach ($features as $feature) : ?>
  <div id="featureModal_<?php echo html_escape($feature->id) ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom modal-md">
      <form method="post" enctype="multipart/form-data" class="validate-form" action="<?php echo url('admin/package/update_features/' . $feature->id) ?>" role="form" novalidate>
        @csrf
        <div class="modal-content modal-lg">
          <div class="modal-header">
            <h4 class="modal-title" id="vcenter"><?php echo helper_trans('update') ?> - <?php echo html_escape($feature->name) ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">

            <div class="nav-tabs-customs">

              <div class="row">

                <div class="col-md-12 mb-20">
                  <div class="form-group row">
                    <label class="col-sm-12 text-left control-label col-form-label"><?php echo helper_trans('name') ?></label>
                    <div class="col-sm-12">
                      <input type="text" class="form-control" name="name" value="<?php echo html_escape($feature->name) ?>">
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <!-- mohthly -->
                  <div class="monthly_area">
                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-12 text-left control-label col-form-label"><?php echo helper_trans('monthly') ?> <?php echo helper_trans('basic-limit') ?></label>
                      <div class="col-sm-12">
                        <input type="number" class="form-control" name="basic" value="<?php echo $feature->basic; ?>">
                        <p class="small text-info"><i class="fa fa-info-circle"></i> <?php echo helper_trans('limit-suggestions'); ?></p>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-12 text-left control-label col-form-label"><?php echo helper_trans('monthly') ?> <?php echo helper_trans('standared-limit') ?></label>
                      <div class="col-sm-12">
                        <input type="number" class="form-control" name="standared" value="<?php echo $feature->standared; ?>">
                        <p class="small text-info"><i class="fa fa-info-circle"></i> <?php echo helper_trans('limit-suggestions'); ?></p>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-12 text-left control-label col-form-label"><?php echo helper_trans('monthly') ?> <?php echo helper_trans('premium-limit') ?></label>
                      <div class="col-sm-12">
                        <input type="number" class="form-control" name="premium" value="<?php echo $feature->premium; ?>">
                        <p class="small text-info"><i class="fa fa-info-circle"></i> <?php echo helper_trans('limit-suggestions'); ?></p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <!-- yearly -->
                  <div class="yearly_area">
                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-12 text-left control-label col-form-label"><?php echo helper_trans('yearly') ?> <?php echo helper_trans('basic-limit') ?></label>
                      <div class="col-sm-12">
                        <input type="number" class="form-control" name="year_basic" value="<?php echo $feature->year_basic; ?>">
                        <p class="small text-info"><i class="fa fa-info-circle"></i> <?php echo helper_trans('limit-suggestions'); ?></p>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-12 text-left control-label col-form-label"><?php echo helper_trans('yearly') ?> <?php echo helper_trans('standared-limit') ?></label>
                      <div class="col-sm-12">
                        <input type="number" class="form-control" name="year_standared" value="<?php echo $feature->year_standared; ?>">
                        <p class="small text-info"><i class="fa fa-info-circle"></i> <?php echo helper_trans('limit-suggestions'); ?></p>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-12 text-left control-label col-form-label"><?php echo helper_trans('yearly') ?> <?php echo helper_trans('premium-limit') ?></label>
                      <div class="col-sm-12">
                        <input type="number" class="form-control" name="year_premium" value="<?php echo $feature->year_premium; ?>">
                        <p class="small text-info"><i class="fa fa-info-circle"></i> <?php echo helper_trans('limit-suggestions'); ?></p>
                      </div>
                    </div>
                  </div>
                </div>

              </div>

            </div>

          </div>

          <div class="modal-footer">


            <button type="submit" class="btn btn-info waves-effect pull-left"><i class="fa fa-check"></i> <?php echo helper_trans('save-changes') ?></button>
          </div>
        </div>
      </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<?php endforeach; ?>


<?php foreach ($packages as $package) : ?>
  <div id="packageModal_<?php echo html_escape($package->id) ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom modal-md">
      <form method="post" enctype="multipart/form-data" class="validate-form" action="<?php echo url('admin/package/update/' . $package->id) ?>" role="form" novalidate>
        @csrf
        <div class="modal-content modal-md">
          <div class="modal-header">
            <h4 class="modal-title" id="vcenter"><?php echo helper_trans('update-package') ?> - <?php echo html_escape($package->name) ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">

            <div class="form-group row">
              <label class="col-sm-12 text-left control-label col-form-label"><?php echo helper_trans('name') ?></label>
              <div class="col-sm-12">
                <input type="text" class="form-control" name="name" value="<?php echo html_escape($package->name) ?>">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-12 text-left control-label col-form-label"><?php echo helper_trans('monthly-price') ?></label>
              <div class="col-sm-12">
                <input type="text" class="form-control" name="monthly_price" value="<?php echo html_escape($package->monthly_price) ?>">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-12 text-left control-label col-form-label"><?php echo helper_trans('yearly-price') ?></label>
              <div class="col-sm-12">
                <input type="text" class="form-control" name="price" value="<?php echo html_escape($package->price) ?>">
              </div>
            </div>

            <div class="form-group row mt-20">
              <label for="inputEmail3" class="col-sm-12 text-left control-label col-form-label"></label>
              <div class="col-sm-12">
                <input type="checkbox" name="is_special" value="1" id="md_checkbox_3" class="filled-in chk-col-blue" <?php if ($package->is_special == 1) {
                                                                                                                        echo "checked";
                                                                                                                      } ?> />
                <label for="md_checkbox_3"><?php echo helper_trans('is-popular-packages') ?>?</label>
              </div>
            </div>


          </div>

          <div class="modal-footer">


            <button type="submit" class="btn btn-info waves-effect pull-right"><?php echo helper_trans('save-changes') ?></button>
          </div>
        </div>
      </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<?php endforeach; ?>