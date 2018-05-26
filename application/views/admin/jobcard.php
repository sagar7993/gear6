<!DOCTYPE html>
<html class="no-scroll">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Job Card Details</title>
	<link rel="shortcut icon" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/jQueryUI/jquery-ui.css'); ?>">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Raleway" type="text/css">
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/materialize.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/estyle.css'); ?>">
</head>
<body>
<div class="main">
	<div class="">
		<div>
			<ul class="collapsible" data-collapsible="accordion">
				<li>
					<div class="collapsible-header active"><i class="material-icons">receipt</i>Order Details</div>
					<div class="collapsible-body">
						<div class="review-options-container row">
							<div class="col m6 l3 right-dot-margin padding-10px">
								<label for="exampleInputPassword1">Order ID</label><br/>
								<label class="selected-options"><b><?php if(isset($OId)) { echo $OId; } ?></b></label>
							</div>
							<div class="col m6 l3 right-dot-margin padding-10px">
								<label for="exampleInputPassword1">Service Center</label><br/>
								<label class="selected-options"><b><?php if(isset($scenter)) { echo convert_to_camel_case($scenter[0]['ScName']); } ?></b></label>
							</div>
							<div class="col m6 l3 right-dot-margin padding-10px">
								<label for="exampleInputPassword1">Service Type</label><br/>
								<label class="selected-options"><b><?php echo convert_to_camel_case($stype); ?></b></label>
							</div>
							<div class="col m6 l3 padding-10px">
								<label for="exampleInputPassword1">Bike Model</label><br/>
								<label class="selected-options"><b><?php if(isset($bikemodel)) { echo $bikemodel; } ?></b></label>
							</div>
							<div class="col m6 l3 margin-top-22px">
								<label for="exampleInputPassword1">Bike Number</label><br/>
								<label class="selected-options"><b><?php if(isset($bikenumber)) { echo $bikenumber; } ?></b></label>
							</div>
							<div class="col m6 l3 margin-top-22px">
								<label for="exampleInputPassword1">Payment</label><br/>
								<label class="selected-options"><b>Paid - <?php if(isset($tot_paid)) { echo $tot_paid; } ?> INR</b></label>
							</div>
							<div class="col s12 m6 margin-top-22px">
								<label for="exampleInputPassword1">Time Slot</label><br/>
								<label class="selected-options"><b><?php if(isset($timeslot)) { echo $timeslot; } ?></b></label>
							</div>
							<div class="col s10 m6 margin-top-22px">
								<label for="exampleInputPassword1">Address</label><br/>
								<label class="selected-options">
									<b><?php if(isset($csaddress)) { echo $csaddress; } ?></b>
								</label>
							</div>
							<div class="col s2 margin-top-22px">
								<label for="exampleInputPassword1">Map</label><br/>
								<label class="selected-options"><b><a href="https://www.google.com/maps/place/<?php if(isset($u_loc_pin)) { echo $u_loc_pin; } ?>"><i class="material-icons">pin_drop</i></a></b></label>
							</div>
							<div class="col s12 m6 margin-top-22px">
								<label for="exampleInputPassword1">Contact</label><br/>
								<label class="selected-options"><b><a href="tel:+91<?php if(isset($uphone)) { echo $uphone; } ?>"><?php if(isset($uphone)) { echo '+91' . $uphone; } ?></a></b></label>
							</div>
					</div>
				</div>
				</li>
				<li>
					<div class="collapsible-header active"><i class="material-icons">chrome_reader_mode</i>Job Card</div>
					<div class="collapsible-body">
						<div class="review-options-container row">
							<form id="exjcform">
							<div class="col s12 m6 right-dot-margin padding-10px">
								<label for="exampleInputPassword1">Standard Jobs</label><br/>
								<label class="selected-options"><b><?php if(isset($chosen_amenities)) { echo $chosen_amenities; } else { echo 'NIL'; } ?></b></label>
							</div>
							<div class="col s12 m6 right-dot-margin padding-10px">
								<label for="exampleInputPassword1">Other Jobs</label><br/>
								<label class="selected-options"><b><?php if(isset($chosen_aservices)) { echo $chosen_aservices; } else { echo 'NIL'; } ?></b></label>
							</div>
							<div class="col s12 right-dot-margin padding-10px">
								<label for="exampleInputPassword1">User Comments</label><br/>
								<label class="selected-options"><b><?php if(isset($scenter) && (isset($scenter[0]['ServiceDesc1']) || isset($scenter[0]['ServiceDesc2']))) { echo $scenter[0]['ServiceDesc1'] . ' ' . $scenter[0]['ServiceDesc2']; } else { echo 'NIL'; } ?></b></label>
							</div>
							<div class="input-field col s6">
								<input id="cr_bikecolor" name="cr_bikecolor" type="text" class="input-form" readonly disabled>
							</div>
							<div class="input-field col s6">
								<input id="cr_kms" name="cr_kms" type="text" class="input-form" readonly disabled>
							</div>
							<?php if(isset($JcKms)) { ?>
								<div class="input-field col s12 m6 l6">
									<input id="cr_jckms" name="cr_jckms" type="text" class="input-form" value="<?php echo 'Distance : ' . $JcKms . ' Kms'; ?>" readonly disabled>
								</div>
							<?php } ?>
							<?php if(isset($JcNum)) { ?>
								<div class="input-field col s12 m6 l6">
									<input id="cr_jcnum" name="cr_jcnum" type="text" class="input-form" value="<?php echo 'Job Card No. : ' . $JcNum; ?>" readonly disabled>
								</div>
							<?php } ?>
							<div class="col s12 m6 right-dot-margin padding-10px">
								<label for="cs_fuelrange">Fuel Range</label><br/>
								<p class="range-field" style="padding:0">
									<input type="range" id="cs_fuelrange" name="cs_fuelrange" min="0" max="100" />
								</p>
							</div>
							<?php if(isset($jccats) && count($jccats) > 0) { foreach($jccats as $jccat) { ?>
							<div class="input-field col s12 m12 l12">
								<h5><b><?php echo $jccat['JCPName']; ?></h5></b>
								<?php if(isset($jccat['SJCCats']) && count($jccat['SJCCats']) > 0) { foreach($jccat['SJCCats'] as $category) { ?>
								<div class="input-field col s12 m4 l3">
									<input type="checkbox" class="filled-in" id="scat_<?php echo $category['JCSubCats'][0]['JCSCatId']; ?>" value="<?php echo $category['JCSubCats'][0]['JCSCatId']; ?>"/>
									<label for="scat_<?php echo $category['JCSubCats'][0]['JCSCatId']; ?>"><?php echo $category['JCCatName'] . " " . $category['JCSubCats'][0]['JCSCatName']; ?></label>
								</div>									
								<?php } } ?>
							</div>
							<div class="input-field col s12 m12 l12">
								<?php if(isset($jccat['MJCCats']) && count($jccat['MJCCats']) > 0) { foreach($jccat['MJCCats'] as $category) { ?>
									<div class="input-field col s12 m12 l12">
										<h6><strong><?php echo $category['JCCatName']; ?></strong></h6>
										<?php foreach($category['JCSubCats'] as $subcategory) { ?>
											<?php if($category['isMultiple'] == "0") { ?>
												<div class="input-field col col s12 m4 l4">
													<input class="with-gap" name="<?php echo $category['JCFormName']; ?>" type="radio" id="scat_<?php echo $subcategory['JCSCatId']; ?>" value="<?php echo $subcategory['JCSCatId']; ?>"/>
											      	<label for="scat_<?php echo $subcategory['JCSCatId']; ?>"><?php echo $subcategory['JCSCatName']; ?></label>
											    </div>
											<?php } else { ?>
												<div class="input-field col col s12 m6 l3">
													<input type="checkbox" class="filled-in" id="scat_<?php echo $subcategory['JCSCatId']; ?>" value="<?php echo $subcategory['JCSCatId']; ?>"/>
													<label for="scat_<?php echo $subcategory['JCSCatId']; ?>"><?php echo $subcategory['JCSCatName']; ?></label>
												</div>
											<?php } ?>
										<?php } ?>
									</div>
								<?php } } ?>
							</div>
							<?php } } ?>
							<div class="row">
								<div class="row">
								<div class="input-field col s12">
									<textarea id="us_comments" name="us_comments" class="materialize-textarea"></textarea>
									<label for="us_comments" class="input-label">User Comments</label>
								</div>
								</div>
							</div>
							<input type="hidden" name="oid" value="<?php if(isset($OId)) { echo $OId; } ?>">
							</form>
						</div>
					</div>
				</li>
				<li>
					<div class="collapsible-header active"><i class="material-icons">monetization_on</i>Price Estimates</div>
					<div class="collapsible-body ">
						<div class="row">
							<div class="col s12">
								<div class="review-options-container">
									<div class="form-group col s4 right-dot-margin">
										<label for="">Estimated Date</label><br>
										<label class="selected-options"><?php if(isset($jc_bike_estdate)) { echo $jc_bike_estdate; } else { echo "NA"; } ?></label>
									</div>
									<div class="form-group col s4 right-dot-margin" style="padding-left:25px;">
										<label for="">Estimated Time</label><br>
										<label class="selected-options"><?php if(isset($jc_bike_esttime)) { echo $jc_bike_esttime; } else { echo "NA"; } ?></label>
									</div>
									<div class="form-group col s4" style="padding-left:25px;">
										<label for="">Estimated Cost (Approximate)</label><br>
										<label class="selected-options"><?php if(isset($jc_bike_estprice)) { echo $jc_bike_estprice; } else { echo "NA"; } ?></label>
									</div>
									<?php if(isset($CPName)) { ?>
										<div class="input-field col s12 m6 l6">
											<input id="cr_cpname" name="cr_cpname" type="text" class="input-form" value="<?php echo 'Contact Person : ' . $CPName; ?>" readonly disabled>
										</div>
									<?php } ?>
									<?php if(isset($CPPhone)) { ?>
										<div class="input-field col s12 m6 l6">
											<input id="cr_cpphone" name="cr_cpphone" type="text" class="input-form" value="<?php echo 'Contact Number : ' . $CPPhone; ?>" readonly disabled>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<?php if(isset($jc_bike_estremarks)) { ?>
						<div class="row" style="margin-left:3px!important;">
							<div class="col s12">
								<label for="">Admin Remarks</label>
								<label class="selected-options"><?php if(isset($jc_bike_estremarks)) { echo $jc_bike_estremarks; } else { echo "NA"; } ?></label>
							</div>
						</div>
						<?php } ?>
					</div>
				</li>
				<li>
					<div class="collapsible-header"><i class="material-icons">perm_media</i>Media</div>
					<div class="collapsible-body">
						<div class="review-options-container row">
							<div class="row" style="padding-top:50px;" id="uploaded_jcimages">
								<?php if(isset($jcimages)) { foreach($jcimages as $jcimgurl) { ?>
									<h6 align="center"><b>Bike Images</b></h6>
									<div class="col s6"> 
										<img class="materialboxed" width="auto" height="100px" src="<?php echo $jcimgurl; ?>">
									</div>
								<?php } } else { ?>
									<h6 align="center"><b>No Bike Images Uploaded</b></h6>
								<?php } ?>
								<?php if(isset($billimages)) { foreach($billimages as $billimgurl) { ?>
									<h6 align="center"><b>Bill Images</b></h6>
									<div class="col s6"> 
										<img class="materialboxed" width="auto" height="100px" src="<?php echo $billimgurl; ?>">
									</div>
								<?php } } else { ?>
									<h6 align="center"><b>No Bill Images Uploaded</b></h6>
								<?php } ?>

							</div>
						</div>
					</div>
				</li>
				<li>
					<div class="collapsible-header"><i class="material-icons">perm_data_setting</i>Service Center Details</div>
					<div class="collapsible-body">
						<div class="review-options-container row">
							<div class="col m6 l3 right-dot-margin padding-10px">
								<label for="exampleInputPassword1">Name</label><br/>
								<label class="selected-options"><b><?php if(isset($fscdetails)) { echo convert_to_camel_case($fscdetails['CPerson']); } ?></b></label>
							</div>
							<div class="col m6 l3 right-dot-margin padding-10px">
								<label for="exampleInputPassword1">Contact</label><br/>
								<label class="selected-options"><b><a href="tel:<?php if(isset($fscdetails)) { echo '+91' . $fscdetails['Phone']; } ?>"><?php if(isset($fscdetails)) { echo '+91' . $fscdetails['Phone']; } ?></a></b></label>
							</div>
							<div class="col s10 m6 margin-top-22px">
								<label for="exampleInputPassword1">Address</label><br/>
								<label class="selected-options"><b><?php if(isset($scaddress)) { echo $scaddress; } ?></b></label>
							</div>
							<div class="col s2 margin-top-22px">
								<label for="exampleInputPassword1">Map</label><br/>
								<label class="selected-options"><b><a href="https://www.google.com/maps/place/<?php if(isset($fscdetails)) { echo $fscdetails['Latitude'] . ',' . $fscdetails['Longitude']; } ?>"><i class="material-icons">pin_drop</i></a></b></label>
							</div>
						</div>
					</div>
				</li>
				<li style="display:none;">
					<div class="collapsible-header"><i class="material-icons">attach_money</i>Pricing Details</div>
					<div class="collapsible-body">
						<table class="tcenter bordered">
							<thead style="background:#f6f6f5">
								<tr style="background:#f6f6f5">
									<th data-field="id">Component</th>
									<th data-field="price">Price</th>
								</tr>
							</thead>
							<tbody>
								<?php if(isset($estprices) && $estprices !== NULL) { ?>
								<?php foreach($estprices as $estprice) { if(isset($estprice['apdesc']) && isset($estprice['aprice'])) { ?>
								<tr>
									<td><?php echo convert_to_camel_case($estprice['apdesc']); ?></td>
									<td><?php echo $estprice['aprice']; ?> INR</td>
								</tr>
								<?php if(intval($estprice['atprice']) != 0) { ?>
								<tr>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo convert_to_camel_case($estprice['atdesc']); ?></td>
									<td><?php echo $estprice['atprice']; ?> INR</td>
								</tr>
								<?php } } } } ?>
								<?php if (isset($oprices) && count($oprices) > 0) { ?>
								<?php foreach($oprices as $oprice) { if(isset($oprice['opdesc']) && isset($oprice['oprice'])) { ?>
								<tr>
									<td><?php echo convert_to_camel_case($oprice['opdesc']); ?></td>
									<td><?php echo $oprice['oprice']; ?> INR</td>
								</tr>
								<?php } } } ?>
								<?php if (isset($discprices) && count($discprices) > 0) { ?>
								<?php foreach($discprices as $discprice) { if(isset($discprice['apdesc']) && isset($discprice['aprice'])) { ?>
								<tr>
									<td><?php echo convert_to_camel_case($discprice['apdesc']); ?></td>
									<td>&nbsp;-&nbsp;<?php echo $discprice['aprice']; ?> INR</td>
								</tr>
								<?php } } } ?>
							</tbody>
							<tfoot>
								<tr style="background:#a7a7a7;color:#ffffff;border-bottom:1px solid #ffffff;">
									<td>Total Billed Amount</td>
									<td><?php if(isset($tot_billed)) { echo $tot_billed; } ?> INR</td>
								</tr>
								<tr style="background:#a7a7a7;color:#ffffff;border-bottom:1px solid #ffffff;">
									<td>Total Paid</td>
									<td><?php if(isset($tot_paid)) { echo $tot_paid; } ?> INR</td>
								</tr>
								<tr style="background:#a7a7a7;color:#ffffff;border-bottom:1px solid #ffffff;">
									<td>Total Due</td>
									<td><?php if(isset($to_be_paid)) { echo $to_be_paid; } ?> INR</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</li>
				<li style="display:none;">
					<div class="collapsible-header"><i class="material-icons">assignment</i>Executive Checklist</div>
					<div class="collapsible-body popout">
						<ul class="collapsible popout" data-collapsible="accordion">
							<?php if(isset($execlcats) && count($execlcats) > 0) { foreach($execlcats as $execlcat) { ?>
							<li>
								<div class="collapsible-header"><?php echo $execlcat['CLCatIcon'] . $execlcat['CLCatName']; ?></div>
								<div class="collapsible-body">
									<table class="tcenter bordered">
										<tbody>
											<?php if(isset($execlscats) && count($execlscats) > 0) { foreach($execlscats[intval($execlcat['CLCatId'])] as $execlscat) { ?>
											<tr>
												<td><?php echo $execlscat['CLSCatName']; ?></td>
												<td>
													<input class="execlchecks" type="checkbox" id="execl_<?php echo $execlscat['CLSCatId']; ?>" name="execlchecks[]" value="<?php echo $execlscat['CLSCatId']; ?>" />
													<label for="execl_<?php echo $execlscat['CLSCatId']; ?>"></label>
												</td>
											</tr>
											<?php } } ?>
										</tbody>
									</table>
								</div>
							</li>
							<?php } } ?>
						</ul>
					</div>
				</li>
			</ul>
		</div>
	</div>
</div>
</body>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/materialize.js'); ?>"></script>
</html>
<script>
<?php if(isset($OId)) { echo 'var univ_order_id = "' . $OId . '";'; } ?>
var jccats = <?php if(isset($jccats)) { echo json_encode($jccats); } else echo json_encode(array()); ?>;
var jcselects = <?php if(isset($jcselects)) { echo json_encode($jcselects); } else echo json_encode(array()); ?>;
$(document).ready(function() {
	<?php if(isset($cr_bikecolor)) { echo '$("#cr_bikecolor").val("Bike Color : ' . $cr_bikecolor . '");'; } ?>
	<?php if(isset($cr_kms)) { echo '$("#cr_kms").val("Kms Reading : ' . $cr_kms . '");'; } ?>
	<?php if(isset($cs_fuelrange)) { echo '$("#cs_fuelrange").val("' . $cs_fuelrange . '");'; } ?>
	<?php if(isset($us_comments)) { echo '$("#us_comments").val("' . trim(preg_replace("/\r+|\n+/", ", ", $us_comments)) . '");'; } ?>
	$(".button-collapse").sideNav();
	$("select").material_select();
	$('.materialboxed').materialbox();
});
$(function() {
	try {
		for(var i = 0; i < jcselects['JCSelects'].length; i++) {
			$('#scat_' + jcselects['JCSelects'][i]).attr('checked', 'checked');
		}
	} catch(err) { }
	try {
		for(var i = 0; i < jcselects['ChecklistVals'].length; i++) {
			$('#execl_' + jcselects['ChecklistVals'][i]).attr('checked', 'checked');
		}
	} catch(err) {
		//Do Nothing
	}
});
</script>