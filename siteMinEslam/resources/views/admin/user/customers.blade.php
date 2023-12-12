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
                <h3 class="box-title"><?php echo helper_trans('edit-customer'); ?></h3>
                <?php else : ?>
                <h3 class="box-title"><?php echo helper_trans('add-new-customer'); ?> </h3>
                <?php endif; ?>

                <div class="box-tools pull-right">
                    <?php if (isset($page_title) && $page_title == "Edit") : ?>
                    <a href="<?php echo url('admin/customer'); ?>" class="pull-right rounded btn btn-default btn-sm"><i
                            class="fa fa-angle-left"></i> <?php echo helper_trans('back'); ?></a>
                    <?php else : ?>
                    <a href="#" class="text-right rounded btn btn-default btn-sm cancel_btn"><i
                            class="fa fa-angle-left"></i> <?php echo helper_trans('back'); ?></a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="box-body">
                <form id="cat-form" method="post" enctype="multipart/form-data" class="validate-form"
                    action="<?php echo url('admin/customer/add'); ?>" role="form" novalidate>
                    @csrf

                    <div class="form-group">
                        <label><?php echo helper_trans('customer-name'); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" required name="name" value="<?php echo html_escape($customer[0]['name']); ?>">
                    </div>

                    <div class="form-group">
                        <label><?php echo helper_trans('email'); ?></label>
                        <input type="email" class="form-control" name="email" value="<?php echo html_escape($customer[0]['email']); ?>">
                    </div>

                    <div class="form-group">
                        <label><?php echo helper_trans('phone'); ?></label>
                        <input type="text" class="form-control" name="phone" value="<?php echo html_escape($customer[0]['phone']); ?>">
                    </div>

                    <div class="form-group">
                        <label><?php echo helper_trans('address'); ?> </label>
                        <textarea class="form-control" name="address"><?php echo html_escape($customer[0]['address']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label><?php echo helper_trans('business') . ' ' . helper_trans('number'); ?></label>
                        <input type="text" class="form-control" name="cus_number" value="<?php echo html_escape($customer[0]['cus_number']); ?>">
                    </div>

                    <div class="form-group">
                        <label><?php echo helper_trans('tax') . '/vat ' . helper_trans('number'); ?></label>
                        <input type="text" class="form-control" name="vat_code" value="<?php echo html_escape($customer[0]['vat_code']); ?>">
                    </div>

                    <hr>

                    <h4><?php echo helper_trans('billing-information'); ?></h4><br>

                    <div class="form-group">
                        <label class="col-sm-2 control-label p-0" for="example-input-normal"><?php echo helper_trans('country'); ?>
                        </label>
                        <select class="form-control col-sm-12 SlectBox" id="country" required name="country" onclick="console.log($(this).val())" 
                            style="width: 100%" >
                            <?php foreach ($countries as $country) : ?>
                            <option value="{{ $country->id }}" onchange="console.log('change is firing')">
                                {{$country->name }}
                            </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label p-0" for="example-input-normal"><?php echo helper_trans('currency'); ?>
                        </label>
                        <select class="form-control col-sm-12 wd-100" id="currency" name="currency">
                            {{-- <option value=""></option> --}}
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?php echo helper_trans('city'); ?></label>
                        <input type="text" class="form-control" name="city" value="<?php echo html_escape($customer[0]['city']); ?>">
                    </div>

                    <div class="form-group">
                        <label><?php echo helper_trans('postal-zip-code'); ?></label>
                        <input type="text" class="form-control" name="postal_code" value="<?php echo html_escape($customer[0]['postal_code']); ?>">
                    </div>

                    <div class="form-group">
                        <label><?php echo helper_trans('address'); ?> 1</label>
                        <textarea class="form-control" name="address1"><?php echo html_escape($customer[0]['address1']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label><?php echo helper_trans('address'); ?> 2</label>
                        <textarea class="form-control" name="address2"><?php echo html_escape($customer[0]['address2']); ?></textarea>
                    </div>


                    <input type="hidden" name="id" value="<?php echo html_escape($customer['0']['id']); ?>">



                    <hr>

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

            <div class="box-footer">

            </div>
        </div>


        <?php if (isset($page_title) && $page_title != "Edit") : ?>

        <div class="list_area container">

            <?php if (isset($page_title) && $page_title == "Edit") : ?>
            <h3 class="box-title"><?php echo helper_trans('edit-customer'); ?> <a href="<?php echo url('admin/customer'); ?>"
                    class="pull-right btn btn-primary btn-sm"><i class="fa fa-angle-left"></i> <?php echo helper_trans('back'); ?></a>
            </h3>
            <?php else : ?>
            <h3 class="box-title"><?php echo helper_trans('all-customers'); ?> <a href="#"
                    class="pull-right btn btn-info rounded btn-sm add_btn"><i class="fa fa-plus"></i>
                    <?php echo helper_trans('add-new-customer'); ?></a></h3>
            <?php endif; ?>

            <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
                <table class="table table-hover <?php if (count($customers) > 10) {
                    echo 'datatable';
                } ?>">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo helper_trans('name'); ?></th>
                            <th><?php echo helper_trans('info'); ?></th>
                            <th><?php echo helper_trans('email'); ?></th>
                            <th><?php echo helper_trans('phone'); ?></th>
                            <th><?php echo helper_trans('added'); ?></th>
                            <th><?php echo helper_trans('action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
              foreach ($customers as $customer) : ?>
                        <tr id="row_<?php echo html_escape($customer->id); ?>">

                            <td><?php echo $i; ?></td>
                            <td><strong><?php echo html_escape($customer->name); ?></strong></td>
                            <td>
                                <p class="mb-0"><?php echo html_escape($customer->country_name); ?></p>
                                <p class="mb-0 fs-12"><?php echo html_escape($customer->currency_code . ' - ' . $customer->currency_name . ' (' . $customer->currency_symbol . ')'); ?></p>
                            </td>
                            <td><?php echo html_escape($customer->email); ?></td>
                            <td><?php echo html_escape($customer->phone); ?></td>
                            <td><?php echo my_date_show($customer->created_at); ?></td>

                            <td class="actions" width="12%">
                                <a href="<?php echo url('admin/customer/edit/' . html_escape($customer->id)); ?>" class="on-default edit-row" data-placement="top"
                                    title="<?php echo helper_trans('edit'); ?>"><i class="fa fa-pencil"></i></a> &nbsp;

                                <a data-val="<?php echo helper_trans('customer'); ?>" data-id="<?php echo html_escape($customer->id); ?>"
                                    href="<?php echo url('admin/customer/delete/' . html_escape($customer->id)); ?>" class="on-default remove-row delete_item"
                                    data-toggle="tooltip" data-placement="top" title="Delete"><i
                                        class="fa fa-trash-o"></i></a> &nbsp;
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
{{-- <script>
    $(document).on('change', "#country", function () {
    var Id = $(this).val();
    console.log("Eslam");
    if (Id != '') {
      var url = base_url + 'admin/customer/load_currency/' + Id;
      $.post(url, { data: 'value', 'csrf_test_name': csrf_token }, function (data) {
        $('#currency').html(data);
        $('#currency').prop('disabled', false);
      }
      );
    }
  });

</script> --}}
