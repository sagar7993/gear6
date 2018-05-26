<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Admin Panel - Vendor Order Feedback Details</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link rel="shortcut icon" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/vstyle.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/ionicons.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/module.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/datatables/dataTables.bootstrap.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/jQueryUI/jquery-ui.css'); ?>">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oxygen" type="text/css">
</head>
<body>
	<?php $this->load->view('admin/components/_head'); ?>
	<?php if(isset($a_is_logged_in) && $a_is_logged_in == 1) { ?>
		<div class="wrapper row-offcanvas row-offcanvas-left">
			<?php $this->load->view('admin/components/_sidebar'); ?>
			<aside class="right-side">
				<section class="content-header">
					<h1>
						Feedback
						<small>Order ID&nbsp;: <?php if(isset($oid)) { echo $oid; } ?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="#"><i class="fa fa-dashboard"></i> Feedback</a></li>
						<li class="active">Vendor Order Feedback</li>
					</ol>
				</section>
				<section class="section-center1">
					<div class="section-header-2">
						<span class="confirm-title">Customer Rating and Feedback for Order Id: <?php if(isset($oid)) { echo convert_to_camel_case($oid); } ?></span>
					</div>
					<div class="section-content1">
						<?php if(isset($od_fback) && $od_fback !== NULL) { $count = 0; ?>
						<div class="modal-body">
							<?php foreach($feedback as $row) { ?>
								<div class="col-xs-12 ratingContainer">
									<div class="col-xs-8 ratingTitle"><?php echo $row['FbQName']; ?></div>
									<div class="col-xs-4 feedbackRating ratingEvent rate_it_i_say" id="question_<?php echo $row['ExecFbQId']; ?>"><?php echo intval($od_fback[$count]['ExecFbAnswer']); ?></div>
								</div>
							<?php $count++; } ?>
						</div><br>
						<div class="col-xs-12">
							<div class="col-xs-2 green-text margin-left-15"><strong>Remarks&nbsp;:</strong></div>
							<div class="col-xs-9"><?php if(isset($remarks) && $remarks != NULL && $remarks != "") { echo $remarks; } else { echo 'No remarks by the admin for this feedback.'; } ?></div>
						</div>
						<?php } else { ?>
							<div class="col-xs-12">
								<div class="col-xs-12 green-text margin-left-15"><strong>No feedback by the user yet for this order&nbsp;</strong></div>
							</div>
						<?php } ?>
					</div>
				</section>
			</aside>
		</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/highcharts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/app.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/exporting.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.knob.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.sparkline.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/raty/jquery.raty.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script type="text/javascript">
	$(function() {
		add_callbacks();
	});
</script>
</body>
</html>