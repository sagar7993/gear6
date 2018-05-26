$(function() {
	$('.spttype').on('change', function() {
		if($(this).val() == '4') {
			$(this).parent().parent().find('.spdetail').val('gear6.in Convenience Fee');
		}
	});
	$("input[type='checkbox'], input[type='radio']").icheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
	$('.collapsible').collapsible({
		accordion : false
    });
    $('#reminder_date').datepicker ({
    	'dateFormat': "yy-mm-dd",
    	'autoclose': true,
    	onSelect: function(dateText, inst) {
    		$("#followup_reminder_date").val(dateText);
    	}
    });
    $('.gear6Rating').on('click', function(e) {
    	$('#nps').val($(this).find('input').val());
    });
    $('.serviceCenterRating').on('click', function(e) {
    	$('#user_feedback_rating').val($(this).find('input').val());
    });
	try {
		$('#ex_fup_updates_table').DataTable({
			data: ex_fup_updates,
			bSearchable: true,
			bSortable: true,
			bInfo: true,
			bLengthChange: true,
			bPaginate: true,
			bFilter: true,
			buttons: [],
			aaSorting: [],
			columns: [
				{ title: "Status" },
				{ title: "Remarks" },
				{ title: "Location" },
				{ title: "Updated By" },
				{ title: "Timestamp" }
			],
			oLanguage: {
				"sEmptyTable": "No data available for your query.",
				"sSearch": ""
			},
			dom: 'Bfrtip'
		});
		$('#ex_rtime_updates_table').DataTable({
			data: ex_rtime_updates,
			bSearchable: true,
			bSortable: true,
			bInfo: true,
			bLengthChange: true,
			bPaginate: true,
			bFilter: true,
			buttons: [],
			aaSorting: [],
			columns: [
				{ title: "Status" },
				{ title: "Remarks" },
				{ title: "Location" },
				{ title: "Updated By" },
				{ title: "Timestamp" }
			],
			oLanguage: {
				"sEmptyTable": "No data available for your query.",
				"sSearch": ""
			},
			dom: 'Bfrtip'
		});
		$('#ex_ps_updates_table').DataTable({
			data: ex_ps_updates,
			bSearchable: true,
			bSortable: true,
			bInfo: true,
			bLengthChange: true,
			bPaginate: true,
			bFilter: true,
			buttons: [],
			aaSorting: [],
			columns: [
				{ title: "Est. Time" },
				{ title: "Est. Price" },
				{ title: "Location" },
				{ title: "SC Comments" },
				{ title: "User Comments" },
				{ title: "Updated By" },
				{ title: "Timestamp" }
			],
			oLanguage: {
				"sEmptyTable": "No data available for your query.",
				"sSearch": ""
			},
			dom: 'Bfrtip'
		});
	} catch(err) {
		console.log(err);
	}
	try {
		$('#puc_renewal_date').datepicker({
			'dateFormat': "yy-mm-dd",
			"setDate": new Date(),
			'autoclose': true
		});
		$('#insurance_renewal_date').datepicker({
			'dateFormat': "yy-mm-dd",
			"setDate": new Date(),
			'autoclose': true
		});
		$('#service_reminder_date').datepicker({
			'dateFormat': "yy-mm-dd",
			"setDate": new Date(),
			'autoclose': true
		});
		$('#estimated_date').datepicker({
			'dateFormat': "yy-mm-dd",
			"setDate": new Date(),
			'autoclose': true
		});
	} catch (err) {}
	$('#res_ord_hlink').on('click', function() {
		showRescheduleModal();
	});
});
function validateDates() {
	var insurance_renewal_date = $("#insurance_renewal_date").val();
	var puc_renewal_date = $("#puc_renewal_date").val();
	var service_reminder_date = $("#service_reminder_date").val();
	if((service_reminder_date.length == 0 || service_reminder_date == "") && (insurance_renewal_date.length == 0 || insurance_renewal_date == "") && (puc_renewal_date.length == 0 || puc_renewal_date == "")) {
		$("#insurance_puc_renewal_date_update_button").attr('disabled','disabled');
	} else {
		$("#insurance_puc_renewal_date_update_button").removeAttr('disabled');
	}
}
function validateHappay() {
	var HappayTId = $("#HappayTId").val(); var HappayAmount = $("#HappayAmount").val();
	if(HappayTId == null || HappayTId == undefined || HappayTId == "" || HappayAmount == null || HappayAmount == undefined || HappayAmount == "" || isNaN(HappayAmount)) {
		$("#happayButton").attr('disabled','disabled');
	} else {
		$("#happayButton").removeAttr('disabled');
	}
}
validateHappay();
function validatePickup() {
	var pickup_drop_flag = $("input[type='radio'][name='pick-type']").val();
	if(pickup_drop_flag.length == 0 || pickup_drop_flag == "" || pickup_drop_flag == undefined || pickup_drop_flag == null) {
		$("#pickup_drop_flag_update_button").attr('disabled','disabled');
	} else {
		$("#pickup_drop_flag_update_button").removeAttr('disabled');
	}
}
function validateBreakdown() {
	var transport_mode = $("#transport_mode").val();
	x = 0;
	if($("input[type='checkbox'][name='isBreakdown']:checked").length > 0) {
		if(transport_mode.length == 0 || transport_mode == "" || transport_mode == undefined || transport_mode == null) {
			x = 1;
		}
	} else {
		if(transport_mode != '') {
			$('#transport_mode').val('').trigger("change");
		}
	}
	if(x == 1) {
		$("#is_breakdown_flag_update_button").attr('disabled', 'disabled');
	} else {
		$("#is_breakdown_flag_update_button").removeAttr('disabled');
	}
}
function showAddressModal() {
	document.getElementById('addressDialog').style.display = 'block';
	$("#addressDialog").dialog({
		width: 500
	});
}
function showModal() {
	document.getElementById('dialog').style.display = 'block';
	$("#dialog").dialog({
		width: 500
	});
}
function showReminderModal() {
	document.getElementById('reminderDialog').style.display = 'block';
	$("#reminderDialog").dialog({
		width: 500
	});
}
function reminderDateValidation() {
	var fup_status = $('#fup_status').val();
	if(Number(fup_status) == 23) {
		var followup_reminder_date = $("#followup_reminder_date").val(); var reminder_date = $("#reminder_date").val();
		var scRating = $('.serviceCenterRating').find('input').val(); var g6Rating = $('.gear6Rating').find('input').val();
		if(followup_reminder_date == "" || followup_reminder_date == null || followup_reminder_date == undefined
		 || followup_reminder_date != reminder_date) {
			swal("Error", "Please select a reminder date and enter service center and Gear6 ratings", "error");
		} else {
			$("#followup_status_form").submit();
		}
	} else {
		$("#followup_status_form").submit();
	}
}
$('#reminderDateValidation').on('click', function(e) {
	e.preventDefault();
	reminderDateValidation();
});
function showRescheduleModal() {
	$('#res_ord_date').datepicker ({
		'dateFormat': "yy-mm-dd",
		'autoclose': true,
		onSelect: function(dateText, inst) {
			rescheduleValidation();
		}
	});
	try {
		var select2 = $('#res_ord_time').select2({
			placeholder: "Reschedule Time",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2.val(null).trigger("change");
	} catch(err) {}
	document.getElementById('rescheduleDialog').style.display = 'block';
	$("#rescheduleDialog").dialog({
		width: 500
	});
}
function rescheduleValidation() {
	var date = $("#res_ord_date").val(); var time = $("#res_ord_time").val(); var x = 0;
	if(date == null || date == '' || date == undefined) {
		x = 1;
	}
	if(time == null || time == '' || time == undefined) {
		x = 1;
	}
	if(x == 0) {
		$("#rescheduleOrderButton").removeAttr('disabled');
	} else {
		$("#rescheduleOrderButton").attr('disabled','disabled');
	}
}
function deleteDiscountedPrice(element) {
	swal({
		title: "Are you sure?",
		text: "You are about to delete this discounted price.",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Yes",
		cancelButtonText: "Cancel",
		closeOnConfirm: false,
		closeOnCancel: true,
		animation: "slide-from-top",
	}, function(isConfirm) {
		if (isConfirm) {
		    var cancel_url = $(element).attr("data-cancel");
		    window.location.assign(cancel_url);
		} else {
			//Do Nothing
		}
	});
}
function deleteEstimatedPrice(element) {
	swal({
		title: "Are you sure?",
		text: "You are about to delete this service price.",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Yes",
		cancelButtonText: "Cancel",
		closeOnConfirm: false,
		closeOnCancel: true,
		animation: "slide-from-top",
	}, function(isConfirm) {
		if (isConfirm) {
		    var cancel_url = $(element).attr("data-cancel");
		    window.location.assign(cancel_url);
		} else {
			//Do Nothing
		}
	});
}
function deleteAdditionalPrice(element) {
	swal({
		title: "Are you sure?",
		text: "You are about to delete this additional price.",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Yes",
		cancelButtonText: "Cancel",
		closeOnConfirm: false,
		closeOnCancel: true,
		animation: "slide-from-top",
	}, function(isConfirm) {
		if (isConfirm) {
		    var cancel_url = $(element).attr("data-cancel");
		    window.location.assign(cancel_url);
		} else {
			//Do Nothing
		}
	});
}
try {
	validateDates();
	validatePickup();
} catch (err) {
	//Do Nothing
}
try {
	var select2a = $('#fup_status').select2({
		placeholder: "FollowUp Status History",
		minimumResultsForSearch: 10,
		containerCssClass: "cityCombo12"
	});
	select2a.val(null).trigger("change");
} catch(err) {
	//Do Nothing
}
try {
	var select2b = $('#spttype1').select2({
		placeholder: "Select Tax",
		minimumResultsForSearch: 10,
		containerCssClass: "cityCombo12"
	});
	select2b.val(null).trigger("change");
} catch(err) {
	//Do Nothing
}
try {
	var select2x = $('#transport_mode').select2({
		placeholder: "Mode Of Transport",
		minimumResultsForSearch: 10,
		containerCssClass: "cityCombo12"
	});
} catch(err) {
	//Do Nothing
}
$(document).on('ifChecked', 'input[name=isBreakdown]', function() {
	$("#isBreakdown").val('1'); validateBreakdown();
});
$(document).on('ifUnchecked', 'input[name=isBreakdown]', function() {
	$("#isBreakdown").val('0'); validateBreakdown();
});
function followUpStatusSelect() {
	var fup_status = $('#fup_status').val();
	if(Number(fup_status) == 20) {
		document.getElementById('fup_status_container').style.display = 'block';
	} else {
		document.getElementById('fup_status_container').style.display = 'none';
	}
	if(Number(fup_status) == 19) {
		$('#fup_remarks').val('');
		$('#fup_remarks').attr('readonly','readonly');
	}
	if(Number(fup_status) == 23) {
		showReminderModal();
	}
}
function initiateGooglePlaces() {
	var swlati = 12.730357; var swlongi = 77.359706; var nelati = 13.169339; var nelongi = 77.889796;
	var address = (document.getElementById('location'));
	var defaultBounds = new google.maps.LatLngBounds(new google.maps.LatLng(swlati, swlongi), new google.maps.LatLng(nelati, nelongi));
	var options = {	bounds : defaultBounds, componentRestrictions : { country : 'IN' } };
	var autocomplete = new google.maps.places.Autocomplete(address, options);
	autocomplete.addListener('place_changed', function() {
		var place = autocomplete.getPlace();
		var latitude = place.geometry.location.lat();
		document.getElementById('latitude').value = latitude;
		var longitude = place.geometry.location.lng();
		document.getElementById('longitude').value = longitude;
		document.getElementById('location').value = place.name;
		validateAddress();
	});
}
function validateAddress() {
	var addrLine1 = $("#AddrLine1").val(); var addrLine2 = $("#AddrLine2").val(); var x = 0;
	var location = $("#location").val(); var latitude = $("#latitude").val(); var longitude = $("#longitude").val();
	if(addrLine1.length == 0 || addrLine1 == "" || addrLine1 == undefined || addrLine1 == null) {
		x = 1; swal('Error', 'Please enter address line 1', 'error');
	}
	if(addrLine2.length == 0 || addrLine2 == "" || addrLine2 == undefined || addrLine2 == null) {
		x = 1; swal('Error', 'Please enter address line 2', 'error');
	}
	if(location.length == 0 || location == "" || location == undefined || location == null) {
		x = 1; swal('Error', 'Please select a location', 'error');
	}
	if(latitude.length == 0 || latitude == "" || latitude == undefined || latitude == null) {
		x = 1; swal('Error', 'Please select a location', 'error');
	}
	if(longitude.length == 0 || longitude == "" || longitude == undefined || longitude == null) {
		x = 1; swal('Error', 'Please select a location', 'error');
	}
	if(x == 0) {
		$("#addressChangeButton").removeAttr('disabled');
	} else {
		$("#addressChangeButton").attr('disabled','disabled');
	}
}
$(document).ready(function() {
	initiateGooglePlaces();
	validateAddress();
});