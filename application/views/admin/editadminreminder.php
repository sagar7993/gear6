<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - View Admin Reminder - Admin Panel</title>
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
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>">
	<link rel="stylesheet" type="text/css" href="/nhome/js/lib/swal/sweetalert.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oxygen" type="text/css">
</head>
<body>
	<?php $this->load->view('admin/components/_head'); ?>
	<?php if(isset($a_is_logged_in) && $a_is_logged_in == 1) { ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<?php $this->load->view('admin/components/_sidebar'); ?>
		<aside class="right-side">
			<section class="content-header">
				<h1>
					Dashboard
					<small>Overview &amp; Analysis</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Dashboard</li>
				</ol>
			</section>
			<section>
				<div align="center"><h5>View Admin / Executive Reminders</h5></div>
				<div id="detail_tab" class="table-margin-top">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Reminder Date</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Description</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;User Type</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Remind To</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Rejection Reason</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Send SMS</th>
								<th><i class="fa fa-user"></i> &nbsp;&nbsp;Created By</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Updated By</th>
								<th class="last"><i class="fa fa-user"></i> &nbsp;&nbsp;Enabled</th>
							</tr>
						</thead>
						<tbody>
							<?php $search_flag = array("oiasa89w48kfoweo9234ife", "aviawefiw98r23k4of9ekng", "dslfgliearoi345ksfe", "asdava9dwr4kl32l"); ?>
							<?php if(isset($rows) && count($rows) > 0) { foreach($rows as $row) { ?>
							<tr>
								<td><?php echo $row['timestamp']; ?></td>
								<td><?php echo $row['description']; ?></td>
								<td><?php echo $row['user_type']; ?></td>
								<td><?php echo $row['remind_to']; ?></td>
								<td><?php echo $row['reason']; ?></td>
								<td><?php echo $row['send_sms']; ?></td>
								<td><?php echo $row['createdBy']; ?></td>
								<td><?php echo $row['updatedBy']; ?></td>
								<td>
									<div class="checkbox"><label class="paymode"><input type="checkbox" id="is_enabled_<?php echo $row['id']; ?>" name="is_enabled" value="<?php echo $row['id']?>"<?php if($row['is_enabled'] == '1') { echo ' checked data-checked="1"'; } else { echo ' data-checked="0"'; } ?>></input></label></div>
								</td>
							</tr>
							<?php } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th class="first"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Reminder Date</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Description</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;User Type</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Remind To</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Rejection Reason</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Send SMS</th>
								<th><i class="fa fa-user"></i> &nbsp;&nbsp;Created By</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Updated By</th>
								<th class="last"><i class="fa fa-user"></i> &nbsp;&nbsp;Enabled</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</section>
		</aside>
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/highcharts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script>
$(function() {
	$(document).on('ifChanged', 'input[name=is_enabled]', function() {
		var elem = $(this); var id = elem.val(); var checked = elem.attr("data-checked");
		var form = '<form action="/admin/manageadmin/update_admin_reminder" method="POST">';
		if(checked == '1') {
		    swal({
	    		title: "Are you sure?",
	    		text: "You are about to reject this reminder.",
	    		type: "input",
	    		showCancelButton: true, 
	    		closeOnConfirm: false,
	    		closeOnCancel: true,
	    		animation: "slide-from-top",
	    		inputPlaceholder: "Write something"
	    	}, function(reason) {
	    		if (reason === false) {
	    			$('#is_enabled_' + id).icheck('checked');
	    			return false;
	    		}
	    		if (reason === "") {
	    			swal.showInputError("Please state the reason for rejecting this reminder.");
	    			return false;
	    		}
	    		if (reason.length < 10) {
	    			swal.showInputError("The reason should be atleast 10 or more characters.");
	    			return false;
	    		}
	    		form += '<input type="hidden" name="is_enabled" value="' + 0 + '" />';
	    		form += '<input type="hidden" name="id" value="' + id + '" />';
	    		form += '<input type="hidden" name="reason" value="' + reason + '" />';
	    		form += '<input type="submit" name="admin_submit" value="submit" /></form>';
	    		var created_form = $(form).appendTo('body'); created_form.submit();
		    });
		} else if(checked == '0') {
		    swal({
	    		title: "Are you sure?",
	    		text: "You are about to enable this reminder",
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
    				form += '<input type="hidden" name="is_enabled" value="' + 1 + '" />';
    				form += '<input type="hidden" name="id" value="' + id + '" />';
    			    form += '<input type="submit" name="admin_submit" value="submit" /></form>';
	    			var created_form = $(form).appendTo('body'); created_form.submit();
		    	} else {
	    			$('#is_enabled_' + id).icheck('unchecked');
	    		}
	    	});
		}
	});
});
</script>
</body>
</html>