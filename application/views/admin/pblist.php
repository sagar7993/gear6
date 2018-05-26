<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Petrol Bunks - Admin Panel</title>
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
					<small>PetrolBunks</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">PetrolBunks Dashboard</li>
				</ol>
			</section>
			<section>
				<div id="detail_tab" class="table-margin-top">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Bunk Name</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Company</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Email</th>
								<th class="last"><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Location</th>
							</tr>
						</thead>
						<tbody> 
							<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
							<tr id="<?php echo $count; ?>">
								<td><a href="" class="order-id-link"><?php echo convert_to_camel_case($row['PBName']); ?></a></td>
								<td><?php echo $row['ServiceProvider']; ?></td>
								<td><?php echo $row['Phone']; ?></td>
								<td><?php echo $row['Email']; ?></td>
								<td><?php echo $row['LocationName']; ?></td>
							</tr>
							<?php $count += 1; } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th class="first"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Bunk Name</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Company</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Email</th>
								<th class="last"><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Location</th>
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
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
</body>
</html>