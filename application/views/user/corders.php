<!DOCTYPE html>
<html class="no-scroll">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Order History Page (<?php echo convert_to_camel_case($this->session->userdata('name')); ?>)</title>
	<link rel="shortcut icon" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/materialize.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/ionicons.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/module.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/custom-jquery.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/jQueryUI/jquery-ui.css'); ?>">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/ustyle.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/flatui.css'); ?>">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Raleway" type="text/css">
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/estyle.css'); ?>">
	<link rel="stylesheet" type="text/css" href="/nhome/js/lib/swal/sweetalert.css">
	<style type="text/css">
		.input-field label {
			top: 0.1rem;
			left: 3rem;
		}
		.login_btn {
			width: 50%;
		}
		.modal-backdrop.in {
		    opacity: 0;
		}
		.modal {
			max-height: 100%;
			margin-top: -1%;
		}
		.btn.btn-primary {
			background-color: black!important;
			opacity: 0.5;
		}
		.modal .modal-footer {
			width: 50%;
			margin-left: 23%;
		}
		.ratingContainer {
			padding: 7px;
		}
		.text-area {
			margin-top: 10px;
		}
		textarea::-webkit-input-placeholder {
			color: white !important;
		}		 
		textarea:-moz-placeholder { /* Firefox 18- */
			color: white !important;  
		}
		textarea::-moz-placeholder {  /* Firefox 19+ */
			color: white !important;  
		}
		textarea:-ms-input-placeholder {  
			color: white !important;  
		}
	</style>
</head>
<body>
	<div class="load-wrap">
		<div class="preloader-wrapper big active center loader1" >
			<div class="spinner-layer spinner-green-only">
				<div class="circle-clipper left">
					<div class="circle"></div>
				</div>
				<div class="gap-patch">
					<div class="circle"></div>
				</div>
				<div class="circle-clipper right">
					<div class="circle"></div>
				</div>
			</div>
		</div>
	</div>
	<?php if(isset($serid) && $serid != 3) { if (isset($scenter) && isset($OId)) { foreach ($scenter as $sc) { ?>
		<div class="modal corderModal" id="<?php echo 'feedback_' . $OId; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header custom-modal-header">
						<button type="button" class="close ratingCloseEvent" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Feedback to <?php echo " ".convert_to_camel_case($sc['ScName']); ?></h4>
					</div>
					<div class="modal-body">
						<input type="hidden" class="question_count" value="<?php echo count($feedback); ?>" />
						<?php foreach($feedback as $row) { ?>
							<div class="col-xs-12 ratingContainer">
								<div class="col-xs-8 ratingTitle"><?php echo $row['FbQName']; ?></div>
								<div class="col-xs-4 feedbackRating ratingEvent" id="question_<?php echo $OId; ?>_<?php echo $row['ExecFbQId']; ?>"></div>
							</div>
						<?php } ?>
						<div class="col-xs-12">
							<textarea class="form-group col-xs-12 text-area fb_desc fb_desc_corder" placeholder="Enter the feedback description (Optional)" style="color:white;"></textarea>
						</div>
						<input type="hidden" class="fb_sc_id" value="<?php echo $sc['ScId']; ?>" />
						<input type="hidden" class="fb_oid" value="<?php echo $OId; ?>" />
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary fb_submit center" align="center" disabled="true">Submit</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	<?php } } } ?>
	<?php if($is_canres_enabled) { ?>
		<div class="modal" id="rescheduleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content flat-modal">
					<div class="modal-header custom-modal-header">
						<a class="close sClose modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></a>
						<h4 class="modal-title">Reschedule Current Order</h4>
					</div>
					<form method="POST" action="/user/account/rescheduleOrder">
					<div class="modal-body">
						<div class="col-xs-12" >
							<div class="form-group">
								<p>
									<input type="text" onchange="checkreschedule();" id="rs_date" class="form-control dpDate modalInput" name="rs_date" readonly="true" style="cursor:pointer;" placeholder="Appointment Date">
								</p>
							</div>
						</div>
						<div class="col-xs-12">
							<textarea class="form-group col-xs-12 modalInput text-area" oninput="checkreschedule();" name="rs_reason" id="rs_reason" placeholder="Reason for Reschedule"></textarea>
						</div>
					</div>
					<input type="hidden" name="rs_order_id" id="rs_order_id" />
					<div class="modal-footer">
						<button type="submit" id="rs_submit" class="btn login_btn waves-effect waves-light" disabled>Reschedule</button>
					</div>
					</form>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	<?php } ?>
	<div class="modal" id="contactVendor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content flat-modal">
				<div class="modal-header custom-modal-header">
					<a class="close sClose modal-close" id="closeM" aria-label="Close"><span aria-hidden="true">X</span></a>
					<?php
						if (isset($scenter)) {
							foreach ($scenter as $sc) {
								echo '<h4 class="modal-title">Contact&nbsp;: ' . convert_to_camel_case($sc['ScName']) . '</h4>';
							}
						}
					?>
				</div>
				<div class="modal-body signup-modal-body">
					<div class="row">
						<?php if(isset($serid) && $serid != 3) { if(isset($scaddress)) { 
							echo '<div class="col s12 font-bold">' . $scaddress . '</div>';
						} }
						?>
						<div class="col s12 font-bold margin-top-10px center-align">
							<?php if(isset($scenter)) { foreach($scenter as $sc) { ?>
								<div class="col s12 emModalTitle"><div><span class="white-font"><strong><?php echo $sc['ScName']; ?> - Mobile:&nbsp;</strong></span><?php echo $sc['Phone']; ?></div></div>
							<?php } } ?>
						</div>
						<div class="col s12 margin-top-10px">
							<textarea class="col-xs-12 text-area modalInput" name="comments" id="cv_comments" placeholder="Type your message here"></textarea>
						</div>
						<div class="s12 center-align">
							<button type="button" class="btn login_btn waves-effect waves-light">Send</button>
						</div>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<div class="modal" id="contactMR" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content flat-modal">
				<div class="modal-header custom-modal-header">
					<a class="close sClose modal-close" id="closeM" aria-label="Close"><span aria-hidden="true">X</span></a>
					<h4 class="modal-title">Contact - <?php if(isset($site_name)) { echo $site_name; } ?></h4>
				</div>
				<div class="modal-body signup-modal-body">
				<div class="row">
					<div class="col s12 font-bold center-align">
						<div class="col s12 emModalTitle"><div><span class="white-font"><strong>Contact Number :</strong></span> +91-9494845111</div></div>
					</div>
					<div class="col s12 margin-top-10px">
						<textarea class="col-xs-12 text-area modalInput" name="comments" id="cmr_comments" placeholder="Type your message here"></textarea>
					</div>
					<div class="col s12 center-align">
						<button type="button" class="btn login_btn waves-effect waves-light">Send</button>
					</div>
				</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<?php $this->load->view('user/components/__head'); ?>
	<?php $this->load->view('user/components/_sidebar'); ?>
	<aside class="right-side1">
		<section class="user-header-container">
			<div class="uhBox">
				<div class="uhNL">
					<div class="uhName">
						<i class="material-icons left">account_circle</i>
						<span class="user-profile-name"><?php echo convert_to_camel_case($this->session->userdata('name')); ?></span>
					</div>
					<?php if($user_addresses[0]['AddrLine1'] != "") { ?>
					<div class="uhLoc clearfix">
						<i class="material-icons">pin_drop</i>
						<span><?php echo convert_to_camel_case($user_addresses[0]['AddrLine1']) . " , " . convert_to_camel_case($user_addresses[0]['LocationName']); ?></span>
					</div>
					<?php } ?>
				</div>
				<div class="uhPE">
					<div class="uhPhone">
						<i class="material-icons">phonelink_ring</i>
						<span>&nbsp;<?php echo $user_addresses[0]['Phone']; ?></span>
					</div>
					<div class="uhPhone">
						<i class="material-icons">email</i>
						<span>&nbsp;<?php echo $user_addresses[0]['Email']; ?></span>
					</div>
				</div>
				<div class="uhEB">
					<div class="uhEd">
						<div class="uhEdit">
							<i class="material-icons">border_color</i>
							<span><a href="<?php echo base_url('user/account/uprofile'); ?>" class="white-font">Edit</a></span>
						</div>
						<div class="uhEdit">
							<i class="material-icons">loupe</i>
							<span><a class="white-font" href="<?php echo site_url('user/userhome'); ?>">Book New</a></span>
						</div>
					</div>
				</div>
				<div></div>
				<div></div>
				<div></div>
			</div>
		</section>
		<div class="bc">
			<div class="bcItem"><a href="<?php echo site_url('user/userhome'); ?>">Home</a></div>
			<span class="arrw">>></span>
			<div class="bcItem active-bc">My Orders</div>
		</div>
		<div class="user-order-block">
		<?php if(isset($aorders['OIds']) && count($aorders['OIds']) > 0) { ?>
		<ul class="collapsible" data-collapsible="accordion">
			<li>
				<div class="collapsible-header"><i class="material-icons">local_activity</i>Active Orders - <span class="selected-options"><?php echo count($aorders['OIds']); ?></span></div>
				<div class="collapsible-body">
					<ul class="collapsible popout" data-collapsible="accordion">
						<?php $count = 0; foreach($aorders['OIds'] as $oid) { ?>
						<li>
							<div class="collapsible-header"><i class="material-icons">more</i>
								<div class="uhOrder-inline">
									<div class="uhOid">
										<b>Order ID&nbsp;:</b>
										<span>&nbsp;<?php echo $oid; ?>&nbsp;-&nbsp;<?php echo $aorders['stypes'][$count]; ?></span>
									</div>
								</div>
							</div>
							<?php if($aorders['serid'][$count] != 3) { ?>
							<div class="collapsible-body">
								<div class="review-options-container row">
									<div class="col s12 m6 l3 right-dot-margin padding-10px center-align">
										<label for="exampleInputPassword1">Order ID</label><br/>
										<label class="selected-options"><?php echo $oid; ?></label>
									</div>
									<div class="col s12 m6 l3 right-dot-margin padding-10px center-align">
										<label for="exampleInputPassword1">Service Center</label><br/>
										<label class="selected-options"><?php echo $aorders['scenters'][$count][0]['ScName']; ?></label>
									</div>
									<div class="col s12 m6 l3 right-dot-margin padding-10px center-align">
										<label for="exampleInputPassword1">Service Type</label><br/>
										<label class="selected-options"><?php echo $aorders['stypes'][$count]; ?></label>
									</div>
									<div class="col s12 m6 l3 padding-10px center-align">
										<label for="exampleInputPassword1">Bike Model</label><br/>
										<label class="selected-options"><?php echo $aorders['bikemodels'][$count]; ?></label>
									</div>
									<div class="col s12 m6 margin-top-22px center-align">
										<label for="exampleInputPassword1">Time Slot</label><br/>
										<label class="selected-options"><?php echo $aorders['timeslots'][$count]; ?></label>
									</div>
									<div class="col s12 m6 margin-top-22px center-align">
										<label for="exampleInputPassword1"></label><br/>
										<label class="selected-options text-underline"><i class="material-icons left">track_changes</i><a class="trackOrder" data-oid="<?php echo $oid; ?>">Track Order</a></label> 
									</div>
								</div>
							</div>
							<?php } else { ?>
							<div class="collapsible-body">
								<div class="review-options-container row">
									<div class="col s12 m6 l4 right-dot-margin padding-10px center-align">
										<label for="exampleInputPassword1">Order ID</label><br/>
										<label class="selected-options"><?php echo $oid; ?></label>
									</div>
									<div class="col s12 m6 l4 right-dot-margin padding-10px center-align">
										<label for="exampleInputPassword1">Service Type</label><br/>
										<label class="selected-options"><?php echo $aorders['stypes'][$count]; ?></label>
									</div>
									<div class="col s12 m6 l4 padding-10px center-align">
										<label for="exampleInputPassword1">Bike Model</label><br/>
										<label class="selected-options"><?php echo $aorders['bikemodels'][$count]; ?></label>
									</div>
									<div class="col s12 m6 margin-top-22px padding-10px center-align">
										<label for="exampleInputPassword1">Service Centers</label><br/>
										<?php foreach($aorders['scenters'][$count] as $sc) { ?>
										<label class="selected-options">&nbsp;&nbsp;<?php echo convert_to_camel_case($sc['ScName']); ?></label>
										<?php } ?>
									</div>
									<div class="col s12 m6 margin-top-22px center-align">
										<label for="exampleInputPassword1"></label><br/>
										<label class="selected-options text-underline"><i class="material-icons left">track_changes</i><a class="trackOrder" data-oid="<?php echo $oid; ?>">Track Order</a></label> 
									</div>
									<div class="col s12 margin-top-22px padding-20px center-align">
										<label for="exampleInputPassword1">Query</label><br/>
										<label class="selected-options"><?php echo $aorders['scenters'][$count][0]['ServiceDesc1'] . ' - ' . $aorders['scenters'][$count][0]['ServiceDesc2']; ?></label>
									</div>
								</div>
							</div>
							<?php } ?>
						</li>
						<?php $count += 1; } ?>
					</ul>
				</div>
			</li>
		</ul>
		<?php } ?>
		<?php if(isset($OId) && $OId != "") { ?>
		<div class="uhOrder">
			<div class="uhOid">
				<b>Order ID&nbsp;:</b>
				<span>&nbsp;<?php echo $OId; ?></span>
			</div>
		</div>
		<?php } ?>
		<?php if(isset($OId) && $OId != "") { ?>
		<section class="section-center2">
			<div class="section-header-1">
				<span class="confirm-title">Order Status</span>
				<?php
					if(isset($serid) && $serid != 3) {
						if (isset($scenter) && isset($OId)) {
							foreach ($scenter as $sc) {
								echo '<span class="rateItLink" style="margin-top:10px;" data-toggle="modal" data-target="#feedback_' . $OId  . '" data-order="' . $OId . '">Rate the Service [' . convert_to_camel_case($sc['ScName']) . ']</span>';
							}
						}
					}
				?>
			</div>
			<div class="section-content-action1">
				<?php if(empty($mr_remarks) || $mr_remarks == "") { ?>
				<?php if(isset($serid) && $serid == 1 && isset($statuses) && isset($scenter)) { ?>
				<div class="col-xs-12" id="ps-block">
					<div class="track-text-block">
						<div class="col-custom">Order Approved</div>
						<div class="col-custom">Service In Progress</div>
						<div class="col-custom">Ready To Deliver</div>
					</div>
					<div class="fconn-block">
						<div class="fconnector"></div>
						<div class="fconnector"></div>
						<div class="fconnector-last"></div>
					</div>
					<div class="fstatus-block">
						<div class="col-xs-3 padding-0">
							<div class="status-hover fStatus" id="p-status-1">
								<span class="<?php if ($statuses[0]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-1"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="col-xs-3 padding-0">
							<div class="status-hover prStatus" id="p-status-2">
								<span class="<?php if ($statuses[1]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-2"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="col-xs-3 padding-0">
							<div class="status-hover prStatusC" id="p-status-3">
								<span class="<?php if ($statuses[2]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-3"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="col-xs-3 padding-0">
							<div class="status-hover prStatus" id="p-status-4">
								<span class="<?php if ($statuses[3]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-4"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="status-hover-block">
						<div class="status-strip-content">
							<div class="status-hover-container">
								<div class="status-hover-block-1" id="p-status-hover-block-1" <?php if ($statuses[0]['Order'] == $scenter[0]['Order'] || $scenter[0]['Order'] == 0) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[0]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[0]['StatusDesc2'];
											} else {
												echo $statuses[0]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="p-status-hover-block-2" <?php if ($statuses[1]['Order'] == $scenter[0]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[1]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[1]['StatusDesc2'];
											} else {
												echo $statuses[1]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="p-status-hover-block-3" <?php if ($statuses[2]['Order'] == $scenter[0]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[2]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[2]['StatusDesc2'];
											} else {
												echo $statuses[2]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="p-status-hover-block-4" <?php if ($statuses[3]['Order'] == $scenter[0]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[3]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[3]['StatusDesc2'];
											} else {
												echo $statuses[3]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
				<?php if(isset($serid) && $serid == 2 && isset($statuses) && isset($scenter)) { ?>
				<div class="col-xs-12" id="repair-block">
					<div class="track-text-block">
						<div class="col-custom">Order Approved</div>
						<div class="col-custom">Repair In Progress</div>
						<div class="col-custom">Ready To Deliver</div>
					</div>
					<div class="fconn-block">
						<div class="fconnector"></div>
						<div class="fconnector"></div>
						<div class="fconnector-last"></div>
					</div>
					<div class="fstatus-block">
						<div class="col-xs-3 padding-0">
							<div class="status-hover fStatus" id="r-status-1">
								<span class="<?php if ($statuses[0]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-1"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="col-xs-3 padding-0">
							<div class="status-hover prStatus" id="r-status-2">
								<span class="<?php if ($statuses[1]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-2"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="col-xs-3 padding-0">
							<div class="status-hover prStatusC" id="r-status-3">
								<span class="<?php if ($statuses[2]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-3"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="col-xs-3 padding-0">
							<div class="status-hover prStatus" id="r-status-4">
								<span class="<?php if ($statuses[3]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-4"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="status-hover-block">
						<div class="status-strip-content">
							<div class="status-hover-container">
								<div class="status-hover-block-1" id="r-status-hover-block-1" <?php if ($statuses[0]['Order'] == $scenter[0]['Order'] || $scenter[0]['Order'] == 0) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[0]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[0]['StatusDesc2'];
											} else {
												echo $statuses[0]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="r-status-hover-block-2" <?php if ($statuses[1]['Order'] == $scenter[0]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[1]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[1]['StatusDesc2'];
											} else {
												echo $statuses[1]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="r-status-hover-block-3" <?php if ($statuses[2]['Order'] == $scenter[0]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[2]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[2]['StatusDesc2'];
											} else {
												echo $statuses[2]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="r-status-hover-block-4" <?php if ($statuses[3]['Order'] == $scenter[0]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[3]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[3]['StatusDesc2'];
											} else {
												echo $statuses[3]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
				<?php if(isset($serid) && $serid == 4 && isset($statuses) && isset($scenter)) { ?>
				<div class="col-xs-12" id="insurance-block">
					<div class="track-text-block">
						<div class="text-center track-text">Order Approved</div>
						<div class="text-center track-text"><span class="margin-left-n10">Renewal in Progress</span></div>
						<div class="text-center track-text">Insurance Renewed</div>
					</div>
					<div class="conn-block">
						<div class="col-xs-6 connector"></div>
						<div class="col-xs-6 connector-last"></div>
					</div>
					<div class="status-block">
						<div class="rbBox-first">
							<div class="status-hover fStatus" id="i-status-1">
								<span class="<?php if ($statuses[0]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-1"  style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="rbBox-center">
							<div class="status-hover cStatus" id="i-status-2">
								<span class="<?php if ($statuses[1]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-2" style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="rbBox-last">
							<div class="status-hover lStatus" id="i-status-3">
								<span class="<?php if ($statuses[2]['Order'] <= $scenter[0]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-3" style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="status-hover-block">
						<div class="status-strip-content">
							<div class="status-hover-container">
								<div class="status-hover-block-1" id="i-status-hover-block-1" <?php if ($statuses[0]['Order'] == $scenter[0]['Order'] || $scenter[0]['Order'] == 0) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[0]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[0]['StatusDesc2'];
											} else {
												echo $statuses[0]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="i-status-hover-block-2" <?php if ($statuses[1]['Order'] == $scenter[0]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[1]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[1]['StatusDesc2'];
											} else {
												echo $statuses[1]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="i-status-hover-block-3" <?php if ($statuses[2]['Order'] == $scenter[0]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[2]['Order'] <= $scenter[0]['Order']) {
												echo $statuses[2]['StatusDesc2'];
											} else {
												echo $statuses[2]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
				<?php if(isset($serid) && $serid == 3 && isset($statuses) && isset($scenter)) { $count = 1; foreach ($scenter as $sc) { ?>
				<label for="">Service Center Name: </label>
				<label class="selected-options"><?php if(isset($scenter)) { echo convert_to_camel_case($scenter[$count - 1]['ScName']); } ?></label>
				<div class="col-xs-12" id="query-block">
					<div class="track-text-block">
						<div class="text-center track-text">Query Approved</div>
						<div class="text-center track-text">Query Check in-process</div>
						<div class="text-center track-text">Query Answered</div>
					</div>
					<div class="conn-block">
						<div class="col-xs-6 connector"></div>
						<div class="col-xs-6 connector-last"></div>
					</div>
					<div class="status-block">
						<div class="rbBox-first">
							<div class="status-hover fStatus" id="q-status-<?php echo $count . '-1'; ?>">
								<span class="<?php if ($statuses[0]['Order'] <= $scenter[$count - 1]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-<?php echo $count . '-1'; ?>" style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="rbBox-center">
							<div class="status-hover cStatus" id="q-status-<?php echo $count . '-2'; ?>">
								<span class="<?php if ($statuses[1]['Order'] <= $scenter[$count - 1]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-<?php echo $count . '-2'; ?>" style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
						<div class="rbBox-last">
							<div class="status-hover lStatus" id="q-status-<?php echo $count . '-3'; ?>">
								<span class="<?php if ($statuses[2]['Order'] <= $scenter[$count - 1]['Order']) { echo 'status-active'; } else { echo 'status-inactive'; } ?>"></span>
								<div>
									<i class="fa fa-top-chevron chevron-<?php echo $count . '-3'; ?>" style="margin-top: -7px;display:none;"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="status-hover-block">
						<div class="status-strip-content">
							<div class="status-hover-container">
								<div class="status-hover-block-1" id="q-status-hover-block-<?php echo $count . '-1'; ?>" <?php if ($statuses[0]['Order'] == $scenter[$count - 1]['Order'] || $scenter[$count - 1]['Order'] == 0) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[0]['Order'] <= $scenter[$count - 1]['Order']) {
												echo $statuses[0]['StatusDesc2'];
											} else {
												echo $statuses[0]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="q-status-hover-block-<?php echo $count . '-2'; ?>" <?php if ($statuses[1]['Order'] == $scenter[$count - 1]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[1]['Order'] <= $scenter[$count - 1]['Order']) {
												echo $statuses[1]['StatusDesc2'];
											} else {
												echo $statuses[1]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
								<div class="status-hover-block-1" id="q-status-hover-block-<?php echo $count . '-3'; ?>" <?php if ($statuses[2]['Order'] == $scenter[$count - 1]['Order']) { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
									<div class="col-xs-7 status-hover-text-block">
										<div class="status-hover-text-title">
											<?php
											if ($statuses[2]['Order'] <= $scenter[$count - 1]['Order']) {
												echo $statuses[2]['StatusDesc2'];
											} else {
												echo $statuses[2]['StatusDesc1'];
											}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<br><br>
				<?php $count += 1; } } ?>
				<?php } else { ?>
				<div class="col-xs-12">
					<?php echo $mr_remarks; ?>
				</div>
				<?php } ?>
			</section>
			<section class="section-center2">
				<div class="section-header-1">
					<span class="confirm-title">Manage Order</span>
				</div>
				<div class="section-content-action">
					<br>
					<?php if(isset($to_be_paid) && $to_be_paid > 0.01) { ?>
						<div class="row" style="margin-bottom: 0px !important;">
							<div class="col s8 m8 offset-m2 offset-s2">
								<div class="card blue-grey darken-1" style="background-color: #f6f6f5 !important;">
									<div class="card-content white-text center" style="padding: 0 0 0 0;">
										<span class="card-title" style="color: #028cbc;">Pay the balance amount: <?php echo $to_be_paid; ?> INR</span>
										<?php if(isset($to_be_paid) && $to_be_paid >= 3000) { echo '<p style="color:black;">Note : Additional 2% will be charged since your bill amount is above Rs. 3000</p>'; } ?>
										<div class="row">
											<div class="col s12 m12">
												<div class="col s12 m12" style="margin-top: 1%;">
													<div class="col s12 m12">
														<div class="checkbox payment_mode" style="display:none;"><label class="paygateway"><input type="radio" name="paymtGtw" value="1" class="paymtgtw_radio" checked/><span style="margin-left:5px">Pay Online (CC / DC / NB / Paytm)</span></label></div>
													</div>
													<input type="hidden" id="yet_to_be_paid" value="<?php echo $to_be_paid; ?>">
												</div>
											</div>
										</div>
									</div>
									<div class="card-action center">
										<button type="button" id="pordertxn" class="btn waves-effect waves-light flat-btn">Pay Amount</button>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="section-content-action-update">
					<div class="">
						<ul class="collapsible popout" data-collapsible="accordion">
							<?php if(isset($serid) && $serid != 3) { ?>
							<li>
							<div class="collapsible-header active"><i class="material-icons">local_atm</i>Pricing Details
								<?php if(FALSE && $is_amt_cfmd == 1 && $to_be_paid > 0.01) { ?>
									<div class="right text-center" style="padding-top:5px;">
										<button type="button" name="cfrmAmount" id="cfrmAmount" class="btn waves-effect waves-light flat-btn margin-bottom-20px"> Confirm Amount </button>
									</div>
								<?php } ?>
							</div>
								<div class="collapsible-body">
								<div class="price-details-container1">
										<div class="service-title-container1">Price Details</div>
										<div class="price-title-container1">Price</div>
										<div class="price-map-container1">
											<?php if(isset($estprices) && $estprices !== NULL) { ?>
											<div class="service-list-container">
												<div class="sub-price-text">Service / Amenity Details - <span>Estimated Charges</span></div>
												<?php foreach($estprices as $estprice) { if(isset($estprice['apdesc']) && isset($estprice['aprice'])) { ?>
													<div class="service-title"><?php echo convert_to_camel_case($estprice['apdesc']); ?></div>
													<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $estprice['aprice']; ?></div>
													<?php if(intval($estprice['atprice']) != 0) { ?>
														<div class="service-title"><?php echo $estprice['atdesc']; ?></div>
														<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $estprice['atprice']; ?></div>
													<?php } ?>
												<?php } } ?>
											</div>
											<div class="final-price-container">
												<i class="fa fa-inr"></i>&nbsp;<?php echo $estprices[count($estprices) - 1]['ptotal']; ?>
											</div>
											<?php } ?>
											<?php if (isset($oprices) && count($oprices) > 0) { ?>
											<div class="service-list-container">
												<div class="sub-price-text">Additional Charges</div>
												<?php foreach($oprices as $oprice) { if(isset($oprice['opdesc']) && isset($oprice['oprice'])) { ?>
													<div class="service-title"><?php echo convert_to_camel_case($oprice['opdesc']); ?></div>
													<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $oprice['oprice']; ?></div>
												<?php } } ?>
											</div>
											<div class="final-price-container">
												<i class="fa fa-inr"></i>&nbsp;<?php if(isset($oprices)) { echo $oprices[count($oprices) - 1]['ptotal']; } else { echo $estprice; } ?>
											</div>
											<?php } ?>
											<?php if (isset($discprices) && count($discprices) > 0) { ?>
												<div class="service-list-container">
													<div class="sub-price-text">Discount Details</div>
													<?php foreach($discprices as $discprice) { if(isset($discprice['apdesc']) && isset($discprice['aprice'])) { ?>
														<div class="service-title"><?php echo convert_to_camel_case($discprice['apdesc']); ?></div>
														<div class="price-text">&nbsp;-&nbsp;<i class="fa fa-inr"></i>&nbsp;<?php echo $discprice['aprice']; ?></div>
													<?php } } ?>
												</div>
												<div class="final-price-container">
													&nbsp;-&nbsp;<i class="fa fa-inr"></i>&nbsp;<?php if(isset($discprices)) { echo $discprices[count($discprices) - 1]['ptotal']; } else { echo $estprice; } ?>
												</div>
											<?php } ?>
										</div>
									</div>
									<div class="price-details-container1">
										<div class="service-title-container1">Total Price Summary</div>
										<div class="price-title-container1">Price</div>
										<div class="price-map-container1">
											<div class="service-list-container">
												<div class="service-title">Total Billed Amount</div>
												<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php if(isset($tot_billed)) { echo $tot_billed; } ?></div>
												<div class="service-title">Total Paid Amount</div>
												<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php if(isset($tot_paid)) { echo $tot_paid; } ?></div>
												<div class="service-title">Total Amount to be Paid</div>
												<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php if(isset($to_be_paid)) { echo $to_be_paid; } ?></div>
											</div>
										</div>
									</div>
									
								</div>
							</li>
							<?php } ?>
							<li>
								<div class="collapsible-header"><i class="material-icons">assignment</i>Status History</div>
									<div class="collapsible-body">
										<?php if(isset($stathists)) { ?>
										<?php if(isset($serid) && $serid != 3) { ?>
											<br/>
											<div class="col-xs-12 status-history-title-container">
												<div class="form-group col-xs-2 width20 center-align">
													<strong>Order Status</strong>
												</div>
												<div class="form-group col-xs-8 width60 center-align" >
													<strong>Status Description by Vendor</strong>
												</div>
												<div class="form-group col-xs-2 center-align">
													<strong>Updated On</strong>
												</div>
											</div>
										<?php } ?>
										<?php
										if(isset($serid) && $serid == 3) { $active_sc_id = 0; }
										foreach($stathists as $stathist) {
											if(isset($serid) && $serid == 3 && isset($scenter)) {
												foreach($scenter as $sc) {
													if($active_sc_id != $stathist['sc_id'] && $stathist['sc_id'] == $sc['ScId']) {
														echo '<label class="selected-options">&nbsp;&nbsp;&nbsp;&nbsp;' . convert_to_camel_case($sc['ScName']) . '</label>';
														echo '<br/>
															<div class="col-xs-12 status-history-title-container">
																<div class="form-group col-xs-2 width20">
																	<strong>Order Status</strong>
																</div>
																<div class="form-group col-xs-8 width60" >
																	<strong>Status Description by Vendor</strong>
																</div>
																<div class="form-group col-xs-2 ">
																	<strong>Updated On</strong>
																</div>
															</div>';
														$active_sc_id = $stathist['sc_id'];
													}
												}
											}
										?>
										<div class="col-xs-12 status-history-content-container">
											<div class="form-group col-xs-2 width20">
												<?php echo $stathist['sname']; ?>
											</div>
											<div class="form-group col-xs-8 width60 both-dot-border" >
												<?php if(isset($stathist['sdesc']) && $stathist['sdesc'] != '') { echo $stathist['sdesc']; } else { echo '&nbsp;&nbsp;'; } ?>
											</div>
											<div class="form-group col-xs-2 width20">
												<?php echo $stathist['date']; ?>
											</div>
										</div>
										<?php } } else { ?>
										<div class="col-xs-12 status-history-content-container">
											<strong>No status changes yet.</strong>
										</div>
									<?php } ?>
								</div>
							</li>
							<li>
							<div class="collapsible-header"><i class="material-icons">offline_pin</i>Order Actions</div>
								<div class="collapsible-body">
								<div class="col-xs-12 status-history-content-container">
									<div class="col-xs-4 text-center">
										<button type="submit" name="submitform" id="reschedule" data-target="rescheduleModal" class="btn waves-effect waves-light flat-btn modal-trigger" <?php if(!$is_canres_enabled) { echo 'disabled'; } ?>>Reschedule</button>
									</div>
									<div class="col-xs-4 text-center">
										<button type="submit" name="submitform" id="cvendor" data-target="contactVendor" class="btn waves-effect waves-light flat-btn modal-trigger">Contact Vendor</button>
									</div>
									<div class="col-xs-4 text-center">
										<button type="submit" name="submitform" id="cmr" data-target="contactMR" class="btn waves-effect waves-light flat-btn modal-trigger">Contact Us</button>
									</div>
								</div>
							</div>
							</li>
							<?php if(isset($serid) && $serid != 3) { ?>
							<li>
							<div class="collapsible-header"><i class="material-icons">receipt</i>Order Transactions</div>
								<div class="collapsible-body">
								<?php if(isset($ord_trans) && $ord_trans !== NULL) { ?>
										<br/>
										<div class="col-xs-12 status-history-title-container">
											<div class="form-group col-xs-2 width20">
												<strong>Transaction Id</strong>
											</div>
											<div class="form-group col-xs-6" style="width: 40%">
												<strong>Transaction Date</strong>
											</div>
											<div class="form-group col-xs-2 width20">
												<strong>Transaction Amount</strong>
											</div>
											<div class="form-group col-xs-2 width20">
												<strong>Transaction Status</strong>
											</div>
										</div>
										<?php
										foreach($ord_trans as $trans) {
										?>
										<div class="col-xs-12 status-history-content-container">
											<div class="form-group col-xs-2 width20">
												<?php echo $trans['TId']; ?>
											</div>
											<div class="form-group col-xs-6 both-dot-border" style="width: 40%">
												<?php echo $trans['TimeStamp']; ?>
											</div>
											<div class="form-group col-xs-2 width20">
												<?php echo $trans['PaymtAmt']; ?>
											</div>
											<div class="form-group col-xs-2 width20 both-dot-border">
												<?php echo $trans['PaymtStatus']; ?>
											</div>
										</div>
										<?php } } else { ?>
										<div class="col-xs-12 status-history-content-container">
											<strong>No payment transactions yet.</strong>
										</div>
									<?php } ?>
								</div>
								<?php } ?>
							</li>
							<?php if($is_jc_updated) { ?>
							<li>
								<div class="collapsible-header active"><i class="material-icons">chrome_reader_mode</i>Job Card</div>
								<div class="collapsible-body ">
									<div class="row">
										<form id="exjcform">
										<div class="col s12 m6">
											<label for="exampleInputPassword1">Standard Jobs</label><br/>
											<label class="selected-options2"><b><?php if(isset($chosen_amenities)) { echo $chosen_amenities; } else { echo 'NIL'; } ?></b></label>
										</div>
										<div class="col s12 m6">
											<label for="exampleInputPassword1">Other Jobs</label><br/>
											<label class="selected-options2"><b><?php if(isset($chosen_aservices)) { echo $chosen_aservices; } else { echo 'NIL'; } ?></b></label>
										</div>
										<div class="col s12">
											<label for="exampleInputPassword1">User Comments</label><br/>
											<label class="selected-options2"><b><?php if(isset($scenter) && (isset($scenter[0]['ServiceDesc1']) || isset($scenter[0]['ServiceDesc2']))) { echo $scenter[0]['ServiceDesc1'] . ' ' . $scenter[0]['ServiceDesc2']; } else { echo 'NIL'; } ?></b></label>
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
										<div class="col s12 m12 l12">
											<label for="cs_fuelrange">Fuel Range</label><br/>
											<p class="range-field" style="padding:0">
												<input type="range" id="cs_fuelrange" name="cs_fuelrange" min="0" max="100" readonly="true"/>
											</p>
										</div>
										<?php if(isset($jccats) && count($jccats) > 0) { foreach($jccats as $jccat) { ?>
										<div class="input-field col s12 m12 l12">
											<h5><b><?php echo $jccat['JCPName']; ?></h5></b>
											<?php if(isset($jccat['SJCCats']) && count($jccat['SJCCats']) > 0) { foreach($jccat['SJCCats'] as $category) { ?>
											<div class="input-field col s12 m4 l3">
												<input type="checkbox" class="filled-in" id="scat_<?php echo $category['JCSubCats'][0]['JCSCatId']; ?>" value="<?php echo $category['JCSubCats'][0]['JCSCatId']; ?>" onclick="return false"/>
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
																<input class="with-gap" name="<?php echo $category['JCFormName']; ?>" type="radio" id="scat_<?php echo $subcategory['JCSCatId']; ?>" value="<?php echo $subcategory['JCSCatId']; ?>" onclick="return false"/>
														      	<label for="scat_<?php echo $subcategory['JCSCatId']; ?>"><?php echo $subcategory['JCSCatName']; ?></label>
														    </div>
														<?php } else { ?>
															<div class="input-field col col s12 m6 l3">
																<input type="checkbox" class="filled-in" id="scat_<?php echo $subcategory['JCSCatId']; ?>" value="<?php echo $subcategory['JCSCatId']; ?>" onclick="return false"/>
																<label for="scat_<?php echo $subcategory['JCSCatId']; ?>"><?php echo $subcategory['JCSCatName']; ?></label>
															</div>
														<?php } ?>
													<?php } ?>
												</div>
											<?php } } ?>
										</div>
										<?php } } ?>
										<input type="hidden" name="oid" value="<?php if(isset($OId)) { echo $OId; } ?>">
										</form>
									</div>
								</div>
							</li>
							<?php } ?>
							<?php if(isset($ex_rtime_updates) && count($ex_rtime_updates) > 0) { ?>
							<li>
								<div class="collapsible-header active"><i class="material-icons">assignment</i>Executive Real-Time Updates</div>
								<div class="collapsible-body ">
									<br/>
									<div class="col-xs-12 status-history-title-container">
										<div class="form-group col-xs-2">
											<strong>Status</strong>
										</div>
										<div class="form-group col-xs-3">
											<strong>Remarks</strong>
										</div>
										<div class="form-group col-xs-3">
											<strong>Location</strong>
										</div>
										<div class="form-group col-xs-2">
											<strong>Updated By</strong>
										</div>
										<div class="form-group col-xs-2">
											<strong>Timestamp</strong>
										</div>
									</div>
									<?php foreach($ex_rtime_updates as $trans) { ?>
									<div class="col-xs-12 status-history-title-container">
										<div class="form-group col-xs-2">
											<?php echo $trans[0]; ?>
										</div>
										<div class="form-group col-xs-3 both-dot-border">
											<?php echo $trans[1]; ?>
										</div>
										<div class="form-group col-xs-3">
											<?php echo $trans[2]; ?>
										</div>
										<div class="form-group col-xs-2 both-dot-border">
											<?php echo $trans[3]; ?>
										</div>
										<div class="form-group col-xs-2">
											<?php echo $trans[4]; ?>
										</div>
									</div>
									<?php } ?>
								</div>
							</li>
							<?php } ?>
							<?php if($is_est_updated) { ?>
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
							<?php } ?>
						</ul>
					</div>
				</div>
			</section>
			<section class="section-center1">
				<div class="section-header-2">
					<span class="confirm-title">Order Details <?php if(isset($bikenumber)) { echo 'for Bike: ' . $bikenumber; } ?></span>
				</div>
				<div class="section-content1">
					<div class="congo-text">
						<span>Congratulations, Your Service Order booking has been Successfully Completed</span><br>
						<span><strong>Below are the details</strong></span>
					</div>
					<div class="review-options-container">
						<?php if(isset($serid) && $serid != 3 && $serid != 4) { ?>
							<div class="form-group col-xs-3 ht50 right-dot-margin">
						<?php } else { ?>
							<div class="form-group col-xs-4 right-dot-margin"> 
						<?php } ?>
							<label for="">Order ID</label><br>
							<label class="selected-options"><?php if(isset($OId)) { echo $OId; } ?></label>
						</div>
						<?php if(isset($serid) && $serid != 3 && $serid != 4) { ?>
						<div class="form-group col-xs-3 ht50 right-dot-margin margin-left-10px">
							<label for="">Service Center</label><br>
							<?php
								if (isset($scenter)) {
									foreach ($scenter as $sc) {
										echo '<label class="selected-options">' . convert_to_camel_case($sc['ScName']) . '</label>';
									}
								}
							?>
						</div>
						<?php } ?>
						<?php if(isset($serid) && $serid != 3 && $serid != 4) { ?>
							<div class="form-group col-xs-3 ht50 right-dot-margin">
						<?php } else { ?>
							<div class="form-group col-xs-4 right-dot-margin"> 
						<?php } ?>
							<label for="">Service Type</label><br>
							<label class="selected-options"><?php if(isset($stype)) { echo $stype; } ?></label>
						</div>
						<?php if(isset($serid) && $serid != 3) { ?>
							<div class="form-group col-xs-3 ht50">
						<?php } else { ?>
						<div class="form-group col-xs-4"> 
						<?php } ?>
							<label for="">Bike Model</label><br>
							<label class="selected-options"><?php if(isset($bikemodel)) { echo convert_to_camel_case($bikemodel); } ?></label>
						</div>
						<div class="col-xs-6">
							<label for="">Time Slot</label><br>
							<label class="selected-options"><?php if(isset($timeslot)) { echo $timeslot; } ?></label> 
						</div>
						<div class="col-xs-6 pay-mode-final">
							<label for="">Mode of Payment&nbsp;&nbsp; :&nbsp;&nbsp;</label> 
							<label class="selected-options"><?php if(isset($paymode)) { echo convert_to_camel_case($paymode); } ?></label> 
						</div>
					</div>
					<?php if(isset($serid) && ($serid == 1 || $serid == 4)) { ?>
					<br>
					<?php if(isset($scenter[0]['ServiceDesc2']) && $scenter[0]['ServiceDesc2'] != '') { ?>
					<div class="col-xs-12 query-show-block">
						<div class="col-xs-2">
							<strong>Description : </strong>
						</div>
						<div class="col-xs-10">
							<span class="selected-options"><?php echo $scenter[0]['ServiceDesc2']; ?></span>
						</div>
					</div>
					<?php } ?>
					<br>
					<?php if(isset($mr_remarks) && $mr_remarks != '') { ?>
					<div class="col-xs-12 query-show-block">
						<div class="col-xs-2">
							<strong>Order Remarks : </strong>
						</div>
						<div class="col-xs-10">
							<span class="selected-options"><?php echo $mr_remarks; ?></span>
						</div>
					</div>
					<br>
					<?php } ?>
					<?php } ?>
					<?php if(isset($serid) && $serid == 3) { ?>
					<br>
					<?php if(isset($scenter[0]['ServiceDesc1']) && $scenter[0]['ServiceDesc1'] != '') { ?>
					<div class="col-xs-12 query-show-block">
						<div class="col-xs-2">
							<strong>Query : </strong>
						</div>
						<div class="col-xs-10">
							<span class="selected-options"><?php echo $scenter[0]['ServiceDesc1']; ?></span>
						</div>
					</div>
					<?php } ?>
					<?php if(isset($scenter[0]['ServiceDesc2']) && $scenter[0]['ServiceDesc2'] != '') { ?>
					<div class="col-xs-12 query-show-block">
						<div class="col-xs-2">
							<strong>Description : </strong>
						</div>
						<div class="col-xs-10">
							<span class="selected-options"><?php echo $scenter[0]['ServiceDesc2']; ?></span>
						</div>
					</div>
					<?php } ?>
					<br>
					<?php } ?>
					<?php if(isset($serid) && $serid == 2) { ?>
					<br>
					<?php if(isset($scenter[0]['ServiceDesc1']) && $scenter[0]['ServiceDesc1'] != '') { ?>
					<div class="col-xs-12 query-show-block">
						<div class="col-xs-2">
							<strong>Repair Info : </strong>
						</div>
						<div class="col-xs-10">
							<span class="selected-options"><?php echo $scenter[0]['ServiceDesc1']; ?></span>
						</div>
					</div>
					<?php } ?>
					<?php if(isset($scenter[0]['ServiceDesc2']) && $scenter[0]['ServiceDesc2'] != '') { ?>
					<div class="col-xs-12 query-show-block">
						<div class="col-xs-2">
							<strong>Description : </strong>
						</div>
						<div class="col-xs-10">
							<span class="selected-options"><?php echo $scenter[0]['ServiceDesc2']; ?></span>
						</div>
					</div>
					<?php } ?>
					<br>
					<?php } ?>
					<?php if(isset($serid) && $serid == 4 && isset($insren_details)) { ?>
					<div class="col-xs-12">
						<br>
					</div>
					<div class="review-options-container1">
						<div class="form-group col-xs-3 right-dot-margin margin-left-10px">
							<label for="">Previous Insurer</label><br>
							<label class="selected-options"><?php echo convert_to_camel_case($insren_details['InsurerName']); ?></label>
						</div>
						<div class="form-group col-xs-3 right-dot-margin margin-left-10px">
							<label for="">Previous Policy Expiry</label><br>
							<label class="selected-options"><?php if($insren_details['ExpiryDays'] == 0) { echo 'Already Expired'; } else { echo 'Next ' . $insren_details['ExpiryDays'] . ' Days'; } ?></label>
						</div>
						<div class="form-group col-xs-3 right-dot-margin margin-left-10px">
							<label for="">Registration Year</label><br>
							<label class="selected-options"><?php echo convert_to_camel_case($insren_details['RegYear']); ?></label>
						</div>
						<div class="form-group col-xs-3  margin-left-10px">
							<label for="">Claims Made Previously</label><br>
							<label class="selected-options"><?php if($insren_details['isClaimedBefore'] == 1) { echo 'Yes'; } else { echo 'No'; } ?></label>
						</div>
					</div>
					<br>
					<?php } ?>
					<?php if(isset($omedia) && count($omedia) > 0) { ?>
						<div class="row">
							<div class="col s12 selected-options padding-10px margin-top-10px margin-bottom-10px">User Uploaded Images</div>
							<div class="col s12 lgrey-bg padding-20px">
								<?php foreach($omedia as $om) { ?>
								<div class="col s12 m4 center-align"><img class="materialboxed" height="150px" width="150px" src="<?php echo get_awss3_url('uploads/omedia/' . $om['FileType'] . '/' . $om['FileData']); ?>"></div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					<?php if(isset($chosen_amenities)) { ?>
						<div class="col-xs-12 query-show-block">
							<div class="col-xs-2">
								<strong>Selected Amenities : </strong>
							</div>
							<div class="col-xs-10">
								<span class="selected-options"><?php echo $chosen_amenities; ?></span>
							</div>
						</div>
						<br>
					<?php } ?>
					<div class="addr-container1">
						<div class="user-addr-container">
							<div class="user-addr-title">User Details</div>
							<div class="user-addr-content">
								<?php if($uaddress != "") { echo $uaddress; } else { echo '<div>' . $uname . '</div><div>' . $uemail . '</div><div>' . $uphone . '</div>'; } ?>
							</div>
						</div>
						<div class="sc-addr-container">
							<?php if(isset($serid) && $serid != 3) { ?>
								<div class="sc-addr-title">Service Centre Details</div>
							<?php } else { ?>
								<div class="sc-addr-title">Service Centre(s) Details</div>
							<?php } ?>
							<div class="sc-addr-content">
								<?php
									if(isset($serid) && $serid != 3 && $serid != 4) {
										if(isset($scaddress)) { echo $scaddress; }
									} elseif(isset($serid) && $serid == 4) {
										echo '<div>gear6.in - Phone: +91-9494845111</div>';
									} else {
										if (isset($scenter)) {
											foreach ($scenter as $sc) {
												echo '<div>' . convert_to_camel_case($sc['ScName']) . ' - Phone: ' . $sc['Phone'] . '</div>';
											}
										}
									}
								?>
							</div>
						</div>
					</div>
					<br>
				</div>
			</section>
			<?php if(isset($estprices) && $estprices !== NULL) { ?>
			<div class="price-details-container1">
				<div class="service-title-container1">Price Details</div>
				<div class="price-title-container1">Price</div>
				<div class="price-map-container1">
					<?php if (isset($estprices) && count($estprices) > 0) { ?>
					<div class="sub-price-text">Service / Amenity Details - <span>Estimated Charges</span></div>
					<div class="service-list-container">
						<?php foreach($estprices as $estprice) { if(isset($estprice['apdesc']) && isset($estprice['aprice'])) { ?>
							<div class="service-title"><?php echo convert_to_camel_case($estprice['apdesc']); ?></div>
							<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $estprice['aprice']; ?></div>
						<?php } } ?>
					</div>
					<div class="final-price-container">
						<i class="fa fa-inr"></i>&nbsp;<?php echo $estprices[count($estprices) - 1]['ptotal']; ?>
					</div><br><br>
					<?php } ?>
					<?php if (isset($oprices) && count($oprices) > 0) { ?>
					<div class="sub-price-text">Additional Charges</div>
					<div class="service-list-container">
						<?php foreach($oprices as $oprice) { if(isset($oprice['opdesc']) && isset($oprice['oprice'])) { ?>
							<div class="service-title"><?php echo convert_to_camel_case($oprice['opdesc']); ?></div>
							<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $oprice['oprice']; ?></div>
						<?php } } ?>
					</div>
					<div class="final-price-container">
						<i class="fa fa-inr"></i>&nbsp;<?php echo $oprices[count($oprices) - 1]['ptotal']; ?>
					</div><br><br>
					<?php } ?>
					<?php if (isset($discprices) && count($discprices) > 0) { ?>
						<div class="service-list-container">
							<div class="sub-price-text">Discount Details</div>
							<?php foreach($discprices as $discprice) { if(isset($discprice['apdesc']) && isset($discprice['aprice'])) { ?>
								<div class="service-title"><?php echo convert_to_camel_case($discprice['apdesc']); ?></div>
								<div class="price-text">&nbsp;-&nbsp;<i class="fa fa-inr"></i>&nbsp;<?php echo $discprice['aprice']; ?></div>
							<?php } } ?>
						</div>
						<div class="final-price-container">
							&nbsp;-&nbsp;<i class="fa fa-inr"></i>&nbsp;<?php if(isset($discprices)) { echo $discprices[count($discprices) - 1]['ptotal']; } else { echo $estprice; } ?>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php } else { ?><br><?php } ?>
			<?php } else { ?>
			<div class="center">No Orders Yet</div>
			<?php } ?>
		</div>
	</aside>
	<?php $this->load->view('user/components/_foot'); ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/raty/jquery.raty.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/materialize.js'); ?>"></script>
<script type="text/javascript" src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/account.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/feedback.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/signup.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
<script>
	var univ_order_id = "<?php if(isset($OId)) { echo $OId; } ?>";
	var jccats = <?php if(isset($jccats)) { echo json_encode($jccats); } else echo json_encode(array()); ?>;
	var jcselects = <?php if(isset($jcselects)) { echo json_encode($jcselects); } else echo json_encode(array()); ?>;
	var feedback = <?php if(isset($feedback)) { echo json_encode($feedback); } else echo json_encode(array()); ?>;
	$(document).ready(function() {
		<?php if(isset($cr_bikecolor)) { echo '$("#cr_bikecolor").val("Bike Color : ' . $cr_bikecolor . '");'; } ?>
		<?php if(isset($cr_kms)) { echo '$("#cr_kms").val("Kms Reading : ' . $cr_kms . '");'; } ?>
		<?php if(isset($cs_fuelrange)) { echo '$("#cs_fuelrange").val("' . $cs_fuelrange . '");'; } ?>
		<?php if(isset($us_comments)) { echo '$("#us_comments").val("' . $us_comments . '");'; } ?>
	});
	$(function() {
		try {
			$(".payment_mode input[type='checkbox'], .payment_mode input[type='radio']").icheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green'
			});
		} catch(err) {}
		$('input[name="paymtGtw"][value="1"]').icheck('checked');
		<?php if(!$this->input->cookie('CityId')) { echo "openCityModal();"; } ?>
		<?php if(isset($is_first_login) && $is_first_login == 1) { echo "openFirstTimeLoginModal();"; } ?>
		$('#rs_date').pickadate({
			min: +1,
			max: 45,
			format: 'dddd, dd mmm, yyyy',
			formatSubmit: 'dddd, dd mmm, yyyy',
			closeOnSelect: true,
			container: 'body',
			onOpen: function() {
					$('#datepicker').val('');	
			},
			onSet: function() {	
				if($('#datepicker').val() != "" ){
					$(this).close();
				}
			}
		});
		$(window).load(function(){$('.load-wrap').hide();$('html').removeClass('no-scroll');});
		try{
			for(var i = 0; i < jcselects['JCSelects'].length; i++) {
				$('#scat_' + jcselects['JCSelects'][i]).attr('checked', 'checked');
			}
		} catch(err) { }
		try{
			for(var i = 0; i < jcselects['ChecklistVals'].length; i++) {
				$('#execl_' + jcselects['ChecklistVals'][i]).attr('checked', 'checked');
			}
		} catch(err) {
			//Do Nothing
		}
	});
</script>
</body>
</html>