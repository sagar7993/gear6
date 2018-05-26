$(function () {
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
});
var bikeModelInAjaxList = 0; var serviceCenterInAjaxList = 0; var serviceCenterSelected = null; var sparePartSelected = null;
$('#location').on('input change', function() {
	document.getElementById('ulatitude').value = "";
	document.getElementById('ulongitude').value = "";
	validation();
});
function initiateGooglePlaces() {
	var swlati = 12.730357; var swlongi = 77.359706; var nelati = 13.169339; var nelongi = 77.889796;
	var address = (document.getElementById('location'));
	var defaultBounds = new google.maps.LatLngBounds(new google.maps.LatLng(swlati, swlongi), new google.maps.LatLng(nelati, nelongi));
	var options = {	bounds : defaultBounds, componentRestrictions : { country : 'IN' } };
	var autocomplete = new google.maps.places.Autocomplete(address, options);
	autocomplete.addListener('place_changed', function() {
		var place = autocomplete.getPlace();
		var latitude = place.geometry.location.lat();
		document.getElementById('ulatitude').value = latitude;
		var longitude = place.geometry.location.lng();
		document.getElementById('ulongitude').value = longitude;
		document.getElementById('location').value = place.name;
		validation();
	});
}
initiateGooglePlaces();
function validation() {
	var lat = document.getElementById('ulatitude').value.trim();
	var lng = document.getElementById('ulongitude').value.trim();
	if(bikeModelInAjaxList == 1) {
		if(lat== "" || lat == null || lat == undefined || lat.length == 0 || lng == "" || lng == null || lng == undefined || lng.length == 0) {
			$("#serviceCenterButton").attr('disabled','disabled');
		} else {
			$("#serviceCenterButton").removeAttr('disabled');
		}
	} else {
		$("#serviceCenterButton").attr('disabled','disabled');
	}
}
function fetchSpareParts(serviceCenter) {
	$("#sparePartButton").removeAttr('disabled');
	distance = serviceCenter.substring(serviceCenter.indexOf("(")+1, serviceCenter.indexOf(")")).trim();
	distance = Number(distance.substring(0, distance.indexOf(" Kms")).trim()); var sel_options = '';
	serviceCenterSelected = serviceCenter.substring(serviceCenter.indexOf("(")+1);
	serviceCenterSelected = serviceCenterSelected.substring(serviceCenterSelected.indexOf("(")+1);
	serviceCenterSelected = Number(serviceCenterSelected.substring(0, serviceCenterSelected.indexOf(")")));
	$.ajax({
		type: "POST",
		url: "/admin/orders/getSparePartsByServiceCenter",
		data: { "serviceCenter" : serviceCenterSelected },
		dataType: "json",
		success: function (data) {
			for(var i = 0; i < data.length; i++) {
				sel_options += '<option value="' + data[i].trim() + '">' + data[i].trim() + '</option>';
			}
			$('#sparePart').html(sel_options);
			var select2 = $('#sparePart').select2({
				placeholder: "Spare Parts",
				minimumResultsForSearch: 10,
				containerCssClass: "cityCombo12"
			});
			select2.val(null).trigger("change");
			$(document.body).on("change","#sparePart",function(){
				sparePartSelected = this.value; sparePartSelected = sparePartSelected.substring(0, sparePartSelected.indexOf("(") - 1);
			})
		},
		error: function (error) {
			console.log(error);
		}
	});
}
$("#bikeModel").autocomplete({
	source: function(request, response) {
		$.ajax({
			type: "POST",
			url: "/admin/orders/getBikeModels",
			data: {"text": request.term},
			dataType: "json",
			success: function (data) {
				bikeModelInAjaxList = 0;
				response(data);
			},
			error: function (error) {
				console.log(error);
			}
		});
	},
	minLength: 3,
	select: function (a, b) {
		$("#bikeModel").val(b.item.value);
		bikeModelInAjaxList = 1;
		validation();
	}
});
$(function() {
	$('#bikeModel').on('input', function() {
		bikeModelInAjaxList = 0;
		validation();
	});
});
$('#sparePartButton').on('click', function(e) {
	var bike = $("#bikeModel").val().trim();
	var company = bike.substring(bike.indexOf("(")+1, bike.indexOf(")")).trim();
	var bikeCompany = bike.substring(bike.indexOf("("), bike.indexOf(")") + 1).trim();
	var bikeModel = bike.substring(bike.indexOf(bikeCompany) + bikeCompany.length);
	var model = bikeModel.substring(bikeModel.indexOf("(")+1, bikeModel.indexOf(")")).trim();
	var form = '<form action="/admin/orders/getPricing" method="POST">';
	form += '<input type="hidden" name="bikeModel" value="' + model + '" />';
	form += '<input type="hidden" name="serviceCenter" value="' + serviceCenterSelected + '" />';
	if(sparePartSelected !== null) { form += '<input type="hidden" name="sparePart" value="' + sparePartSelected + '" />'; }
	form += '<input type="submit" name="spare_part_submit" value="submit" /></form>';
	var created_form = $(form).appendTo('body');
	created_form.submit();
});
$('#serviceCenterButton').on('click', function(e) {
	document.getElementById("step2").style.display = "block"; var location = null; var sel_options = '';
	$("#bikeModel").attr('disabled','disabled'); $("#location").attr('disabled','disabled'); $("#serviceCenterButton").attr('disabled','disabled');
		var bike = $("#bikeModel").val().trim();
		var company = bike.substring(bike.indexOf("(")+1, bike.indexOf(")")).trim();
		var bikeCompany = bike.substring(bike.indexOf("("), bike.indexOf(")") + 1).trim();
		var bikeModel = bike.substring(bike.indexOf(bikeCompany) + bikeCompany.length).trim();
		var model = Number(bikeModel.substring(bikeModel.indexOf("(")+1, bikeModel.indexOf(")")).trim());
	$.ajax({
		type: "POST",
		url: "/admin/orders/getServiceCenterByLocation",
		data: { "latitude" : $("#ulatitude").val(), "longitude" : $("#ulongitude").val(), "BikeModelId" : model },
		dataType: "json",
		success: function (data) {
			for(var i = 0; i < data.length; i++) {
				var serviceCenter = data[i].trim(); serviceCenter = serviceCenter.substring(0, serviceCenter.indexOf(")")+1).trim();
				sel_options += '<option value="' + data[i].trim() + '">' + serviceCenter + '</option>';
			}
			$('#serviceCenter').html(sel_options);
			var select2 = $('#serviceCenter').select2({
				placeholder: "Service Center",
				minimumResultsForSearch: 10,
				containerCssClass: "cityCombo12"
			});
			select2.val(null).trigger("change");
			$(document.body).on("change","#serviceCenter",function(){
				$("#sparePart").removeAttr('disabled');
				fetchSpareParts(this.value);
			})
		},
		error: function (error) {
			console.log(error);
		}
	});
});