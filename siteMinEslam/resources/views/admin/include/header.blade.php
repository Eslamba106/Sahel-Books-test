<!DOCTYPE html>
<?php $settings = get_settings(); ?>

<html lang="en" dir="<?php echo $settings->dir; ?>">

<head>

    <?php $user = get_logged_user(session('id')); ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo url($settings->favicon); ?>">

    <title><?php echo html_escape($settings->site_name); ?> &bull; <?php if (isset(helper_get_business()->name)) {
        echo html_escape(helper_get_business()->name) . ' &bull;';
    } ?> <?php if (isset($page_title)) {
        echo html_escape($page_title);
    } else {
        echo 'Dashboard';
    } ?></title>

    <!-- Bootstrap 4.0-->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css')}}">
    <!-- Bootstrap 4.0-->
    <link rel="stylesheet" href="{{ asset('admin/css/bootstrap-extend.css')}}">
    <!-- Append Grid -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.min.css" rel="stylesheet"
        type="text/css">

    <link rel="stylesheet" href="{{ asset('assets/admin/css/font-awesome.min.css')}}">
    <link href="{{ asset('assets/admin/css/toast.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/sweet-alert.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/animate.min.css')}}" rel="stylesheet" />
    <!-- DataTables -->
    <link href="{{ asset('assets/admin/css/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/master_style.css')}}">

    <?php if (text_dir() == 'rtl') : ?>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/custom-rtl.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-rtl.min.css')}}" crossorigin="anonymous">
    <?php endif ?>

    <link rel="stylesheet" href="{{ asset('assets/admin/css/skins/_all-skins.css')}}">
    <link href="{{ asset('assets/admin/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/icons.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/front/css/simple-line-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/front/font/flaticon.css')}}">
    <link href="{{ asset('assets/admin/css/bootstrap-switch.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/select2.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/themify.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/bootstrap4-toggle.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/summernote.css')}}" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style type="text/css">
        .radio input[type="radio"],
        .radio-inline input[type="radio"],
        .checkbox input[type="checkbox"],
        .checkbox-inline input[type="checkbox"] {
            margin-right: -20px !important;
        }
    </style>

    <!-- Color picker plugins css -->
    <link href="{{asset('assets/admin/plugins/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}"
        rel="stylesheet">

    <script type="text/javascript">
        var csrf_token = '';
        var token_name = ''
    </script>


</head>

<body class="hold-transition skin-blue-light sidebar-mini">

    <!-- Preloader -->
    <div class="preloader">
        <div class="container text-center">
            <div class="spinner-llg"></div>
        </div>
    </div>
    <!-- /Preloader -->

    <!-- Site wrapper -->
    <div class="wrapper">

        <?php if (isset($page_title) && $page_title != 'Online Payment') : ?>
        <header class="main-header">
            @if (is_admin())
                <a target="_blank" href="{{url('')}}" class="switch_businesss logo text-center">
                    <span class="logo-lg">
                        <img width="130px" src="{{asset('images/accept-cards.jpg')}}" alt="{{html_escape($settings->site_name)}}">
                    </span>
                </a>
            @else
                <a href="#" class="switch_business logo text-centers">
                    <span class="logo-lg">
                        <img width="40px" src="{{asset('images/accept-cards.jpg')}}" alt="<?php echo html_escape($settings->site_name); ?>">
                        <span><?php  if (isset(helper_get_business()->name)) { echo html_escape(helper_get_business()->name); } ?> </span>
                    </span>
                    <span class="buss-arrow pull-right"><i class="icon-arrow-right"></i></span>
                </a>

                <div class="business_switch_panel animate-ltr" style="display: none;">
                    <div class="buss_switch_panel_header">
                        <img width="40px" src="<?php echo url($settings->favicon); ?>" alt="<?php echo html_escape($settings->site_name); ?>">
                        <span class="acc">Your <?php echo html_escape($settings->site_name); ?> <?php echo helper_trans('accounts'); ?></span>
                        <span class="business_close pull-<?php echo $settings->dir == 'rtl' ? 'left' : 'right'; ?>">×</span>
                    </div>

                    <div class="buss_switch_panel_body">
                        <ul class="switcher_business_menu pb-20">
                            <?php foreach (get_my_business() as $mybuss) : ?>
                            <li class="business_menu_item <?php if (helper_get_business()->uid == $mybuss->uid) {
                                echo 'default';
                            } ?>">
                                <a class="business_menu_item_link" href="<?php echo url('admin/profile/switch_business/' . $mybuss->uid); ?>">
                                    <span class="business-menu_item_label">
                                        <?php echo $mybuss->name; ?>
                                        <?php if (helper_get_business()->uid == $mybuss->uid) : ?>
                                        <span class="is_default pull-right"><i
                                                class="flaticon-checked text-success"></i></span>
                                        <?php endif ?>
                                    </span>
                                </a>
                            </li>
                            <?php endforeach ?>
                        </ul>

                        <div class="seperater"></div>

                        <a class="new_business_link" href="<?php echo url('admin/business'); ?>"><i class="icon-briefcase"></i>
                            <span><?php echo helper_trans('manage-business'); ?></span></a>
                        <a class="new_business_link" href="<?php echo url('admin/business/invoice_customize'); ?>"><i class="fa fa-paint-brush"></i>
                            <span><?php echo helper_trans('invoice-customization'); ?></span></a>
                        <a class="new_business_link" href="<?php echo url('admin/profile'); ?>"><i class="flaticon-user-1"></i>
                            <span><?php echo helper_trans('manage-profile'); ?></span></a>
                        <a class="new_business_link" href="<?php echo url('auth/logout'); ?>"><i class="icon-logout"></i>
                            <span><?php echo helper_trans('sign-out'); ?></span></a>
                    </div>

                    <div class="buss_switch_panel_footer">

                    </div>
                </div>
            @endif





            <nav class="navbar navbar-static-top hidden-md">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
            </nav>

        </header>
        <?php endif; ?>
