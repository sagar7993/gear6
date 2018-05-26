<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Panel - PickUp Areas</title>
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
						PickUp Areas Info
						<small>Areas,Radius &amp; Price Chart</small>
					</h1>
				</section>
				<section class="area-content">
					<div class="col-xs-12 area-box">
						<div class="pickup-title teal-bold">Pick Up Areas by Location</div>
						<div class="col-xs-12 area-container loc-container">
							<div class="col-xs-4">
								<input type="text" class="col-xs-6 form-control location" readonly name="area1_name" id="area1_name" placeholder="Area Location">
							</div>
							<div class="col-xs-2">
								<select class="form-control styled-select" disabled name="area1_type" onchange="checkfield();" id="area1_type">
									<option value="pick">PickUp</option>
									<option value="drop">Drop</option>
									<option selected value="both">PickUp &amp; Drop</option>
								</select>
							</div>
							<div class="col-xs-3">
								<input type="text" class="col-xs-10 form-control" readonly name="area1_price" id="area1_price" oninput="checkfield();" placeholder="Price in INR">
							</div>
							<div class="col-xs-2 field-area-edit area_edit" id="area_1_edit"></div>
						</div>
						<div class="col-xs-12 area-container loc-container">
							<div class="col-xs-4">
								<input type="text" class="col-xs-6 form-control location" onchange="checkfield();" readonly name="area2_name" id="area2_name" placeholder="Area Location">
							</div>
							<div class="col-xs-2">
								<select class="form-control styled-select" disabled name="area2_type" id="area2_type">
									<option value="pick">PickUp</option>
									<option value="drop">Drop</option>
									<option selected value="both">PickUp &amp; Drop</option>
								</select>
							</div>
							<div class="col-xs-3">
								<input type="text" class="col-xs-10 form-control" readonly name="area2_price" id="area2_price" oninput="checkfield();" placeholder="Price in INR">
							</div>
							<div class="col-xs-2 field-area-edit area_edit" id="area_2_edit"></div>
						</div>
						<div class="col-xs-12 area-container loc-container">
							<div class="col-xs-4">
								<input type="text" class="col-xs-6 form-control location" onchange="checkfield();" readonly name="area3_name" id="area3_name" placeholder="Area Location">
							</div>
							<div class="col-xs-2">
								<select class="form-control styled-select" disabled name="area3_type" id="area3_type">
									<option value="pick">PickUp</option>
									<option value="drop">Drop</option>
									<option selected value="both">PickUp &amp; Drop</option>
								</select>
							</div>
							<div class="col-xs-3">
								<input type="text" class="col-xs-10 form-control" readonly name="area3_price" id="area3_price" oninput="checkfield();" placeholder="Price in INR">
							</div>
							<div class="col-xs-2 field-area-edit area_edit" id="area_3_edit"></div>
							<div class="col-xs-2 field-area-add" id="area_add"></div>
						</div>
					</div>
					<div class="button-content col-xs-12 center">
						<div class="button-box col-xs-12">
							<div class="button-container col-xs-12">
								<button class='next btn waves-effect waves-light btn-flat btnUpdate-pu right' id="apupdate" disabled>
									Update
								</button>
							</div>
						</div>
					</div>
				</section>
				<?php if(isset($radii_prices) && count($radii_prices) >=2 ) { ?>
				<section class="radius-content">
					<?php $pick_type = $radii_prices[0]['Type']; ?>
					<div class="col-xs-12 radius-box">
						<div class="pickup-title teal-bold">Pick Up Areas by Radius</div>
						<div class="col-xs-12 area-container">
							<div class="col-xs-4 col-xs-offset-4">
								<select class="form-control styled-select" name="type_for_radii" id="type_for_radii" onchange="checkfield1();">
									<option <?php if($pick_type == 'pick') { echo 'selected '; } ?>value="pick">PickUp</option>
									<option <?php if($pick_type == 'drop') { echo 'selected '; } ?>value="drop">Drop</option>
									<option <?php if($pick_type == 'both') { echo 'selected '; } ?>value="both">PickUp &amp; Drop</option>
								</select>
							</div>
						</div>
						<?php $rcount = 0; $count_check = count($radii_prices) - 1; foreach($radii_prices as $rprice) { if($rcount < $count_check) { ?>
						<div class="col-xs-12 area-container rad-container">
							<div class="col-xs-1">
								<input type="text" class="col-xs-6 form-control from-val" readonly name="rad<?php echo ($rcount + 1); ?>_kms" id="rad<?php echo ($rcount + 1); ?>_kms" placeholder="kms" value="<?php echo $rprice['SDistance']; ?>">
							</div>
							<div class="col-xs-2">
								<div class="radius-from col-xs-2">to</div>
								<div class="col-xs-8">
									<input type="hidden" class="secret_end_distance_select" value="<?php echo $rprice['EDistance']; ?>">
									<select class="form-control styled-select to-val" disabled id="to_val_<?php echo ($rcount + 1); ?>" name="to_val_<?php echo ($rcount + 1); ?>">
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
									</select>
								</div>
							</div>
							<div class="col-xs-4 width29">
								<div class="radius-from">kms&nbsp;&nbsp;&nbsp;Radius From :</div>
								<div class="radius-from-location"><?php if(isset($sc_location)) { echo $sc_location; } ?></div>
							</div>
							<div class="col-xs-3">
								<input type="text" class="col-xs-6 form-control" oninput="checkfield1();" readonly name="rad<?php echo ($rcount + 1); ?>_price" id="rad<?php echo ($rcount + 1); ?>_price" value="<?php echo $rprice['Price']; ?>" placeholder="Price in INR">
							</div>
							<div class="col-xs-2 field-area-edit rad_edit" id="rad_edit_<?php echo ($rcount + 1); ?>"></div>
							<?php if($rcount == $count_check - 1) { ?>
								<div class="col-xs-2 field-area-add" id="rad_add"></div>
							<?php } ?>
						</div>
						<?php $rcount += 1; } } ?>
						<div class="col-xs-12 area-container last-box">
							<div class="col-xs-1 margin-top-10px">
								Greater
							</div>
							<div class="col-xs-2">
								<div class="radius-from col-xs-2">than</div>
								<div class="col-xs-8">
									<input type="hidden" class="secret_end_distance_select" value="<?php echo $radii_prices[$count_check]['SDistance']; ?>">
									<select class="form-control styled-select to-val" disabled id="to_val_last" name="to_val_last">
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
									</select>
								</div>
							</div>
							<div class="col-xs-4 width29">
								<div class="radius-from">kms&nbsp;&nbsp;&nbsp;Radius From :</div>
								<div class="radius-from-location"><?php if(isset($sc_location)) { echo $sc_location; } ?></div>
							</div>
							<div class="col-xs-3">
								<input type="text" class="col-xs-6 form-control" oninput="checkfield1();" readonly name="rad_last_price" id="rad_last_price" value="<?php echo $radii_prices[$count_check]['Price']; ?>" placeholder="Price in INR">
							</div>
							<div class="col-xs-2 field-area-edit rad_edit" id="rad_edit_last"></div>
						</div>
					</div>
					<div class="button-content col-xs-12 center">
						<div class="button-box col-xs-12">
							<div class="button-container col-xs-12">
								<button class='next btn waves-effect waves-light btn-flat btnUpdate-pu right' id="rpupdate" disabled>
									Update
								</button>
							</div>
						</div>
					</div>
				</section>
				<?php } else { ?>
				<section class="radius-content">
					<div class="col-xs-12 radius-box">
						<div class="pickup-title teal-bold">Pick Up Areas by Radius</div>
						<div class="col-xs-12 area-container">
							<div class="col-xs-4 col-xs-offset-4">
								<select class="form-control styled-select" name="type_for_radii" id="type_for_radii">
									<option value="pick">PickUp</option>
									<option value="drop">Drop</option>
									<option selected value="both">PickUp &amp; Drop</option>
								</select>
							</div>
						</div>
						<div class="col-xs-12 area-container rad-container">
							<div class="col-xs-1">
								<input type="text" class="col-xs-6 form-control from-val" readonly name="rad1_kms" id="rad1_kms" placeholder="kms" value="0">
							</div>
							<div class="col-xs-2">
								<div class="radius-from col-xs-2">to</div>
								<div class="col-xs-8">
									<select class="form-control styled-select to-val" disabled id="to_val_1" name="to_val_1">
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5" selected>5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
									</select>
								</div>
							</div>
							<div class="col-xs-4 width29">
								<div class="radius-from">kms&nbsp;&nbsp;&nbsp;Radius From :</div>
								<div class="radius-from-location"><?php if(isset($sc_location)) { echo $sc_location; } ?></div>
							</div>
							<div class="col-xs-3">
								<input type="text" class="col-xs-6 form-control" oninput="checkfield1();" readonly name="rad1_price" id="rad1_price" placeholder="Price in INR">
							</div>
							<div class="col-xs-2 field-area-edit rad_edit" id="rad_edit_1"></div>
						</div>
						<div class="col-xs-12 area-container rad-container">
							<div class="col-xs-1">
								<input type="text" class="col-xs-6 form-control from-val"  readonly name="rad2_kms" id="rad2_kms" placeholder="kms" value="5">
							</div>
							<div class="col-xs-2">
								<div class="radius-from col-xs-2">to</div>
								<div class="col-xs-8">
									<select class="form-control styled-select to-val" disabled id="to_val_2" name="to_val_2">
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10" selected>10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
									</select>
								</div>
							</div>
							<div class="col-xs-4 width29">
								<div class="radius-from">kms&nbsp;&nbsp;&nbsp;Radius From :</div>
								<div class="radius-from-location"><?php if(isset($sc_location)) { echo $sc_location; } ?></div>
							</div>
							<div class="col-xs-3">
								<input type="text" class="col-xs-6 form-control" oninput="checkfield1();" readonly name="rad2_price" id="rad2_price" placeholder="Price in INR">
							</div>
							<div class="col-xs-2 field-area-edit rad_edit" id="rad_edit_2"></div>
						</div>
						<div class="col-xs-12 area-container  rad-container">
							<div class="col-xs-1">
								<input type="text" class="col-xs-6 form-control from-val"  readonly name="rad3_kms" id="rad3_kms" placeholder="kms" value="10">
							</div>
							<div class="col-xs-2">
								<div class="radius-from col-xs-2">to</div>
								<div class="col-xs-8">
									<select class="form-control styled-select to-val" disabled id="to_val_3" name="to_val_3">
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15" selected>15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
									</select>
								</div>
							</div>
							<div class="col-xs-4 width29">
								<div class="radius-from">kms&nbsp;&nbsp;&nbsp;Radius From :</div>
								<div class="radius-from-location"><?php if(isset($sc_location)) { echo $sc_location; } ?></div>
							</div>
							<div class="col-xs-3">
								<input type="text" class="col-xs-6 form-control" oninput="checkfield1();" readonly name="rad3_price" id="rad3_price" placeholder="Price in INR">
							</div>
							<div class="col-xs-2 field-area-edit rad_edit" id="rad_edit_3"></div>
							<div class="col-xs-2 field-area-add" id="rad_add"></div>
						</div>
						<div class="col-xs-12 area-container last-box">
							<div class="col-xs-1 margin-top-10px">
								Greater
							</div>
							<div class="col-xs-2">
								<div class="radius-from col-xs-2">than</div>
								<div class="col-xs-8">
									<select class="form-control styled-select to-val" disabled id="to_val_last" name="to_val_last">
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15" selected>15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
									</select>
								</div>
							</div>
							<div class="col-xs-4 width29">
								<div class="radius-from">kms&nbsp;&nbsp;&nbsp;Radius From :</div>
								<div class="radius-from-location"><?php if(isset($sc_location)) { echo $sc_location; } ?></div>
							</div>
							<div class="col-xs-3">
								<input type="text" class="col-xs-6 form-control" oninput="checkfield1();" readonly name="rad_last_price" id="rad_last_price" placeholder="Price in INR">
							</div>
							<div class="col-xs-2 field-area-edit rad_edit" id="rad_edit_last"></div>
						</div>
					</div>
					<div class="button-content col-xs-12 center">
						<div class="button-box col-xs-12">
							<div class="button-container col-xs-12">
								<button class='next btn waves-effect waves-light btn-flat btnUpdate-pu right' id="rpupdate" disabled>
									Update
								</button>
							</div>
						</div>
					</div>
				</section>
				<?php } ?>
				<div class='callout callout-info' style="margin-top:22px;">
					<h5>Location Based Charges</h5>
					<p>List of all the Locations with corresponding PickUp / Drop prices allotted</p>
				</div>  
				<div id="detail_tab1" class="table-margin-top margin-bottom-0">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-location-arrow"></i> &nbsp;&nbsp;Location</th>
								<th><i class="fa fa-cogs"></i> &nbsp;&nbsp;Type</th>
								<th><i class="fa fa-inr"></i> &nbsp;&nbsp;Price</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Last Updated</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($fixarea_prices)) { foreach($fixarea_prices as $fixarea_price) { ?>
							<tr id="<?php echo $fixarea_price['PickPriceId']; ?>">
								<td><?php echo convert_to_camel_case($fixarea_price['LocationName']); ?></td>
								<td><?php echo $fixarea_price['Type']; ?></td>
								<td><?php echo $fixarea_price['Price']; ?></td>
								<td><?php echo $fixarea_price['Timestamp']; ?></td>
							</tr>
							<?php } } ?>
						</tbody>
					</table>
				</div>
			</aside><!-- /.right-side -->
		</div>
	<?php $this->load->view('vendor/components/_foot'); ?>
	<?php } ?>
<?php $this->load->view('vendor/components/_vjs'); ?>
<script type="text/javascript" src="<?php echo site_url('js/vpickareas.js'); ?>"></script>
<script type="text/javascript">
var availableLocations = [<?php if (isset($areas)) { echo $areas; } ?>];
</script>
</body>
</html>