<div class="content-wrapper">

  <!-- Main content -->
  <section class="content">

    <div class="col-md-6 m-auto box add_area mt-50" style="display: <?php if ($page_title == "Edit") {
                                                                      echo "block";
                                                                    } else {
                                                                      echo "none";
                                                                    } ?>">
      <div class="box-header with-border">
        <?php if (isset($page_title) && $page_title == "Edit") : ?>
          <h3 class="box-title"><?php echo helper_trans('edit-category') ?></h3>
        <?php else : ?>
          <h3 class="box-title"><?php echo helper_trans('add-new-category') ?> </h3>
        <?php endif; ?>

        <div class="box-tools pull-right">
          <?php if (isset($page_title) && $page_title == "Edit") : ?>
            <a href="<?php echo url('admin/category') ?>" class="pull-rightrounded  btn btn-default btn-sm"><i class="fa fa-angle-left"></i> <?php echo helper_trans('back') ?></a>
          <?php else : ?>
            <a href="#" class="text-right btn btn-default rounded btn-sm cancel_btn"><i class="fa fa-angle-left"></i> <?php echo helper_trans('back') ?></a>
          <?php endif; ?>
        </div>
      </div>

      <div class="box-body">
        <form id="cat-form" method="post" enctype="multipart/form-data" class="validate-form" action="<?php echo url('admin/category/add') ?>" role="form" novalidate>
          @csrf

          <div class="form-group">
            <label><?php echo helper_trans('category-name') ?> <span class="text-danger">*</span></label>
            <input type="text" class="form-control" required name="name" value="<?php echo html_escape($category[0]['name']); ?>">
          </div>

          <div class="row m-t-30">
            <div class="col-sm-2 text-left">
              <div class="radio radio-info radio-inline">
                <input type="radio" id="inlineRadio1" required value="1" name="type" <?php if ($category[0]['type'] == 1) {
                                                                                        echo "checked";
                                                                                      } ?>>
                <label for="inlineRadio1"> <?php echo helper_trans('income') ?> </label>
              </div>
            </div>
            <div class="col-sm-1 text-left">
              <div class="radio radio-info radio-inline">
                <input type="radio" id="inlineRadio2" required value="2" name="type" <?php if ($category[0]['type'] == 2) {
                                                                                        echo "checked";
                                                                                      } ?>>
                <label for="inlineRadio2"> <?php echo helper_trans('expense') ?> </label>
              </div>
            </div>
          </div>

          <input type="hidden" name="id" value="<?php echo html_escape($category['0']['id']); ?>">




          <div class="row mt-20">
            <div class="col-sm-12">
              <?php if (isset($page_title) && $page_title == "Edit") : ?>
                <button type="submit" class="btn btn-info rounded pull-left"><i class="fa fa-check"></i> <?php echo helper_trans('save-changes') ?></button>
              <?php else : ?>
                <button type="submit" class="btn btn-info rounded pull-left"><i class="fa fa-check"></i> <?php echo helper_trans('save') ?></button>
              <?php endif; ?>
            </div>
          </div>

        </form>

      </div>
    </div>


    <?php if (isset($page_title) && $page_title != "Edit") : ?>
      <div class="list_area container">

        <?php if (isset($page_title) && $page_title == "Edit") : ?>
          <h3 class="box-title"><?php echo helper_trans('edit-category') ?> <a href="<?php echo url('admin/portfolio_category') ?>" class="pull-right btn btn-primary btn-sm"><i class="fa fa-angle-left"></i> <?php echo helper_trans('back') ?></a></h3>
        <?php else : ?>
          <h3 class="box-title"><?php echo helper_trans('categories') ?> <a href="#" class="pull-right btn btn-info rounded btn-sm add_btn"><i class="fa fa-plus"></i> <?php echo helper_trans('add-new-category') ?></a></h3>
        <?php endif; ?>

        <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
          <table class="table table-bordered table-hover <?php if (count($categories) > 10) {
                                                            echo "datatable";
                                                          } ?>" id="dg_table">
            <thead>
              <tr>
                <th>#</th>
                <th><?php echo helper_trans('name') ?></th>
                <th><?php echo helper_trans('type') ?></th>
                <th><?php echo helper_trans('action') ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1;
              foreach ($categories as $cat) : ?>
                <tr id="row_<?php echo html_escape($cat->id); ?>">

                  <td><?php echo $i; ?></td>
                  <td><?php echo html_escape($cat->name); ?></td>
                  <td>
                    <?php if ($cat->type == 1) : ?>
                      <span class="label label-success"><?php echo helper_trans('income') ?></span>
                    <?php else : ?>
                      <span class="label label-primary"><?php echo helper_trans('expense') ?></span>
                    <?php endif ?>
                  </td>

                  <td class="actions" width="15%">
                    <a href="<?php echo url('admin/category/edit/' . html_escape($cat->id)); ?>" class="on-default edit-row" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a> &nbsp;

                    <a data-val="Category" data-id="<?php echo html_escape($cat->id); ?>" href="<?php echo url('admin/category/delete/' . html_escape($cat->id)); ?>" class="on-default remove-row delete_item" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></a>
                  </td>
                </tr>

              <?php $i++;
              endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>

  </section>
</div>