<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Home - Login</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('vendor/components/_vcss'); ?>
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
	<?php $this->load->view('vendor/components/_head'); ?>
	<main>
		<div class="vlogin-wrapper" id="agent_login">
			<div class="center-align margin-top-50 margin-bottom-20px">
				<h4>Agent Login</h4>
			</div>
			<form method="POST">
			<div class="boxShadow">
				<div class="row no-row">
					<?php if(isset($login_error_message)) { echo '<div class="alert alert-danger" role="alert">' . $login_error_message . '</div><br>'; } ?>
					<?php if(isset($reg_success_msg)) { echo '<div class="alert alert-success">' . $reg_success_msg . '</div><br>'; } ?>
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
				<div class="col s12 m6 left-align">
					<label><a class="pointer" id="fgpwd_agent">Forgot Password?</a></label>
				</div>
				<div class="col s12 m6 right-align">
					<label><a href="<?php echo base_url('home/vregister'); ?>" class="pointer" id="register_agent">Register</a></label>
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
			<form method="POST" action="/home/resetpwd_vendor">
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
				<div class="col s12 center-align">
					<button type="submit" id="rp_rpwd" class="btn waves-effect waves-light btn col s12">
						Reset Password
					</button>
				</div>
			</div>
			</form>
		</div>
	</main>
	<?php $this->load->view('vendor/components/_foot'); ?>
<?php $this->load->view('vendor/components/_vjs'); ?>
<script type="text/javascript" src="<?php echo site_url('js/vregister.js'); ?>"></script>
<script>
</script>
</body>
</html>