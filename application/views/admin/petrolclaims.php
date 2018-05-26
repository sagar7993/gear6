<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Petrol Claims - Admin Panel</title>
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
					Petrol Claims
					<small>Petrol Claims</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i>Manage Executives</a></li>
					<li class="active">Petrol Claims</li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class=" col-xs-12 form-group fields-container" id="oType_1">
						<div class="col-xs-5 field-box">
							<div class="form-group">
								<input type="text" value="<?php if(isset($startDate)) { echo $startDate; } ?>" class="form-control date-field" onchange="checkfield();" id="startDate" name="startDate" placeholder="Start Date" readonly onfocus="this.removeAttribute('readonly');">
							</div>
						</div>
						<div class="col-xs-5 field-box">
							<div class="form-group">
								<input type="text" value="<?php if(isset($endDate)) { echo $endDate; } ?>" class="form-control date-field" onchange="checkfield();" id="endDate" name="endDate" placeholder="End Date" readonly onfocus="this.removeAttribute('readonly');">
							</div>
						</div>
						<div class="col-xs-2 field-box">
							<div class="form-group">
								<button class='next btn btn-primary btnUpdate-pu' id="search" disabled>Search</button>
							</div>
						</div>
					</div>
				</div>
				<label class="selected-options">&nbsp;&nbsp;&nbsp;&nbsp;Filter Petrol Claims</label>
				<div class="row">
					<div class="col-xs-12">
						<div class="col-xs-4">
							<div class="checkbox">
								<label class="search-filter">
									<input type="radio" name="search-filter" class="odstatusfilter" value="1" id="radio_1">
									<span style="margin-left:15px">Approved</span>
								</label>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="checkbox">
								<label class="search-filter">
									<input type="radio" name="search-filter" class="odstatusfilter" value="2" id="radio_2">
									<span style="margin-left:15px">Rejected</span>
								</label>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="checkbox">
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
								<th style="display:none;"><i class="fa fa-user"></i> &nbsp;&nbsp;Executive Name</th>
								<th class="first"><i class="fa fa-user"></i> &nbsp;&nbsp;Executive Name</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Date</th>
								<th><i class="fa fa-comment-o"></i> &nbsp;&nbsp;Purpose</th>
								<th><i class="fa fa-map-marker"></i> &nbsp;&nbsp;Start Location</th>
								<th><i class="fa fa-map-marker"></i> &nbsp;&nbsp;End Location</th>
								<th><i class="fa fa-location"></i> &nbsp;&nbsp;Distance</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Price</th>
								<th><i class="fa fa-clock-o"></i> &nbsp;&nbsp;Start Time</th>
								<th><i class="fa fa-clock-o"></i> &nbsp;&nbsp;End Time</th>
								<th><i class="fa fa-ban"></i> &nbsp;&nbsp;Denial Reason</th>
								<th><i class="fa fa-hand-o-up"></i> &nbsp;&nbsp;Approve</th>
								<th class="last"><i class="fa fa-hand-o-up"></i> &nbsp;&nbsp;Deny</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($rows) && count($rows) > 0) { foreach($rows as $row) { ?>
								<tr id="p_<?php echo $row['PetrolBillsId']; ?>">
									<td style="display:none;" id="search_<?php echo $row['PetrolBillsId']; ?>" data-search="<?php echo $row['isApproved']; ?>"><?php echo convert_to_camel_case($row['ExecName']); ?></td>
									<td><?php echo convert_to_camel_case($row['ExecName']); ?></td>
									<td><?php echo $row['Date']; ?></td>
									<td><?php echo $row['Purpose']; ?></td>
									<td><?php echo $row['SLocation']; ?></td>
									<td><?php echo $row['ELocation']; ?></td>
									<td><?php echo $row['Kms']; ?></td>
									<td><?php echo $row['Price']; ?></td>
									<td><?php echo $row['StartTimestamp']; ?></td>
									<td><?php echo $row['EndTimestamp']; ?></td>
									<td><?php echo $row['DeniedReason']; ?></td>
									<td>
										<div class="checkbox">
											<label class="paymode">
												<input type="checkbox"
												id="check_approve_<?php echo $row['PetrolBillsId']; ?>"
												name="petrolbills_approve"
												value="<?php echo $row['PetrolBillsId']; ?>"
												petrolbills-id="<?php echo $row['PetrolBillsId']; ?>"
												petrolbills-date="<?php echo $row['Date']; ?>"
												petrolbills-purpose="<?php echo $row['Purpose']; ?>"
												petrolbills-is-approved="<?php echo $row['isApproved']; ?>">
												</input>
											</label>
										</div>
									</td>
									<td>
										<div class="checkbox">
											<label class="paymode">
												<input type="checkbox"
												id="check_deny_<?php echo $row['PetrolBillsId']; ?>"
												name="petrolbills_deny"
												value="<?php echo $row['PetrolBillsId']; ?>"
												petrolbills-id="<?php echo $row['PetrolBillsId']; ?>"
												petrolbills-date="<?php echo $row['Date']; ?>"
												petrolbills-purpose="<?php echo $row['Purpose']; ?>"
												petrolbills-is-approved="<?php echo $row['isApproved']; ?>">
												</input>
											</label>
										</div>
									</td>
								</tr>
							<?php } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th style="display:none;"><i class="fa fa-user"></i> &nbsp;&nbsp;Executive Name</th>
								<th class="first"><i class="fa fa-user"></i> &nbsp;&nbsp;Executive Name</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Date</th>
								<th><i class="fa fa-comment-o"></i> &nbsp;&nbsp;Purpose</th>
								<th><i class="fa fa-map-marker"></i> &nbsp;&nbsp;Start Location</th>
								<th><i class="fa fa-map-marker"></i> &nbsp;&nbsp;End Location</th>
								<th><i class="fa fa-location"></i> &nbsp;&nbsp;Distance</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Price</th>
								<th><i class="fa fa-clock-o"></i> &nbsp;&nbsp;Start Time</th>
								<th><i class="fa fa-clock-o"></i> &nbsp;&nbsp;End Time</th>
								<th><i class="fa fa-ban"></i> &nbsp;&nbsp;Denial Reason</th>
								<th><i class="fa fa-hand-o-up"></i> &nbsp;&nbsp;Approve</th>
								<th class="last"><i class="fa fa-hand-o-up"></i> &nbsp;&nbsp;Deny</th>
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
	<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
	<script type="text/javascript" src="<?php echo site_url('js/petrolClaims.js?v=1.0'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
	<script type="text/javascript">		
		$("input[type='checkbox'], input[type='radio']").icheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green'
		});
		<?php if(isset($rows) && count($rows) > 0) { foreach($rows as $row) { ?>
			<?php if($row['isApproved'] == '1') { ?>
				$('#check_approve_<?php echo $row['PetrolBillsId'] ?>').icheck('checked');
				$('#check_approve_<?php echo $row['PetrolBillsId'] ?>').attr("data-checked", '1');
				$('#check_deny_<?php echo $row['PetrolBillsId'] ?>').attr("data-checked", '0');
			<?php } ?>
			<?php if($row['isApproved'] == '2') { ?>
				$('#check_deny_<?php echo $row['PetrolBillsId'] ?>').icheck('checked');
				$('#check_approve_<?php echo $row['PetrolBillsId'] ?>').attr("data-checked", '0');
				$('#check_deny_<?php echo $row['PetrolBillsId'] ?>').attr("data-checked", '1');
			<?php } ?>
		<?php } } ?>
	</script>
</body>
</html>