<html>
<head></head>
<body>
<section class="section-center" style="margin: 0 auto;width: 65%;">
	<div class="section-header" style="background-color: #2c858d;color: white;
	overflow: auto;
	margin-top: 5%;text-align:center;padding:10px;">
	<span><img width="200px" height="35px" src="<?php echo site_url('img/mr_logo.png'); ?>"></span>
	</div>
	<div class="section-content" style="background-color: white;
	padding: 20px;
	overflow: auto;">
		<?php if(isset($status) && $status == 'Success') { ?>
			<div style="" class="congo-text">
				<span><?php if(isset($smsg)) { echo $smsg; } ?>.</span><span>&nbsp;To track your order, login to <strong><a href="https://www.gear6.in" style="text-decoration:none;font-weight:bold;">gear6.in</a></strong></span><br>
				<?php if (isset($pwd)) { echo '<span><b>Enter this Password while logging in for the first time: ' . $pwd . '</b></span><br>'; } ?>
				<span><strong>Below are the details</strong></span>
			</div>
		<?php } elseif(isset($status) && $status == 'Failure') { ?>
			<div style="" class="congo-text">
				<span><?php if(isset($error)) { echo $error; } ?>.</span><span>&nbsp;To track your order, login to <strong><a href="https://www.gear6.in" style="text-decoration:none;font-weight:bold;">gear6.in</a></strong></span><br>
				<?php if (isset($pwd)) { echo '<span><b>Enter this Password while logging in for the first time: ' . $pwd . '</b></span><br>'; } ?>
				<span><strong>Below are the details</strong></span>
			</div>
		<?php } else { ?>
			<div style="" class="congo-text">
				<span>Congratulations <?php if(isset($uname)) { echo '<strong>' . $uname . '</strong>'; } ?>, Your Service Order booking has been Successfully Completed.</span><span>&nbsp;To track your order, login to <strong><a href="https://www.gear6.in" style="text-decoration:none;font-weight:bold;">gear6.in</a></strong></span><br>
				<?php if (isset($pwd)) { echo '<span><b>Enter this Password while logging in for the first time: ' . $pwd . '</b></span><br>'; } ?>
				<span><strong>Below are the details</strong></span>
			</div>
		<?php } ?>
		<div style="margin-top: 3%;" class="review-options-container">
			<?php if(isset($serid) && $serid != 3) { ?>
				<div style="border-right: 2px dotted #7f7d6e;width:45%;float:left;height: 50px;" class="form-group col-xs-3 ht50 right-dot-margin">
			<?php } else { ?>
				<div style="border-right: 2px dotted #7f7d6e;width:30%;float:left" class="form-group col-xs-4 right-dot-margin"> 
			<?php } ?>
				<label for="">Order ID</label><br>
				<label style="color: #2c858d;font-size: 16px;font-weight: 600;" class="selected-options"><?php if(isset($OId)) { echo $OId; } ?></label>
			</div>
			<?php if(isset($serid) && $serid != 3 && $serid != 4) { ?>
			<div style="height: 50px;width:50%;margin-left:10px;float:left" class="form-group col-xs-3 ht50 right-dot-margin margin-left-10px" style="">
				<label for="">Service Center</label><br>
				<?php
					if (isset($scenter)) {
						foreach ($scenter as $sc) {
							echo '<label style="color: #2c858d;font-size: 16px;font-weight: 600;" class="selected-options">' . convert_to_camel_case($sc['ScName']) . '</label>';
						}
					}
				?>
			</div>
			<?php } ?>
			<?php if(isset($serid) && $serid != 3) { ?>
				<div style="border-right: 2px dotted #7f7d6e;width:45%;margin-top:10px;float:left;height: 50px;" class="form-group col-xs-3 ht50 right-dot-margin" style="">
			<?php } else { ?>
				<div style="border-right: 2px dotted #7f7d6e;width:30%;margin-left:10px;float:left;" class="form-group col-xs-4 right-dot-margin" style=""> 
			<?php } ?>
				<label for="">Service Type</label><br>
				<label style="color: #2c858d;font-size: 16px;font-weight: 600;" class="selected-options"><?php if(isset($stype)) { echo $stype; } ?></label>
			</div>
			<?php if(isset($serid) && $serid != 3) { ?>
				<div style="width:50%;height: 50px;margin-top:10px;float:left;margin-left:10px" class="form-group col-xs-3 ht50" style="">
			<?php } else { ?>
			<div style="width:33%;float:left;margin-left:10px" class="form-group col-xs-4" style=""> 
			<?php } ?>
				<label for="">Bike Model</label><br>
				<label style="color: #2c858d;font-size: 16px;font-weight: 600;" class="selected-options"><?php if(isset($bikemodel)) { echo convert_to_camel_case($bikemodel); } ?></label>
			</div>
			<?php if(isset($serid) && $serid != 3) { ?>
			<div style="width:50%" class="col-xs-6">
				<label for="">Time Slot</label><br>
				<label style="color: #2c858d;font-size: 16px;font-weight: 600;" class="selected-options"><?php if(isset($timeslot)) { echo $timeslot; } ?></label> 
			</div>
			<div style="width:50%;margin-top:-5px;float:right;margin-bottom:5px;background-color: #f6f6f5;padding: 5px;padding-left: 25px;padding-top: 7px;" class="col-xs-6 pay-mode-final">
				<label for="">Mode of Payment&nbsp;&nbsp; :&nbsp;&nbsp;</label> 
				<label style="color: #2c858d;font-size: 16px;font-weight: 600;" class="selected-options"><?php if(isset($paymode)) { echo convert_to_camel_case($paymode); } ?></label> 
			</div>
			<?php } ?>
		</div>
		<?php if(isset($serid) && $serid == 3) { ?>
			<br>
		<?php } ?>
		<div style=" width: 100%;margin-top:100px;background-color: #f6f6f5;overflow: auto;padding:10px;display:table" class="addr-container">
			<div style="background-color: #f6f6f5;padding:15px;color: #2c858d;font-weight: 600;display:table-cell;" class="user-addr-container">
				<div style="" class="user-addr-title">Your Details</div>
				<div style="color: #2c858d;font-weight: 300;" class="user-addr-content">
					<?php if($uaddress != "") { echo $uaddress; } else { echo '<div>' . $uname . '</div><div>' . $uemail . '</div><div>' . $phone . '</div>'; } ?>
				</div>
			</div>
			<div style="background-color: #f6f6f5;padding:15px;border-left:5px solid #fff;color: #2c858d;font-weight: 600;display:table-cell;" class="sc-addr-container">
				<?php if(isset($serid) && $serid != 3) { ?>
					<div style="" class="sc-addr-title">Service Centre Details</div>
				<?php } else { ?>
				<div style="" class="sc-addr-title">Service Centre(s) Details</div>
				<?php } ?>
				<div style="color: #2c858d;font-weight: 300;" class="sc-addr-content">
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
		<div style="padding: 20px 0px;
	color: #2c858d;
	font-weight: 600;background: #fff;
	" class="price-details-container1">
			<div style="padding: 20px;
	float: left;
	margin-top: -5%;" class="service-title-container1">Service / Amenity Details</div>
			<div style="padding: 20px;
	float: right;
	margin-top: -5%;padding: 30px 40px 0px 40px !important;
	margin-top: -4%;" class="price-title-container1">Price</div>
			<div style="width: 96%;
	background-color: #f6f6f5;
	border-radius: 0px;
	height: auto;
	margin-left: 2%;
	overflow: auto;
	margin-left: 2%;" class="price-map-container1">
				<div style="clear: both;
	color: #2c858d;
	font-weight: 300;
	overflow: auto;" class="service-list-container">
					<?php foreach($estprices as $estprice) { if(!isset($estprice['ptotal'])) { ?>
						<div style="clear: both;padding:10px 20px 0px 20px;" class="service-title"><?php echo convert_to_camel_case($estprice['apdesc']); ?></div>
						<div style="clear: both;float: right;margin-top: -20px;margin-right: 20px;" class="price-text">&#8377;&nbsp;<?php echo $estprice['aprice']; ?></div>
						<?php if(intval($estprice['atprice']) != 0) { ?>
							<div style="clear: both;padding:10px 20px 0px 20px;" class="service-title"><?php echo $estprice['atdesc']; ?></div>
							<div style="clear: both;float: right;margin-top: -20px;margin-right: 20px;" class="price-text">&#8377;&nbsp;<?php echo $estprice['atprice']; ?></div>
						<?php } ?>
					<?php } } ?>
				</div>
				<div style="float: right;
	margin-right: 20px;
	border-top: 1px solid #2c858d;
	width: 100px;
	text-align: right;
	padding-top: 10px;
	margin-top: 20px;
	color: #2c858d;margin-bottom:10px" class="final-price-container">
					&#8377;&nbsp;<?php echo $estprices[count($estprices) - 1]['ptotal']; ?>
				</div>
			</div>
		</div>
		<?php } else { ?><br><?php } ?>
		<div style="padding: 20px;
	color: #2c858d;
	font-weight: 600;" class="terms-container">
			<div style="" class="terms-title-text"><?php if(isset($site_name)) { echo $site_name; } ?> Terms &amp; Conditions</div><br>
			<div style="color: #2c858d;
	font-weight: 300;text-align:justify" class="terms-detail">
				The page, page code, and any and all copyrights, trademarks, service marks, trade names 
				and all other intellectual property or material or property rights herein are
				proprietary to the Company and are owned by the Company and/or its licensors and 
				content providers, and are protected by applicable domestic and international 
				intellectual property laws. The depiction of such proprietary content does 
				not permit or allow any user or person (whatsoever) to make any use of the 
				same without prior written permission of the Company in any manner or reference or context.
			</div>
			<div style="float: right;" class="terms-regards">
				<div style="color: #2c858d;
	font-weight: 300;" class="thanks-text">Thanks and Regards</div><div style="margin-left: 10px;" class="team-text"><strong>Team <?php if(isset($site_name)) { echo $site_name; } ?></strong></div> 
			</div>
		</div>
	</div>
</section>
</body>
</html>