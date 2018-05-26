<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Panel - Account Settings</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('vendor/components/_vcss'); ?>
</head>
<body>
	<?php $this->load->view('vendor/components/_head'); ?>
	<?php if(isset($v_is_logged_in) && $v_is_logged_in == 1) { ?>
		<div class="wrapper row-offcanvas row-offcanvas-left">
			<?php $this->load->view('vendor/components/_sidebar'); ?>
			<aside class="right-side">
				<section class="content-header">
					<h1>
						Account Settings
						<small>Update Account Details</small>
					</h1>
				</section>
				<form method="POST" action="/vendor/profile/settings">
				<section class="area-content">
					<div class="col-xs-12 area-box">
						<div class="pickup-title teal-bold">Update Password</div>
						<div class="col-xs-12 fields-update-container">
							<?php if(isset($cp_error) && $cp_error == 0) { ?>
								<div style="color: #2c858d; margin-left: 41%;"><b>Password successfully updated.</b></div>
							<?php } ?>
							<div class="col-xs-4 col-md-offset-4 form-group fields-container">
								<div class="col-xs-12 field-box">
									<input data-type="pwd" data-mandatory="true" type="password" class="form-control" name="pwd" id="pr_pwd" placeholder="Your Current Password">
								</div>
								<div class="col-xs-12 field-box">
									<input data-type="pwd1" data-mandatory="true" type="password" class="form-control" name="fu_pwd" id="fu_pwd" placeholder="New Password">
								</div>
								<div class="col-xs-12 field-box">
									<input data-type="pwd2" data-mandatory="true" type="password" class="form-control" name="rf_pwd" id="rf_pwd" placeholder="Retype New Password">
								</div>
							</div>
						</div>
						<div class="button-box-contact col-xs-12">
							<div class="button-container col-xs-6" style="margin-left: 40%;">
								<button type="submit" class='next btn btn-primary btnUpdate-pu' id="vpwd_update">
									Update Password
								</button>
							</div>
						</div>
					</div>
				</section>
				</form>
				<section class="area-content">
					<div class="col-xs-12 area-box">
						<div class="pickup-title teal-bold">Update Slot Interval</div>
						<div class="col-xs-12 fields-update-container">
							
						</div>
					</div>
				</section>
			</aside><!-- /.right-side -->
		</div>
	<?php $this->load->view('vendor/components/_foot'); ?>
	<?php } ?>
<?php $this->load->view('vendor/components/_vjs'); ?>
<script type="text/javascript">
<?php if(isset($cp_error) && $cp_error == 1) { ?>
	showValidation('pr_pwd', 'Invalid current password');
<?php } ?>
$(function() {
	$('#vpwd_update').on('click', function(event) {
		$('form input').each(function() {
			if($(this).data('mandatory') == true) {
				if($(this).data('type') == 'pwd') {
					if($(this).val() == "") {
						showValidation($(this).attr('id'), "Enter current password");
					}
				} else if($(this).data('type') == 'pwd1') {
					if($(this).val() == "") {
						showValidation($(this).attr('id'), "Enter a new password");
					} else if($(this).val().length < 6) {
						showValidation($(this).attr('id'), "Minimum of 6 characters required");
					}
				} else if($(this).data('type') == 'pwd2') {
					if($(this).val() == "") {
						showValidation($(this).attr('id'), "Re-Enter the new password");
					} else if($(this).val().length < 6) {
						showValidation($(this).attr('id'), "Minimum of 6 characters required");
					} else if($(this).val() != $("#fu_pwd").val()) {
						showValidation($(this).attr('id'), "The two new passwords didn't match");
					}
				}
			}
		});
	});
});
</script>
</body>
</html>