var availableLocations = [];
var univ_otp_check = false;
var ph1_unique = false;
var ph2_unique = false;
$(document).on({
	ajaxStart: function() { $('.load-wrap').show();
		$('html').addClass('no-scroll'); },
	ajaxStop: function() { $('.load-wrap').hide();
		$('html').removeClass('no-scroll'); }
});
$(document).ready(function() {
	$('#vType,#vTypeL').select2({
		placeholder: "Vendor Type",
		minimumResultsForSearch: 10
	});
	$('#ecType').select2({
		placeholder: "PUC Type",
		minimumResultsForSearch: 10
	});
	$('.stime').select2({
		placeholder: "Start Time",
		minimumResultsForSearch: 10
	});
	$('.etime').select2({
		placeholder: "End Time",
		minimumResultsForSearch: 10
	});
	$('#fuelType').select2({
		placeholder: "Fuel Type",
		minimumResultsForSearch: 10
	});
	$('#company').select2({
		placeholder: "Service Provider",
		minimumResultsForSearch: 10
	});
	$('#city').select2({
		placeholder: "Choose Your City",
		minimumResultsForSearch: 10
	});
	$('#rp_sendotp').on('click', function() {
		var ph = $('#rp_phone').val();
		if(isValidPhone(ph)) {
			makeAjaxCallForForgotOTP(ph);
		} else {
			$('#rp_msg').show('slow');
			$('#rp_msg').html('Phone number is not valid.');
		}
	});
	$('#rp_otp').on('input', function() {
		var otp = $.trim($(this).val());
		var phNum = $('#rp_phone').val();
		if (otp.length == 6) {
			$.ajax({
				type: "POST",
				url: "/home/check_fgototp_vendor",
				data: {otp: otp, phNum: phNum},
				dataType: "text",
				cache: false,
				success: function(data) {
					if (data == 1) {
						univ_otp_check = true;
					} else {
						alert('The entered OTP is either expired / invalid.');
						univ_otp_check = false;
						$('#rp_otp').val('');
					}
					checkrpwd();
				}
			});
		} else if (otp.length > 6) {
			$(this).val('');
		}
	});
	$('#fgpwd_agent').on('click', function(e) {
		e.preventDefault();
		$('#agent_login').hide('slow');
		$('#agent_otp').show('slow');
	});	
	$('.timingdays').on('ifChecked', function() {
		var tmg_id = $(this).val();
		$('#stime_' + tmg_id).val('0');
		$('#stime_' + tmg_id).trigger('change');
		$('#etime_' + tmg_id).val('23.5');
		$('#etime_' + tmg_id).trigger('change');
	});
	$('.timingdays').on('ifUnchecked', function() {
		var tmg_id = $(this).val();
		$('#stime_' + tmg_id).val('');
		$('#stime_' + tmg_id).trigger('change');
		$('#etime_' + tmg_id).val('');
		$('#etime_' + tmg_id).trigger('change');
	});
	$('.mediaUpload').change(function() {
		var preview_id = $(this).attr('data-id');
		var oFReader = new FileReader();
		oFReader.readAsDataURL($('#' + this.id)[0].files[0]);
		$('#nii' + preview_id).hide();
		oFReader.onload = function(oFREvent) {
			$("#uploadPreview" + preview_id)[0].src = oFREvent.target.result;
		};
	});
	$('#lati, #longi').on('click', function() {
		// getLocation();
	});
	$('#elicdate').pickadate({
		format: 'dddd, dd mmm, yyyy',
		formatSubmit: 'dddd, dd mmm, yyyy',
	});
	$('#vType').on("select2:select", function() {
		var type = $(this).val();
		$('#contact_person_details').hide();
		$('#pb_box').hide();
		$('#pb_details').hide();
		$('#ec_box').hide();
		$('#ec_details').hide();
		$('#timings_box').hide();
		if(type == "sc") {
			enableSC();
		} else if(type == "pb") {
			enablePB();
		} else if(type == "ec") {
			enablePUC();
		} else if(type == "pt") {
			enablePuncture();
		}
	});
	$('#city').on('change', function() {
		cityChanged();
	});
	$('#submit').on('click', function(event) {
		$('form input, form select, form textarea').each(function() {
			if($(this).data('mandatory') == true ) {
				if($(this).data('type') == 'select' && $('#select2-' + $(this).attr('id') + '-container').is(':visible') && !isEmpty($(this).val())) {
					$('.error-text1').remove();
					$(this).parent().append('<div class="error-text1">Please fill valid ' + $(this).data('error') + '</div>');
					$('html,body').animate({
					scrollTop: $('#select2-' + $(this).attr('id') + '-container').offset().top - 50}, 'slow');
					event.preventDefault();
					return false;
				} else if($(this).is(':visible')) {
					if($(this).data('type') == 'name') {
						if(!isEmpty($(this).val())) {
							showValidation($(this).attr('id'));
						}
					} else if($(this).data('type') == 'phone') {
						if(!isValidPhone($(this).val())) {
							showValidation($(this).attr('id'));
						}
						if($(this).attr('id') == 'vphone') {
							showUniquePhoneValidation();
						}
					} else if($(this).data('type') == 'email') {
						if(!isValidEmail($(this).val())) {
							showValidation($(this).attr('id'));
						}
					} else if($(this).data('type') == 'int') {
						if(!isValidInt($(this).val())) {
							showValidation($(this).attr('id'));
						}
					} else if($(this).data('type') == 'pint') {
						if(!isValidPint($(this).val())) {
							showValidation($(this).attr('id'));
						}
					} else if($(this).data('type') == 'location') {
						if(!isValidLocation($(this).val())) {
							showValidation($(this).attr('id'));
						}
					} else if($(this).data('type') == 'pincode') {
						if(!isValidPincode($(this).val())) {
							showValidation($(this).attr('id'));
						}
					} else if($(this).data('type') == 'radio') {
						var cnames = $("input[name='slottype']:checked").val();
						if(typeof cnames === "undefined" || !isValidInt(cnames)) {
							showValidation('stype_valdtn', 'Choose Valid Slots Type');
						}
					}
				}
			}
		});
	});
	$('#phone, #vphone').on('input', function() {
		if($(this).val().length == 10) {
			isUniquePhone($(this).val(), $(this).attr("id"));
		}
	});
});
function checkrpwd() {
	var pwd1 = $('#rp_pwd1').val();
	var pwd2 = $('#rp_pwd2').val();
	if(univ_otp_check && pwd1 != "" && pwd2 != "" && pwd1 == pwd2) {
		$('#rp_rpwd').removeAttr('disabled');
	} else {
		$('#rp_rpwd').attr('disabled', 'disabled');
	}
}
function makeAjaxCallForForgotOTP(phNum) {
	$.ajax({
		type: "POST",
		url: "/home/send_fgototp_vendor",
		data: {phNum: phNum},
		dataType: "json",
		cache: false,
		success: function(data) {
			if (data.err) {
				$('#rp_msg').show('slow');
				$('#rp_msg').html(data.err);
			} else {
				$('#agent_otp').hide('slow');
				$('#agent_rpwd').show('slow');
				$('#hidrp_phone').val(phNum);
			}
		}
	});
}
function cityChanged() {
	var changed_city = $('#city').val();
	$.ajax({
		type: "POST",
		url: "/home/getCityLocations",
		data: {city: changed_city},
		dataType: "json",
		cache:false,
		success: function(data) {
			var temp_array;
			if(data !== null) {
				var temp_array = $.map(data, function(value, index) {
					return [value];
				});
			}
			availableLocations = temp_array;
			var location_array;
			if (typeof availableLocations === "undefined") {
				location_array = [];
			} else {
				location_array = availableLocations;
			}
			$("#area").autocomplete ({
				source: availableLocations,
				select: function (a, b) {
					$("#area").val(b.item.value);
				},
				response: function(event, ui) {
					if (ui.content.length === 0) {
						alert("No results found");
					}
				}
			});
		}
	});
}
function enableSC() {
	$("#company").select2();
	$('#company').empty();
	$("#company").removeClass('select2-offscreen').select2({
		placeholder: "Company",
		minimumResultsForSearch: 10,
		data: sc_data
	}).trigger("change");
	$('#contact_person_details').show();
	$('#ptprice').hide();
	$('#defslots').show();
	$('#slottype_block').show();
	$('#slotInterval').show();
	$('#adln1').attr('data-mandatory', true);
	$('#adln2').attr('data-mandatory', true);
	$('#email').attr('data-mandatory', true);
	$('#pincode').attr('data-mandatory', true);
}
function enablePB() {
	$("#company").select2();
	$('#company').empty();
	$("#company").removeClass('select2-offscreen').select2({
		placeholder: "Company",
		minimumResultsForSearch: 10,
		data: pb_data
	}).trigger("change");
	$('#pb_box').show();
	$('#pb_details').show();
	$('#timings_box').show();
	$('#ptprice').hide();
	$('#defslots').hide();
	$('#slottype_block').hide();
	$('#adln1').attr('data-mandatory', false);
	$('#adln2').attr('data-mandatory', false);
	$('#email').attr('data-mandatory', false);
	$('#pincode').attr('data-mandatory', false);
}
function enablePUC() {
	$("#company").select2("destroy");
	$('#ec_box').show();
	$('#ec_details').show();
	$('#timings_box').show();
	$('#ptprice').hide();
	$('#defslots').hide();
	$('#slottype_block').hide();
	$('#adln1').attr('data-mandatory', true);
	$('#adln2').attr('data-mandatory', true);
	$('#email').attr('data-mandatory', true);
	$('#pincode').attr('data-mandatory', true);
}
function enablePuncture() {
	$("#company").select2("destroy");
	$('#timings_box').show();
	$('#ptprice').show();
	$('#defslots').hide();
	$('#slottype_block').hide();
	$('#adln1').attr('data-mandatory', false);
	$('#adln2').attr('data-mandatory', false);
	$('#email').attr('data-mandatory', false);
	$('#pincode').attr('data-mandatory', false);
}
function getLocation() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition);
	} else {
		alert("Geolocation is not supported by this browser.");
	}
}
function showPosition(position) {
	$('#lati').val(Number(position.coords.latitude.toFixed(7)));
	$('#longi').val(Number(position.coords.longitude.toFixed(7)));
}
function showUniquePhoneValidation() {
	var ophone = $("#phone").val();
	var vphone = $("#vphone").val();
	if(ophone == vphone) {
		showValidation("vphone", "Owner and Contact person numbers should be different");
	} else {
		if(!ph1_unique) {
			showValidation("phone", "This number is already used and cannot be used again");
		}
		if(!ph2_unique) {
			showValidation("vphone", "This number is already used and cannot be used again");
		}
	}
}
isEmpty = function(val) {
	if(val == "" || val == undefined || val === null){
		return false;
	}
	return true;
}
isValidPhone = function(phNum) {
	if (phNum == "" || phNum < 7000000000 || phNum > 9999999999 || isNaN(phNum)) {
		return false;
	}
	return true;
}
isUniquePhone = function(phone, id) {
	$.ajax({
		type: "POST",
		url: "/home/validate_phones",
		data: {phone: phone},
		dataType: "text",
		cache: false,
		success: function(data) {
			if (data && id == "phone") {
				ph1_unique = true;
			} else if(id == "phone") {
				ph1_unique = false;
			}
			if(data && id == "vphone") {
				ph2_unique = true;
			} else if(id == "vphone") {
				ph2_unique = false;
			}
		}
	});
}
isValidEmail = function(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}
isValidInt = function(val) {
	if(parseInt(val) >= 0) {
		return true;
	}
	return false;
}
isValidPint = function(val) {
	if(val > 0) {
		return true;
	}
	return false;
}
isValidPincode = function(val) {
	if(val > 0 && val < 999999) {
		return true;
	}
	return false;
}
isValidLocation = function(code) {
	return ($.inArray(code, availableLocations) > -1);
}