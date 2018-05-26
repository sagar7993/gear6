<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Modify Offers - Admin Panel</title>
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
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oxygen" type="text/css">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>">
	<link rel="stylesheet" type="text/css" href="/nhome/js/lib/swal/sweetalert.css">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.partial.css'); ?>">
	<style type="text/css">
		.select2-container{
			height: 3.9rem;
		}
		.select2-container--default .select2-selection--single .select2-selection__rendered {
			margin-top: 5px;
		}
	</style>
</head>
<body>
	<?php $this->load->view('admin/components/_head'); ?>
	<?php if(isset($a_is_logged_in) && $a_is_logged_in == 1) { ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<?php $this->load->view('admin/components/_sidebar'); ?>
		<aside class="right-side">
			<section class="content-header">
				<h1>
					Modify Offer
					<small>Modify Offer's Details</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i>Manage Offers</a></li>
					<li class="active">Modify Offer</li>
				</ol>
			</section>
			<section class="content">
				<?php if(isset($err_offer)) { echo '<div class="alert alert-danger" role="alert">' . $err_offer . '</div>'; } ?><br>
				<div class="col-xs-12 fields-update-container">
					<div class="col-xs-4 form-group fields-container" id="oType_1" style="">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" oninput="checkfieldOffer();" name="coupon_code" id="coupon_code" placeholder="Coupon Code">
						</div>
					</div>
					<div class=" col-xs-4 form-group fields-container" id="oType_1" style="">
						<div class="col-xs-12 field-box">
							<select class="form-control styled-select" onchange="checkfieldOffer();" name="coupon_type" id="coupon_type">
								<option disabled selected style='display:none;' value=''>Coupon Type</option>
								<option value="p">Percentage</option>
								<option value="f">Fixed Value</option>
							</select>
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="number" class="form-control" oninput="checkfieldOffer();" name="coupon_amount" id="coupon_amount" placeholder="Coupon Amount">
						</div>
					</div>
				</div>
				<div class="col-xs-12 fields-update-container">
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="number" class="form-control" oninput="checkfieldOffer();" name="maximum_discount" id="maximum_discount" placeholder="Maximum Discount">
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="number" class="form-control" oninput="checkfieldOffer();" name="minimum_purchase" id="minimum_purchase" placeholder="Minimum Purchase">
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="number" class="form-control" oninput="checkfieldOffer();" name=" maximum_uses" id="maximum_uses" placeholder="Maximum Uses">
						</div>
					</div>
				</div>
				<div class="col-xs-12 fields-update-container">
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="number" class="form-control" oninput="checkfieldOffer();" name="per_user_limit" id="per_user_limit" placeholder="Per User Limit">
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control dpDate2" readonly='true' style="cursor:pointer;" onchange="checkfieldOffer();" name="validFrom" id="validFrom" placeholder="Valid From">
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control dpDate2" readonly='true' style="cursor:pointer;" onchange="checkfieldOffer();" name="validTill" id="validTill" placeholder="Valid Till">
						</div>
					</div>
				</div>
				<div class="col-xs-12 fields-update-container">
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" oninput="checkfieldOffer();" name="user_id" id="user_id" placeholder="User">
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" oninput="checkfieldOffer();" name="service_id" id="service_id" placeholder="Service">
						</div>
					</div>
					<div class="col-xs-4 form-group fields-container">
						<div class="col-xs-12 field-box">
							<select class="form-control styled-select" onchange="checkfieldOffer();" name="is_enabled" id="is_enabled">
								<option disabled selected style='display:none;' value=''>Is Enabled</option>
								<option value="0">0</option>
								<option value="1">1</option>
							</select>
						</div>
					</div>
				</div>
				<div class="button-box-contact col-xs-12">
					<div class="button-container col-xs-6 col-xs-offset-4">
						 <button class='next btn btn-primary btnUpdate-pu' id="coupon_mod" disabled>
							Update Offer
						</button>
						 <button class='next btn btn-primary btnUpdate-pu' id="coupon_del" disabled>
							Delete Offer
						</button>
					</div>
				</div>
			</section>
			<section>
				<div id="detail_tab" class="table-margin-top">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-user"></i> &nbsp;&nbsp;Coupon Code</th>
								<th><i class="fa fa-edit"></i> &nbsp;&nbsp;Coupon Type</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Coupon Amount</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Max Discount</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Min Purchase</th>
								<th><i class="fa fa-user-times"></i> &nbsp;&nbsp;Max Uses</th>
								<th><i class="fa fa-user-times"></i> &nbsp;&nbsp;Per User Limit</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Valid From</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Valid Till</th>
								<th><i class="fa fa-check"></i> &nbsp;&nbsp;Is Enabled</th>
								<th><i class="fa fa-user"></i> &nbsp;&nbsp;User</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Service</th>
								<th class="last"><i class="fa fa-hand-o-up"></i> &nbsp;&nbsp;Select</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($rows) && count($rows) > 0) { foreach($rows as $row) { ?>
							<tr id="o_<?php echo $row['CouponId']; ?>">
								<td><a href="" class="order-id-link"><?php echo $row['CCode']; ?></a></td>
								<td><?php echo $row['CType']; ?></td>
								<td><?php echo $row['CAmount']; ?></td>
								<td><?php echo $row['MaxDiscount']; ?></td>
								<td><?php echo $row['MinPurchase']; ?></td>
								<td><?php echo $row['MaxUses']; ?></td>
								<td><?php echo $row['PerUserLimit']; ?></td>
								<td><?php echo $row['ValidFrom']; ?></td>
								<td><?php echo $row['ValidTill']; ?></td>
								<td><?php echo $row['isEnabled']; ?></td>
								<td><?php echo $row['UserId']; ?></td>
								<td><?php echo $row['ServiceId']; ?></td>
								<td>
									<div class="checkbox" style="">
										<label class="paymode">
											<input type="radio" name="coupon_id" 
												id="check_<?php echo $row['CouponId']; ?>"
												value="<?php echo $row['CouponId']; ?>"
												coupon-id="<?php echo $row['CouponId']; ?>"
												coupon-code="<?php echo $row['CCode']; ?>"
												coupon-type="<?php echo $row['CType']; ?>"
												coupon-amount="<?php echo $row['CAmount']; ?>"
												coupon-maximum-discount="<?php echo $row['MaxDiscount']; ?>"
												coupon-minimum-purchase="<?php echo $row['MinPurchase']; ?>"
												coupon-maximum-uses="<?php echo $row['MaxUses']; ?>"
												coupon-per-user-limit="<?php echo $row['PerUserLimit']; ?>"
												coupon-valid-from="<?php echo $row['ValidFrom']; ?>"
												coupon-valid-till="<?php echo $row['ValidTill']; ?>"
												coupon-is-enabled="<?php echo $row['isEnabled']; ?>"
												coupon-user-id="<?php echo $row['UserId']; ?>"
												coupon-service-id="<?php echo $row['ServiceId']; ?>">
											</input>
										</label>
									</div>
								</td>
							</tr>
							<?php } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th class="first"><i class="fa fa-user"></i> &nbsp;&nbsp;Coupon Code</th>
								<th><i class="fa fa-edit"></i> &nbsp;&nbsp;Coupon Type</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Coupon Amount</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Max Discount</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Min Purchase</th>
								<th><i class="fa fa-user-times"></i> &nbsp;&nbsp;Max Uses</th>
								<th><i class="fa fa-user-times"></i> &nbsp;&nbsp;Per User Limit</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Valid From</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Valid Till</th>
								<th><i class="fa fa-check"></i> &nbsp;&nbsp;Is Enabled</th>
								<th><i class="fa fa-user"></i> &nbsp;&nbsp;User</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Service</th>
								<th class="last"><i class="fa fa-hand-o-up"></i> &nbsp;&nbsp;Select</th>
							</tr>
						</tfoot>
					</table>
				</div><!-- Detail Tab -- >
			</section>
		</aside><!-- /.right-side -->
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
	<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/select2.full.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/avlist.js'); ?>"></script>
	<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
	<script type="text/javascript" src="<?php echo site_url('js/addoffer.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
</body>
</html>