<div class="row mb-20 mt-250">
	<div class="col-md-12 text-center">
		<div class="alert alert-danger">
			<div class="lh-34">
				<h1 class="text-danger"><i class="icon-info"></i></h1>
				<h4 class="mb-0"><?php echo helper_trans('reached-limit') ?></h4>
				<p><?php echo helper_trans('you-already-created') ?> <strong><?php echo html_escape($total . ' ' . $limit_for) ?></strong>. <?php echo helper_trans('package-limit-msg') ?>. </p>
			</div><br>
			<div class="clearfix"></div>
		</div>
		<a href="<?php echo url('admin/subscription') ?>" class="btn btn-info rounded"><?php echo helper_trans('upgrade-your-plan') ?></a>
	</div>
</div>