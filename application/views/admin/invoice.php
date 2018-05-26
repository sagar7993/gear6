<html lang="en" class="no-scroll">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="gear6.in">
	<title>gear6.in - Invoice</title>
</head>
<body style="margin: 0;font-family: arial;">
	<div style="width:100%;height:100%;background:#f6f6f5">
		<div style="background:#028cbc;height:15%;width:100%;overflow:auto;">
			<div style="width:50%;height:100%;float:left;">
				<div style="width:50%;height:100%;">
					<img src="https://www.gear6.in/img/mr_logo.png" style="max-width: 100%;max-height: 85%;">
					<div style="text-align: center;color: #fff;font-size: 10px;">Your Bike is Precious,Your Time is Priceless</div>
				</div>
			</div>
			<div style="width:50%;float:left;text-align:center;font-size: 21pt;vertical-align: middle;color: #fff;margin-top: 30px;">
				INVOICE 
			</div>
		</div>
		<div style="width: 100%;overflow: auto;background:#d7d7d7">
			<div style="background:#d7d7d7;height:35px;text-align:center;font-size:100%;padding-top:12px;width:33.33%;float:left">Feedback</div>
			<div style="background:#d7d7d7;height:35px;text-align:center;font-size:100%;padding-top:12px;width:32%;float:left;border-left: 1px solid #a7a7a7;border-right: 1px solid #a7a7a7;"><a href="mailto:support@gear6.in?Subject=Regarding%20Order:%20<?php echo $OId; ?>">Contact Us</a></div>
			<div style="background:#d7d7d7;height:35px;text-align:center;font-size:100%;padding-top:12px;width:33.33%;float:left">Refer A Friend</div>
		</div>
		<div style="padding: 1% 5%;">
			<p>Hi <?php if(isset($uname)) { echo convert_to_camel_case($uname); } ?>,</p>
			<p>Please find the below details as the invoice for your <?php if(isset($stype)) { echo $stype; } ?> order on <?php if(isset($timeslot)) { echo $timeslot; } ?>.</p>
			<p>Looking forward to offer more services to you!</p>
		</div>
		<div style="width: 100%;overflow: auto;font-weight: bold;">
			<div style="height:35px;text-align:center;font-size:100%;padding-top:12px;width:33.33%;float:left">Billed To</div>
			<div style="height:35px;text-align:center;font-size:100%;padding-top:12px;width:32%;float:left;border-left: 1px dotted #d7d7d7;border-right: 1px dotted #d7d7d7;">Bike Info</div>
			<div style="height:35px;text-align:center;font-size:100%;padding-top:12px;width:33.33%;float:left">Invoice Info</div>
		</div>
		<div style="width: 100%;overflow: auto;">
			<div style="overflow:auto;text-align:left;font-size:100%;padding:1% 0 0 5%;width:28.33%;float:left">
				<div><?php if(isset($uname)) { echo convert_to_camel_case($uname); } ?></div>
				<?php if(isset($uaddress)) { echo $uaddress; } ?>
			</div>
			<div style="overflow:auto;text-align:left;font-size:100%;padding:1% 0 0 1%;width:31%;float:left;">
				<div>Bike Model&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?php if(isset($bikemodel)) { echo $bikemodel; } ?></div>
				<div>Reg. Number&nbsp;:&nbsp;<?php if(isset($bikenumber)) { echo $bikenumber; } ?></div>
			</div>
			<div style="overflow:auto;text-align:left;font-size:100%;padding:1% 0 0 1%;width:32.33%;float:left">
				<div>Order ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?php if(isset($OId)) { echo $OId; } ?></div>
				<div>Invoice No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;G6INV<?php if(isset($OId)) { echo substr($OId, 2); } ?></div>
				<div>Service Tax Code:&nbsp;AAFCN0555MSD001</div>
				<div>Due Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?php if(isset($InvoiceDate)) { echo $InvoiceDate; } ?></div>
				<div>Payment Mode:&nbsp;<?php if(isset($paymode)) { echo $paymode; } ?></div>
				<!-- <div>Coupon&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;G6FIRST</div> -->
			</div>
		</div>
		<div style="width:100%;background:#d7d7d7;overflow:auto">
			<div style="width:90%;margin:0 auto;">
				<div style="height:35px;background:#d7d7d7;float:left;padding-top:15px;width:10%">S.No</div>
				<div style="height:35px;background:#d7d7d7;float:left;padding-top:15px;width:50%">Description</div>
				<div style="height:35px;background:#d7d7d7;float:left;padding-top:15px;width:10%;text-align:right;">Basic</div>
				<div style="height:35px;background:#d7d7d7;float:left;padding-top:15px;width:10%;text-align:right;">Tax</div>
				<div style="height:35px;background:#d7d7d7;float:left;padding-top:15px;width:20%;text-align:right;">Net</div>
			</div>	
		</div>
		<div style="width:100%;overflow:auto">
			<div style="width:90%;margin:0 auto;">
				<?php $count = 1; if(isset($estprices) && count($estprices) > 0) { foreach($estprices as $estprice) { ?>
				<?php if(!isset($estprice['ptotal'])) { ?>
				<div style="float:left;clear:left;padding-top:15px;width:10%">
					<div><?php echo $count; ?></div>
				</div>
				<div style="float:left;padding-top:15px;width:50%">
					<div><?php echo convert_to_camel_case($estprice['apdesc']); ?></div>
				</div>
				<div style="float:left;padding-top:15px;width:10%;text-align:right;">
					<div><?php echo $estprice['aprice']; ?></div>
				</div>
				<div style="float:left;padding-top:15px;width:10%;text-align:right;">
					<div><?php echo $estprice['atprice']; ?></div>
				</div>
				<div style="float:left;padding-top:15px;width:20%;text-align:right;">
					<div><?php echo (floatval($estprice['aprice']) + floatval($estprice['atprice'])); ?></div>
				</div>
				<?php $count += 1; } ?>
				<?php } } ?>
				<?php if(isset($oprices) && count($oprices) > 0) { foreach($oprices as $oprice) { ?>
				<?php if(!isset($oprice['ptotal'])) { ?>
				<div style="float:left;clear:left;padding-top:15px;width:10%">
					<div><?php echo $count; ?></div>
				</div>
				<div style="float:left;padding-top:15px;width:50%">
					<div><?php echo convert_to_camel_case($oprice['opdesc']); ?></div>
				</div>
				<div style="float:left;padding-top:15px;width:10%;text-align:right;">
					<div><?php echo $oprice['oprice']; ?></div>
				</div>
				<div style="float:left;padding-top:15px;width:10%;text-align:right;">
					<div>0</div>
				</div>
				<div style="float:left;padding-top:15px;width:20%;text-align:right;">
					<div><?php echo $oprice['oprice']; ?></div>
				</div>
				<?php $count += 1; } ?>
				<?php } } ?>
				<div style="height:1px;background:#d7d7d7;margin-top:5px;float:left;width:100%;"></div>
			</div>	
		</div>
		<div style="width:90%;margin:0 auto;">
			<div style="width:100%;text-align:right;margin-top:5px;">
				Total Amount : <?php echo floatval($estprices[count($estprices) - 1]['ptotal']) + floatval($oprices[count($oprices) - 1]['ptotal']); ?>
			</div>
			<div style="width:100%;text-align:right;margin-top:5px;">
				Discount : <?php echo floatval($discprices[count($discprices) - 1]['ptotal']); ?>
			</div>
			<div style="width:100%;text-align:right;margin-top:5px;">
				Amount Payable : <?php echo $tot_billed; ?>
			</div>
			<div style="width:100%;text-align:right;margin-top:5px;">
				Amount Paid : <?php echo $tot_billed; ?>
			</div>
		</div>
	</div>
</body>
</html>