<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Panel - Queried Orders</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('vendor/components/_vcss'); ?>
</head>
<body>
	<?php $this->load->view('vendor/components/_head'); ?>
	<?php if(isset($v_is_logged_in) && $v_is_logged_in == 1) { ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<?php $this->load->view('vendor/components/_sidebar'); ?>
		<aside class="right-side">
			<?php $this->load->view('vendor/components/_heading'); ?>
			<section class="">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-solid">
							<div class="box-header">
								<i class="fa fa-bar-chart-o"></i>
								<h3 class="box-title">Order Split Details - Queried</h3>
							</div><!-- /.box-header -->
							<div class="box-body">
								<div class="row">
									<div class="col-md-4 text-center">
										<input type="text" class="knob" readonly value="<?php if (isset($n_query)) { echo $n_query; } ?>" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#00a65a"/>
										<div class="knob-label">New Queries</div>
									</div><!-- ./col -->
									<div class="col-md-4 text-center">
										<input type="text" class="knob" readonly value="<?php if (isset($d_query)) { echo $d_query; } ?>" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#f56954"/>
										<div class="knob-label">Delayed Queries</div>
									</div><!-- ./col -->
									<div class="col-md-4 text-center">
										<input type="text" class="knob" readonly value="<?php if (isset($a_query)) { echo $a_query; } ?>" data-skin="tron" data-thickness="0.2" data-width="120" data-height="120" data-fgColor="#932ab6"/>
										<div class="knob-label">Answered Queries</div>
									</div><!-- ./col -->
								</div><!-- /.row -->
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div><!-- /.col -->
				</div>
			</section><!-- /.content -->
			<div class="row">
				<div class="col-xs-12">
					<div class="box box-solid">
						<div class="box-header dash-box-header">
							<i class="fa fa-bar-chart-o"></i>
							<h3 class="box-title font16">Query Analysis - Weekly/Monthly</h3>
						</div><!-- /.box-header -->
						<div class="box-body">
							<div class="checkbox" id="toggle-grp">
								<label>
									<div class="btn-group btn-toggle form-group toggle-btn"> 
										<button class="btn btn-xs btn-default active tog-btn">Weekly</button>
										<button class="btn btn-xs btn-primary tog-btn">Monthly</button>
									</div>
								</label>
							</div>
							<div class="row">
								<div id="query_page_analysis" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
							</div><!-- /.row -->
						</div><!-- /.box-body -->
					</div><!-- /.box -->
				</div><!-- /.col -->
			</div><!-- /.row -->
			<section>
				<div id="detail_tab" class="table-margin-top">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Order ID</th>
								<th style="width:12%"><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Bike Model</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th class="last"><i class="fa fa-user"></i> &nbsp;&nbsp;Customer Name</th>
								<th class="last"><i class="fa fa-keyboard-o"></i> &nbsp;&nbsp;Query In-Brief</th>
							</tr>
						</thead>
						<tbody> 
							<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
							<tr id="<?php echo $count; ?>">
								<td ><a href="<?php echo site_url('vendor/odetail/show/' . $row['oid']); ?>" class="order-id-link"><?php echo $row['oid']; ?></a></td>
								<td><?php echo $row['bmodel']; ?></td>
								<td><?php echo $row['phone']; ?></td>
								<td><?php echo $row['username']; ?></td>
								<td><?php echo $row['query_desc']; ?></td>
							</tr>
							<?php $count += 1; } } ?>
						</tbody>
					</table>
				</div>
			</section>
		</aside>
	</div>
	<?php $this->load->view('vendor/components/_foot'); ?>
	<?php } ?>
<?php $this->load->view('vendor/components/_vjs'); ?>
<script type="text/javascript" src="<?php echo site_url('js/highcharts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/exporting.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.knob.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.sparkline.min.js'); ?>"></script>
<script>
function query_page_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/vendor/vendorhome/query_page_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
			$('#query_page_analysis').highcharts({
				chart: {
					type: 'spline'
				},
				title: {
					text: data.atype.name
				},
				subtitle: {
					text: 'Source: gear6.in'
				},
				xAxis: {
					categories: data.categories
				},
				yAxis: {
					title: {
						text: 'Queries'
					},
					labels: {
						formatter: function () {
							return this.value;
						}
					},
					allowDecimals: false
				},
				tooltip: {
					crosshairs: true,
					shared: true
				},
				plotOptions: {
					spline: {
						marker: {
							radius: 4,
							lineColor: '#666666',
							lineWidth: 1
						}
					}
				},
				series: [{
					name: 'Queried',
					marker: {
						symbol: 'square'
					},
					data: data.queried
				}, {
					name: 'Answered',
					marker: {
						symbol: 'diamond'
					},
					data: data.answered
				}]
			});
		}
	});
}
$(function() {
	query_page_analysis('Weekly');
	$('.btn-toggle').on('click', function() {
		$(this).find('.btn').toggleClass('active');
		$(this).find('.btn').toggleClass('btn-primary');
		$(this).find('.btn').toggleClass('btn-default');
		var analysis_type = $(this).find('.active').text().trim();
		query_page_analysis(analysis_type);
	});
});
</script>
</body>
</html>