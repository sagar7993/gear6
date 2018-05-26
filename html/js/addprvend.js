$(function() {
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
	var select2b = $('#sc_id').select2({
		placeholder: "Service Center",
		minimumResultsForSearch: 10,
		containerCssClass: "cityCombo12",
	});
	select2b.val(null).trigger("change");
	var select2c = $('#gender').select2({
		placeholder: "Gender",
		minimumResultsForSearch: 10,
		containerCssClass: "cityCombo12"
	});
	select2c.val(null).trigger("change");
	$('#vend_add').on('click', function(e) {
		var sc_id  = $("#sc_id").val().join(", ");
		var gender  = $("#gender").val();
		var fname  = $("#fname").val();
		var password  = $("#password").val();
		var phone  = $("#p_phone").val();
		var email  = $("#email").val();
		var address  = $("#address").val();
		var alt_ph  = $("#alt_ph").val();
		var form_input_array = ["sc_id", "gender", "fname", "password", "phone", "email", "address", "alt_ph"];
		var input_objects = {sc_id: sc_id, gender: gender, fname: fname, password: password, phone: phone, email: email, address: address, alt_ph: alt_ph};
		var form = '<form action="/admin/vendors/create_prvendor" method="POST">';
		for (i = 0; i < form_input_array.length; i++) {
			form += '<input type="hidden" name="' + form_input_array[i] + '" value="' + input_objects[form_input_array[i]] + '" />';
		}
		form += '<input type="submit" name="order_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body'); created_form.submit();
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
	var str1 = new Array(7);
	str1[0] = $("#sc_id").val();
	str1[1] = $("#gender").val();
	str1[2] = $("#fname").val();
	str1[3] = $("#password").val();
	str1[4] = $("#p_phone").val();
	str1[5] = $("#email").val();
	str1[6] = $("#address").val();
	var x = 0;
	for (var i = 0; i < str1.length; i++) {
		if(str1[i] == null || str1[i] == undefined || str1[i] == '') {
			x = 1;
		}
	}
	if (str1[4] < 7000000000 || str1[4] > 9999999999 || isNaN(str1[4])) {
		x = 1;
	}
	if(!IsEmail(str1[5])) {
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
