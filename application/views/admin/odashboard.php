<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Orders Home - Admin Panel</title>
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
					Orders Dashboard
					<small>Overview &amp; Analysis</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Orders Dashboard</li>
				</ol>
			</section>
			<section class="content">
				<div class='callout callout-info'>
					<h4>Orders Summary</h4>
					<p>The following graphs and ring charts provide you complete details of Total and today's orders.</p>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<div class="info-box bg-teal">
							<span class="info-box-icon"><i class="fa fa-sitemap"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Orders</span>
								<span class="info-box-number"><?php if(isset($tot_processed)) { echo $tot_processed; } ?></span>
								<div class="progress">
									<div class="progress-bar" style="width: 65%"></div>
								</div>
								<span class="progress-description">
									Processed of <?php if(isset($tot_orders)) { echo $tot_orders; } ?>
								</span>
							</div><!-- /.info-box-content -->
						</div><!-- /.info-box -->
					</div>
					<div class="col-xs-4">
						<div class="info-box bg-green">
							<span class="info-box-icon"><i class="fa fa-question-circle"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Queried</span>
								<span class="info-box-number"><?php if(isset($tot_queried)) { echo $tot_queried; } ?></span>
								<div class="progress">
									<div class="progress-bar" style="width: 20%"></div>
								</div>
								<span class="progress-description">
									of <?php if(isset($tot_orders)) { echo $tot_orders; } ?> Total Orders
								</span>
							</div><!-- /.info-box-content -->
						</div><!-- /.info-box -->
					</div>
					<div class="col-xs-4">
						<div class="info-box bg-yellow">
							<span class="info-box-icon"><i class="fa fa-warning"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Delayed</span>
								<span class="info-box-number"><?php if(isset($tot_delayed)) { echo $tot_delayed; } ?></span>
								<div class="progress">
									<div class="progress-bar" style="width: 10%"></div>
								</div>
								<span class="progress-description">
									of <?php if(isset($nav_allotted_count)) { echo $nav_allotted_count; } ?> Allotted orders
								</span>
							</div><!-- /.info-box-content -->
						</div><!-- /.info-box -->
					</div>
				</div>
				<br>
				<div class="row">
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
									<div id="order_type_analysis" style="height:300px"></div>
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
									<div id="pick_up_analysis" style="height:300px"></div>
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
									<div id="dropped_analysis" style="height:300px"></div>  
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
									<div id="delay_analysis" style="height:300px"></div> 
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
									<div id="order_summary_analysis" style="height:300px"></div> 
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
									<div id="status_type_analysis" class="col-md-12" style="height:150px;"></div>
									<div id="query_type_analysis" class="col-md-12" style="height:150px;"></div>
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
									<div id="future_orders_analysis" style="height:300px"></div>
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
									<div id="emg_pt_order_analysis" style="height:300px"></div>
								</div>
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div>
				</div><!-- /.row -->
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="box box-solid">
							<div class="box-header dash-box-header">
								<i class="fa fa-bar-chart-o"></i>
								<h5 class="box-title font16">Total Orders - Next 7 Days</h5>
							</div><!-- /.box-header -->
							<div class="box-body">
								<div class="row">
									<div id="next_week_analysis" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
								</div><!-- /.row -->
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div>
				</div>
			</section><!-- /.content -->
		</aside><!-- /.right-side -->
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/app.js'); ?>"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo site_url('js/highcharts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/exporting.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.knob.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.sparkline.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/graphs.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
</body>
</html>