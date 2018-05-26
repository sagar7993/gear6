$(function() {
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
	try {
		$('#dob').datepicker ({
			'dateFormat': "yy-mm-dd",
			'minDate': new Date(1940, 1 - 1, 1),
			'maxDate': new Date(2001, 1 - 1, 1),
			"setDate": new Date(),
			'autoclose': true
		});
	} catch(err) {}
	try {
		var select2a = $('#upriv').select2({
			placeholder: "Select Privilege",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2a.val(null).trigger("change");
	} catch(err) {}
	try {
		if($('#city_id').data('type') == 'select') {
			var select2b = $('#city_id').select2({
				placeholder: "Select City",
				minimumResultsForSearch: 10,
				containerCssClass: "cityCombo12"
			});
			select2b.val(null).trigger("change");
		}
	} catch(err) {}
	try {
		var select2c = $('#isActive').select2({
			placeholder: "Is Active?",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2c.val(null).trigger("change");
	} catch(err) {}	
	try {
		var select2d = $('#gender').select2({
			placeholder: "Select Gender",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2d.val(null).trigger("change");
	} catch(err) {}
	$('#admin_add').on('click', function(e) {
		var city_id  = $("#city_id").val();
		var upriv  = $("#upriv").val();
		var fname  = $("#fname").val();
		var phone  = $("#p_phone").val();
		var email  = $("#email").val();
		var password  = $("#p_password").val();
		var form_input_array = ["city_id", "upriv", "fname", "phone", "email", "password"];
		var input_objects = {city_id: city_id, upriv: upriv, fname: fname, phone: phone, email: email, password: password};
		var form = '<form action="/admin/manageadmin/create_admin" method="POST">';
		for (i = 0; i < form_input_array.length; i++) {
			form += '<input type="hidden" name="' + form_input_array[i] + '" value="' + input_objects[form_input_array[i]] + '" />';
		}
		form += '<input type="submit" name="order_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
	$('#executive_add').on('click', function(e) {
		var fname  = $("#fname").val();
		var city_id = $("#city_id").val();
		var phone  = $("#p_phone").val();
		var email  = $("#email").val();
		var password  = $("#p_password").val();
		var dob  = $("#dob").val();
		var gender  = $("#gender").val();
		var isActive  = $("#isActive").val();
		var form_input_array = ["city_id", "fname", "phone", "email", "password", "dob", "gender", "isActive"];
		var input_objects = {city_id: city_id, fname: fname, phone: phone, email: email, password: password, dob: dob, gender: gender, isActive: isActive};
		var form = '<form action="/admin/manageexecutive/create_executive" method="POST">';
		for (i = 0; i < form_input_array.length; i++) {
			form += '<input type="hidden" name="' + form_input_array[i] + '" value="' + input_objects[form_input_array[i]] + '" />';
		}
		form += '<input type="submit" name="order_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
});
function checkfieldAdmin() {
	var str1 = new Array(6);
	str1[0] = $("#city_id").val();
	str1[1] = $("#upriv").val();
	str1[2] = $("#fname").val();
	str1[3] = $("#p_phone").val();
	str1[4] = $("#email").val();
	str1[5] = $("#p_password").val();
	var x = 0;
	for (var i = 0; i < str1.length; i++) {
		if(str1[i] == null || str1[i] == undefined || str1[i] == '') {
			x = 1;
		}
	}
	if (str1[3] < 7000000000 || str1[3] > 9999999999 || isNaN(str1[3])) {
		x = 1;
	}
	if(!IsEmail(str1[4])) {
		x = 1;
	}
	if(x == 0) {
		$("#admin_add").removeAttr('disabled');
	} else {
		$("#admin_add").attr('disabled','disabled');
	}
}
function checkfieldExecutive() {
	var str1 = new Array(7);
	str1[0] = $("#dob").val();
	str1[1] = $("#gender").val();
	str1[2] = $("#fname").val();
	str1[3] = $("#p_phone").val();
	str1[4] = $("#email").val();
	str1[5] = $("#p_password").val();
	str1[6] = $("#isActive").val();
	str1[7] = $("#city_id").val();
	var x = 0;
	for (var i = 0; i < str1.length; i++) {
		if(str1[i] == null || str1[i] == undefined || str1[i] == '') {
			x = 1;
		}
	}
	if (str1[3] < 7000000000 || str1[3] > 9999999999 || isNaN(str1[3])) {
		x = 1;
	}
	if(!IsEmail(str1[4])) {
		x = 1;
	}
	if(x == 0) {
		$("#executive_add").removeAttr('disabled');
	} else {
		$("#executive_add").attr('disabled','disabled');
	}
}
function IsEmail(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}