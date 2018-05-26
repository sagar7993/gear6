<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - User Details - Admin Panel</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link rel="shortcut icon" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/vstyle.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/ionicons.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/module.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/datatables/dataTables.bootstrap.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/jQueryUI/jquery-ui.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/custom-jquery.css'); ?>">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oxygen" type="text/css">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.partial.css'); ?>">
	<link rel="stylesheet" type="text/css" href="/nhome/js/lib/swal/sweetalert.css">
	<style type="text/css">
		.select2-container{
			height: 3.9rem;
		}
		.select2-container--default .select2-selection--single .select2-selection__rendered {
			margin-top: 5px;
		}
	</style>
</head>
<body>
	<?php $this->load->view('admin/components/_head'); ?>
	<?php if(isset($a_is_logged_in) && $a_is_logged_in == 1) { ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<?php $this->load->view('admin/components/_sidebar'); ?>
		<aside class="right-side">
			<section class="content-header">
				<h1>
					User Details - <?php if (isset($user_addresses) && count($user_addresses) >= 1) { echo convert_to_camel_case($user_addresses[0]['UserName']); } ?>
					<small>Customers</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> User Details</a></li>
					<li class="active"> Customers</li>
				</ol>
			</section>
			<div class="">
				<section class="section-center1">
					<div class="section-header-2">
						<span class="confirm-title">User Personal Info</span>
					</div>
					<div class="section-content1" id="scroll-top-content">
						<div class="col-xs-6 fields-update-container">
							<div class="col-xs-12 fields-container">
								<div class="col-xs-10 field-box">
									<label>Full Name</label><br>
									<input type="text" class="form-control" oninput="checkfield();" readonly name="name" value="<?php echo convert_to_camel_case($user_addresses[0]['UserName']); ?>" id="sc_name" placeholder="Rakesh Vaddadi">
								</div>
							</div>
							<div class="col-xs-12 fields-container">
								<div class="col-xs-10 field-box">
									<label>Contact Number</label><br>
									<input type="text" class="form-control" oninput="checkfield();" readonly name="phone" value="<?php echo convert_to_camel_case($user_addresses[0]['Phone']); ?>" id="owner_name" placeholder="8123885915">
								</div>
							</div>
							<div class="col-xs-12 fields-container" id="cityselection" style="display:none;">
								<div class="col-xs-10 field-box">
									<label>City</label><br>
									<select class="form-control styled-select" id="city" onchange="cityChanged();" name="city">
										<?php
											foreach ($cities as $city) {
												if($user_addresses[0]['CityId'] == $city->CityId) {
													echo '<option value="' . $city->CityId . '" selected readonly>'.convert_to_camel_case($city->CityName).'</option>';
													break;
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
									} else {
										$full_name = '';
										$email = '';
										$readonly = '';
									}
								?>
								<div class="col-xs-10 field-box">
									<label>Default Address</label><br>
								</div>
								<div class="col-xs-12 addr-block-ua" id="addr-block">
									<div class="form-group col-xs-6" style="width: 51.5%;">
										<input type="text" id="addr1"<?php echo $readonly; ?> style="margin-left: -8px;" class="form-control" oninput="checkfield();" name="adln1" id="adln1" placeholder="Address Line1">
									</div>
									<div class="form-group col-xs-6" style="width:52%">
										<input type="text" id="addr2"<?php echo $readonly; ?> style="margin-left: -8px;" class="form-control" oninput="checkfield();" name="adln2" id="adln2" placeholder="Address Line2">
									</div>
									<div class="form-group col-xs-6 loc-1" style="width: 51.5%;">
										<input type="text" id="addr_location"<?php echo $readonly; ?> style="margin-left: -8px;" class="form-control area" oninput="checkfield();" name="location" placeholder="Location">
									</div>
									<div class="form-group col-xs-6" style="width:52%">
										<input type="text" id="addr_landmark"<?php echo $readonly; ?> style="margin-left: -8px;" class="form-control" name="landmark" placeholder="Landmark (Optional)">
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-6 image-upload-container">
							<div class="col-xs-12 user-image-container custom-upload">
								<input type='file' class="imgInp" id="imgInp1" />
								<br>
								<img id="imgInp1Thumb" src="" class="custom-img" alt="" height="250" />
							</div>
						</div>
						<br>
						<?php if(isset($user_addresses) && count($user_addresses) >= 1) { ?>
						<div class="col-xs-12 addr-links">
							<p class="addr-block-open-ua" id="addr-block-open">Address List</p>
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
														<div style="display:none;">' . convert_to_camel_case($user_address['CityId']) . '</div>
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
						<?php } ?>
						<div class="button-box-contact col-xs-12">
							<div class="button-container col-xs-6 col-md-offset-5">
								<button class='next btn btn-primary btnUpdate-pu' id="addrSubmit" disabled="true">
									Update
								</button>
							</div>
						</div>
					</div>
				</section>
				<section class="section-center1">
					<div class="section-header-2">
						<span class="confirm-title">Account Info</span>
					</div>
					<div class="section-content1">
						<div class="col-xs-6 fields-update-container">
							<div class="col-xs-12 fields-container">
								<div class="col-xs-10 field-box">
									<label>Registered Mobile Number</label><br>
									<input type="text" class="form-control white-bg" oninput="checkfieldUpdate();" name="phone" value="<?php echo convert_to_camel_case($user_addresses[0]['Phone']); ?>" id="user_phone" placeholder="8123885915">
								</div>
							</div>
							<div class="col-xs-12 fields-container">
								<div class="col-xs-10 field-box">
									<label>Email ID</label><br>
									<input type="text" class="form-control white-bg" oninput="checkfieldUpdate();" id="user_email" value="<?php echo $user_addresses[0]['Email']; ?>" placeholder="rakhe123kbl@gmail.com">
								</div>
							</div>
						</div>
						<br>
						<div class="button-box-contact col-xs-12">
							<div class="button-container col-xs-6 col-md-offset-5">
								<button class='next btn btn-primary btnUpdate-pu' id="pwdSubmit" disabled="true">
									Update
								</button>
							</div>
						</div>
					</div>
				</section>
			</div>
		</aside><!-- /.right-side -->
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/select2.full.min.js'); ?>"></script>
<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/audetail.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script type="text/javascript">
	function checkfieldUpdate() {
		phNum = $('#user_phone').val(); email = $('#user_email').val();
		if(IsEmail(email) && isValidPhone(phNum)) {
			$('#pwdSubmit').removeAttr('disabled');
		} else {
			$('#pwdSubmit').attr('disabled', 'true');
		}
	}
	function IsEmail(email) {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(email);
	}
	function isValidPhone(phNum) {
		phNum = Number(phNum);
		if (phNum == "" || phNum < 7000000000 || phNum > 9999999999 || isNaN(phNum)) {
			return false;
		}
		return true;
	}
	$('#pwdSubmit').click(function() {
		Phone = $('#user_phone').val(); Email = $('#user_email').val();
		$.ajax({
			type: "POST",
			url: "/admin/orders/updateUser",
			data: { Phone: Phone, Email: Email, UserId: "<?php echo $UserId; ?>" },
			dataType: "json",
			cache: false,
			success: function(data) {
				if(data.status == 1 && data.email == 1 && data.phone == 1) {
					swal('Success', 'User account details updated successfully.', 'success');
				} else {
					if(data.email == 2) {
						swal('Error', 'Email is already registered with another user.', 'error');
					} else if(data.phone == 2) {
						swal('Error', 'Phone is already registered with another user.', 'error');
					}
				}
			},
			error: function(error) {
				console.log(error); swal('Error', 'Something went wrong.', 'error');
			}
		});
	});
	$(document).ready(function() {
		checkfieldUpdate();
	});
</script>
</body>
</html>