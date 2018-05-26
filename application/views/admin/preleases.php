<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - UnReleased Vendor Payments - Admin Panel</title>
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
					UnReleased Vendor Payments
					<small>Update Payment Release Status to Service Centers</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">UnReleased Vendor Payments</li>
				</ol>
			</section>
			<section>
				<div id="detail_tab" class="table-margin-top">
					<form method="POST" action="/admin/orders/update_preleases">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-institution"></i> &nbsp;&nbsp;Order Id</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Transaction Id</th>
								<th><i class="fa fa-map-marker"></i> &nbsp;&nbsp;Payment From</th>
								<th><i class="fa fa-map-marker"></i> &nbsp;&nbsp;Payment To</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Timestamp</th>
								<th><i class="fa fa-sitemap"></i> &nbsp;&nbsp;Amount Paid</th>
								<th class="last"><i class="fa fa-star"></i>
									<div class="checkbox" style="">
										<label class="price">
											<input type="checkbox" id="select_all_scs">
											<span style="margin-left:5px">Update Status</span>
										</label>
									</div>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($rows) && count($rows) > 0) { foreach($rows as $row) { ?>
							<tr id="sc_<?php echo $row['TId']; ?>">
								<td><a href="<?php echo site_url('admin/orders/odetail/' . $row['OId']); ?>" class="order-id-link"><?php echo $row['OId']; ?></a></td>
								<td><?php echo $row['TId']; ?></td>
								<td><?php echo $row['UserName']; ?></td>
								<td><?php echo $row['ScName']; ?></td>
								<td><?php echo $row['TimeStamp']; ?></td>
								<td><?php echo $row['PaymtAmt']; ?></td>
								<td>
									<div class="checkbox" style="">
										<label class="price">
											<input type="checkbox" class="app_vendors" name="tids[]" value="<?php echo $row['TId']; ?>">
										</label>
									</div>
								</td>
							</tr>
							<?php } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th class="first"><i class="fa fa-institution"></i> &nbsp;&nbsp;Order Id</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Transaction Id</th>
								<th><i class="fa fa-map-marker"></i> &nbsp;&nbsp;Payment From</th>
								<th><i class="fa fa-map-marker"></i> &nbsp;&nbsp;Payment To</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Timestamp</th>
								<th><i class="fa fa-sitemap"></i> &nbsp;&nbsp;Amount Paid</th>
								<th class="last">Update Status</th>
							</tr>
						</tfoot>
					</table>
					<div class="col-xs-12 text-center">
						<button type="submit" id="submit" name="verify" class="btn btn-primary" >
							Confirm Payment Release
						</button>
					</div>
					</form>
				</div><!-- Detail Tab -- >
			</section>
		</aside><!-- /.right-side -->
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
<script type="text/javascript" src="<?php echo site_url('js/raty/jquery.raty.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/avlist.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script>
$(function() {
	$('#select_all_scs').on('ifChecked', function() {
		$('.app_vendors').icheck('checked');
	});
	$('#select_all_scs').on('ifUnchecked', function() {
		$('.app_vendors').icheck('unchecked');
	});
});
</script>
</body>
</html>