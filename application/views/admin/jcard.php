<html lang="en" class="no-scroll">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="gear6.in">
	<title>gear6.in - JobCard</title>
</head>
<body  style="margin: 0;font-family: arial;">
	<div style="background:#f6f6f5">
		<div style="background:#028cbc;height:90px;width:100%;border-bottom:1px solid #d7d7d7;">
			<div style="width:50%;height:100%;float:left;">
				<div style="width:50%;height:100%;">
					<img src="https://www.gear6.in/img/gear_logo.JPG" style="max-width: 100%;max-height: 80%;">
					<div style="text-align: center;color: #fff;font-size: 10px;">Your Bike is Precious,Your Time is Priceless</div>
				</div>
			</div>
			<div style="width:50%;float:left;text-align:center;font-size: 21pt;vertical-align: middle;color: #fff;margin-top: 30px;">
				<div>SERVICE JOB CARD</div>
				<div style="font-size:12pt"><?php echo $timeslot; ?></div>
			</div>
		</div>
		<div style="width:100%;margin:0 auto;padding:20px">
			<div style="width:33.33%;float:left;text-align:left">User Name : <b><?php if(isset($uname)) { echo convert_to_camel_case($uname); } ?></b></div>
			<div style="width:33.33%;float:left;text-align:left">Contact Number : <b><?php if(isset($uphone)) { echo $uphone; } ?></b></div>
			<div style="width:33.33%;float:left;text-align:left">Email : <b><?php if(isset($uemail)) { echo $uemail; } ?></b></div>
		</div>
		<div style="width:100%;margin:0 auto;padding:20px">
			<div style="width:33.33%;float:left;text-align:left">Order ID : <b><?php if(isset($OId)) { echo $OId; } ?></b></div>
			<div style="width:33.33%;float:left;text-align:left">Bike Model : <b><?php if(isset($bikemodel)) { echo $bikemodel; } ?></b></div>
			<div style="width:33.33%;float:left;text-align:left">Reg No. : <b><?php if(isset($bikenumber)) { echo $bikenumber; } ?></b></div>
			<div style="width:100%;padding:20px 0;float:left;text-align:left">Address : <b><?php if(isset($csaddress)) { echo $csaddress; } ?></b></div>
		</div>
		<div style="width:100%;margin:0 auto;padding:20px">
			<div style="width:33.33%;float:left;text-align:left">Type : <b><?php if(isset($stype)) { echo $stype; } ?></b></div>
			<div style="width:33.33%;float:left;text-align:left">Color : <b>_________________</b></div>
			<div style="width:33.33%;float:left;text-align:left">Kms: <b>___________________</b></div>
		</div>
		<div style="width:100%;margin:0 auto;padding:20px">
			<div style="width:100%;padding-top:20px;float:left;text-align:left">Standard Jobs : <b><?php if(isset($chosen_aservices)) { echo $chosen_aservices; } else { echo 'NIL'; } ?></b>
			</div>
			<div style="width:100%;padding-top:20px;float:left;text-align:left">Other Jobs : <b><?php if(isset($chosen_amenities)) { echo $chosen_amenities; } else { echo 'NIL'; } ?></b>
			</div>
			<div style="width:100%;padding-top:20px;float:left;text-align:left">User Comments : <?php if(isset($scenter[0]['ServiceDesc1'])) { echo '<b>' . $scenter[0]['ServiceDesc1'] . '</b>'; } elseif(isset($scenter[0]['ServiceDesc2'])) { echo '<b>' . $scenter[0]['ServiceDesc2'] . '</b>'; } else { echo '<b>NIL</b>'; } ?>
			</div>
			<div style="width:100%;padding-top:20px;float:left;text-align:left">Other Comments :
			<div>1.<hr></div>
			<div>2.<hr></div>
			<div>3.<hr></div>
			</div>
			<div style="width:100%;padding-top:20px;float:left;text-align:left">Accessories &amp; Documents:
			<div><hr></div>
			</div>
			<div style="width:100%;padding-top:20px;float:left;text-align:left">Others (if any):
			<div><hr></div>
			</div>
		</div>
		<div style="width:100%;margin:0 auto;padding:20px;overflow:auto">
			<div style="background: #d7d7d7;height: 30px;border: 1px solid #a7a7a7;border-radius: 50%;width: 40px;text-align: center;padding-top: 10px;float:left;margin-left:20px;">HL</div>
			<div style="background: #d7d7d7;height: 30px;border: 1px solid #a7a7a7;border-radius: 50%;width: 40px;text-align: center;padding-top: 10px;float:left;margin-left:20px;">LM</div>
			<div style="background: #d7d7d7;height: 30px;border: 1px solid #a7a7a7;border-radius: 50%;width: 40px;text-align: center;padding-top: 10px;float:left;margin-left:20px;">RM</div>
			<div style="background: #d7d7d7;height: 30px;border: 1px solid #a7a7a7;border-radius: 50%;width: 40px;text-align: center;padding-top: 10px;float:left;margin-left:20px;">&#9836;</div>
			<div style="background: #d7d7d7;height: 30px;border: 1px solid #a7a7a7;border-radius: 50%;width: 40px;text-align: center;padding-top: 10px;float:left;margin-left:20px;">FLI</div>
			<div style="background: #d7d7d7;height: 30px;border: 1px solid #a7a7a7;border-radius: 50%;width: 40px;text-align: center;padding-top: 10px;float:left;margin-left:20px;">FRI</div>
			<div style="background: #d7d7d7;height: 30px;border: 1px solid #a7a7a7;border-radius: 50%;width: 40px;text-align: center;padding-top: 10px;float:left;margin-left:20px;">RLI</div>
			<div style="background: #d7d7d7;height: 30px;border: 1px solid #a7a7a7;border-radius: 50%;width: 40px;text-align: center;padding-top: 10px;float:left;margin-left:20px;">RRI</div>
			<div style="background: #d7d7d7;height: 30px;border: 1px solid #a7a7a7;border-radius: 50%;width: 40px;text-align: center;padding-top: 10px;float:left;margin-left:20px;">TL</div>
			<div style="background: #d7d7d7;height: 30px;border: 1px solid #a7a7a7;border-radius: 50%;width: 40px;text-align: center;padding-top: 10px;float:left;margin-left:20px;">I&#9732;</div>
			<div style="width:100px;height:100px;margin-top:20px;clear:both;float:left;">
					<img src="https://www.gear6.in/img/scooter_right.jpg" style="max-width: 100%;max-height: 85%;">
			</div>
			<div style="width:100px;height:100px;margin-top:20px;float:left;margin-left:20px;">
					<img src="https://www.gear6.in/img/scooter_left.jpg" style="max-width: 100%;max-height: 85%;">
			</div>
			<div style="width:100px;height:100px;margin-top:20px;float:left;margin-left:20px;">
					<img src="https://www.gear6.in/img/bike_left.jpg" style="max-width: 100%;max-height: 85%;">
			</div>
			<div style="width:100px;height:100px;margin-top:20px;float:left;margin-left:20px;">
					<img src="https://www.gear6.in/img/bike_right.jpg" style="max-width: 100%;max-height: 85%;">
			</div>
			<div style="width:100px;height:100px;margin-top:20px;float:left;margin-left:20px;">
					<img src="https://www.gear6.in/img/fuel_meter.jpg" style="max-width: 100%;max-height: 85%;">
			</div>
			<div style="width:100px;height:100px;margin-top:20px;float:left;margin-left:20px;">
					<img src="https://www.gear6.in/img/battery_meter.png" style="max-width: 100%;max-height: 85%;">
			</div>

			<div style="width:100%;padding-top:20px;clear:both;text-align:left">Disclaimer :</div>
			<ul>
				<li>You should be in better contact reach with gear6.in for on time delivery.</li>
				<li>Any extra repair or spare requirements will cost you extra money and time.</li>
				<li>gear6.in is not responsible for any loss/damage during entire service cycle.</li>
				<li>gear6.in is not responsible for any valuables/documents kept in bike.</li>
				<li>All disputes subject to jurisdiction of Bengaluru Jurisdiction. </li>
			</ul>

			<div style="width:50%;padding-top:20px;clear:both;text-align:left;float:left;">Executive Signature</div>
			<div style="width:50%;padding-top:20px;text-align:right;float:left">Customer Signature&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
		</div>


		<div style="background:#028cbc;height:100px;width:100%;border-bottom:1px solid #d7d7d7;">
			<div style="width:50%;height:100%;float:left;">
				<div style="width:50%;height:100%;">
					<img src="https://www.gear6.in/img/gear_logo.JPG" style="max-width: 100%;max-height: 85%;">
					<div style="text-align: center;color: #fff;font-size: 10px;">Your Bike is Precious,Your Time is Priceless</div>
				</div>
			</div>
			<div style="width:50%;float:left;text-align:center;font-size: 21pt;vertical-align: middle;color: #fff;margin-top: 30px;">
				<div>SERVICE JOB CARD</div>
				<div style="font-size:12pt"><?php echo $timeslot; ?></div>
			</div>
		</div>
		<div style="width:100%;margin:0 auto;padding:20px">
			<div style="width:33.33%;float:left;text-align:left">User Name : <b><?php if(isset($uname)) { echo convert_to_camel_case($uname); } ?></b></div>
			<div style="width:33.33%;float:left;text-align:left">Contact Number : <b><?php if(isset($uphone)) { echo $uphone; } ?></b></div>
			<div style="width:33.33%;float:left;text-align:left">Email : <b><?php if(isset($uemail)) { echo $uemail; } ?></b></div>
		</div>
		<div style="width:100%;margin:0 auto;padding:20px">
			<div style="width:33.33%;float:left;text-align:left">Order ID : <b><?php if(isset($OId)) { echo $OId; } ?></b></div>
			<div style="width:33.33%;float:left;text-align:left">Bike Model : <b><?php if(isset($bikemodel)) { echo $bikemodel; } ?></b></div>
			<div style="width:33.33%;float:left;text-align:left">Reg No. : <b><?php if(isset($bikenumber)) { echo $bikenumber; } ?></b></div>
			<div style="width:100%;padding:20px 0;float:left;text-align:left">Address : <b><?php if(isset($csaddress)) { echo $csaddress; } ?></b></div>
		</div>
		<div style="width:100%;margin:0 auto;padding:20px">
			<div style="width:33.33%;float:left;text-align:left">Executive Name : <span style="margin-top:10px;">______________________</span></div>
			<div style="width:33.33%;float:left;text-align:left">Executive Contact : <span style="margin-top:10px;">___________________</span></div>
			<div style="width:33.33%;float:left;text-align:left">Amount Paid : <span style="margin-top:10px;"> <?php if(isset($tot_paid)) { echo $tot_paid; } ?> INR</span></div>
			<div style="width:33.33%;float:left;text-align:left;margin-top:20px;">Pick Up Time : <span style="margin-top:10px;">______________________</span></div>
			<div style="width:33.33%;float:left;text-align:left;margin-top:20px;">Expected Delivery : <span style="margin-top:10px;">___________________</span></div>
			<div style="width:33.33%;float:left;text-align:left;margin-top:20px;">Other Comments : <span style="margin-top:10px;">_______________________</span></div>

			<div style="width:50%;padding-top:40px;clear:both;text-align:left;float:left;">Executive Signature</div>
			<div style="width:50%;padding-top:40px;text-align:right;float:left">Customer Signature&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
		</div>

		<div style="margin: 20px auto;height: 1px;border: 1px dotted #d7d7d7;width: 100%;clear: both;">
		</div>

		<div style="background:#028cbc;height:100px;width:100%;border-bottom:1px solid #d7d7d7;">
			<div style="width:50%;height:100%;float:left;">
				<div style="width:50%;height:100%;">
					<img src="https://www.gear6.in/img/gear_logo.JPG" style="max-width: 100%;max-height: 85%;">
					<div style="text-align: center;color: #fff;font-size: 10px;">Your Bike is Precious,Your Time is Priceless</div>
				</div>
			</div>
			<div style="width:50%;float:left;text-align:center;font-size: 21pt;vertical-align: middle;color: #fff;margin-top: 30px;">
				<div>SERVICE JOB CARD</div>
				<div style="font-size:12pt"><?php echo $timeslot; ?> - Customer Copy</div>
			</div>
		</div>
		<div style="width:100%;margin:0 auto;padding:20px">
			<div style="width:33.33%;float:left;text-align:left">User Name : <b><?php if(isset($uname)) { echo convert_to_camel_case($uname); } ?></b></div>
			<div style="width:33.33%;float:left;text-align:left">Contact Number : <b><?php if(isset($uphone)) { echo $uphone; } ?></b></div>
			<div style="width:33.33%;float:left;text-align:left">Email : <b><?php if(isset($uemail)) { echo $uemail; } ?></b></div>
		</div>
		<div style="width:100%;margin:0 auto;padding:20px">
			<div style="width:33.33%;float:left;text-align:left">Order ID : <b><?php if(isset($OId)) { echo $OId; } ?></b></div>
			<div style="width:33.33%;float:left;text-align:left">Bike Model : <b><?php if(isset($bikemodel)) { echo $bikemodel; } ?></b></div>
			<div style="width:33.33%;float:left;text-align:left">Reg No. : <b><?php if(isset($bikenumber)) { echo $bikenumber; } ?></b></div>
			<div style="width:100%;padding:20px 0;float:left;text-align:left">Address : <b><?php if(isset($csaddress)) { echo $csaddress; } ?></b></div>
		</div>
		<div style="width:100%;margin:0 auto;padding:20px">
			<div style="width:33.33%;float:left;text-align:left">Executive Name : <span style="margin-top:10px;">______________________</span></div>
			<div style="width:33.33%;float:left;text-align:left">Executive Contact : <span style="margin-top:10px;">___________________</span></div>
			<div style="width:33.33%;float:left;text-align:left">Amount Paid : <span style="margin-top:10px;"><?php if(isset($tot_paid)) { echo $tot_paid; } ?> INR</span></div>
			<div style="width:33.33%;float:left;text-align:left;margin-top:20px;">Pick Up Time : <span style="margin-top:10px;">______________________</span></div>
			<div style="width:33.33%;float:left;text-align:left;margin-top:20px;">Expected Delivery : <span style="margin-top:10px;">___________________</span></div>
			<div style="width:33.33%;float:left;text-align:left;margin-top:20px;">Other Comments : <span style="margin-top:10px;">_______________________</span></div>

			<div style="width:50%;padding-top:40px;clear:both;text-align:left;float:left;">Executive Signature</div>
			<div style="width:50%;padding-top:40px;text-align:right;float:left">Customer Signature&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </div>
		</div>
	</div>
</body>
</html>