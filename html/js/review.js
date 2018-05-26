var valid_mimes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/ogg', 'video/webm'];
var is_valid_media = true;
var is_files_uploaded = false;
var is_otp_verified = false;
$(window).load(function() {
	$('#loader-gif').hide();
	$('html').removeClass('no-scroll');
});
$("input[type='checkbox'], input[type='radio']").icheck({
	checkboxClass: 'icheckbox_square-green',
	radioClass: 'iradio_square-green'
});
$(document).on({
	ajaxStart: function() { $('#loader-gif').show();
		$('html').addClass('no-scroll'); },
	ajaxStop: function() { $('#loader-gif').hide();
		$('html').removeClass('no-scroll'); }
});
$(function() {
	$('#confeecal').on('click', function() {
		var kms = parseFloat($('#schomedist').val());
		var mins = parseInt($('input[name="isaccidental"]:checked').val());
		var serid = parseInt($.cookie('servicetype'));
		var total_cf = 0;
		var basecharge = 0;
		if(kms != '' && kms > 0) {
			if(mins == 1) {
				basecharge = 200;
			} else {
				if(serid == 1 || serid == 2) {
					basecharge += 75.00;
				} else if (serid == 4) {
					basecharge += 0.00;
				}
			}
			total_cf += basecharge;
			var loop_check = true;
			if(mins == 1) {
				while(loop_check) {
					if(kms <= 2 && mins <= 60) {
						$('#confeeval').html('INR ' + total_cf.toFixed(2));
						$('#confeevalsec').show('slow');
						loop_check = false;
					}
					if(kms > 2) {
						total_cf += (kms - 2) * 20;
						kms = 2;
					}
				}
			} else {
				while(loop_check) {
					if(kms <= 3) {
						if(serid == 4) {
							total_cf = 150.00;
							var cf = total_cf.toFixed(2);
						} else {
							var cf = total_cf.toFixed(2);
						}
						$('#confeeval').html('INR ' + total_cf.toFixed(2));
						$('#confeevalsec').show('slow');
						loop_check = false;
					} else if(kms > 3 && kms <= 12) {
						if(serid == 1 || serid == 2) {
							total_cf += (kms - 3) * 5;
						}
						kms = 3;
					} else if(kms > 12 && kms <= 20) {
						if(serid == 1 || serid == 2) {
							total_cf += (kms - 12) * 7.5;
						}
						kms = 12;
					} else if(kms > 20 && kms <= 25) {
						if(serid == 1 || serid == 2) {
							total_cf += (kms - 20) * 10;
						}
						kms = 20;
					} else if(kms > 25 && kms <= 30) {
						if(serid == 1 || serid == 2) {
							total_cf += (kms - 25) * 13;
						}
						kms = 25;
					} else if(kms > 30) {
						if(serid == 1 || serid == 2) {
							total_cf += (kms - 30) * 15;
						}
						kms = 30;
					}
				}
			}
		} else {
			$('#confeeval').val('INR 0');
			$('#confeevalsec').show('slow');
		}
	});
	$('.mediaUpload').on('change', function() {
		checkMediaValidity();
		var preview_id = $(this).attr('data-id');
		var oFReader = new FileReader();
		oFReader.readAsDataURL($('#' + this.id)[0].files[0]);
		$('#nii' + preview_id).hide();
		oFReader.onload = function(oFREvent) {
			$("#uploadPreview" + preview_id)[0].src = oFREvent.target.result;
		};
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
		icons : icons
	});
	$('#accordion1 button').on('click', function(e) {
		var delta = ($(this).is('.next') ? 1 : -1);
		if(this.id == "zero") {
			$('#track1').addClass('active-progress');
			$('#tr-i-0').addClass('no-display');
			if($('#tr-i-1').hasClass('no-display')) {
				$('#tr-i-1').removeClass('no-display');
			}
		} else if(this.id == "first") {
			showFirstValidation();
			$('#paymtBlock').show();
			$('#whole_coupon_block').show();
			var amtys = new Array();
			var asers = new Array();
			var user_lc = $('#location').val();
			$.each($("input[name='amenity[]']:checked"), function() {
				amtys.push($(this).val());
			});
			$.each($("input[name='aservice[]']:checked"), function() {
				asers.push($(this).val());
			});
			if (amtys.length > 0) {
				amtys = amtys.toString();
			} else {
				amtys = '';
			}
			if (asers.length > 0) {
				asers = asers.toString();
			} else {
				asers = '';
			}
			if(is_valid_media && is_files_uploaded) {
				upload_media_files();
			}
			$.ajax({
				type: "POST",
				url: "/user/review/get_prices",
				data: {amtys: amtys, user_lc: user_lc, asers: asers},
				dataType: "text",
				cache: false,
				success: function(data) {
					$('#est-price').html(data);
					$('#est-price').show();
					$('#track2').addClass('active-progress');
					$('#tr-i-1').addClass('no-display');
					if($('#tr-i-2').hasClass('no-display')){
						$('#tr-i-2').removeClass('no-display');
					}
					if($('#is_prices_null').val() == 1) {
						$('#paymtBlock').hide();
					} else {
						show_free_service_concession();
					}
					check_final_submit();
				}
			});
		} else if(this.id == "second") {
			$('#track3').addClass('active-progress');
			$('#tr-i-2').addClass('no-display');
		} else if(this.id == "goBack"){
			$('#track2').removeClass('active-progress');
			$('#tr-i-2').addClass('no-display');
			$('#tr-i-1').removeClass('no-display');
		} else if(this.id == "goBack_0"){
			$('#track1').removeClass('active-progress');
			$('#tr-i-1').addClass('no-display');
			$('#tr-i-0').removeClass('no-display');
		}
		$("#accordion1").accordion({
			beforeActivate: function(event, ui) {
				var newIndex = $(ui.oldHeader).index('h3');
				if ($.cookie('servicetype') == 4) {
					var loopIndex = 3;
				} else {
					var loopIndex = 2;
				}
				for (var i = 0; i <= loopIndex; i++) {
					if(i != newIndex + delta) {
						$('#acc' + i).addClass('ui-state-disabled');
					} else {
						$('#acc' + i).removeClass('ui-state-disabled');
					}
				}
			}
		});
		$('#accordion1').accordion('option', 'active', ($('#accordion1').accordion('option','active') + delta));
	});
	$('#otp').on('input', function() {
		var otp = $.trim($(this).val());
		if (otp.length == 6) {
			$.ajax({
				type: "POST",
				url: "/user/review/check_otp",
				data: {otp: otp},
				dataType: "text",
				cache: false,
				success: function(data) {
					if (data == 1) {
						is_otp_verified = true;
						check_final_submit();
					} else {
						alert('The entered OTP is either expired / invalid. Reload the page to get a new OTP to the given mobile.');
					}
				}
			});
		} else if (otp.length > 6) {
			$(this).val('');
		}
	});
	$('#second').on('click', function() {
		var full_name = $("#full_name").val();
		var email = $("#email").val();
		var adln1 = $("#adln1").val();
		var adln2 = $("#adln2").val();
		var landmark = $("#landmark").val();
		var location = $("#location").val();
		var comments = $("#comments").val();
		var paymt = $('input[name="paymt"]:checked').val();
		var paymtgtw = 1;
		var isBreakdown = parseInt($('input[name="isBreakdown"]:checked').val());
		var imageupload = $('#imgUploadData').val();
		if(typeof paymt === "undefined") {
			paymt = 'NIL';
		}
		if (is_logged_in === true) {
			var addr = $('input[name="addr"]:checked').val();
		} else {
			var addr = '';
		}
		if ($.cookie('servicetype') != 3) {
			var amtys = new Array();
			var asers = new Array();
			$.each($("input[name='amenity[]']:checked"), function() {
				amtys.push($(this).val());
			});
			$.each($("input[name='aservice[]']:checked"), function() {
				asers.push($(this).val());
			});
			$.each($(".maservice"), function() {
				asers.push($(this).val());
			});
			if (amtys.length > 0) {
				amtys = amtys.toString();
			} else {
				amtys = '';
			}
			if (asers.length > 0) {
				asers = asers.toString();
			} else {
				asers = '';
			}
			if ($.cookie('servicetype') == 4) {
				var regYear = $("#regYear").val();
				var expDate = $("#expDate").val();
				var prevIns = $("#prevIns").val();
				var isClaimed = $('input[name="isClaimed"]:checked').val();
				var input_objects = {regYear: regYear, expDate: expDate, prevIns: prevIns, isClaimed: isClaimed, full_name: full_name, email: email, addr: addr, adln1: adln1, adln2: adln2, location: location, landmark: landmark, comments: comments, paymt: paymt, paymtgtw: paymtgtw, amtys: amtys};
			} else if($.cookie('servicetype') == 2) {
				var input_objects = {isBreakdown: isBreakdown, full_name: full_name, email: email, addr: addr, adln1: adln1, adln2: adln2, location: location, landmark: landmark, comments: comments, paymt: paymt, paymtgtw: paymtgtw, amtys: amtys, imageupload: imageupload};
			} else {
				var input_objects = {isBreakdown: isBreakdown, full_name: full_name, email: email, addr: addr, adln1: adln1, adln2: adln2, location: location, landmark: landmark, comments: comments, paymt: paymt, paymtgtw: paymtgtw, amtys: amtys, asers: asers, imageupload: imageupload};
			}
		} else {
			var input_objects = {full_name: full_name, email: email, addr: addr, adln1: adln1, adln2: adln2, location: location, landmark: landmark, comments: comments, paymt: paymt, paymtgtw: paymtgtw, imageupload: imageupload};
		}
		$.ajax({
			type: "POST",
			url: "/user/review/initiateTrxn",
			data: input_objects,
			dataType: "text",
			cache: false,
			success: function(data) {
				if(data.charAt(0) == '<') {
					var created_form = $(data).appendTo('body');
					created_form.submit();
				} else if(data.charAt(0) == 'G') {
					var to_pay_rzpay = parseInt(parseFloat($('#total_price_value').val()).toFixed(2) * 100);
					var rp_options = {
						"key": "rzp_live_1SU5AHKZFVCfcO",
						"amount": to_pay_rzpay,
						"name": "NewEin Technologies Private Limited",
						"description": data,
						"image": "https://www.gear6.in/img/social_logo.png",
						"handler": function (response) {
							var created_form = $('<form method="POST" action="/user/result/showRZPayStatus"><input type="hidden" name="paymt_txn_id" value="' + response.razorpay_payment_id + '"><input type="hidden" name="oid" value="' + data + '"><input type="hidden" name="amount" value="' + to_pay_rzpay + '"></form>').appendTo('body');
							created_form.submit();
						},
						"prefill": {
							"name": full_name,
							"email": email,
							"contact": $('#us_phone').val()
						},
						"notes": {
							"oid": data
						},
						"theme": {
							"color": "#028CBC",
							"close_button": false
						},
						"modal": {
							"ondismiss": function() {
								throw new Error('This is not an error. This is just to abort javascript');
							}
						}
					};
					var rzp1 = new Razorpay(rp_options);
					rzp1.open();
				} else {
					window.location.href = data;
				}
			}
		});
	});
	$('#coupon_submit, #fcoupon_submit').on('click', function() {
		var ccode = $('#coupon_input').val();
		var fccode = $('#fcoupon_input').val();
		var pprice = $('#total_price_value').val();
		var chosen_one = $(this).attr('id').split("_")[0];
		if($('#' + chosen_one + '_input').val().length < 3) {
			$('#coupon_msg_error').html('<div class="alert alert-warning" role="alert">Enter a valid coupon<a class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a></div>');
			$('#coupon_msg_error').show('slow');
		} else {
			$('#' + chosen_one + '_input_block').hide('slow');
			$('#coupon_msg_error').hide("slow");
			$('#all_coupon_block').hide("slow");
			$.ajax({
				type: "POST",
				url: "/user/coupons/check_coupon",
				data: {ccode: ccode, fccode: fccode, pprice: pprice},
				dataType: "json",
				success: function(data) {
					if(data.emsg != 0 || data.emsg != '0') {
						$('#coupon_msg_error').html('<div class="alert alert-warning" role="alert">' + data.emsg + '<a class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a></div>');
						$('#coupon_msg_error').show('slow');
						$('#' + chosen_one + '_input_block').show('slow');
						$('#all_coupon_block').show("slow");
						$('#coupon_input').val('');
						$('#fcoupon_input').val('');
					} else {
						$('input[name="paymt"][value="RP"]').icheck('checked');
						$('#paymtBlock').hide();
						show_discount_prices(data);
						$('#coupon_msg_error').html('<div class="alert alert-success" role="alert">Coupon applied successfully<a class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a></div>');
						$('#coupon_msg_error').show('slow');
					}
				}
			});
		}
	});
	$('.paymt_radio').on('ifChecked', function() {
		if($(this).val() == 'COD') {
			$('#whole_coupon_block').hide('slow');
		} else {
			$('#whole_coupon_block').show('slow');
		}
		check_final_submit();
	});
	$('#ulogin').on('click', function() {
		$('#login_modal').addClass("show");
	});
	$("#cslide-slides").cslide();
	$('#addr-block-open').on('click', function() {
		$('#addr-select-container').slideToggle("slow", function() { $('html, body').animate({scrollTop: $("#addr-select-container").offset().top}, 1000);});
	});
	$('#add-new-addr').on('click', function() {
		$('#addr-block :input').each(function() {
			$(this).val("");
			$(this).attr('readonly', false);
		});
		$('.addr_radio').each(function() {
			$('.addr_radio').icheck('unchecked');
		});
		$('#addr1').focus();
	});
	$('.addr_radio').on('ifChecked', function() {
		var id = $(this).attr('id');
		insertAddressValues(id);
		$('#addr-select-container').slideToggle("slow", function() {$('html, body').animate({scrollTop: $("#accordion1").offset().top}, 500);});
		makeAddrReadOnly();
	});
	if (is_logged_in === true) {
		insertAddressValues('addr_1');
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
			$(slidesContainerId+" .cslide-prev").addClass("cslide-disabled");
			if (!$(slidesContainerId+" .cslide-slide.cslide-active.cslide-first").hasClass("cslide-last")) {           
				$(slidesContainerId+" .cslide-prev-next").css({
					display : "block"
				});
			}
			$(slidesContainerId+" .cslide-next").on('click', function() {
				var i = $(slidesContainerId+" .cslide-slide.cslide-active").index();
				var n = i+1;
				var slideLeft = "-"+n*100+"%";
				if (!$(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-last")) {
					$(slidesContainerId+" .cslide-slide.cslide-active").removeClass("cslide-active").next(".cslide-slide").addClass("cslide-active");
					$(slidesContainerId+" .cslide-slides-container").animate({
						marginLeft : slideLeft
					},250);
					if ($(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-last")) {
						$(slidesContainerId+" .cslide-next").addClass("cslide-disabled");
					}
				}
				if ((!$(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-first")) && $(".cslide-prev").hasClass("cslide-disabled")) {
					$(slidesContainerId+" .cslide-prev").removeClass("cslide-disabled");
				}
			});
			$(slidesContainerId+" .cslide-prev").on('click', function(){
				var i = $(slidesContainerId+" .cslide-slide.cslide-active").index();
				var n = i-1;
				var slideRight = "-"+n*100+"%";
				if (!$(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-first")) {
					$(slidesContainerId+" .cslide-slide.cslide-active").removeClass("cslide-active").prev(".cslide-slide").addClass("cslide-active");
					$(slidesContainerId+" .cslide-slides-container").animate({
						marginLeft : slideRight
					},250);
					if ($(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-first")) {
						$(slidesContainerId+" .cslide-prev").addClass("cslide-disabled");
					}
				}
				if ((!$(slidesContainerId+" .cslide-slide.cslide-active").hasClass("cslide-last")) && $(".cslide-next").hasClass("cslide-disabled")) {
					$(slidesContainerId+" .cslide-next").removeClass("cslide-disabled");
				}
			});
		});
		return this;
	}
}(jQuery));
function show_free_service_concession() {
	var free_service_availed = $('input[name="isFreeService"]:checked').val();
	var pprice = $('#total_price_value').val();
	if(typeof free_service_availed !== "undefined") {
		$.ajax({
			type: "POST",
			url: "/user/review/get_free_service_discount",
			data: {ref: free_service_availed, pprice: pprice},
			dataType: "json",
			cache: false,
			success: function(data) {
				if(typeof data.fsvalue !== 'undefined') {
					var html = '<div class="" style="border-bottom:1px dotted #028cbc;margin-bottom:10px;">Discount Details</div>';
					if(typeof data.fsvalue !== "undefined" && data.fsvalue != "") {
						html += '<div class="left">Free Servicing Discount</div>';
						html += '<div class="right"><i class="fa fa-inr"></i>&nbsp;' + data.fsvalue + '</div>';
						$('#all_coupon_block').html(html);
						$('#all_coupon_block').show('slow');
					}
					if(data.to_pay == 0 || data.to_pay == '0') {
						$('#is_prices_null').val(1);
						$('#paymtBlock').hide();
					} else {
						$('#is_prices_null').val(0);
					}
					$('#whole_coupon_block').remove();
					$('#total_price_value').val(data.to_pay);
					$('#total_price_label').html(data.to_pay);
					$('#goBack').hide('slow');
					check_final_submit();
				}
			}
		});
	}
}
function check_final_submit() {
	if(!is_query_order) {
		var sel_paymt = $('input[name="paymt"]:checked').val();
		if(is_logged_in && (typeof sel_paymt !== 'undefined' || $('#is_prices_null').val() == 1)) {
			$('#second').removeAttr('disabled');
		} else if(!is_logged_in && (typeof sel_paymt !== 'undefined' || $('#is_prices_null').val() == 1) && is_otp_verified) {
			$('#second').removeAttr('disabled');
		} else {
			$('#second').attr('disabled','disabled');
		}
	}
}
function show_discount_prices(data) {
	var html = '<div class="" style="border-bottom:1px dotted #028cbc;margin-bottom:10px;">Discount Details</div>';
	if(typeof data.fdvalue !== "undefined" && data.fdvalue != "") {
		html += '<div class="left">Fixed Coupon Discount / Gift Card Discount -- <a href="#" id="fcoupon_remove"><span style="color: #8a6d3b;">Remove</span></a></div>';
		html += '<div class="right"><i class="fa fa-inr"></i>&nbsp;' + data.fdvalue + '</div>';
		$('#all_coupon_block').html(html);
		$('#all_coupon_block').show('slow');
	}
	if(typeof data.cdvalue !== "undefined" && data.cdvalue != "") {
		html += '<div class="left" style="clear:left;">Offer Discount -- <a href="#" id="coupon_remove"><span style="color: #8a6d3b;">Remove</span></a></div>';
		html += '<div class="right"><i class="fa fa-inr"></i>&nbsp;' + data.cdvalue + '</div>';
		$('#all_coupon_block').html(html);
		$('#all_coupon_block').show('slow');
	}
	if((typeof data.fdvalue === "undefined" || data.fdvalue == "") && (typeof data.cdvalue === "undefined" || data.cdvalue == "")) {
		$('#paymtBlock').show();
	}
	if(parseInt(data.to_pay) <= 0.01) {
		$('#is_prices_null').val(1);
		$('#paymtBlock').hide();
	} else {
		$('#is_prices_null').val(0);
	}
	$('#goBack').hide('slow');
	$('#total_price_value').val(data.to_pay);
	$('#total_price_label').html(data.to_pay);
	$('#coupon_input').val('');
	$('#fcoupon_input').val('');
	coupon_remove_event_binder();
	check_final_submit();
}
function coupon_remove_event_binder() {
	$('#coupon_remove, #fcoupon_remove').on('click', function() {
		var chosen_one = $(this).attr('id').split("_")[0];
		$('#all_coupon_block').html('');
		$('#all_coupon_block').hide("slow");
		$('#coupon_msg_error').hide("slow");
		$.ajax({
			type: "GET",
			url: "/user/coupons/remove_" + chosen_one,
			dataType: "json",
			success: function(data) {
				show_discount_prices(data);
				$('#' + chosen_one + '_input_block').show('slow');
				$('#coupon_msg_error').html('<div class="alert alert-success" role="alert">Coupon removed successfully<a class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a></div>');
				$('#coupon_msg_error').show('slow');
			}
		});
	});
}
showValidation = function(check,id) {
	if(check == true) {
		return true;
	} else {
		$(id).parent().append('<div class="error-text">Please fill valid '+$(id).data('error')+'</div>');
		throw new Error('This is not an error. This is just to abort javascript');
	}
}
function showFirstValidation() {
	if(!is_valid_media) {
		$('.mediaUpload').val('');
		$('#uploadPreview1').attr('src', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==');
		$('#uploadPreview2').attr('src', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==');
		$('#uploadPreview3').attr('src', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==');
		$('#nii1').show();
		$('#nii2').show();
		$('#nii3').show();
		is_valid_media = true;
		is_files_uploaded = false;
		showValidation(false,'#media-label');
	}
	var str2 = ['#full_name','#email','#adln1','#adln2','#location','#comments'];
	var e0 = $("#full_name").val();
	var e1 = $("#email").val();
	var e2 = $("#adln1").val();
	var e3 = $("#adln2").val();
	var e4 = $("#location").val();
	var e5 = $("#comments").val();
	var str1 = [e0,e1,e2,e3,e4,e5];
	var x = 0;
	var e8 = 1;
	var eid = 0;
	$('.error-text').remove();
	for (i = 0; i < str1.length; i++) {
		if (str1[i] == "" || str1[i] == undefined || str1[i] == null) {
			if($.cookie('servicetype') != 3) {
				if(i == 5) {
					if ($.cookie('servicetype') == 2) {
						showValidation(false, str2[i]);
					}
				} else {
					showValidation(false, str2[i]);
				}
			} else {
				if(i != 0 && i != 1 && i != 5) {
					continue;
				} else {
					showValidation(false,str2[i]);
				}
			}
		}
	}
	if(!IsEmail(str1[1])) {
		showValidation(false,str2[1]);
	}
	if($.cookie('servicetype') != 3) {
		if(!isValidLocation(str1[4])) {
			showValidation(false, str2[4]);
		}
		if ($.cookie('servicetype') == 2) {
			if (e5.length <= 10) {
				showValidation(false, str2[5]);
			}
		}
	} else {
		if (e5.length <= 10) {
			showValidation(false, str2[5]);
		}
	}
}
function checkfield1() {
	var e0 = $("#comments").val();
	var e1 = $("#full_name").val();
	var e2 = $("#email").val();
	var e3 = $("#adln1").val();
	var e4 = $("#adln2").val();
	var e5 = $("#location").val();
	var x = 0;
	var e6 = 1;
	if(e1.length <= 2 || e2.length <= 6 || e3.length <= 2 || e4.length <= 2 || e5.length <= 2) {
		e6 = 0;
	}
	if (!IsEmail(e2) || !isValidLocation(e5)) {
		e6 = 0;
	}
	if ($.cookie('servicetype') == 2 || $.cookie('servicetype') == 3) {
		if (e0.length <= 10) {
			e6 = 0;
		}
	}
	if(e6 == 0) {
		x = 1;
	}
	if(!is_valid_media) {
		x = 1;
	}
	if(x == 0) {
		$("#first").removeAttr('disabled');
	} else {
		$("#first").attr('disabled','disabled');
	}
}
function checkMediaValidity() {
	for(i = 1; i <= 3; i++) {
		if($('#uploadImage_' + i).val()) {
			is_files_uploaded = true;
			if(($('#uploadImage_' + i)[0].files[0].size) / (1024 * 1024) > 5 || $.inArray($('#uploadImage_' + i)[0].files[0].type, valid_mimes) == -1) {
				is_valid_media = false;
				return;
			}
		}
	}
	is_valid_media = true;
}
function upload_media_files() {
	var formData = new FormData($('#uploadImage_1').parents('form')[0]);
	$.ajax({
		type: 'post',
		url: '/user/review/omedia_upload',
		data: formData,
		dataType: 'text',
		success: function (data) {
			$('#imgUploadData').val(data);
		},
		cache: false,
		processData: false,
		contentType: false
	});
}
$('#zero').on('click', function() {
	var str1 = new Array(5);
	var str2 = ['#regYear','#expDate','#prevIns','#approximateTime', '#phNum'];
	str1[0]  = $("#regYear").val();
	str1[1]  = $("#expDate").val();
	str1[2]  = $("#prevIns").val();
	str1[3] = $("#approximateTime").val();
	str1[4] = $("#phNum").val();
	var x = 0;
	var eid = 0;
	$('.error-text').remove();
	for (i = 0; i < str1.length; i++) {
		if (is_logged_in === false && i == 4) {
			if (str1[i] == null || str1[i] == "" || str1[i] < 7000000000  || str1[i] > 9999999999 || isNaN(str1[i])) {
				x = 1;
				eid = i;
				break;
			}
		}
		if(str1[i] == null || str1[i] == "") {
			x = 1;
			eid = i;
			break;
		}
	}
	if(x == 0) {
		$.ajax({
			type: 'post',
			url: '/user/review/set_insurance_renewal_cookies',
			data: { phone: str1[4], slot: str1[3] },
			dataType: 'text',
			success: function (data) {
				if(data == 1) {
					if(is_logged_in === false) {
						$.ajax({
							type: 'post',
							url: '/user/review/check_phone',
							data: {phone:str1[4]},
							success: function (data) {
								if(data == 1) {
									var delta = ($('#zero').is('.next') ? 1 : -1);
									$('#track1').addClass('active-progress');
									$('#tr-i-0').addClass('no-display');
									if($('#tr-i-1').hasClass('no-display')) {
										$('#tr-i-1').removeClass('no-display');
									}
									$('#accordion1').accordion('option', 'active', ($('#accordion1').accordion('option','active') + delta));
								} else {
									$('.login-modal-close').remove();
									openBlkLoginModal();
								}
							}
						});
					} else {
						var delta = ($('#zero').is('.next') ? 1 : -1);
						$('#track1').addClass('active-progress');
						$('#tr-i-0').addClass('no-display');
						if($('#tr-i-1').hasClass('no-display')) {
							$('#tr-i-1').removeClass('no-display');
						}
						$('#accordion1').accordion('option', 'active', ($('#accordion1').accordion('option','active') + delta));
					}
				}
			}
		});
	} else {
		$(str2[eid]).parent().append('<div class="error-text">Please fill valid '+$(str2[eid]).data('error')+'</div>');
		throw new Error('This is not an error. This is just to abort javascript');
	}
});
function insertAddressValues(id) {
	$('#adln1').val($('#addr_content_'+id.split("_")[1]+' :first-child').text());
	$('#adln2').val($('#addr_content_'+id.split("_")[1]+' :nth-child(2)').text());
	$('#location').val($('#addr_content_'+id.split("_")[1]+' :nth-child(3)').text());
	$('#landmark').val($('#addr_content_'+id.split("_")[1]+' :nth-child(4)').text());
}
function makeAddrReadOnly() {
	$('#adln1').attr('readonly', true);
	$('#adln2').attr('readonly', true);
	$('#location').attr('readonly', true);
	$('#landmark').attr('readonly', true);
}
function IsEmail(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}
function isValidLocation(code) {
	return ($.inArray(code, availableLocations) > -1);
}