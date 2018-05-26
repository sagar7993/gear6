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
		<div style="" class="congo-text">
			<span><strong>Hi <?php if(isset($fname)) { echo strtok(convert_to_camel_case($fname), ' ') . ', '; } ?></strong></span><br>
			<p>Your bike check-up is done. Below are the extra charges required for the repairs. Please approve it in your <a href="<?php base_url('user'); ?>">gear6.in</a> website/app through your registered account so that the service center can proceed furthur with the repairs/service.</p>
		</div>
		<?php if(isset($oprices) && $oprices !== NULL) { ?>
		<div style="padding: 20px 0px;
	color: #2c858d;
	font-weight: 600;background: #fff;
	" class="price-details-container1">
			<div style="padding: 20px;
	float: left;
	margin-top: -5%;" class="service-title-container1">Price Description</div>
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
					<?php foreach($oprices as $oprice) { if(!isset($oprice['ptotal'])) { ?>
						<div style="clear: both;padding:10px 20px 0px 20px;" class="service-title"><?php echo convert_to_camel_case($oprice['opdesc']); ?></div>
						<div style="clear: both;float: right;margin-top: -20px;margin-right: 20px;" class="price-text">&#8377;&nbsp;<?php echo $oprice['oprice']; ?></div>
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
					&#8377;&nbsp;<?php echo $oprices[count($oprices) - 1]['ptotal']; ?>
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