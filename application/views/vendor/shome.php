<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Home - Dashboard</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('vendor/components/_vcss'); ?>
</head>
<body>
	<header class="header">
		<nav role="navigation">
			<!-- Sidebar toggle button-->
			<div class="nav-wrapper">
				<a href="" class="left hide-on-med-and-down padding-10px clear-hover-bg">
					<img src="<?php echo site_url('img/mr_logo.png'); ?>" class="left header-logo" />
				</a>
				<a id="nav-desktop" class="right hide-on-large-only padding-10px clear-hover-bg">
					<img src="<?php echo site_url('img/mr_logo.png'); ?>" class="right header-logo-mob" />
				</a>
				<?php if(isset($v_is_logged_in) && $v_is_logged_in == 1) { ?>
				<ul class="right hide-on-med-and-down">
					<li class="pointer">
						<a class='dropdown-button' data-activates='noti_ffeed'>
							<i class="nav-icon material-icons left">mail</i>
							<span class="label label-success">4</span>
						</a>
						<ul class="dropdown-content custom-dd custom-vdd notify-scroll">
						</ul>
					</li>
					<li class="pointer">
						<a class='dropdown-button' data-activates='notif_warning'>
							<i class="nav-icon material-icons left">alarm</i>
							<span class="label label-warning">7</span>
						</a>
						<ul class="dropdown-content custom-dd custom-vdd notify-scroll" id="notif_warning">
							<li class="header">You have 7 notifications</li>
							<li>
								<ul class="menu">
									<li>
										<a href="#">
											<i class="nav-icon1 material-icons left">local_activity</i> You have a pickUp in 1hr @Marathahalli
										</a>
									</li>
									
								</ul>
							</li>
							<li class="footer vnav-footer"><a href="#">View all</a></li>
						</ul>
					</li>
					<li class="pointer">
						<a class='dropdown-button' data-activates='noti_ofeed'>
							<i class="nav-icon material-icons left">dvr</i>
							<span class="label label-danger" id="noti_ocount"></span>
						</a>
						<ul class="dropdown-content custom-dd custom-vdd notify-scroll" id="noti_ofeed">								
						</ul>
					</li>
					<li class="pointer">
						<a class='dropdown-button' data-activates='user_data'>
							<i class="nav-icon material-icons left">account_circle</i>
							<span><?php echo convert_to_camel_case($this->session->userdata('v_name')); ?></span>
						</a>
						<ul class="dropdown-content custom-dd custom-vdd" id="user_data">
							<li class="user-header bg-light-blue">
								<div class="vProfile-img-section center-align">
									<i class="nav-icon material-icons left nav-user-img">account_circle</i>
								</div>
								<p class="center-align">
									<?php echo convert_to_camel_case($this->session->userdata('v_role')); ?> - <?php echo convert_to_camel_case($this->session->userdata('v_sc_name')); ?>
									<br />
									<small>Thubarahalli</small>
								</p>
							</li>
							<div class="pull-left">
								<?php if(isset($page) && $page == 'profile') { ?>
									<a href="<?php echo base_url('vendor/vendorhome'); ?>" class="btn waves-effect waves-light btn-flat">Dashboard</a>
								<?php } else { ?>
									<a href="<?php echo base_url('vendor/profile'); ?>" class="btn waves-effect waves-light btn-flat">Profile</a>
								<?php } ?>
							</div>
							<div class="pull-right">
								<a href="/home/vendor_logout/<?php echo base64_encode(current_url()); ?>" class="btn waves-effect waves-light btn-flat">Sign out</a>
							</div>
						</ul>
					</li>
				</ul>
				<?php } else { ?>
				<ul id="nav-mobile" class="right hide-on-med-and-down">
					<li>
						<a id="nav-desktop" class="left hide-on-med-and-down">
							<i class="nav-icon material-icons left">perm_phone_msg</i>
							<span class="">080- 42296199
								<span class="">[9am - 6pm]</span>
							</span>
						</a>
					</li>
				</ul>
				<?php } ?>
			</div>
		</nav>
	</header>
	<?php if(isset($v_is_logged_in) && $v_is_logged_in == 1) { ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<aside class="left-side sidebar-offcanvas">                
			<section class="sidebar">
				<section>
					<div class="sidebar-title"><i class="material-icons">account_circle</i><div>&nbsp;<?php echo convert_to_camel_case($this->session->userdata('v_role')); ?></div></div>
				</section>
				<?php if(isset($page) && $page == 'profile') { ?>
				<ul class="sidebar-menu">
					<li class="side-menu-list side-menu-inactive dashboard" id="contact">
						<a href="<?php echo base_url('vendor/profile'); ?>">
							<span class="big-icon"><i class="material-icons">contact_phone</i></span><br> <span >Contact Info</span>
						</a>
					</li>
					<li class="side-menu-list side-menu-inactive" id="services">
						<a href="<?php echo base_url('vendor/profile/services'); ?>">
							<span class="big-icon"><i class="material-icons">folder_special</i></span><br>
							<span>Services</span>
						</a>
					</li>
					<li class="side-menu-list side-menu-inactive" id="price-chart">
						<a href="<?php echo base_url('vendor/profile/pricechart'); ?>">
							<span class="big-icon"><i class="material-icons">local_atm</i></span><br>
							<span>Price Chart</span>
						</a>
					</li>
					<li class="side-menu-list side-menu-inactive" id="pickup">
						<a href="<?php echo base_url('vendor/profile/pickareas'); ?>">
							<span class="big-icon"><i class="material-icons">pin_drop</i></span><br>
							<span>Pick Up Areas</span>
						</a>
					</li>
					<li class="side-menu-list side-menu-inactive" id="slotm">
						<a href="<?php echo base_url('vendor/profile/slotmgmt'); ?>" target="_blank">
							<span class="big-icon"><i class="material-icons">developer_board</i></span><br>
							<span>Slot Management</span>
						</a>
					</li>
					<li class="side-menu-list side-menu-inactive" id="feedback">
						<a href="<?php echo base_url('vendor/profile/feedbacks'); ?>">
							<span class="big-icon"><i class="material-icons">border_color</i></span><br>
							<span>Feedback</span>
						</a>
					</li>
				</ul>
				<?php } else { ?>
				<ul class="sidebar-menu">
					<li class="side-menu-list side-menu-inactive dashboard" id="dashboard">
						<a href="<?php echo base_url('vendor/vendorhome'); ?>" >
							<span class="big-icon"><i class="material-icons">settings_input_svideo</i></span><br> <span >Dashboard</span>
						</a>
					</li>
					<li class="side-menu-list side-menu-inactive" id="upcoming">
						<a href="<?php echo base_url('vendor/upcoming'); ?>">
							<span class="big-icon"><i class="material-icons">perm_data_setting</i></span> <br>
							<span>Today's<small class="badge badge-float bg-blue"><?php if(isset($nav_upcoming_count)) { echo $nav_upcoming_count; } ?></small></span>
						</a>
					</li>
					<li class="side-menu-list side-menu-inactive" id="unallot">
						<a href="<?php echo base_url('vendor/unallotted'); ?>">
							<span class="big-icon"><i class="material-icons">new_releases</i></span><br><span>UnAllotted</span>
							<small class="badge badge-float bg-red"><?php if(isset($nav_unallotted_count)) { echo $nav_unallotted_count; } ?></small>
						</a>
					</li>
					<li class="side-menu-list side-menu-inactive" id="allot">
						<a href="<?php echo base_url('vendor/allotted'); ?>">
							<span class="big-icon"><i class="material-icons">event</i></span> <br><span>Allotted</span>
							<small class="badge badge-float bg-teal"><?php if(isset($nav_allotted_count)) { echo $nav_allotted_count; } ?></small>
						</a>
					</li>
					<li class="side-menu-list side-menu-inactive" id="queried">
						<a href="<?php echo base_url('vendor/queried'); ?>">
							<span class="big-icon"><i class="material-icons">trending_up</i></span> <br><span>Queried</span>
							<small class="badge badge-float bg-yellow"><?php if(isset($nav_queried_count)) { echo $nav_queried_count; } ?></small>
						</a>
					</li>
					<li class="side-menu-list side-menu-inactive" id="serviced">
						<a href="<?php echo base_url('vendor/serviced'); ?>">
							<span class="big-icon"><i class="material-icons">alarm_on</i></span> <br><span >Serviced</span>
							<small class="badge badge-float bg-grey">10</small>
						</a>
					</li>
					<li class="side-menu-list side-menu-inactive" id="history">
						<a href="<?php echo base_url('vendor/archived'); ?>">
							<span class="big-icon"><i class="material-icons">restore</i></span> <br><span>History</span>
						</a>
					</li>
				</ul>
				<?php } ?>
			</section>
			<!-- /.sidebar -->
			<input type="hidden" id="active" value="<?php if(isset($active)) { echo $active; } ?>">
		</aside>
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
<script type="text/javascript" src="<?php echo site_url('js/svdash.js'); ?>"></script>
<script>
</script>
</body>
</html>