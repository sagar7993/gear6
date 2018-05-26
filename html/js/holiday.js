$(document).ready(function(){
    try {
    	$('.collapsible').collapsible({
	    	accordion : false // A setting that changes the collapsible behavior to expandable instead of the default accordion style
	    });
    } catch(err) {
    	//Do Nothing
    }
});
var holidays; var service_centers = []; var g6_ckbs = false;
$(function() {
	$("input[type='checkbox'], input[type='radio']").icheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
	$('#holiday_dates').multiDatesPicker({
		dateFormat: "yy-mm-dd",
		minDate: 0,
		maxDate: 45,
		onSelect: validation
	});
	$('#sc_ckbs').on('ifChecked', function() {
		$('.sc_ckbs').icheck('checked');
		validation();
	});
	$('#sc_ckbs').on('ifUnchecked', function() {
		$('.sc_ckbs').icheck('unchecked');
		validation();
	});
	$('#g6_ckbs').on('ifChecked', function() {
		g6_ckbs = true;
		validation();
	});
	$('#g6_ckbs').on('ifUnchecked', function() {
		g6_ckbs = false;
		validation();
	});
});
var validation = function() {
	holidays = $("#holiday_dates").val();
	service_centers = $("input[name='sc_ids[]']:checked").map(function() { return $(this).attr("id"); }).get();
	if(g6_ckbs == true) {
		service_centers.push("-1");
	}
	if(holidays.length > 0) {
		if(service_centers.length > 0) {
			$("#update_holidays").removeAttr('disabled');
		} else {
			if(g6_ckbs == true) {
				$("#update_holidays").removeAttr('disabled');
			} else {
				$("#update_holidays").attr('disabled','disabled');
			}
		}
	}
}
$('#update_holidays').on('click', function(e) {
	if(holidays.length > 0) {
		var form = '<form action="/admin/vendors/bulk_update_holidays" method="POST">';
		form += '<input type="hidden" name="holidays" value="' + holidays + '" />';
		form += '<input type="hidden" name="service_centers" value="' + service_centers + '" />';
		form += '<input type="submit" name="vendor_holidays_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	}
});
function deleteServiceCenterHoliday(element) {
	var serviceCenterId = $(element).attr("service-center-id");
	var holidayDate = $(element).attr("holiday-date");
	var serviceCenterName = $(element).attr("service-center-name");
	swal({
		title: "Are you sure?",
		text: "You are about to delete the holiday for " + serviceCenterName + " on " + holidayDate,
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Delete",
		cancelButtonText: "Cancel",
		closeOnConfirm: false,
		closeOnCancel: true,
		animation: "slide-from-top",
	}, function(isConfirm) {
		if (isConfirm) {
	  		var form = '<form action="/admin/vendors/deleteServiceCenterHoliday" method="POST">';
	  		form += '<input type="hidden" name="holidayDate" value="' + holidayDate + '" />';
	  		form += '<input type="hidden" name="serviceCenterId" value="' + serviceCenterId + '" />';
	  		form += '<input type="submit" name="holidayDateSubmit" value="submit" /></form>';
	  		var created_form = $(form).appendTo('body');
	  		created_form.submit();
	  	} else {
	  		swal("Cancelled", "Operation aborted.", "error");
	  	}
	});	
}
function deleteHoliday(element) {
	var holidayDate = $(element).attr("holiday-date");
	swal({
		title: "Are you sure?",
		text: "You are about to delete the holiday for ALL service centers on " + holidayDate,
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Delete",
		cancelButtonText: "Cancel",
		closeOnConfirm: false,
		closeOnCancel: true,
		animation: "slide-from-top",
	}, function(isConfirm) {
		if (isConfirm) {
			var form = '<form action="/admin/vendors/deleteHoliday" method="POST">';
			form += '<input type="hidden" name="holidayDate" value="' + holidayDate + '" />';
			form += '<input type="submit" name="holidayDateSubmit" value="submit" /></form>';
			var created_form = $(form).appendTo('body');
			created_form.submit();
	  	} else {
	  		swal("Cancelled", "Operation aborted.", "error");
	  		$("#collapsible_header_" + holidayDate).trigger("click");
	  	}
	});
}