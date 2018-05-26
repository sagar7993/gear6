<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Archived Orders - Admin Panel</title>
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
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.partial.css'); ?>">
	<style type="text/css">
		.select2-container{
			height: 3.9rem;
		}
		.select2-container--default .select2-selection--single .select2-selection__rendered {
			margin-top: 5px;
		}
		.dt-button {
			margin-left: 7%;
			margin-bottom: 2%;
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
					Dashboard
					<small>Overview &amp; Analysis</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Dashboard</li>
				</ol>
			</section>
			<section class="content">
				<form method="POST" action="/admin/orders/getOrderHistoryCSV" target="_BLANK">
					<div class="col-xs-12">
						<h4 align="center"><b>Choose parameters to export</b></h4>
						<div class="col-xs-12 fields-container">
							<div class="col-xs-3 field-box">
								<label>
									<input type="checkbox" class="form-control" id="select_all" name="select_all"/>
									<span class="checkb-label cursor-pointer">Select All</span>
								</label>
							</div>
							<?php if(isset($parameters) && count($parameters['checkbox']) > 0) { $count = 0; foreach($parameters['checkbox'] as $key => $parameter) { ?>
								<div class="col-xs-3 field-box">
									<label>
										<input type="checkbox" class="form-control parameters" name="parameters[]" value="<?php echo $parameter; ?>"/>
										<span class="checkb-label cursor-pointer"><?php echo $key; ?></span>
									</label>
								</div>
							<?php } } ?>
						</div>
						<div class="col-xs-12 fields-container" id="oType_1">
							<div class="col-xs-3 field-box">
								<select class="form-control styled-select" onchange="validation();" name="admin_followup" id="admin_followup" data-type="select">
									<option disabled selected style='display:none;' value=''>Admin Followup</option>
									<?php if(isset($admin_followup) && count($admin_followup) > 0) { foreach($admin_followup as $admin) { ?>
										<option value="<?php echo $admin['FupStatusId']; ?>"><?php echo $admin['FupStatusName']; ?></option>
									<?php } } ?>
								</select>
							</div>
							<div class="col-xs-3 field-box">
								<select class="form-control styled-select" onchange="validation();" name="executive_followup" id="executive_followup" data-type="select">
									<option disabled selected style='display:none;' value=''>Executive Followup</option>
									<?php if(isset($executive_followup) && count($executive_followup) > 0) { foreach($executive_followup as $executive) { ?>
										<option value="<?php echo $executive['EFupStatusId']; ?>"><?php echo $executive['EFupStatusName']; ?></option>
									<?php } } ?>
								</select>
							</div>
							<div class="col-xs-3 field-box">
								<select class="form-control styled-select" onchange="validation();" name="service_center" id="service_center" data-type="select">
									<option disabled selected style='display:none;' value=''>Service Center</option>
									<?php if(isset($service_center) && count($service_center) > 0) { foreach($service_center as $center) { ?>
										<option value="<?php echo $center['ScId']; ?>"><?php echo $center['ScName']; ?></option>
									<?php } } ?>
								</select>
							</div>
							<div class="col-xs-3 field-box">
								<select class="form-control styled-select" onchange="validation();" name="tie_up" id="tie_up" data-type="select">
									<option disabled selected style='display:none;' value=''>Tie Up</option>
									<?php if(isset($tie_up) && count($tie_up) > 0) { foreach($tie_up as $tie) { ?>
										<option value="<?php echo $tie['TieupId']; ?>"><?php echo $tie['TieupName']; ?></option>
									<?php } } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="button-box-contact col-xs-12" style="margin-bottom:40px;">
						<div class="button-container col-xs-6 col-xs-offset-5">
							<button type="submit" class='next btn btn-primary btnUpdate-pu' id="getOrderHistoryCSV" disabled>
								Get CSV File
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
								<th class="first"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Order ID</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Bike Model</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Order Date</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Order Type</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th class="last"><i class="fa fa-user"></i> &nbsp;&nbsp;Customer Name</th>
								<th class="last"><i class="fa fa-male"></i> &nbsp;&nbsp;Executive Name</th>
							</tr>
						</thead>
						<tbody> 
							<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
							<tr id="<?php echo $count; ?>">
								<td ><a href="<?php echo site_url('admin/orders/odetail/' . $row['oid']); ?>" class="order-id-link"><?php echo $row['oid']; ?></a></td>
								<td><?php echo $row['bmodel']; ?></td>
								<td><?php echo $row['odate']; ?></td>
								<td><?php echo $row['otype']; ?></td>
								<td><?php echo $row['phone']; ?></td>
								<td><?php echo $row['username']; ?></td>
								<td><?php echo $row['execname']; ?></td>
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
								<th class="last"><i class="fa fa-user"></i> &nbsp;&nbsp;Customer Name</th>
								<th class="last"><i class="fa fa-male"></i> &nbsp;&nbsp;Executive Name</th>
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
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/select2.full.min.js'); ?>"></script>
<?php if(!in_array('exportorderdata', $denied_secs)) { ?>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.buttons.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jszip.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/buttons.html5.min.js'); ?>"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/exporting.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/archived.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
</body>
</html>