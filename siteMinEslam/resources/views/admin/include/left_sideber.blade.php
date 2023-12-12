  <?php if (isset($page_title) && $page_title != 'Online Payment') : ?>

  <aside class="main-sidebar">
      <section class="sidebar mt-10">

          <ul class="sidebar-menu" data-widget="tree">

              <?php if (is_admin()) : ?>


              <li class="treeview <?php if (isset($main_page) && $main_page == 'Settings') {
                  echo 'active';} ?>">
                  <a href="#"><i class="flaticon-settings-1"></i>
                      <span><?php echo helper_trans('settings'); ?></span>
                      <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                  </a>

                  <ul class="treeview-menu">

                      <li class="<?php if (isset($page_title) && $page_title == 'Payment Settings') {
                          echo 'active';
                      } ?>">
                          <a href="<?php echo url('admin/payment'); ?>">
                              <i class="flaticon-save-money"></i> <span><?php echo helper_trans('payment-settings'); ?></span>
                          </a>
                      </li>


                  </ul>
              </li>


              <li class="has_sub">
                  <a href="<?php echo url('admin/language'); ?>" class="waves-effect"><i class="flaticon-accept"></i>
                      <span><?php echo helper_trans('testimonials'); ?> <?php echo helper_trans('language'); ?> </span> </a>
              </li>

              <li class="<?php if (isset($page_title) && $page_title == 'Users') {
                  echo 'active';
              } ?>">
                  <a href="<?php echo url('admin/users'); ?>">
                      <i class="flaticon-group"></i> <span><span><?php echo helper_trans('users'); ?></span>
                  </a>
              </li>




              <?php else : ?>

              <li class="<?php if (isset($page_title) && $page_title == 'User Dashboard') {
                  echo 'active';
              } ?>">
                  <a href="<?php echo url('admin/dashboard/business'); ?>">
                      <i class="flaticon-home-1"></i> <span><?php echo helper_trans('dashboard'); ?></span>
                  </a>
              </li>

              <li class="<?php if (isset($page_title) && $page_title == 'Subscription') {
                  echo 'active';
              } ?>">
                  <a href="<?php echo url('admin/subscription'); ?>">
                      <i class="flaticon-time-is-money"></i> <span><?php echo helper_trans('subscription'); ?></span>
                  </a>
              </li>


              <li class="<?php if (isset($page_title) && $page_title == 'Change Password') {
                  echo 'active';
              } ?>">
                  <a href="<?php echo url('change_password'); ?>">
                      <i class="flaticon-lock-1"></i> <span><?php echo helper_trans('change-password'); ?></span>
                  </a>
              </li>

              <?php if (check_payment_status() == TRUE || settings()->enable_paypal == 0 || user()->user_type == 'trial') : ?>

              <?php if (check_package_limit('invoice-payments') == -1) : ?>
              <li class="<?php if (isset($page_title) && $page_title == 'Payment Settings') {
                  echo 'active';
              } ?>">
                  <a href="<?php echo url('admin/payment/user'); ?>">
                      <i class="flaticon-save-money"></i> <span><?php echo helper_trans('payment-settings'); ?></span>
                  </a>
              </li>
              <?php endif; ?>

              <li class="treeview <?php if (isset($main_page) && $main_page == 'Sales') {
                  echo 'active';
              } ?>">

                  <a href="#"><i class="flaticon-business-cards"></i>
                      <span><?php echo helper_trans('sales'); ?></span>
                      <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                  </a>

                  <ul class="treeview-menu">

                      <li class="<?php if (isset($page_title) && $page_title == 'Customers') {
                          echo 'active';
                      } ?>">
                          <a href="<?php echo url('admin/customer'); ?>">
                              <i class="flaticon-network"></i> <span><?php echo helper_trans('customers'); ?></span>
                          </a>
                      </li>

                      <li class="<?php if (isset($page_title) && $page_title == 'Products') {
                          echo 'active';
                      } ?>">
                          <a href="<?php echo url('admin/product/all/sell'); ?>">
                              <i class="flaticon-box-1"></i> <span><?php echo helper_trans('products-services'); ?></span>
                          </a>
                      </li>

                      <li class="<?php if (isset($page_title) && $page_title == 'Estimate') {
                          echo 'active';
                      } ?>">
                          <a href="<?php echo url('admin/estimate'); ?>">
                              <i class="flaticon-contract"></i> <span><?php echo helper_trans('estimates'); ?></span>
                          </a>
                      </li>

                      <li class="<?php if (isset($page_title) && $page_title == 'Invoices') {
                          echo 'active';
                      } ?>">
                          <a href="<?php echo url('admin/invoice/type/1'); ?>">
                              <i class="flaticon-approve-invoice"></i> <span><?php echo helper_trans('invoices'); ?></span>
                          </a>
                      </li>

                      <li class="<?php if (isset($page_title) && $page_title == 'Create Recurring Invoice') {
                          echo 'active';
                      } ?>">
                          <a href="<?php echo url('admin/invoice/create/1'); ?>">
                              <i class="flaticon-iterative"></i> <span><?php echo helper_trans('recurring-invoice'); ?> </span>
                          </a>
                      </li>

                  </ul>
              </li>

              <li class="treeview <?php if (isset($main_page) && $main_page == 'Purchases') {
                  echo 'active';
              } ?>">

                  <a href="#"><i class="icon-basket"></i>
                      <span><?php echo helper_trans('purchases'); ?></span>
                      <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                  </a>
                  <ul class="treeview-menu">

                      <li class="<?php if (isset($page_title) && $page_title == 'Bills') {
                          echo 'active';
                      } ?>">
                          <a href="<?php echo url('admin/bills'); ?>">
                              <i class="flaticon-credit-card"></i> <span><?php echo helper_trans('bills'); ?></span>
                          </a>
                      </li>

                      <li class="<?php if (isset($page_title) && $page_title == 'Vendor') {
                          echo 'active';
                      } ?>">
                          <a href="<?php echo url('admin/vendor'); ?>">
                              <i class="flaticon-group"></i> <span><?php echo helper_trans('vendors'); ?></span>
                          </a>
                      </li>

                      <li class="<?php if (isset($page_title) && $page_title == 'Expense') {
                          echo 'active';
                      } ?>">
                          <a href="<?php echo url('admin/expense'); ?>">
                              <i class="flaticon-bill"></i> <span><?php echo helper_trans('expense'); ?></span>
                          </a>
                      </li>

                      <li class="<?php if (isset($page_title) && $page_title == 'Products') {
                          echo 'active';
                      } ?>">
                          <a href="<?php echo url('admin/product/all/buy'); ?>">
                              <i class="flaticon-box-1"></i> <span><?php echo helper_trans('products-services'); ?></span>
                          </a>
                      </li>

                  </ul>
              </li>
              <li class="treeview <?php if (isset($main_page) && $main_page == 'Accounting') {
                  echo 'active';
              } ?>">

                  <a href="#"><i class="icon-wallet"></i>
                      <span><?php echo helper_trans('accounting'); ?></span>
                      <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                  </a>
                  <ul class="treeview-menu">
                      <li class="<?php if (isset($page_title) && $page_title == 'Category') {
                          echo 'active';
                      } ?>">
                          <a href="<?php echo url('admin/category'); ?>">
                              <i class="flaticon-folder-1"></i> <span><?php echo helper_trans('categories'); ?></span>
                          </a>
                      </li>

                      <li class="<?php if (isset($page_title) && $page_title == 'Tax') {
                          echo 'active';
                      } ?>">
                          <a href="<?php echo url('admin/tax'); ?>">
                              <i class="flaticon-tax"></i> <span><?php echo helper_trans('tax'); ?></span>
                          </a>
                      </li>


                  </ul>
              </li>

              <!--  -->
              <?php if (settings()->enable_multilingual == 1) : ?>
              <li class="treeview">
                  <a href="#"><i class="icon-globe"></i>
                      <span><?php echo helper_trans('language'); ?> : <?php echo lang_short_form(); ?></span>
                      <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                  </a>
                  <ul class="treeview-menu">
                      <?php foreach (get_language() as $lang) : ?>
                      <li><a href="<?php echo url('home/switch_lang/' . $lang->slug); ?>"><span> <?php echo html_escape($lang->name); ?></span> </a></li>
                      <?php endforeach ?>
                  </ul>
              </li>
              <?php endif ?>

              <li class="<?php if (isset($page_title) && $page_title == 'Profile') {
                  echo 'active';
              } ?>">
                  <a href="<?php echo url('admin/profile'); ?>">
                      <i class="flaticon-settings-1"></i> <span><?php echo helper_trans('settings'); ?></span>
                  </a>
              </li>
              <?php endif ?>


              <?php endif; ?>


              <li class="">
                  <a href="<?php echo url('auth/logout'); ?>">
                      <i class="flaticon-exit"></i> <span><?php echo helper_trans('logout'); ?></span>
                  </a>
              </li>
          </ul>


      </section>
  </aside>

  <?php endif ?>
