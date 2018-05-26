<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Registration</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('vendor/components/_vcss'); ?>
</head>
<body>
	<?php $this->load->view('vendor/components/_head'); ?>
	<main>
		<div id="agent_register" class="vregister-wrapper">
			<div class="center-align margin-top-5pc margin-bottom-20px">
				<h4>Agent Registration</h4>
			</div>
			<div class="row">
			</div>
			<form method="POST" action="/home/reg_vendor" enctype="multipart/form-data">
				<div class="col s12">
					<label>Service Center Details</label>
				</div>
				<div class="boxShadow row">
					<div class="col s12 m4" >
						<select data-type="select" data-mandatory="true" data-error="Vendor Type" class="styled-select" name="sctype" id="vType">
							<option ></option>
							<option value="sc">Service Center</option>
							<option value="pb">Petrol Bunk</option>
							<option value="ec">PUC</option>
							<option value="pt">Puncture</option>
						</select>
					</div>
					<div class="col s12 m4">
						<select data-type="select" data-mandatory="true" data-error="Company" class="styled-select" id="company" name="company">
							<option></option>
						</select>
					</div>
					<div class="col s12 m4">
						<input data-type="name" data-mandatory="true" data-error="Center Name" type="text" class="" name="scname" id="scname" placeholder="Center Name">
					</div>
					<div class="col s12 m4">
						<input data-type="pint" data-mandatory="false" data-error="Price" type="text" class="" name="ptprice" id="ptprice" placeholder="Estimated Price in INR" style="display:none;">
					</div>
					<div class="col s12 m12" id="slottype_block" style="display:none;">
						<div class="col s12 m4 margin-top-10px height45px" style="">
							<label class="price">
								<input data-type="radio" data-mandatory="true" data-error="Slots Type" type="radio" class="slottype" name="slottype" value="1">
								<span style="margin-left:5px">5 Hour Guaranteed Delivery Slots</span>
							</label>
							<div style="padding-top: 10px;"><input type="hidden" data-error="Slots Type" id="stype_valdtn" /></div>
						</div>
						<div class="col s12 m4 margin-top-10px height45px" style="">
							<label class="price">
								<input data-type="radio" data-mandatory="true" data-error="Slots Type" type="radio" class="slottype" name="slottype" value="2">
								<span style="margin-left:5px">One Day Guaranteed Delivery Slots</span>
							</label>
						</div>
						<div class="col s12 m4 margin-top-10px height45px" style="">
							<label class="price">
								<input data-type="radio" data-mandatory="true" data-error="Slots Type" type="radio" class="slottype" name="slottype" value="3">
								<span style="margin-left:5px">Next Day Guaranteed Delivery Slots</span>
							</label>
						</div>
					</div>
					<div class="col s12 m4">
						<input data-type="int" data-mandatory="true" data-error="Default Slots" type="text" class="" name="defslots" id="defslots" placeholder="Default Slots Per Hour" style="display:none;">
					</div>
					<div class="col s12 m4">
						<input data-type="int" data-mandatory="true" data-error="Slot Interval" type="text" class="" name="slotInterval" id="slotInterval" placeholder="Slot Interval (In Hours)" style="display:none;">
					</div>
				</div>
				<div class="row" id="owner_details">
					<div class="col s12">
						<label>Owner Details</label>
					</div>
					<div class="boxShadow row">
						<div class="col s12 m4">
							<input data-type="name" data-mandatory="true" data-error="Owner Name" type="text" class="" name="oname" id="oname" placeholder="Owner Name">
						</div>
						<div class="col s12 m4">
							<input data-type="phone" data-mandatory="true" data-error="Mobile Number" type="text" class="" name="phone" id="phone" placeholder="Mobile Number">
						</div>
						<div class="col s12 m4">
							<input data-type="phone" data-mandatory="false" data-error="Mobile Number" type="text" class="" name="altphone" id="altphone" placeholder="Alternate Number">
						</div>
						<div class="col s12 m4">
							<input data-type="phone" data-mandatory="false" data-error="Landline" type="text" class="" name="landline" id="landline" placeholder="Landline">
						</div>
						<div class="col s12 m4">
							<input data-type="email" data-mandatory="true" data-error="e-Mail" type="text" class="" name="email" id="email" placeholder="Email Id">
						</div>
					</div>
				</div>
				<div class="row" id="contact_person_details" style="display:none">
					<div class="col s12">
						<label>Contact Person Details</label>
					</div>
					<div class="boxShadow row">
						<div class="col s12 m4">
							<input data-type="name" data-mandatory="true" data-error="Name" type="text" class="" name="cperson" id="cperson" placeholder="Contact Person">
						</div>
						<div class="col s12 m4">
							<input data-type="phone" data-mandatory="true" data-error="Mobile Number" type="text" class="" name="vphone" id="vphone" placeholder="Mobile Number">
						</div>
						<div class="col s12 m4">
							<input data-type="phone" data-mandatory="false" data-error="Mobile Number" type="text" class="" name="valtphone" id="valtphone" placeholder="Alternate Number">
						</div>
						<div class="col s12 m4">
							<input data-type="phone" data-mandatory="false" data-error="" type="text" class="" name="vlandline" id="vlandline" placeholder="Landline">
						</div>
						<div class="col s12 m4">
							<input data-type="email" data-mandatory="false" data-error="e-Mail" type="text" class="" name="vemail" id="vemail" placeholder="Email Id">
						</div>
					</div>
				</div>
				<div class="row" id="address_details">
					<div class="col s12">
						<label>Address Details</label>
					</div>
					<div class="boxShadow row">
						<div class="row">
							<div class="col s12 m4">
								<input data-type="name" data-mandatory="true" data-error="Address" type="text" class="" name="adln1" id="adln1" placeholder="Address Line 1">
							</div>
							<div class="col s12 m4">
								<input data-type="name" data-mandatory="true" data-error="Address" type="text" class="" name="adln2" id="adln2" placeholder="Address Line 2">
							</div>
							<div class="col s12 m4">
								<select data-type="select" data-mandatory="true" data-error="City" name="city" id="city">
									<?php if(isset($cities) && count($cities) > 0) { foreach($cities as $city) { ?>
									<option value="<?php echo $city->CityId; ?>"><?php echo convert_to_camel_case($city->CityName); ?></option>
									<?php } } ?>
								</select>
							</div>
							<div class="col s12 m4" >
								<input data-type="location" data-mandatory="true" data-error="Location" type="text" class="form-control area fromLInput" name="area" id="area" placeholder="Location">
							</div>
							<div class="col s12 m4">
								<input data-type="name" data-mandatory="false" data-error="Landmark" type="text" class="" name="landmark" id="landmark" placeholder="Landmark">
							</div>
							<div class="col s12 m4">
								<input data-type="pincode" data-mandatory="true" data-error="Pincode" type="text" class="" name="pincode" id="pincode" placeholder="Pincode">
							</div>
						</div>
						<div class="row">
							<div class="col s12 m4">
								<input data-type="int" data-mandatory="true" data-error="Latitide" type="text" name="lati" id="lati" placeholder="Click to add Latitude">
							</div>
							<div class="col s12 m4">
								<input data-type="int" data-mandatory="true" data-error="Longitude" type="text" name="longi" id="longi" placeholder="Click to add Longitude">
							</div>
						</div>
					</div>
				</div>
				<div class="row" id="media_details">
					<div class="col s12">
						<label>Logo</label>
					</div>
					<div class="row auto-overflow vendor-image-section z-depth-1 vreg-logo-sec">
						<div class="col s12 m4 media-upload">
							<label for="uploadImage_0">
								<img id="uploadPreview0" style="width: 100px; height: 100px;" />
								<div id="nii0" class="no-image-icon"><i class="material-icons">collections</i></div>
							</label>
							<input data-type="image" data-mandatory="false" data-error="image" id="uploadImage_0" class="mediaUpload" data-id="0" type="file" name="uploadImage_0" style="display:none" />
						</div>
					</div>
				</div>
				<div class="row" id="media_details">
					<div class="col s12">
						<label>Media</label>
					</div>
					<div class="row auto-overflow vendor-image-section z-depth-1">
						<div class="col s12 m4 media-upload">
							<label for="uploadImage_1">
								<img id="uploadPreview1" style="width: 100px; height: 100px;" />
								<div id="nii1" class="no-image-icon"><i class="material-icons">collections</i></div>
							</label>
							<input data-type="image" data-mandatory="false" data-error="image" id="uploadImage_1" class="mediaUpload" data-id="1" type="file" name="uploadImage_1" style="display:none" />
						</div>
						<div class="col s12 m4 media-upload">
							<label for="uploadImage_2">
								<img id="uploadPreview2" style="width: 100px; height: 100px;" />
								<div id="nii2" class="no-image-icon"><i class="material-icons">collections</i></div>
							</label>
							<input data-type="image" data-mandatory="false" data-error="image" id="uploadImage_2" class="mediaUpload" data-id="2" type="file" name="uploadImage_2" style="display:none" />
						</div>
						<div class="col s12 m4 media-upload">
							<label for="uploadImage_3">
								<img id="uploadPreview3" style="width: 100px; height: 100px;" />
								<div id="nii3" class="no-image-icon"><i class="material-icons">collections</i></div>
							</label>
							<input data-type="image" data-mandatory="false" data-error="image" id="uploadImage_3" class="mediaUpload" data-id="3" type="file" name="uploadImage_3" style="display:none" />
						</div>
						<div class="col s12 m4 media-upload">
							<label for="uploadImage_4">
								<img id="uploadPreview4" style="width: 100px; height: 100px;" />
								<div id="nii4" class="no-image-icon"><i class="material-icons">collections</i></div>
							</label>
							<input data-type="image" data-mandatory="false" data-error="image" id="uploadImage_4" class="mediaUpload" data-id="4" type="file" name="uploadImage_4" style="display:none" />
						</div>
						<div class="col s12 m4 media-upload">
							<label for="uploadImage_5">
								<img id="uploadPreview5" style="width: 100px; height: 100px;" />
								<div id="nii5" class="no-image-icon"><i class="material-icons">collections</i></div>
							</label>
							<input data-type="image" data-mandatory="false" data-error="image" id="uploadImage_5" class="mediaUpload" data-id="5" type="file" name="uploadImage_5" style="display:none" />
						</div>
						<div class="col s12 m4 media-upload">
							<label for="uploadImage_6">
								<img id="uploadPreview6" style="width: 100px; height: 100px;" />
								<div id="nii6" class="no-image-icon"><i class="material-icons">collections</i></div>
							</label>
							<input data-type="image" data-mandatory="false" data-error="image" id="uploadImage_6" class="mediaUpload" data-id="6" type="file" name="uploadImage_6" style="display:none" />
						</div>
					</div>
				</div>
				<div class="row" id="ec_details" style="display:none">
					<div class="col s12">
						<label>PUC Details</label>
					</div>
					<div class="boxShadow row">
						<div class="col s12 m6" >
							<select data-type="select" data-mandatory="true" data-error="PUC Type" class="styled-select" name="ectype" id="ecType">
								<option ></option>
								<option value="office">Static</option>
								<option value="mobile">Mobile</option>
							</select>
						</div>
						<div class="col s12 m6" >
							<select data-type="select" data-mandatory="true" data-error="Fuel Type" class="styled-select" name="ftype" id="fuelType">
								<option ></option>
								<option value="petrol">Petrol</option>
								<option value="diesel">Diesel</option>
								<option value="both">Both</option>
							</select>
						</div>
						<div class="col s12 m4">
							<input data-type="name" data-mandatory="true" data-error="Station Code" type="text" class="" name="estncode" id="estncode" placeholder="Station Code">
						</div>
						<div class="col s12 m4">
							<input data-type="name" data-mandatory="true" data-error="License No." type="text" class="" name="elicnum" id="elicnum" placeholder="License No.">
						</div>
						<div class="col s12 m4">
							<input data-type="name" data-mandatory="true" data-error="License Expiry" type="text" class="dpDate" name="elicdate" id="elicdate" placeholder="License Expiry">
						</div>
					</div>
				</div>
				<div class="row" id="ec_box" style="display:none">
					<div class="col s12">
						<label>PUC Rate Card</label>
					</div>
					<div class="boxShadow row">
						<div class="left-align col s12 m4"><span class="col s12 m6 margin-top-10px" >2W - Petrol :</span><span class="col s12 m6 secondary-content"><input data-type="int" data-mandatory="true" data-error="Price" type="text" class="" name="2w_p" id="2w_p" placeholder="0 INR"></span></div>
						<div class="left-align col s12 m4"><span class="col s12 m6 margin-top-10px" >4W - Petrol :</span><span class="col s12 m6 secondary-content"><input data-type="int" data-mandatory="true" data-error="Price" type="text" class="" name="4w_p" id="4w_p" placeholder="0 INR"></span></div>
						<div class="left-align col s12 m4"><span class="col s12 m6 margin-top-10px" >4W - Diesel :</span><span class="col s12 m6 secondary-content"><input data-type="int" data-mandatory="true" data-error="Price" type="text" class="" name="4w_d" id="4w_d" placeholder="0 INR"></span></div>
					</div>
				</div>
				<div class="row" id="pb_box" style="display:none">
					<div class="col s12">
						<label>Petrol Bunk Details</label>
					</div>
					<div class="boxShadow row">
						<div class="col s12">
							<div class="col s12 m3">
								<input data-type="" data-mandatory="false" data-error="" type="text" class="" name="plicnum" id="plicnum" placeholder="License Number">
							</div>
							<div class="left-align col s12 m3"><span class="col s12 m6 margin-top-10px" >Petrol :</span><span class="col s12 m6 secondary-content"><input data-type="int" data-mandatory="true" data-error="Price" type="text" class="" name="pprice" id="pprice" placeholder="0 INR"></span></div>
							<div class="left-align col s12 m3"><span class="col s12 m6 margin-top-10px" >Diesel :</span><span class="col s12 m6 secondary-content"><input data-type="int" data-mandatory="true" data-error="Price" type="text" class="" name="dprice" id="dprice" placeholder="0 INR"></span></div>
							<div class="left-align col s12 m3"><span class="col s12 m6 margin-top-10px" >LPG :</span><span class="col s12 m6 secondary-content"><input data-type="int" data-mandatory="true" data-error="Price" type="text" class="" name="lprice" id="lprice" placeholder="0 INR"></span></div>
						</div>
					</div>
				</div>
				<div class="row" id="pb_details" style="display:none">
					<div class="col s12">
						<label>Petrol Bunk Amenity Details</label>
					</div>
					<div class="boxShadow row">
						<?php if(isset($amenities)) { foreach($amenities as $amenity) { ?>
						<div class="col s12 m6 margin-bottom-20px">
							<div class="checkbox col s12 m6 margin-top-10px height45px" style="">
								<label class="price">
									<input data-type="check" data-mandatory="false" data-error="Amenity" type="checkbox" name="amenity[]" value="<?php echo $amenity->AmId; ?>">
									<span style="margin-left:5px"><?php echo $amenity->AmName ?></span>
								</label>
							</div>
							<?php if(intval($amenity->PriceApply) == 1) { ?>
							<div class="col s12 m6"><input data-type="int" data-mandatory="true" data-error="Price" type="text" class="" name="aprice_<?php echo $amenity->AmId; ?>" id="aprice_<?php echo $amenity->AmId; ?>" placeholder="Price in INR"></div>
							<?php } ?>
							<div class="col s12">
								<textarea data-type="name" data-mandatory="false" data-error="Description" placeholder="Description" name="amdesc_<?php echo $amenity->AmId; ?>" id="amdesc_<?php echo $amenity->AmId; ?>"></textarea>
							</div>
						</div>
						<?php } } ?>
					</div>
				</div>
				<div class="row" id="timings_box" style="display:none">
					<div class="col s12">
						<label>Timings</label>
					</div>
					<div class="boxShadow row">
						<?php
							$days = [['id' => 'mon', 'day' => 'Monday' ], ['id' => 'tue', 'day' => 'Tuesday' ], ['id' => 'wed', 'day' => 'Wednesday' ], ['id' => 'thu', 'day' => 'Thursday' ], ['id' => 'fri', 'day' => 'Friday' ], ['id' => 'sat', 'day' => 'Saturday' ], ['id' => 'sun', 'day' => 'Sunday' ]];
							foreach($days as $day) {
						?>
						<div class="col s12 m6 margin-bottom-20px">
							<div class="checkbox col s12 m4 margin-top-10px" style="">
								<label class="price">
									<input data-type="check" data-mandatory="false" data-error="Day" type="checkbox" class="timingdays" name="tmngdays[]" value="<?php echo $day['id']; ?>">
									<span style="margin-left:5px"><?php echo convert_to_camel_case($day['day']); ?></span>
								</label>
							</div>
							<div class="col s12 m4">
								<select data-type="select" data-mandatory="true" data-error="Time" class="stime" name="stime_<?php echo $day['id']; ?>" id="stime_<?php echo $day['id']; ?>">
									<option></option>
									<?php
										for($i = 0; $i < 24; $i += 0.5) {
											$int_part = floor($i);
											$decimal_part = $i - ($int_part);
											if($int_part < 12) {
												if($int_part == 0) {
													$int_part = 12;
												}
												if($int_part < 10) {
													$time = '0' . $int_part . ':';
												} else {
													$time = $int_part . ':';
												}
												if ($decimal_part < 0.01) {
													$time .= '00 AM';
												} else {
													$time .= '30 AM';
												}
											} elseif($int_part == 12) {
												$time = '12:';
												if ($decimal_part < 0.01) {
													$time .= '00 PM';
												} else {
													$time .= '30 PM';
												}
											} else {
												if(($int_part - 12) < 10) {
													$time = '0' . ($int_part - 12) . ':';
												} else {
													$time = ($int_part - 12) . ':';
												}
												if ($decimal_part < 0.01) {
													$time .= '00 PM';
												} else {
													$time .= '30 PM';
												}
											}
									?>
									<option value="<?php echo $i; ?>"><?php echo $time; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col s12 m4">
								<select data-type="select" data-mandatory="true" data-error="Time" class="etime" name="etime_<?php echo $day['id']; ?>" id="etime_<?php echo $day['id']; ?>">
									<option></option>
									<?php
										for($i = 0; $i < 24; $i += 0.5) {
											$int_part = floor($i);
											$decimal_part = $i - ($int_part);
											if($int_part < 12) {
												if($int_part == 0) {
													$int_part = 12;
												}
												if($int_part < 10) {
													$time = '0' . $int_part . ':';
												} else {
													$time = $int_part . ':';
												}
												if ($decimal_part < 0.01) {
													$time .= '00 AM';
												} else {
													$time .= '30 AM';
												}
											} elseif($int_part == 12) {
												$time = '12:';
												if ($decimal_part < 0.01) {
													$time .= '00 PM';
												} else {
													$time .= '30 PM';
												}
											} else {
												if(($int_part - 12) < 10) {
													$time = '0' . ($int_part - 12) . ':';
												} else {
													$time = ($int_part - 12) . ':';
												}
												if ($decimal_part < 0.01) {
													$time .= '00 PM';
												} else {
													$time .= '30 PM';
												}
											}
									?>
									<option value="<?php echo $i; ?>"><?php echo $time; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="col s12 center-align">
					<button type="submit" id="submit" name="vregister" class="btn waves-effect waves-light btn" >
						Submit
					</button>
				</div>
			</form>
		</div>
	</main>
	<?php $this->load->view('vendor/components/_foot'); ?>
<?php $this->load->view('vendor/components/_vjs'); ?>
<script type="text/javascript" src="<?php echo site_url('js/vregister.js'); ?>"></script>
<script>
	var sc_data = <?php if(isset($sc_companies)) { echo $sc_companies; } ?>;
	var pb_data = [{ id: 'Shell', text: 'Shell' }, { id: 'IOCL', text: 'Indian Oil' }, { id: 'HP', text: 'Hindusthan Petroleum' }, { id: 'BP', text: 'Bharat Petroleum' }];
	$(function() {
		$('#city').val("1").trigger("change");
	});
</script>
</body>
</html>