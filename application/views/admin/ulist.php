<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Customers List - Admin Panel</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link rel="shortcut icon" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/vstyle.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/ionicons.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/module.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/datatables/buttons.dataTables.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/datatables/dataTables.bootstrap.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/jQueryUI/jquery-ui.css'); ?>">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oxygen" type="text/css">
</head>
<body>
	<style>
		.dt-button {
			margin-left: 7%;
			margin-bottom: 2%;
		}
	</style>
	<?php $this->load->view('admin/components/_head'); ?>
	<?php if(isset($a_is_logged_in) && $a_is_logged_in == 1) { ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<?php $this->load->view('admin/components/_sidebar'); ?>
		<aside class="right-side">
			<section class="content-header">
				<h1>
					Customers
					<small>List &amp; Analysis</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Customers List</li>
				</ol>
			</section>
			<section>
				<div class="adv-search-box-btn">
					<button class="btn btn-primary" id="adv-open">Advanced Search</button>
				</div>
				<div class="col-xs-12" id="adv-search-box" style="display:none">
					<section class="adv-section-center">
						<div class="adv-section-content">
							<div class="adv-section-header">
								<span class="confirm-title">Choose the parameters</span>
							</div>
							<div class="review-options-container">
								<div class="advSearch-container">
									<div class="form-group col-xs-4 stype" id="stype">
										<select class="form-control styled-select2" name="searchby_1" id="stypeDD_1">
											<option disabled selected style='display:none;'>Search By</option>
											<option value="oid">Order ID</option>
											<option value="BM">Bike Model</option>
											<option value="OD">Order Date</option>
											<option value="OT">Order Type</option>
											<option value="CT">Contact</option>
											<option value="CN">Customer Name</option>
										</select>
									</div>
									<div class="form-group col-xs-4  margin-left-10px">
										<select class="form-control styled-select2" name="filterby_1" id="filterBy_1">
											<option disabled selected style='display:none;'>Filter By</option>
											<option value="Periodic Servicing">Begins With</option>
											<option value="Repair">Ends With</option>
											<option value="Query">Exactly is</option>
											<option value="Insurance">Like</option>
										</select>
									</div>
									<div class="form-group col-xs-4  margin-left-10px" id="txtSearch_1">
										<input type="text" class="form-control" name="txtsearch_1" id="txt-search_1" placeholder="Enter Search Text">
									</div>
									<div class="form-group col-xs-4  margin-left-10px" id="bikeModel_1" style="display:none">
										<select class="form-control styled-select2" name="bikemodel_1" id="bikeModelDD_1">
											<option disabled selected style='display:none;'>Bike Model</option>
											<option value="Periodic Servicing">Hero CBZ</option>
											<option value="Repair">Hero Karizma</option>
											<option value="Query">Acheiver</option>
											<option value="Insurance">Discover</option>
										</select>
									</div>
									<div class="form-group col-xs-4  margin-left-10px" id="oType_1" style="display:none">
										<select class="form-control styled-select2" name="otype_1" id="oTypeDD_1">
											<option disabled selected style='display:none;'>Select Order Type</option>
											<option value="Periodic Servicing">Periodic Servicing</option>
											<option value="Repair">Repair</option>
											<option value="Query">Query</option>
											<option value="Insurance">Insurance Renewal</option>
										</select>
									</div>
									<div class="col-xs-4">
										<div class="form-group col-xs-6 margin-left-n40">
											<div class="checkbox">
												<label class="ignoreCase">
													<input type="checkbox" name="" checked value="" style="margin-right:5px">
													<span style="margin-left:5px">Ignore Case</span>
												</label>
											</div>
										</div>
										<div class="form-group col-xs-6 date-range-cb">
											<div class="checkbox">
												<label class="dateRangeCB" id="dr_1">
													<input type="checkbox" name="" value="" id="dateRangeCB_1" class="drCB" style="margin-right:5px">
													<span style="margin-left:5px">Select Date Range</span>
												</label>
											</div>
										</div>
									</div>
									<div class="date-range-container" id="dateRangeBox_1" style="display:none">
										<div class="col-xs-4" >
											<div class="form-group">
												<p>
													<input type="text" onchange=""  id="startDate_1" class="dpDate3" name="date_" readonly style="cursor:pointer;"placeholder="Start Date">
												</p>
											</div>
										</div>
										<div class="col-xs-4" >
											<div class="form-group">
												<p>
													<input type="text" onchange=""  id="endDate_1" class="dpDate3" name="date_" readonly style="cursor:pointer;"placeholder="End Date">
												</p>
											</div>
										</div>
									</div>
									<div class="col-xs-2 field-area-add" id="adv_add"></div>
								</div>
							</div>
							<div class="col-xs-12 text-center">
								<button type="submit" name="submitform" id="open" class="btn btn-primary action-btn">Search</button>
								<button type="submit" name="submitform" id="open" class="btn btn-primary action-btn" >Cancel</button>
							</div>
						</div>
					</section>
				</div>
				<div id="detail_tab" class="table-margin-top">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-user"></i> &nbsp;&nbsp;Customer</th>
								<th><i class="fa fa-map-marker"></i> &nbsp;&nbsp;Location</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-envelope"></i> &nbsp;&nbsp;Email</th>
								<th><i class="fa fa-envelope"></i> &nbsp;&nbsp;DOB</th>
								<th><i class="fa fa-sitemap"></i> &nbsp;&nbsp;Total Orders</th>
								<th class="last"><i class="fa fa-sitemap"></i> &nbsp;&nbsp;Registered On</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($rows) && count($rows) > 0) { foreach($rows as $row) { ?>
							<tr id="u_<?php echo $row['UserId']; ?>">
								<td><a href="<?php echo site_url('admin/users/udetails/' . $row['UserId']); ?>" class="order-id-link"><?php echo trim(convert_to_camel_case($row['UserName'])); ?></a></td>
								<td><?php echo convert_to_camel_case($row['LocationName']); ?></td>
								<td><?php echo $row['Phone']; ?></td>
								<td><?php echo $row['Email']; ?></td>
								<td><?php echo $row['DOB']; ?></td>
								<td><a href="<?php echo site_url('admin/users/uodetails/' . $row['UserId']); ?>" class="order-id-link"><?php echo $row['OCount']; ?></a></td>
								<td><?php echo $row['Timestamp']; ?></td>
							</tr>
							<?php } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th class="first"><i class="fa fa-user"></i> &nbsp;&nbsp;Customer</th>
								<th><i class="fa fa-map-marker"></i> &nbsp;&nbsp;Location</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-envelope"></i> &nbsp;&nbsp;Email</th>
								<th><i class="fa fa-envelope"></i> &nbsp;&nbsp;DOB</th>
								<th><i class="fa fa-sitemap"></i> &nbsp;&nbsp;Total Orders</th>
								<th class="last"><i class="fa fa-sitemap"></i> &nbsp;&nbsp;Registered On</th>
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
<script type="text/javascript" src="<?php echo site_url('js/highcharts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/app.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.buttons.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jszip.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/buttons.html5.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/exporting.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.knob.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.sparkline.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/raty/jquery.raty.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/avlist.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
</body>
</html>