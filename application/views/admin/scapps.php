<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - UnApproved Vendors List - Admin Panel</title>
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
					Service Centers
					<small>UnApproved Vendors</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">UnApproved Vendors</li>
				</ol>
			</section>
			<section>
				<div id="detail_tab" class="table-margin-top">
					<form method="POST" action="/admin/approvals/scapprove">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-institution"></i> &nbsp;&nbsp;Service Center</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Company</th>
								<th><i class="fa fa-map-marker"></i> &nbsp;&nbsp;Location</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-sitemap"></i> &nbsp;&nbsp;Email</th>
								<th class="last"><i class="fa fa-star"></i>
									<div class="checkbox" style="">
										<label class="price">
											<input type="checkbox" id="select_all_scs">
											<span style="margin-left:5px">Approve</span>
										</label>
									</div>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($rows) && count($rows) > 0) { foreach($rows as $row) { ?>
							<tr id="sc_<?php echo $row['ScId']; ?>">
								<td><a href="<?php echo site_url('admin/vendors/scdetails/' . $row['ScId']); ?>" class="order-id-link"><?php echo convert_to_camel_case($row['ScName']); ?></a></td>
								<td><?php echo $row['BikeCompany']; ?></td>
								<td><?php echo convert_to_camel_case($row['LocationName']); ?></td>
								<td><?php echo $row['Phone']; ?></td>
								<td><?php echo $row['Email']; ?></td>
								<td>
									<div class="checkbox" style="">
										<label class="price">
											<input type="checkbox" class="app_vendors" name="sc_ids[]" value="<?php echo $row['ScId']; ?>">
										</label>
									</div>
								</td>
							</tr>
							<?php } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th class="first"><i class="fa fa-institution"></i> &nbsp;&nbsp;Service Center</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Company</th>
								<th><i class="fa fa-map-marker"></i> &nbsp;&nbsp;Location</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-sitemap"></i> &nbsp;&nbsp;Email</th>
								<th class="last"><i class="fa fa-star"></i>Approve</th>
							</tr>
						</tfoot>
					</table>
					<div class="col-xs-12 text-center">
						<button type="submit" id="submit" name="verify" class="btn btn-primary" >
							Verify
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
	$(document).on('ifChecked', '#select_all_scs', function() {
		$('.app_vendors').icheck('checked');
	});
	$(document).on('ifUnchecked', '#select_all_scs', function() {
		$('.app_vendors').icheck('unchecked');
	});
	$(document).on('ifUnchecked', '.app_vendors', function() {
		$('#select_all_scs').icheck('unchecked');
	});
});
</script>
</body>
</html>