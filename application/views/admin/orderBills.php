<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Orders Bills - Admin Panel</title>
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
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('nhome/js/lib/swal/sweetalert.css'); ?>">
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
					<small>Orders Bills</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Orders Bills</li>
				</ol>
			</section>
			<section>
				<div id="detail_tab" class="table-margin-top">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-gears"></i> &nbsp;&nbsp;Executive Name</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Cash in Hand</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Latest Transaction</th>
								<th class="last"><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Oldest Transaction</th>
							</tr>
						</thead>
						<tbody> 
							<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
							<tr>
								<td><a href="<?php echo site_url('admin/orders/executiveBills/' . $row['ExecId']); ?>" target="_blank" class="order-id-link"><?php echo convert_to_camel_case($row['ExecName']); ?></a></td>
								<td><?php echo $row['Phone']; ?></td>
								<td><?php echo $row['wallet']; ?></td>
								<td><?php echo $row['Latest']; ?></td>
								<td><?php echo $row['Oldest']; ?></td>
							</tr>
							<?php $count += 1; } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th class="first"><i class="fa fa-gears"></i> &nbsp;&nbsp;Executive Name</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Cash in Hand</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Latest Transaction</th>
								<th class="last"><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Oldest Transaction</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-xs-12" align="center">
						<h5><b>Order Bill Details - Export To CSV</b></h5>
					</div>
					<div class="col-xs-5 form-group fields-container">
						<input type="text" class="form-control dpDate2 cursor-pointer" readonly='true' onchange="validate_order_bills();" name="startDate" id="startDate" placeholder="Start Date">
					</div>
					<div class="col-xs-5 form-group fields-container">
						<input type="text" class="form-control dpDate2 cursor-pointer" readonly='true' onchange="validate_order_bills();" name="endDate" id="endDate" placeholder="End Date">
					</div>
					<div class="col-xs-2 form-group fields-container">
						<button class='next btn btn-primary btnUpdate-pu' id="filter" disabled="disabled">
							Get Bills
						</button>
					</div>
				</div>
			</section>
		</aside>
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.buttons.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jszip.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/buttons.html5.min.js'); ?>"></script>
<script src="<?php echo site_url('nhome/js/lib/swal/sweetalert.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script type="text/javascript">
	$('.dpDate2').datepicker ({
	    'dateFormat': "yy-mm-dd",
	    "setDate": new Date(),
	    'autoclose': true
	});
	function validate_order_bills() {
		var startDate = $("#startDate").val(); var endDate = $("#endDate").val(); var x = 1;
		if(startDate == "" || startDate == null || startDate == undefined || endDate == "" || endDate == null || endDate == undefined) {
			x = 1;
		} else {
			if(new Date(startDate) > new Date(endDate)) {
				x = 1;
			} else {
				x = 0;
			}
		}		
		if(x == 0) {
		    $("#filter").removeAttr('disabled');
		} else {
		    $("#filter").attr('disabled','disabled');
		}
	}
	$("#filter").on('click', function() {
		var form = '<form method="POST" action="/admin/orders/get_executive_bills_csv">';
		form += '<input name="startDate" value="' + $("#startDate").val() + '" />';
		form += '<input name="endDate" value="' + $("#endDate").val() + '" />';
		form += '<input type="submit" name="order_bills" value="submit" /></form>';
		var created_form = $(form).appendTo('body'); created_form.submit();
	});
</script>
</body>
</html>