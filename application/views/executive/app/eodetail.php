<?php $this->load->view('executive/components/_head'); ?>
	<div>
		<ul class="collapsible" data-collapsible="accordion">
			<li>
				<div class="collapsible-header active"><i class="material-icons">receipt</i>Order Details</div>
				<div class="collapsible-body ">
					<div class="review-options-container row">
						<div class="col m6 l3 right-dot-margin padding-10px " style="">
							<label for="exampleInputPassword1">Order ID</label><br/>
							<label class="selected-options"><b><?php if(isset($OId)) { echo $OId; } ?></b></label>
						</div>
						<div class="col m6 l3 right-dot-margin padding-10px " style="">
							<label for="exampleInputPassword1">Service Center</label><br/>
							<label class="selected-options"><b><?php if(isset($scenter)) { echo convert_to_camel_case($scenter[0]['ScName']); } ?></b></label>
						</div>
						<div class="col m6 l3 right-dot-margin padding-10px " style="">
							<label for="exampleInputPassword1">Service Type</label><br/>
							<label class="selected-options"><b><?php echo convert_to_camel_case($stype); ?></b></label>
						</div>
						<div class="col m6 l3 padding-10px " style="">
							<label for="exampleInputPassword1">Bike Model</label><br/>
							<label class="selected-options"><b><?php if(isset($bikemodel)) { echo $bikemodel; } ?></b></label>
						</div>
						<div class="col m6 l3 margin-top-22px ">
							<label for="exampleInputPassword1">Bike Number</label><br/>
							<label class="selected-options"><b><?php if(isset($bikenumber)) { echo $bikenumber; } ?></b></label>
						</div>
						<div class="col m6 l3 margin-top-22px ">
							<label for="exampleInputPassword1">Payment</label><br/>
							<label class="selected-options"><b>Paid - <?php if(isset($tot_paid)) { echo $tot_paid; } ?> INR</b></label>
						</div>
						<div class="col s12 m6 margin-top-22px ">
							<label for="exampleInputPassword1">Time Slot</label><br/>
							<label class="selected-options"><b><?php if(isset($timeslot)) { echo $timeslot; } ?></b></label>
						</div>
						<div class="col s10 m6 margin-top-22px ">
							<label for="exampleInputPassword1">Address</label><br/>
							<label class="selected-options">
								<b><?php if(isset($csaddress)) { echo $csaddress; } ?></b>
							</label>
						</div>
						<div class="col s2 margin-top-22px ">
							<label for="exampleInputPassword1">Map</label><br/>
							<label class="selected-options"><b><a href="https://www.google.com/maps/place/<?php if(isset($u_loc_pin)) { echo $u_loc_pin; } ?>"><i class="material-icons">pin_drop</i></a></b></label>
						</div>
						<div class="col s12 m6 margin-top-22px ">
							<label for="exampleInputPassword1">Contact</label><br/>
							<label class="selected-options"><b><a href="tel:+91<?php if(isset($uphone)) { echo $uphone; } ?>"><?php if(isset($uphone)) { echo '+91' . $uphone; } ?></a></b></label>
						</div>
				</div>
			</div>
			</li>
			<li>
				<div class="collapsible-header active"><i class="material-icons">chrome_reader_mode</i>Job Card</div>
				<div class="collapsible-body ">
					<div class="review-options-container row">
						<form id="exjcform">
						<div class="col s12 m6 right-dot-margin padding-10px " style="">
							<label for="exampleInputPassword1">Standard Jobs</label><br/>
							<label class="selected-options"><b><?php if(isset($chosen_amenities)) { echo $chosen_amenities; } else { echo 'NIL'; } ?></b></label>
						</div>
						<div class="col s12 m6 right-dot-margin padding-10px " style="">
							<label for="exampleInputPassword1">Other Jobs</label><br/>
							<label class="selected-options"><b><?php if(isset($chosen_aservices)) { echo $chosen_aservices; } else { echo 'NIL'; } ?></b></label>
						</div>
						<div class="col s12 m6 right-dot-margin padding-10px " style="">
							<label for="exampleInputPassword1">User Comments</label><br/>
							<label class="selected-options"><b><?php if(isset($scenter) && isset($scenter[0]['ServiceDesc1']) && isset($scenter[0]['ServiceDesc2'])) { echo $scenter[0]['ServiceDesc1'] . ' ' . $scenter[0]['ServiceDesc2']; } else { echo 'NIL'; } ?></b></label>
						</div>
						<div class="input-field col s12">
							<input id="cr_bikecolor" name="cr_bikecolor" type="text" class="input-form">
							<label for="cr_bikecolor" class="input-label">Bike Color</label>
						</div>
						<div class="input-field col s12">
							<input id="cr_kms" name="cr_kms" type="text" class="input-form">
							<label for="cr_kms" class="input-label">Kms Reading</label>
						</div>
						<table class="tcenter tbordered">
							<tbody>
								<?php if($bikeparts && count($bikeparts) > 0) { foreach($bikeparts as $bikepart) { ?>
								<tr>
									<td><?php echo $bikepart->BikePartName; ?></td>
									<td>
										<input class="bpartsckd" type="checkbox" name="bpartsckd[]" id="bpn_<?php echo $bikepart->BikePartId; ?>" value="<?php echo $bikepart->BikePartId; ?>">
										<label for="bpn_<?php echo $bikepart->BikePartId; ?>"></label>
									</td>
								</tr>
								<?php } } ?>
							</tbody>
						</table>
						<div class="col s12 m6 right-dot-margin padding-10px " style="">
							<label for="cs_fuelrange">Fuel Range</label><br/>
							<p class="range-field" style="padding:0">
								<input type="range" id="cs_fuelrange" name="cs_fuelrange" min="0" max="100" />
							</p>
						</div>
						<?php if(isset($jccats) && count($jccats) > 0) { foreach($jccats as $jccat) { ?>
						<div class="input-field col s12">
							<select id="<?php echo $jccat['JCFormName']; ?>" name="<?php echo $jccat['JCFormName']; ?><?php if(intval($jccat['isMultiple']) == 1) { echo '[]'; } ?>"<?php if(intval($jccat['isMultiple']) == 1) { echo ' multiple'; } ?> class="browser-default">
								<option value="" disabled selected><?php echo $jccat['JCCatName']; ?></option>
								<?php if(isset($jcscats) && count($jcscats) > 0) { foreach($jcscats[intval($jccat['JCCatId'])] as $jcscat) { ?>
								<option value="<?php echo $jcscat['JCSCatId']; ?>"><?php echo $jcscat['JCSCatName']; ?></option>
								<?php } } ?>
							</select>
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
						<div class="col s12" id="jclistupld_btncont" style="text-align:center;margin-top:12px;">
							<button class="waves-effect waves-light btn login-btn width100" id="jc_update">
								Submit
							</button>
						</div>
						</form>
					</div>
				</div>
			</li>
			<li>
				<div class="collapsible-header"><i class="material-icons">perm_media</i>Media</div>
				<div class="collapsible-body ">
					<div class="review-options-container row">
						<form>
							<div class="col s12 file-field input-field">
								<div class="btn">
									<span>Image</span>
									<input type="file" name="jc_image" id="jc_image">
								</div>
								<div class="file-path-wrapper">
									<input class="file-path validate" style="border:none;box-shadow:none;" type="text" placeholder="Upload one or more files" readonly>
								</div>
							</div>
							<input type="hidden" name="oid" value="<?php if(isset($OId)) { echo $OId; } ?>">
							<div class="col s12" style="text-align:center;margin-top:12px;" id="jcimgupld_btncont">
								<button class="waves-effect waves-light btn login-btn width100" id="ex_jcimg_upload">
									Upload Image
								</button>
							</div>
						</form>
						<div class="row" style="padding-top:50px;" id="uploaded_jcimages">
							<?php if(isset($jcimages)) { foreach($jcimages as $jcimgurl) { ?>
								<div class="col s6"> 
									<img class="materialboxed" width="auto" height="100px" src="<?php echo $jcimgurl; ?>">
								</div>
							<?php } } ?>
						</div>
					</div>
				</div>
			</li>
			<li>
				<div class="collapsible-header"><i class="material-icons">perm_data_setting</i>Service Center Details</div>
				<div class="collapsible-body ">
					<div class="review-options-container row">
						<div class="col m6 l3 right-dot-margin padding-10px " style="">
							<label for="exampleInputPassword1">Name</label><br/>
							<label class="selected-options"><b><?php if(isset($fscdetails)) { echo convert_to_camel_case($fscdetails['CPerson']); } ?></b></label>
						</div>
						<div class="col m6 l3 right-dot-margin padding-10px " style="">
							<label for="exampleInputPassword1">Contact</label><br/>
							<label class="selected-options"><b><a href="tel:<?php if(isset($fscdetails)) { echo '+91' . $fscdetails['Phone']; } ?>"><?php if(isset($fscdetails)) { echo '+91' . $fscdetails['Phone']; } ?></a></b></label>
						</div>
						<div class="col s10 m6 margin-top-22px ">
							<label for="exampleInputPassword1">Address</label><br/>
							<label class="selected-options"><b><?php if(isset($scaddress)) { echo $scaddress; } ?></b></label>
						</div>
						<div class="col s2 margin-top-22px ">
							<label for="exampleInputPassword1">Map</label><br/>
							<label class="selected-options"><b><a href="https://www.google.com/maps/place/<?php if(isset($fscdetails)) { echo $fscdetails['Latitude'] . ',' . $fscdetails['Longitude']; } ?>"><i class="material-icons">pin_drop</i></a></b></label>
						</div>
					</div>
				</div>
			</li>
			<li>
				<div class="collapsible-header"><i class="material-icons">attach_money</i>Pricing Details</div>
				<div class="collapsible-body ">
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
			<li>
				<div class="collapsible-header"><i class="material-icons">assignment</i>Executive Checklist</div>
				<div class="collapsible-body popout">
					<ul class="collapsible popout" data-collapsible="accordion">
						<?php if(isset($execlcats) && count($execlcats) > 0) { foreach($execlcats as $execlcat) { ?>
						<li>
							<div class="collapsible-header"><?php echo $execlcat['CLCatIcon'] . $execlcat['CLCatName']; ?></div>
							<div class="collapsible-body ">
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
<?php $this->load->view('executive/components/_foot'); ?>
<script>
<?php if(isset($OId)) { echo 'var univ_order_id = "' . $OId . '";'; } ?>
var execlscatsckd = [<?php if(isset($execlscatsckd)) { echo $execlscatsckd; } ?>];
var bikepartsckd = [<?php if(isset($bikepartsckd)) { echo $bikepartsckd; } ?>];
$(document).ready(function() {
	<?php if(isset($jcselects) && count($jcselects) > 0) { foreach($jcselects as $key => $value) { ?>
		$('#<?php echo $key; ?>').val(<?php echo json_encode($value); ?>);
	<?php } } ?>
	<?php if(isset($cr_bikecolor)) { echo '$("#cr_bikecolor").val("' . $cr_bikecolor . '");'; } ?>
	<?php if(isset($cr_kms)) { echo '$("#cr_kms").val("' . $cr_kms . '");'; } ?>
	<?php if(isset($cs_fuelrange)) { echo '$("#cs_fuelrange").val("' . $cs_fuelrange . '");'; } ?>
	<?php if(isset($us_comments)) { echo '$("#us_comments").val("' . $us_comments . '");'; } ?>
	$(".button-collapse").sideNav();
	$("select").material_select();
	$('.materialboxed').materialbox();
});
</script>