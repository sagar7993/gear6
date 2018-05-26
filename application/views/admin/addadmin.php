<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Add Admins - Admin Panel</title>
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
					Add New Admin
					<small>Add a new admin</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i>Manage Admins</a></li>
					<li class="active">Add Admin</li>
				</ol>
			</section>
			<section class="content">
				<?php if(isset($err_phone)) { echo '<div class="alert alert-danger" role="alert">' . $err_phone . '</div>'; } ?><br>
				<div class="col-xs-12 fields-update-container">
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" oninput="checkfieldAdmin();" name="fname" id="fname" placeholder="Admin Name" readonly onfocus="this.removeAttribute('readonly');">
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" oninput="checkfieldAdmin();" name="email" id="email" placeholder="Email ID" readonly onfocus="this.removeAttribute('readonly');">
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="password" class="form-control" oninput="checkfieldAdmin();" name="p_password" id="p_password" placeholder="Password" readonly onfocus="this.removeAttribute('readonly');">
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" oninput="checkfieldAdmin();" name="p_phone" id="p_phone" placeholder="Mobile Number" maxlength="10" readonly onfocus="this.removeAttribute('readonly');">
						</div>
					</div>
					<?php if(isset($admin_city_id) && $admin_city_id > 0) { ?>
					<input type="hidden" name="city_id" id="city_id" data-type="input" value="<?php echo $admin_city_id; ?>" />
					<?php } else { ?>
					<div class="col-xs-4 form-group fields-container" id="oType_1" style="">
						<div class="col-xs-12 field-box">
							<select class="form-control styled-select" onchange="checkfieldAdmin();" name="city_id" id="city_id" data-type="select">
								<option disabled selected style='display:none;' value=''>Select City</option>
								<?php if(isset($cities)) { foreach($cities as $city) { ?>
								<option value="<?php echo $city->CityId; ?>"><?php echo convert_to_camel_case($city->CityName); ?></option>
								<?php } } ?>
							</select>
						</div>
					</div>
					<?php } ?>
					<div class=" col-xs-4 form-group fields-container" id="oType_1" style="">
						<div class="col-xs-12 field-box">
							<select class="form-control styled-select" onchange="checkfieldAdmin();" name="upriv" id="upriv">
								<option disabled selected style='display:none;' value=''>Select Privilige</option>
								<option value="Admin">Admin</option>
								<option value="Customer Care">Customer Care</option>
							</select>
						</div>
					</div>
				</div>
				<div class="button-box-contact col-xs-12">
					<div class="button-container col-xs-6 col-xs-offset-5">
						 <button class='next btn btn-primary btnUpdate-pu' id="admin_add" disabled>
							Add Admin
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
<script type="text/javascript" src="<?php echo site_url('js/addadminexec.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
</script>
</body>
</html>