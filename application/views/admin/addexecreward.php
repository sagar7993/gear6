<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Add Executive Rewards - Admin Panel</title>
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
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.partial.css'); ?>">
	<link rel="stylesheet" type="text/css" href="/nhome/js/lib/swal/sweetalert.css">
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
					Add New Executive Reward
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i>Manage Executive Rewards</a></li>
					<li class="active">Executive Reward</li>
				</ol>
			</section>
			<section class="content">
				<div class="col-xs-12 fields-update-container">
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<select class="form-control styled-select" onchange="addReward();" name="executive" id="executive" data-type="select">
								<option disabled selected style='display:none;' value=''>Select Executive</option>
								<?php if(isset($executives)) { foreach($executives as $executive) { ?>
								<option value="<?php echo $executive->ExecId; ?>"><?php echo convert_to_camel_case($executive->ExecName); ?></option>
								<?php } } ?>
							</select>
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" oninput="addReward();" name="oid" id="oid" placeholder="Order ID" readonly onfocus="this.removeAttribute('readonly');">
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="number" min="0" step="any" class="form-control" oninput="addReward();" name="amount" id="amount" placeholder="Amount" readonly onfocus="this.removeAttribute('readonly');">
						</div>
					</div>
				</div>
				<div class="col-xs-12 fields-update-container">
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<select class="form-control styled-select" onchange="addReward();" name="type" id="type" data-type="select">
								<option disabled selected style='display:none;' value=''>Select Reward Type</option>
								<option value="Credit">Credit</option><option value="Debit">Debit</option>
							</select>
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" oninput="addReward();" name="description" id="description" placeholder="Description" readonly onfocus="this.removeAttribute('readonly');">
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<select class="form-control styled-select" onchange="addReward();" name="ClearFrequency" id="ClearFrequency" data-type="select">
								<option disabled selected style='display:none;' value=''>Clear Frequency</option>
								<option value="0">Daily</option>
								<option value="1">Weekly</option>
								<option value="2">Monthly</option>
							</select>
						</div>
					</div>
				</div>
				<div class="button-box-contact col-xs-12">
					<div class="button-container col-xs-6 col-xs-offset-5">
						 <button class='next btn btn-primary btnUpdate-pu' id="reward_add" disabled>
							Add Reward
						</button>
					</div>
				</div>
			</section>
		</aside><!-- /.right-side -->
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/select2.full.min.js'); ?>"></script>
<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/execreward.js'); ?>"></script>
</script>
</body>
</html>