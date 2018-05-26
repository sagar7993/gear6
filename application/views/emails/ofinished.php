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
			<p>Your bike servicing w.r.t order Id: G600100123 has been finished and is ready for delivery.</p>
			Home drop off chosen:
			<p>Your bike will be delivered to your address mentioned while placing this order soon.</p>
			<p>Thank you for placing the service request with us. Don’t hesitate to give us any feedback.</p>
		</div>
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