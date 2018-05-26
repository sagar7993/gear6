<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Panel - Price Chart</title>
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
						Price Chart
						<small>Serice &amp; Amenities Prices</small>
					</h1>
				</section>
				<form method="POST" action="/vendor/profile/save_srprice">
				<section class="area-content">
					<div class="col-xs-12 area-box">
						<div class="pickup-title teal-bold">Services</div>
						<div class="sub-title-grey">Add/Edit desired price for respective services you opted</div>
						<div class="col-xs-12 area-container">
							<div class="col-xs-12 area-container">
								<div class="col-xs-3">
									<div class="form-group" style="">
										<select class="form-control styled-select" onchange="checkfield1();" name="stype" id="stype">
											<option value="" disabled selected style='display:none;'>Service Category</option>
											<?php if(isset($pservices)) { foreach($pservices as $pservice) { ?>
												<option value="<?php echo $pservice['ServiceId']; ?>"><?php echo $pservice['ServiceName']; ?></option>
											<?php } } ?>
										</select>
									</div>
								</div>
								<div class="col-xs-3">
									<div class="form-group" style="">
										<select class="form-control styled-select" name="company" id="company">
											<option value="" selected style='display:none;'>Select Company</option>
											<?php if(isset($bcompanies)) { foreach($bcompanies as $bcompany) { ?>
												<option value="<?php echo $bcompany['BikeCompanyId']; ?>"><?php echo convert_to_camel_case($bcompany['BikeCompanyName']); ?></option>
											<?php } } ?>
										</select>
									</div>
								</div>
								<div class="col-xs-2">
									<input type="text" class="col-xs-6 form-control " oninput="checkfield1();" name="sr_price" id="sr_price" placeholder="Price in INR">
								</div>
								<button type="submit" name="srp_sub" id="srp_sub" class="margin-top-5px btn waves-effect waves-light btn-flat left" disabled>
									Update Price
								</button> 
								<button type="button" id="srp_can" class="margin-top-5px margin-left-10px btn waves-effect waves-light btn-flat left" >
									Cancel
								</button>
							</div>
						</div>
					</div>
				</section>
				<section class="bike-models" id="bike-models" style="display:none">
					<div class="col-xs-12 radius-box">
						<div class="pickup-title teal-bold">Select Bike Models</div>
						<div class="sub-title-grey">Select the Bike Models to which you want to apply the price</div>
						<div class="col-xs-12 area-container" id="bm_container">
						</div>
					</div>
				</section><!-- /.content -->
				</form>
				<section class="radius-content">
					<div class="col-xs-12 radius-box">
						<div class="pickup-title teal-bold">Amenities</div>
						<div class="sub-title-grey">Select or Add the Amenities your Service Center offers</div>
						<form method="POST" action="/vendor/profile/save_amprice">
						<div class="col-xs-12 area-container">
							<div class="col-xs-3">
								<div class="form-group" style="">
									<select class="form-control styled-select" onchange="checkfield();" name="am_id" id="am_id">
										<option value="" disabled selected style='display:none;'>Service Amenity</option>
										<?php if(isset($pamenities)) { foreach($pamenities as $pamenity) { ?>
											<option value="<?php echo $pamenity['AmId']; ?>"><?php echo convert_to_camel_case($pamenity['AmName']); ?></option>
										<?php } } ?>
									</select>
								</div>
							</div>
							<div class="col-xs-3">
								<input type="text" class="col-xs-6 form-control " oninput="checkfield();" name="am_price" id="am_price" placeholder="Price in INR">
							</div>
							<button type="submit" name="amp_sub" id="amp_sub" class="margin-top-5px btn waves-effect waves-light btn-flat" disabled>
								Update Price
							</button>
						</div>
						</form>
					</div>
				</section><!-- /.content -->
				<div class='callout callout-info' style="margin-top: 22px;">
					<h5>Amenity Price Chart</h5>
					<p>List of all the amenities and the corresponding prices allotted</p>
				</div>  
				<div id="detail_tab1" class="table-margin-top margin-bottom-0">
					<table id="example3" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-cogs"></i> &nbsp;&nbsp;Amenity</th>
								<th><i class="fa fa-inr"></i> &nbsp;&nbsp;Price</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Last Updated</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($amprices)) { foreach($amprices as $amprice) { ?>
							<tr id="<?php echo $amprice['APriceId']; ?>">
								<td><?php echo convert_to_camel_case($amprice['AmName']); ?></td>
								<td><?php echo $amprice['Price']; ?></td>
								<td><?php echo $amprice['Timestamp']; ?></td>
							</tr>
							<?php } } ?>
						</tbody>
					</table>
				</div>
				<div class='callout callout-info margin-top-50'>
					<h5>Services Price Chart</h5>
					<p>List of all the Services and the corresponding prices allotted</p>
				</div>
				<div id="detail_tab" class="table-margin-top margin-bottom-0">
					<table id="example2" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-cogs"></i> &nbsp;&nbsp;Service</th>
								<th><i class="fa fa-institution"></i> &nbsp;&nbsp;Company</th>
								<th style="width:12%"><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Bike Model</th>
								<th><i class="fa fa-inr"></i> &nbsp;&nbsp;Price</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Last Updated</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($srprices)) { foreach($srprices as $srprice) { ?>
							<tr id="<?php echo $srprice['SPriceId']; ?>">
								<td><?php echo $srprice['ServiceName']; ?></td>
								<td><?php echo convert_to_camel_case($srprice['BikeCompanyName']); ?></td>
								<td><?php echo convert_to_camel_case($srprice['BikeModelName']); ?></td>
								<td><?php echo $srprice['Price']; ?></td>
								<td><?php echo $srprice['Timestamp']; ?></td>
							</tr>
							<?php } } ?>
						</tbody>
					</table>
				</div>
			</aside><!-- /.right-side -->
		</div>
	<?php $this->load->view('vendor/components/_foot'); ?>
	<?php } ?>
<?php $this->load->view('vendor/components/_vjs'); ?>
<script type="text/javascript" src="<?php echo site_url('js/vpricechart.js'); ?>"></script>
<script type="text/javascript">
</script>
</body>
</html>