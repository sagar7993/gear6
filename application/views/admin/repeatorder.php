<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Repeat Order - Admin Panel</title>
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
	<style>
	.pickup-title {
		margin-bottom: 10px;
	}
	</style>
	<?php $this->load->view('admin/components/_head'); ?>
	<?php if(isset($a_is_logged_in) && $a_is_logged_in == 1) { ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<?php $this->load->view('admin/components/_sidebar'); ?>
		<aside class="right-side">
			<section class="content-header">
				<h1>
					Orders Dashboard
					<small>New Order</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Repeat Order</li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
						<form class="col-xs-12" method="POST" action="/admin/orders/fetch_sc_details">
						<div class="row">
							<div class="pickup-title teal-bold">Service Center Name</div>
							<div id="sc_fetch_msg" style="color:crimson;"></div>
							<div class="form-group col-xs-6">
								<input type="text" class="form-control" placeholder="Enter the service center name" name="sc_name" id="sc_name" <?php if(isset($scname)) { echo 'value="' . $scname . '"'; } ?>>
							</div>
							<div class="form-group col-xs-3">
								<button type="submit" class="btn btn-primary waves-effect waves-light" id="fetch_sc_details">
									Fetch Sc Details
								</button>
							</div>
							<div class="form-group col-xs-3">
								<div class="checkbox" style=""><label class="dontsendsms"><input type="checkbox" value="1" id="dontsendsms"><span style="margin-left:5px">Don't SMS</span></label></div>
							</div>
						</div>
						</form>
						<?php if(isset($sc_chosen) && $sc_chosen == 1) { ?>
						<form class="col-xs-12" method="POST" action="/admin/orders/finalize_order">
						<div class="row" id="main_placeorder_page">
							<div class="row">
								<div class="col-xs-6">
									<div class="pickup-title teal-bold">Choose a Business</div>
									<div class="form-group col-xs-12">
										<select class="form-control" title="Select a Business" id="business" name="business">
											<?php if(isset($businesses)) { foreach($businesses as $business) { ?>
											<option value="<?php echo $business->TieupId; ?>"<?php if($business->TieupId == 1) { echo ' selected'; } ?>><?php echo $business->TieupName; ?></option>
											<?php } } ?>
										</select>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="pickup-title teal-bold">Grievance Order?</div>
									<div class="form-group col-xs-12">
										<select class="form-control" title="Grievance Order" id="isGrievance" name="isGrievance">
											<option value="0" selected>New / Repeat Order</option>
											<option value="1">Grievance Order</option>
										</select>
									</div>
								</div>
								<div class="pickup-title teal-bold" style="clear: left;">Enter user phone number</div>
								<div id="ph_msg" style="display:none;color:crimson;">
								</div>
								<div class="form-group col-xs-6">
									<input data-type="phone" data-mandatory="true" data-error="Phone Number" type="text" class="form-control" placeholder="Enter the user phone number (10 digits)" name="phone" id="phone_num" value="<?php echo $user_details['Phone']?>"/>
									<input type="hidden" name="user_id" id="user_id">
									<input type="hidden" name="user_addr_id" id="user_addr_id" value="0">
								</div>
								<div class="form-group col-xs-6">
									<button type="button" class="btn btn-primary waves-effect waves-light" id="fetch_user_details">
										Fetch User Details
									</button>
								</div>
								<input type="hidden" class="sendsmsclass" name="sendsmsflag" value="1">
							</div>
							<div class="row">
								<div class="pickup-title teal-bold">Choose the service</div>
								<?php if(isset($services)) { foreach($services as $service) { ?>
								<div class="form-group col-xs-3">
									<div class="checkbox" style=""><label class="bservice"><input type="radio" name="user_service" value="<?php echo $service['ServiceId']; ?>" class="user_service" <?php if($service_id == $service['ServiceId']) { echo "checked"; } ?>><span style="margin-left:5px"><?php echo convert_to_camel_case($service['ServiceName']); ?></span></label></div>
								</div>
								<?php } } ?>
								<div class="form-group col-xs-12">
									<div id="ser_err"></div>
								</div>
							</div>
							<div class="row">
								<div class="pickup-title teal-bold">Choose the bike company</div>
								<?php if(isset($bikecompanies)) { $count = 0; foreach($bikecompanies as $company) { ?>
								<div class="form-group col-xs-3">
									<div class="checkbox" style=""><label class="bcompany"><input type="radio" name="user_bikecomp" value="<?php echo $company['BikeCompanyId']; ?>" class="user_bikecomp"><span style="margin-left:5px"><?php echo convert_to_camel_case($company['BikeCompanyName']); ?></span></label></div>
								</div>
								<?php $count += 1; } } ?>
							</div>
							<div class="row">
								<div class="pickup-title teal-bold">Choose the bike model</div>
								<div class="form-group col-xs-3">
									<select data-type="text" data-mandatory="true" data-error="Bike Model" class="form-control" title="Select the Bike Model" id="user_bikemodel" name="user_bikemodel">
										<option selected style="display:none;" value="">Choose bike model</option>
										<?php if(isset($bikemodels)) { foreach($bikemodels as $bikemodel) { ?>
										<option value="<?php echo $bikemodel['BikeModelId']; ?>" <?php if($BikeModelId == $bikemodel['BikeModelId']) { echo "selected"; } ?>><?php echo convert_to_camel_case($bikemodel['BikeModelName']); ?></option>
										<?php } } ?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="pickup-title teal-bold">Choose service date</div>
								<div id="slots_msg" style="display:none;color:crimson;">
								</div>
								<div class="form-group col-xs-3">
									<input data-type="text" data-mandatory="true" data-error="Order Date" type="text" class="form-control dpDate2" readonly='true' style="cursor:pointer;" name="user_date" id="user_date" placeholder="Choose service date">
									<input type="hidden" name="user_odate" id="user_odate">
								</div>
								<div class="form-group col-xs-6">
									<button type="button" class="btn btn-primary waves-effect waves-light" id="fetch_user_slots">
										Fetch Slots
									</button>
								</div>
								<div class="form-group col-xs-12">
									<div id="slot_err"></div>
								</div>
								<div id="shwRum_buk_slots" class="col-xs-12 slotsContainer"></div>
							</div>
							<div class="row" id="user_amenity_block" style="display:none;">
							</div>
							<div class="row" id="user_aser_block" style="display:none;">
							</div>
							<div class="row" id="ins_ren_block" style="display:none;">
								<div class="pickup-title teal-bold">Enter Insurance Details</div>
								<div class="form-group col-xs-4 margin-bottom-20px">
									<select class="form-control" data-error="Registration year" id="regYear" name="regYear">
										<option style="display:none;" selected>Choose Registration Year</option>
										<option value="2014">2015</option>
										<option value="2014">2014</option>
										<option value="2013">2013</option>
										<option value="2012">2012</option>
										<option value="2011">2011</option>
										<option value="2010">2010</option>
										<option value="2009">2009</option>
										<option value="2008">2008</option>
										<option value="2007">2007</option>
										<option value="2006">2006</option>
										<option value="2005">2005</option>
									</select>
								</div>
								<div class="form-group col-xs-4 margin-bottom-20px" style="">
									<select class="form-control" data-error="Expiry Date" id="expDate" name="expDate">
										<option style="display:none;" selected>Choose Expiry Date</option>
										<option value="10">Next 10 Days</option>
										<option value="30">Next 30 Days</option>
										<option value="60">More than 60 Days</option>
										<option value="0">Already Expired</option>
									</select>
								</div>
								<div class="form-group col-xs-4 margin-bottom-20px" style="">
									<select class="form-control"  data-error="Previous Insurer" id="prevIns" name="prevIns">
										<option style="display:none;" selected>Choose Your Previous Insurer</option>
										<?php
											if (isset($insurers)) {
												foreach ($insurers as $insurer) {
													echo '<option value="' . $insurer->InsurerId . '">' . $insurer->InsurerName . '</option>';
												}
											}
										?>
									</select>
								</div>
								<div class="form-group col-xs-6" style="margin-top: 0.5%;">
									<label><input id="claims_made" type="checkbox" name="isClaimed" value="1"><span style="margin-lefT:10px;margin-bottom:1px;">I have made Claims in Previous Policy</span></label>
								</div>
							</div>
							<div class="row">
								<div class="pickup-title teal-bold">Fill / Verify User Details</div>
								<div class="col-xs-12">
									<div class="form-group col-xs-4">
										<input data-type="text" data-mandatory="true" data-error="Full Name" type="text" class="form-control" name="full_name" id="full_name" placeholder="Enter User Full Name">
									</div>
									<div class="form-group col-xs-4">
										<input data-type="email" data-mandatory="true" data-error="Email" type="text" class="form-control" name="email" id="email" placeholder="Enter User Email">
									</div>
									<div class="form-group col-xs-2">
										<input data-type="regnum" data-mandatory="true" data-error="Reg No." type="text" class="form-control" name="reg_num" id="reg_num" placeholder="Reg No." value="<?php echo $RegNumber; ?>">
									</div>
									<div class="form-group col-xs-2">
										<input data-type="bikenum" data-mandatory="true" data-error="Bike Number" type="text" class="form-control" name="bike_num" id="bike_num" placeholder="User Bike Number" value="<?php echo $BikeNumber; ?>">
									</div>
								</div>
								<div class="col-xs-8">
									<div class="form-group col-xs-6">
										<input data-type="text" data-mandatory="true" data-error="Flat / House No." type="text" class="form-control check_for_addr_change" name="adln1" id="adln1" placeholder="Flat/ House No.">
									</div>
									<div class="form-group col-xs-6">
										<input data-type="text" data-mandatory="true" data-error="Street Name" type="text" class="form-control check_for_addr_change" name="adln2" id="adln2" placeholder="Street Name">
									</div>
									<div class="form-group col-xs-6">
										<input data-type="location" data-mandatory="true" data-error="Location Name" type="text" class="form-control check_for_addr_change" name="location" id="location" placeholder="Location Name">
									</div>
									<input type="hidden" id="ulatitude" name="nulati">
									<input type="hidden" id="ulongitude" name="nulongi">
									<div class="form-group col-xs-6">
										<input type="text" class="form-control check_for_addr_change" name="landmark" id="landmark" placeholder="Landmark (Optional)">
									</div>
								</div>
								<div class="col-xs-4">
									<div class="col-xs-12" style="margin-left: -40px;">
										<textarea class="form-control" name="comments" id="comments" placeholder="Service Comments"></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div style="text-align: center;">
									<button type="submit" class="btn btn-primary waves-effect waves-light" id="final_submit">
										Repeat Order
									</button>
								</div>
							</div>
						</div>
					</form>
					<?php } ?>
				</div>
			</section><!-- /.content -->
		</aside>
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/highcharts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCZ126reFV784ZQTqw_JfD08mnS0jI7nWo&libraries=places"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/exporting.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.knob.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.sparkline.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/picker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script type="text/javascript">
var availableRegNums = [<?php if (isset($regnums)) { echo $regnums; } ?>];
var availableScs = [<?php if (isset($scnames)) { echo $scnames; } ?>];
<?php if(isset($city_row)) { ?>
	var swlati = <?php echo $city_row->SwLati; ?>;
	var swlongi = <?php echo $city_row->SwLongi; ?>;
	var nelati = <?php echo $city_row->NeLati; ?>;
	var nelongi = <?php echo $city_row->NeLongi; ?>;
<?php } else { ?>
	var swlati;
	var swlongi;
	var nelati;
	var nelongi;
<?php } ?>
$(function () {
	initiateGooglePlaces();
	$("input[type='checkbox'], input[type='radio']").icheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
	$('#dontsendsms').on('ifChecked', function() {
		$('.sendsmsclass').val('0');
	});
	$('#dontsendsms').on('ifUnchecked', function() {
		$('.sendsmsclass').val('1');
	});
	$("#reg_num").autocomplete ({
		source: availableRegNums,
		select: function (a, b) {
			$("#reg_num").val(b.item.value);
		}
	});
	$("#sc_name").autocomplete({
		source: availableScs,
		select: function (a, b) {
			$("#sc_name").val(b.item.value);
		}
	});
	$('#user_date').datepicker({
		dateFormat: "yy-mm-dd",
		minDate: 0,
		maxDate: 45
	});
	<?php if(isset($bikecompanies)) { ?>
		$('input[name=user_bikecomp][value=<?php echo $bikecompanies[0]['BikeCompanyId']; ?>]').icheck('checked');
	<?php } ?>
	$('.user_bikecomp').on('ifChecked', function(e) {
		var bc_id = $(this).val();
		$.ajax({
			type: "GET",
			url: "/admin/orders/get_bikemodels/" + bc_id + "/1",
			dataType: "json",
			cache: false,
			success: function(data) {
				var bikelist = '<option selected style="display:none;" value="">Choose bike model</option>';
				if (data != null) {
					for (i = 0; i < data.length; i++) {
						bikelist += '<option value="' + data[i].BikeModelId + '">' + data[i].BikeModelName +'</option>';
					}
				}
				$('#user_bikemodel').html(bikelist);
			}
		});
	});
	$('#user_bikemodel').on('change', function() {
		var service = $("input[name='user_service']:checked").val();
		if((service == 1 || service == '1') && $(this).val() != '') {
			get_user_aser_block($(this).val());
		}
	});
	$('.user_service').on('ifChecked', function(e) {
		var service = $("input[name='user_service']:checked").val();
		if(service == 4 || service == '4') {
			$('#ins_ren_block').show('slow');
		} else {
			$('#ins_ren_block').hide('slow');
		}
		if((service == 1 || service == '1') && $('#user_bikemodel').val() != '') {
			get_user_aser_block($('#user_bikemodel').val());
		} else {
			$('#user_aser_block').hide('slow');
		}
		$.ajax({
			type: "POST",
			url: "/admin/orders/fetch_amenities",
			data: {service: service},
			dataType: "text",
			cache: false,
			success: function(data) {
				$('#user_amenity_block').html(data);
				$('#user_amenity_block').each(function() {
					$(this).find('input').icheck({
						checkboxClass: 'icheckbox_square-green',
						radioClass: 'iradio_square-green slotRadio'
					});
				});
				$('#user_amenity_block').show('slow');
			}
		});
	});
	$('#fetch_user_details').on('click', function() {
		var phone_num = parseInt($('#phone_num').val());
		if(phone_num >= 7000000000  && phone_num <= 9999999999 && !isNaN(phone_num)) {
			$('#ph_msg').hide('slow');
			$(this).hide('slow');
			$.ajax({
				type: "POST",
				url: "/admin/orders/fetch_user_details",
				data: {phone: phone_num},
				dataType: "json",
				success: function(data) {
					if(data !== null && data !== "") {
						$('#user_id').val(data.UserId);
						$('#user_addr_id').val(data.UserAddrId);
						$('#full_name').val(data.UserName);
						$('#email').val(data.Email);
						// $('#reg_num').val(data.RegNum);
						// $('#bike_num').val(data.BikeNumber);
						$('#adln1').val(data.AddrLine1);
						$('#adln2').val(data.AddrLine2);
						$('#location').val(data.LocationName);
						$('#landmark').val(data.Landmark);
						$('#phone_num').attr('readonly', 'readonly');
						$('#ph_msg').html('Details fetched successfully...');
						$('#ph_msg').show('slow');
					} else {
						$('#ph_msg').html('User not registered. We will register him while you place this order.');
						$('#ph_msg').show('slow');
						$('#fetch_user_details').show('slow');
					}
					$('.check_for_addr_change').on('input', function() {
						$('#user_addr_id').val('0');
					});
				}
			});
		} else {
			$('#ph_msg').html('Enter a valid phone.');
			$('#ph_msg').show('slow');
		}
	});
	$('#fetch_user_slots').on('click', function() {
		var date = $('#user_date').val();
		var service = $("input[name='user_service']:checked").val();
		if(date != "" && typeof service !== "undefined") {
			$(this).hide("slow");
			$('#slots_msg').hide('slow');
			$.ajax({
				type: "POST",
				url: "/admin/orders/get_slots",
				data: {date: date, service: service},
				dataType: "json",
				success: function(data) {
					$('#user_odate').val(data.odate);
					$('#shwRum_buk_slots').html(data.html);
					$('.slotsContainer').each(function() {
						$(this).find('input').icheck({
							checkboxClass: 'icheckbox_square-green',
							radioClass: 'iradio_square-green slotRadio'
						});
					});
					$('#fetch_user_slots').show("slow");
				}
			});
		} else {
			$('#slots_msg').html('Choose service type and order date to get slots');
			$('#slots_msg').show('slow');
		}
	});
	$('#final_submit').on('click', function(event) {
		var sel_service = $('input[name=user_service]:checked').val();
		var sel_slot = $('input[name=user_slot]').val();
		if(sel_service == "" || typeof sel_service === "undefined") {
			showMuserValidation('ser_err', 'You have to choose the service');
		}
		if(sel_slot == "" || typeof sel_slot === "undefined") {
			showMuserValidation('slot_err', 'You have to choose the time slot.');
		}
		$('form input, form select, form textarea').each(function() {
			var blah = $(this).data('mandatory');
			if($(this).data('mandatory') == true) {
				if($(this).data('type') == 'text') {
					if($(this).val() == "") {
						showMuserValidation($(this).attr('id'));
					}
				} else if($(this).data('type') == 'phone') {
					if(!isValidPhone($(this).val())) {
						showMuserValidation($(this).attr('id'));
					}
				} else if($(this).data('type') == 'email') {
					if(!IsEmail($(this).val())) {
						showMuserValidation($(this).attr('id'));
					}
				} else if($(this).data('type') == 'bikenum') {
					if(!isValidBikeNum($(this).val())) {
						showMuserValidation($(this).attr('id'));
					}
				} else if($(this).data('type') == 'location') {
					if(!isValidLocation()) {
						showMuserValidation($(this).attr('id'));
					}
				} else if($(this).data('type') == 'regnum') {
					if(!isValidRegNum($(this).val())) {
						showMuserValidation($(this).attr('id'));
					}
				}
			}
		});
	});
	$('#fetch_user_details').trigger("click");
});
var initiateGooglePlaces = function() {
	var address = (document.getElementById('location'));
	var defaultBounds = new google.maps.LatLngBounds(
		new google.maps.LatLng(swlati, swlongi),
		new google.maps.LatLng(nelati, nelongi)
	);
	var options = {
		bounds: defaultBounds,
		componentRestrictions: {country: 'IN'}
	};
	var autocomplete = new google.maps.places.Autocomplete(address, options);
	autocomplete.addListener('place_changed', function() {
		var place = autocomplete.getPlace();
		var latitude = place.geometry.location.lat();
		document.getElementById('ulatitude').value = latitude;
		var longitude = place.geometry.location.lng();
		document.getElementById('ulongitude').value = longitude;
		$('#location').val(place.name);
	});
}
showMuserValidation = function(id, message) {
	if(typeof message === "undefined") {
		message = 'Please fill valid ' + $('#' + id).data('error');
	}
	$('.error-text').remove();
	$('#' + id).parent().append('<div class="error-text">' + message + '</div>');
	$('html,body').animate({
		scrollTop: $($('#' + id)).offset().top
	}, 'slow');
	event.preventDefault();
	throw new Error('This is not an error. This is just to abort javascript');
	return false;
}
function IsEmail(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}
isValidPhone = function(phNum) {
	if (phNum == "" || phNum < 7000000000 || phNum > 9999999999 || isNaN(phNum)) {
		return false;
	}
	return true;
}
function isValidLocation() {
	var location = $('#location').val();
	var ulati = $('#ulatitude').val();
	var ulongi = $('#ulongitude').val();
	if(ulati && ulongi && location) {
		return true;
	} else {
		return false;
	}
}
function isValidRegNum(code) {
	return ($.inArray(code, availableRegNums) > -1);
}
function isValidBikeNum(bikenum) {
	var regex = /^[a-zA-Z]{0,4}\s?\d{4,5}$/;
	return regex.test(bikenum);
}
function get_user_aser_block(bmid) {
	$.ajax({
		type: "POST",
		url: "/admin/orders/fetch_asers",
		data: {bmid: bmid},
		dataType: "text",
		cache: false,
		success: function(data) {
			if(data !== null && data !== "") {
				$('#user_aser_block').html(data);
				$('#user_aser_block').each(function() {
					$(this).find('input:checkbox').icheck({
						checkboxClass: 'icheckbox_square-green'
					});
				});
				$('#user_aser_block').show('slow');
			}
		}
	});
}
</script>
</body>
</html>