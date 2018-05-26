<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - User Profile Page (<?php echo convert_to_camel_case($this->session->userdata('name')); ?>)</title>
	<link rel="shortcut icon" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/materialize.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/ionicons.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/module.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/datatables/dataTables.bootstrap.css'); ?>"   />
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/jQueryUI/jquery-ui.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/custom-jquery.css'); ?>">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/ustyle.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/flatui.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Raleway"  type="text/css">
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
	<div class="load-wrap">
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
	<?php $this->load->view('user/components/__head'); ?>
	<?php $this->load->view('user/components/_sidebar'); ?>
	<aside class="right-side1">
		<?php if(isset($pwd_errors)) { echo '<br/><div class="alert alert-danger" role="alert">' . $pwd_errors . '</div>'; } ?>
		<section class="user-header-container">
			<div class="uhBox">
				<div class="uhNL">
					<div class="uhName">
						<i class="material-icons left">account_circle</i>
						<span class="user-profile-name"><?php echo convert_to_camel_case($this->session->userdata('name')); ?></span>
					</div>
					<?php if($user_addresses[0]['AddrLine1'] != "") { ?>
					<div class="uhLoc clearfix">
						<i class="material-icons">pin_drop</i>
						<span><?php echo convert_to_camel_case($user_addresses[0]['AddrLine1'])." , ".convert_to_camel_case($user_addresses[0]['LocationName']); ?></span>
					</div>
					<?php } ?>
				</div>
				<div class="uhPE">
					<div class="uhPhone">
						<i class="material-icons">phonelink_ring</i>
						<span>&nbsp;<?php echo $user_addresses[0]['Phone']; ?></span>
					</div>
					<div class="uhPhone">
						<i class="material-icons">email</i>
						<span>&nbsp;<?php echo $user_addresses[0]['Email']; ?></span>
					</div>
				</div>
				<div class="uhEB">
					<div class="uhEd">
						<div class="uhEdit">
							<i class="material-icons">border_color</i>
							<span><a href="<?php echo base_url('user/account/uprofile'); ?>" class="white-font">Edit</a></span>
						</div>
						<div class="uhEdit">
							<i class="material-icons">loupe</i>
							<span><a class="white-font" href="<?php echo site_url('user/userhome'); ?>">Book New</a></span>
						</div>
					</div>
				</div>
			</div>
		</section>
		<div class="bc">
			<div class="bcItem"><a href="<?php echo site_url('user/userhome'); ?>">Home</a></div>
			<span class="arrw">>></span>
			<div class="bcItem active-bc">My Profile</div>
		</div>
		<div class="user-order-block">
			<section class="section-center1">
				<div class="section-header-2">
					<span class="confirm-title">Update Mobile Number</span>
				</div>
				<div class="section-content1">
					<div class="col-xs-12 fields-update-container uprofile-box"> <!-- media -->
						<form method="POST" action="/user/account/change_account_Phone">
						<div class="alert alert-danger modal-error-text" role="alert" id="chacphmsg" style="display:none;margin-bottom:0"></div>
						<div class="col-xs-12 fields-container" id="chacphcont">
							<div class="col-xs-6 cust-col-10 field-box">
								<label>New Mobile Number (Note: Your login details will be changed)</label><br />
								<input type="text" class="form-control white-bg" readonly name="chacphone" id="chac_phone" value="<?php echo $user_addresses[0]['Phone']; ?>" placeholder="Mobile Number">
							</div>
							<div class="col-xs-3 user-field-edit edit_field" id="chac_phone_edit"></div>
							<div class="col-xs-2 col-xs-offset-1" style="margin-top: 50px;"><a id="chacphsotp" style="cursor: pointer;">Send OTP</a></div>
						</div>
						<div class="col-xs-12 fields-container" id="chacphotpcont" style="display:none;">
							<div class="col-xs-6 field-box">
								<label>Enter OTP</label><br />
								<input type="text" class="form-control white-bg" name="chacphotp" id="chacphotp" placeholder="OTP">
							</div>
							<div class="button-container col-xs-6" style="margin-top: 50px;">
								<button type="submit" class='next btn btn-primary btnUpdate-pu' id="chacphsubmit">
									Update Phone
								</button>
							</div>
						</div>
						</form>
					</div>
				</div>
			</section>
			<section class="section-center1">
				<div class="section-header-2">
					<span class="confirm-title">Personal Info</span>
				</div>
				<div class="section-content1" id="scroll-top-content">
					<div class="col-xs-12 fields-update-container uprofile-box">
						<div class="col-xs-6 fields-container">
							<div class="col-xs-10 cust-col-10 field-box ">
								<label>Full Name</label><br>
								<input type="text" class="form-control white-bg" oninput="checkfield();" readonly name="name" value="<?php echo convert_to_camel_case($user_addresses[0]['UserName']); ?>" id="sc_name" placeholder="Rakesh Vaddadi">
							</div>
							<div class="col-xs-2 user-field-edit edit_field" id="sc_name_edit"></div>
						</div>
						<div class="col-xs-6 fields-container padding-left-0">
							<div class="col-xs-10 cust-col-10 field-box padding-left-0">
								<label>Contact Number</label><br>
								<input type="text" class="form-control white-bg" readonly name="phone" value="<?php echo $user_addresses[0]['Phone']; ?>" id="user_phone" placeholder="8123885915">
							</div>
						</div>
						<div class="col-xs-12 fields-container" id="cityselection"<?php if(isset($user_addresses[0]['UserAddrId'])) { echo ' style="display:none;"'; } ?>>
							<div class="col-xs-10 cust-col-10 field-box">
								<label>City</label><br>
								<select class="form-control styled-select2" id="city" onchange="cityChanged();" name="city">
									<option value="" disabled selected style='display:none;'>--&nbsp;&nbsp;Select City&nbsp;&nbsp;--</option>
									<?php
										if(isset($cities) && isset($user_addresses)) {
											foreach ($cities as $city) {
												if($user_addresses[0]['CityId'] == $city->CityId) {
													echo '<option value="'.$city->CityId.'" selected>'.convert_to_camel_case($city->CityName).'</option>';
												} else {
													echo '<option value="'.$city->CityId.'">'.convert_to_camel_case($city->CityName).'</option>';
												}
											}
										}
									?>
								</select>
							</div>
						</div>
						<div class="col-xs-12">
							<?php
								if (isset($user_addresses) && count($user_addresses) >= 1) {
									$full_name = $user_addresses[0]['UserName'];
									$email = $user_addresses[0]['Email'];
									$readonly = ' readonly="true"';
									if(isset($user_addresses[0]['UserAddrId'])) {
										$readonly1 = ' readonly="true"';
									} else {
										$readonly1 = '';
									}
								} else {
									$full_name = '';
									$email = '';
									$readonly = '';
									$readonly1 = '';
								}
							?>
							<div class="col-xs-10 cust-col-10 field-box">
								<label>Address</label><br>
							</div>
							<div class="col-xs-12 addr-block-ua" id="addr-block">
								<div class="form-group col-xs-6">
									<input type="text" id="addr1"<?php echo $readonly1; ?> style="margin-left: -8px;" class="form-control" oninput="checkfield();" name="adln1" id="adln1" placeholder="Address Line1">
								</div>
								<div class="form-group col-xs-6">
									<input type="text" id="addr2"<?php echo $readonly1; ?> style="margin-left: -8px;" class="form-control" oninput="checkfield();" name="adln2" id="adln2" placeholder="Address Line2">
								</div>
								<div class="form-group col-xs-6 loc-1">
									<input type="text" id="addr_location"<?php echo $readonly1; ?> style="margin-left: -8px;" class="form-control area" oninput="checkfield();" name="location" placeholder="Location">
								</div>
								<div class="form-group col-xs-6">
									<input type="text" id="addr_landmark"<?php echo $readonly1; ?> style="margin-left: -8px;" class="form-control" oninput="checkfield();" name="landmark" placeholder="Landmark (Optional)">
								</div>
							</div>
						</div>
					</div>
					<br>
					<?php if(isset($user_addresses) && count($user_addresses) >= 1 && isset($user_addresses[0]['UserAddrId'])) { ?>
					<div class="col-xs-12 addr-links">
						<p class="addr-block-open-ua" id="addr-block-open">Choose A Default Address From The List</p>
						<p class="addr-block-open-ua" id="add-new-addr">Add New Address</p>
					</div>
					<div class="col-xs-12 addr-select-container" id="addr-select-container" style="display:none;" >
						<section id="cslide-slides" class="cslide-slides-master clearfix">							
							<div class="cslide-slides-container clearfix">
								<div class="cslide-slide">
									<?php
										$count = 0;
										foreach($user_addresses as $user_address) {
											if($count == 0) {
												$checked = ' checked';
											} else {
												$checked = '';
											}
											echo '<div class="user-addr-container-select">
												<div class="user-addr-title">User Address ' . ($count + 1) . '</div>
												<div class="user-addr-content" id="addr_content_' . ($count + 1) . '">
													<div>' . convert_to_camel_case($user_address['AddrLine1']) . '</div>
													<div>' . convert_to_camel_case($user_address['AddrLine2']) . '</div>
													<div>' . $user_address['LocationName'] . '</div>
													<div>' . convert_to_camel_case($user_address['Landmark']) . '</div>
													<div style="display:none;">' . $user_address['CityId'] . '</div>
												</div>
												<div class="checkbox addr-select-radio" style="">
													<label class="addr-select"><input type="radio"' . $checked . ' class="addr_radio" id="addr_' . ($count + 1) . '" name="addr" value="' . $user_address['UserAddrId'] . '">
													<span style="margin-left:5px">Select Address</span></label>
												</div> 
											</div>';
											$count += 1;
											if ($count % 3 == 0 && $count / 3 >= 1) {
												echo '</div><div class="cslide-slide">';
											}
										}
									?>
								</div>
							</div>
							<div class="cslide-prev-ua-next clearfix">
								<span class="cslide-prev-ua"><span class="glyphicon glyphicon-chevron-left"></span></span>
								<span class="cslide-next-ua"><span class="glyphicon glyphicon-chevron-right"></span></span>
							</div>
						</section><!-- /sliding content section -->
					</div>
					<?php } else { ?>
						<div class="col-xs-12 addr-links">
							<p class="addr-block-open-ua" id="addr-block-open" style="display:none;"></p>
							<p class="addr-block-open-ua" id="add-new-addr">Add New Address</p>
						</div>
					<?php } ?>
					<div class="button-box-contact col-xs-12">
						<div class="button-container col-xs-6 col-md-offset-5">
							<button class='next btn waves-effect waves-light flat-btn' id="addrSubmit" disabled="true">
								Update
							</button>
							<button class='next btn btn-primary btnCancel-pu' id="addrCancel" >
								Cancel
							</button>
						</div>
					</div>
				</div>
			</section>
			<section class="section-center1" id="show_referral_url">
				<div class="section-header-2">
					<span class="confirm-title">Account Info</span>
				</div>
				<div class="section-content1">
					<div class="col-xs-8 fields-update-container uprofile-box">
						<div class="col-xs-12 fields-container">
							<div class="col-xs-8 cust-col-10 field-box ">
								<label>Registered Mobile Number</label><br>
								<input type="text" class="form-control white-bg" readonly name="phone" value="<?php echo $user_addresses[0]['Phone']; ?>" id="sc_name" placeholder="8123885915">
							</div>
						</div>
						<div class="col-xs-12 fields-container">
							<div class="col-xs-8 cust-col-10 field-box ">
								<label>Email ID</label><br>
								<input type="text" class="form-control white-bg" oninput="checkfield1();" readonly name="email" id="email_id" value="<?php echo $user_addresses[0]['Email']; ?>" placeholder="rakhe123kbl@gmail.com">
							</div>
							<div class="col-xs-2 user-field-edit edit_field" id="email_id_edit"></div>
						</div>
						<?php if($this->session->userdata('mode') == 'Email') { ?>
						<div class="col-xs-12 fields-container">
							<div class="col-xs-8 cust-col-10 field-box">
								<label>Password</label><br>
								<input type="password" class="form-control white-bg" oninput="checkfield1();" readonly name="cp" id="old_pwd" placeholder="##########">
							</div>
							<div class="col-xs-2 user-field-edit edit_pwd" id="pwd_edit"></div>
						</div>
						<div class="col-xs-12 fields-container" id="new-pwd-block" style="display:none">
							<div class="col-xs-8 cust-col-10 field-box">
								<input type="password" class="form-control blue-bg" oninput="checkfield1();"  name="np" id="new_pwd" placeholder="New password">
								<br>
								<input type="password" class="form-control blue-bg" oninput="checkfield1();"  name="cnp" id="confirm_new_pwd" placeholder="Confirm New password">
							</div>
						</div>
						<?php } ?>
						<?php if(isset($referral_code)) { ?>
						<div class="col-xs-12 fields-container">
							<div class="col-xs-12 field-box">
								<label>Your Referral Code. Check terms and conditions for more details.</label><br>
								<input type="text" class="form-control" readonly placeholder="New password" value="<?php echo $referral_code; ?>">
							</div>
						</div>
						<?php } ?>
					</div>
					<br>
					<div class="button-box-contact col-xs-12">
						<div class="button-container col-xs-6 col-md-offset-5">
							<button class='next btn waves-effect waves-light flat-btn' id="pwdSubmit" disabled="true">
								Update
							</button>
							<button class='next btn btn-primary btnCancel-pu' id="pwdCancel" >
								Cancel
							</button>
						</div>
					</div>
				</div>
			</section>
		</div>
	</aside>
	<?php $this->load->view('user/components/_foot'); ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/materialize.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/account.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/signup.js?v=1.0'); ?>"></script>
<script>
	<?php echo 'var univ_login_mode = "' . $this->session->userdata('mode') . '";'; ?>
	<?php if(isset($uprofile)) { echo 'var page_id = "' . $uprofile . '";'; } ?>
	if((typeof page_id !== "undefined") && (page_id == 1 || page_id == '1')) {
		var availableLocations;
		var univ_username = "<?php echo convert_to_camel_case($this->session->userdata('name')); ?>";
		var univ_email = "<?php echo $this->session->userdata('email'); ?>";
		var is_pwd_changing = false;
	}
	$(function() {
		$("input[type='checkbox'], input[type='radio']").icheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green'
		});
		<?php if(!$this->input->cookie('CityId')) { echo "openCityModal();"; } ?>
		<?php if(isset($is_first_login) && $is_first_login == 1) { echo "openFirstTimeLoginModal();"; } ?>
	});
</script>
</body>
</html>