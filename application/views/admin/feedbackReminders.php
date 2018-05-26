<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Feedback Reminders (In the Review Page) - Admin Panel</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link rel="shortcut icon" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/vstyle.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/ionicons.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/module.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/datatables/buttons.dataTables.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/datatables/dataTables.bootstrap.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/jQueryUI/jquery-ui.css'); ?>">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
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
					<small>Review Page Feedback Reminders</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Orders Dashboard</li>
				</ol>
			</section>
			<section>
				<div id="detail_tab" class="table-margin-top">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Order ID</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Bike Model</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Order Date</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Order Type</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-user"></i> &nbsp;&nbsp;Customer Name</th>
								<th class="last"><i class="fa fa-calendar"></i> &nbsp;&nbsp;Last Followup</th>
							</tr>
						</thead>
						<tbody> 
							<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
							<tr id="<?php echo $count; ?>">
								<td><a target="_blank" href="<?php echo site_url('admin/orders/odetail/' . $row['oid']); ?>?feedback=true" class="order-id-link"><?php echo $row['oid']; ?></a></td>
								<td><?php echo $row['bmodel']; ?></td>
								<td><?php echo $row['odate']; ?></td>
								<td><?php echo $row['otype']; ?></td>
								<td><?php echo $row['phone']; ?></td>
								<td><?php echo $row['username']; ?></td>
								<td><?php echo $row['fupsname']; ?></td>
							</tr>
							<?php $count += 1; } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th class="first"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Order ID</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Bike Model</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Order Date</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Order Type</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-user"></i> &nbsp;&nbsp;Customer Name</th>
								<th class="last"><i class="fa fa-calendar"></i> &nbsp;&nbsp;Last Followup</th>
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
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/app.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.buttons.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jszip.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/buttons.html5.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
</body>
</html>