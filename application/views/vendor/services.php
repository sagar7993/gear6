<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Panel - Vendor Services Details</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('vendor/components/_vcss'); ?>
	<style type="text/css">
		.styled-select {
			font-size: 1rem;
		}
		.form-control {
			height: 47px;
		}
	</style>
</head>
<body>
	<?php $this->load->view('vendor/components/_head'); ?>
	<?php if(isset($v_is_logged_in) && $v_is_logged_in == 1) { ?>
		<div class="wrapper row-offcanvas row-offcanvas-left">
			<?php $this->load->view('vendor/components/_sidebar'); ?>
			<aside class="right-side">
				<section class="content-header">
					<h1>
						Services
						<small>Services Offered, Bikes &amp; Amenities</small>
					</h1>
				</section>
				<form method="POST" action="/vendor/profile/save_services">
				<section class="area-content">
					<div class="col-xs-12 area-box">
						<div class="pickup-title teal-bold">Services Offered</div>
						<div class="sub-title-grey">Select the Services your Service Center offers</div>
						<div class="col-xs-12 area-container">
							<?php if(isset($services)) { foreach($services as $service) { ?>
							<div class="col-xs-3">
								<div class="checkbox" style="">
									<label class=""><input type="checkbox" class="scs addr_radio" id="ser_<?php echo $service->ServiceId; ?>"<?php if (isset($sel_services) && in_array(intval($service->ServiceId), $sel_services)) { echo " checked"; } ?> name="service[]" value="<?php echo $service->ServiceId; ?>">
										<span style="margin-left:5px"><?php echo convert_to_camel_case($service->ServiceName); ?></span>
									</label>
								</div> 
							</div>
							<?php } } ?>
						</div>
					</div>
					<div class="button-content">
						<div class="button-box col-xs-12">
							<div class="button-container col-xs-12">
								<button class='next btn waves-effect waves-light btn-flat btnUpdate-pu right' disabled id="serviceSubmit" type="submit">
									Update Services
								</button>
							</div>
						</div>
					</div>
				</section>
				</form>
				<form method="POST" action="/vendor/profile/save_bikemodels">
				<section class="radius-content">
					<div class="col-xs-12 radius-box">
						<div class="pickup-title teal-bold">Bike Companies</div>
						<div class="sub-title-grey">Select the Bike Companies of which models your Service Center offers services</div>
						<div class="col-xs-12 area-container padding-right-0 margin-left-0">
							<div class="col-xs-3">
								<div class="form-group" style="">
									<select class="form-control styled-select" name="company" id="company">
										<option value="" selected style='display:none;'>Select Company</option>
										<?php if(isset($bcompanies)) { foreach($bcompanies as $bcompany) { ?>
											<option value="<?php echo $bcompany->BikeCompanyId; ?>"><?php echo convert_to_camel_case($bcompany->BikeCompanyName); ?></option>
										<?php } } ?>
									</select>
								</div>
							</div>
							<div class="button-box col-xs-8 padding-right-0 right">
								<div class="button-container col-xs-12 right">
									<button class='next btn waves-effect waves-light btn-flat btnCancel-pu right' id="bmCancel" >
										Cancel
									</button>
									<button class='next btn waves-effect waves-light btn-flat btnUpdate-pu right' disabled id="bmSubmit" type="submit">
										Update BikeModels
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 radius-box" style="display:none;" id="bike-models">
						<div class="pickup-title teal-bold">Bike Models</div>
						<div class="sub-title-grey">Select the Bike Models to which your Service Center offer all services</div>
						<div class="col-xs-12 area-container" id="bm_container">
						</div>
					</div>
				</section><!-- /.content -->
				</form>
				<form method="POST" action="/vendor/profile/save_amenities">
				<section class="radius-content">
					<div class="col-xs-12 radius-box">
						<div class="pickup-title teal-bold">Amenities</div>
						<div class="sub-title-grey">Select or Add the Amenities your Service Center offers</div>
						<div class="col-xs-12 area-container">
							<?php if(isset($amenities)) { foreach($amenities as $amenity) { ?>
							<div class="col-xs-6">
								<div class="checkbox col-xs-12">
									<label class=""><input type="checkbox" class="amty addr_radio" id="amty_<?php echo $amenity->AmId; ?>"<?php if (isset($sel_amenities[intval($amenity->AmId)]) &&  $sel_amenities[intval($amenity->AmId)]['id'] == intval($amenity->AmId)) { echo " checked"; } ?> name="amenity[]" value="<?php echo $amenity->AmId; ?>">
										<span style="margin-left:5px"><?php echo convert_to_camel_case($amenity->AmName); ?></span>
									</label>
								</div>
								<div id="ad_<?php echo $amenity->AmId; ?>" class="col-xs-12" style="display:block !important">
									<div class="col-xs-12 padding-left-10px">
										<input type="text" class="col-xs-6 form-control <?php if (isset($sel_amenities[intval($amenity->AmId)])) { echo ''; } else { echo 'grey-bg'; } ?>" oninput="checkfield();" id="amdesc_<?php echo $amenity->AmId; ?>" name="am_desc_<?php echo $amenity->AmId; ?>"<?php if (isset($sel_amenities[intval($amenity->AmId)])) { echo ' value="' . $sel_amenities[intval($amenity->AmId)]['desc'] . '"'; } else { echo ' readonly'; } ?> placeholder="Enter Amenity Description">
									</div>
								</div>
							</div>
							<?php } } ?>
						</div>
					</div>
					<div class="button-content">
						<div class="button-box col-xs-12">
							<div class="button-container col-xs-12">
								<button class='next btn waves-effect waves-light btn-flat btnUpdate-pu right' id="amtySubmit" disabled>
									Update
								</button>
							</div>
						</div>
					</div>
				</section><!-- /.content -->
				</form>
			</aside><!-- /.right-side -->
		</div>
	<?php $this->load->view('vendor/components/_foot'); ?>
	<?php } ?>
<?php $this->load->view('vendor/components/_vjs'); ?>
<script type="text/javascript" src="<?php echo site_url('js/vpservices.js'); ?>"></script>
<script type="text/javascript">
</script>
</body>
</html>