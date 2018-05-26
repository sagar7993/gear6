<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Order Review</title>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/module.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/custom-jquery.css'); ?>">
	<?php $this->load->view('user/components/_ucss'); ?>
</head>
<body style="background-color:#f6f6f5;min-height:0px !important;">
<main>
	<div class="row remove-margin-bottom loader-gif-container" id="loader-gif">
		<div class="col s12 m12 l12 loader-gif-cover">
			<img class="responsive-img center loader-gif-img" src="<?php echo site_url('nhome/images/Logo-loop.gif'); ?>" id="loading">
		</div>
	</div>
	<?php $this->load->view('user/components/__head'); ?>
	<section class="progress-section1 accordion-tab-review-page">
		<div class="progress-container">
			<div class="tracker active-progress" id="track0">
				<b class="circle-num">1</b>
				<span>Insurance Details</span>
				<i id="tr-i-0"></i>
			</div>
			<div class="tracker" id="track1">
				<b class="circle-num">2</b>
				<span>Personal Info</span>
				<i id="tr-i-1"></i>
			</div>
			<div class="tracker" id="track2">
				<b class="circle-num">3</b>
				<span>Review Order</span>
				<i id="tr-i-2"></i>
			</div>
		</div>
	</section>
	<section class="center1" style="clear:both">
		<div class="filterBYContainer">
			<div style="clear: both;"></div>
			<div id="accordion1" style="background-color:#f6f6f5">
				<h3 id="acc0" class="no-display">
					<span class="margin-left-10px">Registration Details</span>
				</h3>
				<div class="accordionFiltersExpandedView accBodyStyle"><br>
					<div class="form-group col-xs-4 margin-bottom-20px" style="">
						<select class="form-control styled-select2" data-error="Registration year" id="regYear" name="regYear">
							<option></option>
							<option value="2016">2016</option>
							<option value="2015">2015</option>
							<option value="2014">2014</option>
							<option value="2013">2013</option>
							<option value="2012">2012</option>
							<option value="2011">2011</option>
							<option value="2010">2010</option>
							<option value="2009">2009</option>
							<option value="2008">2008</option>
							<option value="2007">2007</option>
							<option value="2006">2006</option>
							<option value="2005">2005</option>
						</select>
					</div>
					<div class="form-group col-xs-4 margin-bottom-20px" style="">
						<select class="form-control styled-select2" data-error="Expiry Date" id="expDate" name="expDate">
							<option></option>
							<option value="10">Next 10 Days</option>
							<option value="30">Next 30 Days</option>
							<option value="60">More than 60 Days</option>
							<option value="0">Already Expired</option>
						</select>
					</div>
					<div class="form-group col-xs-4 margin-bottom-20px" style="">
						<select class="form-control styled-select2"  data-error="Previous Insurer" id="prevIns" name="prevIns">
							<option></option>
							<?php
								if (isset($insurers)) {
									foreach ($insurers as $insurer) {
										echo '<option value="' . $insurer->InsurerId . '">' . $insurer->InsurerName . '</option>';
									}
								}
							?>
						</select>
					</div>
					<div class="form-group col-xs-6 margin-bottom-20px" style="">
						<select class="form-control styled-select2" data-error="Approximate Time" id="approximateTime" name="approximateTime">
							<option></option>
							<option value="8">8 AM</option>
							<option value="9">9 AM</option>
							<option value="10">10 AM</option>
							<option value="11">11 AM</option>
							<option value="12">12 PM</option>
							<option value="13">1 PM</option>
							<option value="14">2 PM</option>
							<option value="15">3 PM</option>
							<option value="16">4 PM</option>
							<option value="17">5 PM</option>
						</select>
					</div>
					<div class="form-group col-xs-6 margin-bottom-20px" style="">
						<?php if(isset($is_logged_in) && $is_logged_in == 0) { ?>
						<div class="col s12 m5" style="">
							<input type="text" class="white-bg" style="" maxlength="10" name="phone" id="phNum" placeholder="Mobile Number" data-error="Mobile Number">
						</div>
						<?php } else { ?>
						<div class="col s12 m5" style="">
							<input type="text" class="white-bg" id="phNum" value="<?php echo $this->session->userdata('phone'); ?>" disabled>
							<div><a href="/user/account/uprofile/3" style="color: #000000;">Change Account Phone Number</a></div>
						</div>
						<?php } ?>
					</div>
					<div class="form-group col-xs-6" style="margin-top: 0.5%;color: #a7a7a7;font-weight:700;">
						<label><input id="claims_made" type="checkbox" name="isClaimed" value="1"><span style="margin-left:10px;margin-bottom:1px;">I have made Claims in Previous Policy</span></label>
					</div>
					<div class="form-group col-xs-6" style="margin-right:1%;float:right">
						<a class="next btn waves-effect waves-light next-step-btn" id="zero">
							Next Step
						</a>
					</div>
				</div>
				<h3 id="acc1" class="ui-state-disabled no-display" style="display:none">
					<span class="margin-left-10px">Personal Info</span>
				</h3>
				<div class="accordionFiltersExpandedView accBodyStyle">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 padding-0">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padding-0">
								<?php
									if(isset($bikedetails)) {
										$reg_num = $bikedetails['RegNum'];
										$bike_num = $bikedetails['BikeNumber'];
									} else {
										$reg_num = '';
										$bike_num = '';
									}
									if (isset($user_addresses) && count($user_addresses) > 0) {
										$readonly = ' readonly="true"';
										if(isset($user_addresses[0]['UserAddrId'])) {
											$readonly1 = ' readonly="true"';
										} else {
											$readonly1 = '';
										}
									} else {
										$readonly = '';
										$readonly1 = '';
									}
									if (isset($u_name) && count($u_email) > 0) {
										$full_name = $u_name;
										$email = $u_email;
									} else {
										$full_name = '';
										$email = '';
									}
									if(isset($serid) && $serid == 3) {
										$width_class = "col-xs-6 col-sm-6 col-md-6 col-lg-6";
									} else {
										$width_class = "col-xs-4 col-sm-4 col-md-4 col-lg-4";
									}
								?>
								<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6 full-name">
									<input type="text" class="form-control "<?php echo $readonly; ?> data-error="Full Name" name="full_name" value="<?php echo $full_name; ?>" id="full_name" placeholder="Full Name">
								</div>
								<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6 email-id">
									<input type="email" class="form-control "<?php echo $readonly; ?> data-error="e-Mail" name="email" value="<?php echo $email; ?>" id="email" placeholder="Email Id">
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padding-0" id="addr-block">
								<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6 margin-bottom-0">
									<input type="text" data-error="Flat/ House No." class="form-control"<?php echo $readonly1; ?> name="adln1" id="adln1" placeholder="Flat/House No.">
								</div>
								<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
									<input type="text" data-error="Street" class="form-control"<?php echo $readonly1; ?> name="adln2" id="adln2" placeholder="Street Name">
								</div>
								<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
									<input type="text" data-error="Location" class="form-control area"<?php echo $readonly1; ?> name="location" id="location" placeholder="Location">
								</div>
								<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
									<input type="text" data-error="Landmark" class="form-control"<?php echo $readonly1; ?> name="landmark" id="landmark" placeholder="Landmark (Optional)">
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 padding-0">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 remarks-box padding-left-20px">
									<textarea data-error="Comments" style="height: 190px !important;" class="form-group col-xs-12  text-area" name="comments" id="comments" placeholder="<?php if($serid == 2 || $serid == 3) { echo 'Enter your Query / Repair Description (in not less than 10 Characters)'; } else { echo 'Any other comments on your order (Optional)'; } ?>"></textarea>
								</div>
							</div>
						</div>
					</div>
					<?php if(isset($is_logged_in) && $is_logged_in == 1 && isset($user_addresses) && count($user_addresses) >= 1 && isset($user_addresses[0]['UserAddrId'])) { ?>
					<div class="col-xs-12 addr-links">
						<p class="addr-block-open" id="addr-block-open">Choose from your Address List</p>
						<p class="addr-block-open" id="add-new-addr">Add New Address</p>
					</div>
					<div class="col-xs-12 addr-select-container" id="addr-select-container" style="display:none;">
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
							<div class="cslide-prev-next clearfix">
								<span class="cslide-prev"><span class="glyphicon glyphicon-chevron-left"></span></span>
								<span class="cslide-next"><span class="glyphicon glyphicon-chevron-right"></span></span>
							</div>
						</section>
						<br>
					</div>
					<?php } ?>
					<div class="row">
						<div class="col-xs-12">
							<?php if($amenities !== NULL) { ?><p class="col-xs-12" style="float: left;margin-left: 15px;margin-right: 6.5%;color: #028cbc;padding: 10px 0;">Choose Amenities</p><?php foreach($amenities as $amenity) { ?>
								<div class="form-group col-xs-3 col-md-offset-1 need-hpick">
									<div class="checkbox" style="color: #a7a7a7;font-weight:700;"><label class="price"><input type="checkbox" name="amenity[]" class="amenity" value="<?php echo $amenity['AmId']; ?>"><span style="margin-left:5px"><?php echo $amenity['AmName'] . ' - ' . $amenity['AmDesc']; ?></span></label></div>
								</div>
							<?php } } ?>
						</div>
						<div class="col-xs-12">
							<div class="form-group col-xs-12 go-for-review">
								<button class='next btn waves-effect waves-light' id="first" style="font-size:12px;float:right;">
								Go for Review
								</button>
								<button class='previous btn waves-effect waves-light' id="goBack_0" style="font-size:12px;float:right;margin-right:20px;">
									Go Back
								</button>
							</div>
						</div>
					</div>
				</div>
				<h3 id="acc2" class="ui-state-disabled no-display" style="display:none">
					<span class="margin-left-10px">Review your Service Order</span>
				</h3>
				<div class="accordionFiltersExpandedView accBodyStyle"><br>
					<div class="form-group col-xs-3 right-dot-margin height-70" style="">
						<div class="final-options-text col-xs-12">
							<i class="material-icons">perm_data_setting</i>
							<div class="margin-top-5pc">
							<p class="selected-options"><?php if (isset($servicetype)) { echo $servicetype; } ?></p>
							</div>
						</div>
					</div>
					<div class="form-group col-xs-3 right-dot-margin height-70" style="">
						<div class="final-options-text col-xs-12">
							<i class="material-icons">today</i>
							<div class="margin-top-5pc">
							<p class="selected-options"><?php if (isset($servicedate)) { echo convert_to_camel_case($servicedate); } ?></p>
							</div>
						</div>
					</div>
					<div class="form-group col-xs-3<?php if (isset($servicecenter)) { echo " right-dot-margin"; } ?> height-70" style="">
						<div class="final-options-text col-xs-12">
							<i class="material-icons">directions_bike</i>
							<div class="margin-top-5pc">
							<p class="selected-options"><?php if (isset($company) && isset($bikemodel)) { echo convert_to_camel_case($company . ' ' . $bikemodel); } ?></p>
							</div>
						</div>
					</div>
					<?php if (isset($servicecenter)) { ?>
					<div class="form-group col-xs-3 height-70" style="">
						<div class="final-options-text col-xs-12">
							<i class="material-icons">store_mall_directory</i>
							<div class="margin-top-5pc">
							<p class="selected-options"><?php echo convert_to_camel_case($servicecenter); ?></p>
							</div>
						</div>
					</div>
					<?php } ?>
					<?php if(isset($serid) && $serid != 3) { ?>
					<div class="col-xs-12" id="est-price" style="display:none;">
					</div>
					<div class="col-xs-12" id="paymtBlock">
						<div class="col-xs-12">
							<?php if(isset($is_resc_order) && $is_resc_order == 1) { ?>
							<div class="form-group col-xs-6">
								<div class="checkbox" style="color: #a7a7a7;font-weight:700;"><label class="paymode"><input type="radio" name="paymt" value="COD" class="paymt_radio"><span style="margin-left:5px">Cash On Delivery (Rescheduled Order)</span></label></div>
							</div>
							<?php } else { ?>
							<div class="form-group col-xs-6">
								<div class="checkbox" style="color: #a7a7a7;font-weight:700;"><label class="paymode"><input type="radio" name="paymt" value="COD" class="paymt_radio"><span style="margin-left:5px">Cash On Delivery</span></label></div>
							</div>
							<div class="form-group col-xs-6">
								<div class="checkbox" style="color: #a7a7a7;font-weight:700;"><label class="paymode"><input type="radio" name="paymt" value="RP" class="paymt_radio"><span style="margin-left:5px">Online Payment</span></label></div>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="col-xs-6" id="whole_coupon_block">
						<div id="coupon_msg_error" style="display:none; margin-bottom: 32px;">
						</div>
						<div class="row" id="coupon_input_block">
							<div class="input-field col s8">
								<input type="text" class="validate" id="coupon_input" placeholder="Enter Offer / Gift Coupon" style="height: 2.4rem;">
								<label for="coupon_input">Offer / Gift Coupon</label>
							</div>
							<div class="input-field col s4">
								<a class="waves-effect waves-light btn btn-xs" id="coupon_submit" style="color: #FFFFFF;">Apply Coupon</a>
							</div>
						</div>
						<div class="row" id="fcoupon_input_block">
							<div class="input-field col s8">
								<input type="text" class="validate" id="fcoupon_input" placeholder="Enter Referral Coupon" style="height: 2.4rem;">
								<label for="fcoupon_input">Referral Coupon</label>
							</div>
							<div class="input-field col s4">
								<a class="waves-effect waves-light btn btn-xs" id="fcoupon_submit" style="color: #FFFFFF;">Apply Coupon</a>
							</div>
						</div>
					</div>
					<?php } ?>
					<div class="col-xs-12">
						<?php if(isset($is_logged_in) && $is_logged_in == 0) { ?>
						<div class="form-group col-xs-4 otp-textbox">
							<input type="text" class="form-control " name="otp" id="otp" placeholder="Enter 6 digit OTP">
						</div>
						<?php } ?>
						<?php if(isset($serid) && $serid != 3) { ?>
						<div class="col-xs-12" style="color:#a7a7a7"><b>Note:</b> gear6 convenience fee is applicable. <a class="modal-trigger" href="#pricecalc">Calculate</a></div>
						<?php } ?>
						<button class="next btn waves-effect waves-light flat-btn go-back-btn" style="" id="second" <?php if(isset($serid) && $serid != 3) { echo 'disabled'; } ?>>Checkout</button>
						<button class="previous btn waves-effect waves-light flat-btn checkout-btn" style="" id="goBack"> Go Back</button>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div id="pricecalc" class="modal bottom-sheet">
		<div class="modal-content" style="background-color:#ffffff;">
			<div class="row">
				<div class="col s6 offset-s3">
					<h4>Convenience Fee Calculator</h4>
					<div class="input-field col s12">
						<input placeholder="Distance in Kms" id="schomedist" type="text">
						<label for="schomedist">Enter to and fro distance from your location to selected service center</label>
					</div>
					<div class="input-field col s12">
						<div class="input-field col s12">
							<input type="checkbox" name="isaccidental" id="isaccidental" value="1" />
							<label for="isaccidental" style="margin-left: 7%;margin-top: -1%;cursor: pointer; color: #a7a7a7; font-weight:700;">Is the vehicle in breakdown condition?</label>
						</div>
					</div>
					<div class="col s3 offset-s3" style="margin-top: 3%;">
						<a class="waves-effect waves-light btn" id="confeecal">Calculate</a>
					</div>
					<div class="col s3 offset-s1" style="margin-top: 2%;" id="confeevalsec" style="display:none;">
						<div class="collection">
							<a href="javascript:;" class="collection-item" style="text-align:center;"><b id="confeeval"></b></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br/>
	<input type="hidden" id="us_phone" value="<?php if(isset($ph_num)) { echo $ph_num; } ?>"/>
	</main>
	<?php $this->load->view('user/components/_foot'); ?>
	<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/jquery.cookie.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/select2.full.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/materialize.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/signup.js?v=1.0'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/jquery.ui.datepicker.validation.js'); ?>"></script>
	<script type="text/javascript" src="https://checkout.razorpay.com/v1/checkout.js"></script>
	<script type="text/javascript" src="<?php echo site_url('js/review.js?v=1.1'); ?>"></script>
	<script>
		var is_query_order = <?php if(isset($serid) && $serid != 3) { echo 'false'; } else { echo 'true'; } ?>;
		var is_first_login = false; var open_blklogin_modal = false; var open_login_modal = false;
		var availableLocations = [<?php if (isset($areas)) { echo $areas; } ?>];
		var availableRegNums = [<?php if (isset($regnums)) { echo $regnums; } ?>];
		<?php if(isset($is_logged_in) && $is_logged_in == 1) { echo 'var is_logged_in = true;'; } else { echo 'var is_logged_in = false;'; } ?>
		$(function() {
			<?php if(isset($is_resc_order) && $is_resc_order == 1) { ?> $('input[name="paymt"][value="COD"]').icheck('checked', function() { check_final_submit(); }); <?php } ?>
			<?php if(!$this->input->cookie('CityId')) { echo "openCityModal();"; } ?>
			<?php if(isset($is_first_login) && $is_first_login == 1) {
				echo "var is_first_login = true;";
				echo "openFirstTimeLoginModal();";
			} ?>
			<?php
				if(isset($open_blklogin_modal) && $open_blklogin_modal == 1) {
					echo "var open_blklogin_modal = true;";
					echo "openBlkLoginModal();";
				} elseif(isset($open_login_modal) && $open_login_modal == 1) {
					echo "var open_login_modal = true;";
					echo "openLoginModal();";
				}
			?>
			$("#location").autocomplete ({
				source: availableLocations,
				select: function (a, b) {
					$("#location").val(b.item.value);
					showFirstValidation();
				}
			});
			$("#reg_num").autocomplete ({
				source: availableRegNums,
				select: function (a, b) {
					$("#reg_num").val(b.item.value);
					showFirstValidation();
				}
			});
			$('#regYear').select2({
				placeholder: "Registration Year",
				minimumResultsForSearch: 12,
				containerCssClass: ""
			});
			$('#expDate').select2({
				placeholder: "Expiry Date",
				minimumResultsForSearch: 12,
				containerCssClass: ""
			});
			$('#approximateTime').select2({
				placeholder: "Approximate Time",
				minimumResultsForSearch: 12,
				containerCssClass: ""
			});
			$('#prevIns').select2({
				placeholder: "Previous Insurer",
				minimumResultsForSearch: 12,
				containerCssClass: ""
			});
			$(".button-collapse").sideNav();
		});
	</script>
</body>
</html>