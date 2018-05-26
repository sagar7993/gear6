<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Add Admin Reminder - Admin Panel</title>
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
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/jqueryui-timepicker.css'); ?>">
	<style type="text/css">
		.select2-container{
			height: 4rem!important;
		}
		.select2-container--default.select2-container--focus .select2-selection--multiple {
		  border: none;
		  outline: 0;
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
					Add New Admin Reminder
					<small>Add a new admin reminder</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i>Manage Admins</a></li>
					<li class="active">Add Admin Reminder</li>
				</ol>
			</section>
			<section class="content">
				<?php if(isset($err_phone)) { echo '<div class="alert alert-danger" role="alert">' . $err_phone . '</div>'; } ?><br>
				<div class="col-xs-12 fields-update-container">
					<div class="row">
						<div class="col-xs-4 form-group fields-container">
							<div class="col-xs-12 field-box">
								<input type="text" class="form-control" onchange="checkfieldAdmin();" name="reminder_date" id="reminder_date" placeholder="Reminder Date" readonly onfocus="this.removeAttribute('readonly');">
							</div>
						</div>
						<div class="col-xs-4 form-group fields-container">
							<div class="col-xs-12 field-box">
								<input type="text" class="form-control" onchange="checkfieldAdmin();" name="reminder_time" id="reminder_time" placeholder="Reminder Time" readonly onfocus="this.removeAttribute('readonly');">
							</div>
						</div>
						<div class="col-xs-4 form-group fields-container">
							<div class="col-xs-12 field-box">
								<input type="text" class="form-control" oninput="checkfieldAdmin();" name="description" id="description" placeholder="Reminder Text" readonly onfocus="this.removeAttribute('readonly');">
							</div>
						</div>
					</div>
					<div class="row">
						<div class=" col-xs-4 form-group fields-container">
							<div class="col-xs-12 field-box">
								<select class="form-control styled-select" onchange="get_remind_to();" name="user_type" id="user_type">
									<option disabled style='display:none;' value=''>User Type</option>
									<option selected value="Admin">Admin</option>
									<option value="Executive">Executive</option>
								</select>
							</div>
						</div>
						<div class="col-xs-4 form-group fields-container">
							<div class="col-xs-12 field-box">
								<select multiple="multiple" class="form-control styled-select" onchange="checkfieldAdmin();" name="remind_to" id="remind_to">
									<option disabled selected style='display:none;' value=''>Remind To</option>
									<?php foreach($admin as $a) { ?>
										<option value="<?php echo $a->AdminId ?>"><?php echo $a->AdminName ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class=" col-xs-4 form-group fields-container">
							<div class="col-xs-12 field-box">
								<select class="form-control styled-select" onchange="checkfieldAdmin();" name="send_sms" id="send_sms">
									<option disabled selected style='display:none;' value=''>Send SMS</option>
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="button-box-contact col-xs-12">
					<div class="button-container col-xs-6 col-xs-offset-5">
						 <button class='next btn btn-primary btnUpdate-pu' id="add_reminder" disabled>
							Add Reminder
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
<script type="text/javascript" src="<?php echo site_url('js/addadminreminder.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jqueryui-timepicker.js'); ?>"></script>
<script type="text/javascript">
	var admin = <?php echo json_encode($admin); ?>;
	var executive = <?php echo json_encode($executive); ?>
</script>
</body>
</html>