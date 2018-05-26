$flag = 0;
var is_query_modified = false;
$(function() {
	checkFLoatDiv();
	initiateGooglePlaces();
	$("input[type='checkbox'], input[type='radio']").icheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
	$('#city').on('change', function() {
		var city = this.value;
		setCity(city);
	});
	$(window).load(function() {
		$('.load-wrap').hide();
		$('html').removeClass('no-scroll');
	});
	$('#company').select2({
		placeholder: "Bike Company",
		minimumResultsForSearch: 12
	});
	$('#bikediv').select2({
		placeholder: "Bike Model",
		minimumResultsForSearch: 10
	});
	$('#stype').select2({
		placeholder: "Service Type",
		minimumResultsForSearch: 10
	});
	$('#ulogin').on('click', function() {
		$('#login_modal').addClass("show");
	});
	$('#topsearch-bang').on('click', function(e) {
		e.preventDefault();
		var city = 1;
		setCity(city);
	});
	$('#topsearch-hyd').on('click', function(e) {
		e.preventDefault();
		var city = 3;
		setCity(city);
	});
	$('#topsearch-pune').on('click', function(e) {
		e.preventDefault();
		var city = 2;
		setCity(city);
	});
	$('.company-search').click(function() {
		$cVal = $(this).text();
		$cId = $("#company option").filter(function() {
			return this.text == $cVal; 
		}).val();
		$("#company").select2('val', $cId);
		bikelist($('#company').val());
		$('html, body').animate({scrollTop: $(".search-box").offset().top}, 500);
	});
	$(window).scroll(function() {
		toggleGoTop();
	});
	$('#goTop').click(function() {
		$('html, body').animate({scrollTop: $('body').offset().top}, 500);
	});
});
function setCity(city) {
	if (city != "") {
		var created_form = $('<form action="/user/userhome/citySelect" method="POST"><input type="hidden" id="city_selected" name="city" value="' + city + '" /></form>').appendTo('body');
		created_form.submit();
	}
}
var bypass = false;
$('#stype').on('change', function() {
	var stype = $('#stype').val();
	if(stype == '9' || stype == '10' || stype == '11') {
		$('.lowerSection').hide('slow');
		bypass = true;
	} else if(stype == '3' || stype == '7') {
		$('.lowerSection').show('slow');
		disableDate();
		bypass = false;
	} else {
		$('.lowerSection').show('slow');
		enableDate();
		bypass = false;
	}
});
$('#submit').on('click',function() {
	var e1 = $("#area");
	var e2 = $("#stype");
	var e3 = $("#company");
	var e4 = $("#bikediv");
	var str1 = new Array();
	var str2 = ['#area','#stype','#company','#bikediv','#datepicker', '#area', '#area'];
	str1[0]  = e1.val();
	str1[1]  = e2.val();
	str1[2]  = e3.val();
	str1[3]  = e4.val();
	str1[4]  = $("#datepicker").val();
	str1[5]  = $("#ulatitude").val();
	str1[6]  = $("#ulongitude").val();
	var x = 0;
	var eid = 0;
	$('.error-text').remove();
	if(bypass == true) {
		str1[2] = '1';
		str1[3] = '1';
		str1[4] = '1';
	}
	for (i = 0; i < str1.length; i++) {
		if (str1[i] == "" || str1[i] == undefined || str1[i] === null) {
			x = 1;
			eid = i;
			break;
		}
	}
	if (x == 0) {
		if(!is_query_modified) {
			return true;
		} else {
			is_query_modified = false;
		}
	} else {
		$(str2[eid]).parent().append('<div class="error-text">Please select valid '+$(str2[eid]).data('error')+'</div>');
		return false;
	}
});
function bikelist(company) {
	if (company != "") {
		$.ajax({
			type: "POST",
			url: "/user/userhome/bikeList",
			data: {company: company},
			dataType: "json",
			cache:false,
			success: function(data) {
				window.scrollTo(0, 0);
				var bikelist = '<option></option>';
				if (data != null) {
					for (i = 0; i < data.length; i++) {
						bikelist += '<option value="' + data[i].BikeModelId + '">' + data[i].BikeModelName +'</option>';
					}
				}
				$('#bikediv').html(bikelist);
				if (is_query_modified && $.cookie('model') != '' && $.cookie('model') !== null) {
					$('#bikediv').select2('val', $.cookie('model'));
				} else {
					$('#bikediv').select2('val', '');
				}
			}
		});
	}
	return false;
}
function isValidLocation(code) {
	return ($.inArray(code, availableLocations) > -1);
}
function disableDate() {
	$('#datepicker').addClass('grey-bg');
	$('#datepicker').addClass('dpDate2');
	$('#datepicker').val($.datepicker.formatDate('DD, d MM, yy', new Date()));
	$('#datepicker_query').val($.datepicker.formatDate('DD, d MM, yy', new Date()));
	$( "#datepicker" ).datepicker( "disable");
	$flag = 1;
	return;
}
function enableDate() {
	$("#datepicker").datepicker("enable");
	$('#datepicker').removeClass('grey-bg');
	$('#datepicker').removeClass('dpDate2');
	if($flag == 1) {
		$('#datepicker').val('');
		$flag = 0;
	}
	return;
}
function enableDate1() {
	$("#datepicker").datepicker("enable");
	$('#datepicker').removeClass('grey-bg');
	if($flag == 1) {
		$('#datepicker').val('');
		$flag = 0;
	}
	return;
}
function toggleGoTop() {
	$wTop = $(window).scrollTop();
	$anchor = $('.logo-header');
	if($anchor.length > 0){
		$oTop = $anchor.offset().top;
		if($wTop > $oTop) {
			$('#goTop').addClass('go-top-fixed');
		} else {
			if($('#goTop').hasClass('go-top-fixed')) {
				$('#goTop').removeClass('go-top-fixed');
			}
		}
	}
}
checkFLoatDiv = function() {
	$('.fadeInBlock').each( function(i) {
	$winTop = $(window).scrollTop();
		$objTop = '100';
		if($winTop > $objTop) {
			$(this).css('opacity','1');
			$(this).addClass('float-in');
			$('.fixed-action-btn').addClass('no-display');
		} else if(($objTop - $winTop) > 50) {
			$(this).css('opacity','0');
			$('.fixed-action-btn').removeClass('no-display');
			$(this).removeClass('float-in');
		}
	}); 
}
initiateGooglePlaces = function() {
	$('#area').on('input', function() {
		$('#ulatitude').val('');
		$('#ulongitude').val('');
	});
	var address = (document.getElementById('area'));
	var defaultBounds = new google.maps.LatLngBounds(
		new google.maps.LatLng(swlati, swlongi),
		new google.maps.LatLng(nelati, nelongi)
	);
	var options = {
		bounds: defaultBounds,
		componentRestrictions: {country: 'in'}
	};
	var autocomplete = new google.maps.places.Autocomplete(address, options);
	autocomplete.addListener('place_changed', function() {
		var place = autocomplete.getPlace();
		var latitude = place.geometry.location.lat();
		document.getElementById('ulatitude').value = latitude
		var longitude = place.geometry.location.lng();
		document.getElementById('ulongitude').value = longitude
		$('#area').val(place.name);
	});
}
function getCityNameFromPlace(place) {
	var cities = [];
	cities["Bangalore"] = "Bangalore";
	cities["Bengaluru"] = "Bangalore";
	cities["Chennai"] = "Chennai";
	cities["Madras"] = "Chennai";
	cities["Pune"] = "Pune";
	cities["Hyderabad"] = "Hyderabad";
	cities["Mumbai Suburban"] = "Mumbai";
	cities["Mumbai"] = "Mumbai";
	cities["Bombay"] = "Mumbai";
	cities["Delhi"] = "New Delhi";
	cities["New Delhi"] = "New Delhi";
	cities["Gurgaon"] = "Gurgaon";
	cities["Kolkata"] = "Kolkata";
	cities["Calcutta"] = "Kolkata";
	var addrlength = place.address_components.length;
	for(var i = 0; i < addrlength; i++) {
		if(cities[place.address_components[i].long_name]) {
			return cities[place.address_components[i].long_name];
		}
		if(cities[place.address_components[i].short_name]) {
			return cities[place.address_components[i].short_name];
		}
	}
}