<!DOCTYPE html PUBLIC>
<html>
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name="viewport" content="width=device-width" />
	<link href="https://www.google.com/fonts#UsePlace:use/Collection:Lato" rel="fonts" />
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" class="mui-body" style="max-width:100%;margin:0px auto;">
	<tbody>
		<tr>
			<td>
			<center>
			<div id="image-container" style="width:100%"><span class="sg-image" data-imagelibrary="%7B%22width%22%3A%22736%22%2C%22height%22%3A%22146%22%2C%22alignment%22%3A%22center%22%2C%22src%22%3A%22https%3A//www.gear6.in/img/emails/e1_header_mini.jpg%22%2C%22alt_text%22%3A%22gear6.in%22%2C%22link%22%3A%22%22%2C%22classes%22%3A%7B%22sg-image%22%3A1%7D%7D" style="float: none; display: block; text-align: center;"><img alt="gear6.in" height="146" src="https://www.gear6.in/img/emails/e1_header_mini.jpg" style="width: 736px; height: 146px;" width="736" /></span></div>

			<div id="intro-content" style="width:auto;font-size: 14pt;font-style: italic;">
			<div>&nbsp;</div>

			<div><span style="font-size:36px;"><strong><span style="color:#028CBC;"><span style="font-family:lucida sans unicode,lucida grande,sans-serif;font-style: normal;">Proforma Invoice</span></span></strong></span></div>

			<div>&nbsp;</div>

			<div>
			<table align="center" border="0" cellpadding="5" cellspacing="2" style="width:500px;">
				<tbody>
					<tr>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><em><strong>Billed To</strong></em></span></td>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><?php if(isset($odetails)) { echo convert_to_camel_case($odetails->UserName); } ?></span></td>
					</tr>
					<tr>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><em><strong>Order Id</strong></em></span></td>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><?php if(isset($oid)) { echo $oid; } ?></span></td>
					</tr>
					<tr>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><em><strong>Proforma Invoice Number</strong></em></span></td>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;">PG6INV<?php if(isset($oid)) { echo substr($oid, 2); } ?></span></td>
					</tr>
					<tr>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><strong><em>Service Tax Code</em></strong></span></td>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;">AAFCN0555MSD001</span></td>
					</tr>
					<tr>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><em><strong>Due Date</strong></em></span></td>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><?php echo date('d F, Y', strtotime("now")); ?></span></td>
					</tr>
				</tbody>
			</table>
			</div>
			</div>

			<div id="image-container" style="width:100%">
			<div style="width:90%;margin:0 auto;">
			<div style="text-align: center;color: #028cbc;font-size: 30px;">
			<div>&nbsp;</div>
			</div>

			<div style="text-align: center;color: #028cbc;font-size: 30px;"><span style="font-family:lucida sans unicode,lucida grande,sans-serif;">Pricing Details</span></div>

			<div>&nbsp;</div>

			<div style="text-align: center;color: #028cbc;font-size: 30px;">
			<table align="center" border="0" cellpadding="5" cellspacing="1" style="width:500px;">
				<tbody>
					<tr>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><span style="text-align: center;">Total Service / Repair / Insurance Charges</span></span></td>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><?php if(isset($tot_billed)) { echo $tot_billed; } else { echo 0; } ?> INR</span></td>
					</tr>
					<tr>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><span style="text-align: center;">gear6.in Convenience Fee</span></span></td>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><?php if(isset($tot_conv)) { echo $tot_conv; } else { echo 0; } ?> INR</span></td>
					</tr>
					<tr>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><strong style="text-align: center;">Sub Total</strong></span></td>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><strong><?php if(isset($tot_billed) || isset($tot_conv)) { echo ($tot_billed + $tot_conv); } else { echo 0; } ?> INR</strong></span></td>
					</tr>
					<tr>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><strong><span style="text-align: center;">Discount</span></strong></span></td>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><strong><?php if(isset($disc_amount)) { echo $disc_amount; } else { echo 0; } ?> INR</strong></span></td>
					</tr>
					<tr>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><strong><span style="text-align: center;">Amount Payable</span></strong></span></td>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><strong><?php if(isset($tot_billed) || isset($tot_conv) || isset($disc_amount)) { echo $tot_billed + $tot_conv - $disc_amount; } else { echo 0; } ?> INR</strong></span></td>
					</tr>
					<tr>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><strong><span style="text-align: center;">Amount Paid</span></strong></span></td>
						<td><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><strong><?php if(isset($tot_paid)) { echo $tot_paid; } else { echo 0; } ?> INR</strong></span></td>
					</tr>
				</tbody>
			</table>
			</div>

			<div style="width:100%;text-align:center;margin-top:5px;">&nbsp;</div>

			<hr />
			<address style="width: 100%; text-align: center; margin-top: 5px;"><span style="color:#028CBC;"><span style="font-size:20px;"><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><em><strong>Amount to be Paid: <?php if(isset($to_be_paid)) { echo $to_be_paid; } else { echo 0; } ?> INR</strong></em></span></span></span></address>

			<hr />
			<div style="width:100%;text-align:center;margin-top:5px;">&nbsp;</div>
			</div>
			</div>

			<div style="text-align: center;"><strong>&nbsp;<span style="color:#FF0000;">***</span></strong><span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><span style="color:#FF0000;">Note:</span> Invoice with detailed split prices and taxes will be mailed to you within 48 hours of the payment.</span></div>

			<div>&nbsp;</div>

			<a href="javascript:;" style="color:#fff;text-decoration:none;" id="rzpaychkout"><div style="background: #028cbc;padding: 10px;color: #fff;font-style: normal;font-weight: bold;font-family: sans-serif;max-width:250px; margin-top:10px;">Pay Now</div></a>

			<?php if(isset($to_be_paid) && $to_be_paid >= 3000) { echo '<p style="color:black;">Note : Additional 2% will be charged since your bill amount is above Rs. 3000</p>'; } ?>

			<div>&nbsp;</div>
			</center>
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td>
			<table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;height:70px;text-align:center;border-bottom:1px solid rgba(212,212,212,0.28);margin-bottom:30px" width="581">
				<tbody>
					<tr>
						<td width="30">&nbsp;</td>
						<td width="30"><span class="sg-image" data-imagelibrary="%7B%22width%22%3A%2230%22%2C%22height%22%3A%2230%22%2C%22alignment%22%3A%22center%22%2C%22border%22%3A0%2C%22src%22%3A%22https%3A//ci3.googleusercontent.com/proxy/qXgDet5aHbHOeV5_edJKSBvdXdGcGbCUgFZQj-5tKB8sPS62fGRtUcGZDxVi6wPHk0y5zq9aZKo%3Ds0-d-e1-ft%23http%3A//i61.tinypic.com/33utq3l.png%22%2C%22link%22%3A%22https%3A//www.facebook.com/gear6.in%22%2C%22classes%22%3A%7B%22sg-image%22%3A1%7D%7D" style="float: none; display: block; text-align: center;"><a href="https://www.facebook.com/gear6.in" target="_blank"><img border="0" class="CToWUd" height="30" src="https://ci3.googleusercontent.com/proxy/qXgDet5aHbHOeV5_edJKSBvdXdGcGbCUgFZQj-5tKB8sPS62fGRtUcGZDxVi6wPHk0y5zq9aZKo=s0-d-e1-ft#http://i61.tinypic.com/33utq3l.png" style="text-decoration: none; display: block; outline: none; width: 30px; height: 30px;" width="30" /></a></span></td>
						<td style="padding-left:6px;padding-right:20px;font-family:helvetica neue,helvetica,sans-serif;font-weight:lighter;font-size:14px;letter-spacing:2px;text-align:left"><a href="https://www.facebook.com/gear6.in" style="text-decoration:none;color:#ffffff;color:#666666;font-size:16px;padding-bottom:17px;font-family:'Myriad Pro','Arial',sans-serif;font-weight:300" target="_blank">Facebook </a></td>
						<td width="30"><span class="sg-image" data-imagelibrary="%7B%22width%22%3A%2230%22%2C%22height%22%3A%2230%22%2C%22alignment%22%3A%22center%22%2C%22border%22%3A0%2C%22src%22%3A%22https%3A//ci3.googleusercontent.com/proxy/CZNKQUMICsDlecUz112WVgA9UsZMNnbO_KFz8VYYlnZL-BtDCxDA4GrVwJ_-SMAey9PxFvpC5Q%3Ds0-d-e1-ft%23http%3A//i61.tinypic.com/ngc1s8.png%22%2C%22link%22%3A%22https%3A//twitter.com/gear6_in%22%2C%22classes%22%3A%7B%22sg-image%22%3A1%7D%7D" style="float: none; display: block; text-align: center;"><a href="https://twitter.com/gear6_in" style="text-decoration:none" target="_blank"><img border="0" class="CToWUd" height="30" src="https://ci3.googleusercontent.com/proxy/CZNKQUMICsDlecUz112WVgA9UsZMNnbO_KFz8VYYlnZL-BtDCxDA4GrVwJ_-SMAey9PxFvpC5Q=s0-d-e1-ft#http://i61.tinypic.com/ngc1s8.png" style="text-decoration: none; display: block; outline: none; width: 30px; height: 30px;" width="30" /></a></span></td>
						<td style="padding-left:6px;padding-right:20px;font-family:helvetica neue,helvetica,sans-serif;font-weight:lighter;font-size:14px;letter-spacing:2px;text-align:left"><a href="https://twitter.com/gear6_in" style="text-decoration:none;color:#ffffff;color:#666666;font-size:16px;padding-bottom:17px;font-family:'Myriad Pro','Arial',sans-serif;font-weight:300" target="_blank">Twitter </a></td>
						<td width="30"><span class="sg-image" data-imagelibrary="%7B%22width%22%3A%2230%22%2C%22height%22%3A%2230%22%2C%22alignment%22%3A%22center%22%2C%22border%22%3A0%2C%22src%22%3A%22https%3A//ci6.googleusercontent.com/proxy/Wci6Ui4-TqKfDRlmmNSZqsSn5k12MUPrETjUwnFNonqQMKQxC-epIKRGkhUpNiknXD3TcAf95A%3Ds0-d-e1-ft%23http%3A//i62.tinypic.com/rjjzg4.png%22%2C%22link%22%3A%22https%3A//plus.google.com/+Gear6In/%22%2C%22classes%22%3A%7B%22sg-image%22%3A1%7D%7D" style="float: none; display: block; text-align: center;"><a href="https://plus.google.com/+Gear6In/" style="text-decoration:none" target="_blank"><img border="0" class="CToWUd" height="30" src="https://ci6.googleusercontent.com/proxy/Wci6Ui4-TqKfDRlmmNSZqsSn5k12MUPrETjUwnFNonqQMKQxC-epIKRGkhUpNiknXD3TcAf95A=s0-d-e1-ft#http://i62.tinypic.com/rjjzg4.png" style="text-decoration: none; display: block; outline: none; width: 30px; height: 30px;" width="30" /></a></span></td>
						<td style="padding-left:6px;padding-right:20px;font-family:helvetica neue,helvetica,sans-serif;font-weight:lighter;font-size:14px;letter-spacing:2px;text-align:left"><a href="https://plus.google.com/+Gear6In/" style="text-decoration:none;color:#ffffff;color:#666666;font-size:16px;padding-bottom:17px;font-family:'Myriad Pro','Arial',sans-serif;font-weight:300" target="_blank">Google+ </a></td>
					</tr>
				</tbody>
			</table>

			<table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;height:30px" width="581">
				<tbody>
					<tr>
						<td style="text-align:center;font-size:18px;color:#666666;font-size:16px;padding-bottom:17px;font-family:'Myriad Pro','Arial',sans-serif;font-weight:300"><span class="il">gear6.in</span> &copy; NewEin Technologies Private Limited - 2016</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
$(function() {
	$("#rzpaychkout").on('click', function() {
		<?php if(isset($to_be_paid) && $to_be_paid >= 3000) { $to_be_paid += $to_be_paid * 0.02; $to_be_paid = round($to_be_paid, 2); } ?>
		var rp_options = {
			"key": "rzp_live_1SU5AHKZFVCfcO",
			"amount": <?php if(isset($to_be_paid)) { echo $to_be_paid * 100; } else { echo 0; } ?>,
			"name": "NewEin Technologies Private Limited",
			"description": "<?php if(isset($oid)) { echo $oid; } ?>",
			"image": "https://www.gear6.in/img/social_logo.png",
			"handler": function (response) {
				var created_form = $('<form method="POST" action="/user/result/showRZPayStatus"><input type="hidden" name="paymt_txn_id" value="' + response.razorpay_payment_id + '"><input type="hidden" name="oid" value="<?php if(isset($oid)) { echo $oid; } ?>"><input type="hidden" name="amount" value="' + <?php if(isset($to_be_paid)) { echo $to_be_paid; } else { echo 0; } ?> * 100 + '"><input type="hidden" name="paymt_link_expire" value="1"></form>').appendTo('body');
				created_form.submit();
			},
			"prefill": {
				"name": "<?php if(isset($odetails)) { echo $odetails->UserName; } ?>",
				"email": "<?php if(isset($odetails)) { echo $odetails->Email; } ?>",
				"contact": "<?php if(isset($odetails)) { echo $odetails->Phone; } ?>"
			},
			"notes": {
				"oid": "<?php if(isset($oid)) { echo $oid; } ?>"
			},
			"theme": {
				"color": "#028CBC",
				"close_button": true
			},
			"modal": {
				"ondismiss": function() {
					throw new Error('This is not an error. This is just to abort javascript');
				}
			}
		};
		var rzp1 = new Razorpay(rp_options);
		rzp1.open();
	});
	$("#rzpaychkout").trigger("click");
});
</script>
</body>
</html>
