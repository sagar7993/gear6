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
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Raleway"  type="text/css">
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/estyle.css'); ?>">
	<link rel="stylesheet" type="text/css" href="/nhome/js/lib/swal/sweetalert.css">
	<style type="text/css">
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
			padding: 7px!important;
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
			<div class="bcItem active-bc">Previous Orders</div>
		</div>
		<div class="user-order-block">
			<div class="">
				<div style="clear: both;"></div>
				<?php if(isset($OIds) && count($OIds) > 0) { $count = 0; $ser_check = 1; ?>
				<ul class="collapsible popout" data-collapsible="accordion">
					<?php foreach($OIds as $OId) { ?>
					<div class="section-header-1 margin-top-10px">
					<?php
						if($serid[$count] != $ser_check) {
							$ser_check = $serid[$count];
							echo '<span class="confirm-title">' . $stypes[$count] . '</span>';
						} else {
							if($count == 0)  {
								echo '<span class="confirm-title">' . $stypes[$count] . '</span>';
							}
						}
					?>
					</div>
					<li id="li_acc<?php echo $count; ?>" class="margin-top-10px">
					<div id="acc<?php echo $count; ?>" class="collapsible-header"><i class="material-icons">archive</i>
						<span class="margin-left-10px">Order ID - <?php echo $OId; ?></span>
						<span class="rateItContainer">
							<?php
							if(isset($serid[$count]) && $serid[$count] != 3) {
								if (isset($scenters[$count])) {
									foreach ($scenters[$count] as $sc) {
										echo '<span class="rateItLink" data-toggle="modal" data-target="#feedback_' . $OId  . '" data-order="' . $OId . '">Rate the Service [' . convert_to_camel_case($sc['ScName']) . ']</span>';
									}
								}
							}
						?>
						</span>
					</div>
					<div class="collapsible-body">
						<br/>
					<div class="review-options-container">
						<div class="form-group col-xs-3 right-dot-margin height-70" style="">
							<div class="final-options-text col-xs-12">
								<i class="fa fa-gears fa-class-style"></i>
								<div class="margin-top-5pc">
								<p class="selected-options"><?php echo $stypes[$count]; ?></p>
								</div>
							</div>
						</div>
						<div class="form-group col-xs-3 right-dot-margin  height-70" style="">
							<div class="final-options-text col-xs-12">
								<i class="fa fa-calendar fa-class-style"></i>
								<div class="margin-top-5pc">
								<p class="selected-options"><?php echo $timeslots[$count]; ?></p>
								</div>
							</div>
						</div>
						<div class="form-group col-xs-3 right-dot-margin  height-70" style="">
							<div class="final-options-text col-xs-12">
								<i class="fa fa-motorcycle fa-class-style"></i>
								<div class="margin-top-5pc">
								<p class="selected-options"><?php echo $bikemodels[$count]; ?></p>
								</div>
							</div>
						</div>
						<div class="form-group col-xs-3   height-70" style="">
							<div class="final-options-text col-xs-12">
								<i class="fa fa-map-marker fa-class-style"></i>
								<div class="margin-top-5pc">
								<p class="selected-options">
									<?php
										if (isset($scenters[$count])) {
											foreach ($scenters[$count] as $sc) {
												echo '<label class="selected-options">' . convert_to_camel_case($sc['ScName']) . '</label>';
											}
										}
									?>
								</p>
							</div>
							</div>
						</div>
						<div class="col-xs-6 pay-mode-final">
							<label for="exampleInputPassword1">Mode of Payment&nbsp;&nbsp; :&nbsp;&nbsp;</label> 
							<label class="selected-options"><?php echo $paymodes[$count]; ?></label> 
						</div>
						<?php if(isset($serid[$count]) && ($serid[$count] == 1 || $serid[$count] == 4)) { ?>
						<br>
						<?php if(isset($scenters[$count][0]['ServiceDesc2']) && $scenters[$count][0]['ServiceDesc2'] != '') { ?>
						<div class="col-xs-12 query-show-block">
							<div class="col-xs-2">
								<strong>Description : </strong>
							</div>
							<div class="col-xs-10">
								<span class="selected-options"><?php echo $scenters[$count][0]['ServiceDesc2']; ?></span>
							</div>
						</div>
						<?php } ?>
						<br>
						<?php } ?>
						<?php if(isset($serid[$count]) && $serid[$count] == 3) { ?>
						<br>
						<?php if(isset($scenters[$count][0]['ServiceDesc1']) && $scenters[$count][0]['ServiceDesc1'] != '') { ?>
						<div class="col-xs-12 query-show-block">
							<div class="col-xs-2">
								<strong>Query : </strong>
							</div>
							<div class="col-xs-10">
								<span class="selected-options"><?php echo $scenters[$count][0]['ServiceDesc1']; ?></span>
							</div>
						</div>
						<?php } ?>
						<?php if(isset($scenters[$count][0]['ServiceDesc2']) && $scenters[$count][0]['ServiceDesc2'] != '') { ?>
						<div class="col-xs-12 query-show-block">
							<div class="col-xs-2">
								<strong>Description : </strong>
							</div>
							<div class="col-xs-10">
								<span class="selected-options"><?php echo $scenters[$count][0]['ServiceDesc2']; ?></span>
							</div>
						</div>
						<?php } ?>
						<br>
						<?php } ?>
						<?php if(isset($serid[$count]) && $serid[$count] == 2) { ?>
						<br>
						<?php if(isset($scenters[$count][0]['ServiceDesc1']) && $scenters[$count][0]['ServiceDesc1'] != '') { ?>
						<div class="col-xs-12 query-show-block">
							<div class="col-xs-2">
								<strong>Repair Info : </strong>
							</div>
							<div class="col-xs-10">
								<span class="selected-options"><?php echo $scenters[$count][0]['ServiceDesc1']; ?></span>
							</div>
						</div>
						<?php } ?>
						<?php if(isset($scenters[$count][0]['ServiceDesc2']) && $scenters[$count][0]['ServiceDesc2'] != '') { ?>
						<div class="col-xs-12 query-show-block">
							<div class="col-xs-2">
								<strong>Description : </strong>
							</div>
							<div class="col-xs-10">
								<span class="selected-options"><?php echo $scenters[$count][0]['ServiceDesc2']; ?></span>
							</div>
						</div>
						<?php } ?>
						<br>
						<?php } ?>
						<?php if(isset($serid[$count]) && $serid[$count] == 4 && isset($insren_details[$count])) { ?>
						<div class="col-xs-12">
							<br>
						</div>
						<div class="review-options-container1">
							<div class="form-group col-xs-3 right-dot-margin margin-left-10px" style="">
								<label for="">Previous Insurer</label><br>
								<label class="selected-options"><?php echo convert_to_camel_case($insren_details[$count]['InsurerName']); ?></label>
							</div>
							<div class="form-group col-xs-3 right-dot-margin margin-left-10px" style="">
								<label for="">Previous Policy Expiry</label><br>
								<label class="selected-options"><?php if($insren_details[$count]['ExpiryDays'] == 0) { echo 'Already Expired'; } else { echo 'Next ' . $insren_details[$count]['ExpiryDays'] . ' Days'; } ?></label>
							</div>
							<div class="form-group col-xs-3 right-dot-margin margin-left-10px" style="">
								<label for="">Registration Year</label><br>
								<label class="selected-options"><?php echo convert_to_camel_case($insren_details[$count]['RegYear']); ?></label>
							</div>
							<div class="form-group col-xs-3  margin-left-10px" style="">
								<label for="">Claims Made Previously</label><br>
								<label class="selected-options"><?php if($insren_details[$count]['isClaimedBefore'] == 1) { echo 'Yes'; } else { echo 'No'; } ?></label>
							</div>
						</div>
						<br>
						<?php } ?>
						<?php if(isset($mr_remarks[$count]) && $mr_remarks[$count] != '') { ?>
						<div class="col-xs-12 query-show-block">
							<div class="col-xs-2">
								<strong>Order Remarks : </strong>
							</div>
							<div class="col-xs-10">
								<span class="selected-options"><?php echo $mr_remarks[$count]; ?></span>
							</div>
						</div>
						<br>
						<?php } ?>
						</div>
						<div class="addr-container2">
							<div class="user-addr-container">
								<div class="user-addr-title">User Address</div>
								<div class="user-addr-content">
									<?php echo $uaddresses[$count]; ?>
								</div>
							</div>
							<div class="sc-addr-container">
								<?php if(isset($serid[$count]) && $serid[$count] != 3) { ?>
									<div class="sc-addr-title">Service Centre Address</div>
								<?php } else { ?>
									<div class="sc-addr-title">Service Centre(s) Details</div>
								<?php } ?>
								<div class="sc-addr-content">
									<?php
										if(isset($serid[$count]) && $serid[$count] != 3) {
											if(isset($scaddresses[$count])) { echo $scaddresses[$count]; }
										} else {
											if (isset($scenters[$count])) {
												foreach ($scenters[$count]as $sc) {
													echo '<div>' . convert_to_camel_case($sc['ScName']) . ' - Phone: ' . $sc['Phone'] . '</div>';
												}
											}
										}
									?>
								</div>
							</div>
						</div>
						<?php if(isset($serid[$count]) && $serid[$count] != 3) { ?>
						<div class="price-details-container1">
							<div class="service-title-container1">Price Details</div>
							<div class="price-title-container1">Price</div>
							<div class="price-map-container1">
								<?php if(isset($estprices[$count]) && $estprices[$count] !== NULL) { ?>
								<div class="sub-price-text">Service / Amenity Details - <span>Estimated Charges</span></div>
								<div class="service-list-container">
									<?php foreach($estprices[$count] as $estprice) { if(isset($estprice['apdesc']) && isset($estprice['aprice'])) { ?>
										<div class="service-title"><?php echo convert_to_camel_case($estprice['apdesc']); ?></div>
										<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $estprice['aprice']; ?></div>
									<?php } } ?>
								</div>
								<div class="final-price-container">
									<i class="fa fa-inr"></i>&nbsp;<?php echo $estprices[$count][count($estprices[$count]) - 1]['ptotal']; ?>
								</div><br><br>
								<?php } ?>
								<?php if (isset($opriceses[$count]) && count($opriceses[$count]) > 0) { ?>
								<div class="sub-price-text">Additional Charges</div>
								<div class="service-list-container">
									<?php foreach($opriceses[$count] as $oprice) { if(isset($oprice['opdesc']) && isset($oprice['oprice'])) { ?>
										<div class="service-title"><?php echo convert_to_camel_case($oprice['opdesc']); ?></div>
										<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $oprice['oprice']; ?></div>
									<?php } } ?>
								</div>
								<div class="final-price-container">
									<i class="fa fa-inr"></i>&nbsp;<?php echo $opriceses[$count][count($opriceses[$count]) - 1]['ptotal']; ?>
								</div>
								<?php } ?>
							</div>
						</div>
						<?php } else { ?><br><?php } ?>
					</div>
					</li>
					<?php $count += 1; } ?>
				</ul>
				<?php if(isset($OIds) && count($OIds) > 0) { $count = 0; ?>
				<?php foreach($OIds as $OId) { ?>
				<?php if(isset($serid[$count]) && $serid[$count] != 3) { if (isset($scenters[$count])) { foreach ($scenters[$count] as $sc) { ?>
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
											<div class="col-xs-4 feedbackRatingPorder ratingEvent" id="question_<?php echo $OId; ?>_<?php echo $row['ExecFbQId']; ?>"></div>
										</div>
									<?php } ?>
									<div class="col-xs-12">
										<textarea class="form-group col-xs-12 text-area fb_desc fb_desc_porder" placeholder="Enter the feedback description (Optional)" style="color:white;"></textarea>
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
				<?php $count += 1; } } ?>
				<?php } else { ?>
				<div class="center">No Orders Yet</div>
				<?php } ?>
			</div>
		</div>
	</aside>
	<?php $this->load->view('user/components/_foot'); ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/raty/jquery.raty.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/materialize.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/account.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/feedback.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/signup.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
<script>
	var feedback = <?php if(isset($feedback)) { echo json_encode($feedback); } else echo json_encode(array()); ?>;
	$(function() {
		$("input[type='checkbox'], input[type='radio']").icheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green'
		});
		<?php if(!$this->input->cookie('CityId')) { echo "openCityModal();"; } ?>
		<?php if(isset($is_first_login) && $is_first_login == 1) { echo "openFirstTimeLoginModal();"; } ?>
		$(window).load(function(){$('.load-wrap').hide();$('html').removeClass('no-scroll');});
	});
</script>
</body>
</html>