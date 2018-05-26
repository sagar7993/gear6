<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Service Center Details - Admin Panel</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link rel="shortcut icon" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/vstyle.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/ionicons.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/module.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/datatables/dataTables.bootstrap.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/jQueryUI/jquery-ui.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/custom-jquery.css'); ?>">
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
					Service Center Details - <?php if (isset($sc_details)) { echo convert_to_camel_case($sc_details['ScName']); } ?>
					<small>Vendors</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Service Center Details</a></li>
					<li class="active"> Vendors</li>
				</ol>
			</section>
			<section class="contact-content">
				<div class="col-xs-6 fields-update-container">
					<div class="col-xs-12 fields-container">
						<div class="col-xs-10 field-box">
							<input type="text" class="form-control" readonly name="sc_name" id="sc_name" value="<?php echo convert_to_camel_case($sc_details['ScName']); ?>" placeholder="Service Center Name">
						</div>
					</div>
					<div class="col-xs-12 fields-container">
						<div class="col-xs-10 field-box">
							<input type="text" class="form-control" readonly name="owner_name" id="owner_name" value="" placeholder="Owner's Name">
						</div>
					</div>
					<div class="col-xs-12 fields-container">
						<div class="col-xs-10 field-box">
							<input type="text" class="form-control" readonly name="email" id="email" value="" placeholder="Service Center Email">
						</div>
					</div>
					<div class="col-xs-12 fields-container ll-box">
						<div class="ll-container">
							<div class="col-xs-10 field-box">
								<input type="text" class="form-control" readonly name="landline" id="landline" value="" placeholder="Landline Number">
							</div>
						</div>
					</div>
					<div class="col-xs-12 fields-container ph-box">
						<div class="ph-container">
							<div class="col-xs-10 field-box">
								<input type="text" class="form-control" readonly name="phNum" id="phNum" value="" placeholder="Mobile Number">
							</div>
						</div>
					</div>
					<div class="col-xs-12 fields-container ph-box">
						<div class="ph-container">
							<div class="col-xs-10 field-box">
								<input type="text" class="form-control" readonly name="city" id="city" value="" placeholder="City Name">
							</div>
						</div>
					</div>
					<div class="col-xs-12">
						<?php
							$readonly = ' readonly="true"';
						?>
						<div class="col-xs-10 field-box">
							<label>Address</label><br>
						</div>
						<div class="col-xs-12 addr-block-ua" id="addr-block">
							<div class="form-group col-xs-6" style="width: 51.5%;">
								<input type="text" id="addr1"<?php echo $readonly; ?> style="margin-left: -8px;" class="form-control" name="adln1" id="adln1" placeholder="Address Line1">
							</div>
							<div class="form-group col-xs-6" style="width:52%">
								<input type="text" id="addr2"<?php echo $readonly; ?> style="margin-left: -8px;" class="form-control" name="adln2" id="adln2" placeholder="Address Line2">
							</div>
							<div class="form-group col-xs-6 loc-1" style="width: 51.5%;">
								<input type="text" id="addr_location"<?php echo $readonly; ?> style="margin-left: -8px;" class="form-control area" name="location" placeholder="Location">
							</div>
							<div class="form-group col-xs-6" style="width:52%">
								<input type="text" id="addr_landmark"<?php echo $readonly; ?> style="margin-left: -8px;" class="form-control" name="landmark" placeholder="Landmark (Optional)">
								<input type="hidden" id="sc_addr_slit_id" value="">
							</div>
						</div>
						<?php
						if(isset($sc_details)) {
							echo '<div class="user-addr-content" id="addr_content" style="display:none;">
								<div>' . convert_to_camel_case($sc_details['AddrLine1']) . '</div>
								<div>' . convert_to_camel_case($sc_details['AddrLine2']) . '</div>
								<div>' . $sc_details['LocationName'] . '</div>
								<div>' . convert_to_camel_case($sc_details['Landmark']) . '</div>
								<div>' . $sc_details['CityName'] . '</div>
								<div>' . convert_to_camel_case($sc_details['ScName']) . '</div>
								<div>' . convert_to_camel_case($sc_details['Owner']) . '</div>
								<div>' . $sc_details['Email'] . '</div>
								<div>' . $sc_details['Landline'] . '</div>
								<div>' . $sc_details['Phone'] . '</div>
								<div>' . $sc_details['Latitude'] . '</div>
								<div>' . $sc_details['Longitude'] . '</div>
							</div>';
						}
						?>
					</div>
					<div class="col-xs-12 fields-container">
						<div class="col-xs-10 field-box">
							<div class="col-xs-6">
								<input type="number" class="col-xs-6 form-control" name="lat_num" id="lat_num" placeholder="Latitude">
							</div>
							<div class="col-xs-6">
								<input type="number" class="col-xs-6 form-control" name="lon_num" id="lon_num" placeholder="Longitude">
							</div>
						</div>
					</div>
				</div>
			</section>
		</aside><!-- /.right-side -->
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/app.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script>
$(function () {
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
	insertAddressValues();
});
function insertAddressValues() {
	$('#addr1').val($('#addr_content :first-child').text());
	$('#addr2').val($('#addr_content :nth-child(2)').text());
	$('#addr_location').val($('#addr_content :nth-child(3)').text());
	$('#addr_landmark').val($('#addr_content :nth-child(4)').text());
	$('#city').val($('#addr_content :nth-child(5)').text());
	$('#sc_name').val($('#addr_content :nth-child(6)').text());
	$('#owner_name').val($('#addr_content :nth-child(7)').text());
	$('#email').val($('#addr_content :nth-child(8)').text());
	$('#landline').val($('#addr_content :nth-child(9)').text());
	$('#phNum').val($('#addr_content :nth-child(10)').text());
	$("#lat_num").val($('#addr_content :nth-child(11)').text());
	$("#lon_num").val($('#addr_content :nth-child(12)').text());
}
</script>
</body>
</html>