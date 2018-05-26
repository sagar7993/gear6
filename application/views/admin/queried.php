<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Queried Orders - Admin Panel</title>
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
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-solid">
							<div class="box-header">
								<i class="fa fa-bar-chart-o"></i>
								<h3 class="box-title">Order Split Details - Queried</h3>
								<div class="box-tools pull-right">
									<button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
									<button class="btn btn-default btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
							</div><!-- /.box-header -->
							<div class="box-body">
								<div class="row">
									<div class="col-md-4 text-center">
										<input type="text" class="knob" readonly value="<?php if (isset($n_query)) { echo $n_query; } ?>" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#00a65a"/>
										<div class="knob-label">New Queries</div>
									</div><!-- ./col -->
									<div class="col-md-4 text-center">
										<input type="text" class="knob" readonly value="<?php if (isset($d_query)) { echo $d_query; } ?>" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#f56954"/>
										<div class="knob-label">Delayed Queries</div>
									</div><!-- ./col -->
									<div class="col-md-4 text-center">
										<input type="text" class="knob" readonly value="<?php if (isset($a_query)) { echo $a_query; } ?>" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#932ab6"/>
										<div class="knob-label">Answered Queries</div>
									</div><!-- ./col -->
								</div><!-- /.row -->
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div><!-- /.col -->
				</div>
				<label class="selected-options">&nbsp;&nbsp;&nbsp;&nbsp;Filter Queries</label>
				<div class="row">
					<div class="col-xs-12">
						<div class="col-xs-4">
							<div class="checkbox">
								<label class="search-filter">
									<input type="radio" name="search-filter" class="odstatusfilter" value="0" id="radio_1">
									<span style="margin-left:15px">Pending</span>
								</label>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="checkbox">
								<label class="search-filter">
									<input type="radio" name="search-filter" class="odstatusfilter" value="1" id="radio_2">
									<span style="margin-left:15px">Answered</span>
								</label>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="checkbox">
								<label class="search-filter">
									<a href="javascript:;" name="clear-search-filter" id="osclearfilter">Reset</a>
								</label>
							</div>
						</div>
					</div>
				</div>
			</section><!-- /.content -->
			<section>
				<div id="detail_tab" class="table-margin-top">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th style="display:none;" class="first"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Order ID</th>
								<th><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Order ID</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Bike Model</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-user"></i> &nbsp;&nbsp;Customer Name</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Timestamp</th>
								<th class="last"><i class="fa fa-keyboard-o"></i> &nbsp;&nbsp;Query In-Brief</th>
							</tr>
						</thead>
						<tbody> 
							<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
							<tr id="<?php echo $count; ?>">
								<td id="search_<?php echo $row['oid']; ?>" data-search="<?php echo $row['status']; ?>" style="display:none;"><?php echo $row['oid']; ?></td>
								<td><a href="<?php echo site_url('admin/orders/odetail/' . $row['oid']); ?>" class="order-id-link"><?php echo $row['oid']; ?></a></td>
								<td><?php echo $row['bmodel']; ?></td>
								<td><?php echo $row['phone']; ?></td>
								<td><?php echo $row['username']; ?></td>
								<td><?php echo $row['TimeStamp']; ?></td>
								<td><?php echo $row['query_desc']; ?></td>
							</tr>
							<?php $count += 1; } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th style="display:none;" class="first"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Order ID</th>
								<th><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Order ID</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Bike Model</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-user"></i> &nbsp;&nbsp;Customer Name</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Timestamp</th>
								<th class="last"><i class="fa fa-keyboard-o"></i> &nbsp;&nbsp;Query In-Brief</th>
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
	$("input[type='checkbox'], input[type='radio']").icheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
	$('.odstatusfilter').on("ifChecked", function() {
		var query = $(this).val();
		oTable.fnFilter(query, 0, true, false);
	});
	$('#osclearfilter').on('click', function() {
		for (var i = 1; i <= 2; i++) {
			$('#radio_' + i).icheck('unchecked');
		}
		oTable.fnFilter('', 0, true, false);
	});
	$(document).ready(function() {
		$('#radio_1').icheck('checked');
	});
</script>
</body>
</html>