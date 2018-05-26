<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Executive Order Tracking - Admin Panel</title>
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
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.partial.css'); ?>">
	<link rel="stylesheet" type="text/css" href="/nhome/js/lib/swal/sweetalert.css">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/materialize.admin.css'); ?>">
	<style type="text/css">
		.select2-container{
			height: 3.9rem;
		}
		.select2-container--default .select2-selection--single .select2-selection__rendered {
			margin-top: 5px;
		}
	</style>
</head>
<body>
	<?php $this->load->view('admin/components/_head'); ?>
	<?php if(isset($a_is_logged_in) && $a_is_logged_in == 1) { ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<?php $this->load->view('admin/components/_sidebar'); ?>
		<aside class="right-side">
			<section class="content-header">
				<h1>
					Executive Order Tracking
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i>Admin Panel</a></li>
					<li class="active">Executive Order Tracking</li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-xs-12" align="center">
						<h5><b>Filter by date</b></h5>
					</div>
					<div class="col-xs-5 form-group fields-container">
						<input type="text" class="form-control dpDate2 cursor-pointer" readonly='true' onchange="validate_tracking();" name="startDate" id="startDate" placeholder="Start Date" value="<?php if(isset($startDatePicker)) { echo $startDatePicker; } ?>">
					</div>
					<div class="col-xs-5 form-group fields-container">
						<input type="text" class="form-control dpDate2 cursor-pointer" readonly='true' onchange="validate_tracking();" name="endDate" id="endDate" placeholder="End Date" value="<?php if(isset($endDatePicker)) { echo $endDatePicker; } ?>">
					</div>
					<div class="col-xs-2 form-group fields-container">
						<button class='next btn btn-primary btnUpdate-pu' id="filterTracking" disabled="disabled">
							Track Orders
						</button>
					</div>
				</div>
			</section>
			<section class="content">
				<h4 align="center">Showing results between <b><?php echo $startDate; ?></b> and <b><?php echo $endDate; ?></b></h4>
					<?php foreach($rows as $row) { ?>
					<ul class="collapsible popout" data-collapsible="accordion">
						<li>
							<div class="collapsible-header<?php if(count($row['bikes']) > 0) { echo ' active'; } ?>"><i class="material-icons">history</i><b><?php echo $row['dateFormat']; ?></b></div>
							<div class="collapsible-body">
								<br/>
								<?php if(count($row['bikes']) == 0) { ?>
									<div class="row">
										<div class="col-xs-12">
											<h4 align="center">No orders</h4>
										</div>
									</div>
								<?php } else { ?>
									<div class="col-xs-12 status-history-content-container" style="border-top:2px dotted #a7a7a7;border-bottom:2px dotted #a7a7a7;padding-top:10px;margin-bottom:10px;">
										<div class="form-group col-xs-3" style="border-right:2px dotted #a7a7a7;">
											Executive
										</div>
										<div class="form-group col-xs-3" style="border-right:2px dotted #a7a7a7;">
											Phone
										</div>
										<div class="form-group col-xs-3" style="border-right:2px dotted #a7a7a7;">
											Bikes Picked
										</div>
										<div class="form-group col-xs-3">
											Bikes Delivered
										</div>
									</div>
									<?php foreach($row['bikes'] as $bike) { ?>
										<div class="col-xs-12 status-history-content-container">
											<div class="form-group col-xs-3" style="border-right:2px dotted #a7a7a7;">
												<?php echo $bike['ExecName']; ?>
											</div>
											<div class="form-group col-xs-3" style="border-right:2px dotted #a7a7a7;">
												<?php echo $bike['Phone']; ?>
											</div>
											<div class="form-group col-xs-3" style="border-right:2px dotted #a7a7a7;">
												<?php if($bike['picked'] > 0) { ?>
													<a target="_blank" href="<?php echo site_url('admin/orders/getPickupDetails/pickup/' . $bike['ExecId'] . '/' . $row['date']); ?>" class="order-id-link">
														<?php echo $bike['picked']; ?>
													</a>
												<?php } else { ?>
													<?php echo $bike['picked']; ?>
												<?php } ?>
											</div>
											<div class="form-group col-xs-3">
												<?php if($bike['delivered'] > 0) { ?>
													<a target="_blank" href="<?php echo site_url('admin/orders/getPickupDetails/delivered/' . $bike['ExecId'] . '/' . $row['date']); ?>" class="order-id-link">
														<?php echo $bike['delivered']; ?>
													</a>
												<?php } else { ?>
													<?php echo $bike['delivered']; ?>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
								<?php } ?>
							</div>
						</li>
					</ul>
				<?php } ?>
			</section>
		</aside><!-- /.right-side -->
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/select2.full.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
<script type="text/javascript" src="<?php echo site_url('js/materialize.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/execreward.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
</body>
</html>