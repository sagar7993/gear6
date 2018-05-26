<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Executive Leave Requests - Admin Panel</title>
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
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oxygen" type="text/css">
	<link rel="stylesheet" type="text/css" href="/nhome/js/lib/swal/sweetalert.css">
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
				<label class="selected-options">&nbsp;&nbsp;&nbsp;&nbsp;Filter Results</label>
				<div class="row">
					<div class="col-xs-12">
						<div class="col-xs-4">
							<div class="checkbox" style="">
								<label class="search-filter">
									<input type="radio" name="search-filter" class="odstatusfilter" value="asdfawe4gsbaerawtgaer" id="radio_1">
									<span style="margin-left:15px">Approved</span>
								</label>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="checkbox" style="">
								<label class="search-filter">
									<input type="radio" name="search-filter" class="odstatusfilter" value="asdre4hbsfefvzdfszdf" id="radio_2">
									<span style="margin-left:15px">Un-Approved</span>
								</label>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="checkbox" style="">
								<label class="search-filter">
									<a href="javascript:;" name="clear-search-filter" id="osclearfilter">Reset</a>
								</label>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section>
				<div align="center"><h3>Executive Leave Requests</h3></div>
				<div id="detail_tab" class="table-margin-top">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Executive</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;From</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;To</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Reason</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Updated On</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Updated By</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Leave Type</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
							<tr>
								<td><?php echo $row['ExecName']; ?></td>
								<td><?php echo $row['from_date']; ?></td>
								<td><?php echo $row['to_date']; ?></td>
								<td><?php echo $row['reason']; ?></td>
								<td><?php echo $row['updated_at']; ?></td>
								<td><?php echo $row['updatedBy']; ?></td>
								<?php if($row['status'] == 'Approved') { ?>
									<td data-search="asdfawe4gsbaerawtgaer"><?php echo $row['status']; ?></td>
								<?php } elseif($row['status'] == 'Pending' || $row['status'] == 'Rejected') { ?>
									<td data-search="asdre4hbsfefvzdfszdf"><?php echo $row['status']; ?></td>
								<?php } ?>
							</tr>
							<?php } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th class="first"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Executive</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;From</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;To</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Reason</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Updated On</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Updated By</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Leave Type</th>
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
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/execLeave.js'); ?>"></script>
</body>
</html>