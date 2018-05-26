<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Holiday Management - Admin Panel</title>
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
</head>
<body>
	<?php $this->load->view('admin/components/_head'); ?>
	<?php if(isset($a_is_logged_in) && $a_is_logged_in == 1) { ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<?php $this->load->view('admin/components/_sidebar'); ?>
		<aside class="right-side">
			<section class="content-header">
				<h1>
					Service Centers
					<small>Holiday Management</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
					<li class="active">Vendor Dashboard</li>
				</ol>
			</section>
			<section class="content">
				<div class="pickup-title teal-bold" align="center">Bulk Update Holidays Across Vendors</div>
				<div class="col-xs-12 fields-update-container">
					<div class="col-xs-12 fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="col-xs-12 form-control dpDate" name="holiday_dates" id="holiday_dates" placeholder="Choose dates" required readonly/>
						</div>
					</div>
					<div class="pickup-title teal-bold" align="center">Choose Service Centers</div><br/>
					<div class="col-xs-12 fields-container">
						<div class="input-field col-xs-6" align="center">
							<div class="checkbox" style="">
								<label class="scs"><input type="checkbox" id="sc_ckbs">
									<span style="margin-left:15px">Select / Unselect All Service Centers</span>
								</label>
							</div>
						</div>
						<div class="input-field col-xs-6" align="center">
							<div class="checkbox" style="">
								<label class="scs"><input type="checkbox" id="g6_ckbs" value="-1" onchange="validation();">
									<span style="margin-left:15px">Gear6</span>
								</label>
							</div>
						</div>
						<br/><br/><br/>
						<?php if(isset($scs) && count($scs) > 0) { foreach($scs as $sc) { ?>
						<?php if($sc->ScId != -1) { ?>
						<div class="input-field col-xs-4">
							<div class="checkbox" style="">
								<label class="scs">
									<input type="checkbox" name="sc_ids[]" id="<?php echo $sc->ScId; ?>" value="<?php echo $sc->ScId; ?>" class="sc_ckbs" onchange="validation();">
									<span style="margin-left:15px"><?php echo convert_to_camel_case($sc->ScName); ?></span>
								</label>
							</div>
						</div>
						<?php } } } ?>
					</div>
				</div>
				<div class="col-xs-12 text-center">
					<button type="submit" id="update_holidays" name="update_holidays" class="btn btn-primary" disabled>
						Update
					</button>
				</div>
			</section>
		</aside><!-- right-side -->
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.multidatespicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/holiday.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script>
$(function () {
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
});
</script>
</body>
</html>