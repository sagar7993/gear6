<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Executive Bills - Admin Panel</title>
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
					<small>Orders Bills</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Executive Bills</li>
				</ol>
			</section>
			<section>
				<div id="detail_tab" class="table-margin-top">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-gears"></i> &nbsp;&nbsp;Order Id</th>
								<th><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Customer</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Order Date</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Online Amount</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Cash Amount</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Total Amount</th>
								<th><i class="fa fa-hand-o-up"></i> &nbsp;&nbsp;Cash Submitted</th>
								<th class="last"><i class="fa fa-hand-o-up"></i> &nbsp;&nbsp;Bill Submitted</th>
							</tr>
						</thead>
						<tbody> 
							<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
							<tr id="<?php echo $count; ?>">
								<td><a href="<?php echo site_url('admin/orders/odetail/' . $row['OId']); ?>" target="_blank" class="order-id-link"><?php echo $row['OId']; ?></a></td>
								<td><a href="<?php echo site_url('admin/users/uodetails/' . $row['UserId']); ?>" target="_blank" class="order-id-link"><?php echo convert_to_camel_case($row['UserName']); ?></a></td>
								<td><?php echo $row['Phone']; ?></td>
								<td><?php echo $row['ODate']; ?></td>
								<td><?php echo $row['onlineAmount']; ?></td>
								<td><?php echo $row['cashAmount']; ?></td>
								<td><?php echo $row['totalAmount']; ?></td>
								<td>
									<div class="checkbox" style="">
										<label class="paymode">
											<input type="checkbox" id="cash_submitted_<?php echo $row['ExecBillId']; ?>"
											name="cash_submitted" value="<?php echo $row['ExecBillId']; ?>"<?php if($row['isCashSubmitted'] == "1") { echo " checked"; } ?>>
											</input>
										</label>
									</div>
								</td>
								<td>
									<div class="checkbox" style="">
										<label class="paymode">
											<input type="checkbox" id="bill_submitted_<?php echo $row['ExecBillId']; ?>"
											name="bill_submitted" value="<?php echo $row['ExecBillId']; ?>"<?php if($row['isBillSubmitted'] == "1") { echo " checked"; } ?>>
											</input>
										</label>
									</div>
								</td>
							</tr>
							<?php $count += 1; } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th class="first"><i class="fa fa-gears"></i> &nbsp;&nbsp;Order Id</th>
								<th><i class="fa fa-bookmark"></i> &nbsp;&nbsp;Customer</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Order Date</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Online Amount</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Cash Amount</th>
								<th><i class="fa fa-money"></i> &nbsp;&nbsp;Total Amount</th>
								<th><i class="fa fa-hand-o-up"></i> &nbsp;&nbsp;Cash Submitted</th>
								<th class="last"><i class="fa fa-hand-o-up"></i> &nbsp;&nbsp;Bill Submitted</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-xs-12" align="center">
						<h5><b>Approved Order Bills</b></h5>
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
				<div class="row">
					<div id="detail_tab2" class="table-margin-top" style="display:none;">
						<table id="example2" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						</table>
					</div>
				</div>
			</section>
			<br/><br/>
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
<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script type="text/javascript">
	var oTable = null;
	function submit_form(ExecBillId, flag, column) {
		var form = '<form action="/admin/orders/update_exec_bill" method="POST">';
		form += '<input type="hidden" name="ExecBillId" value="' + ExecBillId + '" />';
		form += '<input type="hidden" name="flag" value="' + flag + '" />';
		form += '<input type="hidden" name="column" value="' + column + '" />';
		form += '<input type="hidden" name="ExecId" value="<?php echo $executive; ?>" />';
		form += '<input type="submit" name="update_exec_bill_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body'); created_form.submit();
	}
	$(document).on('ifChecked', 'input[name=cash_submitted]', function() {
		var elem = $(this); var execBillId = elem.val(); submit_form(execBillId, 1, "isCashSubmitted");
	});
	$(document).on('ifUnchecked', 'input[name=cash_submitted]', function() {
		var elem = $(this); var execBillId = elem.val(); submit_form(execBillId, 0, "isCashSubmitted");
	});
	$(document).on('ifChecked', 'input[name=bill_submitted]', function() {
		var elem = $(this); var execBillId = elem.val(); submit_form(execBillId, 1, "isBillSubmitted");
	});
	$(document).on('ifUnchecked', 'input[name=bill_submitted]', function() {
		var elem = $(this); var execBillId = elem.val(); submit_form(execBillId, 0, "isBillSubmitted");
	});
	$(document).on('ifUnchecked', 'input[name=cash_submitted_approved]', function() {
		var elem = $(this); var execBillId = elem.val(); submit_form(execBillId, 0, "isCashSubmitted");
	});
	$(document).on('ifUnchecked', 'input[name=bill_submitted_approved]', function() {
		var elem = $(this); var execBillId = elem.val(); submit_form(execBillId, 0, "isBillSubmitted");
	});
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
		$.ajax({
			type: "POST",
			url: "/admin/orders/get_approved_executive_bills",
			data: { startDate : $("#startDate").val(), endDate : $("#endDate").val(), ExecId : <?php echo $executive; ?> },
			dataType: "json",
			cache: false,
			success: function(data) {
				if(oTable != null) { oTable.fnDestroy(); }
				$('#example2').empty(); $('#detail_tab2').css("display", "block");
				try {
					if(data.length == 0) {
						swal("Error", "No records found.", "error");
					} else {
						oTable = $('#example2').dataTable({
							data: data,
							bSearchable: true,
							bSortable: true,
							bInfo: true,
							bLengthChange: true,
							bPaginate: true,
							bFilter: true,
							buttons: [],
							aaSorting: [],
							bDestroy : true,
							columns: [
								{ title: "Order Id" },
								{ title: "Customer" },
								{ title: "Contact" },
								{ title: "Order Date" },
								{ title: "Online Amount" },
								{ title: "Cash Amount" },
								{ title: "Total Amount" },
								{ title: "Cash Submitted" },
								{ title: "Bill Submitted" }
							],
							oLanguage: {
								"sEmptyTable": "No data available for your query.",
								"sSearch": ""
							},
							dom: 'Bfrtip',
							fnDrawCallback: add_ajax_callbacks()
						});
						$('#example2').css('width', '100%');
						$('#example2').css('overflow-x', 'scroll');
					}
				} catch(err) {
					console.log(err);
				}
			}
		});
	});
	function add_ajax_callbacks() {
		setTimeout(function() {
		  $("input[name='cash_submitted_approved'], input[name='bill_submitted_approved']").icheck({
		  	checkboxClass: 'icheckbox_square-green',
		  	radioClass: 'iradio_square-green'
		  });
		}, 100);
	}
</script>
</body>
</html>