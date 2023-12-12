<ul class="nav nav-tabs admin">
  <li><a class="<?php if (isset($page_title) && $page_title == 'Personal Information') {
                  echo "active";
                } ?>" href="<?php echo url('admin/profile') ?>"><i class="fa fa-cog"></i> <span class="hidden-xs"><?php echo helper_trans('general-settings') ?></span> </a></li>
  <li><a class="<?php if (isset($page_title) && $page_title == 'Change Password') {
                  echo "active";
                } ?>" href="<?php echo url('admin/profile/change_password') ?>"><i class="fa fa-lock"></i> <span class="hidden-xs"><?php echo helper_trans('change-password') ?></span></a></li>
  <li><a class="<?php if (isset($page_title) && $page_title == 'Business' || $page == 'Business') {
                  echo "active";
                } ?>" href="<?php echo url('admin/business') ?>"><i class="fa fa-briefcase"></i> <span class="hidden-xs"><?php echo helper_trans('business') ?></span></a></li>
</ul>