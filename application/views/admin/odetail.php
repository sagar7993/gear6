<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Order Details - Admin Panel</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link rel="shortcut icon" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/vstyle.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/ionicons.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/module.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/datatables/dataTables.bootstrap.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/jQueryUI/jquery-ui.css'); ?>">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.min.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oxygen" type="text/css">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>">
	<link rel="stylesheet" type="text/css" href="/nhome/js/lib/swal/sweetalert.css">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.partial.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/materialize.admin.css'); ?>">
</head>
<body>
	<style>
		h3.ui-state-active {
			background: #009688 !important;
			padding: 8px !important;
			margin: 5px 15px 5px 15px !important;
			color: #333333 !important;
			font-size: 15px !important;
		}
		h3.ui-state-default {
			background: #009688 !important;
			padding: 12px !important;
			padding-left: 31px !important;
			margin: 5px 15px 0px 15px !important;
			color: #fff !important;
			font-size: 15px !important;
		}
		.dataTables_filter {
			margin-top: 36px !important;
		}
		#rescheduleDialog .select2-container{
			border:none!important;
		}
		.select2-container .select2-selection--single .select2-selection__rendered {
			font-size: 15px;
			margin-top: 5px;
		}
		.select2-container--default .select2-selection--single .select2-selection__arrow {
			top: 6px;
		}
		.select2-results__option {
			font-size: 15px;
		}
		span .cityCombo12 {
			background-color: white!important;
		}
		.ratingContainer {
			background-color: #d7d7d7;
		}
	</style>
	<?php $this->load->view('admin/components/_head'); ?>
	<?php if(isset($a_is_logged_in) && $a_is_logged_in == 1) { ?>
	<?php $this->load->view('admin/components/_sidebar'); ?>
	<aside class="right-side" style="margin-top:-20px;">
		<section class="content-header"><br/>
			<h1>
				Order Details (<?php if(isset($tieup['TieupName'])) { echo convert_to_camel_case($tieup['TieupName']); } ?>)
				<small><?php if(isset($OId) && isset($uname) && isset($uemail) && isset($uphone)) { echo $OId . ' - User: ' . convert_to_camel_case($uname) . ' - Phone: ' . $uphone . ' - Email: ' . $uemail; } ?></small>
			</h1>
		</section>
		<div class="vendor-order-block">
			<section class="section-center2">
				<div class="section-header-1"<?php if(isset($isGrievance) && $isGrievance == 1) { echo " style='background:red!important;'"; } ?>>
					<span class="confirm-title-odetail line-height-60px"><?php if(isset($isGrievance) && $isGrievance == 1) { echo "Repeat Order"; } else { echo "Order Tracking"; } ?></span>
					<?php if(isset($is_cancelled) && !$is_cancelled) { ?>
					<?php if(isset($serid) && $serid != 3) { ?>
					<span class="confirm-title-odetail margin-top-10px" style="float:right;margin-right:2%;"><a data-cancel="<?php echo site_url('admin/orders/cancel_order/' . $OId); ?>" id="cancel_order_href" style="color:white;cursor:pointer;">Cancel Order</a></span>
					<span class="confirm-title-odetail margin-top-10px" style="float:right;"><a href="javascript:;" id="res_ord_hlink" style="color:white;">Reschedule Order</a></span>
					<?php } ?>
					<?php if(isset($isinvoicesent) && $isinvoicesent == 0) { ?>
					<span class="confirm-title-odetail margin-top-10px" style="float:right;<?php if(isset($serid) && ($serid != 1 && $serid != 2)) { echo "margin-right:2%;"; } ?>"><a data-send-invoice="<?php echo site_url('admin/orders/emailinvoice/' . $OId); ?>" id="send_invoice_href" style="color:white;cursor:pointer;">Send Invoice</a></span>
					<?php } ?>
					<span class="confirm-title-odetail margin-top-10px" style="float:right;"><a href="<?php echo site_url('admin/orders/invoice/' . $OId); ?>" style="color:white;" target="_blank">Generate Invoice</a></span>
					<span class="confirm-title-odetail margin-top-10px" style="float:right;"><a href="<?php echo site_url('admin/orders/jobcard/' . $OId); ?>" style="color:white;" target="_blank">Generate Job Card</a></span>
					<span class="confirm-title-odetail margin-top-10px" style="float:right;"><a href="<?php echo site_url('admin/users/uodetails/' . $UserId); ?>" style="color:white;" target="_blank">User History</a></span>
					<?php if(isset($serid) && ($serid == 1 || $serid == 2)) { ?>	
						<span class="confirm-title-odetail margin-top-10px" style="float:right;"><a href="<?php echo site_url('admin/orders/repeat_order/' . $OId . '/' . $ScId); ?>" style="color:white;" target="_blank">Repeat Order</a></span>
					<?php } ?>
					<span class="confirm-title-odetail" style="float:right;">
						<div class="checkbox">
							<label class="price">
								<input type="checkbox" id="dontsendsms">
								<span class="confirm-title-odetail" style="margin-left:5px">Don't SMS</span>
							</label>
						</div>
					</span>
					<?php } ?>
				</div>
				<?php if(isset($is_cancelled) && !$is_cancelled) { ?>
				<div class="section-content-action1">
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
				<?php if(isset($serid) && $serid != 3) { ?>
				<div class="col-xs-12">
					<form method="POST" action="/admin/orders/ulocation_pin_update/">
						<input type="hidden" name="oid" value="<?php if(isset($OId)) { echo $OId; } ?>">
						<div class="col-xs-12 fields-container">
							<div class="col-xs-12" style="margin-top:10px;margin-bottom:-15px;"><label class="selected-options">&nbsp;&nbsp;&nbsp;&nbsp;Precise User Location</label></div>
							<div class="col-xs-12 form-group">
								<div class="col-xs-4 field-box">
									<input type="number" class="form-control" name="u_lati" id="u_lati" step="any" value="<?php if(isset($u_lati)) { echo $u_lati; } ?>" placeholder="User Latitude" required>
								</div>
								<div class="col-xs-4 field-box">
									<input type="number" class="form-control" name="u_longi" id="u_longi" step="any" value="<?php if(isset($u_longi)) { echo $u_longi; } ?>" placeholder="User Longitude" required>
								</div>
								<div class="col-xs-3 text-center" style="float:right;">
									<br><button type="submit" class="btn btn-primary action-btn">Update Location</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<?php } ?>
				<?php if(isset($serid) && $serid == 3 && isset($statuses) && isset($scenter)) { $count = 1; foreach ($scenter as $sc) { ?>
				<label style="margin-left:36%;">Service Center Name :</label>
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
									<i class="fa fa-top-chevron chevron-<?php echo $count . '-1'; ?>"  style="margin-top: -7px;display:none;"></i>
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
				<?php if($count != count($scenter)) { echo '<br><br><br><br><br><br><br><br/>'; } ?>
				<?php $count += 1; } } ?>
				<div class="col-xs-12">
					<form method="POST" action="/admin/orders/pickup_drop_flag_update/">
						<input type="hidden" name="oid" value="<?php if(isset($OId)) { echo $OId; } ?>"/>
						<div class="col-xs-12 fields-container">
							<div class="col-xs-12 form-group">
								<div class="col-xs-3 field-box">
									<div class="checkbox">
										<label class="paymode">
											<input type="radio" name="pick-type" value="1" onchange="validatePickup();"<?php if(isset($pickup_drop_flag) && $pickup_drop_flag == 1) { echo " checked"; } ?>>
											<span style="margin-left:5px">Pickup</span>
										</label>
									</div>
								</div>
								<div class="col-xs-3 field-box">
									<div class="checkbox">
										<label class="paymode">
											<input type="radio" name="pick-type" value="2" onchange="validatePickup();"<?php if(isset($pickup_drop_flag) && $pickup_drop_flag == 2) { echo " checked"; } ?>>
											<span style="margin-left:5px">Drop</span>
										</label>
									</div>
								</div>
								<div class="col-xs-3 field-box">
									<div class="checkbox">
										<label class="paymode">
											<input type="radio" name="pick-type" value="3" onchange="validatePickup();"<?php if(isset($pickup_drop_flag) && $pickup_drop_flag == 3) { echo " checked"; } ?>>
											<span style="margin-left:5px">Pickup & Drop</span>
										</label>
									</div>
								</div>
								<div class="col-xs-3 text-center" style="float:right;">
									<br><button type="submit" class="btn btn-primary action-btn" disabled id="pickup_drop_flag_update_button">Update</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="col-xs-12">
					<form method="POST" action="/admin/orders/is_breakdown_flag_update/">
						<input type="hidden" name="oid" value="<?php if(isset($OId)) { echo $OId; } ?>"/>
						<div class="col-xs-12 fields-container">
							<div class="col-xs-12 form-group">
								<div class="col-xs-4 field-box">
									<div class="checkbox">
										<label class="paymode">
											<input type="checkbox" id="isBreakdown" name="isBreakdown" value="1" onchange="validateBreakdown();">
											<span style="margin-left:5px">Breakdown Condition?</span>
										</label>
									</div>
								</div>
								<div class="col-xs-4 field-box">
									<select class="form-control styled-select" name="transport_mode" id="transport_mode" title="Mode Of Transport" onchange="validateBreakdown();">
										<option selected value=''>Mode Of Transport</option>
										<option value="1">Executive Towing</option>
										<option value="2">Yellooboard</option>
										<option value="3">Truck</option>
									</select>
								</div>
								<div class="col-xs-4 text-center" style="float:right;">
									<br><button type="submit" class="btn btn-primary action-btn" disabled id="is_breakdown_flag_update_button" style="margin-left: 28%;">Update</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="col-xs-12">
					<form method="POST" action="/admin/orders/insurance_puc_date_update/">
						<input type="hidden" name="OId" value="<?php if(isset($OId)) { echo $OId; } ?>">
						<input type="hidden" name="ODate" value="<?php if(isset($ODate)) { echo $ODate; } ?>">
						<input type="hidden" name="bikenumber" value="<?php if(isset($bikenumber)) { echo $bikenumber; } ?>">
						<div class="col-xs-12 fields-container">
							<div class="col-xs-12" style="margin-top:10px;margin-bottom:-15px;"><label class="selected-options">&nbsp;&nbsp;&nbsp;&nbsp;Update Next Insurance / Next PUC Renewal / Next Service Reminder Date</label></div>
							<div class="col-xs-12 form-group">
								<div class="col-xs-3 field-box">
									<input type="text" value="<?php if(isset($insurance_renewal_date)) { echo $insurance_renewal_date; } ?>" class="form-control dpDate2" readonly='true' onchange="validateDates();" name="insurance_renewal_date" id="insurance_renewal_date" placeholder="Insurance Renewal" style="cursor:pointer;">
								</div>
								<div class="col-xs-3 field-box">
									<input type="text" value="<?php if(isset($puc_renewal_date)) { echo $puc_renewal_date; } ?>" class="form-control dpDate2" readonly='true' onchange="validateDates();" name="puc_renewal_date" id="puc_renewal_date" placeholder="Next PUC Date" style="cursor:pointer;">
								</div>
								<div class="col-xs-3 field-box">
									<input type="text" value="<?php if(isset($service_reminder_date)) { echo $service_reminder_date; } ?>" class="form-control dpDate2" readonly='true' onchange="validateDates();" name="service_reminder_date" id="service_reminder_date" placeholder="Next Service Date" style="cursor:pointer;">
								</div>
								<div class="col-xs-3 text-center margin-top-22px">
									<button type="submit" class="btn btn-primary action-btn" disabled id="insurance_puc_renewal_date_update_button">Create Reminder</button>
								</div>
							</div>
						</div>
					</form>
					<form method="POST" action="/admin/orders/fup_status_update" id="followup_status_form">
						<input type="hidden" name="oid" value="<?php if(isset($OId)) { echo $OId; } ?>">
						<div class="col-xs-12 fields-container">
							<div class="col-xs-12" style="margin-top:10px;margin-bottom:-15px;"><label class="selected-options">&nbsp;&nbsp;&nbsp;&nbsp;Status History Followup</label></div>
							<div class="col-xs-12 form-group">
								<div class="col-xs-6 field-box">
									<textarea type="text" class="form-control" name="fup_remarks" id="fup_remarks" placeholder="Followup Remarks"></textarea>
								</div>
								<div class="col-xs-6 field-box padding-right-38px">
									<select class="form-control styled-select" name="fup_status" id="fup_status" title="FollowUp Status" onchange="followUpStatusSelect();">
										<option selected style="display:none;" value=''>FollowUp Status</option>
										<?php if(isset($fup_statuses)) { foreach($fup_statuses as $fup_status) { ?>
										<option value="<?php echo $fup_status->FupStatusId; ?>"><?php echo convert_to_camel_case($fup_status->FupStatusName); ?></option>
										<?php } } ?>
									</select>
								</div>
								<div class="col-xs-3 text">
									<div class="col-xs-12 checkbox">
										<label class="price">
											<input type="checkbox" id="smstouser" name="smstouser" value="1">
											<span style="margin-left:5px">SMS to User</span>
										</label>
									</div>
									<div class="col-xs-12 checkbox">
										<label class="price">
											<input type="checkbox" id="smstoexec" name="smstoexec" value="1">
											<span style="margin-left:5px">SMS to Executives</span>
										</label>
									</div>
								</div>
								<input type="hidden" name="followup_reminder_date" id="followup_reminder_date">
								<input type="hidden" name="followup_reminder_type" id="followup_reminder_type" value="<?php if(isset($_GET["reminder_type"]) && ($_GET["reminder_type"] == 'service_reminder_date' || $_GET["reminder_type"] == 'insurance_renewal_date' || $_GET["reminder_type"] == 'puc_renewal_date')) { echo $_GET["reminder_type"]; } else { echo ""; } ?>">
								<?php if(isset($_GET["feedback"]) && $_GET["feedback"] == TRUE) { ?>
									<div class="col-xs-12 field-box">
										<textarea type="text" class="form-control" name="feedback_remarks" id="feedback_remarks" placeholder="Feedback Remarks"></textarea>
									</div>
									<div class="center-align">
										<div class="col-xs-6 ratingContainer">
											<div class="col-xs-4 ratingTitle" style="color:#028cbc;">Service Rating</div>
											<div class="col-xs-8 serviceCenterRating ratingEvent"></div>
										</div>
										<div class="col-xs-6 ratingContainer">
											<div class="col-xs-4 ratingTitle" style="color:#028cbc;">Gear6 Rating</div>
											<div class="col-xs-8 gear6Rating ratingEvent"></div>
										</div>
									</div>
									<input type="hidden" name="nps" id="nps" value="<?php echo $nps;?>"/>
									<input type="hidden" name="user_feedback_rating" id="user_feedback_rating" value="<?php echo $user_feedback_rating;?>"/>
								<?php } ?>
							</div>
							<div class="col-xs-12 form-group">
								<div id="fup_status_container" style="display:none;">
									<div class="col-xs-4 text-center">
										<div class="col-xs-12 field-box">
											<input type="text" class="form-control" name="estimated_date" id="estimated_date" placeholder="Estimated Date" value="<?php if(isset($estimations) && $estimations != NULL) { echo $estimations['EstDate']; } ?>" style="cursor:pointer;">
										</div>
									</div>
									<div class="col-xs-4 text-center">
										<div class="col-xs-12 field-box">
											<input type="text" class="form-control" name="estimated_time" id="estimated_time" placeholder="Estimated Time" value="<?php if(isset($estimations) && $estimations != NULL) { echo $estimations['EstTime']; } ?>">
										</div>
									</div>
									<div class="col-xs-4 text-center">
										<div class="col-xs-12 field-box">
											<input type="text" class="form-control" name="estimated_price" id="estimated_price" placeholder="Estimated Price" value="<?php if(isset($estimations) && $estimations != NULL) { echo $estimations['EstPrice']; } ?>">
										</div>
									</div>
								</div>
								<div class="col-xs-12 text-center">
									<br/><button type="submit" class="btn btn-primary action-btn" id="reminderDateValidation">Update Status</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<?php } else { ?>
					<div class="col-xs-12 section-content-action-update">
						<strong>This order is cancelled.</strong>
					</div>
				<?php } ?>
			</section>
			<?php if((isset($serid) && $serid != 3) && ((isset($ex_rtime_updates) && count($ex_rtime_updates) > 0) || (isset($ex_fup_updates) && count($ex_fup_updates) > 0) || (isset($ex_pre_servicing_updates) && count($ex_pre_servicing_updates) > 0))) { ?>
			<section class="section-center2">
				<div class="section-header-1">
					<span class="confirm-title">Executive Updates</span>
				</div>
				<div class="section-content-action-update">
					<?php if((isset($ex_rtime_updates) && count($ex_rtime_updates) > 0) || (isset($ex_fup_updates) && count($ex_fup_updates) > 0) || (isset($ex_pre_servicing_updates) && count($ex_pre_servicing_updates) > 0)) { ?>
						<ul class="collapsible popout" data-collapsible="accordion">
							<?php if(isset($ex_rtime_updates) && count($ex_rtime_updates) > 0) { ?>
								<li>
									<div class="collapsible-header"><i class="material-icons">history</i><b>Running Status History</b></div>
									<div class="collapsible-body">
										<table id="ex_rtime_updates_table" border="0" cellpadding="0" cellspacing="0" class="table custom-table" style="margin-top: -52px !important;"></table>
									</div>
								</li>
							<?php } ?>
							<?php if(isset($ex_fup_updates) && count($ex_fup_updates) > 0) { ?>
								<li>
									<div class="collapsible-header"><i class="material-icons">history</i><b>FollowUp Status History</b></div>
									<div class="collapsible-body">
										<table id="ex_fup_updates_table" border="0" cellpadding="0" cellspacing="0" class="table custom-table" style="margin-top: -52px !important;"></table>
									</div>
								</li>
							<?php } ?>
							<?php if(isset($ex_pre_servicing_updates) && count($ex_pre_servicing_updates) > 0) { ?>
								<li>
									<div class="collapsible-header"><i class="material-icons">history</i><b>Pre-Servicing Updates</b></div>
									<div class="collapsible-body">
										<table id="ex_ps_updates_table" border="0" cellpadding="0" cellspacing="0" class="table custom-table" style="margin-top: -52px !important;"></table>
									</div>
								</li>
							<?php } ?>
						</ul>
					<?php } ?>
			</section>
			<?php } ?>
			<section class="section-center2">
				<div class="section-header-1">
					<span class="confirm-title">Manage Order</span>
					<span class="confirm-title" style="float:right;margin-right:2%;"><a href="<?php echo site_url('admin/orders/eodetail/' . $OId); ?>" target="_blank" style="color:white;">Job Card</a></span>
					<span class="confirm-title" style="float:right;margin-right:2%;"><a href="<?php echo site_url('admin/users/udetails/' . $UserId); ?>" target="_blank" style="color:white;">Manage User</a></span>
				</div>
				<div class="section-content-action-update">
					<ul class="collapsible popout" data-collapsible="accordion">
						<li>
							<div class="collapsible-header"><i class="material-icons">track_changes</i><b>Change Status</b></div>
							<div class="collapsible-body">
								<?php if(isset($is_cancelled) && !$is_cancelled) { ?>
								<br/>
								<?php if(TRUE || (isset($is_amt_cfmd) && $is_amt_cfmd != 1) || (isset($serid) && $serid == 3)) { ?>
								<?php $count = 0; foreach($rest_statuses as $rest_status) { ?>
								<div class="col-xs-12">
								<?php echo '<label class="selected-options">&nbsp;&nbsp;&nbsp;&nbsp;' . convert_to_camel_case($scenter[$count]['ScName']) . '</label>'; ?>
								<?php if(isset($rest_status[0]['StatusName']) && $rest_status[0]['StatusName'] != '' && $rest_status[0]['StatusName'] !== NULL) { ?>
								<form method="POST" action="/admin/orders/changeStatus/">
									<div class="form-group col-xs-4 margin-top-5" style="width:15%;">Change Status : </div>
									<input type="hidden" class="sendsmsclass" name="sendsmsflag" value="1">
									<input type="hidden" name="sc_id" value="<?php echo $scenter[$count]['ScId']; ?>">
									<input type="hidden" name="oid" value="<?php if(isset($OId)) { echo $OId; } ?>">
									<input id="stype-vendor-<?php echo $scenter[$count]['ScId']; ?>" class="stype-vendor" name="servicetype" type="hidden" value="<?php echo $rest_status[0]['StatusId']; ?>">
									<div class="col-xs-3 text-center">
										<button type="submit" name="changeStatus" id="changeStatus_<?php echo $scenter[$count]['ScId']; ?>" disabled="true" class="btn btn-primary action-btn"><?php echo convert_to_camel_case($rest_status[0]['StatusName']); ?></button>
									</div>
								</div>
								<div class="col-xs-12 fields-container desc_txt_mandatory" id="stat_txt_mandatory_<?php echo $scenter[$count]['ScId']; ?>" style="display:none">
									<div class="col-xs-6 field-box">
										<?php if((isset($serid) && $serid == 1 )) { ?>
											<textarea  type="text" class="form-control stat_desc_c" name="stat_desc_c" id="desc_txt_mandatory_<?php echo $scenter[$count]['ScId']; ?>" placeholder="Your bike check-up is done, proceeding for the service"></textarea>
										<?php } else if((isset($serid) && $serid == 2)) { ?>
											<textarea  type="text" class="form-control stat_desc_c" name="stat_desc_c" id="desc_txt_mandatory_<?php echo $scenter[$count]['ScId']; ?>" placeholder="Your bike repair check-up is done, proceeding for the repair"></textarea>
										<?php } else if((isset($serid) && $serid == 3)) { ?>
											<textarea  type="text" class="form-control stat_desc_c" name="stat_desc_c" id="desc_txt_mandatory_<?php echo $scenter[$count]['ScId']; ?>" placeholder="Type Query Answer Here (Compulsory)"></textarea>
										<?php } else if((isset($serid) && $serid == 4)) { ?>
											<textarea  type="text" class="form-control stat_desc_c" name="stat_desc_c" id="desc_txt_mandatory_<?php echo $scenter[$count]['ScId']; ?>" placeholder="Your insurance details check-up is over, proceeding for its renewal"></textarea>
										<?php } ?>
									</div>
									<div class="col-xs-6 field-box">
										<textarea type="text" class="form-control" name="admin_notes1" id="admin_notes1" placeholder="Admin Notes (Optional)"></textarea>
									</div>
								</div>
								<div class="col-xs-12 fields-container desc_txt_optional" id="stat_txt_optional_<?php echo $scenter[$count]['ScId']; ?>">
									<div class="col-xs-6 field-box">
										<textarea type="text" class="form-control" name="stat_desc_o" id="desc_txt" placeholder="Status Description (Optional)"></textarea>
									</div>
									<div class="col-xs-6 field-box">
										<textarea type="text" class="form-control" name="admin_notes" id="admin_notes" placeholder="Admin Notes (Optional)"></textarea>
									</div>
								</div>
								</form>
								<?php } else { ?>
									<?php if(isset($send_final_message) && $send_final_message == 0) { ?>
									<form method="POST" action="/admin/orders/send_thankyou/">
										<div class="form-group col-xs-4 margin-top-5">
											Send Thankyou SMS:
										</div>
										<input type="hidden" name="oid" value="<?php if(isset($OId)) { echo $OId; } ?>">
										<div class="col-xs-3 text-center">
											<button type="submit" class="btn btn-primary action-btn">Send SMS</button>
										</div>
									</div>
									<input type="hidden" class="sendsmsclass" name="sendsmsflag" value="1">
									<div class="col-xs-12 fields-container">
										<div class="col-xs-8 field-box">
											<textarea type="text" class="form-control" name="thank_sms_txt" id="thank_sms_txt" placeholder="SMS Text"></textarea>
										</div>
									</div>
									</form>
									<?php } else { ?>
									<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>This order is totally processed. Nothing left to do here</strong>
									</div>
									<?php } ?>
								<?php } ?>
								<?php $count += 1; } ?>
								<?php } else { ?>
									<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please wait till the user approves the amount</strong>
								<?php } } ?>
								<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
							</div>
						</li>
						<li>
						  <div class="collapsible-header"><i class="material-icons">attach_money</i><b>Add Service Detail and Price</b></div>
						  <div class="collapsible-body">
								<?php if(isset($serid) && $serid != 3) { ?>
								<div class="price-details-container1">
									<div class="service-title-container1">Price Details</div>
									<div class="price-title-container1 margin-bottom-10px">Price</div>
									<div class="price-map-container1">
										<?php if(isset($estprices) && $estprices !== NULL) { ?>
										<div class="service-list-container">
											<div class="sub-price-text green-text">Service / Amenity Details - <span>Estimated Charges</span></div>
											<?php foreach($estprices as $estprice) { if(isset($estprice['apdesc']) && isset($estprice['aprice'])) { ?>
												<div class="service-title">
													<?php if(isset($estprice['apid'])) { ?>
														<a href="#" class="edit_aprice" data-type="ap" data-ttype="<?php echo $estprice['attype']; ?>" data-id="<?php echo $estprice['apid']; ?>" data-apdesc="<?php echo $estprice['apdesc']; ?>" data-aprice="<?php echo $estprice['aprice']; ?>"><img src="<?php echo site_url('img/icons/edit_disabled.png'); ?>" height="20px" width="20px" /></a>		
														<a data-cancel="<?php echo site_url('admin/orders/delete_estimate/' . $OId . '/' . $estprice['apid']); ?>" onclick="deleteEstimatedPrice(this);" style="cursor:pointer;<?php if(floatval($estprice['aprice']) <= 0.01) { echo "visibility:visible;"; } else { echo "visibility:hidden;"; } ?>"><img src="https://www.gear6.in/img/delete-image.png" height="20px" width="20px"></a>
													<?php } ?>
													&nbsp;<?php echo convert_to_camel_case($estprice['apdesc']); ?>
												</div>
												<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $estprice['aprice']; ?></div>
												<?php if(intval($estprice['atprice']) != 0) { ?>
													<div class="service-title">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $estprice['atdesc']; ?></div>
													<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $estprice['atprice']; ?></div>
												<?php } ?>
											<?php } } ?>
										</div>
										<div class="final-price-container margin-bottom-10px">
											<i class="fa fa-inr"></i>&nbsp;<?php echo $estprices[count($estprices) - 1]['ptotal']; ?>
										</div>
										<?php } ?>
										<?php if (isset($oprices) && count($oprices) > 0) { ?>
											<div class="service-list-container">
												<div class="sub-price-text green-text">Additional Charges</div>
												<?php foreach($oprices as $oprice) { if(isset($oprice['opdesc']) && isset($oprice['oprice'])) { ?>
													<div class="service-title">
														<?php if(isset($oprice['opid'])) { ?>
															<a href="#" class="edit_oprice" data-type="op" data-id="<?php echo $oprice['opid']; ?>" data-opdesc="<?php echo $oprice['opdesc']; ?>" data-oprice="<?php echo $oprice['oprice']; ?>"><img src="<?php echo site_url('img/icons/edit_disabled.png'); ?>" height="20px" width="20px" /></a>
														<?php } ?>
														<a data-cancel="<?php echo site_url('admin/orders/delete_additional/' . $OId . '/' . $oprice['opid']); ?>" onclick="deleteAdditionalPrice(this);" style="cursor:pointer;<?php if(floatval($oprice['oprice']) <= 0.01) { echo "visibility:visible;"; } else { echo "visibility:hidden;"; } ?>"><img src="https://www.gear6.in/img/delete-image.png" height="20px" width="20px"></a>
														&nbsp;<?php echo convert_to_camel_case($oprice['opdesc']); ?>
													</div>
													<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $oprice['oprice']; ?></div>
												<?php } } ?>
											</div>
											<div class="final-price-container">
												<i class="fa fa-inr"></i>&nbsp;<?php echo $oprices[count($oprices) - 1]['ptotal']; ?>
											</div>
										<?php } ?>
										<?php if (isset($discprices) && count($discprices) > 0) { ?>
											<div class="service-list-container">
												<div class="sub-price-text green-text">Discount Details</div>
												<?php foreach($discprices as $discprice) { if(isset($discprice['apdesc']) && isset($discprice['aprice'])) { ?>
													<div class="service-title">
														<a data-cancel="<?php echo site_url('admin/orders/delete_discount/' . $OId . '/' . $discprice['apid']); ?>" onclick="deleteDiscountedPrice(this);" style="cursor:pointer;"><img src="https://www.gear6.in/img/delete-image.png" height="20px" width="20px"></a>
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo convert_to_camel_case($discprice['apdesc']); ?>
													</div>
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
									<div class="price-title-container1 margin-bottom-10px">Price</div>
									<div class="price-map-container1">
										<div class="service-list-container">
											<div class="service-title">Total Billed Amount</div>
											<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php if(isset($tot_billed)) { echo $tot_billed; } ?></div>
											<div class="service-title">Total Paid Amount</div>
											<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php if(isset($tot_paid)) { echo $tot_paid; } ?></div>
											<div class="service-title">Total Amount to be Paid</div>
											<div class="price-text margin-bottom-10px"><i class="fa fa-inr"></i>&nbsp;<?php if(isset($to_be_paid)) { echo $to_be_paid; } ?></div>
										</div>
									</div>
								</div>
								<div class="col-xs-12 price-list-container" style="display:none">
									<div class="col-xs-12 price-container">
										<div class="form-group col-xs-5 width50">
											<input type="text" class="form-control spdetail" oninput="checkPriceDetails();" id="spdetail1" placeholder="Service Detail">
										</div>
										<div class="form-group col-xs-4 width25 margin-left-n25">
											<input type="number" min="0" class="form-control sp" oninput="checkPriceDetails();" id="sp1" placeholder="Price Detail - eg. 350">
										</div>
										<div class="form-group col-xs-4 width25">
											<select class="form-control spttype" id="spttype1">
												<option value="0">No Tax</option>
												<option value="1">Service Tax</option>
												<option value="2">VAT</option>
												<option value="3">Discount</option>
												<option value="4">Convenience Fee</option>
											</select>
										</div>
										<div class="col-xs-2 field-area-add" id="price_add"></div>
									</div>
								</div>
								<div class="center text-center" id="price-list-submit" style="display:none;margin-bottom:10px!important;">
									<button name="submitform" id="priceupdate" disabled="true" class="btn btn-primary action-btn">Update Price</button>
								</div>
								<div class="col-xs-12">
									<div class="form-group col-xs-4">
										<input type="number" class="form-control" name="cf_kms" id="cf_kms" min="0.01" step="0.01" placeholder="Enter Kilometers">
									</div>
									<div class="form-group col-xs-5">
										<input type="number" class="form-control" name="cf_mins" id="cf_mins" min="2" step="2" placeholder="Enter Time in Minutes (Only For Towing)">
									</div>
									<div class="form-group col-xs-3">
										<input type="text" class="form-control" name="cf_price" id="cf_price" placeholder="Calculated convenience fee" readonly="true">
									</div>
									<input type="hidden" name="cf_serid" id="cf_serid" value="<?php if(isset($serid)) { echo $serid; } ?>">
								</div>
								<div class="price-details-container1">
									<div class="service-title-container1" style="margin-top:-30px!important;">Executive Happay Transaction Details</div>
									<div class="col-xs-12" style="margin-top:10px!important;">
										<form method="post" action="/admin/orders/update_happay">
											<div class="form-group col-xs-5">
												<input type="text" value="<?php echo $HappayTId; ?>" class="form-control" oninput="validateHappay();" name="HappayTId" id="HappayTId" placeholder="Happay Transaction Id">
											</div>
											<div class="form-group col-xs-5">
												<input type="number" value="<?php echo $HappayAmount; ?>" class="form-control" oninput="validateHappay();" name="HappayAmount" id="HappayAmount"  min="0" placeholder="Happay Amount">
											</div>
											<input type="hidden" name="oid" value="<?php if(isset($OId)) { echo $OId; } ?>">
											<div class="form-group col-xs-2">
												<input name="happayButton" id="happayButton" type="submit" disabled="true" class="btn btn-primary action-btn" value="Update Happay"></input>
											</div>
										</form>
									</div>
								</div>
								<div class="col-xs-12" style="display:none" id="oprice_edit_block">
									<form method="POST" action="/admin/orders/update_opricedetail">
										<div class="col-xs-12">
											<div class="form-group col-xs-4">
												<input type="text" class="form-control" name="opdesc" id="op_opdesc" placeholder="Edit Price Description" required>
											</div>
											<div class="form-group col-xs-4">
												<input type="number" class="form-control" min="0" step="0.01" name="oprice" id="op_oprice" placeholder="Enter New Price" required>
											</div>
											<input type="hidden" name="opid" id="op_opid" />
											<input type="hidden" name="ptype" id="p_ptype" />
											<input type="hidden" name="ttype" id="p_ttype" />
											<input type="hidden" name="opoid" value="<?php echo $OId; ?>" />
											<div class="text-center col-xs-4">
												<button name="opriceupdate" id="opriceupdate" class="btn btn-primary action-btn">Edit Price Detail</button>
											</div>
										</div>
									</form>
								</div>
								<?php } ?>
						  </div>
						</li>
						<li>
						  <div class="collapsible-header"><i class="material-icons">history</i><b>Status History</b></div>
						  <div class="collapsible-body">
							<?php if(isset($stathists)) { ?>
								<?php if(isset($serid) && $serid != 3) { ?>
									<br/>
									<div class="col-xs-12 status-history-title-container">
										<div class="form-group col-xs-2 width20">
											<strong>Order Status</strong>
										</div>
										<div class="form-group col-xs-4" style="width:30%;">
											<strong>Status Description / Admin Notes</strong>
										</div>
										<div class="form-group col-xs-4" style="width:30%;">
											<strong>Status Updated By</strong>
										</div>
										<div class="form-group col-xs-2 ">
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
														<div class="form-group col-xs-4" style="width:30%;">
															<strong>Status Description / Admin Notes</strong>
														</div>
														<div class="form-group col-xs-4" style="width:30%;">
															<strong>Status Updated By</strong>
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
									<div class="form-group col-xs-4 both-dot-border" style="width:30%;">
										<?php if(isset($stathist['sdesc']) && $stathist['sdesc'] != '') { echo $stathist['sdesc']; } else { echo '&nbsp;&nbsp;'; } ?>
									</div>
									<div class="form-group col-xs-4" style="width:30%;border-right:2px dotted #a7a7a7;">
										<?php if(isset($stathist['modified_by']) && $stathist['modified_by'] != '') { echo convert_to_camel_case($stathist['modified_by']); } else { echo '&nbsp;&nbsp;'; } ?>
									</div>
									<div class="form-group col-xs-2 width20">
										<?php echo $stathist['date']; ?>
									</div>
								</div>
								<div class="col-xs-12 status-history-content-container">
									<div class="form-group col-xs-2 width20">
										<?php echo $stathist['sname']; ?>
									</div>
									<div class="form-group col-xs-4 both-dot-border" style="width:30%;">
										<?php if(isset($stathist['admin_notes']) && $stathist['admin_notes'] != '') { echo $stathist['admin_notes']; } else { echo '&nbsp;&nbsp;'; } ?>
									</div>
									<div class="form-group col-xs-4" style="width:30%;border-right:2px dotted #a7a7a7;">
										<?php if(isset($stathist['modified_by']) && $stathist['modified_by'] != '') { echo convert_to_camel_case($stathist['modified_by']); } else { echo '&nbsp;&nbsp;'; } ?>
									</div>
									<div class="form-group col-xs-2 width20">
										<?php echo $stathist['date']; ?>
									</div>
								</div>
								<?php } } else { ?>
								<p>
									<strong>No status changes yet.</strong>
								</p>
							<?php } ?>
						  </div>
						</li>
						<li>
						  <div class="collapsible-header"><i class="material-icons">history</i><b>Followup Status History</b></div>
						  <div class="collapsible-body">
							<?php if(isset($fupstathistory)) { ?>
									<div class="col-xs-12 status-history-title-container">
										<div class="form-group col-xs-2 width20">
											<strong>Status Name</strong>
										</div>
										<div class="form-group col-xs-4" style="width:30%;">
											<strong>Status Remarks</strong>
										</div>
										<div class="form-group col-xs-4" style="width:30%;">
											<strong>Status Updated By</strong>
										</div>
										<div class="form-group col-xs-2 ">
											<strong>Updated On</strong>
										</div>
									</div>
								<?php foreach($fupstathistory as $stathist) { ?>
								<div class="col-xs-12 status-history-content-container">
									<div class="form-group col-xs-2 width20">
										<?php echo convert_to_camel_case($stathist['FupStatusName']); ?>
									</div>
									<div class="form-group col-xs-4 both-dot-border" style="width:30%;">
										<?php if(isset($stathist['Remarks']) && $stathist['Remarks'] != '') { echo $stathist['Remarks']; } else { echo '&nbsp;&nbsp;'; } ?>
									</div>
									<div class="form-group col-xs-4" style="width:30%;border-right:2px dotted #a7a7a7;">
										<?php echo convert_to_camel_case($stathist['UpdatedBy']); ?>
									</div>
									<div class="form-group col-xs-2 width20">
										<?php echo date("d/m/Y - h:i A", strtotime($stathist['Timestamp'] . ' UTC')); ?>
									</div>
								</div>
								<?php } } else { ?>
								<p>
									<strong>No Followup status changes yet.</strong>
								</p>
							<?php } ?>
						  </div>
						</li>
						<?php if(isset($serid) && $serid != 3) { ?>
							<li>
							  <div class="collapsible-header"><i class="material-icons">account_balance_wallet</i><b>Order Transactions</b></div>
							  <div class="collapsible-body">
								<?php if(isset($ord_trans) && $ord_trans !== NULL) { ?>
									<br/>
									<div class="col-xs-12 status-history-title-container">
										<div class="form-group col-xs-3 right-dot-margin">
											<strong>Transaction Id</strong>
										</div>
										<div class="form-group col-xs-3 right-dot-margin">
											<strong>Transaction Date</strong>
										</div>
										<div class="form-group col-xs-2 right-dot-margin">
											<strong>Transaction Amount</strong>
										</div>
										<div class="form-group col-xs-2 right-dot-margin">
											<strong>Transaction Mode</strong>
										</div>
										<div class="form-group col-xs-2">
											<strong>Transaction Status</strong>
										</div>
									</div>
									<?php
									foreach($ord_trans as $trans) {
									?>
									<div class="col-xs-12 status-history-content-container">
										<div class="form-group col-xs-3 right-dot-margin">
											<?php echo $trans['TId']; ?>
										</div>
										<div class="form-group col-xs-3 right-dot-margin">
											<?php echo $trans['TimeStamp']; ?>
										</div>
										<div class="form-group col-xs-2 right-dot-margin">
											<?php echo $trans['PaymtAmt']; ?>
										</div>
										<div class="form-group col-xs-2 right-dot-margin">
											<?php echo $trans['PaymtMode']; ?>
										</div>
										<div class="form-group col-xs-2">
											<?php echo $trans['PaymtStatus']; ?>
										</div>
									</div>
									<?php } } else { ?>
									<p>
										<strong>No payment transactions yet.</strong>
									</p>
								<?php } ?>
							  </div>
							</li>
						<?php } ?>
					</ul>
				</div>
			</section>
			<!-- Executive Details -->
			<section class="section-center1">
				<div class="section-header-2">
					<span class="confirm-title">Assigned Executives Details for Order ID : <?php if(isset($OId)) { echo $OId; } ?></span>
				</div>
				<div class="section-content1">
					<div class="congo-text">
						<span><strong>View / Add Executives</strong></span>
						<span style="float:right;margin-right:5%!important;cursor:pointer!important;" onclick="showModal();"><img src="/img/icons/add_active.png"></span>
					</div>
					<?php if(isset($execassigns) && count($execassigns) > 0) { foreach ($execassigns as $execassign) { ?>
						<div class="review-options-container">
							<div class="form-group col-xs-3 ht50 right-dot-margin margin-left-10px">
								<label>Executive Name</label><br>
								<label class="selected-options"><?php echo $execassign['ExecName'] ?></label>
							</div>
							<div class="form-group col-xs-3 ht50 right-dot-margin margin-left-10px">
								<label>Phone</label><br>
								<label class="selected-options"><?php echo $execassign['Phone'] ?></label>
							</div>
							<div class="form-group col-xs-3 ht50 right-dot-margin margin-left-10px">
								<label>Email</label><br>
								<label class="selected-options"><?php echo $execassign['Email'] ?></label>
							</div>
							<div class="form-group col-xs-3 ht50 margin-left-10px">
								<label>Timestamp</label><br>
								<label class="selected-options"><?php echo $execassign['Timestamp'] ?></label>
							</div>
						</div>
					<?php } } ?>
				</div>
			</section>
			<!-- Searched Options content -->
			<section class="section-center1">
				<div class="section-header-2">
					<span class="confirm-title">Order Details <?php if(isset($bikenumber)) { echo 'for Bike: ' . $bikenumber; } ?></span>
				</div>
				<div class="section-content1">
					<div class="congo-text">
						<span><strong><?php if(isset($uname)) { echo convert_to_camel_case($uname); } ?></strong>'s Order Details</span>
					</div>
					<div class="review-options-container">
						<?php if(isset($serid) && $serid != 3) { ?>
							<div class="form-group col-xs-3 ht50 right-dot-margin">
						<?php } else { ?>
							<div class="form-group col-xs-4 right-dot-margin"> 
						<?php } ?>
							<label>Order ID</label><br>
							<label class="selected-options"><?php if(isset($OId)) { echo $OId; } ?></label>
						</div>
						<?php if(isset($serid) && $serid != 3) { ?>
						<div class="form-group col-xs-3 ht50 right-dot-margin margin-left-10px">
							<label>Service Center</label><br>
							<?php
								if (isset($scenter)) {
									foreach ($scenter as $sc) {
										echo '<label class="selected-options">' . convert_to_camel_case($sc['ScName']) . '</label>';
									}
								}
							?>
						</div>
						<?php } ?>
						<?php if(isset($serid) && $serid != 3) { ?>
							<div class="form-group col-xs-3 ht50 right-dot-margin">
						<?php } else { ?>
							<div class="form-group col-xs-4 right-dot-margin"> 
						<?php } ?>
							<label>Service Type</label><br>
							<label class="selected-options"><?php if(isset($stype)) { echo $stype; } ?></label>
						</div>
						<?php if(isset($serid) && $serid != 3) { ?>
						<div class="form-group col-xs-3 ht50">
						<?php } else { ?>
						<div class="form-group col-xs-4"> 
						<?php } ?>
							<label>Bike Model</label><br>
							<label class="selected-options"><?php if(isset($bikemodel)) { echo convert_to_camel_case($bikemodel); } ?></label>
						</div>
						<div class="col-xs-3">
							<label>Time Slot</label><br>
							<label class="selected-options"><?php if(isset($timeslot)) { echo $timeslot; } ?></label> 
						</div>
						<div class="col-xs-3">
							<label>Timestamp</label><br>
							<label class="selected-options"><?php if(isset($timestamp)) { echo $timestamp; } ?></label> 
						</div>
						<div class="col-xs-3 pay-mode-final">
							<label>Mode of Payment&nbsp;&nbsp; :&nbsp;&nbsp;</label> 
							<label class="selected-options"><?php if(isset($paymode)) { echo convert_to_camel_case($paymode); } ?></label>
							<?php if(isset($isBreakdown) && ($isBreakdown == 1)) { ?>
								<label class="selected-options">Bike is in breakdown condition.</label>
							<?php } ?>
						</div>
					</div>
					<?php if(isset($omedia) && count($omedia) > 0) { ?>
						<div class="row">
							<div class="col s12 selected-options padding-10px margin-top-10px margin-bottom-10px">User Uploaded Images</div>
							<div class="col s12 padding-20px">
								<?php foreach($omedia as $om) { ?>
								<div class="col s12 m4 center-align"><img class="materialboxed" height="150px" width="150px" src="<?php echo get_awss3_url('uploads/omedia/' . $om['FileType'] . '/' . $om['FileData']); ?>"></div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
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
							<label>Previous Insurer</label><br>
							<label class="selected-options"><?php echo convert_to_camel_case($insren_details['InsurerName']); ?></label>
						</div>
						<div class="form-group col-xs-3 right-dot-margin margin-left-10px">
							<label>Previous Policy Expiry</label><br>
							<label class="selected-options"><?php if($insren_details['ExpiryDays'] == 0) { echo 'Already Expired'; } else { echo 'Next ' . $insren_details['ExpiryDays'] . ' Days'; } ?></label>
						</div>
						<div class="form-group col-xs-3 right-dot-margin margin-left-10px">
							<label>Registration Year</label><br>
							<label class="selected-options"><?php echo convert_to_camel_case($insren_details['RegYear']); ?></label>
						</div>
						<div class="form-group col-xs-3  margin-left-10px">
							<label>Claims Made Previously</label><br>
							<label class="selected-options"><?php if($insren_details['isClaimedBefore'] == 1) { echo 'Yes'; } else { echo 'No'; } ?></label>
						</div>
					</div>
					<br>
					<?php } ?>
					<div class="addr-container1">
						<div class="cursor-pointer" onclick="showAddressModal();">
							<div class="user-addr-container">
								<div class="user-addr-title">User Address</div>
								<div class="user-addr-content">
									<?php if(isset($uaddress)) { echo $uaddress; } ?>
								</div>
							</div>
						</div>
						<div class="sc-addr-container">
							<?php if(isset($serid) && $serid != 3) { ?>
								<div class="sc-addr-title">Service Centre Address</div>
							<?php } else { ?>
							<div class="sc-addr-title">Service Centre(s) Details</div>
							<?php } ?>
							<div class="sc-addr-content">
								<?php
									if(isset($serid) && $serid != 3) {
										if(isset($scaddress)) { echo $scaddress; }
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
		</div>
	</aside>
	<div id="addressDialog" title="Change User Address" style="display:none;width:700px !important;">
		<form method="POST" action="/admin/orders/updateUserAddress">
			<div class="row">
				<div class="col-xs-12 fields-update-container">
					<div class="col-xs-12 m6 l6 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" onchange="" name="AddrLine1" id="AddrLine1" onchange="validateAddress();" placeholder="Address Line 1" value="<?php if(isset($AddrLine1)) { echo $AddrLine1; } ?>">
						</div>
					</div>
					<div class="col-xs-12 m6 l6 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" onchange="" name="AddrLine2" id="AddrLine2" onchange="validateAddress();" placeholder="Address Line 2" value="<?php if(isset($AddrLine2)) { echo $AddrLine2; } ?>">
						</div>
					</div>
					<div class="col-xs-12 m6 l6 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" onchange="" name="location" id="location" onchange="validateAddress();" placeholder="Location" value="<?php if(isset($LocationName)) { echo $LocationName; } ?>">
						</div>
					</div>
					<div class="col-xs-12 m6 l6 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" onchange="" name="landmark" id="landmark" onchange="validateAddress();" placeholder="Landmark (Optional)" value="<?php if(isset($Landmark)) { echo $Landmark; } ?>">
						</div>
					</div>
				</div>
					<input type="hidden" name="UserId" value="<?php if(isset($UserId)) { echo $UserId; } ?>" />
					<input type="hidden" name="UserAddrId" value="<?php if(isset($UserAddrId)) { echo $UserAddrId; } ?>" />
					<input type="hidden" name="CityId" value="<?php if(isset($CityId)) { echo $CityId; } ?>" />
					<input type="hidden" name="OId" value="<?php if(isset($OId)) { echo $OId; } ?>" />
					<input type="hidden" name="latitude" id="latitude" value="<?php if(isset($Latitude)) { echo $Latitude; } ?>" />
					<input type="hidden" name="longitude" id="longitude" value="<?php if(isset($Longitude)) { echo $Longitude; } ?>" />
					<div class="col-xs-4 col-xs-offset-3 fields-container margin-top-10px">
						<button type="submit" class="btn" disabled="disabled" id="addressChangeButton">Change User Address</button>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div id="dialog" title="Add executives" style="display:none;width:700px !important;">
		<form method="POST" action="/admin/orders/od_assign_execs">
			<div class="row">
				<div class="col-xs-12">
					<div class="col-xs-6 col-xs-offset-3 fields-container margin-bottom-10px">
						<label>
							<input type="checkbox" class="form-control" name="smsuser" value="1"/>
							<span class="checkb-label cursor-pointer" style="margin-left:5px!important;margin-bottom:10px !important;">Send SMS to User</span>
						</label>
					</div>
					<?php if(isset($execs) && count($execs) > 0) { foreach($execs as $exec) { ?>
					<div class="col-xs-6 fields-container">
						<label>
							<input type="checkbox" class="form-control" name="execs[]" value="<?php echo $exec['ExecId']; ?>"/>
							<span class="checkb-label cursor-pointer" style="margin-left:5px!important;"><?php echo $exec['ExecName']; ?></span>
						</label>
					</div>
					<?php } } ?>
					<input type="hidden" name="oid" value="<?php if(isset($OId)) { echo $OId; } ?>" />
					<div class="col-xs-4 col-xs-offset-3 fields-container margin-top-10px">
						<button type="submit" class="btn">Assign Executives</button>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div id="reminderDialog" title="Set Reminder" style="display:none;width:700px !important;">
		<div class="row">
			<div class="col-xs-12 fields-update-container">
				<div class="col-xs-12 m6 l6 form-group fields-container">
					<div class="col-xs-12 field-box">
						<input type="text" class="form-control dpDate2" style="cursor:pointer;" onchange="reminderDateValidation();" name="reminder_date" id="reminder_date" placeholder="Reminder Date">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="rescheduleDialog" title="Reschedule Order" style="display:none;width:700px !important;">
		<form method="POST" action="/admin/orders/reschedule_order">
			<div class="row">
				<input type="hidden" name="oid" value="<?php if(isset($OId)) { echo $OId; } ?>" />
				<div class="col-xs-12 fields-update-container">
					<div class="col-xs-12 m6 l6 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control dpDate2" style="cursor:pointer;" onchange="rescheduleValidation();" name="res_date" id="res_ord_date" placeholder="Reschedule Date">
						</div>
					</div>
					<div class="col-xs-12 m6 l6 form-group fields-container">
						<div class="col-xs-12 field-box">
							<select class="form-control styled-select" onchange="rescheduleValidation();" name="res_time" id="res_ord_time">
								<option disabled selected style='display:none;' value=''>Reschedule Time</option>
								<option value="8">8 A.M.</option>
								<option value="9">9 A.M.</option>
								<option value="10">10 A.M.</option>
								<option value="11">11 A.M.</option>
								<option value="12">12 P.M.</option>
								<option value="13">1 P.M.</option>
								<option value="14">2 P.M.</option>
								<option value="15">3 P.M.</option>
								<option value="16">4 P.M.</option>
								<option value="17">5 P.M.</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-xs-12 fields-update-container">
					<div class="col-xs-6 form-group fields-container">
						<div class="col-xs-12 col-xs-offset-7 fields-container">
							<button id="rescheduleOrderButton" name="rescheduleOrderButton" type="submit" class="btn" disabled>Reschedule Order</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/highcharts.js'); ?>"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCZ126reFV784ZQTqw_JfD08mnS0jI7nWo&libraries=places"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/raty/jquery.raty.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/select2.full.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/account.js'); ?>"></script>
<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
<script type="text/javascript" src="<?php echo site_url('js/aodetail.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.multidatespicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.timepicker.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.ui.datepicker.validation.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/materialize.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/odetail.js?v=1.2'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.2'); ?>"></script>
<script>
	var send_sms_flag = 1;
	var ex_rtime_updates = <?php if(isset($ex_rtime_updates)) { echo json_encode($ex_rtime_updates); } else { echo 'null'; } ?>;
	var ex_fup_updates = <?php if(isset($ex_fup_updates)) { echo json_encode($ex_fup_updates); } else { echo 'null'; } ?>;
	var ex_ps_updates = <?php if(isset($ex_pre_servicing_updates)) { echo json_encode($ex_pre_servicing_updates); } else { echo 'null'; } ?>;
	var check_status_for_price = <?php if(isset($scenter)) { echo $scenter[0]['StatusId']; } ?>;
	var univ_sc_id = parseInt(<?php if(isset($scenter)) { echo $scenter[0]['ScId']; } else { echo '""'; } ?>);
	var univ_order_id = "<?php if(isset($OId)) { echo $OId; } ?>";
	var statserial = <?php if (isset($stathists)) { echo $stathists[count($stathists) - 1]['statserial']; } else { echo 'null'; } ?>;
	var est_time = <?php if (isset($estimations)) { echo '"' . $estimations['EstTime'] . '"'; } else { echo 'null'; } ?>;
	$(function() {
		<?php if(isset($send_final_message) && $send_final_message == 0) { ?>$('#thank_sms_txt').val('Thank you for utilizing our services, we look forward to serving you again. Rate our services on our app / visit gear6.in');<?php } ?>
		$('.serviceCenterRating').raty({number:5, score:Number(<?php echo $user_feedback_rating; ?>)}); $('.gear6Rating').raty({number:10, score:Number(<?php echo $nps; ?>)});
		<?php if(isset($is_breakdown_flag) && $is_breakdown_flag == 1) { ?>
			$('#isBreakdown').icheck('checked');
		<?php } ?>
		<?php if(isset($transport_mode) && $transport_mode != '' && $transport_mode != NULL && intval($transport_mode) > 0) { ?>
			$('#transport_mode').val('<?php if(isset($transport_mode)) { echo $transport_mode; } ?>').trigger('change');
		<?php } ?>
	});
</script>
</body>
</html>