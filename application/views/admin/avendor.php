<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Add Vendors - Admin Panel</title>
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
					Add New Vendor
					<small>Add a new user to any Service Center</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i>Manage Vendors</a></li>
					<li class="active">Add Vendor</li>
				</ol>
			</section>
			<section class="content">
				<?php if(isset($err_phone)) { echo '<div class="alert alert-danger" role="alert">' . $err_phone . '</div>'; } ?><br>
				<div class="col-xs-12 fields-update-container">
					<div class="col-xs-3 form-group fields-container">
						<div class="col-xs-12 field-box">
							<select class="form-control styled-select" onchange="get_scs();" name="city_id" id="city_id">
								<option disabled selected style='display:none;' value=''>Select City</option>
								<?php if(isset($cities)) { foreach($cities as $city) { ?>
								<option value="<?php echo $city->CityId; ?>"><?php echo convert_to_camel_case($city->CityName); ?></option>
								<?php } } ?>
							</select>
						</div>
					</div>
					<div class=" col-xs-3 form-group fields-container ">
						<div class="col-xs-12 field-box">
							<select class="form-control styled-select" onchange="checkfield();" name="sc_id" id="sc_id">
								<option disabled selected style='display:none;' value=''>Service Center</option>
							</select>
						</div>
					</div>
					<div class=" col-xs-3 form-group fields-container ">
						<div class="col-xs-12 field-box">
							<select class="form-control styled-select" onchange="checkfield();" name="gender" id="gender">
								<option disabled selected style='display:none;' value=''>Gender</option>
								<option value="Male">Male</option>
								<option value="Female">Female</option>
							</select>
						</div>
					</div>
					<div class=" col-xs-3 form-group fields-container">
						<div class="col-xs-12 field-box">
							<select class="form-control styled-select" onchange="checkfield();" name="upriv" id="upriv">
								<option disabled selected style='display:none;' value=''>Select Privilige</option>
								<option value="Admin">Admin</option>
								<option value="Super User">Super User</option>
								<option value="User">User</option>
							</select>
						</div>
					</div>
					<div class="col-xs-3 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" oninput="checkfield();" name="fname" id="fname" placeholder="Full Name">
						</div>
					</div>
					<div class="col-xs-3 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control dpDate2" readonly='true' style="cursor:pointer;" onchange="checkfield();" name="dob" id="dob" placeholder="Date of Birth">
						</div>
					</div>
					<div class="col-xs-3 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" oninput="checkfield();" name="p_phone" id="p_phone" placeholder="Mobile Number">
						</div>
					</div>
					<div class="col-xs-3 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" name="alt_ph" id="alt_ph" placeholder="Alternate Mobile Number (Optional)">
						</div>
					</div>
					<div class="col-xs-3 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" oninput="checkfield();" name="email" id="email" placeholder="email ID">
						</div>
					</div>
					<div class="col-xs-6 form-group fields-container">
						<div class="col-xs-12 field-box">
							<textarea  type="text" class="form-control" oninput="checkfield();" name="address" id="address" placeholder="Full Address"></textarea>
						</div>
					</div>
				</div>
				<div class="button-box-contact col-xs-12">
					<div class="button-container col-xs-6 col-md-offset-5">
						 <button class='next btn btn-primary btnUpdate-pu' id="vend_add" disabled>
							Add User
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
<script type="text/javascript" src="<?php echo site_url('js/app.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/select2.full.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/addvend.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script>
$(function () {
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
	try {
		var select2a = $('#city_id').select2({
			placeholder: "Select City",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2a.val(null).trigger("change");
		var select2c = $('#gender').select2({
			placeholder: "Gender",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2c.val(null).trigger("change");
		var select2d = $('#upriv').select2({
			placeholder: "Select Privilege",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2d.val(null).trigger("change");
	} catch(err) {
		//Do Nothing
	}
});
</script>
</body>
</html>