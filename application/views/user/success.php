<html lang="en" style="min-height:0px !important;">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title><?php if(isset($site_name)) { echo $site_name; } ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/module.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/custom-jquery.css'); ?>">
	<?php $this->load->view('user/components/_ucss'); ?>
</head>
<body style="background-color:#f6f6f5;min-height:0px !important;">
	<?php $this->load->view('user/components/__head'); ?>
	<section class="section-center">
		<div class="section-header">
			<span class="confirm-title">Order Details</span>
		</div>
		<div class="section-content">
			<?php if(isset($status) && $status == 'Success') { ?>
				<div class="congo-text">
					<span><?php echo $smsg; ?></span><br>
					<span><strong>Below are the details</strong></span>
				</div>
			<?php } elseif(isset($status) && $status == 'Failure') { ?>
				<div class="congo-text">
					<span><?php echo $error; ?></span><br>
					<span><strong>Below are the details</strong></span>
				</div>
			<?php } else { ?>
				<div class="congo-text">
					<span>Congratulations <?php if(isset($uname)) { echo '<strong>' . $uname . '</strong>'; } ?>, Your Service Order booking has been Successfully Completed</span><br>
					<span><strong>Below are the details</strong></span>
				</div>
			<?php } ?>
			<div class="review-options-container">
				<?php if(isset($serid) && $serid != 3) { ?>
					<div class="form-group col-xs-3 ht50 right-dot-margin" style="">
				<?php } else { ?>
					<div class="form-group col-xs-4 right-dot-margin" style=""> 
				<?php } ?>
					<label for="">Order ID</label><br>
					<label class="selected-options"><?php if(isset($OId)) { echo $OId; } ?></label>
				</div>
				<?php if(isset($serid) && $serid != 3 && $serid != 4) { ?>
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
				<?php if(isset($serid) && $serid != 3) { ?>
				<div class="col-xs-6">
					<label for="">Time Slot</label><br>
					<label class="selected-options"><?php if(isset($timeslot)) { echo $timeslot; } ?></label> 
				</div>
				<div class="col-xs-6 pay-mode-final">
					<label for="">Mode of Payment&nbsp;&nbsp; :&nbsp;&nbsp;</label> 
					<label class="selected-options"><?php if(isset($paymode)) { echo convert_to_camel_case($paymode); } ?></label> 
				</div>
				<?php } ?>
			</div>
			<?php if(isset($serid) && $serid == 3) { ?>
				<br>
			<?php } ?>
			<div class="addr-container">
				<div class="user-addr-container">
					<div class="user-addr-title">Your Details</div>
					<div class="user-addr-content">
						<?php if($uaddress != "") { echo $uaddress; } else { echo '<div>' . $uname . '</div><div>' . $uemail . '</div><div>' . $phone . '</div>'; } ?>
					</div>
				</div>
				<div class="sc-addr-container">
					<?php if(isset($serid) && $serid != 3) { ?>
						<div class="sc-addr-title">Service Centre Details</div>
					<?php } else { ?>
					<div class="sc-addr-title">Service Centre(s) Details</div>
					<?php } ?>
					<div class="sc-addr-content">
						<?php
							if(isset($serid) && $serid != 3 && $serid != 4) {
								if(isset($scaddress)) { echo $scaddress; }
							} elseif(isset($serid) && $serid == 4) {
								echo '<div>gear6.in - Phone: +91-9494845111</div>';
							} else {
								if (isset($scenter)) {
									foreach ($scenter as $sc) {
										echo '<div>' . convert_to_camel_case($sc['ScName']) . ' - Phone: ' . $sc['Phone'] . '</div>';
									}
									echo '<div><strong style="font-size:10px">
											Finalize one of them from your profile with respect to their responses and your convenience.</strong>
										</div>';
								}
							}
						?>
					</div>
				</div>
			</div>
			<br>
			<?php if(isset($estprices) && $estprices !== NULL) { ?>
			<div class="price-details-container1">
				<div class="service-title-container1">Service / Amenity Details</div>
				<div class="price-title-container1">Price</div>
				<div class="price-map-container1">
					<div class="service-list-container">
						<?php foreach($estprices as $estprice) { if(isset($estprice['apdesc']) && isset($estprice['aprice'])) { ?>
							<div class="service-title"><?php echo convert_to_camel_case($estprice['apdesc']); ?></div>
							<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $estprice['aprice']; ?></div>
							<?php if(intval($estprice['atprice']) != 0) { ?>
								<div class="service-title"><?php echo $estprice['atdesc']; ?></div>
								<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $estprice['atprice']; ?></div>
							<?php } ?>
						<?php } } ?>
					</div>
					<div class="final-price-container">
						<i class="fa fa-inr"></i>&nbsp;<?php echo $estprices[count($estprices) - 1]['ptotal']; ?>
					</div>
				</div>
			</div>
			<?php } else { ?><br><?php } ?>
			<div class="terms-container">
				<div class="terms-title-text"><?php if(isset($site_name)) { echo $site_name; } ?> Terms &amp; Conditions</div><br>
				<div class="terms-detail">
					The page, page code, and any and all copyrights, trademarks, service marks, trade names 
					and all other intellectual property or material or property rights herein are
					proprietary to the Company and are owned by the Company and/or its licensors and 
					content providers, and are protected by applicable domestic and international 
					intellectual property laws. The depiction of such proprietary content does 
					not permit or allow any user or person (whatsoever) to make any use of the 
					same without prior written permission of the Company in any manner or reference or context.
				</div>
				<div class="terms-regards">
					<div class="thanks-text">Thanks and Regards</div><div class="team-text"><strong>Team <?php if(isset($site_name)) { echo $site_name; } ?></strong></div> 
				</div>
			</div>
		</div>
	</section>
	<br/>
	<?php $this->load->view('user/components/_foot'); ?>
	<?php $this->load->view('user/components/_ujs'); ?>
	<script>
	$(function() {
		<?php if(!$this->input->cookie('CityId')) { echo "openCityModal();"; } ?>
		<?php if(isset($is_first_login) && $is_first_login == 1) { echo "openFirstTimeLoginModal();"; } ?>
		<?php
			if(isset($open_blklogin_modal) && $open_blklogin_modal == 1) {
				echo "openBlkLoginModal();";
			} elseif(isset($open_login_modal) && $open_login_modal == 1) {
				echo "openLoginModal();";
			}
		?>
	});
	</script>
</body>
</html>