<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Allotted Orders - Admin Panel</title>
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
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/materialize.admin.css'); ?>">
</head>
<body>
	<style>
		.checkb-label {
			font-weight:normal;
			margin-left: 15px;
		}
		h3.ui-state-active {
			background: #009688 !important;
			padding: 8px !important;
			margin: 5px 15px 5px 15px !important;
			color: #333333 !important;
			font-size: 15px !important;
		}
		h3.ui-state-default {
			background: #009688 !important;
			padding: 12px !important;
			padding-left: 31px !important;
			margin: 5px 15px 0px 15px !important;
			color: #fff !important;
			font-size: 15px !important;
		}
	</style>
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
			<?php if(!in_array('assignorders', $denied_secs)) { ?>
			<section class="section-center2" style="display:none;">
				<div class="section-header-1">
					<span class="confirm-title">Assign Executives</span>
				</div>
				<div class="section-content-action-update">
					<div class="col-xs-12">
						<ul class="collapsible" data-collapsible="accordion">
							<li>
								<div class="collapsible-header"><i class="material-icons">track_changes</i><b>Assign Orders to Executives</b></div>
								<div class="collapsible-body">
									<form method="POST" action="/admin/orders/assign_execs/">
										<div class="col-xs-12">
											<div class="col-xs-12 fields-container">
												<div class="col-xs-12" style="margin-top:10px;margin-bottom:-15px;"><label class="selected-options">&nbsp;&nbsp;&nbsp;&nbsp;Choose Order Ids</label></div>
												<?php if(isset($trows) && count($trows) > 0) { foreach($trows as $row) { ?>
												<div class="col-xs-4 field-box">
													<label>
														<input type="checkbox" class="form-control" name="ea_oids[]" value="<?php echo $row['oid']; ?>">
														<span class="checkb-label cursor-pointer"><?php echo $row['username'] . ' - ' . $row['bmodel'] . ' (' . $row['oid'] . ')'; ?></span>
													</label>
												</div>
												<?php } } ?>
											</div>
											<div class="col-xs-12 fields-container">
												<div class="col-xs-12" style="margin-top:10px;margin-bottom:-15px;"><label class="selected-options">&nbsp;&nbsp;&nbsp;&nbsp;Choose Service Executives</label></div>
												<?php if(isset($serexs) && count($serexs) > 0) { foreach($serexs as $serex) { ?>
												<div class="col-xs-4 field-box">
													<label>
														<input type="checkbox" class="form-control" name="ea_serexs[]" value="<?php echo $serex->ExecId; ?>">
														<span class="checkb-label cursor-pointer"><?php echo convert_to_camel_case($serex->ExecName); ?></span>
													</label>
												</div>
												<?php } } ?>
											</div>
											<div class="col-xs-12 text-center">
												<br><button type="submit" class="btn btn-primary action-btn">Assign Orders</button><br><br>
											</div>
										</div>
									</form>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</section>
			<?php } ?>
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-solid">
							<div class="box-header">
								<i class="fa fa-bar-chart-o"></i>
								<h3 class="box-title">Order Split Details - Allotted</h3>
								<div class="box-tools pull-right">
									<button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
									<button class="btn btn-default btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
							</div><!-- /.box-header -->
							<div class="box-body">
								<div class="row">
									<div class="col-md-4 text-center">
										<input type="text" class="knob" readonly value="<?php if (isset($ps_count)) { echo $ps_count; } ?>" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#00a65a"/>
										<div class="knob-label">Periodic Servicing</div>
									</div><!-- ./col -->
									<div class="col-md-4 text-center">
										<input type="text" class="knob" readonly value="<?php if (isset($r_count)) { echo $r_count; } ?>" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#f56954"/>
										<div class="knob-label">Repair/Accidental</div>
									</div><!-- ./col -->
									<div class="col-md-4 text-center">
										<input type="text" class="knob" readonly value="<?php if (isset($ir_count)) { echo $ir_count; } ?>" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#932ab6"/>
										<div class="knob-label">Insurance Renewal</div>
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
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Last Followup</th>
								<th class="last"><i class="fa fa-user"></i> &nbsp;&nbsp;Assigned Executives</th>
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
								<td><?php echo $row['fupsname']; ?></td>
								<td><?php echo $row['execnames']; ?></td>
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
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Last Followup</th>
								<th class="last"><i class="fa fa-user"></i> &nbsp;&nbsp;Assigned Executives</th>
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
<script type="text/javascript" src="<?php echo site_url('js/exporting.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.knob.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.sparkline.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/materialize.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script>
$(function() {	
	$("input[type='checkbox'], input[type='radio']").icheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
});
</script>
</body>
</html>