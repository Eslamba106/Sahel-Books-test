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
          <h3 class="box-title"><?php echo helper_trans('edit-testimonial') ?></h3>
        <?php else : ?>
          <h3 class="box-title"><?php echo helper_trans('add-new-testimonial') ?> </h3>
        <?php endif; ?>

        <div class="box-tools pull-right">
          <?php if (isset($page_title) && $page_title == "Edit") : ?>
            <a href="<?php echo url('admin/testimonial') ?>" class="pull-right btn btn-primary btn-sm"><i class="fa fa-angle-left"></i> <?php echo helper_trans('add-new-testimonial') ?></a>
          <?php else : ?>
            <a href="#" class="text-right btn btn-default btn-sm cancel_btn"><i class="fa fa-angle-left"></i> <?php echo helper_trans('add-new-testimonial') ?></a>
          <?php endif; ?>
        </div>
      </div>

      <div class="box-body">
        <form id="cat-form" method="post" enctype="multipart/form-data" class="validate-form" action="<?php echo url('admin/testimonial/add') ?>" role="form" novalidate>
          @csrf

          <div class="form-group">
            <label><?php echo helper_trans('client-name') ?> <span class="text-danger">*</span></label>
            <input type="text" class="form-control" required name="name" value="<?php echo html_escape($testimonial[0]['name']); ?>">
          </div>

          <div class="form-group">
            <label><?php echo helper_trans('designation') ?> <span class="text-danger">*</span></label>
            <input type="text" class="form-control" required name="designation" value="<?php echo html_escape($testimonial[0]['designation']); ?>">
          </div>

          <div class="form-group">
            <?php if (isset($page_title) && $page_title == "Edit") : ?>
              <img src="<?php echo url($testimonial[0]['thumb']) ?>"> <br><br>
            <?php endif ?>
            <div class="input-group">
              <span class="input-group-btn">
                <span class="btn btn-info btn-file">
                  <i class="fa fa-upload"></i> <?php echo helper_trans('upload-client-avatar') ?> <input type="file" id="imgInp" name="photo">
                </span>
              </span>
            </div><br>
            <img width="200px" id='img-upload' />
          </div>

          <div class="form-group">
            <label><?php echo helper_trans('feedback') ?> <span class="text-danger">*</span></label>
            <textarea class="form-control" required name="feedback" rows="6"><?php echo html_escape($testimonial[0]['feedback']); ?></textarea>
          </div>

          <input type="hidden" name="id" value="<?php echo html_escape($testimonial['0']['id']); ?>">



          <hr>

          <div class="row m-t-30">
            <div class="col-sm-12">
              <?php if (isset($page_title) && $page_title == "Edit") : ?>
                <button type="submit" class="btn btn-info pull-left"><?php echo helper_trans('save-changes') ?></button>
              <?php else : ?>
                <button type="submit" class="btn btn-info pull-left"> <?php echo helper_trans('save') ?></button>
              <?php endif; ?>
            </div>
          </div>

        </form>

      </div>

      <div class="box-footer">

      </div>
    </div>


    <?php if (isset($page_title) && $page_title != "Edit") : ?>
      <div class="list_area container">

        <?php if (isset($page_title) && $page_title == "Edit") : ?>
          <h3 class="box-title"><?php echo helper_trans('edit-testimonial') ?> <a href="<?php echo url('admin/testimonial') ?>" class="pull-right btn btn-primary btn-sm"><i class="fa fa-angle-left"></i> <?php echo helper_trans('add-new-testimonial') ?></a></h3>
        <?php else : ?>
          <h3 class="box-title"><?php echo helper_trans('testimonial') ?> <a href="#" class="pull-right btn btn-info btn-sm add_btn"><i class="fa fa-plus"></i> <?php echo helper_trans('add-new-testimonial') ?></a></h3>
        <?php endif; ?>


        <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive p-0">
          <table class="table table-bordered <?php if (count($testimonials) > 10) {
                                                echo "datatable";
                                              } ?>" id="dg_tables">
            <thead>
              <tr>
                <th>#</th>
                <th><?php echo helper_trans('thumb') ?></th>
                <th><?php echo helper_trans('name') ?></th>
                <th><?php echo helper_trans('feedback') ?></th>
                <th><?php echo helper_trans('action') ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1;
              foreach ($testimonials as $testimonial) : ?>
                <tr id="row_<?php echo html_escape($testimonial->id); ?>">

                  <td><?php echo $i; ?></td>
                  <td><img width="100px" src="<?php echo url($testimonial->thumb) ?>"></td>
                  <td><?php echo html_escape($testimonial->name); ?></td>
                  <td><?php echo character_limiter($testimonial->feedback, 80); ?></td>

                  <td class="actions" width="12%">
                    <a href="<?php echo url('admin/testimonial/edit/' . html_escape($testimonial->id)); ?>" class="on-default edit-row" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a> &nbsp;

                    <a data-val="Category" data-id="<?php echo html_escape($testimonial->id); ?>" href="<?php echo url('admin/testimonial/delete/' . html_escape($testimonial->id)); ?>" class="on-default remove-row delete_item" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></a> &nbsp;

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