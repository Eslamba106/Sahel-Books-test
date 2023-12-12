<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">

        <div class="col-md-6 m-auto box add_area" style="display: <?php if ($page_title == 'Edit') {
            echo 'block';
        } else {
            echo 'none';
        } ?>">

            <div class="box-header with-border">
                <?php if (isset($page_title) && $page_title == "Edit") : ?>
                <h3 class="box-title"><?php echo helper_trans('edit-product'); ?></h3>
                <?php else : ?>
                <h3 class="box-title"><?php echo helper_trans('add-product'); ?> </h3>
                <?php endif; ?>

                <div class="box-tools pull-right">
                    <?php if (isset($page_title) && $page_title == "Edit") : ?>
                    <a href="<?php echo url('admin/product/all/' . $type); ?>" class="pull-right btn btn-default rounded btn-sm"><i
                            class="fa fa-angle-left"></i> <?php echo helper_trans('back'); ?></a>
                    <?php else : ?>
                    <a href="#" class="text-right btn btn-default rounded btn-sm cancel_btn"><i
                            class="fa fa-angle-left"></i> <?php echo helper_trans('back'); ?></a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="box-body">
                <form id="cat-form" method="post" enctype="multipart/form-data" class="validate-form"
                    action="<?php echo url('admin/product/add'); ?>" role="form" novalidate>
                    @csrf

                    <div class="form-group">
                        <label><?php echo helper_trans('product-name'); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" required name="name" value="<?php echo html_escape($product[0]['name']); ?>">
                    </div>

                    <div class="form-group">
                        <label><?php echo helper_trans('price'); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" required name="price" value="<?php echo html_escape($product[0]['price']); ?>">
                    </div>

                    <?php if (helper_get_business()->enable_stock == 1) : ?>
                    <div class="form-group">
                        <label><?php echo helper_trans('stock-quantity'); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" required name="quantity"
                            value="<?php echo html_escape($product[0]['quantity']); ?>">
                    </div>
                    <?php else : ?>
                    <input type="hidden" class="form-control" name="quantity" value="<?php echo html_escape($product[0]['quantity']); ?>">
                    <?php endif ?>


                    <?php if ($type == 'buy') : ?>
                    <div class="form-group col-md-12 p-0 expense_list">
                        <label class="col-sm-12 control-label p-0" for="example-input-normal"><?php echo helper_trans('expense-category'); ?>
                        </label>
                        <select class="form-control" name="expense_category">
                            <option value="0"><?php echo helper_trans('select'); ?></option>
                            <?php foreach ($expense_category as $expense) : ?>
                            <option value="<?php echo html_escape($expense->id); ?>" <?php echo $product[0]['expense_category'] == $expense->id ? 'selected' : ''; ?>>
                                <?php echo html_escape($expense->name); ?>
                            </option>
                            <?php endforeach ?>
                        </select>
                    </div>


                    <input type="hidden" name="is_buy" value="1">
                    <input type="hidden" name="is_sell" value="0">
                    <?php else : ?>
                    <div class="form-group col-md-12 p-0 income_list">
                        <label class="col-sm-12 control-label p-0" for="example-input-normal"><?php echo helper_trans('income-category'); ?>
                        </label>
                        <select class="form-control" name="income_category">
                            <option value="0"><?php echo helper_trans('select'); ?></option>
                            <?php foreach ($income_category as $income) : ?>
                            <option value="<?php echo html_escape($income->id); ?>" <?php echo $product[0]['income_category'] == $income->id ? 'selected' : ''; ?>>
                                <?php echo html_escape($income->name); ?>
                            </option>
                            <?php endforeach ?>
                        </select>
                    </div><br>

                    <input type="hidden" name="is_sell" value="1">
                    <input type="hidden" name="is_buy" value="0">
                    <?php endif ?>


                    <div class="form-group">
                        <label><?php echo helper_trans('product-details'); ?></label>
                        <textarea class="form-control" name="details" rows="6"><?php echo html_escape($product[0]['details']); ?></textarea>
                    </div>


                    <input type="hidden" name="id" value="<?php echo html_escape($product['0']['id']); ?>">
                    <input type="hidden" name="type" value="<?php echo html_escape($type); ?>">



                    <div class="row m-t-30">
                        <div class="col-sm-12">
                            <?php if (isset($page_title) && $page_title == "Edit") : ?>
                            <button type="submit" class="btn btn-info rounded pull-left"><i class="fa fa-check"></i>
                                <?php echo helper_trans('save-changes'); ?></button>
                            <?php else : ?>
                            <button type="submit" class="btn btn-info rounded pull-left"><i class="fa fa-check"></i>
                                <?php echo helper_trans('save'); ?></button>
                            <?php endif; ?>
                        </div>
                    </div>

                </form>
            </div>
        </div>


        <?php if (isset($page_title) && $page_title != "Edit") : ?>

        <div class="list_area container">



            <?php if (isset($page_title) && $page_title == "Edit") : ?>
            <h3 class="box-title"><?php echo helper_trans('edit-product'); ?><a href="<?php echo url('admin/product'); ?>"
                    class="pull-right btn btn-primary btn-sm"><i class="fa fa-angle-left"></i> <?php echo helper_trans('back'); ?></a>
            </h3>
            <?php else : ?>
            <h3 class="box-title">(<?php if ($type == 'buy') {
                echo helper_trans('purchases');
            } else {
                echo helper_trans('sales');
            } ?>) <?php echo helper_trans('products-services'); ?>
                <a href="#" class="pull-right btn btn-info btn-sm add_btn rounded"><i class="fa fa-plus"></i>
                    <?php echo helper_trans('add-product'); ?></a>
            </h3>
            <?php endif; ?>


            <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
                <table class="table table-bordered  <?php if (count($products) > 10) {
                    echo 'datatable';
                } ?>">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo helper_trans('name'); ?></th>
                            <th><?php echo helper_trans('price'); ?></th>
                            <?php if (helper_get_business()->enable_stock == 1) : ?>
                            <th><?php echo helper_trans('quantity'); ?></th>
                            <?php endif; ?>
                            <th><?php echo helper_trans('type'); ?></th>
                            <th><?php echo helper_trans('action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
              foreach ($products as $product) : ?>
                        <tr id="row_<?php echo html_escape($product->id); ?>">

                            <td><?php echo $i; ?></td>
                            <td><?php echo html_escape($product->name); ?></td>
                            <td><?php echo html_escape($product->price); ?></td>

                            <?php if (helper_get_business()->enable_stock == 1) : ?>
                            <td><?php echo html_escape($product->quantity); ?></td>
                            <?php endif; ?>

                            <td>
                                <?php if ($product->is_buy == 1) : ?>
                                <label class="label label-primary"><?php echo helper_trans('purchases'); ?></label>
                                <?php endif ?>
                                <?php if ($product->is_sell == 1 || $product->is_buy == 0) : ?>
                                <label class="label label-success"><?php echo helper_trans('sales'); ?></label>
                                <?php endif ?>
                            </td>

                            <td class="actions" width="12%">
                                <a href="<?php echo url('admin/product/edit/' . html_escape($product->id) . '/' . $type); ?>" class="on-default edit-row" data-placement="top"
                                    title="Edit"><i class="fa fa-pencil"></i></a> &nbsp;

                                <a data-val="Category" data-id="<?php echo html_escape($product->id); ?>" href="<?php echo url('admin/product/delete/' . html_escape($product->id)); ?>"
                                    class="on-default remove-row delete_item" data-toggle="tooltip"
                                    data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></a> &nbsp;
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
