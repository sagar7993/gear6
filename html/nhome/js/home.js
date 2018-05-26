function placeEmgOrder(isOTP) {
	if(isOTP) {
		var e = sendAccidentOTP(!1);
		var a = document.getElementById("accidentOTP").value;
	} else {
		var a = 0;
		var t = parseInt(document.getElementById("accidentPhoneNumber").value),
			o = document.getElementById("accidentEmail").value,
			n = document.getElementById("accidentText").value;
		if(!IsEmail(o)) {
			sweetAlert("Error", "Please enter a valid Email", "warning"); var e1 = false;
		} else { var e1 = true; }
		if(7e9 > t || t > 9999999999 || isNaN(t)) {
			sweetAlert("Error", "Please enter a valid Mobile Number", "warning"); var e2 = false;
		} else { var e2 = true; }
		if(null == n || "" == n || undefined == n || 0 == n.length) {
			sweetAlert("Error", "Please provide some additional details", "warning"); var e3 = false;
		} else { var e3 = true; }
		if(e1 && e2 && e3) { var e = true; } else { var e = false; }
	}
	if (e) {
		var t = parseInt(document.getElementById("accidentPhoneNumber").value),
			o = document.getElementById("accidentEmail").value,
			n = document.getElementById("accidentText").value,
			l = document.getElementById("searchLocation").value,
			c = document.getElementById("ulatitude").value,
			r = document.getElementById("ulongitude").value,
			s = {
				date_: $scope.oDate,
				date_query: $scope.oDate,
				area: l,
				qlati: c,
				qlongi: r,
				company: $scope.currentBikeCompany,
				model: $scope.currentBikeModelId,
				servicetype: $scope.serviceId,
				accphone: t,
				accemail: o,
				acctext: n,
				accotp: a
			};
		$.ajax({
			url: GEAR6_API + "/placeEmgReq",
			type: "POST",
			data: s,
			success: function(e) {
				"Failed" === e && sweetAlert("Error", "Incorrect OTP. Please enter the correct OTP received on your phone.", "warning"), "Success" === e && swal({
					title: "Success",
					text: "Your order has been placed. Our representatives will get in touch with you shortly.",
					type: "success",
					showCancelButton: !1,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Ok",
					closeOnConfirm: !1,
					animation: "slide-from-top"
				}, function() {
					window.location = "https://www.gear6.in";
				})
			}
		})
	}
}
function placePunctureOrder(isOTP) {
	if(isOTP) {
		var e = sendPunctureOTP(!1);
		var l = document.getElementById("punctureOTP").value;
	} else {
		var l = 0;
		var t = parseInt(document.getElementById("puncturePhoneNumber").value),
			o = document.getElementById("punctureEmail").value,
			n = document.getElementById("punctureText").value,
			a = $("input[name='pttype']:checked").val(),
			x = $("input[name='pttyre']:checked").val();
		if(!IsEmail(o)) {
			sweetAlert("Error", "Please enter a valid Email", "warning"); var e1 = false;
		} else { var e1 = true; }
		if(7e9 > t || t > 9999999999 || isNaN(t)) {
			sweetAlert("Error", "Please enter a valid Mobile Number", "warning"); var e2 = false;
		} else { var e2 = true; }
		if(null == a || undefined == a || "" == a) {
			sweetAlert("Error", "Choose a valid tyre type", "warning"); var e3 = false;
		} else { var e3 = true; }
		if(null == x || undefined == x || "" == x) {
			sweetAlert("Error", "Choose the tyre that is punctured.", "warning"); var e4 = false;
		} else { var e4 = true; }
		if(null == n || void 0 == n || 0 == n.length) {
			sweetAlert("Error", "Please provide some additional details", "warning"); var e5 = false;
		} else { var e5 = true; }
		if(e1 && e2 && e3 && e4 && e5) { var e = true; } else { var e = false; }
	}
	if (e) {
		var t = parseInt(document.getElementById("puncturePhoneNumber").value),
			o = document.getElementById("punctureEmail").value,
			n = document.getElementById("punctureText").value,
			x = $("input[name='pttype']:checked").val(),
			a = $("input[name='pttyre']:checked").val(),
			c = document.getElementById("searchLocation").value,
			r = document.getElementById("ulatitude").value,
			s = document.getElementById("ulongitude").value;
		var i = {
			date_: $scope.oDate,
			date_query: $scope.oDate,
			area: c,
			qlati: r,
			qlongi: s,
			company: $scope.currentBikeCompany,
			model: $scope.currentBikeModelId,
			servicetype: $scope.serviceId,
			ptphone: t,
			ptemail: o,
			pttext: n,
			pttype: x,
			pttyre: a,
			ptotp: l
		};
		$.ajax({
			url: GEAR6_API + "/placePtReq",
			type: "POST",
			data: i,
			success: function(e) {
				"Failed" === e && sweetAlert("Error", "Incorrect OTP. Please enter the correct OTP received on your phone.", "warning"), "Success" === e && swal({
					title: "Success",
					text: "Your order has been placed. Our representatives will get in touch with you shortly.",
					type: "success",
					showCancelButton: !1,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Ok",
					closeOnConfirm: !1,
					animation: "slide-from-top"
				}, function() {
					var form = '<form method="POST" action="/user/book">';
					form += '<input type="hidden" name="area" value="' + c + '" />';
					form += '<input type="hidden" name="qlati" value="' + r + '" />';
					form += '<input type="hidden" name="qlongi" value="' + s + '" />';
					form += '<input type="hidden" name="servicetype" value="' + $scope.serviceId + '" />';
					form += '<input type="hidden" name="date_" value="' + $scope.oDate + '" />';
					form += '<input type="hidden" name="date_query" value="' + $scope.oDate + '" />';
					form += '<input type="hidden" name="company" value="' + $scope.currentBikeCompany + '" />';
					form += '<input type="hidden" name="model" value="' + $scope.currentBikeModelId + '" />';
					form += '<input type="hidden" name="book" value="book" />';
					form += '<input type="submit" name="book_submit" value="book_submit" /></form>';
					var created_form = $(form).appendTo('body'); created_form.submit();
				})
			}
		});
	}
}
var GEAR6_API = '/user/userhome';
$scope = {};
document.getElementById("loading").style.display = "block";
$(window).load(function() {
	$scope.scrollCount = 0;
	document.getElementById("scrollPrev").style.display = "none";
	$scope.citySelected = '1'; $scope.bikeModels = [];
	selectCity();
	loopOffers();
	initiateGooglePlaces();
});
scrollNext = function() {
	switch($scope.scrollCount) {
		case 0:
		{
			$scope.scrollCount++; scrollToDiv("#howitworks"); break;
		}
		case 1:
		{
			$scope.scrollCount++; scrollToDiv("#whyyouneed"); break;
		}
		case 2:
		{
			$scope.scrollCount++; scrollToDiv("#whatuserssay"); break;
		}
		case 3:
		{
			$scope.scrollCount++; scrollToDiv("#associations"); break;
		}
		case 4:
		{
			$scope.scrollCount = 0; scrollToDiv("#bookyourservice"); break;
		}
	}
	updateButtons();
};
scrollPrev = function() {
	switch($scope.scrollCount)
	{
		case 0:
		{
			$scope.scrollCount = 4; scrollToDiv("#associations"); break;
		}
		case 1:
		{
			$scope.scrollCount--; scrollToDiv("#bookyourservice"); break;
		}
		case 2:
		{
			$scope.scrollCount--; scrollToDiv("#howitworks"); break;
		}
		case 3:
		{
			$scope.scrollCount--; scrollToDiv("#whyyouneed"); break;
		}
		case 4:
		{
			$scope.scrollCount--; scrollToDiv("#whatuserssay"); break;
		}
	}
	updateButtons();
};
scrollToDiv = function(id) {
	$('html, body').animate({
		scrollTop: $(id).offset().top - 50
	}, 1000);
};
updateButtons = function() {
	if($scope.scrollCount == 0)
	{
		document.getElementById("scrollPrev").style.display = "none";
		document.getElementById("scrollNext").style.display = "block";
	}
	else if($scope.scrollCount == 4)
	{
		document.getElementById("scrollNext").style.display = "none";
		document.getElementById("scrollPrev").style.display = "block";
	}
	else
	{
		document.getElementById("scrollNext").style.display = "block";
		document.getElementById("scrollPrev").style.display = "block";
	}
};
hideSlider = function() {};
selectCity = function() {
	document.getElementById("bike-brands-box").style.display = "none";
	$scope.city = $scope.citySelected;
	$.ajax({
		url: GEAR6_API + '/fetch_services',
		type: 'GET',
		success: function(data)
		{
			$scope.services = data.services;
			populateServicesContainer($scope.services);
		}
	});
};
loopOffers = function() {
	setInterval(function() {
		$('.carousel').carousel('next');
	}, 5000);
};
initiateGooglePlaces = function() {
	var swlati = 12.730357;
	var swlongi = 77.359706;
	var nelati = 13.169339;
	var nelongi = 77.889796;
	$('#searchLocation').on('input', function() {
		$('#ulatitude').val('');
		$('#ulongitude').val('');
	});
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
	autocomplete.addListener('place_changed', function()
	{
		var place = autocomplete.getPlace();
		var latitude = place.geometry.location.lat();
		document.getElementById('ulatitude').value = latitude;
		var longitude = place.geometry.location.lng();
		document.getElementById('ulongitude').value = longitude;
		$('#searchLocation').val(place.name);
	});
};
populateServicesContainer = function(services) {
	$('.slick-slider').slick('unslick');
	$("#services-box-container").html("");
	$("#slick-slider-container").html("");
	for (var i = 0; i < services.length; i++) {
		if(services[i].isEnabled != "1" || services[i].Id == "9") { continue; }
		var d = document.createElement('div');
		$(d).attr("style", "cursor:pointer;");
		$(d).attr("id", services[i].ServiceId);
		$(d).addClass("col s6 m6 l4 serv-box").appendTo($("#services-box-container"));
		$(d).on('click', function() { fetchBikeBrands($(this).attr("id")); });
		var a = document.createElement('a');
		$(a).addClass("dark-gray-color-text-gear6").appendTo($(d));
		var img = document.createElement('img');
		$(img).addClass("circle responsive-img z-depth-1 serv-img").appendTo($(a));
		$(img).attr("src", services[i].SerImg);
		$(img).attr("style", "height:100px;");
		var p = document.createElement('p'); var space = "";
		if(services[i].ServiceName.length < 10) { space = "<br/><br/>"; }
		$(p).html(services[i].ServiceName.trim() + space);
		$(p).appendTo($(a));
	}
	document.getElementById("loading").style.display = "none";
	document.getElementById("services-box").style.display = "block";
};
getLocation = function() {
	if(navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition);
	} else {
		sweetAlert("error", "Geolocation is not supported by this browser.", "error");
	}
};
geocodeLatLng = function() {
	var input = $scope.searchLocation;
	var geocoder = new google.maps.Geocoder;
	var latlngStr = input.split(',', 2);
	var latlng = {
		lat: parseFloat(latlngStr[0]),
		lng: parseFloat(latlngStr[1])
	};
	geocoder.geocode(
	{
		'location': latlng
	}, function(results, status) {
		if(status === google.maps.GeocoderStatus.OK) {
			if(results[1]) {
				$scope.searchLocation = results[1]["address_components"][1]["short_name"];
				$('#searchLocation').val($scope.searchLocation);
			} else {
				window.alert('No results found');
			}
		} else {
			window.alert('Geocoder failed due to: ' + status);
		}
	});
};
showPosition = function(position) {
	$scope.searchLocation = position.coords.latitude + "," + position.coords.longitude;
	document.getElementById('ulatitude').value = position.coords.latitude;
	document.getElementById('ulongitude').value = position.coords.longitude;
	geocodeLatLng();
};
changeCity = function(id, name) {
	$scope.citySelected = id;
	document.getElementById("selected-city-dropdown").innerHTML = name;
	defaultCity();
};
fetchBikeBrands = function(id) {
	document.getElementById("services-box").style.display = "none";
	document.getElementById("loading").style.display = "block";
	$scope.serviceId = id; var blockedDates = [];
	var d = new Date(),	month = '' + (d.getMonth() + 1), day = '' + d.getDate(), year = d.getFullYear();
	if (month.length < 2) { month = '0' + month; }
	if (day.length < 2) { day = '0' + day; }
	var date = [year, month, day].join('-');
	$.ajax({
		url: GEAR6_API + '/get_holidays',
		data: { date : date },
		type: 'POST',
		success: function(data)	{
			data = JSON.parse("[" + data + "]")[0];
			for(var i = 0; i < data.length; i++) {
				var temp = data[i]; temp = temp.split("-");
				for(var j = 0; j < temp.length; j++) {
					temp[j] = Number(temp[j]);
				}
				blockedDates.push(temp);
			}
		}
	});
	var area = document.getElementById('searchLocation').value;
	var latitude = document.getElementById('ulatitude').value;
	var longitude = document.getElementById('ulongitude').value;
	boolLatitude = true; boolLongitude = true;
	if(latitude == null || latitude == undefined || latitude == "" || isNaN(latitude))	{
		boolLatitude = false;
	}
	if(longitude == null || longitude == undefined || longitude == "" || isNaN(longitude))	{
		boolLongitude = false;
	}
	if(!boolLongitude || !boolLongitude)	{
		sweetAlert("Oops!", "Please enter your location.", "warning"); 
		document.getElementById("loading").style.display = "none";
		document.getElementById("services-box").style.display = "block";
	} else {
		if(id == 7 || id == 11) {
			$('#final_action').html('Book Service');
		} else {
			$('#final_action').html('Search');
		}
		if(id == 7 || id == 3) {
			$('.datepicker').val($.datepicker.formatDate('DD, d MM, yy', new Date()));
			$scope.oDate = $.datepicker.formatDate('DD, d MM, yy', new Date());
			$('.datepicker').prop("disabled", true);
		} else {
			$('.datepicker').prop("disabled", false);
		}
		$.ajax({
			url: GEAR6_API + '/get_bike_brands',
			type: 'GET',
			success: function(data2) {
				document.getElementById("services-box").style.display = "none";
				document.getElementById("bike-brands-box").style.display = "block";
				$scope.bikeBrands = data2.bikecompanies;
				$("#slick-slider-container").html(""); $scope.sliderMap = [];
				for (var i = 0; i < $scope.bikeBrands.length; i++) {
					var d = document.createElement('div');
					$(d).attr("style", "cursor:pointer;");
					$(d).attr("name", $scope.bikeBrands[i].BikeCompanyId + "/" + $scope.bikeBrands[i].BikeCompanyName);
					$scope.sliderMap.push($scope.bikeBrands[i].BikeCompanyId + "/" + $scope.bikeBrands[i].BikeCompanyName);
					$(d).appendTo($("#slick-slider-container"));
					$(d).on('click', function() { fetchBikeBrandModels($(this).attr("name")); });
					var imgParent = document.createElement('div');
					$(imgParent).addClass("bike-brand").appendTo($(d));
					$(imgParent).attr("style", "background:url('" + $scope.bikeBrands[i].BCImg + "');background-size:90% 90%;height:100px;width:100px;border-radius:50%;background-attachment:conain;background-repeat:no-repeat;background-position:center;");
					$(imgParent).attr("id",$scope.bikeBrands[i].BikeCompanyName);
				}
				slickSlider($scope.sliderMap);
				$scope.currentBikeCompany = $scope.bikeBrands[0].BikeCompanyId;
				fetchBikeBrandModels($scope.bikeBrands[0].BikeCompanyId + "/" + $scope.bikeBrands[0].BikeCompanyName);
				document.getElementById("loading").style.display = "none";
			}
		});
	}
};
slickSlider = function(sliderMap) {
	$('.slick-center').slick({
		centerMode: true,
		centerPadding: '75px',
		slidesToShow: 2,
		slidesToScroll: 4,
		arrows: true,
		focusOnSelect: true,
		responsive: [
			{
				breakpoint: 768,
				settings: {
				arrows: true,
				centerMode: true,
				centerPadding: '40px',
				slidesToShow: 1
				}
			},
			{
				breakpoint: 480,
				settings: {
				arrows: true,
				centerMode: true,
				centerPadding: '50px',
				slidesToShow: 1
				}
			}
		]
	});        
	$('.slick-center').on('beforeChange', function(event, slick, currentSlide, nextSlide){
	  fetchBikeBrandModels(sliderMap[nextSlide]);
	});
};
backToBrands = function() {
	document.getElementById("services-box").style.display = "block";
	document.getElementById("bike-brands-box").style.display = "none";
	document.getElementById("date").value = "";
	document.getElementById("selected-bike-dropdown-value").textContent = "Select Vehicle";
	$scope.bikeModels = [];
	$scope.currentBikeCompany = undefined;
	$scope.currentBikeModelId = undefined;
	$scope.serviceId = undefined;
	populateServicesContainer($scope.services);
};
fetchBikeBrandModels = function(id) {
	var id = id.substring(0, id.indexOf('/'));
	var json = { company: id };
	$.ajax({
		url: GEAR6_API + '/get_bike_list',
		data: json,
		type: 'POST',
		success: function(data)	{
			$scope.bikeModels = data.bikemodels;
			$scope.currentBikeCompany = id; $scope.currentBikeModelId = undefined;
			var length = $scope.bikeModels.length;
			var sel_options = '';
			for(var i = 0; i < length; i++) {
				sel_options += '<option value="' + $scope.bikeModels[i].BikeModelId + '">' + $scope.bikeModels[i].BikeModelName + '</option>';
			}
			$('#selected-bike-dropdown-value').html(sel_options);
			$scope.select2 = $('#selected-bike-dropdown-value').select2({
				placeholder: "Bike Model",
				minimumResultsForSearch: 10,
				containerCssClass: "cityCombo12"
			});
			$("#selected-bike-dropdown-trigger").removeAttr("disabled");
			$scope.select2.val(null).trigger("change");
		}
	});
};
selectBikeModel = function() {
	try
	{
		$scope.currentBikeModelId = $('#selected-bike-dropdown-value').val();
	}
	catch(err)
	{
		$scope.currentBikeModelId = undefined;
	}
};
fetchServiceCenters = function() {
	var date = document.getElementById("date").value;
	var boolD = false; var boolC = false; var boolM = false;
	if(date == "" || date.length == 0 || date == null || date == undefined)	{
		sweetAlert("Error", "Please select a date.", "warning");
	} else {
		boolD = true;
	}
	if($scope.currentBikeCompany == "" || $scope.currentBikeCompany == null || $scope.currentBikeCompany == undefined) {
		sweetAlert("Error", "Please select a bike brand", "warning");
	} else {
		boolC = true;
	}
	if($scope.currentBikeModelId == "" || $scope.currentBikeModelId == null || $scope.currentBikeModelId == undefined) {
		sweetAlert("Error", "Please select a bike model", "warning");
	} else {
		boolM = true;
	}
	if(boolD == true && boolC == true && boolM == true) {
		if($scope.serviceId == 3 || $scope.serviceId == 7) {
			date = $scope.oDate;
		}
		if($scope.serviceId == 7) {
			$('#accidentModal').openModal({
				dismissible : false,
				ready: function() {
					var overlay = $('<div id="lean-overlay" class="lean-overlay"></div>');
					$("body").append(overlay);
					$('#lean-overlay').css({'opacity':'0.5','display':'block'});
				}
			});
		} else if($scope.serviceId == 11) {
			$('#punctureModal').openModal({
				dismissible : false,
				ready: function() {
					var overlay = $('<div id="lean-overlay" class="lean-overlay"></div>');
					$("body").append(overlay);
					$('#lean-overlay').css({'opacity':'0.5','display':'block'});
				}
			});
			$scope.oDate = date;
		} else {
			var url = '/user/book';
			var area = document.getElementById('searchLocation').value;
			var lat = document.getElementById('ulatitude').value;
			var lon = document.getElementById('ulongitude').value;
			var created_form = $('<form action="' + url + '" method="POST">\
				<input type="hidden" name="date_" value="' + date + '" />\
				<input type="hidden" name="date_query" value="' + date + '" />\
				<input type="hidden" name="area" value="' + area + '" />\
				<input type="hidden" name="qlati" value="' + lat + '" />\
				<input type="hidden" name="qlongi" value="' + lon + '" />\
				<input type="hidden" name="company" value="' + $scope.currentBikeCompany + '" />\
				<input type="hidden" name="model" value="' + $scope.currentBikeModelId + '" />\
				<input type="hidden" name="servicetype" value="' + $scope.serviceId + '" />\
				<input type="hidden" name="book" value="blah" />\
				<input type="submit" name="booksc" value="submit" /></form>').appendTo('body');
			created_form.submit();
		}
	}
};
function sendAccidentOTP(boolOTP) {
	var accidentPhoneNumber = parseInt(document.getElementById("accidentPhoneNumber").value);
	var accidentEmail = document.getElementById("accidentEmail").value;
	var accidentText = document.getElementById("accidentText").value;
	var boolEmail = false; var boolPhone = false; var boolText = false;
	if(!IsEmail(accidentEmail)) {
		sweetAlert("Error", "Please enter a valid Email", "warning");
	}
	else {
		boolEmail = true;
	}
	if(accidentPhoneNumber < 7000000000 || accidentPhoneNumber > 9999999999 || isNaN(accidentPhoneNumber)) {
		sweetAlert("Error", "Please enter a valid Mobile Number", "warning");
	}
	else {
		boolPhone = true;
	}
	if(accidentText == null || accidentText == undefined || accidentText.length == 0) {
		sweetAlert("Error", "Please provide some additional details", "warning");
	}
	else {
		boolText = true;
	}
	if(boolOTP && boolEmail && boolPhone && boolText) {
		$.ajax({
			url: GEAR6_API + '/send_otp_user',
			type: 'POST',
			data: {phNum: String(accidentPhoneNumber)},
			success: function(data)
			{
				document.getElementById("placeEmgOrder").style.display = "block";
				document.getElementById("placeEmgOrderOTP").style.display = "none";
				return true;
			}
		});
	} else if(!boolOTP && boolEmail && boolPhone && boolText) {
		return true;
	} else {
		return false;
	}
};
function sendPunctureOTP(boolOTP) {
	var ptPhoneNumber = parseInt(document.getElementById("puncturePhoneNumber").value);
	var ptEmail = document.getElementById("punctureEmail").value;
	var ptText = document.getElementById("punctureText").value;
	var ptType = $("input[name='pttype']:checked").val();
	var ptTyre = $("input[name='pttyre']:checked").val();
	var boolEmail = false; var boolPhone = false; var boolType = false; var boolTyre = false; var boolText = false;
	if(!IsEmail(ptEmail))
	{
		sweetAlert("Error", "Please enter a valid Email", "warning");
	} else {
		boolEmail = true;
	}
	if(ptPhoneNumber < 7000000000 || ptPhoneNumber > 9999999999 || isNaN(ptPhoneNumber)) {
		sweetAlert("Error", "Please enter a valid Mobile Number", "warning");
	} else {
		boolPhone = true;
	} if(ptType == undefined || ptType == null || ptType == '') {
		sweetAlert("Error", "Choose a valid tyre type", "warning");
	} else {
		boolType = true;
	}
	if(ptTyre == undefined || ptTyre == null || ptTyre == '') {
		sweetAlert("Error", "Choose the tyre that is punctured.", "warning");
	} else {
		boolTyre = true;
	}
	if(ptText == null || ptText == undefined || ptText.length == 0) {
		sweetAlert("Error", "Please provide some additional details", "warning");
	} else {
		boolText = true;
	}
	if(boolOTP && boolEmail && boolPhone && boolType && boolTyre && boolText) {
		$.ajax({
			url: GEAR6_API + '/send_otp_user',
			type: 'POST',
			data: {phNum: String(ptPhoneNumber)},
			success: function(data)
			{
				document.getElementById("placePunctureOrder").style.display = "block";
				document.getElementById("placePunctureOrderOTP").style.display = "none";
				return true;
			}
		});
	} else if(!boolOTP && boolEmail && boolPhone && boolType && boolTyre && boolText) {
		return true;
	} else {
		return false;
	}
}
IsEmail = function (email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}
banner = undefined;
run = function(force) {
	var n = document.querySelector('.smartbanner');
	if (n) { n.parentNode.removeChild(n); }
	new SmartBanner({
		daysHidden: 1, // days to hide banner after close button is clicked (defaults to 15)
		daysReminder: 1, // days to hide banner after "VIEW" button is clicked (defaults to 90)
		appStoreLanguage: 'us', // language code for the App Store (defaults to user's browser language)
		title: 'gear6',
		author: 'NewEin Technologies Pvt Ltd',
		button: 'Install',
		store: {
			ios: 'On the App Store',
			android: 'On Google Play',
			windows: 'In Windows store'
		},
		price: {
			ios: 'FREE',
			android: 'FREE',
			windows: 'FREE'
		},
		force: force
	});
};
getClientOS = function() {
	var pgwBrowser = $.pgwBrowser();
	var os = pgwBrowser.os.group.trim();
	if (os === 'Windows' || os === 'Windows Phone' || os === 'Windows Mobile') {
		run('windows');
	} else if (os === 'iOS') {
		run('ios');
	} else if (os === 'Android') {
		run('android');
	}
}
getClientOS();