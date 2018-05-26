<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Order Map - Admin Panel</title>
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
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.partial.css'); ?>">
	<style type="text/css">
		.select2-container{
			height: 4rem!important;
		}
		.select2-container--default .select2-selection--single .select2-selection__rendered {
			margin-top: 5px;
		}
		.select2-container .select2-selection--single .select2-selection__rendered {
			font-size: 15px;
			margin-top: 5px;
		}
		.select2-container--default .select2-selection--single .select2-selection__arrow {
			top: 9px;
		}
		.select2-results__option {
			font-size: 15px;
		}
		.select2-container--default .select2-selection--multiple .select2-selection__rendered {
			height: 3rem!important;
		}
		.select2-container--default.select2-container--focus .select2-selection--multiple {
		  border: none;
		  outline: 0;
		}
		input[type=text], input[type=password], input[type=email], input[type=url], input[type=time], input[type=date], input[type=datetime-local], input[type=tel], input[type=number], input[type=search], textarea.materialize-textarea {
			background-color: transparent;
			border: none;
			border: 1px solid #d7d7d7;
			border-radius: 0;
			text-align: center;
			vertical-align: middle;
			outline: none;
			height: 4rem;
			width: 100%;
			font-size: 1.4rem;
			margin: 0 0 15px 0;
			padding: 0;
			box-shadow: none;
			-webkit-box-sizing: content-box;
			-moz-box-sizing: content-box;
			box-sizing: content-box;
			transition: all .3s;
		}
		#map img { max-width: none!important; }
		#map { height: 100%; }
		.controls {
		  margin-top: 10px;
		  border: 1px solid transparent;
		  border-radius: 2px 0 0 2px;
		  box-sizing: border-box;
		  -moz-box-sizing: border-box;
		  height: 32px;
		  outline: none;
		  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
		}
		#pac-input {
		  background-color: #fff;
		  font-family: Roboto;
		  height: 3rem;
		  font-size: 1.4rem;
		  font-weight: 300;
		  margin-left: 12px;
		  padding: 0 11px 0 13px;
		  text-overflow: ellipsis;
		  width: 300px;
		  margin-top: 9px;
		}
		#pac-input:focus {
		  border-color: #4d90fe;
		}
		.pac-container {
		  font-family: Roboto;
		}
		#type-selector {
		  color: #fff;
		  background-color: #4d90fe;
		  padding: 5px 11px 0px 11px;
		}
		#type-selector label {
		  font-family: Roboto;
		  font-size: 13px;
		  font-weight: 300;
		}
		#target {
		  width: 345px;
		}
	</style>
	<script type="text/javascript">
		var orderMap = <?php if(isset($orderMap)) { echo json_encode($orderMap); } ?>;
		var serviceCenterMap = <?php if(isset($serviceCenterMap)) { echo json_encode($serviceCenterMap); } ?>;
		var locationMap = <?php if(isset($locationMap)) { echo json_encode($locationMap); } ?>;
		var serviceCenterLocationMap = <?php if(isset($serviceCenterLocationMap)) { echo json_encode($serviceCenterLocationMap); } ?>;
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
					View Order Demography
					<small>View Order Demography</small>
				</h1>
				<ol class="breadcrumb">
					<li><a target="_blank" href="#"><i class="fa fa-dashboard"></i>Order Dashboard</a></li>
					<li class="active">Order Demography</li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="row">
							<div class="col-xs-6 form-group fields-container" id="bikeBrandsContainer">
								<div class="col-xs-12 field-box">
									<select class="form-control styled-select" onchange="validate_order_map();populate_service_centers();" name="bikeBrands" id="bikeBrands" multiple="multiple">
										<?php if(isset($bike_brands) && count($bike_brands) > 0) { foreach($bike_brands as $bike_brand) { ?>
											<option value="<?php echo $bike_brand['BikeCompanyId']; ?>"><?php echo $bike_brand['BikeCompanyName']; ?></option>
										<?php } } ?>
									</select>
								</div>
							</div>
							<div class="col-xs-6 form-group fields-container" id="serviceCentersContainer">
								<div class="col-xs-12 field-box">
									<select class="form-control styled-select" onchange="validate_order_map();" name="serviceCenters" id="serviceCenters" multiple="multiple">
									</select>
								</div>
							</div>
							<div class="col-xs-6 form-group fields-container">
								<input type="text" class="form-control dpDate2 cursor-pointer" readonly='true' onchange="validate_order_map();" name="startDate" id="startDate" placeholder="Start Date">
							</div>
							<div class="col-xs-6 form-group fields-container">
								<input type="text" class="form-control dpDate2 cursor-pointer" readonly='true' onchange="validate_order_map();" name="endDate" id="endDate" placeholder="End Date">
							</div>
							<div class="button-box-contact col-xs-12">
								<div class="button-container col-xs-6 col-xs-offset-5">
									 <button class='next btn btn-primary btnUpdate-pu' id="filter" disabled="disabled">
										Filter Orders
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section class="content" id="map-container" align="center" class="center">
				<input id="pac-input" class="controls" type="text" placeholder="Search Box">
				<div id="map" style="height:400px!important;" align="center" class="center"></div>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<?php echo $dateRange; ?><br/>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<?php echo $bikeBrandsRange; ?><br/>
					</div>
					<div class="col-xs-4">
						<?php echo $serviceCentersRange; ?>
					</div>
					<div class="col-xs-4">
						<?php echo $locationRange; ?>
					</div>
				</div>
			</section>
		</aside>
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCZ126reFV784ZQTqw_JfD08mnS0jI7nWo&libraries=places"></script>
<script type="text/javascript" src="<?php echo site_url('js/select2.full.min.js'); ?>"></script>
<script type="text/javascript">
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
</script>
<script type="text/javascript" src="<?php echo site_url('js/orderDemography.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
</body>
</html>