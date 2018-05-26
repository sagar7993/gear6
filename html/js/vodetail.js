$(document).ready(function() {
	enablePriceAdditions();
	enableStatusChanges();
	$('#desc_txt_edit').on('click', function() {
		$('#desc_txt').attr('readonly', false);
		$('#desc_txt').removeClass('grey-bg');
		$('#desc_txt').focus();
	});
	$('.edit_oprice').on('click', function() {
		var opid = $(this).data("id");
		var opdesc = $(this).data("opdesc");
		var oprice = $(this).data("oprice");
		var ptype = $(this).data("type");
		$('#op_opid').val(opid);
		$('#op_opdesc').val(opdesc);
		$('#op_oprice').val(oprice);
		$('#p_ptype').val(ptype);
		$('#oprice_edit_block').show('slow');
		$('html, body').animate({
			scrollTop: $($('#oprice_edit_block')).offset().top
		}, 'slow');
	});
	$('.edit_aprice').on('click', function() {
		var apid = $(this).data("id");
		var apdesc = $(this).data("apdesc");
		var aprice = $(this).data("aprice");
		var ptype = $(this).data("type");
		var ttype = $(this).data("ttype");
		$('#op_opid').val(apid);
		$('#op_opdesc').val(apdesc);
		$('#op_oprice').val(aprice);
		$('#p_ptype').val(ptype);
		$('#p_ttype').val(ttype);
		$('#oprice_edit_block').show('slow');
		$('html, body').animate({
			scrollTop: $($('#oprice_edit_block')).offset().top
		}, 'slow');
	});
	$(document).on('click', '#price_add', function() {
		$count = 1;
		$('.spdetail').each(function() {
			$count = $count + 1;
		});
		$('.price-list-container').append('<div class="col-xs-12 price-container"><div class="form-group col-xs-4 width50"><input type="text" class="form-control spdetail" oninput="checkPriceDetails();" name="" id="spdetail'+$count+'" placeholder="Service Detail"></div><div class="form-group col-xs-4 width25"><input type="text" class="form-control" oninput="checkPriceDetails();" name="" id="sp'+$count+'" placeholder="Price Detail - eg. 350"></div>\
			<div class="form-group col-xs-4 width25">\
				<select class="form-control spttype" name="" id="spttype'+$count+'">\
					<option value="0">No Tax</option>\
					<option value="1">Service Tax</option>\
					<option value="2">VAT</option>\
					<option value="3">Discount</option>\
				</select>\
			</div>\
		<div class="col-xs-2 field-area-add" id="price_add"></div></div>');
		$(this).remove();
	});
	$('#priceupdate').on('click', function() {
		var spdetails = new Array();
		var sps = new Array();
		var spttypes = new Array();
		$.each($('.price-container'), function(index, value) {
			var spdetail = $('#spdetail' + (index + 1)).val();
			var sp = $('#sp' + (index + 1)).val();
			var spttype = $('#spttype' + (index + 1)).val();
			if (spdetail != "" && sp != "" && spttype != "") {
				spdetails.push(spdetail);
				sps.push(sp);
				spttypes.push(spttype);
			}
		});
		spdetails = spdetails.join('||');
		sps = sps.join('||');
		spttypes = spttypes.join('||');
		var form = '<form method="POST" action="/vendor/odetail/updatePrices/"><input name="oid" value="' + univ_order_id + '" /><input name="spdetails" value="' + spdetails + '" /><input name="sps" value="' + sps + '" /><input name="spttypes" value="' + spttypes + '" /><input type="submit" name="priceupdate" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
	function enablePriceAdditions() {
		if(check_status_for_price == '4' || check_status_for_price == '10' || check_status_for_price == '25') {
			$('.price-list-container').show();
			$('#price-list-submit').show();
		}
	}
	function enableStatusChanges() {
		var stypeval = $('#stype-vendor').val();
		if(stypeval == '4' || stypeval == '10' || stypeval == '16' || stypeval == '25') {
			$('.desc_txt_optional').hide();
			$('.desc_txt_mandatory').show();
			if(stypeval != '16') {
				$('#desc_txt_mandatory').val($('#desc_txt_mandatory').attr('placeholder'));
			}
			checkfield(true);
		} else {
			$('.desc_txt_optional').show();
			$('.desc_txt_mandatory').hide();
			$('#desc_txt_mandatory').val("");
			checkfield(false);
		}
	}
});
function checkfield(iscompul) {
	var str1 = new Array();
	str1[0]  = $('#stype-vendor').val();
	if(iscompul === true) {
		str1[1] = $('#desc_txt_mandatory').val();
	}
	var x = 0;
	for (i = 0; i < str1.length; i++) {
		if (str1[i] == "" || str1[i] == undefined || str1[i] == null) {
			x = 1;
		}
	}
	if (x == 0) {
		$("#changeStatus").removeAttr('disabled');
	} else {
		$("#changeStatus").attr('disabled', true);
	}
}
function checkPriceDetails() {
	var check = true;
	var isonefieldfull = false;
	var ispricevalid = true;
	$('.price-container').each(function(index) {
		var spdetail = $('#spdetail' + (index + 1)).val();
		var sp = $('#sp' + (index + 1)).val();
		if (spdetail != "" && sp != "") {
			isonefieldfull = true;
		}
		if (isNaN(sp)) {
			ispricevalid = false;
		}
		if((spdetail != "" && sp == "") || (spdetail == "" && sp != "")) {
			check = false;
		}
	});
	if (check && isonefieldfull && ispricevalid) {
		$("#priceupdate").removeAttr('disabled');
	} else {
		$("#priceupdate").attr('disabled', true);
	}
}
$(function () {
	initiateGooglePlaces();
});
$('#searchLocation').on('input change', function() {
	document.getElementById('ulatitude').value = "";
	document.getElementById('ulongitude').value = "";
	validation();
});
var initiateGooglePlaces = function() {
	var address = (document.getElementById('searchLocation'));
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
		$('#searchLocation').val(place.name);
		validation();
	});
}
$('#getConvenienceFee').on('click', function() {
	getDistance();
});
function validation() {
	var lat = document.getElementById('ulatitude').value.trim();
	var lng = document.getElementById('ulongitude').value.trim();
	if(lat== "" || lat == null || lat == undefined || lat.length == 0 || lng == "" || lng == null || lng == undefined || lng.length == 0) {
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
				basecharge += 0.00;
			}
		}
		total_cf += basecharge;
		var loop_check = true;
		if(mins != '' && mins > 0) {
			while(loop_check) {
				if(kms <= 2 && mins <= 60) {
					var cf = total_cf.toFixed(2);
					$('#convenienceFeeText').val("INR " + (cf * 0.85).toFixed(2) + " - " + (cf * 1.15).toFixed(2));
					loop_check = false;
				}
				if(kms > 2) {
					total_cf += (kms - 2) * 20;
					kms = 2;
				}
				if(mins > 60) {
					total_cf += (mins - 60) * 2;
					mins = 60;
				}
			}
		} else {
			while(loop_check) {
				if(kms <= 3) {
					if(g_serid == 4) {
						total_cf = 150.00;
						var cf = total_cf.toFixed(2);
					} else {
						var cf = total_cf.toFixed(2);
					}
					if(cf >= 350) {
						$('#convenienceFeeText').val('INR 350.00');
					} else {
						$('#convenienceFeeText').val("INR " + (cf * 0.85).toFixed(2) + " - " + (cf * 1.15).toFixed(2));
					}
					loop_check = false;
				} else if(kms > 3 && kms <= 12) {
					if(g_serid == 1 || g_serid == 2) {
						total_cf += (kms - 3) * 5;
					}
					kms = 3;
				} else if(kms > 12 && kms <= 20) {
					if(g_serid == 1 || g_serid == 2) {
						total_cf += (kms - 12) * 7.5;
					}
					kms = 12;
				} else if(kms > 20 && kms <= 25) {
					if(g_serid == 1 || g_serid == 2) {
						total_cf += (kms - 20) * 10;
					}
					kms = 20;
				} else if(kms > 25 && kms <= 30) {
					if(g_serid == 1 || g_serid == 2) {
						total_cf += (kms - 25) * 13;
					}
					kms = 25;
				} else if(kms > 30) {
					if(g_serid == 1 || g_serid == 2) {
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