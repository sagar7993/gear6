<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Profile - Place Order</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('vendor/components/_vcss'); ?>
	<style type="text/css">
		.styled-select {
			font-size: 1rem;
		}
		.form-control {
			height: 47px;
		}
	</style>
</head>
<body>
	<?php $this->load->view('vendor/components/_head'); ?>
	<?php if(isset($v_is_logged_in) && $v_is_logged_in == 1) { ?>
		<div class="wrapper row-offcanvas row-offcanvas-left">
			<?php $this->load->view('vendor/components/_sidebar'); ?>
			<aside class="right-side">
				<section class="content-header">
					<h1>
						Place Order
						<small>Place an order on behalf of a user</small>
					</h1>
				</section>
				<section class="content">
					<div class="row">
						<form class="col s12" method="POST" action="/vendor/placeorder/finalize_order">
							<div class="row">
								<div class="pickup-title teal-bold">Enter user phone number</div>
								<div id="ph_msg" style="display:none;color:crimson;">
								</div>
								<div class="input-field col s6">
									<input data-type="phone" data-mandatory="true" data-error="Phone Number" type="text" placeholder="Enter the user phone number (10 digits)" name="phone" id="phone" style="height: 2.5rem;">
									<input type="hidden" name="user_id" id="user_id">
									<input type="hidden" name="user_addr_id" id="user_addr_id" value="0">
								</div>
								<div class="input-field col s6">
									<button type="button" class="btn waves-effect waves-light" id="fetch_user_details">
										Fetch User Details
									</button>
								</div>
							</div>
							<div class="row">
								<div class="pickup-title teal-bold">Enter User Location</div>
								<div class="input-field col s6">
									<input data-type="location" data-mandatory="true" data-error="Location Name" type="text" class="form-control check_for_addr_change" name="location" id="location" placeholder="Location Name">
								</div>
								<input type="hidden" id="ulatitude" name="nulati">
								<input type="hidden" id="ulongitude" name="nulongi">
								<div class="input-field col s3" id="convenienceFee" name="convenienceFee" style="display:none;">
									<input type="text" placeholder="Convenience Fee" id="convenienceFeeText" readonly="true">
								</div>
								<div class="input-field col s3">
									<button name="getConvenienceFee" id="getConvenienceFee" class="btn waves-effect waves-light btn-flat" disabled>Get Convenience Fee</button>
								</div>
							</div>
							<div class="row">
								<div class="pickup-title teal-bold">Choose the service</div>
								<?php if(isset($services)) { foreach($services as $service) { ?>
								<div class="input-field col s3">
									<div class="checkbox" style=""><label class="bservice"><input type="radio" name="user_service" value="<?php echo $service['ServiceId']; ?>" class="user_service"><span style="margin-left:5px"><?php echo convert_to_camel_case($service['ServiceName']); ?></span></label></div>
								</div>
								<?php } } ?>
								<div class="input-field col s12">
									<div id="ser_err"></div>
								</div>
							</div>
							<div class="row">
								<div class="pickup-title teal-bold">Choose the bike company</div>
								<?php if(isset($bikecompanies)) { $count = 0; foreach($bikecompanies as $company) { ?>
								<div class="input-field col s3">
									<div class="checkbox" style=""><label class="bcompany"><input type="radio" name="user_bikecomp" value="<?php echo $company['BikeCompanyId']; ?>" class="user_bikecomp"><span style="margin-left:5px"><?php echo convert_to_camel_case($company['BikeCompanyName']); ?></span></label></div>
								</div>
								<?php $count += 1; } } ?>
							</div>
							<div class="row">
								<div class="pickup-title teal-bold">Choose the bike model</div>
								<div class="input-field col s3">
									<select data-type="text" data-mandatory="true" data-error="Bike Model" class="form-control styled-select" title="Select the Bike Model" id="user_bikemodel" name="user_bikemodel">
										<option selected style="display:none;" value="">Choose bike model</option>
										<?php if(isset($bikemodels)) { foreach($bikemodels as $bikemodel) { ?>
										<option value="<?php echo $bikemodel['BikeModelId']; ?>"><?php echo convert_to_camel_case($bikemodel['BikeModelName']); ?></option>
										<?php } } ?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="pickup-title teal-bold">Choose service date</div>
								<div id="slots_msg" style="display:none;color:crimson;">
								</div>
								<div class="input-field col s3">
									<input data-type="text" data-mandatory="true" data-error="Order Date" type="text" class="form-control dpDate2" readonly='true' style="cursor:pointer; height:2.5rem;" name="user_date" id="user_date" placeholder="Choose service date">
									<input type="hidden" name="user_odate" id="user_odate">
								</div>
								<div class="input-field col s6">
									<button type="button" class="btn waves-effect waves-light" id="fetch_user_slots">
										Fetch Slots
									</button>
								</div>
								<div class="input-field col s12">
									<div id="slot_err"></div>
								</div>
								<div id="shwRum_buk_slots" class="col s12 slotsContainer"></div>
							</div>
							<div class="row" id="user_amenity_block" style="display:none;">
							</div>
							<div class="row" id="user_aser_block" style="display:none;">
							</div>
							<div class="row" id="ins_ren_block" style="display:none;">
								<div class="pickup-title teal-bold">Enter Insurance Details</div>
								<div class="input-field col s4 margin-bottom-20px">
									<select class="form-control styled-select" data-error="Registration year" id="regYear" name="regYear">
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
								<div class="input-field col s4 margin-bottom-20px" style="">
									<select class="form-control styled-select" data-error="Expiry Date" id="expDate" name="expDate">
										<option style="display:none;" selected>Choose Expiry Date</option>
										<option value="10">Next 10 Days</option>
										<option value="30">Next 30 Days</option>
										<option value="60">More than 60 Days</option>
										<option value="0">Already Expired</option>
									</select>
								</div>
								<div class="input-field col s4 margin-bottom-20px" style="">
									<select class="form-control styled-select"  data-error="Previous Insurer" id="prevIns" name="prevIns">
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
								<div class="input-field col s4">
									<input data-type="text" data-mandatory="true" data-error="Full Name" type="text" class="form-control" name="full_name" id="full_name" placeholder="Enter User Full Name">
								</div>
								<div class="input-field col s4">
									<input data-type="email" data-mandatory="true" data-error="Email" type="text" class="form-control" name="email" id="email" placeholder="Enter User Email">
								</div>
								<div class="input-field col s4">
									<input data-type="text" data-mandatory="true" data-error="Bike Reg No." type="text" class="form-control" name="reg_num" id="reg_num" placeholder="Bike Reg No.">
								</div>
								<input type="hidden" name="bike_num" value="">
								<div class="col s8">
									<div class="input-field col s6">
										<input data-type="text" data-mandatory="true" data-error="Flat / House No." type="text" class="form-control check_for_addr_change" name="adln1" id="adln1" placeholder="Flat/ House No.">
									</div>
									<div class="input-field col s6">
										<input data-type="text" data-mandatory="true" data-error="Street Name" type="text" class="form-control check_for_addr_change" name="adln2" id="adln2" placeholder="Street Name">
									</div>
									<div class="input-field col s6">
										<input type="text" class="form-control check_for_addr_change" name="landmark" id="landmark" placeholder="Landmark (Optional)">
									</div>
								</div>
								<div class="col s4">
									<div class="input-field col s12">
										<textarea class="form-control text-area" name="comments" id="comments" placeholder="Service Comments"></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="center-align">
									<button type="submit" class="btn waves-effect waves-light" id="final_submit">
										Place Order
									</button>
								</div>
							</div>
						</form>
					</div>
				</section>
			</aside>
		</div>
	<?php $this->load->view('vendor/components/_foot'); ?>
	<?php } ?>
<?php $this->load->view('vendor/components/_vjs'); ?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCZ126reFV784ZQTqw_JfD08mnS0jI7nWo&libraries=places"></script>
<script type="text/javascript">
var sclatitude = <?php if(isset($SCLatitude)) { echo $SCLatitude; } else { echo 'null'; } ?>;
var sclongitude = <?php if(isset($SCLongitude)) { echo $SCLongitude; } else { echo 'null'; } ?>;
var g_serid;
<?php if(isset($city_row)) { ?>
	var swlati = <?php echo $city_row->SwLati; ?>;
	var swlongi = <?php echo $city_row->SwLongi; ?>;
	var nelati = <?php echo $city_row->NeLati; ?>;
	var nelongi = <?php echo $city_row->NeLongi; ?>;
<?php } ?>
$(function () {
	initiateGooglePlaces();
	$("input[name='user_service']").on('ifChecked', function() {
		g_serid = parseInt($(this).val());
	});
	$('#user_date').pickadate({
		min: true,
		max: 45,
		format: 'dddd, dd mmm, yyyy',
		closeOnSelect: true,
		container: 'body',
		onOpen: function() {
			$('#user_date').val('');	
		},
		onSet: function() {
			if($('#user_date').val() != "" ) {
				$(this).close();
			}
		}
	});
	<?php if(isset($bikecompanies)) { ?>
		$('input[name=user_bikecomp][value=<?php echo $bikecompanies[0]['BikeCompanyId']; ?>]').icheck('checked');
	<?php } ?>
	$('.user_bikecomp').on('ifChecked', function(e) {
		var bc_id = $(this).val();
		$.ajax({
			type: "GET",
			url: "/vendor/placeorder/get_bikemodels/" + bc_id + "/1",
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
			url: "/vendor/placeorder/fetch_amenities",
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
		var phone = Number($('#phone').val());
		if(phone != "" && phone >= 7000000000  && phone <= 9999999999 && !isNaN(phone)) {
			$('#ph_msg').hide('slow');
			$(this).hide('slow');
			$.ajax({
				type: "POST",
				url: "/vendor/placeorder/fetch_user_details",
				data: {phone: phone},
				dataType: "json",
				success: function(data) {
					if(data !== null && data !== "") {
						$('#user_id').val(data.UserId);
						$('#user_addr_id').val(data.UserAddrId);
						$('#full_name').val(data.UserName);
						$('#email').val(data.Email);
						$('#reg_num').val(data.RegNum + ' ' + data.BikeNumber);
						$('#adln1').val(data.AddrLine1);
						$('#adln2').val(data.AddrLine2);
						$('#location').val(data.LocationName);
						$('#landmark').val(data.Landmark);
						$('#phone').attr('readonly', 'readonly');
						$('#ph_msg').html('Details fetched successfully...');
						$('#ph_msg').show('slow');
						$('#location').focus();
						validation();
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
				url: "/vendor/placeorder/get_slots",
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
				}
			}
		});
	});
});
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
	phNum = Number(phNum);
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
		url: "/vendor/placeorder/fetch_asers",
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
$('#location').on('input change', function() {
	document.getElementById('ulatitude').value = "";
	document.getElementById('ulongitude').value = "";
	validation();
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
		validation();
	});
}
$('#getConvenienceFee').on('click', function(e) {
	e.preventDefault();
	if(g_serid == undefined) {
		alert('Choose a Service Type');
	} else {
		getDistance();
	}
});
function validation() {
	var lat = document.getElementById('ulatitude').value.trim();
	var lng = document.getElementById('ulongitude').value.trim();
	if(lat == "" || lat == null || lat == undefined || lat.length == 0 || lng == "" || lng == null || lng == undefined || lng.length == 0) {
		$("#getConvenienceFee").attr('disabled','disabled');
	} else {
		$("#getConvenienceFee").removeAttr('disabled');
	}
}
function getConvenienceFee(distance) {
	var kms = distance; var mins = 0;
	var total_cf = 0; var basecharge = 0;
	if(kms != '' && kms > 0) {
		if(mins != '' && mins > 0) {
			basecharge = 200;
		} else {
			if(g_serid == 1 || g_serid == 2) {
				basecharge += 75.00;
			} else if (g_serid == 4) {
				basecharge += 50.00;
			}
		}
		total_cf += basecharge;
		var loop_check = true;
		if(mins != '' && mins > 0) {
			while(loop_check) {
				if(kms <= 5 && mins <= 60) {
					var cf = 2 * total_cf.toFixed(2);
					$('#convenienceFeeText').val("Rs. " + (cf * 0.85).toFixed(2) + " - " + (cf * 1.15).toFixed(2));
					loop_check = false;
				}
				if(kms > 5) {
					total_cf += (kms - 5) * 20;
					kms = 5;
				}
				if(mins > 60) {
					total_cf += (mins - 60) * 2;
					mins = 60;
				}
			}
		} else {
			while(loop_check) {
				if(kms <= 3) {
					var cf = 2 * total_cf.toFixed(2);
					if(cf >= 350) {
						$('#convenienceFeeText').val("INR 350");
					} else {
						$('#convenienceFeeText').val("INR " + (cf * 0.85).toFixed(2) + " - " + (cf * 1.15).toFixed(2));
					}
					loop_check = false;
				} else if(kms > 3 && kms <= 12) {
					if(g_serid == 2) {
						total_cf += (kms - 3) * 6;
					} else {
						total_cf += (kms - 3) * 5;
					}						
					kms = 2;
				} else if(kms > 12 && kms <= 20) {
					if(g_serid == 2) {
						total_cf += (kms - 12) * 10;
					} else {
						total_cf += (kms - 12) * 7.5;
					}
					kms = 12;
				} else if(kms > 20 && kms <= 25) {
					if(g_serid == 2) {
						total_cf += (kms - 20) * 13;
					} else {
						total_cf += (kms - 20) * 10;
					}
					kms = 20;
				} else if(kms > 25 && kms <= 30) {
					if(g_serid == 2) {
						total_cf += (kms - 25) * 15;
					} else {
						total_cf += (kms - 25) * 13;
					}
					kms = 25;
				} else if(kms > 30) {
					if(g_serid == 2) {
						total_cf += (kms - 30) * 17;
					} else {
						total_cf += (kms - 30) * 15;
					}
					kms = 30;
				}
			}
		}
	} else {
		$('#convenienceFeeText').val('Rs. 0');
	}
}
function getDistance() {
	var origin = {lat: Number(document.getElementById('ulatitude').value), lng: Number(document.getElementById('ulongitude').value)};
	var destination = {lat: sclatitude, lng: sclongitude}; var geocoder = new google.maps.Geocoder;
	var service = new google.maps.DistanceMatrixService;
	service.getDistanceMatrix({
		origins: [origin], destinations: [destination], travelMode: google.maps.TravelMode.DRIVING,
		unitSystem: google.maps.UnitSystem.METRIC, avoidHighways: false, avoidTolls: false
	}, function(response, status) {
		if (status !== google.maps.DistanceMatrixStatus.OK) {
			alert('Error was: ' + status);
		} else {
			var originList = response.originAddresses; var destinationList = response.destinationAddresses;
			var distance = response['rows'][0]['elements'][0]['distance']['text'];
			var duration = response['rows'][0]['elements'][0]['duration']['text'];
			distance = Number(distance.substring(0, distance.indexOf(" ")));
			duration = Number(duration.substring(0, duration.indexOf(" ")));
			document.getElementById('convenienceFee').style.display = "block";		
			getConvenienceFee(distance);
		}
	});
}
</script>
</body>
</html>