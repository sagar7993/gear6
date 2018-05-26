<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Home - Dashboard</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('vendor/components/_vcss'); ?>
</head>
<body>
	<?php $this->load->view('vendor/components/_head'); ?>
	<?php if(isset($v_is_logged_in) && $v_is_logged_in == 1) { ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<?php $this->load->view('vendor/components/_sidebar'); ?>
		<aside class="right-side auto-height">
			<section class="dash-content">
				<div class="row margin-bottom-20px margin-top-10px">
					<div class="col-md-4">
						<div class="info-box z-depth-2 bg-teal">
							<span class="info-box-icon"><i class="fa fa-sitemap"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Orders Processed</span>
								<span class="info-box-number"><?php if(isset($tot_processed)) { echo $tot_processed; } ?></span>
								<div class="progress">
									<div class="progress-bar" style="width: <?php if(isset($tot_processed) && isset($tot_orders) && $tot_orders != 0) { echo round(($tot_processed * 100) / $tot_orders); } ?>%"></div>
								</div>
								<span class="progress-description">
									of <?php if(isset($tot_orders)) { echo $tot_orders; } ?> Total Orders
								</span>
							</div><!-- /.info-box-content -->
						</div><!-- /.info-box -->
					</div>
					<div class="col-md-4">
						<div class="info-box z-depth-2 bg-green">
							<span class="info-box-icon"><i class="fa fa-question-circle"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Queried</span>
								<span class="info-box-number"><?php if(isset($tot_queried)) { echo $tot_queried; } ?></span>
								<div class="progress">
									<div class="progress-bar" style="width: <?php if(isset($tot_queried) && isset($tot_orders) && $tot_orders != 0) { echo round(($tot_queried * 100) / $tot_orders); } ?>%"></div>
								</div>
								<span class="progress-description">
									of <?php if(isset($tot_orders)) { echo $tot_orders; } ?> Total Orders
								</span>
							</div><!-- /.info-box-content -->
						</div><!-- /.info-box -->
					</div>
					<div class="col-md-4">
						<div class="info-box z-depth-2 bg-yellow">
							<span class="info-box-icon"><i class="fa fa-warning"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Delayed</span>
								<span class="info-box-number"><?php if(isset($tot_delayed)) { echo $tot_delayed; } ?></span>
								<div class="progress">
									<div class="progress-bar" style="width: <?php if(isset($tot_delayed) && isset($tot_orders_without_query) && $tot_orders_without_query != 0) { echo round(($tot_delayed * 100) / $tot_orders_without_query); } ?>%"></div>
								</div>
								<span class="progress-description">
									of <?php if(isset($tot_orders_without_query)) { echo $tot_orders_without_query; } ?> Total Orders (Queries Exclusive)
								</span>
							</div><!-- /.info-box-content -->
						</div><!-- /.info-box -->
					</div>
				</div>
				<div class="row">
					<div class="box box-solid analysis-block">
						<div class="box-header dash-box-header">
							<i class="fa fa-bar-chart-o"></i>
							<h5 class="box-title font16">Orders Analysis - Weekly/Monthly</h5>
							
						</div><!-- /.box-header -->
						<div class="box-body">
							<div class="row analysis-container">
								<div class="col-md-12">
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
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div>
					<div class="box box-solid analysis-block">
						<div class="box-header dash-box-header">
							<i class="fa fa-bar-chart-o"></i>
							<h5 class="box-title font16">Pick Up Ratio Analysis - Weekly/Monthly</h5>
						</div><!-- /.box-header -->
						<div class="box-body">
							<div class="row analysis-container">
								<div class="col-md-12">
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
						<div class="box-header dash-box-header">
							<i class="fa fa-bar-chart-o"></i>
							<h5 class="box-title font16">Slot Analysis - Weekly/Monthly</h5>
						</div><!-- /.box-header -->
						<div class="box-body">
							<div class="row analysis-container">
								<div class="col-md-12">
									<div class="checkbox" id="toggle-grp">
										<label>
											<div class="btn-group btn-toggle form-group toggle-btn"> 
												<button class="btn btn-xs btn-primary active tog-btn">Weekly</button>
												<button class="btn btn-xs btn-default tog-btn">Monthly</button>
											</div>
										</label>
									</div>
									<div id="slot_analysis" style="height:300px"></div>  
								</div>
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div> 
					<div class="box box-solid analysis-block">
						<div class="box-header dash-box-header">
							<i class="fa fa-bar-chart-o"></i>
							<h5 class="box-title font16">Order Delay Analysis - Weekly/Monthly</h5>
						</div><!-- /.box-header -->
						<div class="box-body">
							<div class="row analysis-container">
								<div class="col-md-12">
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
						<div class="box-header dash-box-header">
							<i class="fa fa-bar-chart-o"></i>
							<h5 class="box-title font16">Order Summary Analysis - Weekly/Monthly</h5>
						</div><!-- /.box-header -->
						<div class="box-body">
							<div class="row analysis-container">
								<div class="col-md-12">
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
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div> 
					<div class="box box-solid analysis-block">
						<div class="box-header dash-box-header">
							<i class="fa fa-bar-chart-o"></i>
							<h5 class="box-title font16">Status and Query Analysis</h5>
						</div><!-- /.box-header -->
						<div class="box-body">
							<div class="row analysis-container">
								<div class="col-md-12">
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
							</div> 
						</div><!-- /.box-body -->
					</div><!-- /.box -->
				</div><!-- /.row -->
				<div class="row">
					<div class="col-md-12">
						<div class="box box-solid big-box-analysis">
							<div class="box-header dash-box-header">
								<i class="fa fa-bar-chart-o"></i>
								<h5 class="box-title font16">Future Order Analysis</h5>
							</div><!-- /.box-header -->
							<div class="box-body">
								<div class="row analysis-container">
									<div class="col-md-12">
										<div class="checkbox" id="toggle-grp">
											<label>
												<div class="btn-group btn-toggle form-group toggle-btn"> 
													<button class="btn btn-xs btn-primary active tog-btn">Weekly</button>
													<button class="btn btn-xs btn-default tog-btn">Monthly</button>
												</div>
											</label>
										</div>
										<div id="future_orders_analysis"></div> 
									</div>
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
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
					</div><!-- /.col -->
				</div><!-- /.row -->
			</section><!-- /.content -->
		</aside><!-- /.right-side -->
	</div><!-- ./wrapper -->
	<?php $this->load->view('vendor/components/_foot'); ?>
	<?php } ?>
	<?php $this->load->view('vendor/components/_vjs'); ?>
<script type="text/javascript" src="<?php echo site_url('js/highcharts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/exporting.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.knob.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.sparkline.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/vdash.js'); ?>"></script>
<script>
</script>
</body>
</html>