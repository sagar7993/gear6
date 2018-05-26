var delete_admin_id = null; var delete_admin_name = null;
var delete_executive_id = null; var delete_executive_name = null;
$(function() {	
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
	try {
		$('#new_dob').datepicker ({
			'dateFormat': "yy-mm-dd",
			'minDate': new Date(1940, 1 - 1, 1),
			'maxDate': new Date(2001, 1 - 1, 1),
			"setDate": new Date(),
			'autoclose': true
		});
	} catch(err) {}
	try {
		if($('#new_city_id').data('type') == 'select') {
			var select2a = $('#new_city_id').select2({
				placeholder: "Select City",
				minimumResultsForSearch: 10,
				containerCssClass: "cityCombo12"
			});
			select2a.val(null).trigger("change");
		}
	} catch(err) {}
	try {
		var select2b = $('#new_priv').select2({
			placeholder: "Select Privilege",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2b.val(null).trigger("change");
	} catch(err) {}
	try {
		var select2c = $('#new_isActive').select2({
			placeholder: "Is Active?",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2c.val(null).trigger("change");
	} catch(err) {}
	try {
		var select2d = $('#new_gender').select2({
			placeholder: "Select Gender",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2d.val(null).trigger("change");
	} catch(err) {}
	$('#admin_mod').on('click', function(e) {
		var a_id = $('input[name=admin_id]:checked').val();
		var new_priv = $('#new_priv').val();
		var new_email = $('#new_email').val();
		var new_name = $('#new_name').val();
		var new_phone = $('#new_phone').val();
		var new_password = $('#new_password').val();
		var new_city_id = $('#new_city_id').val();
		var form = '<form action="/admin/manageadmin/modify_admin" method="POST">';
		form += '<input type="hidden" name="a_id" value="' + a_id + '" />';
		form += '<input type="hidden" name="new_priv" value="' + new_priv + '" />';
		form += '<input type="hidden" name="new_name" value="' + new_name + '" />';
		form += '<input type="hidden" name="new_email" value="' + new_email + '" />';
		form += '<input type="hidden" name="new_phone" value="' + new_phone + '" />';
		form += '<input type="hidden" name="new_city_id" value="' + new_city_id + '" />';
		if(new_password != "" && new_password != null && new_password != undefined) {
			form += '<input type="hidden" name="new_password" value="' + new_password + '" />';
		}
		form += '<input type="hidden" name="new_city_id" value="' + new_city_id + '" />';
		form += '<input type="submit" name="admin_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
	$('#admin_del').on('click', function(e) {
		swal({   
			title: "Are you sure?",
			text: "You are about to delete " + delete_admin_name,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			cancelButtonText: "Cancel",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function(isConfirm) {
			if (isConfirm) {
			    swal("Deleted!", delete_admin_name + " has been deleted.", "success");
			    var form = '<form action="/admin/manageadmin/delete_admin" method="POST">';
			    form += '<input type="hidden" name="delete_admin_id" value="' + delete_admin_id + '" />';
			    form += '<input type="submit" name="admin_submit" value="submit" /></form>';
			    var created_form = $(form).appendTo('body');
			    created_form.submit();
			} else {
				swal("Cancelled", "Operation aborted.", "error");
			}
		});
	});
	$(document).on('ifChecked', 'input[name=admin_id]', function() {
		populateNewFieldsAdmin($(this));
		checkfieldAdmin();
		delete_admin_id = $(this).attr("id").substring($(this).attr("id").indexOf("_")+1);
		delete_admin_name = $(this).attr("admin-name");
		if(delete_admin_id !== null && delete_admin_name !== null) {
			$("#admin_del").removeAttr('disabled');
		} else {
			$("#admin_del").attr('disabled','disabled');
		}
	});
	$('#exec_mod').on('click', function(e) {
		var e_id = $('input[name=executive_id]:checked').val();
		var new_name = $('#new_name').val();
		var new_email = $('#new_email').val();
		var new_phone = $('#new_phone').val();
		var new_dob = $('#new_dob').val();
		var new_gender = $('#new_gender').val();
		var new_isActive = $('#new_isActive').val();
		var new_password = $('#new_password').val();
		var new_city_id = $('#new_city_id').val();
		var form = '<form action="/admin/manageexecutive/modify_executive" method="POST">';
		form += '<input type="hidden" name="e_id" value="' + e_id + '" />';
		form += '<input type="hidden" name="new_name" value="' + new_name + '" />';
		form += '<input type="hidden" name="new_email" value="' + new_email + '" />';
		form += '<input type="hidden" name="new_phone" value="' + new_phone + '" />';
		form += '<input type="hidden" name="new_dob" value="' + new_dob + '" />';
		form += '<input type="hidden" name="new_gender" value="' + new_gender + '" />';
		form += '<input type="hidden" name="new_isActive" value="' + new_isActive + '" />';
		if(new_password != "" && new_password != null && new_password != undefined) {
			form += '<input type="hidden" name="new_password" value="' + new_password + '" />';
		}
		form += '<input type="hidden" name="new_city_id" value="' + new_city_id + '" />';
		form += '<input type="submit" name="executive_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
	$('#executive_del').on('click', function(e) {
		swal({
			title: "Are you sure?",
			text: "You are about to delete " + delete_executive_name,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			cancelButtonText: "Cancel",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function(isConfirm) {
			if (isConfirm) {
			    swal("Deleted!", delete_executive_name + " has been deleted.", "success");
			    var form = '<form action="/admin/manageexecutive/delete_executive" method="POST">';
			    form += '<input type="hidden" name="delete_executive_id" value="' + delete_executive_id + '" />';
			    form += '<input type="submit" name="executive_submit" value="submit" /></form>';
			    var created_form = $(form).appendTo('body');
			    created_form.submit();
			} else {
				swal("Cancelled", "Operation aborted.", "error");
			}
		});
	});
	$(document).on('ifChecked', 'input[name=executive_id]', function() {
		populateNewFieldsExecutive($(this));
		checkfieldExecutive();
		delete_executive_id = $(this).attr("id").substring($(this).attr("id").indexOf("_")+1);
		delete_executive_name = $(this).attr("executive-name");
		if(delete_executive_id !== null && delete_executive_name !== null) {
			$("#executive_del").removeAttr('disabled');
		} else {
			$("#executive_del").attr('disabled','disabled');
		}
	});
});
function populateNewFieldsAdmin(element) {
	document.getElementById('new_email').value = element.attr("admin-email");
	document.getElementById('new_name').value = element.attr("admin-name");
	document.getElementById('new_phone').value = element.attr("admin-phone");
	if($('#new_city_id').data('type') == 'select') {
		$("#new_city_id").select2("val", element.attr("admin-city"));
	} else {
		$("#new_city_id").val(element.attr("admin-city"));
	}
	$("#new_priv").select2("val", element.attr("admin-privilege"));
}
function checkfieldAdmin() {
	var x = 0;
	var new_priv = $('#new_priv').val();
	var new_name = $('#new_name').val();
	var new_email = $('#new_email').val();
	var new_phone = $('#new_phone').val();
	var new_password = $('#new_password').val();
	var new_city_id = $('#new_city_id').val();
	var a_id = $('input[name=admin_id]:checked').val();
	if(new_priv == "" || new_priv == null || new_priv == undefined) {
		x = 1;
	}
	if(new_name == "" || new_name == null || new_name == undefined) {
		x = 1;
	}
	if(new_email == "" || new_email == null || new_email == undefined) {
		x = 1;
	}
	if(new_phone == "" || new_phone == null || new_phone == undefined) {
		x = 1;
	}
	if(new_city_id == "" || new_city_id == null || new_city_id == undefined) {
		x = 1;
	}
	if(typeof a_id == "undefined" || a_id == "") {
		x = 1;
	}
	if (new_phone < 7000000000 || new_phone > 9999999999 || isNaN(new_phone)) {
		x = 1;
	}
	if(!IsEmail(new_email)) {
		x = 1;
	}
	if(x == 0) {
		$("#admin_mod").removeAttr('disabled');
	} else {
		$("#admin_mod").attr('disabled','disabled');
	}
}
function populateNewFieldsExecutive(element) {
	document.getElementById('new_dob').value = element.attr("executive-dob");
	document.getElementById('new_email').value = element.attr("executive-email");
	document.getElementById('new_name').value = element.attr("executive-name");
	document.getElementById('new_phone').value = element.attr("executive-phone");
	$("#new_gender").select2("val", element.attr("executive-gender"));
	$("#new_isActive").select2("val", element.attr("executive-isactive"));
	if($('#new_city_id').data('type') == 'select') {
		$("#new_city_id").select2("val", element.attr("executive-city"));
	} else {
		$("#new_city_id").val(element.attr("admin-city"));
	}
}
function checkfieldExecutive() {
	var x = 0;
	var new_name = $('#new_name').val();
	var new_email = $('#new_email').val();
	var new_phone = $('#new_phone').val();
	var new_dob = $('#new_dob').val();
	var new_gender = $('#new_gender').val();
	var new_city_id = $('#new_city_id').val();
	var new_isActive = $('#new_isActive').val();
	var new_password = $('#new_password').val();
	var e_id = $('input[name=executive_id]:checked').val();
	if(new_name == "" || new_name == null || new_name == undefined) {
		x = 1;
	}
	if(new_email == "" || new_email == null || new_email == undefined) {
		x = 1;
	}
	if(new_phone == "" || new_phone == null || new_phone == undefined) {
		x = 1;
	}
	if(new_dob == "" || new_dob == null || new_dob == undefined) {
		x = 1;
	}
	if(new_gender == "" || new_gender == null || new_gender == undefined) {
		x = 1;
	}
	if(new_isActive == "" || new_isActive == null || new_isActive == undefined) {
		x = 1;
	}
	if(new_city_id == "" || new_city_id == null || new_city_id == undefined) {
		x = 1;
	}
	if(typeof e_id == "undefined" || e_id == "") {
		x = 1;
	}
	if (new_phone < 7000000000 || new_phone > 9999999999 || isNaN(new_phone)) {
		x = 1;
	}
	if(!IsEmail(new_email)) {
		x = 1;
	}
	if(x == 0) {
		$("#exec_mod").removeAttr('disabled');
	} else {
		$("#exec_mod").attr('disabled','disabled');
	}
}
function IsEmail(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}