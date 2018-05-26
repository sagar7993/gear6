<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Write To Vendors - Admin Panel</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link rel="shortcut icon" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/vstyle.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/ionicons.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/module.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/datatables/dataTables.bootstrap.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/jQueryUI/jquery-ui.css'); ?>">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oxygen" type="text/css">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>">
</head>
<body>
	<?php $this->load->view('admin/components/_head'); ?>
	<?php if(isset($a_is_logged_in) && $a_is_logged_in == 1) { ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<?php $this->load->view('admin/components/_sidebar'); ?>
		<aside class="right-side auto-height">
			<section class="content-header">
				<h1>
					Write to Vendors
					<small>Contact one or more Vendors</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Vendor  Dashboard</li>
				</ol>
			</section>
			<section class="content">
				<div class='callout callout-info'>
					<h4>Custom Message Panel for Vendors</h4>
					<p>Write a Greeting/Appreciation/Message/Warning to one or many vendors...</p>
				</div>
				<div class="light-box auto-overflow">
					<div class="msg-radio-grp">
						<div class="col-xs-12 ">
							<div class="col-xs-3">
								<div class="form-group col-xs-12">
									<div class="checkbox" style="margin-top: 25px;">
										<label class="paymode">
											<input type="radio" name="message-type" value="Greeting" onchange="validation();">
											<span style="margin-left:5px">Greeting</span>
										</label>
									</div>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group col-xs-12">
									<div class="checkbox" style="margin-top: 25px;">
										<label class="paymode">
											<input type="radio" name="message-type" value="Appreciation" onchange="validation();">
											<span style="margin-left:5px">Appreciation</span>
										</label>
									</div>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group col-xs-12">
									<div class="checkbox" style="margin-top: 25px;">
										<label class="paymode">
											<input type="radio" name="message-type" value="Warning" onchange="validation();">
											<span style="margin-left:5px">Warning</span>
										</label>
									</div>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group col-xs-12">
									<div class="checkbox" style="margin-top: 25px;">
										<label class="paymode">
											<input type="radio" name="message-type" value="Message" onchange="validation();">
											<span style="margin-left:5px">Message</span>
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="sc-cb-list-box">
					<div class="col-xs-12 sc-list-cb light-box">
						<div class="col-xs-3">
							<div class="form-group col-xs-12">
								<div class="checkbox" style="">
									<label class="paymode">
										<input type="checkbox" class="sc-cb" id="select_all" name="paymt" value="-1" onchange="validation();">
										<span style="margin-left:5px">
											<strong class="green-text">Select All</strong>
										</span>
									</label>
								</div>
							</div>
						</div>
						<?php if(isset($scs) && count($scs) > 0) { foreach($scs as $sc) { ?>
						<?php if($sc->ScId != -1) { ?>
						<div class="col-xs-3">
							<div class="checkbox" style="">
								<label class="scs">
									<input type="checkbox" name="sc_ids[]" id="<?php echo $sc->ScId; ?>" value="<?php echo $sc->ScId; ?>" class="sc-cb" onchange="validation();">
									<span style="margin-left:15px"><?php echo convert_to_camel_case($sc->ScName); ?></span>
								</label>
							</div>
						</div>
						<?php } } } ?>
					</div>
				</div>
				<div class="col-xs-12" style="margin-top: 20px;">
					<div class="col-xs-12">
						<textarea class="form-group col-xs-12" name="comments" 
						id="comments" placeholder="Write your message here ..." oninput="validation();"></textarea>
					</div>
				</div>
				<div class="col-xs-12">
					<div class="col-xs-2"></div>
					<div class="col-xs-3">
						<button type="submit" name="submitform" id="sendEmail" class="btn btn-primary go-btn" disabled>Send Email</button>
					</div>
					<div class="col-xs-2"></div>
					<div class="col-xs-3">
						<button type="submit" name="submitform" id="sendSMS" class="btn btn-primary go-btn" disabled>Send SMS</button>
					</div>
					<div class="col-xs-2"></div>
				</div>
			</section>
		</aside><!-- /.right-side -->
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script>
var message_type; var service_centers = []; var comments;
$(function() {
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
	$("input[type='checkbox'], input[type='radio']").icheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
	$('#select_all').on('ifChecked', function() {
		$('.sc-cb').icheck('checked');
		validation();
	});
	$('#select_all').on('ifUnchecked', function() {
		$('.sc-cb').icheck('unchecked');
		validation();
	});
});
function validation() {
	service_centers = $("input[type='checkbox'][name='sc_ids[]']:checked").map(function() { return $(this).attr("id"); }).get();
	message_type = $("input[type='radio'][name='message-type']:checked").map(function() { return $(this).val(); }).get();
	comments = $("#comments").val();
	if(service_centers.length > 0 && comments.length > 10) {
		$("#sendSMS").removeAttr('disabled');
	} else {
		$("#sendSMS").attr('disabled','disabled');
		$("#sendEmail").attr('disabled','disabled');
	}
	if(service_centers.length > 0 && message_type.length > 0 && comments.length > 10) {
		$("#sendEmail").removeAttr('disabled');
		$("#sendSMS").removeAttr('disabled');
	} else {
		$("#sendEmail").attr('disabled','disabled');
	}
}
$('#sendEmail').on('click', function(e) {
	var form = '<form action="/home/sendVendorEmail" method="POST">';
	form += '<input type="hidden" name="message_type" value="' + message_type[0]; + '" />';
	form += '<input type="hidden" name="service_centers" value="' + service_centers + '" />';
	form += '<input type="hidden" name="comments" value="' + comments + '" />';
	form += '<input type="submit" name="vendor_writeto_submit" value="submit" /></form>';
	var created_form = $(form).appendTo('body');
	created_form.submit();
});
$('#sendSMS').on('click', function(e) {
	var form = '<form action="/home/sendVendorSMS" method="POST">';
	form += '<input type="hidden" name="service_centers" value="' + service_centers + '" />';
	form += '<input type="hidden" name="comments" value="' + comments + '" />';
	form += '<input type="submit" name="vendor_writeto_submit" value="submit" /></form>';
	var created_form = $(form).appendTo('body');
	created_form.submit();
});
</script>
</body>
</html>