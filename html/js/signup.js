var univ_otp_check = false;
var is_social_signin = false;
var univ_ref_code_check = true;
var auth2;
var googleUser;
$(function() {
	$('#s_dob, #fg_dob, #sf_dob').pickadate({
		selectMonths: true, // Creates a dropdown to control month
		selectYears: 60,
		max: -6570,
		closeOnSelect: true,
		container: 'body',
		format: 'yyyy-mm-dd',
		onOpen: function() {
			$(this).val('');
		},
		onSet : function() {
			if($(this).val() != "" ) {
				$(this).close();
			}
		}
	});
	$('.modal-trigger').leanModal({
		ready: function() { 
			$('html').css('overflow','hidden');
		},
		complete: function() { 
			$('html').css('overflow','auto');
			$('#s_login_error').hide();
			$('#s_forget_pwd').hide();
			$('#s_f_pwd_error').hide();
			$('#signup_error').hide();
			$('#bes_login_error').hide();
			$('#bes_pwd_error').hide();
		}
	});
	$('#s_sign_up').on('click', function() {
		$('#fg_type').val('Email');
		makeAjaxCallForOTP();
	});
	$('.s_gender').on('ifChanged', function() {
		checkaotp();
	});
	$('.fg_gender').on('ifChanged', function() {
		checksotp();
	});
	$('#s_terms').on('ifChanged', function() {
		checkaotp();
	});
	$('.sf_gender').on('ifChanged', function() {
		checkasfotp();
	});
	$('#sf_terms').on('ifChanged', function() {
		checkasfotp();
	});
	$('#fg_terms').on('ifChanged', function() {
		checksotp();
	});
	$('#fb').on('hover', function() {
		$('.signup-text').show();
	});
	$('#gplus').on('hover', function() {
		$('.signup-textg').show();
	});
	$(document).on('click', '.modal-close', function() {
		$(this).closest('.modal').closeModal({
			complete: function() { 
				$('.lean-overlay').remove();
			}
		});
	});
	$('#forgot_pwd').on('click', function(e) {
		$('#login').closeModal({
			complete: function() { 
				$('.lean-overlay').remove();
				$('#pwdOtp').openModal({
					dismissible : false,
					ready: function() {
						var overlay = $('<div id="lean-overlay" class="lean-overlay"></div>');
						$("body").append(overlay);
						$('#lean-overlay').css({'opacity':'0.5','display':'block'});
					}
				});
			}
		});
	});
	$('#new_user').on('click', function() {
		$('#login').closeModal({
			complete: function() { 
				$('.lean-overlay').remove();
				$('#signup').openModal({
					dismissible : false,
					ready: function() {
						var overlay = $('<div id="lean-overlay" class="lean-overlay"></div>');
						$("body").append(overlay);
						$('#lean-overlay').css({'opacity':'0.5','display':'block'});
					}
				});
			}
		});
	});
	$('#s_fs_otp').on('click', function() {
		var phNum = $('#s_fgot_phone').val();
		if (phNum == "" || phNum < 7000000000 || phNum > 9999999999 || isNaN(phNum)) {
			$('#s_forget_pwd').show('slow');
			$('#s_forget_pwd').html('Phone number is not valid.');
		} else {
			makeAjaxCallForForgotOTP(phNum);
		}
	});
	$('#s_resend1').on('click', function() {
		$('#s_resend1').hide('slow');
		makeAjaxCallForOTP();
	});
	$('#sfgot_resend1').on('click', function() {
		$('#sfgot_resend1').hide('slow');
		makeAjaxCallForForgotOTP($('#sfgot_phone').val());
	});
	$('#l_login').on('click', function() {
		$('#fgl_type').val('Email');
		$('#fgl_form').submit();
	});
	$('#fg_resend1, #fg_send1').on('click', function() {
		$(this).hide('slow');
		makeAjaxCallForOTP(true);
	});
	$('.s_fbsignin, .l_fbsignin').on('click', function() {
		if($(this).hasClass('l_fbsignin')) {
			is_social_signin = true;
		} else {
			is_social_signin = false;
		}
		FB.login(function (response) {
			if (response.status === 'connected') {
				$('#fg_token').val(response.authResponse.accessToken);
				$('#fg_id').val(response.authResponse.userID);
				$('#fgl_token').val(response.authResponse.accessToken);
				$('#fgl_id').val(response.authResponse.userID);
				getFbLoginData();
			}
		}, {
			scope: 'email',
			auth_type: 'rerequest'
		});
	});
	$('#s_otp_ftime, #fg_otp_ftime').on('input', function() {
		var otp = $.trim($(this).val());
		var temp = $(this).attr('id').split('_')[0];
		var phNum = $('#' + temp + '_phone').val();
		if (otp.length == 6) {
			$.ajax({
				type: "POST",
				url: "/user/userhome/check_otp",
				data: {otp: otp, phNum: phNum},
				dataType: "text",
				cache: false,
				success: function(data) {
					if (data == 1) {
						univ_otp_check = true;
					} else {
						alert('The entered OTP is either expired / invalid.');
						$('#signup_error').html('The entered OTP is either expired / invalid.');
						univ_otp_check = false;
						$('#' + temp + '_resend1').show('fast', function() { $(this).css('display', 'initial'); });
					}
					if(temp == 's') {
						checkaotp();
					} else {
						checksotp();
					}
				}
			});
		} else if (otp.length > 6) {
			$(this).val(''); univ_otp_check = false;
		}
	});
	$('#s_referral, #fg_referral').on('input', function() {
		univ_ref_code_check = false;
		var ref_code = $.trim($(this).val());
		var temp = $(this).attr('id').split('_')[0];
		if(temp == 's') {
			checkaotp();
		} else {
			checksotp();
		}
		if (ref_code.length == 13) {
			$.ajax({
				type: "POST",
				url: "/user/userhome/check_referral",
				data: {ref_code: ref_code},
				dataType: "text",
				cache: false,
				success: function(data) {
					if (data == 1) {
						univ_ref_code_check = true;
					} else {
						alert('The entered Referral Code is invalid.');
						$('#signup_error').html('The entered Referral Code is invalid.');
						univ_ref_code_check = false;
					}
					if(temp == 's') {
						checkaotp();
					} else {
						checksotp();
					}
				}
			});
		} else if (ref_code.length > 13) {
			$(this).val(''); univ_ref_code_check = true;
		}
	});
});
function initGplus () {
	gapi.load('auth2', function() {
		auth2 = gapi.auth2.init(gpAsyncInit);
		$('.s_gpsignin, .l_gpsignin').on('click', function() {
			if($(this).hasClass('l_gpsignin')) {
				is_social_signin = true;
			} else {
				is_social_signin = false;
			}
			auth2.signIn({'scope' : 'profile email', 'prompt' : 'select_account'}).then(function() {
				googleUser = auth2.currentUser.get();
				gpSignInCallback();
			}, function() {});
		});
	});
}
function makeAjaxCallForForgotOTP(phNum) {
	$.ajax({
		type: "POST",
		url: "/user/userhome/sendForgotOtp",
		data: {phNum: phNum},
		dataType: "json",
		cache: false,
		success: function(data) {
			if (data.err) {
				$('#s_forget_pwd').show('slow');
				$('#s_forget_pwd').html(data.err);
			} else {
				$('#pwdOtp').closeModal({
					complete: function() { 
						$('.lean-overlay').remove();
						$('#pwdReset').openModal({
							dismissible : false,
							ready: function() {
								var overlay = $('<div id="lean-overlay" class="lean-overlay"></div>');
								$("body").append(overlay);
								$('#lean-overlay').css({'opacity':'0.5','display':'block'});
							}
						});
					}
				});
				$('#sfgot_phone').val(phNum);
			}
		}
	});
}
function makeAjaxCallForOTP(is_social) {
	var check = false;
	if(is_social === true) {
		var phNum = $('#fg_phone').val();
		if (phNum < 7000000000 || phNum > 9999999999 || isNaN(phNum)) {
			check = false;
			$('#signup_error').show('slow');
			$('#signup_error').html('Phone number is not valid.');
			$('#fg_send1').show('fast', function() { $(this).css('display', 'initial'); });
		} else {
			check = true;
		}
	} else {
		var phNum = $('#s_phone').val();
		$('#s_sign_up').hide('slow');
		check = true;
	}
	if(check) {
		$.ajax({
			type: "POST",
			url: "/user/userhome/insert_otp",
			data: {phNum: phNum},
			dataType: "text",
			cache: false,
			success: function(data) {
				if (data) {
					if(is_social === true) {
						$("#fg_phone").prop("readonly", true);
						$("#fg_phone").addClass("grey-bg");
					} else {
						$('#sign_init').hide('slow');
						$('#social_icons').hide('slow');
						$('#otp_container').show('slow');
						$('#s_continue').show('slow');
					}
				} else {
					if(is_social === true) {
						$('#fg_send1').show('fast', function() { $(this).css('display', 'initial'); });
					} else {
						$('#s_sign_up').show('slow');
					}
					$('#signup_error').show('slow');
					$('#signup_error').html('Phone is already registered.');
				}
			}
		});
	}
}
function checkprfield() {
	var str1 = new Array();
	var x = 0;
	str1[0] = $("#sfgot_otp").val();
	str1[1] = $("#sfgot_pwd").val();
	str1[2] = $("#sfgot_pwd1").val();
	for (i = 0; i < str1.length; i++) {
		if (str1[i] == "" || str1[i] == undefined || str1[i] == null || str1[i].length == 0) {
			x = 1;
		}
	}
	if(str1[1] != str1[2] || str1[1].length < 6 || str1[0].length < 6) {
		x = 1;
	}
	if (x == 0) {
		$("#sfgot_submit").removeAttr('disabled');
	} else {
		$("#sfgot_submit").attr('disabled',true);
	}
}
function checksotp() {
	var str1 = new Array();
	var x = 0;
	str1[0] = $("#fg_dob").val();
	str1[1] = $('input[name="fg_terms"]:checked').val();
	str1[2] = $('input[name="fg_gender"]:checked').val();
	for (i = 0; i < str1.length; i++) {
		if (str1[i] == "" || str1[i] == undefined || str1[i] == null || str1[i].length == 0) {
			x = 1;
		}
	}
	if($("#fg_referral").val() == null || $("#fg_referral").val() == undefined || $("#fg_referral").val() == "") {
		univ_ref_code_check = true;
	}
	if(!univ_ref_code_check) { x = 1; }
	if (x == 0 && univ_otp_check && univ_ref_code_check) {
		$("#fg_continue").removeAttr('disabled');
	} else {
		$("#fg_continue").attr('disabled', true);
	}
}
function checkasfotp() {
	var str1 = new Array();
	var x = 0;
	str1[0] = $("#sf_dob").val();
	str1[1] = $('input[name="sf_gender"]:checked').val();
	str1[2] = $('input[name="sf_terms"]:checked').val();
	str1[3] = $("#sf_pswd1").val();
	str1[4] = $("#sf_pswd2").val();
	for (i = 0; i < str1.length; i++) {
		if (str1[i] == "" || str1[i] == undefined || str1[i] == null || str1[i].length == 0) {
			x = 1;
		}
	}
	if(str1[3] != str1[4] || str1[3].length < 6) {
		x = 1;
	}
	if (x == 0) {
		$("#sf_pwdreset").removeAttr('disabled');
	} else {
		$("#sf_pwdreset").attr('disabled',true);
	}
}
function checkbotp() {
	var str1 = new Array();
	var x = 0;
	str1[0] = $("#s_phone").val();
	str1[1] = $("#s_email").val();
	str1[2] = $("#s_pwd").val();
	str1[3] = $("#s_cpwd").val();
	for (i = 0; i < str1.length; i++) {
		if (str1[i] == "" || str1[i] == undefined || str1[i] == null || str1[i].length == 0) {
			x = 1;
		}
	}
	if (str1[0] < 7000000000 || str1[0] > 9999999999 || isNaN(str1[0]) || !IsEmail(str1[1])) {
		x = 1;
	}
	if(str1[2] != str1[3] || str1[2].length < 6) {
		x = 1;
	}
	if (x == 0) {
		$("#s_sign_up").removeAttr('disabled');
	} else {
		$("#s_sign_up").attr('disabled',true);
	}
}
function checkaotp() {
	var str1 = new Array();
	var x = 0;
	str1[0] = $("#s_fname").val();
	str1[1] = $("#s_dob").val();
	str1[2] = $('input[name="s_gender"]:checked').val();
	str1[3] = $('input[name="s_terms"]:checked').val();
	for (i = 0; i < str1.length; i++) {
		if (str1[i] == "" || str1[i] == undefined || str1[i] == null || str1[i].length == 0) {
			x = 1;
		}
	}
	if(str1[0].length < 3) {
		x = 1;
	}
	if($("#s_referral").val() == null || $("#s_referral").val() == undefined || $("#s_referral").val() == "") {
		univ_ref_code_check = true;
	}
	if(!univ_ref_code_check) { x = 1; }
	if (x == 0 && univ_otp_check && univ_ref_code_check) {
		$("#s_continue").removeAttr('disabled');
	} else {
		$("#s_continue").attr('disabled',true);
	}
}
function IsEmail(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}
function secondStageSocialSignUp() {
	$('#signup_error').hide('slow');
	$('#s_sign_up').hide('slow');
	$('#sign_init').hide('slow');
	$('#social_icons').hide('slow');
	$('#social_signup_fields').show('slow');
	$('#fg_continue').show('slow');
}
function isAlreadySignedUP (ifSignedUp, ifNotSignedUp, gprofile) {
	var soc_id = $('#fgl_id').val();
	var s_type = $('#fgl_type').val();;
	$.ajax({
		type: "POST",
		url: "/user/userhome/isSocialSignedUp",
		data: {soc_id: soc_id, s_type: s_type},
		dataType: "text",
		cache: false,
		success: function(data) {
			if (data == 0) {
				ifNotSignedUp(gprofile);
			} else if(data == 1) {
				ifSignedUp();
			}
		}
	});
}
openCityModal = function() {
	$('#city_change_modal').openModal({
		dismissible : false,
		ready: function() {
			var overlay = $('<div id="lean-overlay" class="lean-overlay"></div>');
			$("body").append(overlay);
			$('#lean-overlay').css({'opacity':'0.5','display':'block'});
		}
	});
}
openSignUpModal = function() {
	$('#signup').openModal({
		dismissible : false,
		ready: function() {
			var overlay = $('<div id="lean-overlay" class="lean-overlay"></div>');
			$("body").append(overlay);
			$('#lean-overlay').css({'opacity':'0.5','display':'block'});
		}
	});
}
openLoginModal = function() {
	$('#login').openModal({
		dismissible : false,
		complete: function() {
			$('html').css('overflow','auto');
			$('#s_login_error').hide();
			$('#s_forget_pwd').hide();
			$('#s_f_pwd_error').hide();
			$('#signup_error').hide();
			$('#bes_login_error').hide();
			$('#bes_pwd_error').hide();
		},
		ready: function() {
			var overlay = $('<div id="lean-overlay" class="lean-overlay"></div>');
			$("body").append(overlay);
			$('#lean-overlay').css({'opacity':'0.5','display':'block'});
		}
	});
}
openBlkLoginModal = function() {
	$('#login').openModal({
		dismissible : false,
		complete: function() {
			$('html').css('overflow','auto');
			$('#s_login_error').hide();
			$('#s_forget_pwd').hide();
			$('#s_f_pwd_error').hide();
			$('#signup_error').hide();
			$('#bes_login_error').hide();
			$('#bes_pwd_error').hide();
		},
		ready: function() {
			var overlay = $('<div id="lean-overlay" class="lean-overlay"></div>');
			$("body").append(overlay);
			$('#lean-overlay').css({'opacity':'0.5','display':'block'});
		}
	});
}
openFirstTimeLoginModal = function() {
	$('#pwdreset_modal').openModal({
		dismissible : false,
		complete: function() {
			$('html').css('overflow','auto');
			$('#s_login_error').hide();
			$('#s_forget_pwd').hide();
			$('#s_f_pwd_error').hide();
			$('#signup_error').hide();
			$('#bes_login_error').hide();
			$('#bes_pwd_error').hide();
		},
		ready: function() {
			var overlay = $('<div id="lean-overlay" class="lean-overlay"></div>');
			$("body").append(overlay);
			$('#lean-overlay').css({'opacity':'0.5','display':'block'});
		}
	});
}
/* Fb Social Login API Code Goes Here */
function getFbLoginData() {
	FB.api('/me/permissions', function(response) {
		if(response.data[1].status == "declined") {
			$('#signup_error').show('slow');
			$('#signup_error').html('Email permission is required.');
		} else {
			if(!is_social_signin) {
				$('#fgl_type').val('Facebook');
				$('#fg_type').val('Facebook');
				isAlreadySignedUP(function() {
					$('#fgl_form').submit();
				}, function(gprofile) {
					FB.api('/me', function(response) {
						$('#fg_name').val(response.name);
						$('#fg_email').val(response.email);
						secondStageSocialSignUp();
					});
				}, null);
				$('#otp_container').hide('slow');
			} else {
				$('#fgl_type').val('Facebook');
				isAlreadySignedUP(function() {
					$('#fgl_form').submit();
				}, function(gprofile) {
					$('#s_login_error').show('slow');
					$('#s_login_error').html('You have to SignUp using Facebook');
				}, null);
			}
		}
	});
}
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s);
	js.id = id;
	js.type = 'text/javascript';
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
} (document, 'script', 'facebook-jssdk'));
window.fbAsyncInit = function() {
	FB.init({
		appId : '819826911429226',
		cookie : true,
		xfbml : true,
		version : 'v2.3'
	});
};
/* Google Social Login API Code Goes Here */
function gpSignInCallback() {
	var profile = googleUser.getBasicProfile();
	var id_token = googleUser.getAuthResponse().id_token;
	$('#fg_token').val(id_token);
	$('#fg_id').val(profile.getId());
	$('#fgl_id').val(profile.getId());
	$('#fgl_token').val(id_token);
	$('#fgl_type').val('Google');
	$('#fg_type').val('Google');
	if(!is_social_signin) {
		isAlreadySignedUP(function() {
			$('#fgl_form').submit();
		}, function() {
			$('#fg_name').val(profile.getName());
			$('#fg_email').val(profile.getEmail());
			secondStageSocialSignUp();
		}, null);
		$('#otp_container').hide('slow');
	} else {
		isAlreadySignedUP(function() {
			$('#fgl_form').submit();
		}, function() {
			$('#s_login_error').show('slow');
			$('#s_login_error').html('You have to SignUp using Google');
		}, null);
	}
}
(function (d, s, id) {
	var js, gjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s);
	js.id = id;
	js.type = 'text/javascript';
	js.src = "https://apis.google.com/js/platform.js?onload=initGplus";
	js.async = true;
	js.defer = true;
	gjs.parentNode.insertBefore(js, gjs);
} (document, 'script', 'gplus-jssdk'));
var gpAsyncInit = {
	'client_id' : '452928637350-t9mvi5q8tbpbq3iuut153j38h6oba3e3.apps.googleusercontent.com',
	'cookie_policy' : 'single_host_origin',
	'scope' : 'profile email'
}