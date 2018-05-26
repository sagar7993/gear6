<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Privileged Vendor Home - Login</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('prvendor/components/_vcss'); ?>
</head>
<body>
	<div class="load-wrap" style="display:none;">
		<div class="preloader-wrapper big active center loader1" >
			<div class="spinner-layer spinner-green-only">
				<div class="circle-clipper left">
					<div class="circle"></div>
				</div>
				<div class="gap-patch">
					<div class="circle"></div>
				</div>
				<div class="circle-clipper right">
					<div class="circle"></div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('prvendor/components/_head'); ?>
	<main>
		<div class="vlogin-wrapper" id="agent_login">
			<div class="center-align margin-top-50 margin-bottom-20px">
				<h4>Privileged Vendor Login</h4>
			</div>
			<form method="POST">
			<div class="boxShadow">
				<div class="row no-row">
					<?php if(isset($login_error_message)) { echo '<div class="alert alert-danger" role="alert">' . $login_error_message . '</div><br>'; } ?>
					<div class="col s12 inp-wrapper margin-top-n5" >
						<i class="fa fa-mobile mob-font"></i>
						<input type="text" class="" name="phone" id="area1_name" placeholder="Mobile Number">
					</div>
					<div class="col s12 inp-wrapper">
						<i class="material-icons">vpn_key</i>
						<input type="password" class="" name="password" id="area1_name" placeholder="Password">
					</div>
				</div>
			</div>
			<div class="row margin-top-22px">
				<div class="col s12 center-align">
					<button type="submit" id="submit" name="login" class="btn waves-effect waves-light btn col s12" >
						Login
					</button>
				</div>
				<div class="col s12 m12 center-align">
					<label><a class="pointer" id="fgpwd_agent">Forgot Password?</a></label>
				</div>
			</div>
			</form>
			<div class="bkService">
			</div><!-- /.box -->
		</div>
		<div class="vlogin-wrapper" id="agent_otp" style="display:none">
			<div class="center-align margin-top-50 margin-bottom-20px">
				<h4>Reset Password</h4>
			</div>
			<div class="boxShadow">
				<div class="alert alert-danger modal-error-text" role="alert" id="rp_msg" style="display:none;"></div>
				<div class="row no-row">
					<div class="col s12 inp-wrapper margin-top-n5">
						<i class="fa fa-mobile mob-font"></i>
						<input type="text" class="" name="" id="rp_phone" placeholder="Mobile Number">
					</div>
				</div>
			</div>
			<div class="row margin-top-22px">
				<div class="col s12 center-align">
					<button id="rp_sendotp" class="btn waves-effect waves-light btn col s12">
						Send OTP
					</button>
				</div>
			</div>
		</div>
		<div class="vlogin-wrapper" id="agent_rpwd" style="display:none">
			<div class="center-align margin-top-50 margin-bottom-20px">
				<h4>Reset Password</h4>
			</div>
			<form method="POST" action="/home/resetpwd_prv">
			<div class="boxShadow">
				<div class="row no-row">
					<div class="col s12 inp-wrapper margin-top-n5">
						<i class="material-icons">vpn_key</i>
						<input type="text" class="" name="rp_otp" id="rp_otp" placeholder="Enter OTP">
					</div>
					<input type="hidden" name="rp_phone" id="hidrp_phone" value="">
					<div class="col s12 inp-wrapper margin-top-n5">
						<i class="material-icons">vpn_key</i>
						<input type="text" class="" name="rp_pwd1" id="rp_pwd1" oninput="checkrpwd();" placeholder="New Password">
					</div>
					<div class="col s12 inp-wrapper margin-top-n5">
						<i class="material-icons">vpn_key</i>
						<input type="text" class="" name="rp_pwd2" id="rp_pwd2" oninput="checkrpwd();" placeholder="Confirm Password">
					</div>
				</div>
			</div>
			<div class="row margin-top-22px">
				<div class="col s12 m12 center-align">
					<button type="submit" id="rp_rpwd" class="btn waves-effect waves-light btn col s12">
						Reset Password
					</button>
				</div>
			</div>
			</form>
		</div>
	</main>
	<?php $this->load->view('prvendor/components/_foot'); ?>
<?php $this->load->view('prvendor/components/_vjs'); ?>
<script>
var univ_otp_check = false;
$(function() {
	$('#rp_sendotp').on('click', function() {
		var ph = $('#rp_phone').val();
		if(isValidPhone(ph)) {
			makeAjaxCallForForgotOTP(ph);
		} else {
			$('#rp_msg').show('slow');
			$('#rp_msg').html('Phone number is not valid.');
		}
	});
	$('#rp_otp').on('input', function() {
		var otp = $.trim($(this).val());
		var phNum = $('#rp_phone').val();
		if (otp.length == 6) {
			$.ajax({
				type: "POST",
				url: "/home/check_fgototp_prv",
				data: {otp: otp, phNum: phNum},
				dataType: "text",
				cache: false,
				success: function(data) {
					if (data == 1) {
						univ_otp_check = true;
					} else {
						alert('The entered OTP is either expired / invalid.');
						univ_otp_check = false;
						$('#rp_otp').val('');
					}
					checkrpwd();
				}
			});
		} else if (otp.length > 6) {
			$(this).val('');
		}
	});
	$('#fgpwd_agent').on('click', function(e) {
		e.preventDefault();
		$('#agent_login').hide('slow');
		$('#agent_otp').show('slow');
	});
});
isValidPhone = function(phNum) {
	if (phNum == "" || phNum < 7000000000 || phNum > 9999999999 || isNaN(phNum)) {
		return false;
	}
	return true;
}
function checkrpwd() {
	var pwd1 = $('#rp_pwd1').val();
	var pwd2 = $('#rp_pwd2').val();
	if(univ_otp_check && pwd1 != "" && pwd2 != "" && pwd1 == pwd2) {
		$('#rp_rpwd').removeAttr('disabled');
	} else {
		$('#rp_rpwd').attr('disabled', 'disabled');
	}
}
function makeAjaxCallForForgotOTP(phNum) {
	$.ajax({
		type: "POST",
		url: "/home/send_fgototp_prv",
		data: {phNum: phNum},
		dataType: "json",
		cache: false,
		success: function(data) {
			if (data.err) {
				$('#rp_msg').show('slow');
				$('#rp_msg').html(data.err);
			} else {
				$('#agent_otp').hide('slow');
				$('#agent_rpwd').show('slow');
				$('#hidrp_phone').val(phNum);
			}
		}
	});
}
</script>
</body>
</html>