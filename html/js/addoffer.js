var user_in_ajax_list = 0; var user_in_referral_ajax_list = 0; var service_in_ajax_list = 0;
var delete_coupon_id = null; var delete_coupon_code = null;
var delete_referral_coupon_id = null; var delete_referral_coupon_code = null;
$(function() {
	try {
		$('#validFrom').datepicker ({
		'dateFormat': "yy-mm-dd",
		'autoclose': true
		});
		$('#validTill').datepicker ({
		'dateFormat': "yy-mm-dd",
		'autoclose': true
		});
		$('#referral_valid_till').datepicker ({
		'dateFormat': "yy-mm-dd",
		'autoclose': true
		});
	} catch (err) {
		// Do Nothing
	}
	try {
		var select2a = $('#coupon_type').select2({
			placeholder: "Coupon Type",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2a.val(null).trigger("change");
		var select2b = $('#is_enabled').select2({
			placeholder: "Is Enabled?",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2b.val(null).trigger("change");
	} catch(err) {
		//Do Nothing
	}
	$('#user_id').on('input', function() {
		user_in_ajax_list = 0;
		checkfieldOffer();
	});
	$('#referral_user_id').on('input', function() {
		user_in_referral_ajax_list = 0;
		checkfieldReferral();
	});
	$('#service_id').on('input', function() {
		service_in_ajax_list = 0;
		checkfieldOffer();
	});
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
	$('#coupon_add').on('click', function(e) {
		var str1 = new Array(12);
		str1[0] = $("#coupon_code").val();
		str1[1] = $("#coupon_type").val();
		str1[2] = $("#coupon_amount").val();
		str1[3] = $("#maximum_discount").val();
		str1[4] = $("#minimum_purchase").val();
		str1[5] = $("#maximum_uses").val();
		str1[6] = $("#per_user_limit").val();
		str1[7] = $("#validFrom").val();
		str1[8] = $("#validTill").val();
		str1[9] = $("#is_enabled").val();
		str1[10] = $("#user_id").val();
		str1[11] = $("#service_id").val();
		var user_id = str1[10];
		user_id = user_id.substring(user_id.indexOf("(")+1).trim();
		user_id = user_id.substring(user_id.indexOf("(")+1, (user_id.length-1)).trim();
		str1[10] = user_id;
		var service_id = str1[11];
		service_id = service_id.substring(service_id.indexOf("(")+1, (service_id.length-1)).trim();
		str1[11] = service_id;
		var str2 = ["coupon_code", "coupon_type", "coupon_amount", "maximum_discount", "minimum_purchase", "maximum_uses",
		 "per_user_limit", "validFrom", "validTill", "is_enabled", "user_id", "service_id"];
		var form = '<form action="/admin/manageoffer/create_offer" method="POST">';
		for (i = 0; i < str2.length; i++) {
			form += '<input type="hidden" name="' + str2[i] + '" value="' + str1[i] + '" />';
		}
		form += '<input type="submit" name="coupon_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
	$('#coupon_mod').on('click', function(e) {
		var o_id = $('input[name=coupon_id]:checked').val();
		var str1 = new Array(12);
		str1[0] = $("#coupon_code").val();
		str1[1] = $("#coupon_type").val();
		str1[2] = $("#coupon_amount").val();
		str1[3] = $("#maximum_discount").val();
		str1[4] = $("#minimum_purchase").val();
		str1[5] = $("#maximum_uses").val();
		str1[6] = $("#per_user_limit").val();
		str1[7] = $("#validFrom").val();
		str1[8] = $("#validTill").val();
		str1[9] = $("#is_enabled").val();
		str1[10] = $("#user_id").val();
		str1[11] = $("#service_id").val();
		var user_id = str1[10];
		user_id = user_id.substring(user_id.indexOf("(")+1).trim();
		user_id = user_id.substring(user_id.indexOf("(")+1, (user_id.length-1)).trim();
		str1[10] = user_id;
		var service_id = str1[11];
		service_id = service_id.substring(service_id.indexOf("(")+1, (service_id.length-1)).trim();
		str1[11] = service_id;
		var str2 = ["coupon_code", "coupon_type", "coupon_amount", "maximum_discount", "minimum_purchase", "maximum_uses",
		 "per_user_limit", "validFrom", "validTill", "is_enabled", "user_id", "service_id"];
		var form = '<form action="/admin/manageoffer/modify_offer" method="POST">';
		for (i = 0; i < str2.length; i++) {
			form += '<input type="hidden" name="' + str2[i] + '" value="' + str1[i] + '" />';
		}
		form += '<input type="hidden" name="coupon_code_id" value="' + o_id + '" />';
		form += '<input type="submit" name="coupon_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
	$('#coupon_del').on('click', function(e) {
		swal({
			title: "Are you sure?",
			text: "You are about to delete " + delete_coupon_code,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			cancelButtonText: "Cancel",
			closeOnConfirm: false,
			closeOnCancel: false,
			animation: "slide-from-top",
		}, function(isConfirm) {
			if (isConfirm) {
			    swal("Deleted!", delete_coupon_code + " has been deleted.", "success");
			    var form = '<form action="/admin/manageoffer/delete_offer" method="POST">';
			    form += '<input type="hidden" name="delete_coupon_id" value="' + delete_coupon_id + '" />';
			    form += '<input type="submit" name="coupon_submit" value="submit" /></form>';
			    var created_form = $(form).appendTo('body');
			    created_form.submit();
			} else {
				swal("Cancelled", "Operation aborted.", "error");
			}
		});
	});
	$(document).on('ifChecked', 'input[name=coupon_id]', function() {
		populateNewFields($(this));
		delete_coupon_id = $(this).attr("id").substring($(this).attr("id").indexOf("_")+1);
		delete_coupon_code = $(this).attr("coupon-code");
		user_in_ajax_list = 1; service_in_ajax_list = 1;
		checkfieldOffer();
		if(delete_coupon_id !== null && delete_coupon_code !== null) {
			$("#coupon_del").removeAttr('disabled');
		} else {
			$("#coupon_del").attr('disabled','disabled');
		}
	});
	$('#referral_coupon_add').on('click', function(e) {
		var str1 = new Array(4);
		str1[0] = $("#referral_coupon_code").val();
		str1[1] = $("#referral_coupon_amount").val();
		str1[2] = $("#referral_valid_till").val();
		str1[3] = $("#referral_user_id").val();
		var user_id = str1[3];
		user_id = user_id.substring(user_id.indexOf("(")+1).trim();
		user_id = user_id.substring(user_id.indexOf("(")+1, (user_id.length-1)).trim();
		str1[3] = user_id;
		var str2 = ["referral_coupon_code", "referral_coupon_amount", "referral_valid_till", "referral_user_id"];
		var form = '<form action="/admin/manageoffer/create_referral" method="POST">';
		for (i = 0; i < str2.length; i++) {
			form += '<input type="hidden" name="' + str2[i] + '" value="' + str1[i] + '" />';
		}
		form += '<input type="submit" name="referral_coupon_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
	$('#referral_coupon_mod').on('click', function(e) {
		var o_id = $('input[name=referral_id]:checked').val();
		var str1 = new Array(4);
		str1[0] = $("#referral_coupon_code").val();
		str1[1] = $("#referral_coupon_amount").val();
		str1[2] = $("#referral_valid_till").val();
		str1[3] = $("#referral_user_id").val();
		var user_id = str1[3];
		user_id = user_id.substring(user_id.indexOf("(")+1).trim();
		user_id = user_id.substring(user_id.indexOf("(")+1, (user_id.length-1)).trim();
		str1[3] = user_id;
		var str2 = ["referral_coupon_code", "referral_coupon_amount", "referral_valid_till", "referral_user_id"];
		var form = '<form action="/admin/manageoffer/modify_referral" method="POST">';
		for (i = 0; i < str2.length; i++) {
			form += '<input type="hidden" name="' + str2[i] + '" value="' + str1[i] + '" />';
		}
		form += '<input type="hidden" name="referral_coupon_code_id" value="' + o_id + '" />';
		form += '<input type="submit" name="referral_coupon_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
	$('#referral_coupon_del').on('click', function(e) {
		swal({
			title: "Are you sure?",
			text: "You are about to delete " + delete_referral_coupon_code,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			cancelButtonText: "Cancel",
			closeOnConfirm: false,
			closeOnCancel: false,
			animation: "slide-from-top",
		}, function(isConfirm) {
			if (isConfirm) {
			    swal("Deleted!", delete_referral_coupon_code + " has been deleted.", "success");
			    var form = '<form action="/admin/manageoffer/delete_referral_offer" method="POST">';
			    form += '<input type="hidden" name="delete_referral_coupon_id" value="' + delete_referral_coupon_id + '" />';
			    form += '<input type="submit" name="referral_coupon_submit" value="submit" /></form>';
			    var created_form = $(form).appendTo('body');
			    created_form.submit();
			} else {
				swal("Cancelled", "Operation aborted.", "error");
			}
		});
	});
	$(document).on('ifChecked', 'input[name=referral_id]', function() {
		populateReferralFields($(this));
		delete_referral_coupon_id = $(this).attr("id").substring($(this).attr("id").indexOf("_")+1);
		delete_referral_coupon_code = $(this).attr("referral-coupon-code");
		user_in_referral_ajax_list = 1;
		checkfieldReferral();
		if(delete_referral_coupon_id !== null && delete_referral_coupon_code !== null) {
			$("#referral_coupon_del").removeAttr('disabled');
		} else {
			$("#referral_coupon_del").attr('disabled','disabled');
		}
	});
});
function checkfieldOffer() {
	var str1 = new Array(12);
	str1[0] = $("#coupon_code").val();
	str1[1] = $("#coupon_type").val();
	str1[2] = $("#coupon_amount").val();
	str1[3] = $("#maximum_discount").val();
	str1[4] = $("#minimum_purchase").val();
	str1[5] = $("#maximum_uses").val();
	str1[6] = $("#per_user_limit").val();
	str1[7] = $("#validFrom").val();
	str1[8] = $("#validTill").val();
	str1[9] = $("#is_enabled").val();
	str1[10] = $("#user_id").val();
	str1[11] = $("#service_id").val();
	var x = 0;
	for (var i = 0; i < 10; i++) {
		if(str1[i] === null || str1[i] == '' || str1[i] == undefined) {
			x = 1;
		}
		if(i == 7) {
			if(str1[i] > str1[i+1]) {
				x = 1;
			}
		}
	}
	if(str1[10] === null || str1[10] == '' || str1[10] == undefined) {
		str1[10] = null;
		user_in_ajax_list = 1;
	}
	if(str1[11] === null || str1[11] == '' || str1[11] == undefined) {
		str1[11] = null;
		service_in_ajax_list = 1;
	}
	if(user_in_ajax_list === 0 || service_in_ajax_list === 0) {
		x = 1;
	}
	if(x == 0) {
		$("#coupon_add").removeAttr('disabled');
		$("#coupon_mod").removeAttr('disabled');
	} else {
		$("#coupon_add").attr('disabled','disabled');
		$("#coupon_mod").attr('disabled','disabled');
	}
}
function checkfieldReferral() {
	var str1 = new Array(4);
	str1[0] = $("#referral_coupon_code").val();
	str1[1] = $("#referral_coupon_amount").val();
	str1[2] = $("#referral_valid_till").val();
	str1[3] = $("#referral_user_id").val();
	var x = 0;
	for (var i = 0; i < 4; i++) {
		if(str1[i] === null || str1[i] == '' || str1[i] == undefined) {
			x = 1;
		}
	}
	if(user_in_referral_ajax_list === 0) {
		x = 1;
	}
	if(x == 0) {
		$("#referral_coupon_add").removeAttr('disabled');
		$("#referral_coupon_mod").removeAttr('disabled');
	} else {
		$("#referral_coupon_add").attr('disabled','disabled');
		$("#referral_coupon_mod").attr('disabled','disabled');
	}
}
function enableAutoField() {
	$("#user_id").autocomplete ({
		source: function(request, response) {
			$.ajax({
				type: "POST",
				url: "/admin/manageoffer/get_user_ajax",
				data: {"user_id": request.term},
				dataType: "json",
				success: function (data) {
					user_in_ajax_list = 0;
					response(data);
				},
				error: function (error) {
					console.log(error);
				}
			});
		},
		minLength: 3,
		select: function (a, b) {
			$("#user_id").val(b.item.value);
			user_in_ajax_list = 1;
			checkfieldOffer();
		}
	});
	$("#service_id").autocomplete ({
		source: function(request, response) {
			$.ajax({
				type: "POST",
				url: "/admin/manageoffer/get_service_ajax",
				data: {"service_id": request.term},
				dataType: "json",
				success: function (data) {
					service_in_ajax_list = 0;
					response(data);
				},
				error: function (error) {
					console.log(error);
				}
			});
		},
		minLength: 1,
		select: function (a, b) {
			$("#service_id").val(b.item.value);
			service_in_ajax_list = 1;
			checkfieldOffer();
		}
	});
	$("#referral_user_id").autocomplete ({
		source: function(request, response) {
			$.ajax({
				type: "POST",
				url: "/admin/manageoffer/get_user_ajax",
				data: {"user_id": request.term},
				dataType: "json",
				success: function (data) {
					user_in_referral_ajax_list = 0;
					response(data);
				},
				error: function (error) {
					console.log(error);
				}
			});
		},
		minLength: 3,
		select: function (a, b) {
			$("#referral_user_id").val(b.item.value);
			user_in_referral_ajax_list = 1;
			checkfieldReferral();
		}
	});
}
enableAutoField();
function populateNewFields(element) {
	document.getElementById("coupon_code").value = element.attr("coupon-code");
	document.getElementById("coupon_type").value = element.attr("coupon-type");
	document.getElementById("coupon_amount").value = element.attr("coupon-amount");
	document.getElementById("maximum_discount").value = element.attr("coupon-maximum-discount");
	document.getElementById("minimum_purchase").value = element.attr("coupon-minimum-purchase");
	document.getElementById("maximum_uses").value = element.attr("coupon-maximum-uses");
	document.getElementById("per_user_limit").value = element.attr("coupon-per-user-limit");
	document.getElementById("validFrom").value = element.attr("coupon-valid-from");
	document.getElementById("validTill").value = element.attr("coupon-valid-till");
	document.getElementById("is_enabled").value = element.attr("coupon-is-enabled");
	document.getElementById("user_id").value = element.attr("coupon-user-id");
	document.getElementById("service_id").value = element.attr("coupon-service-id");
}
function populateReferralFields(element) {
	document.getElementById("referral_coupon_code").value = element.attr("referral-coupon-code");
	document.getElementById("referral_coupon_amount").value = element.attr("referral-coupon-amount");
	document.getElementById("referral_valid_till").value = element.attr("referral-coupon-valid-till");
	document.getElementById("referral_user_id").value = element.attr("referral-coupon-user-id");
}