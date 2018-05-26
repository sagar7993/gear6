<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Modify Admins - Admin Panel</title>
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
					Modify Admin
					<small>Modify Admin's Details</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i>Manage Admins</a></li>
					<li class="active">Modify Admin</li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-solid">
							<div class="box-body">
								<div class="row">
									<?php if(isset($err_phone)) { echo '<div class="alert alert-danger" role="alert">' . $err_phone . '</div>'; } ?><br>
									<div class=" col-xs-12 form-group fields-container" id="oType_1" style="">
										<div class="col-xs-4 field-box">
											<div class="form-group">
												<input type="text" class="form-control" oninput="checkfieldAdmin();" id="new_name" name="new_name" placeholder="Admin Name" readonly onfocus="this.removeAttribute('readonly');">
											</div>
										</div>
										<div class="col-xs-4 field-box">
											<div class="form-group">
												<input type="email" class="form-control" oninput="checkfieldAdmin();" id="new_email" name="new_email" placeholder="Email ID" readonly onfocus="this.removeAttribute('readonly');">
											</div>
										</div>
										<div class="col-xs-4 field-box">
											<div class="form-group">
												<input type="password" class="form-control" oninput="checkfieldAdmin();" id="new_password" name="new_password" placeholder="Password" readonly onfocus="this.removeAttribute('readonly');">
											</div>
										</div>
										<div class="col-xs-4 field-box">
											<div class="form-group">
												<input type="text" class="form-control" oninput="checkfieldAdmin();" id="new_phone" name="new_phone" placeholder="Phone" maxlength="10" readonly onfocus="this.removeAttribute('readonly');">
											</div>
										</div>
										<?php if(isset($admin_city_id) && $admin_city_id > 0) { ?>
										<input type="hidden" name="new_city_id" id="new_city_id" data-type="input" value="<?php echo $admin_city_id; ?>" />
										<?php } else { ?>
										<div class="col-xs-4 field-box">
											<select class="form-control styled-select" onchange="checkfieldAdmin();" name="new_city_id" id="new_city_id" data-type="select">
												<option disabled selected style='display:none;' value=''>Select City</option>
												<?php if(isset($cities)) { foreach($cities as $city) { ?>
												<option value="<?php echo $city->CityId; ?>"><?php echo convert_to_camel_case($city->CityName); ?></option>
												<?php } } ?>
											</select>
										</div>
										<?php } ?>
										<div class="col-xs-4 field-box">
											<select class="form-control styled-select" name="new_priv" id="new_priv" onchange="checkfieldAdmin();">
												<option disabled selected style='display:none;' value=''>Select Privilige</option>
												<option value="Admin">Admin</option>
												<option value="Customer Care">Customer Care</option>
											</select>
										</div>
									</div>
									<div class="button-box-contact col-xs-12">
										<div class="button-container col-xs-6 col-xs-offset-5">
											 <button class='next btn btn-primary btnUpdate-pu' id="admin_mod" disabled>
												Update Admin
											</button>
										</div>
									</div>
								</div><!-- /.row -->
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div><!-- /.col -->
				</div>
			</section><!-- /.content -->
			<section>
				<div id="detail_tab" class="table-margin-top">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-user"></i> &nbsp;&nbsp;Admin Name</th>
								<th><i class="fa fa-building"></i> &nbsp;&nbsp;City</th>
								<th><i class="fa fa-phone"></i> &nbsp;&nbsp;Phone</th>
								<th><i class="fa fa-envelope"></i> &nbsp;&nbsp;Email</th>
								<th><i class="fa fa-flash"></i> &nbsp;&nbsp;Privilege</th>
								<th class="last"><i class="fa fa-hand-o-up"></i> &nbsp;&nbsp;Select</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($rows) && count($rows) > 0) { foreach($rows as $row) { ?>
							<tr id="a_<?php echo $row['AdminId']; ?>">
								<td><a href="" class="order-id-link"><?php echo convert_to_camel_case($row['AdminName']); ?></a></td>
								<td><?php echo convert_to_camel_case($row['CityName']); ?></td>
								<td><?php echo $row['Phone']; ?></td>
								<td><?php echo $row['Email']; ?></td>
								<td><?php echo convert_to_camel_case($row['UserPrivilege']); ?></td>
								<td>
									<div class="checkbox" style="">
										<label class="paymode">
											<input type="radio" name="admin_id" 
												id="check_<?php echo $row['AdminId']; ?>"
												value="<?php echo $row['AdminId']; ?>"
												admin-id="<?php echo $row['AdminId']; ?>"
												admin-name="<?php echo $row['AdminName']; ?>"
												admin-email="<?php echo $row['Email']; ?>"
												admin-phone="<?php echo $row['Phone']; ?>"
												admin-password="<?php echo $row['Pwd']; ?>"
												admin-city="<?php echo $row['CityId']; ?>"
												admin-privilege="<?php echo $row['UserPrivilege']; ?>">
											</input>
										</label>
									</div>
								</td>
							</tr>
							<?php } } ?>
						</tbody>
						<tfoot>
							<tr>
								<th class="first"><i class="fa fa-user"></i> &nbsp;&nbsp;Admin Name</th>
								<th><i class="fa fa-building"></i> &nbsp;&nbsp;City</th>
								<th><i class="fa fa-phone"></i> &nbsp;&nbsp;Phone</th>
								<th><i class="fa fa-envelope"></i> &nbsp;&nbsp;Email</th>
								<th><i class="fa fa-flash"></i> &nbsp;&nbsp;Privilege</th>
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
	<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/editadminexec.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
</body>
</html>