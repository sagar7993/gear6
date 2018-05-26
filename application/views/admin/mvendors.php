<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Modify Vendors - Admin Panel</title>
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
					Modify User
					<small>Modify User's Details</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i>Manage Vendors</a></li>
					<li class="active">Modify User</li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class=" col-xs-3 form-group fields-container" id="oType_1" style="">
							<div class="col-xs-12 field-box">
								<select class="form-control styled-select" name="otype_1" id="oTypeDD_1" onchange="checkfield();">
									<option disabled selected style='display:none;' value=''>Select Privilige</option>
									<option value="Super User">Super User</option>
									<option value="Admin">Admin</option>
									<option value="User">User</option>
								</select>
							</div>
						</div>
						<div class="col-xs-3">
							 <button class='btn btn-primary margin-top-22px' id="vend_mod" disabled>Update</button>
						</div>
					</div><!-- /.col -->
				</div>
			</section><!-- /.content -->
			<section>
				<div id="detail_tab" class="table-margin-top">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-user"></i> &nbsp;&nbsp;User Name</th>
								<th><i class="fa fa-building"></i> &nbsp;&nbsp;Service Center</th>
								<th><i class="fa fa-flash"></i> &nbsp;&nbsp;Privilege</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Company</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
								<th class="last"><i class="fa fa-hand-o-up"></i> &nbsp;&nbsp;Select</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($rows) && isset($bc_dict) && count($rows) > 0) { foreach($rows as $row) { ?>
							<tr id="v_<?php echo $row['VendorId']; ?>">
								<td ><a href="" class="order-id-link"><?php echo convert_to_camel_case($row['VendorName']); ?></a></td>
								<td><?php echo convert_to_camel_case($row['ScName']); ?></td>
								<td><?php echo convert_to_camel_case($row['UserPrivilege']); ?></td>
								<td><?php echo $bc_dict[intval($row['ScId'])]; ?></td>
								<td><?php echo $row['Phone']; ?></td>
								<td><div class="checkbox" style=""><label class="paymode"><input type="radio" name="vend_id" value="<?php echo $row['VendorId']; ?>"></label></div></td>
							</tr>
							<?php } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th class="first"><i class="fa fa-user"></i> &nbsp;&nbsp;User Name</th>
								<th><i class="fa fa-building"></i> &nbsp;&nbsp;Service Center</th>
								<th><i class="fa fa-flash"></i> &nbsp;&nbsp;Privilege</th>
								<th><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Company</th>
								<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
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
<script type="text/javascript" src="<?php echo site_url('js/highcharts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/select2.full.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/avlist.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script>
$(function() {
	try {
		var select2a = $('#oTypeDD_1').select2({
			placeholder: "Select Privilege",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2a.val(null).trigger("change");
	} catch(err) {
		//Do Nothing
	}
	$('#vend_mod').on('click', function(e) {
		var v_id = $('input[name=vend_id]:checked').val();
		var new_priv = $('#oTypeDD_1').val();
		var form = '<form action="/admin/mvendors/modify_vendor" method="POST">';
		form += '<input type="hidden" name="v_id" value="' + v_id + '" /><input type="hidden" name="new_priv" value="' + new_priv + '" />';
		form += '<input type="submit" name="vend_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
	$(document).on('ifChecked', 'input[name=vend_id]', function() {
		checkfield();
	});
});
function checkfield() {
	var x = 0;
	var new_priv = $('#oTypeDD_1').val();
	var v_id = $('input[name=vend_id]:checked').val();
	if(new_priv === "" || new_priv == "" || new_priv === null || new_priv == null) {
		x = 1;
	}
	if(typeof v_id === "undefined" || v_id == "") {
		x = 1;
	}
	if(x == 0) {
		$("#vend_mod").removeAttr('disabled');
	} else {
		$("#vend_mod").attr('disabled','disabled');
	}
}
</script>
</body>
</html>