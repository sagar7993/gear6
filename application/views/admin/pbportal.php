<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Petrol Bunk Portal - Admin Panel</title>
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
					<small>Petrol Bunk Portal</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Petrol Bunk Portal</li>
				</ol>
			</section>
			<section>
				<div class="row" id="parameters">
					<div class="col-xs-12">
						<div class="col-xs-8 field-box">
							<input type="text" class="form-control" oninput="validation();" name="location" id="location" placeholder="Location"/>
							<input style="display:none;" id="ulatitude" name="qlati">
							<input style="display:none;" id="ulongitude" name="qlongi">
						</div>
						<div class="col-xs-4 field-box">
							 <button class='next btn btn-primary btnUpdate-pu' id="punctureButton" disabled>Fetch Petrol Bunks</button>
						</div>
					</div>
				</div>
				<?php if(isset($rows) && count($rows) > 0) { ?>
					<div id="detail_tab" class="table-margin-top">
						<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
							<thead>
								<tr>
									<th class="first"><i class="fa fa-envelope-o"></i> &nbsp;&nbsp;Distance (Kms)</th>
									<th><i class="fa fa-cog"></i> &nbsp;&nbsp;Petrol Bunk Name</th>
									<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
									<th><i class="fa fa-envelope-o"></i> &nbsp;&nbsp;Email</th>
									<th class="last"><i class="fa fa-map-marker"></i> &nbsp;&nbsp;Location</th>
								</tr>
							</thead>
							<tbody> 
								<?php if(isset($rows) && count($rows) > 0) { $count = 0; foreach($rows as $row) { ?>
								<tr id="<?php echo $count; ?>">
									<td><?php echo $row['distance']; ?></td>
									<td><?php echo convert_to_camel_case($row['PBName']); ?></a></td>
									<td><?php echo $row['Phone']; ?></td>
									<td><?php echo $row['Email']; ?></td>
									<td><?php echo $row['LocationName']; ?></td>
								</tr>
								<?php $count += 1; } } ?>
							</tbody>
							<tfoot>
								<tr>
									<th class="first"><i class="fa fa-envelope-o"></i> &nbsp;&nbsp;Distance (Kms)</th>
									<th><i class="fa fa-cog"></i> &nbsp;&nbsp;Petrol Bunk Name</th>
									<th><i class="fa fa-whatsapp"></i> &nbsp;&nbsp;Contact</th>
									<th><i class="fa fa-envelope-o"></i> &nbsp;&nbsp;Email</th>
									<th class="last"><i class="fa fa-map-marker"></i> &nbsp;&nbsp;Location</th>
								</tr>
							</tfoot>
						</table>
					</div>
				<?php } ?>
			</section>
		</aside>
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/highcharts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCZ126reFV784ZQTqw_JfD08mnS0jI7nWo&libraries=places"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/dataTables.bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/admin.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script type="text/javascript">
	function initiateGooglePlaces() {
		var swlati = 12.730357; var swlongi = 77.359706; var nelati = 13.169339; var nelongi = 77.889796;
		var address = (document.getElementById('location'));
		var defaultBounds = new google.maps.LatLngBounds(new google.maps.LatLng(swlati, swlongi), new google.maps.LatLng(nelati, nelongi));
		var options = {	bounds : defaultBounds, componentRestrictions : { country : 'IN' } };
		var autocomplete = new google.maps.places.Autocomplete(address, options);
		autocomplete.addListener('place_changed', function() {
			var place = autocomplete.getPlace();
			var latitude = place.geometry.location.lat();
			document.getElementById('ulatitude').value = latitude;
			var longitude = place.geometry.location.lng();
			document.getElementById('ulongitude').value = longitude;
			document.getElementById('location').value = place.name;
			validation();
		});
	}
	initiateGooglePlaces();
	function validation() {
		var lat = document.getElementById('ulatitude').value.trim();
		var lng = document.getElementById('ulongitude').value.trim();
		if(lat== "" || lat == null || lat == undefined || lat.length == 0 || lng == "" || lng == null || lng == undefined || lng.length == 0) {
			$("#punctureButton").attr('disabled','disabled');
		} else {
			$("#punctureButton").removeAttr('disabled');
		}
	}	$('#location').on('input change', function() {
		document.getElementById('ulatitude').value = "";
		document.getElementById('ulongitude').value = "";
		validation();
	});
	$('#punctureButton').on('click', function(e) {
		var form = '<form action="/admin/orders/get_pblist" method="POST">';
		form += '<input type="hidden" name="latitude" value="' + $("#ulatitude").val() + '" />';
		form += '<input type="hidden" name="longitude" value="' + $("#ulongitude").val() + '" />';
		form += '<input type="submit" name="puncture_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body'); created_form.submit();
	});
</script>
</body>
</html>