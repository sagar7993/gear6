<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - View Petty Cash - Admin Panel</title>
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
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.partial.css'); ?>">
	<link rel="stylesheet" type="text/css" href="/nhome/js/lib/swal/sweetalert.css">
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
					View Petty Cash
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i>View Petty Cash</a></li>
					<li class="active">Petty Cash</li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-xs-12" align="center">
						<h5><b>Filter by date</b></h5>
					</div>
					<div class="col-xs-5 form-group fields-container">
						<input type="text" class="form-control dpDate2 cursor-pointer" readonly='true' onchange="validate_rewards();" name="startDate" id="startDate" placeholder="Start Date" value="<?php echo $startDate; ?>">
					</div>
					<div class="col-xs-5 form-group fields-container">
						<input type="text" class="form-control dpDate2 cursor-pointer" readonly='true' onchange="validate_rewards();" name="endDate" id="endDate" placeholder="End Date" value="<?php echo $endDate; ?>">
					</div>
					<div class="col-xs-2 form-group fields-container">
						<button class='next btn btn-primary btnUpdate-pu' id="filter" disabled="disabled">
							Filter
						</button>
					</div>
				</div>
			</section>
			<section class="content">
				<label class="selected-options">&nbsp;&nbsp;&nbsp;&nbsp;Filter Results</label>
				<div class="row">
					<div class="col-xs-12">
						<div class="col-xs-4">
							<div class="checkbox" style="">
								<label class="search-filter">
									<input type="radio" name="search-filter" class="odstatusfilter" value="asdfawe4gsbaerawtgaer" id="radio_1">
									<span style="margin-left:15px">Approved</span>
								</label>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="checkbox" style="">
								<label class="search-filter">
									<input type="radio" name="search-filter" class="odstatusfilter" value="asdre4hbsfefvzdfszdf" id="radio_2">
									<span style="margin-left:15px">Un-Approved</span>
								</label>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="checkbox" style="">
								<label class="search-filter">
									<a href="javascript:;" name="clear-search-filter" id="osclearfilter">Reset</a>
								</label>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section class="content">
				<div id="detail_tab" class="table-margin-top">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first" style="display:none;"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Order ID</th>
								<th><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Order ID</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Executive</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Date</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Amount</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Description</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Status</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Rejection Reason</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Updated By</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Updated On</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Approve</th>
								<th class="last"><i class="fa fa-gears"></i> &nbsp;&nbsp;Reject</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
							<tr id="<?php echo $count; ?>">
								<?php if($row['status'] == 'Approved') { ?>
									<td style="display:none;" data-search="asdfawe4gsbaerawtgaer"><?php echo $row['OId']; ?></td>
								<?php } elseif($row['status'] == 'Pending' || $row['status'] == 'Rejected') { ?>
									<td style="display:none;" data-search="asdre4hbsfefvzdfszdf"><?php echo $row['OId']; ?></td>
								<?php } ?>
								<td>
									<a target="_blank" href="<?php echo site_url('admin/orders/odetail/' . $row['OId']); ?>"
									class="order-id-link">
										<?php echo $row['OId']; ?>
									</a>
								</td>
								<td><?php echo $row['ExecName']; ?></td>
								<td><?php echo $row['Phone']; ?></td>
								<td><?php echo $row['date']; ?></td>
								<td><?php echo $row['Amount']; ?></td>
								<td><?php echo $row['Description']; ?></td>
								<td><?php echo $row['status']; ?></td>
								<td><?php echo $row['rejection_reason']; ?></td>
								<td><?php echo $row['UpdatedBy']; ?></td>
								<td><?php echo $row['updated_at']; ?></td>
								<td>
									<div class="checkbox">
										<label class="paymode">
											<input type="checkbox" id="check_approve_<?php echo $row['id']; ?>" name="approve" value="<?php echo $row['id']; ?>"<?php if($row['status'] == 'Approved') { echo " checked data-checked='1'"; } else { echo "data-checked='0'"; } ?>>
											</input>
										</label>
									</div>
								</td>
								<td>
									<div class="checkbox">
										<label class="paymode">
											<input type="checkbox" id="check_reject_<?php echo $row['id']; ?>" name="reject" value="<?php echo $row['id']; ?>"<?php if($row['status'] == 'Rejected') { echo " checked data-checked='1'"; } else { echo "data-checked='0'"; } ?>>
											</input>
										</label>
									</div>
								</td>
							</tr>
							<?php $count += 1; } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th class="first" style="display:none;"><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Order ID</th>
								<th><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Order ID</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Executive</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Date</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Amount</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Description</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Status</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Rejection Reason</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Updated By</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Updated On</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Approve</th>
								<th class="last"><i class="fa fa-gears"></i> &nbsp;&nbsp;Reject</th>
							</tr>
						</tfoot>
					</table>
				</div>
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
<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/execreward.js'); ?>"></script>
</body>
</html>