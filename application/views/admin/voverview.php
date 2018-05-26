<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendors Dashboard - Admin Panel</title>
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
		<aside class="right-side auto-height">
			<section class="content-header">
				<h1>
					Vendors Dashboard
					<small>Overview &amp; Analysis</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Vendors Dashboard</li>
				</ol>
			</section>
			<section class="content">
				<div class='callout callout-info'>
					<h4>Today's Orders Summary</h4>
					<p>The following graphs and ring charts provide you complete details of today's orders.</p>
				</div>
				<div class="row">
					<div class="box box-solid analysis-block">
						<div class="box-body">
							<div class="row analysis-container">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="checkbox" id="toggle-grp">
										<label>
											<div class="btn-group btn-toggle form-group toggle-btn"> 
												<button class="btn btn-xs btn-primary active tog-btn">Weekly</button>
												<button class="btn btn-xs btn-default tog-btn">Monthly</button>
											</div>
										</label>
									</div>
									<div id="top_ten_vendors_analysis" style="height:300px"></div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="checkbox" id="toggle-grp">
										<label>
											<div class="btn-group btn-toggle form-group toggle-btn"> 
												<button class="btn btn-xs btn-primary active tog-btn">Weekly</button>
												<button class="btn btn-xs btn-default tog-btn">Monthly</button>
											</div>
										</label>
									</div>
									<div id="last_ten_vendors_analysis" style="height:300px"></div>
								</div>
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div>
					<div class="box box-solid analysis-block">
						<div class="box-body">
							<div class="row analysis-container">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
									<div class="checkbox" id="toggle-grp">
										<label>
											<div class="btn-group btn-toggle form-group toggle-btn"> 
												<button class="btn btn-xs btn-primary active tog-btn">Weekly</button>
												<button class="btn btn-xs btn-default tog-btn">Monthly</button>
											</div>
										</label>
									</div>
									<div id="delay_analysis" style="height:300px"></div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
									<div class="checkbox" id="toggle-grp">
										<label>
											<div class="btn-group btn-toggle form-group toggle-btn"> 
												<button class="btn btn-xs btn-primary active tog-btn">Weekly</button>
												<button class="btn btn-xs btn-default tog-btn">Monthly</button>
											</div>
										</label>
									</div>
									<div id="dropped_analysis" style="height:300px"></div>
								</div>
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div>
				</div><!-- /.row -->
			</section><!-- /.content -->
		</aside><!-- /.right-side -->
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/app.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/highcharts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/exporting.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.knob.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.sparkline.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/vgraphs.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
</body>
</html>