<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Panel - Slots Management</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link rel="shortcut icon" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/ionicons.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/module.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/vstyle1.css'); ?>">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Oxygen" type="text/css">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/fullcalendar.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/fullcalendar.print.css'); ?>" media="print" />
</head>
<body >
	<style>
		.success-dialog {
			background: #FFF !important;
			border: 1px solid #90d93f;
		}
		.ui-dialog-titlebar-close {
			margin-top: -12px !important;
			background: white !important;
		}
		.ui-dialog .ui-dialog-titlebar {
			background: teal;
			padding: 0.1em .5em;
			position: relative;
			color: #fff;
			font-size: 1em;
		}
		.reset {
			margin-top: 14px;
			font-size: 12px;
		}
		.left-0 {
			margin-left:0 !important;
		}
		.error-text, .error-text1 {
			margin-top: -15px;
			margin-bottom: -10px;
			color: #D50909;
			font-size: 12px;
			font-style: italic;
		}
		.fc-day-grid-event {
			width: 100%;
		}
		.ui-dialog {
			width: 50% !important;
		}
	</style>
	<?php if(isset($v_is_logged_in) && $v_is_logged_in == 1) { ?>
	<main class="container">
		<section class="content-header">
			<h1>
				Slot Management
				<small>View/Add/Modify</small>
			</h1>
			<ol class="breadcrumb slotm-bc">
				<li><a href="<?php echo base_url('vendor/vendorhome'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active"><a href="<?php echo base_url('vendor/profile/'); ?>">Profile</a></li>
				<li class="active"><a href="<?php echo base_url('vendor/profile/slotmgmt'); ?>">Slot Management</a></li>
			</ol>
		</section>
		<section class="radius-content" style="margin-top:0">
			<div class="col-xs-12 radius-box">
				<form method="POST" action="/vendor/profile/updateDefaultSlots">
				<div class="pickup-title teal-bold">Default Slots</div>
				<div class="sub-title-grey">Choose a default number of slots to be displayed to the customer when there were no specific slots allotted by you for a particular day</div>
				<div class="col-xs-12 area-container">
					<div class="col-xs-6">
						<input type="number" class="col-xs-6 form-control" name="def_slot_val" value="<?php if(isset($def_slots)) { echo $def_slots; } else { echo 1; } ?>" id="def_slot_val" min="1" max="20" placeholder="Default Number of Slots">
					</div>
					<button type="submit" name="def_slot_sub" id="def_slot_sub" class="btn btn-primary">
						Update Default Slots
					</button>
				</div>
				</form>
				<form method="POST" action="/vendor/profile/bulkUpdateSlots" style="padding-top: 70px; !important">
				<div class="pickup-title teal-bold">Bulk Update Slots</div>
				<div class="sub-title-grey">Choose multiple dates to bulk update / add a slot count per slot duration</div>
				<div class="col-xs-12 area-container">
					<div class="col-xs-6">
						<input type="number" class="col-xs-6 form-control" name="bul_num_slots" value="<?php if(isset($def_slots)) { echo $def_slots; } else { echo 1; } ?>" id="bul_num_slots" min="0" max="20" placeholder="Number of slots per duration">
					</div>
					<div class="col-xs-3">
						<input data-type="text" data-mandatory="true" data-error="Dates" type="text" class="col-xs-12 form-control dpDate" name="bul_dates" id="bulkUpdateCalender" placeholder="Choose dates" required readonly />
					</div>
					<div class="col-xs-3">
						<button type="submit" id="sub_bul_slots" class="btn btn-primary">
							Bulk Update Slots
						</button>
					</div>
				</div>
				</form>
			</div>
		</section><!-- /.content -->
		<br><br>
		<div id='calendar' class="cal-theme margin-top-n30" style="margin-top:-30px !important"></div>
		<div id="dialog" style="display:none;">
			<form method="POST" action="/vendor/profile/set_slots">
			<div class="button-content" id="dialog_slots_content">
			</div>
			<input type="hidden" name="selected_date" id="slotseldate" />
			<div class=" center">
				<div class="button-box button-content col-xs-12">
					<div class="button-container col-xs-offset-1">
						<button type="submit" class='next btn btn-primary btnUpdate-pu' id="SlotSubmitBtn" disabled>
							Update
						</button>
						<button class='next btn btn-primary btnCancel-pu' id="cancel" >
							Cancel
						</button>
					</div>
				</div>
			</div>
			</form>
		</div>
		<div style='clear:both'></div>
	</main>
	<?php $this->load->view('vendor/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/moment.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.custom.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/fullcalendar.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/vnotify.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/vendor.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/vslotm.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.multidatespicker.js'); ?>"></script>
<script type="text/javascript">
$(function() {
	$('#bulkUpdateCalender').multiDatesPicker({
		dateFormat: "yy-mm-dd",
		minDate: 0,
		maxDate: 45,
		<?php if(isset($dis_dates)) { echo "addDisabledDates: " . $dis_dates; } ?>
	});
	$('#sub_bul_slots').on('click', function(event) {
		$('form input').each(function() {
			if($(this).data('mandatory') == true) {
				if($(this).data('type') == 'text') {
					if($(this).val() == "") {
						showValidation($(this).attr('id'), "Choose atleast one date");
					}
				}
			}
		});
	});
});
</script>
</body>
</html>