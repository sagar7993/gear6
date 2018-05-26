<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Write To Users - Admin Panel</title>
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
					Write to Users
					<small>Contact one or more Users</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">User  Dashboard</li>
				</ol>
			</section>
			<section class="content">
				<div class='callout callout-info'>
					<h4>Custom Message Panel for Users</h4>
					<p>Write a Greeting/Appreciation/Message/Warning to one or many Users...</p>
				</div>
				<div class="light-box auto-overflow">
					<div class="msg-radio-grp">
						<div class="col-xs-12 ">
							<div class="col-xs-3" style="margin-top:15px!important;">
								<div class="form-group col-xs-12">
									<div class="checkbox" style="">
										<label class="paymode">
											<input type="radio" name="message-type" value="Greeting" onchange="validation();">
											<span style="margin-left:5px">Greeting</span>
										</label>
									</div>
								</div>
							</div>
							<div class="col-xs-3" style="margin-top:15px!important;">
								<div class="form-group col-xs-12">
									<div class="checkbox" style="">
										<label class="paymode">
											<input type="radio" name="message-type" value="Appreciation" onchange="validation();">
											<span style="margin-left:5px">Appreciation</span>
										</label>
									</div>
								</div>
							</div>
							<div class="col-xs-3" style="margin-top:15px!important;">
								<div class="form-group col-xs-12">
									<div class="checkbox" style="">
										<label class="paymode">
											<input type="radio" name="message-type" value="Warning" onchange="validation();">
											<span style="margin-left:5px">Warning</span>
										</label>
									</div>
								</div>
							</div>
							<div class="col-xs-3" style="margin-top:15px!important;">
								<div class="form-group col-xs-12">
									<div class="checkbox" style="">
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
				<br/>
				<div class="light-box auto-overflow">
					<div class="col-xs-12 ">
						<div class="col-xs-4" style="margin-top:15px!important;">
							<div class="form-group col-xs-12">
								<div class="checkbox" style="">
									<label class="paymode">
										<input type="checkbox" class="sc-cb" id="select_all" name="paymt" value="-1" onchange="validation();">
										<span style="margin-left:5px">
											<strong class="green-text">Select All Users</strong>
										</span>
									</label>
								</div>
							</div>
						</div>
						<div class="col-xs-8" style="margin-top:15px!important;">
							<div class="form-group col-xs-12">
								<input type="text" class="form-control" name="user" id="user" placeholder="User" onchange="validation();">
							</div>
						</div>
					</div>
				</div>
				<br/>
				<div class="col-xs-12 margin-bottom-10px">
					<textarea class="form-control col-xs-12" name="comments" id="comments" placeholder="Write your message here ..." oninput="validation();"></textarea>
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
var message_type; var user; user_id = ""; var comments; var user_in_ajax_list = 0; var all_users_selected = 0;
$(function() {
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
	$("input[type='checkbox'], input[type='radio']").icheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
	$('#select_all').on('ifChecked', function() {
		$('#user').attr('disabled','disabled');
		document.getElementById("user").value = "";
		user_in_ajax_list = 0;
		all_users_selected = 1;
		user_id = "";
		validation();
	});
	$('#select_all').on('ifUnchecked', function() {
		$('#user').removeAttr('disabled');
		document.getElementById("user").value = "";
		user_in_ajax_list = 0;
		all_users_selected = 0;
		user_id = "";
		validation();
	});
});
function validation() {
	message_type = $("input[type='radio'][name='message-type']:checked").map(function() { return $(this).val(); }).get();
	comments = $("#comments").val();
	if(all_users_selected == 0) {
		if(user_id.length > 0 && comments.length > 10 && user_in_ajax_list == 1) {
			$("#sendSMS").removeAttr('disabled');
		} else {
			$("#sendSMS").attr('disabled','disabled');
			$("#sendEmail").attr('disabled','disabled');
		}
		if(user_id.length > 0 && message_type.length > 0 && comments.length > 10 && user_in_ajax_list == 1) {
			$("#sendEmail").removeAttr('disabled');
			$("#sendSMS").removeAttr('disabled');
		} else {
			$("#sendEmail").attr('disabled','disabled');
		}
	} else if(all_users_selected == 1) {
		if(comments.length > 10 && user_in_ajax_list == 0) {
			$("#sendSMS").removeAttr('disabled');
		} else {
			$("#sendSMS").attr('disabled','disabled');
			$("#sendEmail").attr('disabled','disabled');
		}
		if(message_type.length > 0 && comments.length > 10 && user_in_ajax_list == 0) {
			$("#sendEmail").removeAttr('disabled');
			$("#sendSMS").removeAttr('disabled');
		} else {
			$("#sendEmail").attr('disabled','disabled');
		}
	}
}
$('#user').on('input', function() {
	user_in_ajax_list = 0;
	all_users_selected = 0;
	user_id = "";
	validation();
});
$('#sendEmail').on('click', function(e) {
	var form = '<form action="/home/sendUserEmail" method="POST">';
	form += '<input type="hidden" name="message_type" value="' + message_type[0]; + '" />';
	form += '<input type="hidden" name="user_id" value="' + user_id + '" />';
	form += '<input type="hidden" name="comments" value="' + comments + '" />';
	form += '<input type="submit" name="user_writeto_submit" value="submit" /></form>';
	var created_form = $(form).appendTo('body');
	created_form.submit();
});
$('#sendSMS').on('click', function(e) {
	var form = '<form action="/home/sendUserSMS" method="POST">';
	form += '<input type="hidden" name="user_id" value="' + user_id + '" />';
	form += '<input type="hidden" name="comments" value="' + comments + '" />';
	form += '<input type="submit" name="user_writeto_submit" value="submit" /></form>';
	var created_form = $(form).appendTo('body');
	created_form.submit();
});
$("#user").autocomplete ({
	source: function(request, response) {
		$.ajax({
			type: "POST",
			url: "/admin/manageoffer/get_user_ajax",
			data: {"user_id": request.term},
			dataType: "json",
			success: function (data) {
				user_in_ajax_list = 0;
				all_users_selected = 0;
				user_id = "";
				response(data);
			},
			error: function (error) {
				console.log(error);
			}
		});
	},
	minLength: 3,
	select: function (a, b) {
		$("#user").val(b.item.value);
		user_id = $("#user").val();
		user_id = user_id.substring(user_id.indexOf("(")+1).trim();
		user_id = user_id.substring(user_id.indexOf("(")+1, (user_id.length-1)).trim();
		user_in_ajax_list = 1;
		all_users_selected = 0;
		validation();
	}
});
</script>
</body>
</html>