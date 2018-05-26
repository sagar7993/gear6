<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Spare Parts - Admin Panel</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link rel="shortcut icon" target="_blank" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" target="_blank" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" target="_blank" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" target="_blank" href="<?php echo site_url('css/style.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" target="_blank" href="<?php echo site_url('css/vstyle.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" target="_blank" href="<?php echo site_url('css/ionicons.min.css'); ?>">
	<link rel="stylesheet" type="text/css" target="_blank" href="<?php echo site_url('css/module.css'); ?>">
	<link rel="stylesheet" type="text/css" href="/css/select2.min.css">
	<link rel="stylesheet" type="text/css" target="_blank" href="<?php echo site_url('css/datatables/dataTables.bootstrap.css'); ?>">
	<link rel="stylesheet" type="text/css" target="_blank" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" target="_blank" href="//fonts.googleapis.com/css?family=Oxygen" type="text/css">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.partial.css'); ?>">
	<style>
	input[type=text], input[type=password], input[type=email], input[type=url], input[type=time], input[type=date], input[type=datetime-local], input[type=tel], input[type=number], input[type=search], textarea.materialize-textarea {
	  background-color: transparent;
	  border: none;
	  border: 1px solid #d7d7d7;
	  border-radius: 0;
	  text-align: center;
	  outline: none;
	  height: 3rem;
	  width: 100%;
	  font-size: 1.4rem;
	  margin: 0 0 15px 0;
	  padding: 0;
	  box-shadow: none;
	  -webkit-box-sizing: content-box;
	  -moz-box-sizing: content-box;
	  box-sizing: content-box;
	  transition: all .3s; }
	</style>
</head>
<body>
	<?php $this->load->view('admin/components/_head'); ?>
	<?php if(isset($a_is_logged_in) && $a_is_logged_in == 1) { ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<?php $this->load->view('admin/components/_sidebar'); ?>
		<aside class="right-side">
			<section class="content-header">
				<h1>
					View Spare Parts
					<small>View Spare Parts</small>
				</h1>
				<ol class="breadcrumb">
					<li><a target="_blank" href="#"><i class="fa fa-dashboard"></i>Order Dashboard</a></li>
					<li class="active">Spare Parts</li>
				</ol>
			</section>
			<section class="content" id="container">
				<div class="row" id="parameters">
					<div class="col-xs-12">
						<div id="step1" style="display:block;">
							<div class="col-xs-5 field-box">
								<input type="text" class="form-control" oninput="validation();" name="bikeModel" id="bikeModel" placeholder="Bike Model"/>
							</div>
							<div class="col-xs-4 field-box">
								<input type="text" class="form-control" oninput="validation();" name="location" id="location" placeholder="Location"/>
								<input style="display:none;" id="ulatitude" name="qlati">
								<input style="display:none;" id="ulongitude" name="qlongi">
							</div>
							<div class="col-xs-3 field-box">
								 <button class='next btn btn-primary btnUpdate-pu' id="serviceCenterButton" disabled>Fetch Service Centers</button>
							</div>
						</div>
						<div id="step2" style="display:none;">
							<div class="col-xs-5 field-box">
								<select class="form-control styled-select" name="serviceCenter" id="serviceCenter">
									<option></option>
								</select>
							</div>
							<div class="col-xs-5 field-box">
								<select class="form-control styled-select" name="sparePart" id="sparePart">
									<option></option>
								</select>
							</div>
							<div class="col-xs-2 field-box">
								 <button class='next btn btn-primary btnUpdate-pu' id="sparePartButton" disabled>Fetch Pricing</button>
							</div>
						</div>
					</div>
				</div>
				<?php if(isset($OId) && $OId !== NULL) { ?>
				<div class="row" id="priceDetails">
					<div class="col-xs-12">
						<div class="accordionFiltersExpandedView1" style="min-height: 0px !important;background-color:#fff !important;overflow-y:scroll !important;">
							<br/>							
							<?php if(isset($nothingToShowForSparePart)) { ?>
								<div class="price-details-container1">
									<div class="service-title-container1"><?php echo $nothingToShowForSparePart; ?></div>
								</div>
							<?php } ?>
							<div class="price-details-container1">
								<div class="service-title-container1">Price Details</div>
								<div class="price-title-container1">Price</div>
								<div class="price-map-container1">
									<?php if(isset($estprices) && $estprices !== NULL) { ?>
									<div class="service-list-container">
										<div class="sub-price-text green-text">Service / Amenity Details - <span>Estimated Charges</span></div>
										<?php foreach($estprices as $estprice) { if(isset($estprice['apdesc']) && isset($estprice['aprice'])) { ?>
											<div class="service-title">
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo convert_to_camel_case($estprice['apdesc']); ?>
											</div>
											<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $estprice['aprice']; ?></div>
											<?php if(intval($estprice['atprice']) != 0) { ?>
												<div class="service-title">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $estprice['atdesc']; ?></div>
												<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $estprice['atprice']; ?></div>
											<?php } ?>
										<?php } } ?>
									</div>
									<div class="final-price-container">
										<i class="fa fa-inr"></i>&nbsp;<?php echo $estprices[count($estprices) - 1]['ptotal']; ?>
									</div>
									<?php } ?>
									<?php if (isset($oprices) && count($oprices) > 0) { ?>
										<div class="service-list-container">
											<div class="sub-price-text green-text">Additional Charges</div>
											<?php foreach($oprices as $oprice) { if(isset($oprice['opdesc']) && isset($oprice['oprice'])) { ?>
												<div class="service-title">
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo convert_to_camel_case($oprice['opdesc']); ?>
												</div>
												<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $oprice['oprice']; ?></div>
											<?php } } ?>
										</div>
										<div class="final-price-container">
											<i class="fa fa-inr"></i>&nbsp;<?php echo $oprices[count($oprices) - 1]['ptotal']; ?>
										</div>
									<?php } ?>
									<?php if (isset($discprices) && count($discprices) > 0) { ?>
										<div class="service-list-container">
											<div class="sub-price-text green-text">Discount Details</div>
											<?php foreach($discprices as $discprice) { if(isset($discprice['apdesc']) && isset($discprice['aprice'])) { ?>
												<div class="service-title">
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo convert_to_camel_case($discprice['apdesc']); ?>
												</div>
												<div class="price-text">&nbsp;-&nbsp;<i class="fa fa-inr"></i>&nbsp;<?php echo $discprice['aprice']; ?></div>
											<?php } } ?>
										</div>
										<div class="final-price-container">
											&nbsp;-&nbsp;<i class="fa fa-inr"></i>&nbsp;<?php if(isset($discprices)) { echo $discprices[count($discprices) - 1]['ptotal']; } else { echo $estprice; } ?>
										</div>
									<?php } ?>
								</div>
							</div>
							<div class="price-details-container1">
								<div class="service-title-container1">Total Price Summary</div>
								<div class="price-title-container1">Price</div>
								<div class="price-map-container1">
									<div class="service-list-container">
										<div class="service-title">Total Billed Amount</div>
										<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php if(isset($tot_billed)) { echo $tot_billed; } ?></div>
										<div class="service-title">Total Paid Amount</div>
										<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php if(isset($tot_paid)) { echo $tot_paid; } ?></div>
										<div class="service-title">Total Amount to be Paid</div>
										<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php if(isset($to_be_paid)) { echo $to_be_paid; } ?></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } elseif(isset($sparePartPriceRange) && count($sparePartPriceRange) > 0) { ?>
					<?php if($sparePartPriceMin == $sparePartPriceMax) { ?>
						<div class="price-details-container1">
							<div class="service-title-container1">Approximate Price : Rs. <?php echo $sparePartPriceMin; ?></div>
						</div>
					<?php } else { ?>
						<div class="price-details-container1">
							<div class="service-title-container1">Approximate Price : Rs. <?php echo $sparePartPriceMin; ?> - Rs. <?php echo $sparePartPriceMax; ?></div>
						</div>
				<?php } } elseif(isset($nothingToShow)) { ?>
					<div class="price-details-container1">
						<div class="service-title-container1"><?php echo $nothingToShow; ?></div>
					</div>
				<?php } ?>
			</section>
		</aside>
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script src="/nhome/js/lib/select2.full.min.js"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCZ126reFV784ZQTqw_JfD08mnS0jI7nWo&libraries=places"></script>
<script type="text/javascript" src="<?php echo site_url('js/spareParts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
</body>
</html>