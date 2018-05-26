<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Slot Management - Admin Panel</title>
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
					<small>Slot Management</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Vendor Dashboard</li>
				</ol>
			</section>
			<section class="content">
				<div class="pickup-title teal-bold">Bulk Update Slots Across Vendors</div>
				<form method="POST" action="/admin/vendors/bulk_update_slots">
				<div class="col-xs-12 fields-update-container">
					<div class="col-xs-12 fields-container">
						<div class="col-xs-6 field-box">
							<input type="number" class="col-xs-12 form-control" min="0" name="slot_count" id="slot_count" placeholder="Slots Count Per Each Slot Duration" required>
						</div>
						<div class="col-xs-6 field-box">
							<input type="text" class="col-xs-12 form-control dpDate" name="bul_dates" id="bul_dates" placeholder="Choose dates" required readonly />
						</div>
					</div>
					<div class="pickup-title teal-bold">Choose Service Centers</div>
					<div class="col-xs-12 fields-container">
						<div class="input-field col-xs-4">
							<div class="checkbox" style=""><label class="scs"><input type="checkbox" id="sc_ckbs"><span style="margin-left:15px">Select All</span></label></div>
						</div>
						<?php if(isset($scs) && count($scs) > 0) { foreach($scs as $sc) { ?>
						<div class="input-field col-xs-4">
							<div class="checkbox" style=""><label class="scs"><input type="checkbox" name="sc_ids[]" value="<?php echo $sc->ScId; ?>" class="sc_ckbs"><span style="margin-left:15px"><?php echo convert_to_camel_case($sc->ScName); ?></span></label></div>
						</div>
						<?php } } ?>
					</div>
					<div class="pickup-title teal-bold">Choose Hours</div>
					<div class="col-xs-12 fields-container">
						<div class="input-field col-xs-4">
							<div class="checkbox" style=""><label class="shs"><input type="checkbox" id="sh_ckbs"><span style="margin-left:15px">Select All</span></label></div>
						</div>
						<?php for($i = 8; $i <= 17; $i += 0.5) {
							if ($i > 12) {
								$temp_hr = intval($i - 12);
								if($temp_hr == 0) {
									$temp_hr = 12;
								}
								$temp = (intval($i * 60) % 60);
								if($temp == 0) {
									$temp = '00';
								}
								$slot_hour = $temp_hr . ":" . $temp . " PM";
							} elseif ($i == 12) {
								$slot_hour = intval($i) . ":00 PM";
							} else {
								$temp = (intval($i * 60) % 60);
								if($temp == 0) {
									$temp = '00';
								}
								$slot_hour = intval($i) . ":" . $temp . " AM";
							}
						?>
						<div class="input-field col-xs-4">
							<div class="checkbox" style=""><label class="shs"><input type="checkbox" name="slot_hours[]" value="<?php echo $i; ?>" class="sh_ckbs"><span style="margin-left:15px"><?php echo $slot_hour; ?></span></label></div>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="col-xs-12 text-center">
					<button type="submit" name="update_slots" class="btn btn-primary" >
						Update
					</button>
				</div>
				</form>
			</section>
		</aside><!-- /.right-side -->
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/highcharts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/exporting.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.knob.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.sparkline.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/raty/jquery.raty.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.multidatespicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script>
$(function() {
	$("input[type='checkbox'], input[type='radio']").icheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
	$('#bul_dates').multiDatesPicker({
		dateFormat: "yy-mm-dd",
		minDate: 0,
		maxDate: 45
	});
	$('#sc_ckbs').on('ifChecked', function() {
		$('.sc_ckbs').icheck('checked');
	});
	$('#sc_ckbs').on('ifUnchecked', function() {
		$('.sc_ckbs').icheck('unchecked');
	});
	$('#sh_ckbs').on('ifChecked', function() {
		$('.sh_ckbs').icheck('checked');
	});
	$('#sh_ckbs').on('ifUnchecked', function() {
		$('.sh_ckbs').icheck('unchecked');
	});
});
</script>
</body>
</html>