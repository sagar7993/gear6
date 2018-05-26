$(function() {
	$('#dob').datepicker ({
		'dateFormat': "yy-mm-dd",
		'minDate': new Date(1940, 1 - 1, 1),
		'maxDate': new Date(2001, 1 - 1, 1),
		'autoclose': true
	});
	$('#vend_add').on('click', function(e) {
		var sc_id  = $("#sc_id").val();
		var gender  = $("#gender").val();
		var upriv  = $("#upriv").val();
		var fname  = $("#fname").val();
		var dob  = $("#dob").val();
		var phone  = $("#p_phone").val();
		var email  = $("#email").val();
		var address  = $("#address").val();
		var alt_ph  = $("#alt_ph").val();
		var form_input_array = ["sc_id", "gender", "upriv", "fname", "dob", "phone", "email", "address", "alt_ph"];
		var input_objects = {sc_id: sc_id, gender: gender, upriv: upriv, fname: fname, dob: dob, phone: phone, email: email, address: address, alt_ph: alt_ph};
		var form = '<form action="/admin/mvendors/create_vendor" method="POST">';
		for (i = 0; i < form_input_array.length; i++) {
			form += '<input type="hidden" name="' + form_input_array[i] + '" value="' + input_objects[form_input_array[i]] + '" />';
		}
		form += '<input type="submit" name="order_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
	$(".imgInp").change(function() {
		$id = $(this).attr('id');
		readURL(this,$id);
	});
});
function readURL(input, $id) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('#' + $id + 'Thumb').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}
function checkfield() {
	var str1 = new Array(8);
	str1[0] = $("#sc_id").val();
	str1[1] = $("#gender").val();
	str1[2] = $("#upriv").val();
	str1[3] = $("#fname").val();
	str1[4] = $("#dob").val();
	str1[5] = $("#p_phone").val();
	str1[6] = $("#email").val();
	str1[7] = $("#address").val();
	var x = 0;
	for (var i = 0; i < str1.length; i++) {
		if(str1[i] === null || str1[i] == '') {
			x = 1;
		}
	}
	if (str1[5] < 7000000000 || str1[5] > 9999999999 || isNaN(str1[5])) {
		x = 1;
	}
	if(!IsEmail(str1[6])) {
		x = 1;
	}
	if(x == 0) {
		$("#vend_add").removeAttr('disabled');
	} else {
		$("#vend_add").attr('disabled','disabled');
	}
}
function IsEmail(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}
function get_scs() {
	var city_id = $("#city_id").val();
	$.ajax({
		type: "POST",
		url: "/admin/mvendors/get_scs_for_city",
		data: {city_id: city_id},
		dataType: "text",
		cache: false,
		success: function(data) {
			$('#sc_id').html(data);
			var select2b = $('#sc_id').select2({
				placeholder: "Service Center",
				minimumResultsForSearch: 10,
				containerCssClass: "cityCombo12"
			});
			select2b.val(null).trigger("change");
		}
	});
}