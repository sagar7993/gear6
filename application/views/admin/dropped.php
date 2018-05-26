<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Dropped Orders (In the Review Page) - Admin Panel</title>
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
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>">
	<link rel="stylesheet" type="text/css" href="/nhome/js/lib/swal/sweetalert.css">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oxygen" type="text/css">
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
					<small>Review Page Dropped Orders</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Orders Dashboard</li>
				</ol>
			</section>
			<section>
				<div id="detail_tab" class="table-margin-top">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-gears"></i> &nbsp;&nbsp;Service Name</th>
								<th><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Service Center Name</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Phone</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Order Date</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Bike Model</th>
								<th><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Location Name</th>
								<th class="last"><i class="fa fa-hand-o-up"></i> &nbsp;&nbsp;Close</th>
							</tr>
						</thead>
						<tbody> 
							<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
							<tr id="<?php echo $count; ?>">
								<td><?php echo convert_to_camel_case($row['ServiceName']); ?></td>
								<td><?php echo convert_to_camel_case($row['ScName']); ?></td>
								<td><?php echo $row['Phone']; ?></td>
								<td><?php echo $row['Date']; ?></td>
								<td><?php echo convert_to_camel_case($row['BikeCompanyName'] . ' ' . $row['BikeModelName']); ?></td>
								<td><?php echo $row['LocationName']; ?></td>
								<td>
									<div class="checkbox" style="">
										<label class="paymode">
											<input type="checkbox"
												id="dropped_order_<?php echo $row['dropped_order_id']; ?>"
												name="dropped_order"
												value="<?php echo $row['dropped_order_id']; ?>"
												dropped-order-id="<?php echo $row['dropped_order_id']; ?>">
											</input>
										</label>
									</div>
								</td>
							</tr>
							<?php $count += 1; } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th class="first"><i class="fa fa-gears"></i> &nbsp;&nbsp;Service Name</th>
								<th><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Service Center Name</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Phone</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Order Date</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Bike Model</th>
								<th><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Location Name</th>
								<th class="last"><i class="fa fa-hand-o-up"></i> &nbsp;&nbsp;Close</th>
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
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.buttons.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jszip.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/buttons.html5.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/exporting.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.knob.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.sparkline.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script>
	$(function () {
		$(document).on('ifChecked', 'input[name=dropped_order]', function() {
			var elem = $(this); var dropped_order_id = elem.val(); showSwal(dropped_order_id);
		});
	});
	function showSwal(dropped_order_id) {
		swal({
			title: "Are you sure?",
			text: "You are about to close this dropped order.",
			type: "input",
			showCancelButton: true, 
			closeOnConfirm: false,
			closeOnCancel: true,
			animation: "slide-from-top",
			inputPlaceholder: "Write something"
		}, function(Reason) {
			if (Reason === false) {
				$('#dropped_order_' + dropped_order_id).icheck('unchecked');
				return false;
			}
			if (Reason === "") {
				swal.showInputError("Please state the reason for closing this dropped order.");
				return false;
			}
			if (Reason.length < 10) {
				swal.showInputError("The reason should be atleast 10 or more characters.");
				return false;
			}
		    var form = '<form action="/admin/orders/droppedorderstatus" method="POST">';
		    form += '<input type="hidden" name="dropped_order_id" value="' + dropped_order_id + '" />';
		    form += '<input type="hidden" name="Reason" value="' + Reason + '" />';
		    form += '<input type="submit" name="admin_submit" value="submit" /></form>';
		    var created_form = $(form).appendTo('body'); created_form.submit();
		});
	}
</script>
</body>
</html>