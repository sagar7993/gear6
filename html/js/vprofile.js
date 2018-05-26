var availableLocations;
$(function () {
	$('.mediaUpload').change(function() {
		var preview_id = $(this).attr('data-id');
		var oFReader = new FileReader();
		oFReader.readAsDataURL($('#' + this.id)[0].files[0]);
		$('#nii' + preview_id).hide();
		oFReader.onload = function(oFREvent) {
			$("#uploadPreview" + preview_id)[0].src = oFREvent.target.result;
			$("#mediaSubmitBtn").removeAttr('disabled');
		};
	});
	$("#SubmitBtn").on('click', function() {
		var sc_name = $("#sc_name").val();
		var addr1 = $('#addr1').val();
		var addr2 = $('#addr2').val();
		var location = $("#addr_location").val();
		var landmark = $("#addr_landmark").val();
		var owner_name = $("#owner_name").val();
		var email = $("#email").val();
		var phNum = $("#phNum").val();
		var landline = $("#landline").val();
		var lat_num = $("#lat_num").val();
		var lon_num = $("#lon_num").val();
		var city = $("#city").val();
		var addr = $("#sc_addr_slit_id").val();
		var altphNum = $("#altphNum").val();
		var form_input_array = ["sc_name", "addr1", "addr2", "location", "landmark", "owner_name", "email", "phNum", "landline", "lat_num", "lon_num", "city", "addr", "altphNum"];
		var input_objects = {sc_name: sc_name, addr1: addr1, addr2: addr2, location: location, landmark: landmark, owner_name: owner_name, email: email, phNum: phNum, landline: landline, lat_num: lat_num, lon_num: lon_num, city: city, addr: addr, altphNum:altphNum};
		var form = '<form action="/vendor/profile/updateScDetails" method="POST">';
		for (i = 0; i < form_input_array.length; i++) {
			form += '<input type="hidden" name="' + form_input_array[i] + '" value="' + input_objects[form_input_array[i]] + '" />';
		}
		form += '<input type="submit" name="addrSubmit" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
	$(document).on('click', '.edit_field', function() {
		$id = $(this).attr('id').split('_');
		if($id[1] != "") {
			$this_id = $id[0]+'_'+$id[1];
		} else {
			$this_id = $id[0];
		}
		$('#'+$this_id).attr('readonly',false );
		$('#'+$this_id).removeClass('grey-bg');
		$('#'+$this_id).focus();
	});
	$(document).on('click', '.edit_lat_lon', function() {
		$('#lat_num').attr('readonly',false );
		$('#lon_num').attr('readonly',false );
		$('#lat_num').removeClass('grey-bg');
		$('#lon_num').removeClass('grey-bg');
		$('#lat_num').focus();
	});
	$('#add-new-addr').on('click', function() {
		$('#cityselection').show();
		$('#addr1').attr('readonly', false);
		$('#addr2').attr('readonly', false);
		$('#addr_location').attr('readonly', false);
		$('#addr_landmark').attr('readonly', false);
		$('#addr1').focus();
	});
	$('#CancelBtn').on('click', function() {
		insertAddressValues();
		makeAddrReadOnly();
		$('#cityselection').hide();
		cityChanged(true);
	});
	$(".imgInp").on('change', function() {
		$id = $(this).attr('id');
		readURL(this, $id);
	});
	insertAddressValues();
	cityChanged(true);
});
function checkfield() {
	var str1 = new Array(10);
	str1[0] = $("#addr1").val();
	str1[1] = $("#addr2").val();
	str1[2] = $("#addr_location").val();
	str1[3] = $("#sc_name").val();
	str1[4] = $("#owner_name").val();
	str1[5] = $("#email").val();
	str1[6] = $("#phNum").val();
	str1[8] = $("#lat_num").val();
	str1[9] = $("#lon_num").val();
	var x = 0;
	for (var i = 0; i < 10; i++) {
		if(str1[i] === null || str1[i] == '') {
			x = 1;
		}
	}
	if (str1[6] < 7000000000 || str1[6] > 9999999999 || isNaN(str1[6])) {
		x = 1;
	}
	if(!IsEmail(str1[5])) {
		x = 1;
	}
	if(!isValidLocation(str1[2])) {
		x = 1;
	}
	if(x == 0) {
		$("#SubmitBtn").removeAttr('disabled');
	} else {
		$("#SubmitBtn").attr('disabled','disabled');
	}
}
function cityChanged(once) {
	once = typeof once !== 'undefined' ? once : false;
	var changed_city = $('#city').val();
	$.ajax({
		type: "POST",
		url: "/vendor/profile/getCityLocations",
		data: {city: changed_city},
		dataType: "json",
		cache:false,
		success: function(data) {
			var temp_array;
			if(data !== null) {
				var temp_array = $.map(data, function(value, index) {
					return [value];
				});
			}
			availableLocations = temp_array;
			var location_array;
			if (typeof availableLocations === "undefined") {
				location_array = [];
			} else {
				location_array = availableLocations;
			}
			$( "#addr_location" ).autocomplete ({
				source: location_array,
				select: function (a, b) {
					$("#addr_location").val(b.item.value);
					checkfield();
				},
				response: function(event, ui) {
					if (ui.content.length === 0) {
						alert("No results found");
					}
				}
			});
			checkfield();
			if(once) {
				$("#SubmitBtn").attr('disabled','disabled');
			}
		}
	});
}
function readURL(input,$id) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('#'+$id+'Thumb').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}
function insertAddressValues() {
	$('#addr1').val($('#addr_content :first-child').text());
	$('#addr2').val($('#addr_content :nth-child(2)').text());
	$('#addr_location').val($('#addr_content :nth-child(3)').text());
	$('#addr_landmark').val($('#addr_content :nth-child(4)').text());
	$('#city').val($('#addr_content :nth-child(5)').text());
	$('#sc_name').val($('#addr_content :nth-child(6)').text());
	$('#owner_name').val($('#addr_content :nth-child(7)').text());
	$('#email').val($('#addr_content :nth-child(8)').text());
	$('#landline').val($('#addr_content :nth-child(9)').text());
	$('#phNum').val($('#addr_content :nth-child(10)').text());
	$("#lat_num").val($('#addr_content :nth-child(11)').text());
	$("#lon_num").val($('#addr_content :nth-child(12)').text());
	$("#sc_addr_slit_id").val($('#addr_content :nth-child(13)').text());
	$("#altphNum").val($('#addr_content :nth-child(14)').text());
}
function makeAddrReadOnly() {
	$('#addr1').attr('readonly', true);
	$('#addr2').attr('readonly', true);
	$('#addr_location').attr('readonly', true);
	$('#addr_landmark').attr('readonly', true);
	$('#sc_name').attr('readonly', true);
	$('#sc_name').addClass('grey-bg');
	$('#owner_name').attr('readonly', true);
	$('#owner_name').addClass('grey-bg');
	$('#email').attr('readonly', true);
	$('#email').addClass('grey-bg');
	$('#landline').attr('readonly', true);
	$('#landline').addClass('grey-bg');
	$('#phNum').attr('readonly', true);
	$('#phNum').addClass('grey-bg');
	$('#lat_num').attr('readonly', true);
	$('#lat_num').addClass('grey-bg');
	$('#lon_num').attr('readonly', true);
	$('#lon_num').addClass('grey-bg');
	$("#altphNum").attr('readonly', true);
	$("#altphNum").addClass('grey-bg');
}
function isValidLocation(code) {
	if((typeof availableLocations !== "undefined") && (availableLocations.length > 0)) {
		return ($.inArray(code, availableLocations) > -1);
	} else {
		return false;
	}
}
function IsEmail(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}