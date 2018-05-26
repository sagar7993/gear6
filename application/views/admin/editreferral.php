<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Modify Referral Coupons - Admin Panel</title>
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
</head>
<body>
	<?php $this->load->view('admin/components/_head'); ?>
	<?php if(isset($a_is_logged_in) && $a_is_logged_in == 1) { ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<?php $this->load->view('admin/components/_sidebar'); ?>
		<aside class="right-side">
			<section class="content-header">
				<h1>
					Modify Referral Coupon
					<small>Modify referral coupon's details</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i>Manage Referral Coupons</a></li>
					<li class="active">Modify Referral Coupon</li>
				</ol>
			</section>
			<section class="content">
				<?php if(isset($err_offer)) { echo '<div class="alert alert-danger" role="alert">' . $err_offer . '</div>'; } ?><br>
				<div class="col-xs-12 fields-update-container">
					<div class="col-xs-6 form-group fields-container" id="oType_1" style="">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" oninput="checkfieldReferral();" name="referral_coupon_code" id="referral_coupon_code" placeholder="Referral Coupon Code">
						</div>
					</div>
					<div class="col-xs-6 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="number" class="form-control" oninput="checkfieldReferral();" name="referral_coupon_amount" id="referral_coupon_amount" placeholder="Referral Coupon Amount">
						</div>
					</div>
				</div>
				<div class="col-xs-12 fields-update-container">
					<div class="col-xs-6 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control dpDate2" readonly='true' style="cursor:pointer;" onchange="checkfieldReferral();" name="referral_valid_till" id="referral_valid_till" placeholder="Valid Till">
						</div>
					</div>
					<div class="col-xs-6 form-group fields-container">
						<div class="col-xs-12 field-box">
							<input type="text" class="form-control" oninput="checkfieldReferral();" name="referral_user_id" id="referral_user_id" placeholder="User">
						</div>
					</div>
				</div>
				<div class="button-box-contact col-xs-12">
					<div class="button-container col-xs-6 col-xs-offset-4">
						 <button class='next btn btn-primary btnUpdate-pu' id="referral_coupon_mod" disabled>
							Update Referral Coupon
						</button>
						 <button class='next btn btn-primary btnUpdate-pu' id="referral_coupon_del" disabled>
							Delete Referral Coupon
						</button>
					</div>
				</div>
			</section>
			<section>
				<div id="detail_tab" class="table-margin-top">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-user"></i> &nbsp;&nbsp;Referral Coupon Code</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Coupon Amount</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Valid Till</th>
								<th><i class="fa fa-user"></i> &nbsp;&nbsp;User</th>
								<th class="last"><i class="fa fa-hand-o-up"></i> &nbsp;&nbsp;Select</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($rows) && count($rows) > 0) { foreach($rows as $row) { ?>
							<tr id="o_<?php echo $row['FCouponId']; ?>">
								<td><a href="" class="order-id-link"><?php echo $row['CCode']; ?></a></td>
								<td><?php echo $row['CAmount']; ?></td>
								<td><?php echo $row['ValidTill']; ?></td>
								<td><?php echo $row['UserId']; ?></td>
								<td>
									<div class="checkbox" style="">
										<label class="paymode">
											<input type="radio" name="referral_id" 
												id="check_<?php echo $row['FCouponId']; ?>"
												value="<?php echo $row['FCouponId']; ?>"
												referral-coupon-id="<?php echo $row['FCouponId']; ?>"
												referral-coupon-code="<?php echo $row['CCode']; ?>"
												referral-coupon-amount="<?php echo $row['CAmount']; ?>"
												referral-coupon-valid-till="<?php echo $row['ValidTill']; ?>"
												referral-coupon-user-id="<?php echo $row['UserId']; ?>">
											</input>
										</label>
									</div>
								</td>
							</tr>
							<?php } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th class="first"><i class="fa fa-user"></i> &nbsp;&nbsp;Referral Coupon Code</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Coupon Amount</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Valid Till</th>
								<th><i class="fa fa-user"></i> &nbsp;&nbsp;User</th>
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
	<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/avlist.js'); ?>"></script>
	<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
	<script type="text/javascript" src="<?php echo site_url('js/addoffer.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
</body>
</html>