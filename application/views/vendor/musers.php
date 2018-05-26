<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Profile - Manage Users</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('vendor/components/_vcss'); ?>
	<link rel="stylesheet" type="text/css" href="/nhome/js/lib/swal/sweetalert.css">
	<style type="text/css">
		.styled-select {
			font-size: 1rem;
		}
		.form-control {
			height: 47px;
		}
	</style>
</head>
<body>
	<?php $this->load->view('vendor/components/_head'); ?>
	<?php if(isset($v_is_logged_in) && $v_is_logged_in == 1) { ?>
		<div class="wrapper row-offcanvas row-offcanvas-left">
			<?php $this->load->view('vendor/components/_sidebar'); ?>
			<aside class="right-side">
				<section class="content-header">
					<h1>
						Manage Users
						<small>Overview</small>
					</h1>
				</section>
				<section class="content">
					<form method="POST" action="/vendor/profile/manage_users">
					<?php if(isset($err_phone)) { echo '<div class="alert alert-danger" role="alert">' . $err_phone . '</div>'; } ?><br>
					<div class="col-xs-12 fields-update-container">
						<div class="col-xs-12 form-group fields-container">
							<div class="col-xs-3 field-box">
								<select data-type="text" data-mandatory="true" data-error="Gender" class="form-control styled-select" name="gender" id="gender">
									<option selected style='display:none;' value=''>Gender</option>
									<option value="Male">Male</option>
									<option value="Female">Female</option>
								</select>
							</div>
							<div class="col-xs-3 field-box">
								<?php
									if(isset($evendor)) {
										echo "<input type='hidden' id='edit_priv' value='" . $evendor->UserPrivilege . "' />";
										echo "<input type='hidden' id='edit_gend' value='" . $evendor->Gender . "' />";
										echo "<input type='hidden' id='edit_add' value='" . $evendor->Address . "' />";
									}
								?>
								<select data-type="text" data-mandatory="true" data-error="Privilege Name" class="form-control styled-select" name="upriv" id="upriv">
									<?php if($this->session->userdata('v_role') == "Admin") { ?>
										<option selected style='display:none;' value=''>Select Privilige</option>
										<option value="Admin">Admin</option>
										<option value="Super User">Super User</option>
										<option value="User">User</option>
									<?php } else { ?>
										<option selected style='display:none;' value='User'>User</option>
									<?php } ?>
								</select>
							</div>
							<div class="col-xs-3 field-box">
								<input data-type="text" data-mandatory="true" data-error="Full Name" type="text" value="<?php if(isset($evendor)) { echo $evendor->VendorName; } ?>" class="form-control" name="fname" id="fname" placeholder="Full Name">
							</div>
							<div class="col-xs-3 field-box">
								<input data-type="text" data-mandatory="true" data-error="Date of birth" type="text" value="<?php if(isset($evendor)) { echo $evendor->DOB; } ?>" class="form-control dpDate2" readonly='true' style="cursor:pointer;" name="dob" id="dob" placeholder="Date of Birth">
							</div>
						</div>
						<div class="col-xs-6 form-group fields-container">
							<div class="col-xs-6 field-box">
								<input data-type="phone" data-mandatory="true" data-error="Phone Number" type="text" <?php if(isset($evendor)) { echo 'value="' . $evendor->Phone . '" readonly'; } ?> class="form-control" name="p_phone" id="p_phone" placeholder="Mobile Number">
							</div>
							<div class="col-xs-6 field-box">
								<input data-mandatory="false" type="text" class="form-control" name="alt_ph" id="alt_ph" value="<?php if(isset($evendor)) { echo $evendor->AltPhone; } ?>" placeholder="Alternate Mobile Number">
							</div>
							<div class="col-xs-6 field-box">
								<input data-type="email" data-mandatory="true" data-error="Email Id" type="text" class="form-control" value="<?php if(isset($evendor)) { echo $evendor->Email; } ?>" name="email" id="email" placeholder="Email Id">
							</div>
						</div>
						<div class="col-xs-6 form-group fields-container">
							<div class="col-xs-12 field-box">
								<textarea data-type="text" data-mandatory="true" data-error="Full Address" type="text" class="form-control" name="address" id="address" placeholder="Full Address"></textarea>
							</div>
						</div>
					</div>
					<?php if(isset($evendor)) { ?>
						<input type="hidden" name="vid" value="<?php echo $evendor->VendorId; ?>" />
					<?php } ?>
					<div class="button-box-contact col-xs-12">
						<div class="button-container col-xs-6 col-md-offset-5">
							<button type="submit" class='next btn btn-primary btnUpdate-pu' id="vend_add">
								Submit
							</button>
						</div>
					</div>
					</form>
				</section>
				<section>
					<div id="detail_tab" class="table-margin-top">
						<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
							<thead>
								<tr>
									<th class="first"><i class="fa fa-bookmark"></i>&nbsp;&nbsp;User Name</th>
									<th style="width:12%"><i class="fa fa-user"></i>&nbsp;&nbsp;Privilege</th>
									<th><i class="fa fa-whatsapp"></i>&nbsp;&nbsp;User Credentials</th>
									<th><i class="fa fa-edit"></i>&nbsp;&nbsp;Modify</th>
								</tr>
							</thead>
							<tbody>
								<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
								<tr id="<?php echo $count; ?>">
									<td><a href="<?php echo site_url('vendor/profile/manage_users/' . $row['VendorId']); ?>" class="order-id-link"><?php echo convert_to_camel_case($row['VendorName']); ?></a></td>
									<td><?php echo $row['UserPrivilege']; ?></td>
									<td><?php echo $row['Phone']; ?></td>
									<td>
										<a href="<?php echo site_url('vendor/profile/manage_users/' . $row['VendorId']); ?>" class="order-id-link">Edit</a>&nbsp;-&nbsp;<a data-cancel="<?php echo site_url('vendor/profile/delete_user/' . $row['VendorId']); ?>" class="order-id-link" id="vendor-delete" style="cursor:pointer;" onclick="deleteOrder();">Delete</a>
									</td>
								</tr>
								<?php $count += 1; } } ?>
							</tbody>
						</table>
					</div>
				</section>
			</aside>
		</div>
	<?php $this->load->view('vendor/components/_foot'); ?>
	<?php } ?>
<?php $this->load->view('vendor/components/_vjs'); ?>
<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
<script type="text/javascript">
$(function() {
	<?php if(isset($evendor)) { ?>
		$('#upriv').val($('#edit_priv').val());
		$('#gender').val($('#edit_gend').val());
		$('#address').val($('#edit_add').val());
	<?php } ?>
	$('#dob').pickadate({
		selectMonths: true, // Creates a dropdown to control month
		selectYears: 60,
		max: -6570,
		closeOnSelect: true,
		container: 'body',
		onOpen: function() {
			$('#dob').val('');
		},
		onSet: function() {
			if($('#dob').val() != "" ) {
				$(this).close();
			}
		}
	});
	$('#vend_add').on('click', function(event) {
		$('form input, form select, form textarea').each(function() {
			if($(this).data('mandatory') == true) {
				if($(this).data('type') == 'text') {
					if($(this).val() == "") {
						showMuserValidation($(this).attr('id'));
					}
				} else if($(this).data('type') == 'phone') {
					if(!isValidPhone($(this).val())) {
						showMuserValidation($(this).attr('id'));
					}
				} else if($(this).data('type') == 'email') {
					if(!IsEmail($(this).val())) {
						showMuserValidation($(this).attr('id'));
					}
				}
			}
		});
	});
});
showMuserValidation = function(id, message) {
	if(typeof message === "undefined") {
		message = 'Please fill valid ' + $('#' + id).data('error');
	}
	$('.error-text').remove();
	if(id == "address") {
		$('#' + id).parent().append('<div class="error-text" style="margin-top: 0px;">' + message + '</div>');
	} else {
		$('#' + id).parent().append('<div class="error-text">' + message + '</div>');
	}
	$('html,body').animate({
		scrollTop: $($('#' + id)).offset().top
	}, 'slow');
	event.preventDefault();
	throw new Error('This is not an error. This is just to abort javascript');
	return false;
}
function IsEmail(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}
isValidPhone = function(phNum) {
	if (phNum == "" || phNum < 7000000000 || phNum > 9999999999 || isNaN(phNum)) {
		return false;
	}
	return true;
}
function deleteOrder() {
	swal({
		title: "Are you sure?",
		text: "You are about to delete this vendor.",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Yes",
		cancelButtonText: "Cancel",
		closeOnConfirm: false,
		closeOnCancel: true,
		animation: "slide-from-top",
	}, function(isConfirm) {
		if (isConfirm) {
		    var cancel_url = $('#vendor-delete').attr("data-cancel");
		    window.location.assign(cancel_url);
		} else {
			//Do Nothing
		}
	});
}
</script>
</body>
</html>