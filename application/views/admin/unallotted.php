<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Un-Allotted Orders - Admin Panel</title>
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
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-solid">
							<div class="box-header">
								<i class="fa fa-bar-chart-o"></i>
								<h3 class="box-title">Order Split Details - Un-Allotted</h3>
								<div class="box-tools pull-right">
									<button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
									<button class="btn btn-default btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
							</div><!-- /.box-header -->
							<div class="box-body">
								<div class="row">
									<div class="col-md-3 text-center">
										<input type="text" class="knob" readonly value="<?php if (isset($ps_count)) { echo $ps_count; } ?>" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#00a65a"/>
										<div class="knob-label">Periodic Servicing</div>
									</div><!-- ./col -->
									<div class="col-md-3 text-center">
										<input type="text" class="knob" readonly value="<?php if (isset($r_count)) { echo $r_count; } ?>" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#3caeb9"/>
										<div class="knob-label">Repair/Accidental</div>
									</div><!-- ./col -->
									<div class="col-md-3 text-center">
										<input type="text" class="knob" readonly value="<?php if (isset($ir_count)) { echo $ir_count; } ?>" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#932ab6"/>
										<div class="knob-label">Insurance Renewal</div>
									</div><!-- ./col -->
									<div class="col-md-3 text-center">
										<input type="text" class="knob" readonly value="<?php if (isset($de_count)) { echo $de_count; } ?>" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="red"/>
										<div class="knob-label">Delayed Orders</div>
									</div><!-- ./col -->
								</div><!-- /.row -->
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div><!-- /.col -->
				</div>
			</section><!-- /.content -->
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
								<th class="last">Cancel Order</th>
							</tr>
						</thead>
						<tbody> 
							<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
							<tr id="<?php echo $count; ?>">
								<td><a href="<?php echo site_url('admin/orders/odetail/' . $row['oid']); ?>" class="order-id-link"><?php echo $row['oid']; ?></a></td>
								<td><?php echo $row['bmodel']; ?></td>
								<td><?php echo $row['odate']; ?></td>
								<td><?php echo $row['otype']; ?></td>
								<td><?php echo $row['phone']; ?></td>
								<td><?php echo $row['username']; ?></td>
								<td><a data-cancel="<?php echo site_url('admin/orders/cancel_order/' . $row['oid']); ?>" id="cancel_order_href" style="cursor:pointer;" class="order-id-link" onclick="deleteOrder();">Cancel Order</a></td>
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
								<th class="last">Cancel Order</th>
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
<script type="text/javascript" src="<?php echo site_url('js/app.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/exporting.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.knob.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.sparkline.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script type="text/javascript">	
	var send_sms_flag = 0;
	function deleteOrder() {
		swal({
			title: "Are you sure?",
			text: "You are about to delete this order.",
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
			    var cancel_url = $('#cancel_order_href').attr("data-cancel");
			    window.location.assign(cancel_url + '/' + send_sms_flag);
			} else {
				//Do Nothing
			}
		});
	}
</script>
</body>
</html>