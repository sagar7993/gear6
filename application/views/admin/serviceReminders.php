<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Service Reminders - Admin Panel</title>
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
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>">
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
					<small>Service Reminders</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Orders Dashboard</li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="col-xs-4">
							<div class="checkbox" style="">
								<label class="search-filter">
									<input type="radio" name="search-filter" class="odstatusfilter" value="random1" id="radio_1">
									<span style="margin-left:15px">Service Reminder</span>
								</label>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="checkbox" style="">
								<label class="search-filter">
									<input type="radio" name="search-filter" class="odstatusfilter" value="random2" id="radio_2">
									<span style="margin-left:15px">Insurance Renewal</span>
								</label>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="checkbox" style="">
								<label class="search-filter">
									<input type="radio" name="search-filter" class="odstatusfilter" value="random3" id="radio_3">
									<span style="margin-left:15px">Emission Test</span>
								</label>
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
								<th class="first"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Order ID</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Bike Model</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Order Date</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Reminder Date</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Order Type</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-user"></i> &nbsp;&nbsp;Customer Name</th>
								<th class="last"><i class="fa fa-calendar"></i> &nbsp;&nbsp;Last Followup</th>
							</tr>
						</thead>
						<tbody>
							<?php $search_flag = array("random1", "random2", "random3"); ?>
							<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
							<tr id="<?php echo $count; ?>">
								<td data-search="<?php if($row['reminder_type'] == 'service_reminder_date') { echo 'random1'; } elseif($row['reminder_type'] == 'insurance_renewal_date') { echo 'random2'; } elseif($row['reminder_type'] == 'puc_renewal_date') { echo 'random3'; } ?>"><a target="_blank" href="<?php echo site_url('admin/orders/odetail/' . $row['oid']); ?>?reminder_type=<?php echo $row['reminder_type']; ?>" class="order-id-link"><?php echo $row['oid']; ?></a></td>
								<td><?php echo $row['bmodel']; ?></td>
								<td><?php echo $row['odate']; ?></td>
								<td><?php echo $row['reminder_date']; ?></td>
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
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Reminder Date</th>
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
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.buttons.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jszip.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/buttons.html5.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script type="text/javascript">
	$(function() {
		$("input[type='checkbox'], input[type='radio']").icheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green'
		});
		$('.odstatusfilter').on("ifChecked", function() {
			var query = $(this).val();
			oTable.fnFilter(query, 0, true, false);
		});
		$('#radio_1').icheck('checked');
	});
</script>
</body>
</html>