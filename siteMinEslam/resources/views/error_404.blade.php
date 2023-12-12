<!DOCTYPE html>
<html>

<head>
	<title> <?php echo helper_trans('404-not-found') ?></title>
	<link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/front/css/style.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/front/font/flaticon.css')}}">
</head>

<body>

	<div>
		<div class="text-center">
			<img src="{{ asset('assets/front/img/404.jpg')}}">
			<h5><?php echo helper_trans('404-msg') ?></h5><br>
			<a href="<?php echo url('') ?>" class="btn btn-primary"> <i class="flaticon-left-arrow" aria-hidden="true"></i> <?php echo helper_trans('back-to-home') ?> </a>
		</div>
	</div>

	<script src="{{ asset('assets/front/js/jquery-2.2.4.min.js')}}"></script>
	<script src="{{ asset('assets/front/js/bootstrap.min.js')}}"></script>

</body>

</html>