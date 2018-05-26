<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Puncture Orders - Admin Panel</title>
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
					Dashboard
					<small>Puncture Orders</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Orders Dashboard</li>
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
				<label class="selected-options">&nbsp;&nbsp;&nbsp;&nbsp;Filter Puncture Orders</label>
				<div class="row">
					<div class="col-xs-12">
						<div class="col-xs-4">
							<div class="checkbox">
								<label class="search-filter">
									<input type="radio" name="search-filter" class="odstatusfilter" value="1" id="radio_1">
									<span style="margin-left:15px">Cleared</span>
								</label>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="checkbox">
								<label class="search-filter">
									<input type="radio" name="search-filter" class="odstatusfilter" value="0" id="radio_2">
									<span style="margin-left:15px">Pending</span>
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
								<th style="display:none;" class="first"><i class="fa fa-gears"></i> &nbsp;&nbsp;Email</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Email</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Phone</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Date</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Timestamp</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Bike Model</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Tyre Type</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Punctured Tyre</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Description</th>
								<th><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Location Name</th>
								<th class="last"><i class="fa fa-hand-o-up"></i> &nbsp;&nbsp;Clear</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
							<tr id="<?php echo $count; ?>">
								<td style="display:none;" id="clear_row_<?php echo $row['PtOrderId']; ?>" data-search="<?php echo $row['isCleared']; ?>"><?php echo $row['Email']; ?></td>
								<td><?php echo $row['Email']; ?></td>
								<td><?php echo $row['Phone']; ?></td>
								<td><?php echo $row['ODate']; ?></td>
								<td><?php echo $row['Timestamp']; ?></td>
								<td><?php echo convert_to_camel_case($row['BikeCompanyName'] . ' ' . $row['BikeModelName']); ?></td>
								<td><?php echo $row['TyreType']; ?></td>
								<td><?php echo $row['PTyre']; ?></td>
								<td><?php echo $row['Description']; ?></td>
								<td><?php echo $row['LocationName']; ?></td>
								<td>
									<div class="checkbox"><label class="paymode">
										<input type="checkbox" data-checked="<?php echo $row['isCleared']; ?>" id="clear_<?php echo $row['PtOrderId']; ?>" name="clear" value="<?php echo $row['PtOrderId']; ?>"<?php if($row['isCleared'] == 1) { echo ' checked'; } ?>></input>
									</label></div>
								</td>
							</tr>
							<?php $count += 1; } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th style="display:none;" class="first"><i class="fa fa-gears"></i> &nbsp;&nbsp;Email</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Email</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Phone</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Date</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Timestamp</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Bike Model</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Tyre Type</th>
								<th><i class="fa fa-gears"></i> &nbsp;&nbsp;Punctured Tyre</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Description</th>
								<th><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Location Name</th>
								<th class="last"><i class="fa fa-hand-o-up"></i> &nbsp;&nbsp;Clear</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</section>
		</aside>
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/highcharts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script type="text/javascript">
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
	$('.date-field').datepicker ({
		'dateFormat': "yy-mm-dd",
		"setDate": new Date(),
		'autoclose': true
	});
	$("input[type='radio']").icheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
	$('.odstatusfilter').on("ifChecked", function() {
		var query = $(this).val();
		oTable.fnFilter(query, 0, true, false);
	});
	$('#osclearfilter').on('click', function() {
		for (var i = 1; i <= 2; i++) {
			$('#radio_' + i).icheck('unchecked');
		}
		oTable.fnFilter('', 0, true, false);
	});
	$(document).on('ifChanged', 'input[name=clear]', function() {
		var elem = $(this); var id = elem.val(); var checked = elem.attr('data-checked');
		if(checked == '1') {
			swal({
				title: "Are you sure?",
				text: "You are about to un-clear this order.",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes",
				cancelButtonText: "Cancel",
				closeOnConfirm: false,
				closeOnCancel: true,
				showLoaderOnConfirm: true,
				animation: "slide-from-top"
			}, function(isConfirm) {
				if (isConfirm) {
					$.ajax({
						type: "POST",
						url: "/admin/orders/clear_pt_order",
						data: { "PtOrderId": id, "isCleared": 0 }, 
						dataType: "text",
						success: function(data) {
							$('#clear_' + id).attr("data-checked", '0');
							$('#clear_row_' + id).attr("data-search", '0');
							oTable.fnFilter('', 0, true, false);
							swal('Success', 'Un-Cleared', 'success');
						},
						error: function(error) {
							$('#clear_' + id).attr("data-checked", '1');
							$('#clear_' + id).icheck('checked');
							swal('Error', 'Oops.. Something went wrong.', 'error');
						}
					});
				} else {
					$('#clear_' + id).attr("data-checked", '1');
					$('#clear_' + id).icheck('checked');
				}
				return true;
			});
		} else if(checked == '0') {
			swal({
				title: "Are you sure?",
				text: "You are about to clear this order.",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes",
				cancelButtonText: "Cancel",
				closeOnConfirm: false,
				closeOnCancel: true,
				showLoaderOnConfirm: true,
				animation: "slide-from-top"
			}, function(isConfirm) {
				if (isConfirm) {
					$.ajax({
						type: "POST",
						url: "/admin/orders/clear_pt_order",
						data: { "PtOrderId": id, "isCleared": 1 }, 
						dataType: "text",
						success: function(data) {
							$('#clear_' + id).attr("data-checked", '1');
							$('#clear_row_' + id).attr("data-search", '1');
							oTable.fnFilter('', 0, true, false);
							swal('Success', 'Cleared', 'success');
						},
						error: function(error) {
							$('#clear_' + id).attr("data-checked", '0');
							$('#clear_' + id).icheck('unchecked');
							swal('Error', 'Oops.. Something went wrong.', 'error');
						}
					});
				} else {
					$('#clear_' + id).attr("data-checked", '0');
					$('#clear_' + id).icheck('unchecked');
				}
				return true;
			});
		}
	});
	function checkfield() {
		var x = 0;
		var startDate = $('#startDate').val();
		var endDate = $('#endDate').val();
		if(startDate === "" || startDate == "" || startDate === null || startDate == null) {
			x = 1;
		}
		if(endDate === "" || endDate == "" || endDate === null || endDate == null) {
			x = 1;
		}
		if(x == 0) {
			$("#search").removeAttr('disabled');
		} else {
			$("#search").attr('disabled','disabled');
		}
	}
	$('#search').on('click', function(e) {
		var form = '<form action="/admin/orders/ptorders" method="POST">';
		form += '<input type="hidden" name="startDate" value="' + $('#startDate').val() + '" />';
		form += '<input type="hidden" name="endDate" value="' + $('#endDate').val() + '" />';
		form += '<input type="submit" name="admin_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body'); created_form.submit();
	});
	$(document).ready(function() {
	    $('#radio_2').trigger('click');
	});
</script>
</body>
</html>