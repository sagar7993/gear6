<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Profile - Service Center Info</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('vendor/components/_vcss'); ?>
</head>
<body>
	<?php $this->load->view('vendor/components/_head'); ?>
	<?php if(isset($v_is_logged_in) && $v_is_logged_in == 1) { ?>
		<div class="wrapper row-offcanvas row-offcanvas-left">
			<?php $this->load->view('vendor/components/_sidebar'); ?>
			<aside class="right-side">
				<section class="content-header">
					<h1>
						Service Center Info
						<small>Phone , Email , Address &amp; Photos</small>
					</h1>
				</section>
				<section class="contact-content">
					<div class="col-xs-12 fields-update-container">
						<div class="col-xs-6 fields-container">
							<div class="col-xs-10 field-box">
								<label>Service Center Name</label>
								<input type="text" class="form-control" oninput="checkfield();" readonly name="sc_name" id="sc_name" value="<?php echo convert_to_camel_case($sc_details['ScName']); ?>" placeholder="Service Center Name">
							</div>
							<div class="col-xs-2 field-edit edit_field" id="sc_name_edit"></div>
						</div>
						<div class="col-xs-6 fields-container">
							<div class="col-xs-10 field-box">
								<label>Owner Name</label>
								<input type="text" class="form-control" oninput="checkfield();" readonly name="owner_name" id="owner_name" value="" placeholder="Owner's Name">
							</div>
							<div class="col-xs-2 field-edit edit_field" id="owner_name_edit"></div>
						</div>
						<div class="col-xs-6 fields-container">
							<div class="col-xs-10 field-box">
								<label>e-Mail</label>
								<input type="text" class="form-control" oninput="checkfield();" readonly name="email" id="email" value="" placeholder="Service Center Email">
							</div>
							<div class="col-xs-2 field-edit edit_field" id="email__edit"></div>
						</div>
						<div class="col-xs-6 fields-container ll-box">
							<div class="ll-container">
								<div class="col-xs-10 field-box">
									<label>Landline</label>
									<input type="text" class="form-control" oninput="checkfield();" readonly name="landline" id="landline" value="" placeholder="Landline Number">
								</div>
								<div class="col-xs-2 field-edit edit_field" id="landline__edit"></div>
							</div>
						</div>
						<div class="col-xs-6 fields-container ph-box">
							<div class="ph-container">
								<div class="col-xs-10 field-box">
									<label>Mobile Number</label>
									<input type="text" class="form-control" oninput="checkfield();" readonly name="phNum" id="phNum" value="" placeholder="Mobile Number">
								</div>
								<div class="col-xs-2 field-edit edit_field" id="phNum__edit"></div>
							</div>
						</div>
						<div class="col-xs-6 fields-container ph-box">
							<div class="ph-container">
								<div class="col-xs-10 field-box">
									<label>Alternate Mobile Number</label>
									<input type="text" class="form-control" oninput="checkfield();" readonly name="altphNum" id="altphNum" value="" placeholder="Alternate Mobile Number">
								</div>
								<div class="col-xs-2 field-edit edit_field" id="altphNum__edit"></div>
							</div>
						</div>
						<div class="col-xs-12 fields-container" id="cityselection" style="display:none;">
							<div class="col-xs-10 field-box">
								<label>City</label><br>
								<select class="form-control styled-select2" id="city" onchange="cityChanged();" name="city">
									<option value="" disabled selected style='display:none;'>--&nbsp;&nbsp;Select City&nbsp;&nbsp;--</option>
									<?php
										if(isset($cities) && isset($sc_details)) {
											foreach ($cities as $city) {
												echo '<option value="'.$city->CityId.'">'.convert_to_camel_case($city->CityName).'</option>';
											}
										}
									?>
								</select>
							</div>
						</div>
						<div class="col-xs-12">
							<?php
								if (isset($sc_details)) {
									$readonly = ' readonly="true"';
								} else {
									$readonly = '';
								}
							?>
							<div class="col-xs-10 field-box">
								<label>Address</label><br>
							</div>
							<div class="col-xs-12 addr-block-ua" id="addr-block">
								<div class="form-group col-xs-6">
									<input type="text" id="addr1"<?php echo $readonly; ?> style="margin-left: -8px;" class="form-control" oninput="checkfield();" name="adln1" id="adln1" placeholder="Address Line1">
								</div>
								<div class="form-group col-xs-6">
									<input type="text" id="addr2"<?php echo $readonly; ?> style="margin-left: -8px;" class="form-control" oninput="checkfield();" name="adln2" id="adln2" placeholder="Address Line2">
								</div>
								<div class="form-group col-xs-6 loc-1">
									<input type="text" id="addr_location"<?php echo $readonly; ?> style="margin-left: -8px;" class="form-control area" oninput="checkfield();" name="location" placeholder="Location">
								</div>
								<div class="form-group col-xs-6">
									<input type="text" id="addr_landmark"<?php echo $readonly; ?> style="margin-left: -8px;" class="form-control" oninput="checkfield();" name="landmark" placeholder="Landmark (Optional)">
									<input type="hidden" id="sc_addr_slit_id" value="">
								</div>
							</div>
							<div class="col-xs-12 addr-links">
								<p class="addr-block-open-ua" id="add-new-addr">Edit Address</p>
							</div>
							<?php
							if(isset($sc_details)) {
								echo '<div class="user-addr-content" id="addr_content" style="display:none;">
									<div>' . convert_to_camel_case($sc_details['AddrLine1']) . '</div>
									<div>' . convert_to_camel_case($sc_details['AddrLine2']) . '</div>
									<div>' . $sc_details['LocationName'] . '</div>
									<div>' . convert_to_camel_case($sc_details['Landmark']) . '</div>
									<div>' . $sc_details['CityId'] . '</div>
									<div>' . convert_to_camel_case($sc_details['ScName']) . '</div>
									<div>' . convert_to_camel_case($sc_details['Owner']) . '</div>
									<div>' . $sc_details['Email'] . '</div>
									<div>' . $sc_details['Landline'] . '</div>
									<div>' . $sc_details['Phone'] . '</div>
									<div>' . $sc_details['Latitude'] . '</div>
									<div>' . $sc_details['Longitude'] . '</div>
									<div>' . $sc_details['ScAddrSplitId'] . '</div>
									<div>' . $sc_details['AltPhone'] . '</div>
								</div>';
							}
							?>
						</div>
						<div class="col-xs-12 fields-container">
							<div class="col-xs-10 field-box">
								<div class="col-xs-6">
									<label>Latitude</label>
									<input type="text" class="col-xs-6 form-control" oninput="checkfield();" readonly name="lat_num" id="lat_num" placeholder="Latitude">
								</div>
								<div class="col-xs-6">
									<label>Longitude</label>
									<input type="text" class="col-xs-6 form-control" oninput="checkfield();" readonly name="lon_num" id="lon_num" placeholder="Longitude">
								</div>
							</div>
							<div class="col-xs-2 field-edit edit_lat_lon" id="lat_lon_edit"></div>
						</div>
						<div class="button-box-contact col-xs-12">
							<div class="button-container col-xs-6 col-md-offset-5">
								<button class='next btn waves-effect waves-light btn-flat btnUpdate-pu' id="SubmitBtn" disabled>
									Update
								</button>
								<button class='next btn waves-effect waves-light btn-flat btnCancel-pu' id="CancelBtn" >
									Cancel
								</button>
							</div>
						</div>
					</div>
				</section>
				<section class="contact-content">
					<div class="col-xs-12 fields-update-container">
						<div class="pickup-title teal-bold">Service Center Media</div>
						<div class="sub-title-grey">Update Logo, Images of your Service Center</div><br><br>
						<form method="POST" action="/vendor/profile/updateScMedia" enctype="multipart/form-data">
						<div class="row" id="media_details">
							<div class="col s12">
								<label>Logo</label>
							</div>
							<div class="row auto-overflow vendor-image-section vreg-logo-sec">
								<div class="col s12 m4 media-upload">
									<label for="uploadImage_0">
										<img id="uploadPreview0"<?php if(isset($sc_media_data[0])) { echo ' ' . $sc_media_data[0]; } ?> style="width: 100px; height: 100px;" />
										<div id="nii0" class="no-image-icon"><i class="material-icons"><?php if(!isset($sc_media_data[0])) { echo 'collections'; } ?></i></div>
									</label>
									<input data-type="image" data-mandatory="false" data-error="image" id="uploadImage_0" class="mediaUpload" data-id="0" type="file" name="uploadImage_0" style="display:none" />
								</div>
							</div>
						</div>
						<div class="row" id="media_details">
							<div class="col s12">
								<label>Media</label>
							</div>
							<div class="row auto-overflow vendor-image-section">
								<?php for($i = 1; $i <= 6; $i++) { ?>
								<div class="col s12 m4 media-upload">
									<label for="uploadImage_<?php echo $i; ?>">
										<img id="uploadPreview<?php echo $i; ?>"<?php if(isset($sc_media_data[$i])) { echo ' ' . $sc_media_data[$i]; } ?> style="width: 100px; height: 100px;" />
										<div id="nii<?php echo $i; ?>" class="no-image-icon"><i class="material-icons"><?php if(!isset($sc_media_data[$i])) { echo 'collections'; } ?></i></div>
									</label>
									<input data-type="image" data-mandatory="false" data-error="image" id="uploadImage_<?php echo $i; ?>" class="mediaUpload" data-id="<?php echo $i; ?>" type="file" name="uploadImage_<?php echo $i; ?>" style="display:none" />
								</div>
								<?php } ?>
							</div>
						</div>
						<div class="button-box-contact col-xs-12">
							<div class="button-container col-xs-6 col-md-offset-5">
								<button type="submit" class='next btn waves-effect waves-light btn-flat btnUpdate-pu' id="mediaSubmitBtn" disabled>
									Update Media
								</button>
							</div>
						</div>
						</form>
					</div>
				</section>
			</aside><!-- /.right-side -->
		</div>
	<?php $this->load->view('vendor/components/_foot'); ?>
	<?php } ?>
<?php $this->load->view('vendor/components/_vjs'); ?>
<script type="text/javascript" src="<?php echo site_url('js/vprofile.js'); ?>"></script>
<script type="text/javascript">
</script>
</body>
</html>