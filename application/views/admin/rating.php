<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Gear6 User Ratings - Admin Panel</title>
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
		<aside class="right-side">
			<section class="content-header">
				<h1>
					Admin Panel
					<small>Gear6 User Ratings</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Gear6 User Ratings</li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class=" col-xs-12 form-group fields-container" id="oType_1">
						<div class="col-xs-5 field-box">
							<div class="form-group">
								<input type="text" value="<?php if(isset($startDate)) { echo $startDate; } ?>" class="form-control date-field" onchange="checkfield();" id="startDate" name="startDate" placeholder="Start Date" readonly onfocus="this.removeAttribute('readonly');">
							</div>
						</div>
						<div class="col-xs-5 field-box">
							<div class="form-group">
								<input type="text" value="<?php if(isset($endDate)) { echo $endDate; } ?>" class="form-control date-field" onchange="checkfield();" id="endDate" name="endDate" placeholder="End Date" readonly onfocus="this.removeAttribute('readonly');">
							</div>
						</div>
						<div class="col-xs-2 field-box">
							<div class="form-group">
								<button class='next btn btn-primary btnUpdate-pu' id="search" disabled>Search</button>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section>
				<div id="detail_tab" class="table-margin-top">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Order Id</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Customer</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Email</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Timestamp</th>
								<th class="last"><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Rating</th>
							</tr>
						</thead>
						<tbody> 
							<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
							<tr id="<?php echo $count; ?>">
								<td><a href="<?php echo site_url('admin/orders/odetail/' . $row['OId']); ?>" target="_blank" class="order-id-link"><?php echo $row['OId']; ?></a></td>
								<td><a href="<?php echo site_url('admin/users/uodetails/' . $row['UserId']); ?>" target="_blank" class="order-id-link"><?php echo convert_to_camel_case($row['UserName']); ?></a></td>
								<td><?php echo $row['Phone']; ?></td>
								<td><?php echo $row['Email']; ?></td>
								<td><?php echo $row['updated_at']; ?></td>
								<td><?php echo $row['rating']; ?></td>
							</tr>
							<?php $count += 1; } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th class="first"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Order Id</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Customer</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Email</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Timestamp</th>
								<th class="last"><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Rating</th>
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
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/exporting.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.knob.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.sparkline.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script type="text/javascript">
	$('.date-field').datepicker ({
		'dateFormat': "yy-mm-dd",
		"setDate": new Date(),
		'autoclose': true
	});
	function checkfield() {
		var x = 0;
		var startDate = $('#startDate').val();
		var endDate = $('#endDate').val();
		if(startDate === "" || startDate == "" || startDate === null || startDate == null) {
			x = 1;
		}
		if(endDate === "" || endDate == "" || endDate === null || endDate == null) {
			x = 1;
		}
		if(x == 0) {
			$("#search").removeAttr('disabled');
		} else {
			$("#search").attr('disabled','disabled');
		}
	}
	$('#search').on('click', function(e) {
		var form = '<form action="/admin/orders/rating" method="POST">';
		form += '<input type="hidden" name="startDate" value="' + $('#startDate').val() + '" />';
		form += '<input type="hidden" name="endDate" value="' + $('#endDate').val() + '" />';
		form += '<input type="submit" name="admin_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body'); created_form.submit();
	});
</script>
</body>
</html>