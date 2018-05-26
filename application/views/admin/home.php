<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Admin Home - Dashboard</title>
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
	<style type="text/css">
		.z-depth-1, nav, .card-panel, .card, .toast, .btn, .btn-large, .btn-floating, .dropdown-content, .collapsible, .side-nav {
		  -webkit-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
		  -moz-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
		  box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
		}

		.dropdown-content {
		  background-color: #FFFFFF;
		  margin: 0;
		  display: none;
		  min-width: 100px;
		  max-height: 650px;
		  overflow-y: auto;
		  opacity: 0;
		  position: absolute;
		  white-space: nowrap;
		  z-index: 1;
		  will-change: width, height;
		}
		.dropdown-content li {
		  clear: both;
		  color: rgba(0, 0, 0, 0.87);
		  cursor: pointer;
		  line-height: 1.5rem;
		  /*width: 100%;*/
		  margin-left: -40px;
		  text-align: left;
		  text-transform: none;
		}
		.dropdown-content li:hover, .dropdown-content li.active {
		  background-color: #eee;
		}
		.dropdown-content li > a, .dropdown-content li > span {
		  font-size: 1.2rem;
		  color: #26a69a;
		  display: block;
		  padding: 1rem 1rem;
		}
	</style>
</head>
<body>
	<?php $this->load->view('admin/components/_head', $this->data['cities']); ?>
	<?php if(isset($a_is_logged_in) && $a_is_logged_in == 1) { ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<?php $this->load->view('admin/components/_sidebar'); ?>
		<aside class="right-side auto-height">
			<section class="content-header">
				<h1>
					Admin Dashboard
					<small>Overview &amp; Analysis</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Admin Dashboard</li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class='callout callout-info col-md-6'>
						<h4>Today's Orders Summary</h4>
						<p>The following graphs and ring charts provide you complete details of today's orders.</p>
					</div>
					<a target="_blank" href="<?php echo site_url("/admin/orders/rating"); ?>">
						<div class='col-md-6'>
							<div class="info-box bg-green">
								<span class="info-box-icon"><i class="fa fa-star"></i></span>
								<div class="info-box-content">
									<span class="info-box-text">Average NPS (out of 10)</span>
									<span class="info-box-number"><?php echo $rating; ?></span>
									<div class="progress">
										<div class="progress-bar" style="width: <?php echo floatval($rating) * 10; ?>%"></div>
									</div>
									<span class="progress-description">
										of <?php echo $total_rating; ?> Total Ratings
									</span>
								</div><!-- /.info-box-content -->
							</div><!-- /.info-box -->
						</div>
					</a>
				</div>
				<div class="row">
					<div class="col-md-4" style="">
						<div class="box box-solid">
							<div class="box-header">
								<h3 class="box-title text-warning">Today's Orders</h3>
								<div class="box-tools pull-right">
									<button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
								</div>
							</div><!-- /.box-header -->
							<div class="box-body text-center">
								<div class="sparkline" data-type="bar" data-width="100%" data-height="100px" data-bar-Width="44" data-bar-Spacing="10" data-bar-Color="#f39c12">
									40,38,38,44,39
								</div>
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div><!-- /.col -->
					<div class="col-md-4" style="">
						<div class="box box-solid">
							<div class="box-header">
								<h3 class="box-title text-warning">Day&nbsp; v/s Overnight</h3>
								<div class="box-tools pull-right">
									<button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
								</div>
							</div><!-- /.box-header -->
							<div class="box-body text-center">
								<div class="sparkline" data-type="bar" data-width="97%" data-height="100px" data-bar-Width="44" data-bar-Spacing="7" data-bar-Color="#00a65a">
									10,6
								</div>
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div><!-- /.col -->
					<div class="col-md-4"  style="">
						<div class="box box-solid">
							<div class="box-header">
								<h3 class="box-title text-danger">PickUp Ratio</h3>
								<div class="box-tools pull-right">
									<button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
								</div>
							</div><!-- /.box-header -->
							<div class="box-body text-center">
								<div class="sparkline" data-type="pie" data-offset="90" data-width="100px" data-height="100px">
									6,4
								</div>
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div><!-- /.col -->
					<div class="col-xs-12">
						<!-- jQuery Knob -->
						<div class="box box-solid">
							<div class="box-header">
								<i class="fa fa-bar-chart-o"></i>
								<h3 class="box-title">Order Split Details - Today</h3>
								<div class="box-tools pull-right">
									<button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
									<button class="btn btn-default btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
							</div><!-- /.box-header -->
							<div class="box-body">
								<div class="row">
									<div class="col-md-3 col-sm-6 col-xs-6 text-center">
										<input type="text" class="knob" value="43" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#00a65a"/>
										<div class="knob-label">Periodic Servicing</div>
									</div><!-- ./col -->
									<div class="col-md-3 col-sm-6 col-xs-6 text-center">
										<input type="text" class="knob" value="32" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#00c0ef"/>
										<div class="knob-label">Water Wash</div>
									</div><!-- ./col -->
									<div class="col-md-3 col-sm-6 col-xs-6 text-center">
										<input type="text" class="knob" value="54" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#f56954"/>
										<div class="knob-label">Repair/Accidental</div>
									</div><!-- ./col -->
									<div class="col-md-3 col-sm-6 col-xs-6 text-center">
										<input type="text" class="knob" value="14" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#932ab6"/>
										<div class="knob-label">Insurance Renewal</div>
									</div><!-- ./col -->
								</div><!-- /.row -->
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div><!-- /.col -->
				</div><!-- /.row -->
				<div class='callout callout-info'>
					<h4>Comparison of Orders in the coming week</h4>
					<p>The following bar graph helps you to compare order load in the next 6 days.</p>
				</div>
				<div class="row">
					<div class="col-xs-12" style="">
						<div class="box box-solid">
							<div id="container11" style="min-width: 310px; max-width: 800px; height: 400px; margin: 0 auto"></div>
						</div>
					</div>
				</div>
				<div class='callout callout-info'>
					<h4>Overview of Orders in the coming week</h4>
					<p>The following six ring charts helps you to interpret order load in the next 6 days.</p>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-solid">
							<div class="box-header">
								<i class="fa fa-bar-chart-o"></i>
								<h3 class="box-title">Total Orders - Next 6 Days</h3>
								<div class="box-tools pull-right">
									<button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
									<button class="btn btn-default btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
							</div><!-- /.box-header -->
							<div class="box-body">
								<div class="row">
									<div class="col-md-3 col-sm-6 col-xs-6 text-center">
										<input type="text" class="knob" value="30" data-width="90" data-height="90" data-fgColor="#3c8dbc"/>
										<div class="knob-label">21/07</div>
									</div><!-- ./col -->
									<div class="col-md-3 col-sm-6 col-xs-6 text-center">
										<input type="text" class="knob" value="70" data-width="90" data-height="90" data-fgColor="#f56954"/>
										<div class="knob-label">22/07</div>
									</div><!-- ./col -->
									<div class="col-md-3 col-sm-6 col-xs-6 text-center">
										<input type="text" class="knob" value="80"  data-width="90" data-height="90" data-fgColor="#00a65a"/>
										<div class="knob-label">23/07</div>
									</div><!-- ./col -->
									<div class="col-md-3 col-sm-6 col-xs-6 text-center">
										<input type="text" class="knob" value="40" data-width="90" data-height="90" data-fgColor="#00c0ef"/>
										<div class="knob-label">24/07</div>
									</div><!-- ./col -->
								</div><!-- /.row -->
								<div class="row">
									<div class="col-xs-6 text-center">
										<input type="text" class="knob" value="90" data-width="90" data-height="90" data-fgColor="#932ab6"/>
										<div class="knob-label">25/07</div>
									</div><!-- ./col -->
									<div class="col-xs-6 text-center">
										<input type="text" class="knob" value="50" data-width="90" data-height="90" data-fgColor="#39CCCC"/>
										<div class="knob-label">26/07</div>
									</div><!-- ./col -->
								</div><!-- /.row -->
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div><!-- /.col -->
				</div><!-- /.row -->
			</section><!-- /.content -->
		</aside><!-- /.right-side -->
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js"></script>
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
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/materialize.js'); ?>"></script>
<script type="text/javascript">	
	$('.dropdown-button').dropdown({
		inDuration: 300,
		outDuration: 225,
		constrain_width: true, // Does not change width of dropdown to that of the activator
		hover: true, // Activate on hover
		gutter: 0, // Spacing from edge
		belowOrigin: false, // Displays dropdown below the button
		alignment: 'left' // Displays dropdown with edge aligned to the left of button
    });
    function changeCity(id) {
    	city_id = id.substring(id.indexOf('_') + 1, id.length);
    	city_name = $("#" + id).html();
    	var form = '<form action="/admin/adminhome/change_admin_city" method="POST">';
    	form += '<input type="hidden" name="CityId" value="' + city_id + '" />';
    	form += '<input type="hidden" name="CityName" value="' + city_name + '" />';
    	form += '<input type="submit" name="city_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
    }
</script>
</body>
</html>