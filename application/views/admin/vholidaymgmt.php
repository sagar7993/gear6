<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Holiday Management - Admin Panel</title>
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
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('nhome/js/lib/swal/sweetalert.css'); ?>">
	<style>
		a {
			color: #000000!important;
			background: transparent;
		}
		a:hover { 
		    color: #428bca!important;
		    background: transparent;
		}
		.side-menu-active .fa {
			color: #ffffff!important;
			background: transparent;
		}
		.service-center-name {
		  vertical-align: middle!important;
		  width: 50%!important;
		  margin-left: 10%!important;
		  margin-top: 10px!important;
		  margin-bottom: 10px!important;
		}
		.service-center-cross {
			float: right;
			width: 50%!important;
			margin-top: -30px!important;
			padding-left: 40%!important;
			cursor: pointer;
		}
		.date-cross {
			float: right;
			width: 45%!important;
			margin-top: 2%!important;
			padding-left: 35%!important;
			cursor: pointer;
		}
		.date-text {
			margin-left: 2%!important;
			margin-top: 50px!important;
		}
		.collapsible-vendor-holiday-date {
			background-color:#d7d7d7;
			border-bottom:none;
		}
		.collapsible-header-content {
			margin-left: 3%!important;
			line-height: 60px!important;
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
					Service Centers
					<small>Holiday Management</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
					<li class="active">Vendor Dashboard</li>
				</ol>
			</section>
			<section class="content">
				<div class="pickup-title teal-bold" align="center">Modify Holidays Across Vendors</div>
				<div class="col-xs-12 fields-update-container">
					<ul class="collapsible popout" data-collapsible="collapsible">
						<?php if(isset($holidays) && count($holidays) > 0) { foreach($holidays as $date=>$holiday) { ?>
						    <li>
							    <div class="collapsible-header active collapsible-vendor-holiday-date"
							    id="collapsible_header_<?php echo $date; ?>">
							    	<i class="material-icons collapsible-header-content">today</i>
							    	<span class="teal-bold date-text"><?php echo $date; ?></span>
							    	<div class="date-cross">
							    		<i class="material-icons"
								    	name="deleteHoliday"
								    	holiday-date="<?php echo $date; ?>"
								    	onclick="deleteHoliday(this);">delete
								    	</i>
								    </div>
						    	</div>
						    	<div class="collapsible-body">
					    			<?php if(isset($holiday) && count($holiday) > 0) { foreach($holiday as $hol) { ?>
					    			<div class="service-center-name pickup-title teal-bold"><?php echo $hol["ScName"]; ?></div>
					    			<div class="service-center-cross">
					    				<i class="material-icons"
					    				name="deleteServiceCenterHoliday"
					    				service-center-id="<?php echo $hol["ScId"]; ?>"
					    				service-center-name="<?php echo $hol["ScName"]; ?>"
					    				holiday-date="<?php echo $date; ?>"
					    				onclick="deleteServiceCenterHoliday(this);">delete
					    				</i>
					    			</div>
					    			<?php } } ?>
						    	</div>
						    </li>
					  	<?php } } ?>
		  			</ul>
				</div>
			</section>
		</aside><!-- right-side -->
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.multidatespicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/holiday.js'); ?>"></script>
<script src="<?php echo site_url('nhome/js/lib/swal/sweetalert.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script>
$(function () {
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
});
</script>
</body>
</html>