<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Add Executives - Admin Panel</title>
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
					Add New Executive
					<small>Add a new executive</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i>Manage Executives</a></li>
					<li class="active">Add Executive</li>
				</ol>
			</section>
			<section class="content">
				<?php if(isset($err_phone)) { echo '<div class="alert alert-danger" role="alert">' . $err_phone . '</div>'; } ?><br>
				<div class="col-xs-12 fields-update-container">
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control dpDate2" readonly='true' style="cursor:pointer;" onchange="checkfieldExecutive();" name="dob" id="dob" placeholder="Date of Birth">
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" oninput="checkfieldExecutive();" name="fname" id="fname" placeholder="Executive Name" readonly onfocus="this.removeAttribute('readonly');">
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" oninput="checkfieldExecutive();" name="p_phone" id="p_phone" placeholder="Mobile Number" maxlength="10" readonly onfocus="this.removeAttribute('readonly');">
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" oninput="checkfieldExecutive();" name="email" id="email" placeholder="Email ID" readonly onfocus="this.removeAttribute('readonly');">
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="password" class="form-control" oninput="checkfieldExecutive();" name="p_password" id="p_password" placeholder="Password" readonly onfocus="this.removeAttribute('readonly');">
						</div>
					</div>
					<?php if(isset($admin_city_id) && $admin_city_id > 0) { ?>
					<input type="hidden" name="city_id" id="city_id" data-type="input" value="<?php echo $admin_city_id; ?>" />
					<?php } else { ?>
					<div class="col-xs-4 form-group fields-container" id="oType_1" style="">
						<div class="col-xs-12 field-box">
							<select class="form-control styled-select" onchange="checkfieldExecutive();" name="city_id" id="city_id" data-type="select">
								<option disabled selected style='display:none;' value=''>Select City</option>
								<?php if(isset($cities)) { foreach($cities as $city) { ?>
								<option value="<?php echo $city->CityId; ?>"><?php echo convert_to_camel_case($city->CityName); ?></option>
								<?php } } ?>
							</select>
						</div>
					</div>
					<?php } ?>
					<div class=" col-xs-4 form-group fields-container " id="oType_1" style="">
						<div class="col-xs-12 field-box">
							<select class="form-control styled-select" onchange="checkfieldExecutive();" name="isActive" id="isActive">
								<option disabled selected style='display:none;' value=''>Is Active</option>
								<option value="0">0</option>
								<option value="1">1</option>
							</select>
						</div>
					</div>
					<div class=" col-xs-4 form-group fields-container " id="oType_1" style="">
						<div class="col-xs-12 field-box">
							<select class="form-control styled-select" onchange="checkfieldExecutive();" name="gender" id="gender">
								<option disabled selected style='display:none;' value=''>Gender</option>
								<option value="Male">Male</option>
								<option value="Female">Female</option>
							</select>
						</div>
					</div>
				</div>
				<div class="button-box-contact col-xs-12">
					<div class="button-container col-xs-6 col-xs-offset-5">
						 <button class='next btn btn-primary btnUpdate-pu' id="executive_add" disabled>
							Add Executive
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
<script type="text/javascript" src="<?php echo site_url('js/jquery.timepicker.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.ui.datepicker.validation.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/select2.full.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/addadminexec.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
</body>
</html>