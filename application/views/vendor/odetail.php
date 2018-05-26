<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Panel - Order Details</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('vendor/components/_vcss'); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/ustyle.css'); ?>">
	<style>
		.sidebar {
			margin-bottom: 5px;
			font-size: 14px;
			padding: 0px;
		}
	</style>
</head>
<body>
	<?php $this->load->view('vendor/components/_head'); ?>
	<?php if(isset($v_is_logged_in) && $v_is_logged_in == 1) { ?>
	<?php $this->load->view('vendor/components/_sidebar'); ?>
	<aside class="right-side auto-height">
		<section class="content-header">
			<h1>
				Order Details
				<small><?php if(isset($OId) && isset($uname) && isset($uemail) && isset($uphone)) { echo $OId . ' - User: ' . convert_to_camel_case($uname) . ' - Phone: ' . $uphone . ' - Email: ' . $uemail; } ?></small>
			</h1>
		</section>
		<div class="vendor-order-block">
			<?php if(isset($is_cancelled) && !$is_cancelled) { ?>
			<section class="section-center2">
				<div class="section-header-1">
					<span class="confirm-title">Order Status</span>
				</div>
				<div class="section-content-action1">
				<?php if(isset($serid) && $serid == 1 && isset($statuses) && isset($scenter)) { ?>
				<div class="col-xs-12" id="ps-block">
					<div class="track-text-block">
						<div class="col-custom">Order Approved</div>
						<div class="col-custom">Service In Progress</div>
						<div class="col-custom">Ready To Deliver</div>
					</div>
					<div class="fconn-block">
						<div class="fconnector"></div>
						<div class="fconnector"></div>
						<div class="fconnector-last"></div>
					</div>
					<div class="fstatus-block">
						<div class="col-xs-3 padding-0">
							<div class="status-hover fStatus" id="p-status-1">
								<span class="<?php if ($statuses[0]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-1"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="col-xs-3 padding-0">
							<div class="status-hover prStatus" id="p-status-2">
								<span class="<?php if ($statuses[1]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-2"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="col-xs-3 padding-0">
							<div class="status-hover prStatusC" id="p-status-3">
								<span class="<?php if ($statuses[2]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-3"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="col-xs-3 padding-0">
							<div class="status-hover prStatus" id="p-status-4">
								<span class="<?php if ($statuses[3]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-4"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="status-hover-block">
						<div class="status-strip-content">
							<div class="status-hover-container">
								<div class="status-hover-block-1" id="p-status-hover-block-1" <?php if ($statuses[0]['Order'] == $scenter[0]['Order'] || $scenter[0]['Order'] == 0) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[0]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[0]['StatusDesc2'];
											} else {
												echo $statuses[0]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="p-status-hover-block-2" <?php if ($statuses[1]['Order'] == $scenter[0]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[1]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[1]['StatusDesc2'];
											} else {
												echo $statuses[1]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="p-status-hover-block-3" <?php if ($statuses[2]['Order'] == $scenter[0]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[2]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[2]['StatusDesc2'];
											} else {
												echo $statuses[2]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="p-status-hover-block-4" <?php if ($statuses[3]['Order'] == $scenter[0]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[3]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[3]['StatusDesc2'];
											} else {
												echo $statuses[3]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
				<?php if(isset($serid) && $serid == 2 && isset($statuses) && isset($scenter)) { ?>
				<div class="col-xs-12" id="repair-block">
					<div class="track-text-block">
						<div class="col-custom">Order Approved</div>
						<div class="col-custom">Repair In Progress</div>
						<div class="col-custom">Ready To Deliver</div>
					</div>
					<div class="fconn-block">
						<div class="fconnector"></div>
						<div class="fconnector"></div>
						<div class="fconnector-last"></div>
					</div>
					<div class="fstatus-block">
						<div class="col-xs-3 padding-0">
							<div class="status-hover fStatus" id="r-status-1">
								<span class="<?php if ($statuses[0]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-1"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="col-xs-3 padding-0">
							<div class="status-hover prStatus" id="r-status-2">
								<span class="<?php if ($statuses[1]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-2"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="col-xs-3 padding-0">
							<div class="status-hover prStatusC" id="r-status-3">
								<span class="<?php if ($statuses[2]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-3"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="col-xs-3 padding-0">
							<div class="status-hover prStatus" id="r-status-4">
								<span class="<?php if ($statuses[3]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-4"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="status-hover-block">
						<div class="status-strip-content">
							<div class="status-hover-container">
								<div class="status-hover-block-1" id="r-status-hover-block-1" <?php if ($statuses[0]['Order'] == $scenter[0]['Order'] || $scenter[0]['Order'] == 0) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[0]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[0]['StatusDesc2'];
											} else {
												echo $statuses[0]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="r-status-hover-block-2" <?php if ($statuses[1]['Order'] == $scenter[0]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[1]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[1]['StatusDesc2'];
											} else {
												echo $statuses[1]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="r-status-hover-block-3" <?php if ($statuses[2]['Order'] == $scenter[0]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[2]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[2]['StatusDesc2'];
											} else {
												echo $statuses[2]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="r-status-hover-block-4" <?php if ($statuses[3]['Order'] == $scenter[0]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[3]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[3]['StatusDesc2'];
											} else {
												echo $statuses[3]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
				<?php if(isset($serid) && $serid == 4 && isset($statuses) && isset($scenter)) { ?>
				<div class="col-xs-12" id="insurance-block">
					<div class="track-text-block">
						<div class="text-center track-text">Order Approved</div>
						<div class="text-center track-text"><span class="margin-left-n10">Renewal in Progress</span></div>
						<div class="text-center track-text">Insurance Renewed</div>
					</div>
					<div class="conn-block">
						<div class="col-xs-6 connector"></div>
						<div class="col-xs-6 connector-last"></div>
					</div>
					<div class="status-block">
						<div class="rbBox-first">
							<div class="status-hover fStatus" id="i-status-1">
								<span class="<?php if ($statuses[0]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-1"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="rbBox-center">
							<div class="status-hover cStatus" id="i-status-2">
								<span class="<?php if ($statuses[1]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-2" style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="rbBox-last">
							<div class="status-hover lStatus" id="i-status-3">
								<span class="<?php if ($statuses[2]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-3" style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="status-hover-block">
						<div class="status-strip-content">
							<div class="status-hover-container">
								<div class="status-hover-block-1" id="i-status-hover-block-1" <?php if ($statuses[0]['Order'] == $scenter[0]['Order'] || $scenter[0]['Order'] == 0) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[0]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[0]['StatusDesc2'];
											} else {
												echo $statuses[0]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="i-status-hover-block-2" <?php if ($statuses[1]['Order'] == $scenter[0]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[1]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[1]['StatusDesc2'];
											} else {
												echo $statuses[1]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="i-status-hover-block-3" <?php if ($statuses[2]['Order'] == $scenter[0]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[2]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[2]['StatusDesc2'];
											} else {
												echo $statuses[2]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
				<?php if(isset($serid) && $serid == 3 && isset($statuses) && isset($scenter)) { $count = 1; foreach ($scenter as $sc) { ?>
				<label for="">Service Center Name: </label>
				<label class="selected-options"><?php if(isset($scenter)) { echo convert_to_camel_case($scenter[$count - 1]['ScName']); } ?></label>
				<div class="col-xs-12" id="query-block">
					<div class="track-text-block">
						<div class="text-center track-text">Query Approved</div>
						<div class="text-center track-text">Query Check in-process</div>
						<div class="text-center track-text">Query Answered</div>
					</div>
					<div class="conn-block">
						<div class="col-xs-6 connector"></div>
						<div class="col-xs-6 connector-last"></div>
					</div>
					<div class="status-block">
						<div class="rbBox-first">
							<div class="status-hover fStatus" id="q-status-<?php echo $count . '-1'; ?>">
								<span class="<?php if ($statuses[0]['Order'] <= $scenter[$count - 1]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-<?php echo $count . '-1'; ?>"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="rbBox-center">
							<div class="status-hover cStatus" id="q-status-<?php echo $count . '-2'; ?>">
								<span class="<?php if ($statuses[1]['Order'] <= $scenter[$count - 1]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-<?php echo $count . '-2'; ?>" style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="rbBox-last">
							<div class="status-hover lStatus" id="q-status-<?php echo $count . '-3'; ?>">
								<span class="<?php if ($statuses[2]['Order'] <= $scenter[$count - 1]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-<?php echo $count . '-3'; ?>" style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="status-hover-block">
						<div class="status-strip-content">
							<div class="status-hover-container">
								<div class="status-hover-block-1" id="q-status-hover-block-<?php echo $count . '-1'; ?>" <?php if ($statuses[0]['Order'] == $scenter[$count - 1]['Order'] || $scenter[$count - 1]['Order'] == 0) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[0]['Order'] <= $scenter[$count - 1]['Order']) {
												echo $statuses[0]['StatusDesc2'];
											} else {
												echo $statuses[0]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="q-status-hover-block-<?php echo $count . '-2'; ?>" <?php if ($statuses[1]['Order'] == $scenter[$count - 1]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[1]['Order'] <= $scenter[$count - 1]['Order']) {
												echo $statuses[1]['StatusDesc2'];
											} else {
												echo $statuses[1]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="q-status-hover-block-<?php echo $count . '-3'; ?>" <?php if ($statuses[2]['Order'] == $scenter[$count - 1]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[2]['Order'] <= $scenter[$count - 1]['Order']) {
												echo $statuses[2]['StatusDesc2'];
											} else {
												echo $statuses[2]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<br><br>
				<?php $count += 1; } } ?>
			</section>
			<?php } ?>
			<section class="section-center2">
				<div class="section-header-1">
					<span class="confirm-title">Convenience Fee Calculator</span>
				</div>
				<div class="section-content-action-update">
					<?php if(isset($is_cancelled) && !$is_cancelled) { ?>
						<div class="col-xs-12">
							<div class="col-xs-6 fields-container">
								<div class="col-xs-12">
									<input id="searchLocation" name="searchLocation" type="text" placeholder="User Location">
									<input style="display:none;" id="ulatitude" name="qlati">
									<input style="display:none;" id="ulongitude" name="qlongi">
								</div>
							</div>
							<div class="col-xs-3 fields-container" id="convenienceFee" name="convenienceFee" style="display:none;">
								<div class="col-xs-12">
									<input type="text" placeholder="Convenience Fee" id="convenienceFeeText" readonly="true">
								</div>
							</div>
							<div class="col-xs-3 text-center">
								<button name="getConvenienceFee" id="getConvenienceFee" class="btn waves-effect waves-light btn-flat" disabled>Get Convenience Fee</button>
							</div>
						</div>
					<?php } ?>
				</div>
			</section>
			<section class="section-center2">
				<div class="section-header-1">
					<span class="confirm-title">Manage Order</span>
				</div>
				<div class="section-content-action-update">
					<?php if(isset($is_cancelled) && !$is_cancelled) { ?>
					<div class="">
						<ul class="collapsible popout" data-collapsible="accordion">
							<li>
								<div class="collapsible-header"><i class="material-icons">speaker_notes</i>Change Status</div>
								<div class="collapsible-body">
								<br/>
								<?php if(TRUE || (isset($is_amt_cfmd) && $is_amt_cfmd != 1) || (isset($serid) && $serid == 3)) { ?>
								<div class="col-xs-12">
								<?php if(isset($rest_statuses[0]['StatusName']) && $rest_statuses[0]['StatusName'] != '' && $rest_statuses[0]['StatusName'] !== NULL) { ?>
								<form method="POST" action="/vendor/odetail/changeStatus/">
									<div class="form-group col-xs-2 margin-top-5" style="width:15%;">
										Update Status :
									</div>
									<div class="col-xs-6 fields-container desc_txt_mandatory" id="stat_txt_mandatory" style="display:none">
										<div class="col-xs-12">
											<?php if((isset($serid) && $serid == 1 )) { ?>
												<textarea  type="text" class="form-control margin-bottom-20px" oninput="checkfield(true);" name="stat_desc_c" id="desc_txt_mandatory" placeholder="Your bike check-up is done, proceeding for the service"></textarea>
											<?php } else if((isset($serid) && $serid == 2)) { ?>
												<textarea  type="text" class="form-control margin-bottom-20px" oninput="checkfield(true);" name="stat_desc_c" id="desc_txt_mandatory" placeholder="Your bike repair check-up is done, proceeding for the repair"></textarea>
											<?php } else if((isset($serid) && $serid == 3)) { ?>
												<textarea  type="text" class="form-control margin-bottom-20px" oninput="checkfield(true);" name="stat_desc_c" id="desc_txt_mandatory" placeholder="Type Query Answer Here (Compulsory)"></textarea>
											<?php } else if((isset($serid) && $serid == 4)) { ?>
												<textarea  type="text" class="form-control margin-bottom-20px" oninput="checkfield(true);" name="stat_desc_c" id="desc_txt_mandatory" placeholder="Your insurance details check-up is over, proceeding for its renewal"></textarea>
											<?php } ?>
										</div>
									</div>
									<div class="col-xs-6 fields-container desc_txt_optional" id="stat_txt_optional">
										<div class="col-xs-12">
											<textarea type="text" class="form-control margin-bottom-20px" name="stat_desc_o" id="desc_txt" placeholder="Status Description (Optional)"></textarea>
										</div>
									</div>
									<input id="stype-vendor" name="servicetype" type="hidden" value="<?php echo $rest_statuses[0]['StatusId']; ?>">
									<div class="col-xs-3 text-center">
										<button type="submit" name="changeStatus" id="changeStatus" class="btn waves-effect waves-light btn-flat" disabled><?php echo convert_to_camel_case($rest_statuses[0]['StatusName']); ?></button>
									</div>
								</div>
								<input type="hidden" name="oid" value="<?php if(isset($OId)) { echo $OId; } ?>">
								</form>
								<?php } else { ?>
									<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>This order is totally processed. Nothing left to do here</strong>
									</div>
								<?php } ?>
								<?php } else { ?>
									<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please wait till the user approves the amount</strong>
								<?php } ?>
								<br/>
								</div>
							</li>
							<?php if(isset($serid) && $serid != 3) { ?>
							<li>
								<div class="collapsible-header"><i class="material-icons">local_atm</i>Add Service Detail and Price</div>
								<div class="collapsible-body">
								<br/>
								<div class="price-details-container1">
									<div class="service-title-container1">Price Details</div>
									<div class="price-title-container1">Price</div>
									<div class="price-map-container1">
										<?php if(isset($estprices) && $estprices !== NULL) { ?>
										<div class="service-list-container">
											<div class="sub-price-text green-text">Service / Amenity Details - <span>Estimated Charges</span></div>
											<?php foreach($estprices as $estprice) { if(isset($estprice['apdesc']) && isset($estprice['aprice'])) { ?>
												<div class="service-title">
													<?php if(isset($estprice['apid'])) { ?>
														<a href="#" class="edit_aprice" data-type="ap" data-ttype="<?php echo $estprice['attype']; ?>" data-id="<?php echo $estprice['apid']; ?>" data-apdesc="<?php echo $estprice['apdesc']; ?>" data-aprice="<?php echo $estprice['aprice']; ?>"><img src="<?php echo site_url('img/icons/edit_disabled.png'); ?>" height="20px" width="20px" /></a>
													<?php } ?>
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
														<?php if(isset($oprice['opid'])) { ?>
															<a href="#" class="edit_oprice" data-type="op" data-id="<?php echo $oprice['opid']; ?>" data-opdesc="<?php echo $oprice['opdesc']; ?>" data-oprice="<?php echo $oprice['oprice']; ?>"><img src="<?php echo site_url('img/icons/edit_disabled.png'); ?>" height="20px" width="20px" /></a>
														<?php } ?>
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
													<div class="service-title"><?php echo convert_to_camel_case($discprice['apdesc']); ?></div>
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
								<div class="col-xs-12 price-list-container" style="display:none">
									<div class="col-xs-12 price-container">
										<div class="form-group col-xs-4 width50">
											<input type="text" class="form-control spdetail" oninput="checkPriceDetails();" name="" id="spdetail1" placeholder="Service Detail">
										</div>
										<div class="form-group col-xs-4 width25">
											<input type="text" class="form-control sp" oninput="checkPriceDetails();" name="" id="sp1" placeholder="Price Detail - eg. 350">
										</div>
										<div class="form-group col-xs-4 width25">
											<select class="form-control spttype" name="" id="spttype1">
												<option value="0">No Tax</option>
												<option value="1">Service Tax</option>
												<option value="2">VAT</option>
												<option value="3">Discount</option>
											</select>
										</div>
										<div class="col-xs-2 field-area-add" id="price_add"></div>
									</div>
								</div>
								<div class="center text-center" id="price-list-submit" style="display:none">
									<button name="submitform" id="priceupdate" disabled="true" class="btn waves-effect waves-light btn-flat" >Update Price</button>
								</div>
								<div class="col-xs-12" style="display:none" id="oprice_edit_block">
									<form method="POST" action="/vendor/odetail/update_opricedetail">
										<div class="col-xs-12">
											<div class="form-group col-xs-4">
												<input type="text" class="form-control" name="opdesc" id="op_opdesc" placeholder="Edit Price Description" required>
											</div>
											<div class="form-group col-xs-4">
												<input type="number" class="form-control" min="0" step="0.01" name="oprice" id="op_oprice" placeholder="Enter New Price" required>
											</div>
											<input type="hidden" name="opid" id="op_opid" />
											<input type="hidden" name="ptype" id="p_ptype" />
											<input type="hidden" name="ttype" id="p_ttype" />
											<input type="hidden" name="opoid" value="<?php echo $OId; ?>" />
											<div class="col-xs-4" style="margin-top: 5px;">
												<button name="opriceupdate" id="opriceupdate" class="btn waves-effect waves-light btn-flat">Edit Price Detail</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<?php } ?>
							<li>
								<div class="collapsible-header"><i class="material-icons">assignment</i>Status History</div>
								<div class="collapsible-body">
								<br/>
								<?php if(isset($stathists)) { ?>
									<div class="col-xs-12 status-history-title-container">
										<div class="form-group col-xs-2 width20 center-align">
											<strong>Order Status</strong>
										</div>
										<div class="form-group col-xs-8 width60 center-align" >
											<strong>Status Description by Vendor</strong>
										</div>
										<div class="form-group col-xs-2 center-align">
											<strong>Updated On</strong>
										</div>
									</div>
								<?php foreach($stathists as $stathist) { ?>
								<div class="col-xs-12 status-history-content-container">
									<div class="form-group col-xs-2 width20">
										<?php echo $stathist['sname']; ?>
									</div>
									<div class="form-group col-xs-8 width60 both-dot-border" >
										<?php if(isset($stathist['sdesc']) && $stathist['sdesc'] != '') { echo $stathist['sdesc']; } else { echo '&nbsp;&nbsp;'; } ?>
									</div>
									<div class="form-group col-xs-2 width20">
										<?php echo $stathist['date']; ?>
									</div>
								</div>
								<?php } } else { ?>
									<div class="col-xs-12 status-history-content-container">
										<strong>No status changes yet.</strong>
									</div>
								<?php } ?>
							</div>
							<?php if(isset($serid) && $serid != 3) { ?>
							<li>
								<div class="collapsible-header"><i class="material-icons">payment</i>Order Transactions</div>
								<div class="collapsible-body">
								<?php if(isset($ord_trans) && $ord_trans !== NULL) { ?>
									<br/>
									<div class="col-xs-12 status-history-title-container">
										<div class="form-group col-xs-2 width20 center-align">
											<strong>Transaction Id</strong>
										</div>
										<div class="form-group col-xs-6 center-align" style="width: 40%">
											<strong>Transaction Date</strong>
										</div>
										<div class="form-group col-xs-2 width20 center-align">
											<strong>Transaction Amount</strong>
										</div>
										<div class="form-group col-xs-2 width20 center-align">
											<strong>Transaction Status</strong>
										</div>
									</div>
									<?php
									foreach($ord_trans as $trans) {
									?>
									<div class="col-xs-12 status-history-content-container">
										<div class="form-group col-xs-2 width20">
											<?php echo $trans['TId']; ?>
										</div>
										<div class="form-group col-xs-6 both-dot-border" style="width: 40%">
											<?php echo $trans['TimeStamp']; ?>
										</div>
										<div class="form-group col-xs-2 width20 right-dot-margin">
											<?php echo $trans['PaymtAmt']; ?>
										</div>
										<div class="form-group col-xs-2 width20 ">
											<?php echo $trans['PaymtStatus']; ?>
										</div>
									</div>
									<?php } } else { ?>
									<div class="col-xs-12 status-history-content-container">
										<strong>No payment transactions yet.</strong>
									</div>
								<?php } ?>
							</div>
							<?php } ?>
						</ul>
					</div>
					<?php } else { ?>
						<div class="col-xs-12 status-history-content-container">
							<strong>This order is cancelled.</strong>
						</div>
					<?php } ?>
				</div>
			</section>
			<!-- Searched Options content -->
			<section class="section-center1">
				<div class="section-header-2">
					<span class="confirm-title">Order Details <?php if(isset($bikenumber)) { echo 'for Bike: ' . $bikenumber; } ?></span>
				</div>
				<div class="section-content1">
					<div class="congo-text">
						<span><strong><?php if(isset($uname)) { echo convert_to_camel_case($uname); } ?></strong>'s Order Details</span>
					</div>
					<div class="review-options-container">
						<?php if(isset($serid) && $serid != 3) { ?>
							<div class="form-group col-xs-3 ht50 right-dot-margin" style="">
						<?php } else { ?>
							<div class="form-group col-xs-4 right-dot-margin" style=""> 
						<?php } ?>
							<label for="">Order ID</label><br>
							<label class="selected-options"><?php if(isset($OId)) { echo $OId; } ?></label>
						</div>
						<?php if(isset($serid) && $serid != 3) { ?>
						<div class="form-group col-xs-3 ht50 right-dot-margin margin-left-10px" style="">
							<label for="">Service Center</label><br>
							<?php
								if (isset($scenter)) {
									foreach ($scenter as $sc) {
										echo '<label class="selected-options">' . convert_to_camel_case($sc['ScName']) . '</label>';
									}
								}
							?>
						</div>
						<?php } ?>
						<?php if(isset($serid) && $serid != 3) { ?>
							<div class="form-group col-xs-3 ht50 right-dot-margin" style="">
						<?php } else { ?>
							<div class="form-group col-xs-4 right-dot-margin" style=""> 
						<?php } ?>
							<label for="">Service Type</label><br>
							<label class="selected-options"><?php if(isset($stype)) { echo $stype; } ?></label>
						</div>
						<?php if(isset($serid) && $serid != 3) { ?>
							<div class="form-group col-xs-3 ht50" style="">
						<?php } else { ?>
						<div class="form-group col-xs-4" style=""> 
						<?php } ?>
							<label for="">Bike Model</label><br>
							<label class="selected-options"><?php if(isset($bikemodel)) { echo convert_to_camel_case($bikemodel); } ?></label>
						</div>
						<div class="col-xs-6">
							<label for="">Time Slot</label><br>
							<label class="selected-options"><?php if(isset($timeslot)) { echo $timeslot; } ?></label> 
						</div>
						<div class="col-xs-6 pay-mode-final">
							<label for="">Mode of Payment&nbsp;&nbsp; :&nbsp;&nbsp;</label> 
							<label class="selected-options"><?php if(isset($paymode)) { echo convert_to_camel_case($paymode); } ?></label> 
						</div>
					</div>
					<?php if(isset($omedia) && count($omedia) > 0) { ?>
						<div class="row">
							<div class="col s12 selected-options padding-10px margin-top-10px margin-bottom-10px">User Uploaded Images</div>
							<div class="col s12 lgrey-bg padding-20px">
								<?php foreach($omedia as $om) { ?>
								<div class="col s12 m4 center-align"><img class="materialboxed" height="150px" width="150px" src="<?php echo get_awss3_url('uploads/omedia/' . $om['FileType'] . '/' . $om['FileData']); ?>"></div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					<?php if(isset($serid) && ($serid == 1 || $serid == 4)) { ?>
					<br>
					<?php if(isset($scenter[0]['ServiceDesc2']) && $scenter[0]['ServiceDesc2'] != '') { ?>
					<div class="col-xs-12 query-show-block">
						<div class="col-xs-2">
							<strong>Description : </strong>
						</div>
						<div class="col-xs-10">
							<span class="selected-options"><?php echo $scenter[0]['ServiceDesc2']; ?></span>
						</div>
					</div>
					<?php } ?>
					<br>
					<?php } ?>
					<?php if(isset($serid) && $serid == 3) { ?>
					<br>
					<?php if(isset($scenter[0]['ServiceDesc1']) && $scenter[0]['ServiceDesc1'] != '') { ?>
					<div class="col-xs-12 query-show-block">
						<div class="col-xs-2">
							<strong>Query : </strong>
						</div>
						<div class="col-xs-10">
							<span class="selected-options"><?php echo $scenter[0]['ServiceDesc1']; ?></span>
						</div>
					</div>
					<?php } ?>
					<?php if(isset($scenter[0]['ServiceDesc2']) && $scenter[0]['ServiceDesc2'] != '') { ?>
					<div class="col-xs-12 query-show-block">
						<div class="col-xs-2">
							<strong>Description : </strong>
						</div>
						<div class="col-xs-10">
							<span class="selected-options"><?php echo $scenter[0]['ServiceDesc2']; ?></span>
						</div>
					</div>
					<?php } ?>
					<br>
					<?php } ?>
					<?php if(isset($serid) && $serid == 2) { ?>
					<br>
					<?php if(isset($scenter[0]['ServiceDesc1']) && $scenter[0]['ServiceDesc1'] != '') { ?>
					<div class="col-xs-12 query-show-block">
						<div class="col-xs-2">
							<strong>Repair Info : </strong>
						</div>
						<div class="col-xs-10">
							<span class="selected-options"><?php echo $scenter[0]['ServiceDesc1']; ?></span>
						</div>
					</div>
					<?php } ?>
					<?php if(isset($scenter[0]['ServiceDesc2']) && $scenter[0]['ServiceDesc2'] != '') { ?>
					<div class="col-xs-12 query-show-block">
						<div class="col-xs-2">
							<strong>Description : </strong>
						</div>
						<div class="col-xs-10">
							<span class="selected-options"><?php echo $scenter[0]['ServiceDesc2']; ?></span>
						</div>
					</div>
					<?php } ?>
					<br>
					<?php } ?>
					<?php if(isset($serid) && $serid == 4 && isset($insren_details)) { ?>
					<div class="col-xs-12">
						<br>
					</div>
					<div class="review-options-container1">
						<div class="form-group col-xs-3 right-dot-margin margin-left-10px" style="">
							<label for="">Previous Insurer</label><br>
							<label class="selected-options"><?php echo convert_to_camel_case($insren_details['InsurerName']); ?></label>
						</div>
						<div class="form-group col-xs-3 right-dot-margin margin-left-10px" style="">
							<label for="">Previous Policy Expiry</label><br>
							<label class="selected-options"><?php if($insren_details['ExpiryDays'] == 0) { echo 'Already Expired'; } else { echo 'Next ' . $insren_details['ExpiryDays'] . ' Days'; } ?></label>
						</div>
						<div class="form-group col-xs-3 right-dot-margin margin-left-10px" style="">
							<label for="">Registration Year</label><br>
							<label class="selected-options"><?php echo convert_to_camel_case($insren_details['RegYear']); ?></label>
						</div>
						<div class="form-group col-xs-3  margin-left-10px" style="">
							<label for="">Claims Made Previously</label><br>
							<label class="selected-options"><?php if($insren_details['isClaimedBefore'] == 1) { echo 'Yes'; } else { echo 'No'; } ?></label>
						</div>
					</div>
					<br>
					<?php } ?>
					<?php if(isset($chosen_amenities)) { ?>
						<div class="col-xs-12 query-show-block">
							<div class="col-xs-2">
								<strong>Selected Amenities : </strong>
							</div>
							<div class="col-xs-10">
								<span class="selected-options"><?php echo $chosen_amenities; ?></span>
							</div>
						</div>
						<br>
					<?php } ?>
					<div class="addr-container1">
						<div class="user-addr-container">
							<div class="user-addr-title">User Address</div>
							<div class="user-addr-content">
								<?php if(isset($uaddress)) { echo $uaddress; } ?>
							</div>
						</div>
						<div class="sc-addr-container">
							<?php if(isset($serid) && $serid != 3) { ?>
								<div class="sc-addr-title">Service Centre Address</div>
							<?php } else { ?>
							<div class="sc-addr-title">Service Centre(s) Details</div>
							<?php } ?>
							<div class="sc-addr-content">
								<?php if(isset($scaddress)) { echo $scaddress; } ?>
							</div>
						</div>
					</div>
					<br>
				</div>
			</section>
		</div>
	</aside>
	<?php $this->load->view('vendor/components/_foot'); ?>
	<?php } ?>
<?php $this->load->view('vendor/components/_vjs'); ?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCZ126reFV784ZQTqw_JfD08mnS0jI7nWo&libraries=places"></script>
<script type="text/javascript" src="<?php echo site_url('js/account.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/vodetail.js'); ?>"></script>
<script>
	var check_status_for_price = <?php if(isset($scenter)) { echo $scenter[0]['StatusId']; } ?>;
	var univ_order_id = "<?php if(isset($OId)) { echo $OId; } ?>";
	var statserial = <?php if (isset($stathists)) { echo $stathists[count($stathists) - 1]['statserial']; } else { echo 'null'; } ?>;
	<?php if(isset($send_final_message) && $send_final_message == 0) { ?>$('#thank_sms_txt').val('Thank you for placing order with gear6.in :)');<?php } ?>
	var sclatitude = <?php if(isset($SCLatitude)) { echo $SCLatitude; } else { echo 'null'; } ?>;
	var sclongitude = <?php if(isset($SCLongitude)) { echo $SCLongitude; } else { echo 'null'; } ?>;
	var g_serid = <?php if(isset($serid)) { echo $serid; } else { echo 'null'; } ?>;
	<?php if(isset($city_row)) { ?>
		var swlati = <?php echo $city_row->SwLati; ?>;
		var swlongi = <?php echo $city_row->SwLongi; ?>;
		var nelati = <?php echo $city_row->NeLati; ?>;
		var nelongi = <?php echo $city_row->NeLongi; ?>;
	<?php } ?>
</script>
</body>
</html>