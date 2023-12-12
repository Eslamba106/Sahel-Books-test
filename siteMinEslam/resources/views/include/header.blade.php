<?php $settings = Get_Settings(); ?>

<head>
    <meta charset="utf-8">
    <title>
        <?php echo html_escape($settings->site_name); ?> -
        <?php echo html_escape($settings->site_title); ?>
    </title>
    <meta charset="utf-8">
    <meta name="author" content="{{ html_escape($settings->site_name) }}">
    <meta name="description" content="{{ html_escape($settings->description) }}">
    <meta name="keywords" content="{{ html_escape($settings->keywords) }}">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <meta name="theme-color" content="#da5e03" />
    <meta name="msapplication-navbutton-color" content="#da5e03" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#da5e03" />

    <link rel="icon" href="<?php echo url($settings->favicon ?? ''); ?>">
    <link rel="apple-touch-icon" href="img/apple-touch-icon.html">
    <link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.html">
    <link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.html">

    <link href="{{ asset('assets/admin/css/toast.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/sweet-alert.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/front/css/simple-line-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/css/select2.min.css') }}" rel="stylesheet" />

    <!-- new styles ================================================== -->
    <!-- Bootstrap 4.0-->
    <link href="assets/front/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,300i,400,400i,500,500i,700,700i" rel="stylesheet">
    <!--== animate -->
    <link href="assets/front/css/animate.css" rel="stylesheet" />
    <!--== fontawesome -->
    <link href="{{ asset('assets/front/css/fontawesome-all.css') }}" rel="stylesheet" />

    <!--== line-awesome -->
    <link href="{{ asset('assets/front/css/line-awesome.min.css') }}" rel="stylesheet" />
    <!--== magnific-popup -->
    <link href="{{ asset('assets/front/css/magnific-popup/magnific-popup.css') }}" rel="stylesheet" />
    <!--== owl-carousel -->
    <link href="{{ asset('assets/front/css/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
    <!--== base -->
    <link href="{{ asset('assets/front/css/base.css') }}" rel="stylesheet" />
    <!--== shortcodes -->
    <link href="{{ asset('assets/front/css/shortcodes.css') }}" rel="stylesheet" />
    <!--== default-theme -->
    <link href="{{ asset('assets/front/css/style.css') }}" rel="stylesheet" />
    <?php if (text_dir() == 'ltr'): ?>
    <link href="{{ asset('assets/front/css/custom-ltr.css') }}" rel="stylesheet" />
    <?php endif ?>
    <!--== default-theme -->
    <link href="{{ asset('assets/front/css/custom.css') }}" rel="stylesheet" />
    <!--== responsive -->
    <link href="{{ asset('assets/front/css/responsive.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/front/css/sahel.css') }}" rel="stylesheet" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tel css -->
    <link href="{{ asset('assets/front/css/intltelinput.css') }}" rel="stylesheet" />
    <style>
        #switch-case input[type=checkbox] {
            height: 0;
            width: 0;
            visibility: hidden;
        }

        #switch-case label {
            cursor: pointer;
            text-indent: -9999px;
            width: 50px;
            height: 25px;
            background: grey;
            display: inline;
            border-radius: 100px;
            position: relative;
        }

        #switch-case label:after {
            content: '';
            position: absolute;
            top: 5px;
            <?php echo helper_trans('align'); ?>: 5px;
            width: 15px;
            height: 15px;
            background: #fff;
            border-radius: 90px;
            transition: 0.3s;
        }

        #switch-case input:checked+label {
            background: #bada55;
        }

        #switch-case input:checked+label:after {
            left: calc(100% - 5px);
            transform: translateX(-100%);
        }

        #switch-case label:active:after {
            width: 50px;
        }

        .navbar-nav a .sub-arrow::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
        }

        .accordion .card-header a span::before,
        .accordion .card-header a span::after {
            width: 20px;
            height: 2px;
            background: #51409b;
        }

        #cookie-msg {
            z-index: 500;
        }

        .post-title:after {
            display: none;
        }

        /* Pw-strength */

        #passwordHelp {
            font-size: 85%;
        }

        .js-hidden {
            display: none !important;
        }

        .input-group-append {
            margin-left: -1px;
        }

        .btn-outline-secondary {
            color: #F3969A;
            border-color: #F3969A;
            border: 1px solid;
        }
    </style>
    <!-- inject css end -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
    <script src="{{ asset('assets/admin/sweetalert/sweetalert2.min.js') }}"></script>


    <script type="text/javascript">
        var csrf_token = '';
        var token_name = ''
    </script>

    <script type="text/javascript">
        var _html = document.documentElement,
            isTouch = (('ontouchstart' in _html) || (navigator.msMaxTouchPoints > 0) || (navigator.maxTouchPoints));

        _html.className = _html.className.replace("no-js", "js");
        _html.classList.add(isTouch ? "touch" : "no-touch");
    </script>
    <script type="text/javascript" src="{{ asset('front/js/device.min.js') }}"></script>

    <!-- google analytics -->
    <?php if (!empty($settings->google_analytics)): ?>
    <?php echo base64_decode($settings->google_analytics); ?>
    <?php endif ?>

    <?php if ($settings->enable_captcha == 1 && $settings->captcha_site_key != ''): ?>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <?php endif; ?>


</head>
