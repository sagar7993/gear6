if(typeof univ_order_id === "undefined") {
	var univ_order_id = "";
}
$(document).on({
	ajaxStart: function() { $('.load-wrap').show();
		$('html').addClass('no-scroll'); },
	ajaxStop: function() { $('.load-wrap').hide();
		$('html').removeClass('no-scroll'); }
});
$(document).ready(function() {
	setInitialStatuses();
	$('.trackOrder').on('click', function(e) {
		e.preventDefault();
		var oid = $(this).data('oid');
		var form = '<form method="POST" action="/user/account/corders"><input name="oid" value="' + oid + '" /><input type="submit" name="chactorder" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
	$("#cfrmAmount").on('click', function() {
		var form = '<form method="POST" action="/user/account/approvePrice/"><input name="oid" value="' + univ_order_id + '" /><input name="status" value="2" /><input type="submit" name="priceupdate" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
	$("#chacphsotp").on('click', function() {
		var ph = $('#chac_phone').val();
		if(isValidPhone(ph)) {
			if(ph == $('#user_phone').val()) {
				$('#chacphmsg').show('slow');
				$('#chacphmsg').html('This is the current number. Change it!!');
			} else {
				$('#chacphmsg').hide('slow');
				makeAjaxCallForOtp(ph);
			}
		} else {
			$('#chacphmsg').show('slow');
			$('#chacphmsg').html('Phone number is not valid.');
		}
	});
	$("#pordertxn").on('click', function() {
		var paymtgtw = parseInt($('input[name="paymtGtw"]:checked').val());
		var to_be_paid = parseInt(parseFloat($('#yet_to_be_paid').val()).toFixed(2) * 100);
		if(to_be_paid >= 3000) {
			to_be_paid = parseInt(to_be_paid + to_be_paid * 0.02);
		}
		if(paymtgtw == 2) {
			var to_be_paid_f = parseFloat($('#yet_to_be_paid').val()).toFixed(2);
			to_be_paid = to_be_paid_f;
		}
		$.ajax({
			type: "POST",
			url: "/user/account/initiatePorderTrxn",
			data: {oid: univ_order_id, paymtgtw: paymtgtw, to_be_paid: to_be_paid},
			dataType: "json",
			cache: false,
			success: function(data) {
				if(paymtgtw == 2) {
					var created_form = $(data.html).appendTo('body');
					created_form.submit();
				} else if(paymtgtw == 1) {
					var rp_options = {
						"key": "rzp_live_1SU5AHKZFVCfcO",
						"amount": to_be_paid,
						"name": "NewEin Technologies Private Limited",
						"description": univ_order_id,
						"image": "https://www.gear6.in/img/social_logo.png",
						"handler": function (response) {
							var created_form = $('<form method="POST" action="/user/result/showRZPayStatus"><input type="hidden" name="paymt_txn_id" value="' + response.razorpay_payment_id + '"><input type="hidden" name="oid" value="' + univ_order_id + '"><input type="hidden" name="amount" value="' + to_be_paid + '"></form>').appendTo('body');
							created_form.submit();
						},
						"prefill": {
							"name": data.name,
							"email": data.email,
							"contact": data.phone
						},
						"notes": {
							"oid": univ_order_id
						},
						"theme": {
							"color": "#028CBC",
							"close_button": true
						},
						"modal": {
							"ondismiss": function() {
								throw new Error('This is not an error. This is just to abort javascript');
							}
						}
					};
					var rzp1 = new Razorpay(rp_options);
					rzp1.open();
				}
			}
		});
	});
	$('.status-hover').hover(function() {
		var id = $(this).attr('id').split('-')[2];
		var pre = $(this).attr('id').split('-')[0];
		var count = 0;
		if(pre == 'p' || pre == 'r') {
			count = 4;
		} else if (pre == 'q') {
			var test = $(this).attr('id').split('-')[2];
			var test1 = $(this).attr('id').split('-')[3];
			for(var i = 1; i <= 3; i++) {
				if (i == test1) {
					$('#' + pre + '-status-hover-block-' + test + '-' + i).css('display','block');
					$('.chevron-' + test + '-' + i).css('display', 'inline-block');
				} else {
					$('#' + pre + '-status-hover-block-' + test + '-' + i).css('display','none');
					$('.chevron-' + test + '-' + i).css('display', 'none');
				}
			}
		} else {
			count = 3;
		}
		for(var i = 1; i <= count; i++) {
			if (i == id) {
				$('#' + pre + '-status-hover-block-' + id).css('display','block');
				$('.chevron-'+id).css('display','inline-block');
			} else {
				$('#' + pre + '-status-hover-block-' + i).css('display','none');
				$('.chevron-'+i).css('display','none');
			}
		}
	});
	var icons = {
		header : "accordionFilterHeader accordionFilterHeaderExapndIcon",
		activeHeader : "accordionFilterHeader accordionFilterHeaderCollapseIcon"
	};
	$("#accordion1").accordion({
		autoHeight : false,
		navigation : true,
		active : 0,
		animate : 100,
		collapsible : true,
		speed : "slow",
		icons : icons,
		header : 'h3'
	});
	$("#cslide-slides").cslide();
	$('#addr-block-open').on('click', function() {
		$('#addr-select-container').slideToggle("slow", function() {
			$('html, body').animate({ scrollTop: $("#addr-select-container").offset().top }, 1000);
		});
	});
	$('#add-new-addr').on('click', function() {
		addNewAddress();
		$('#cityselection').show();
		checkfield();
	});
	$('#addrCancel').on('click', function() {
		insertAddressValues('addr_1');
		makeAddrReadOnly();
		$('#cityselection').hide();
		$("#sc_name").attr('readonly', true);
		$("#sc_name").val(univ_username);
		$('#sc_name').addClass('white-bg');
		$('#addr_1').icheck('checked');
		$('#addr-select-container').slideUp("slow", function() {$('html, body').animate({scrollTop: $("#scroll-top-content").offset().top}, 500);});
		cityChanged(true);
		checkfield();
	});
	$('.edit_pwd').on('click', function() {
		$('#new-pwd-block').css('display','block');
		$('#old_pwd').attr('placeholder','Old Password');
		$('#old_pwd').attr('readonly', false);
		$('#old_pwd').removeClass('white-bg');
		$('#old_pwd').focus();
		is_pwd_changing = true;
	});
	$('#pwdCancel').on('click', function() {
		if(univ_login_mode == 'Email') {
			$('#new-pwd-block').css('display','none');
			$('#old_pwd').attr('placeholder','##########');
			$('#old_pwd').val("");
			$('#old_pwd').attr('readonly', true);
			$('#old_pwd').addClass('white-bg');
			$("#pwdSubmit").attr('disabled','disabled');
		}
		$('#email_id').attr('readonly', true);
		$("#email_id").val(univ_email);
		$('#email_id').addClass('white-bg');
		is_pwd_changing = false;
	});
	var id;
	$('.addr_radio').on('ifChecked', function() {
		id = $(this).attr('id');
		insertAddressValues(id);
		checkfield();
		$('#addr-select-container').slideToggle("slow",function() {$('html, body').animate({scrollTop: $("#scroll-top-content").offset().top}, 500);});
		makeAddrReadOnly();
		$('#cityselection').hide();
	});
	$(document).on('click', '.edit_field', function() {
		$id = $(this).attr('id').split('_');
		$this_id = $id[0]+'_'+$id[1];
		$('#'+$this_id).attr('readonly', false );
		$('#'+$this_id).removeClass('white-bg');
		$('#'+$this_id).focus();
	});
	$(".imgInp").on('change', function() {
		$id = $(this).attr('id');
		readURL(this, $id);
	});
	$("#addrSubmit").on('click', function() {
		var full_name = $("#sc_name").val();
		if($('input[name="addr"]:checked').length > 0) {
			var addr = $('input[name="addr"]:checked').val();
		} else {
			var addr = "";
			var adln1 = $("#addr1").val();
			var adln2 = $("#addr2").val();
			var landmark = $("#addr_landmark").val();
			var location = $("#addr_location").val();
		}
		var form_input_array = ["full_name", "addr", "adln1", "adln2", "location", "landmark"];
		var input_objects = {full_name: full_name, addr: addr, adln1: adln1, adln2: adln2, location: location, landmark: landmark};
		var form = '<form action="/user/account/updateDefaultAddress" method="POST">';
		for (i = 0; i < form_input_array.length; i++) {
			form += '<input type="hidden" name="' + form_input_array[i] + '" value="' + input_objects[form_input_array[i]] + '" />';
		}
		form += '<input type="submit" name="addrSubmit" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
	$("#pwdSubmit").on('click', function() {
		if(is_pwd_changing) {
			var pwd = $("#old_pwd").val();
			var pswd1 = $("#new_pwd").val();
			var pswd2 = $("#confirm_new_pwd").val();
			var email = $("#email_id").val();
			var form_input_array = ["email", "pwd", "pswd1", "pswd2"];
			var input_objects = {email: email, pwd: pwd, pswd1: pswd1, pswd2: pswd2};
		} else {
			var email = $("#email_id").val();
			var form_input_array = ["email"];
			var input_objects = {email: email};
		}
		var form = '<form action="/user/account/updateEmailPwd" method="POST">';
		for (i = 0; i < form_input_array.length; i++) {
			form += '<input type="hidden" name="' + form_input_array[i] + '" value="' + input_objects[form_input_array[i]] + '" />';
		}
		form += '<input type="submit" name="pwdSubmit" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
	if((typeof page_id !== "undefined") && (page_id == 1 || page_id == '1')) {
		insertAddressValues('addr_1');
		cityChanged(true);
	}
});
(function($) {
	$.fn.cslide = function() {
		this.each(function() {
			var slidesContainerId = "#"+($(this).attr("id"));
			var len = $(slidesContainerId+" .cslide-slide").size();
			var slidesContainerWidth = len*100+"%";
			var slideWidth = (100/len)+"%";
			$(slidesContainerId+" .cslide-slides-container").css({
				width : slidesContainerWidth,
				visibility : "visible"
			});
			$(".cslide-slide").css({
				width : slideWidth
			});
			$(slidesContainerId+" .cslide-slides-container .cslide-slide").last().addClass("cslide-last");
			$(slidesContainerId+" .cslide-slides-container .cslide-slide").first().addClass("cslide-first cslide-active");
			$(slidesContainerId+" .cslide-prev-ua").addClass("cslide-disabled");
			if (!$(slidesContainerId+" .cslide-slide.cslide-active.cslide-first").hasClass("cslide-last")) {           
				$(slidesContainerId+" .cslide-prev-ua-next").css({
					display : "block"
				});
			}
			$(slidesContainerId+" .cslide-next-ua").on('click', function() {
				var i = $(slidesContainerId+" .cslide-slide.cslide-active").index();
				var n = i+1;
				var slideLeft = "-"+n*100+"%";
				if (!$(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-last")) {
					$(slidesContainerId+" .cslide-slide.cslide-active").removeClass("cslide-active").next(".cslide-slide").addClass("cslide-active");
					$(slidesContainerId+" .cslide-slides-container").animate({
						marginLeft : slideLeft
					},250);
					if ($(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-last")) {
						$(slidesContainerId+" .cslide-next-ua").addClass("cslide-disabled");
					}
				}
				if ((!$(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-first")) && $(".cslide-prev-ua").hasClass("cslide-disabled")) {
					$(slidesContainerId+" .cslide-prev-ua").removeClass("cslide-disabled");
				}
			});
			$(slidesContainerId+" .cslide-prev-ua").on('click', function() {
				var i = $(slidesContainerId+" .cslide-slide.cslide-active").index();
				var n = i-1;
				var slideRight = "-"+n*100+"%";
				if (!$(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-first")) {
					$(slidesContainerId+" .cslide-slide.cslide-active").removeClass("cslide-active").prev(".cslide-slide").addClass("cslide-active");
					$(slidesContainerId+" .cslide-slides-container").animate({
						marginLeft : slideRight
					},250);
					if ($(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-first")) {
						$(slidesContainerId+" .cslide-prev-ua").addClass("cslide-disabled");
					}
				}
				if ((!$(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-last")) && $(".cslide-next-ua").hasClass("cslide-disabled")) {
					$(slidesContainerId+" .cslide-next-ua").removeClass("cslide-disabled");
				}
			});
		});
		return this;
	}
}(jQuery));
function setInitialStatuses() {
	if($('.status-inactive').length > 0) {
		var stype = $('.status-inactive').first().parent().attr('id').split('-')[0];
	} else if($('.status-active').length > 0) {
		var stype = $('.status-active').first().parent().attr('id').split('-')[0];
	}
	if(stype != 'q') {
		var active = 0;
		$.each($('.status-active'), function() {
			active += 1;
		});
		if(active == 0) {
			$('.chevron-1').css('display', 'inline-block');
		} else {
			$('.chevron-' + active).css('display', 'inline-block');
		}
	} else {
		for(var i = 1; i <= 3; i++) {
			var active = 0;
			$.each($('.status-active'), function() {
				var temp = $(this).parent().attr('id').split('-');
				if(temp[2] == i) {
					active += 1;
				}
			});
			if(active == 0) {
				$('.chevron-' + i + '-1').css('display', 'inline-block');
			} else {
				$('.chevron-' + i + '-' + active).css('display', 'inline-block');
			}
		}
	}
}
function makeAjaxCallForOtp(phNum) {
	$.ajax({
		type: "POST",
		url: "/user/account/update_phone_otp",
		data: {phNum: phNum},
		dataType: "json",
		cache: false,
		success: function(data) {
			if (data.err) {
				$('#chacphmsg').show('slow');
				$('#chacphmsg').html(data.err);
			} else {
				$('#chac_phone_edit').hide('slow');
				$("#chacphsotp").html('ReSend OTP');
				$('#chac_phone').attr('readonly', 'readonly');
				$('#chacphotpcont').show('slow');
			}
		}
	});
}
function checkfield() {
	var e1 = $("#sc_name").val();
	var e4 = $("#addr1").val();
	var e5 = $("#addr2").val();
	var e6 = $("#addr_location").val();
	var x = 0;
	var e8 = 1;
	if(e1.length <= 2 || e4.length <= 2 || e5.length <= 2 || e6.length <= 2) {
		e8 = 0;
	}
	if (!isValidLocation(e6)) {
		e8 = 0;
	}
	if(e8 == 0) {
		x = 1;
	}
	if(x == 0) {
		$("#addrSubmit").removeAttr('disabled');
	} else {
		$("#addrSubmit").attr('disabled','disabled');
	}
}
function checkfield1() {
	var x = 0;
	var e8 = 1;
	var e1 = $("#email_id").val();
	if(is_pwd_changing) {
		var e4 = $("#old_pwd").val();
		var e5 = $("#new_pwd").val();
		var e6 = $("#confirm_new_pwd").val();
		if(e4.length <= 2 || e5.length <= 2 || e6.length <= 2 || e5 != e6) {
			e8 = 0;
		}
	}
	if(e1.length <= 2) {
		e8 = 0;
	}
	if (!IsEmail(e1)) {
		e8 = 0;
	}
	if(e8 == 0) {
		x = 1;
	}
	if(x == 0) {
		$("#pwdSubmit").removeAttr('disabled');
	} else {
		$("#pwdSubmit").attr('disabled','disabled');
	}
}
function checkcancel() {
	var e1 = $("#can_reason").val();
	var x = 0;
	if(e1.length <= 5) {
		x = 1;
	}
	if(x == 0) {
		$("#can_submit").removeAttr('disabled');
		$('#can_order_id').val(univ_order_id);
	} else {
		$("#can_submit").attr('disabled','disabled');
	}
}
function checkreschedule() {
	var e1 = $("#rs_date").val();
	var e2 = $("#rs_reason").val();
	var x = 0;
	if(e1 == "" || e2.length <= 5) {
		x = 1;
	}
	if(x == 0) {
		$("#rs_submit").removeAttr('disabled');
		$('#rs_order_id').val(univ_order_id);
	} else {
		$("#rs_submit").attr('disabled','disabled');
	}
}
function cityChanged(once) {
	once = typeof once !== "undefined" ? once : false;
	var changed_city = $('#city').val();
	$.ajax({
		type: "POST",
		url: "/user/account/getCityLocations",
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
			$("#addr_location").autocomplete ({
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
				$("#addrSubmit").attr('disabled','disabled');
			}
		}
	});
}
function addNewAddress() {
	$('#addr-block :input').each(function() {
		$(this).val("");
		$(this).attr('readonly',false);
	});
	$('.addr_radio').each(function() {
		$(this).icheck('unchecked');
	});
	$('#addr1').focus();
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
function makeAddrReadOnly() {
	$('#addr1').attr('readonly', true);
	$('#addr2').attr('readonly', true);
	$('#addr_location').attr('readonly', true);
	$('#addr_landmark').attr('readonly', true);
}
function insertAddressValues(id) {
	$('#addr1').val($('#addr_content_'+id.split("_")[1]+' :first-child').text());
	$('#addr2').val($('#addr_content_'+id.split("_")[1]+' :nth-child(2)').text());
	$('#addr_location').val($('#addr_content_'+id.split("_")[1]+' :nth-child(3)').text());
	$('#addr_landmark').val($('#addr_content_'+id.split("_")[1]+' :nth-child(4)').text());
	$('#city').val($('#addr_content_'+id.split("_")[1]+' :nth-child(5)').text());
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
isValidPhone = function(phNum) {
	phNum = Number(phNum);
	if (phNum == "" || phNum < 7000000000 || phNum > 9999999999 || isNaN(phNum)) {
		return false;
	}
	return true;
}