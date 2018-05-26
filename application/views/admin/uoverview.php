<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Users Dashboard - Admin Panel</title>
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
					Users Dashboard
					<small>Overview &amp; Analysis</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Users Dashboard</li>
				</ol>
			</section>
			<section class="content">
				<div class='callout callout-info'>
					<h4>Top 10 Customers</h4>
					<p>The following graph gives you top 10 best customers.</p>
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
									<div id="top_ten_customers_analysis" style="height:300px"></div>
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
<script type="text/javascript">
	function top_ten_customers_analysis(utype) {
		$.ajax({
			type: "POST",
			url: "/admin/users/top_ten_customers_analysis",
			data: {utype: utype},
			datuType: "json",
			cache: false,
			success: function(data) {
				var user = data.user; var series = []; var map = {}; var idmap = {};
				for(var k=0; k<data.user_ids.length; k++) {
					map[data.user_ids[k]] = {};
					for(var x=0; x<data.categories.length; x++) {
						map[data.user_ids[k]][data.categories[x]] = 0;
					}
				}
				for (var i = 0; i < user.length; i++) {
					for (var j = 0; j < user[i].length; j++) {
						idmap[user[i][j]['user_id']] = user[i][j]['name'];
						if(utype !== 'Monthly') {
							map[user[i][j]['user_id']][user[i][j]['date']] = Number(user[i][j]['count']);
						} else {
							map[user[i][j]['user_id']][data.categories[i]] = Number(user[i][j]['count']);
						}
					}
				}
				for(var k=0; k<data.user_ids.length; k++) {
					var temp = map[data.user_ids[k]]; var values = [];
					for(key in temp) { values.push(Number(temp[key])); }
					series.push({
						type: 'column',
						name: idmap[data.user_ids[k]],
						data: values
					});
				}
				$('#top_ten_customers_analysis').highcharts({
					chart: {
						type: 'column'
					},
					title: {
						text: data.utype.name
					},
					subtitle: {
						text: 'Source: gear6.in'
					},
					xAxis: {
						categories: data.categories
					},
					yAxis: {
						title: {
							text: 'Orders Count'
						},
						allowDecimals: false
					},
					credits: {
						enabled: false
					},
					labels: {
						items: [{
							style: {
								left: '50px',
								top: '18px',
								color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
							}
						}]
					},
					series: series
				});
			}
		});
	}
	$(function() {
		try {
			top_ten_customers_analysis('Weekly');
			$('.btn-toggle').on('click', function() {
				$(this).find('.btn').toggleClass('active');
				$(this).find('.btn').toggleClass('btn-primary');
				$(this).find('.btn').toggleClass('btn-default');
				var analysis_type = $(this).find('.active').text().trim();
				var analysis_id = $(this).parent().parent().next().attr('id');
				window[analysis_id](analysis_type);
			});
		} catch (err) {
			// Do Nothing
		}
	});
</script>
</body>
</html>