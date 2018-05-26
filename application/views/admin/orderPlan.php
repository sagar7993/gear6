<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Order Plan - Admin Panel</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link rel="shortcut icon" target="_blank" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" target="_blank" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" target="_blank" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" target="_blank" href="<?php echo site_url('css/style.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" target="_blank" href="<?php echo site_url('css/vstyle.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" target="_blank" href="<?php echo site_url('css/ionicons.min.css'); ?>">
	<link rel="stylesheet" type="text/css" target="_blank" href="<?php echo site_url('css/module.css'); ?>">
	<link rel="stylesheet" type="text/css" target="_blank" href="<?php echo site_url('css/datatables/dataTables.bootstrap.css'); ?>">
	<link rel="stylesheet" type="text/css" target="_blank" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" target="_blank" href="//fonts.googleapis.com/css?family=Oxygen" type="text/css">
	<style type="text/css">
		#map img { max-width: none!important; }
	</style>
	<script type="text/javascript">
		var orderPlan = <?php echo json_encode($orderPlan); ?>;
	</script>
</head>
<body>
	<?php $this->load->view('admin/components/_head'); ?>
	<?php if(isset($a_is_logged_in) && $a_is_logged_in == 1) { ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<?php $this->load->view('admin/components/_sidebar'); ?>
		<aside class="right-side">
			<section class="content-header">
				<h1>
					View Order Plan
					<small>View Order Plan</small>
				</h1>
				<ol class="breadcrumb">
					<li><a target="_blank" href="#"><i class="fa fa-dashboard"></i>Order Dashboard</a></li>
					<li class="active">Order Plan</li>
				</ol>
			</section>
			<section class="content" id="map-container" align="center" class="center">
				<div id="map" style="height:400px!important;" align="center" class="center"></div>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="row">
							<div class="col-xs-1 mobile-hidden margin-top-295px">
								<button id="previousWeekOrderPlan" class="btn btn-xs btn-primary active tog-btn"><i class="fa fa-arrow-left"></i></button>
							</div>
							<div class="col-xs-10">
								<div class="row">
									<?php if(isset($orderPlan)) foreach($orderPlan as $date=>$order) { ?>
										<div class="col-xs-3">
											<div class="box box-solid static-height">
												<div class="box-header">
													<p class="box-title text-warning"><?php echo $date; ?></p><i id="<?php echo $date; ?>" class="fa fa-map-marker cursor-pointer refresh"></i>
												</div>
												<div class="box-body text-center">
													<?php if(isset($order)) foreach($order as $slot=>$plans) { ?>
														<div class="slot-text"><?php echo $slot; ?> - <?php if(isset($plans) && count($plans)>0) 
														{ echo "<u class='cursor-pointer' onclick='showModal(" . json_encode($plans) . ", \"" . $date . "\")'>" . count($plans) . "</u>"; } else { echo "0"; } ?>
														</div>
													<?php } ?>
												</div>
											</div>
										</div>
									<?php } ?>
								</div>
							</div>
							<div class="col-xs-1 mobile-hidden margin-top-295px">
								<button id="nextWeekOrderPlan" class="btn btn-xs btn-primary active tog-btn"><i class="fa fa-arrow-right"></i></button>
							</div>
						</div>
					</div>
				</div>
			</section>
		</aside>
	</div>
	<div id="dialog" style="display:none;">
	  <p id="modal-text"></p>
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCZ126reFV784ZQTqw_JfD08mnS0jI7nWo&libraries=places"></script>
<script type="text/javascript" src="<?php echo site_url('js/orderPlan.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script>
$(function () {
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
});
function showModal(plans, date) {
	var oids = "";
	for(var i = 0; i < plans.length; i++) {
		oids += "<a target='_blank' href='/admin/orders/odetail/" + plans[i].OId + "'>" + plans[i].OId + "</a><br/>";
	}
	$("#modal-text").html(oids);
	document.getElementById('dialog').style.display="none";
	$("#dialog").attr("title", "Orders For " + date);
	$("#dialog").dialog();
}
</script>
</body>
</html>