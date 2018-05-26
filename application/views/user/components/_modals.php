<!--Login modal starts -->
<div class="modal" id="login">
	<div class="modal-dialog">
		<div class="modal-content flat-modal">
			<div class="modal-header custom-modal-header">
				<?php if(!(isset($open_blklogin_modal) && $open_blklogin_modal == 1) && !(isset($is_first_login) && $is_first_login == 1)) { echo '<a class="close sClose modal-close login-modal-close" id="closeM" aria-label="Close"><span aria-hidden="true">X</span></a>'; } ?>
			</div>
			<div class="modal-body signup-modal-body">
				<?php if(isset($login_error_message)) { echo '<div class="alert alert-danger modal-error-text" role="alert" id="bes_login_error">' . $login_error_message . '</div>'; } ?>
				<div class="alert alert-danger modal-error-text" role="alert" id="s_login_error" style="display:none;"></div>
				<form role="form" id="fgl_form" method="POST" action="/user/userhome/login">
					<div class="row">
						<div class="col s12 m6">
							<div class="signup-field-box">
								<div class="signup-field inp-wrapper">
									<i class="fa fa-mobile mob-font"></i>
									<input type="text" class="form-control modalInput" id="phone" name="phone" placeholder="Mobile Number">
								</div> 
							</div>
							<div class="signup-field-box">
								<div class="signup-field inp-wrapper">
									<i class="material-icons">vpn_key</i>
									<input type="password" class="form-control modalInput" id="password" name="password" placeholder="Password">
								</div>
							</div>
							<div class="">
								<input type="hidden" name="red_url" value="<?php echo current_url(); ?>" />
								<div class="login-btn">
									<button type="button" class="btn login_btn waves-effect waves-light" id="l_login">Login</button>
								</div>
							</div>
							<div>
								<div class="col s6 new-user modal-trigger" id="new_user">New User?</div>
								<div class="col s6 forgot-pwd modal-trigger" id="forgot_pwd">Forgot Password?</div>
							</div>
							<input type="hidden" name="fgl_type" id="fgl_type" />
							<input type="hidden" name="fgl_id" id="fgl_id" />
							<input type="hidden" name="fgl_ac_token" id="fgl_token" />
						</div>
					</form>
					<div class="col s1 or-divider-login hide-on-small-only">
						<span class="or-text">OR</span>
					</div>
					<div class="col s12 m5 social-margin social-container">
						<div class="col s12 m12 padding-right-0">
							<div id="fb" class="fb-box"><span class="signup-text l_fbsignin">SignIn<span class="hide-on-small-only">&nbsp;with</span></span><i class="fa fa-facebook fb-icon l_fbsignin"></i></div>
						</div>
						<div class="col s12 m12 padding-right-0">
							<div id="gplus" class="gplus-box"><span class="signup-textg l_gpsignin">SignIn<span class="hide-on-small-only">&nbsp;with</span></span><i class="fa fa-google-plus gplus-icon l_gpsignin"></i></div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Login modal ends-->
<!-- Password OTP modal Starts-->
<div class="modal" id="pwdOtp" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content reset-box">
			<div class="modal-header custom-modal-header">
				<?php if(!(isset($open_blklogin_modal) && $open_blklogin_modal == 1) && !(isset($is_first_login) && $is_first_login == 1)) { echo '<a class="close sClose modal-close login-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></a>'; } ?>
			</div>
			<div class="modal-body signup-modal-body padding-right-0">
				<div class="alert alert-danger modal-error-text" role="alert" id="s_forget_pwd" style="display:none;"></div>
				<div class="row">
					<div class="col s12">
						<div class="signup-field-box">
							<div class="signup-field-box">
								<div class="signup-field inp-wrapper">
									<i class="fa fa-mobile mob-font"></i>
									<input type="text" class="form-control modalInput" name="s_fgot_phone" id="s_fgot_phone" placeholder="Mobile Number">
								</div>
							</div>
						</div>
					</div>
					<div class="col s12">
						<div class="login-btn">
							<button type="button" id="s_fs_otp" class="btn login_btn waves-effect waves-light">Send OTP</button>
						</div>
					</div>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Password OTP modal ends-->
<!-- Password Reset modal Starts-->
<div class="modal" id="pwdReset" tabindex="-1" >
	<div class="modal-dialog">
		<div class="modal-content reset-box">
			<div class="modal-header custom-modal-header">
				<?php if(!(isset($open_blklogin_modal) && $open_blklogin_modal == 1) && !(isset($is_first_login) && $is_first_login == 1)) { echo '<a class="close sClose modal-close login-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></a>'; } ?>
			</div>
			<form method="POST" action="/user/userhome/resetPwd">
				<div class="modal-body signup-modal-body padding-right-0">
					<?php if(isset($pwd_error_message)) { echo '<div class="alert alert-danger modal-error-text" style="margin-bottom:0" role="alert" id="bes_pwd_error">' . $pwd_error_message . '</div>'; } ?>
					<div class="alert alert-danger modal-error-text" role="alert" id="s_f_pwd_error" style="display:none;margin-bottom:0"></div>
					<div class="row">
						<div class="col s12">
							<div class="signup-field-box">
								<div class="signup-field inp-wrapper">
									<i class="fa fa-eye-slash"></i>
									<input type="text" class="form-control modalInput" oninput="checkprfield();" name="sfgot_otp" id="sfgot_otp" placeholder="Enter OTP">
									<a href="#" class="resend" id="sfgot_resend1">Resend OTP</a>
								</div>
							</div>
							<div class="signup-field-box">
								<div class="signup-field inp-wrapper">
									<i class="material-icons">vpn_key</i>
									<input type="password" class="form-control modalInput " oninput="checkprfield();" name="sfgot_pwd" id="sfgot_pwd" placeholder="Choose Password">
									<span class="pwd-hint">(&nbsp; min. six characters &nbsp;)</span>
								</div>
							</div>
							<div class="signup-field-box">
								<div class="signup-field inp-wrapper">
									<i class="material-icons" style="color:rgba(255, 255, 255, 0.82);">vpn_key</i>
									<input type="password" class="form-control modalInput " oninput="checkprfield();" name="sfgot_pwd1" id="sfgot_pwd1" placeholder="Confirm Password">
								</div>
							</div>
						</div>
						<div class="col s12">
							<div class="login-btn margin-top-n10">
								<input type="hidden" name="red_url" value="<?php echo current_url(); ?>" />
								<button type="submit" class="btn login_btn waves-effect waves-light" id="sfgot_submit" disabled>Reset Password</button>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" name="sfgot_phone" id="sfgot_phone" value="<?php if(isset($pwd_reset_phone)) { echo $pwd_reset_phone; } ?>" />
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Password Reset modal ends-->
<!-- SignUp modal Starts-->
<div class="modal" id="signup" tabindex="-1" >
	<div class="modal-dialog">
		<div class="modal-content flat-modal">
			<div class="modal-header custom-modal-header">
				<?php if(!(isset($open_blklogin_modal) && $open_blklogin_modal == 1) && !(isset($is_first_login) && $is_first_login == 1)) { echo '<a class="close sClose modal-close login-modal-close"><span aria-hidden="true">X</span></a>'; } ?>
				<?php if(isset($ref_signup_flag)) { echo '<div class="alert alert-success  modal-error-text" role="alert" style="color: gold;margin-right: 20px;">You were referred by someone. Sign Up now to get Flat Rs. 75 off Coupon.</div>'; } ?>
			</div>
			<form method="POST" action="/user/userhome/signup">
				<div class="modal-body signup-modal-body padding-right-0">
					<div class="alert alert-danger modal-error-text" role="alert" id="signup_error" style="display:none;margin-right:20px;"></div>
					<div class="row">
						<div class="col s12 m6">
							<div class="sign_init" id="sign_init">
								<div class="signup-field-box">
									<div class="signup-field inp-wrapper">
										<i class="fa fa-mobile mob-font"></i>
										<input type="text" class="form-control modalInput" oninput="checkbotp();" name="s_phone" id="s_phone" placeholder="Mobile Number (UserId)">
									</div>
								</div>
								<div class="signup-field-box">
									<div class="signup-field inp-wrapper">
										<i class="fa fa-envelope"></i>
										<input type="text" class="form-control modalInput" oninput="checkbotp();" name="s_email" id="s_email" placeholder="Email Id">
									</div>
								</div>
								<div class="signup-field-box">
									<div class="signup-field inp-wrapper">
										<i class="material-icons">vpn_key</i>
										<input type="password" class="form-control modalInput" oninput="checkbotp();" name="s_pwd" id="s_pwd" placeholder="Choose Password">
										<span class="pwd-hint">(&nbsp; min. six characters &nbsp;)</span>
									</div>
								</div>
								<div class="signup-field-box">
									<div class="signup-field inp-wrapper">
										<i class="material-icons" style="color:rgba(255, 255, 255, 0.82);">vpn_key</i>
										<input type="password" class="form-control modalInput" oninput="checkbotp();" name="s_cpwd" id="s_cpwd" placeholder="Confirm Password">
									</div>
								</div>
								<div class="">
									<input type="hidden" name="red_url" value="<?php echo current_url(); ?>" />
									<div class="login-btn margin-top-n5">
										<button type="button" id="s_sign_up" class="btn login_btn waves-effect waves-light" disabled>Sign Up</button>
									</div>
								</div>
							</div>
							<div class="socialsignup-container" id="social_signup_fields" style="display:none;">
								<div class="signup-field-box">
									<div class="signup-field inp-wrapper">
										<i class="fa fa-mobile mob-font"></i>
										<input type="text" class="form-control modalInput float-left" oninput="checksotp();" name="fg_phone" id="fg_phone" placeholder="Mobile Number (UserName)">
										<a href="#" class="resend" id="fg_send1">Send OTP</a>
									</div>
								</div>
								<div class="signup-field-box">
									<div class="signup-field inp-wrapper">
										<i class="fa fa-eye-slash" style="right:25px"></i>
										<input type="text" class="form-control modalInput float-left" name="fg_otp_ftime" id="fg_otp_ftime" placeholder="Enter OTP">
										<a href="#" class="resend" id="fg_resend1" style="display:none;">Resend OTP</a>
									</div>
								</div>
								<div class="signup-field-box" id="dob-field">
									<div class="signup-field inp-wrapper">
										<i class="fa fa-calendar"></i>
										<input type="text" onchange="checksotp();" id="fg_dob" class="form-control modalInput" name="fg_dob" placeholder="Date of Birth" style="cursor:pointer">
									</div>
								</div>
								<div class="signup-field-box">
									<div class="signup-field inp-wrapper">
										<i class="fa fa-envelope"></i>
										<input type="text" class="form-control modalInput" oninput="checksotp();" name="fg_referral_coupon" id="fg_referral" placeholder="Referral Code">
									</div>
								</div>
								<input type="hidden" name="fg_name" id="fg_name" />
								<input type="hidden" name="fg_type" id="fg_type" />
								<input type="hidden" name="fg_id" id="fg_id" />
								<input type="hidden" name="fg_email" id="fg_email" />
								<input type="hidden" name="fg_ac_token" id="fg_token" />
								<div class="signup-field-box gender-box margin-top-10px" id="fg-gender-field">
									<div class="col s1 signup-field-icon">
										<i class="fa fa-male"></i>
									</div>
									<div class="signup-field sign-radio col s1">
										<div class="checkbox gcb" style="">
											<label class="paymode">
												<input type="radio" class="fg_gender" name="fg_gender" value="male">
											</label>
										</div>
									</div>
									<div class="col s1 signup-field-icon margin-left-22px">
										<i class="fa fa-female"></i>
									</div>
									<div class="signup-field sign-radio col s1"><!-- changedMedia -->
										<div class="checkbox gcb" style="">
											<label class="paymode">
												<input type="radio" class="fg_gender" name="fg_gender" value="female">
											</label>
										</div>
									</div>
								</div>
								<div class="signup-field-box">
									<div class="signup-field col-xs-12">
										<div class="" style=""><label class="termsLabel"><input type="checkbox" id="fg_terms" name="fg_terms" value="1">&nbsp;&nbsp;&nbsp;I agree to the<a href="">&nbsp;Terms &amp; Conditions</a></label></div>
									</div>
								</div>
								<div class="">
									<div class="login-btn margin-top-n10">
										<button type="submit" id="fg_continue" class="btn login_btn continue-btn waves-effect waves-light" disabled>Continue to Profile</button>
									</div>
								</div>
							</div>
							<div class="otp-container" id="otp_container" style="display:none;">
								<div class="signup-field-box">
									<div class="signup-field inp-wrapper">
										<i class="fa fa-eye-slash"></i>
										<input type="text" class="form-control modalInput" name="s_otp_ftime" id="s_otp_ftime" placeholder="Enter OTP">
										<a href="#" class="resend" id="s_resend1">Resend OTP</a>
									</div>
								</div>
								<div class="signup-field-box">
									<div class="signup-field inp-wrapper">
										<i class="fa fa-user"></i>
										<input type="text" class="form-control modalInput " oninput="checkaotp();" name="s_fname" id="s_fname" placeholder="Full Name">
									</div>
								</div>
								<div class="signup-field-box" id="dob-field">
									<div class="signup-field inp-wrapper">
										<i class="fa fa-calendar"></i>
										<input type="text" onchange="checkaotp();" id="s_dob" class="modalInput" name="s_dob" placeholder="Date of Birth" style="cursor:pointer">
									</div>
								</div>
								<div class="signup-field-box">
									<div class="signup-field inp-wrapper">
										<i class="fa fa-envelope"></i>
										<input type="text" class="form-control modalInput" oninput="checkaotp();" name="s_referral_coupon" id="s_referral" placeholder="Referral Code">
									</div>
								</div>
								<div class="signup-field-box gender-box margin-top-10px" id="gender-field">
									<div class="col s1 signup-field-icon">
										<i class="fa fa-male"></i>
									</div>
									<div class="signup-field sign-radio col s1">
										<div class="checkbox gcb" style="">
											<label class="paymode">
												<input type="radio" class="s_gender" name="s_gender" value="male">
											</label>
										</div>
									</div>
									<div class="col s1 signup-field-icon margin-left-22px">
										<i class="fa fa-female"></i>
									</div>
									<div class="signup-field sign-radio col s1"><!-- changedMedia -->
										<div class="checkbox gcb" style="">
											<label class="paymode">
												<input type="radio" class="s_gender" name="s_gender" value="female">
											</label>
										</div>
									</div>
								</div>
								<div class="signup-field-box margin-top-10px">
									<div class="signup-field col s12">
										<div class="" style="">
											<label class="termsLabel">
												<input type="checkbox" id="s_terms" name="s_terms" value="1">&nbsp;&nbsp;&nbsp;I agree to the<a href="">&nbsp;Terms &amp; Conditions</a>
											</label>
										</div>
									</div>
								</div>
								<div class="">
									<div class="login-btn margin-top-n10">
										<button type="submit" id="s_continue" class="btn login_btn continue-btn waves-effect waves-light" disabled>Continue to Profile</button>
									</div>
								</div>
							</div>
						</div>
						<div class="col s12 m6 sign-social">
							<div class="col m2 or-divider">
								<span class="or-text">OR</span>
							</div>
							<div class="col s12 m10 social-margin-signup social-container">
								<div class="col s12 m12 padding-right-0">
									<div id="fb" class="fb-box"><span class="signup-text s_fbsignin">SignUp<span class="hide-on-small-only">&nbsp;with</span></span><i class="fa fa-facebook fb-icon"></i></div>
								</div>
								<div class="col s12 m12 padding-right-0">
									<div id="gplus" class="gplus-box"><span class="signup-textg s_gpsignin">SignUp<span class="hide-on-small-only">&nbsp;with</span></span><i class="fa fa-google-plus gplus-icon s_gpsignin"></i></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- SignUp modal ends-->
<?php if(isset($is_first_login) && $is_first_login == 1) { ?>
<!-- Password change modal starts -->
<div class="modal show" id="pwdreset_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content reset-box">
			<div class="modal-header custom-modal-header">
				<h4 class="modal-title"></h4>
			</div>
			<form role="form" method="POST" action="/user/userhome/resetPwd/1">
				<div class="modal-body signup-modal-body padding-right-0">
					<div class="row">
						<div class="col s12">
							<div class="signup-field-box" id="dob-field">
								<div class="signup-field inp-wrapper">
									<i class="fa fa-calendar"></i>
									<input type="text" onchange="checkasfotp();" id="sf_dob" class="form-control modalInput" name="sf_dob" placeholder="Date of Birth" style="cursor:pointer">
								</div>
							</div>
							<div class="signup-field-box">
								<div class="signup-field inp-wrapper">
									<i class="material-icons">vpn_key</i>
									<input type="password" class="form-control modalInput" oninput="checkasfotp();" name="sf_pswd1" id="sf_pswd1" placeholder="Choose Password">
									<span class="pwd-hint">(&nbsp; min. six characters &nbsp;)</span>
								</div>
							</div>
							<div class="signup-field-box">
								<div class="signup-field inp-wrapper">
									<i class="material-icons" style="color:rgba(255, 255, 255, 0.82);">vpn_key</i>
									<input type="password" class="form-control modalInput" oninput="checkasfotp();" name="sf_pswd2" id="sf_pswd2" placeholder="Confirm Password">
								</div>
							</div>
						</div>
						<div class="signup-field-box gender-box margin-top-10px" id="gender-field">
							<div class="col s1 signup-field-icon margin-left-22px">
								<i class="fa fa-male"></i>
							</div>
							<div class="signup-field col s1">
								<div class="checkbox gcb" style=""><label class="paymode"><input type="radio" class="sf_gender" name="sf_gender" value="male"></label></div>
							</div>
							<div class="col s1 signup-field-icon margin-left-22px">
								<i class="fa fa-female"></i>
							</div>
							<div class="signup-field col s1">
								<div class="checkbox gcb" style=""><label class="paymode"><input type="radio" class="sf_gender" name="sf_gender" value="female"></label></div>
							</div>
						</div>
						<div class="signup-field-box">
							<div class="signup-field col s12">
								<div class="checkbox" style=""><label class="termsLabel"><input type="checkbox" id="sf_terms" name="sf_terms" value="1">&nbsp;&nbsp;&nbsp;I agree to the<a href="">&nbsp;Terms &amp; Conditions</a></label></div>
							</div>
						</div>
						<div class="sign-footer">
							<input type="hidden" name="red_url" value="<?php echo current_url(); ?>" />
							<button type="submit" class="btn login_btn waves-effect waves-light" name="pwdreset" id="sf_pwdreset" disabled>Reset Password</button>
						</div>
					</div>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Password change modal ends-->
<?php } ?>