<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Customers List - Order Details</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link rel="shortcut icon" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/ionicons.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/module.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/vstyle.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/datatables/dataTables.bootstrap.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/jQueryUI/jquery-ui.css'); ?>">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.min.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oxygen" type="text/css">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/materialize.admin.css'); ?>">
</head>
<body>
	<style>
		h3.ui-state-active {
			background: #009688 !important;
			padding: 8px !important;
			margin: 5px 15px 5px 15px !important;
			color: #333333 !important;
			font-size: 15px !important;
		}
		h3.ui-state-default {
			background: #009688 !important;
			padding: 12px !important;
			padding-left: 31px !important;
			margin: 5px 15px 0px 15px !important;
			color: #fff !important;
			font-size: 15px !important;
		}
	</style>
	<?php $this->load->view('admin/components/_head'); ?>
	<?php if(isset($a_is_logged_in) && $a_is_logged_in == 1) { ?>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<?php $this->load->view('admin/components/_sidebar'); ?>
		<aside class="right-side">
			<section class="content-header">
				<h1>
					User Dashboard
					<small>User Order History</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Admin</a></li>
					<li class="active">User Dashboard</li>
				</ol>
			</section>
			<section class="section-center2">
				<div class="section-header-1">
					<span class="confirm-title">User Order History for <?php if(isset($userinfo)) { echo convert_to_camel_case($userinfo->UserName) . ' (Ph: ' . $userinfo->Phone . ')'; } ?></span>
				</div>
				<div class="section-content-action-update">
					<div class="col-xs-12">
						<?php if(isset($service_name) && count($service_name) > 0) { for($i = 0; $i < count($service_name); $i++) { if(isset($OIds[$service_name[$i]["ServiceName"]]) && count($OIds[$service_name[$i]["ServiceName"]]) > 0) { ?>
							<ul class="collapsible" data-collapsible="accordion">
								<li>
							      	<div class="collapsible-header" style="background:#028cbc;color:white;"><i class="material-icons">track_changes</i><b><?php echo $service_name[$i]["ServiceName"] . " ( " . count($OIds[$service_name[$i]["ServiceName"]]) . " Orders )"; ?></b></div>
							      	<div class="collapsible-body">
							      		<div class="row">
	      									<?php if(isset($OIds[$service_name[$i]["ServiceName"]]) && count($OIds[$service_name[$i]["ServiceName"]]) > 0) { $count = 0; ?>
	      									<ul class="collapsible popout" data-collapsible="accordion">
	      										<?php foreach($OIds[$service_name[$i]["ServiceName"]] as $OId) { ?>
	      										    <li>
	      										      	<div class="collapsible-header"><i class="material-icons">track_changes</i><b>Order ID - <?php echo $OId; ?> - <?php echo $stypes[$service_name[$i]["ServiceName"]][$count] . " (" . $odates[$service_name[$i]["ServiceName"]][$count] . ")"; ?></b><a target="_blank" class="btn btn-primary" style="float:right;margin-top:5px;" href="<?php echo site_url("/admin/orders/odetail/" . $OId); ?>">ORDER DETAILS</a></div>
	      										      	<div class="collapsible-body">
	      										      		<div class="row">
	      											      		<div class="review-options-container">
	      											      			<div class="form-group col-xs-3 right-dot-margin height-70">
	      											      				<div class="final-options-text col-xs-12">
	      											      					<i class="fa fa-gears fa-class-style"></i>
	      											      					<div class="margin-top-10px">
	      											      						<span class="selected-options"><?php echo $stypes[$service_name[$i]["ServiceName"]][$count]; ?></span>
	      											      					</div>
	      											      				</div>
	      											      			</div>
	      											      			<div class="form-group col-xs-3 right-dot-margin height-70">
	      											      				<div class="final-options-text col-xs-12">
	      											      					<i class="fa fa-calendar fa-class-style"></i>
	      											      					<div class="margin-top-10px">
	      											      						<span class="selected-options"><?php echo $timeslots[$service_name[$i]["ServiceName"]][$count]; ?></span>
	      											      					</div>
	      											      				</div>
	      											      			</div>
	      											      			<div class="form-group col-xs-3 right-dot-margin height-70">
	      											      				<div class="final-options-text col-xs-12">
	      											      					<i class="fa fa-motorcycle fa-class-style"></i>
	      											      					<div class="margin-top-10px">
	      											      						<span class="selected-options"><?php echo $bikemodels[$service_name[$i]["ServiceName"]][$count]; ?></span>
	      											      					</div>
	      											      				</div>
	      											      			</div>
	      											      			<div class="form-group col-xs-3 height-70">
	      											      				<div class="final-options-text col-xs-12">
	      											      					<i class="fa fa-map-marker fa-class-style"></i>
	      											      					<div class="margin-top-10px">
	      												      					<span class="selected-options">
	      												      						<?php if (isset($scenters[$service_name[$i]["ServiceName"]][$count])) {
	      											      								foreach ($scenters[$service_name[$i]["ServiceName"]][$count] as $sc) {
	      											      									echo '<label class="selected-options">' . convert_to_camel_case($sc['ScName']) . '</label>';
	      											      								}
	      											      							} ?>
	      												      					</span>
	      												      				</div>
	      											      				</div>
	      											      			</div>
	      											      			<div class="col-xs-6 pay-mode-final">
	      											      				<label for="exampleInputPassword1">Mode of Payment&nbsp;&nbsp; :&nbsp;&nbsp;</label> 
	      											      				<label class="selected-options"><?php echo $paymodes[$service_name[$i]["ServiceName"]][$count]; ?></label> 
	      											      			</div>
	      											      			<?php if(isset($serid[$service_name[$i]["ServiceName"]][$count]) && ($serid[$service_name[$i]["ServiceName"]][$count] == 1 || $serid[$service_name[$i]["ServiceName"]][$count] == 4)) { ?>
	      											      			<br>
	      											      			<?php if(isset($scenters[$service_name[$i]["ServiceName"]][$count][0]['ServiceDesc2']) && $scenters[$service_name[$i]["ServiceName"]][$count][0]['ServiceDesc2'] != '') { ?>
	      											      			<div class="col-xs-12 query-show-block">
	      											      				<div class="col-xs-2">
	      											      					<strong>Description : </strong>
	      											      				</div>
	      											      				<div class="col-xs-10">
	      											      					<span class="selected-options"><?php echo $scenters[$service_name[$i]["ServiceName"]][$count][0]['ServiceDesc2']; ?></span>
	      											      				</div>
	      											      			</div>
	      											      			<?php } ?>
	      											      			<br>
	      											      			<?php } ?>
	      											      			<?php if(isset($serid[$service_name[$i]["ServiceName"]][$count]) && $serid[$service_name[$i]["ServiceName"]][$count] == 3) { ?>
	      											      			<br>
	      											      			<?php if(isset($scenters[$service_name[$i]["ServiceName"]][$count][0]['ServiceDesc1']) && $scenters[$service_name[$i]["ServiceName"]][$count][0]['ServiceDesc1'] != '') { ?>
	      											      			<div class="col-xs-12 query-show-block">
	      											      				<div class="col-xs-2">
	      											      					<strong>Query : </strong>
	      											      				</div>
	      											      				<div class="col-xs-10">
	      											      					<span class="selected-options"><?php echo $scenters[$service_name[$i]["ServiceName"]][$count][0]['ServiceDesc1']; ?></span>
	      											      				</div>
	      											      			</div>
	      											      			<?php } ?>
	      											      			<?php if(isset($scenters[$service_name[$i]["ServiceName"]][$count][0]['ServiceDesc2']) && $scenters[$service_name[$i]["ServiceName"]][$count][0]['ServiceDesc2'] != '') { ?>
	      											      			<div class="col-xs-12 query-show-block">
	      											      				<div class="col-xs-2">
	      											      					<strong>Description : </strong>
	      											      				</div>
	      											      				<div class="col-xs-10">
	      											      					<span class="selected-options"><?php echo $scenters[$service_name[$i]["ServiceName"]][$count][0]['ServiceDesc2']; ?></span>
	      											      				</div>
	      											      			</div>
	      											      			<?php } ?>
	      											      			<br>
	      											      			<?php } ?>
	      											      			<?php if(isset($serid[$service_name[$i]["ServiceName"]][$count]) && $serid[$service_name[$i]["ServiceName"]][$count] == 2) { ?>
	      											      			<br>
	      											      			<?php if(isset($scenters[$service_name[$i]["ServiceName"]][$count][0]['ServiceDesc1']) && $scenters[$service_name[$i]["ServiceName"]][$count][0]['ServiceDesc1'] != '') { ?>
	      											      			<div class="col-xs-12 query-show-block">
	      											      				<div class="col-xs-2">
	      											      					<strong>Repair Info : </strong>
	      											      				</div>
	      											      				<div class="col-xs-10">
	      											      					<span class="selected-options"><?php echo $scenters[$service_name[$i]["ServiceName"]][$count][0]['ServiceDesc1']; ?></span>
	      											      				</div>
	      											      			</div>
	      											      			<?php } ?>
	      											      			<?php if(isset($scenters[$service_name[$i]["ServiceName"]][$count][0]['ServiceDesc2']) && $scenters[$service_name[$i]["ServiceName"]][$count][0]['ServiceDesc2'] != '') { ?>
	      											      			<div class="col-xs-12 query-show-block">
	      											      				<div class="col-xs-2">
	      											      					<strong>Description : </strong>
	      											      				</div>
	      											      				<div class="col-xs-10">
	      											      					<span class="selected-options"><?php echo $scenters[$service_name[$i]["ServiceName"]][$count][0]['ServiceDesc2']; ?></span>
	      											      				</div>
	      											      			</div>
	      											      			<?php } ?>
	      											      			<br>
	      											      			<?php } ?>
	      											      			<?php if(isset($serid[$service_name[$i]["ServiceName"]][$count]) && $serid[$service_name[$i]["ServiceName"]][$count] == 4 && isset($insren_details[$service_name[$i]["ServiceName"]][$count])) { ?>
	      											      			<div class="col-xs-12">
	      											      				<br>
	      											      			</div>
	      											      			<div class="review-options-container1">
	      											      				<div class="form-group col-xs-3 right-dot-margin margin-left-10px" style="">
	      											      					<label for="">Previous Insurer</label><br>
	      											      					<label class="selected-options"><?php echo convert_to_camel_case($insren_details[$service_name[$i]["ServiceName"]][$count]['InsurerName']); ?></label>
	      											      				</div>
	      											      				<div class="form-group col-xs-3 right-dot-margin margin-left-10px" style="">
	      											      					<label for="">Previous Policy Expiry</label><br>
	      											      					<label class="selected-options"><?php if($insren_details[$service_name[$i]["ServiceName"]][$count]['ExpiryDays'] == 0) { echo 'Already Expired'; } else { echo 'Next ' . $insren_details[$service_name[$i]["ServiceName"]][$count]['ExpiryDays'] . ' Days'; } ?></label>
	      											      				</div>
	      											      				<div class="form-group col-xs-3 right-dot-margin margin-left-10px" style="">
	      											      					<label for="">Registration Year</label><br>
	      											      					<label class="selected-options"><?php echo convert_to_camel_case($insren_details[$service_name[$i]["ServiceName"]][$count]['RegYear']); ?></label>
	      											      				</div>
	      											      				<div class="form-group col-xs-3  margin-left-10px" style="">
	      											      					<label for="">Claims Made Previously</label><br>
	      											      					<label class="selected-options"><?php if($insren_details[$service_name[$i]["ServiceName"]][$count]['isClaimedBefore'] == 1) { echo 'Yes'; } else { echo 'No'; } ?></label>
	      											      				</div>
	      											      			</div>
	      											      			<br>
	      											      			<?php } ?>
	      											      			<?php if(isset($mr_remarks[$service_name[$i]["ServiceName"]][$count]) && $mr_remarks[$service_name[$i]["ServiceName"]][$count] != '') { ?>
	      											      			<div class="col-xs-12 query-show-block">
	      											      				<div class="col-xs-2">
	      											      					<strong>Order Remarks : </strong>
	      											      				</div>
	      											      				<div class="col-xs-10">
	      											      					<span class="selected-options"><?php echo $mr_remarks[$service_name[$i]["ServiceName"]][$count]; ?></span>
	      											      				</div>
	      											      			</div>
	      											      			<br>
	      											      		<?php } ?>
	      											      		</div>
	      											      	</div>
	      											      	<?php if(isset($ex_rtime_updates[$service_name[$i]["ServiceName"]][$count]) && count($ex_rtime_updates[$service_name[$i]["ServiceName"]][$count]) > 0) { { ?>
	      											      	<br/>
	      											      	<div class="row">
	      											      		<div class="col-xs-12">
	      											      			<ul class="collapsible" data-collapsible="accordion">
	      											      				<li>
	      				      										      	<div class="collapsible-header"><i class="material-icons">history</i><b>Running Status History</b></div>
	      				      										      	<div class="collapsible-body">
	      				      										      		<div class="row margin-top-10px">
	      				      										      			<div class="col-xs-12 status-history-title-container">
	      				      										      				<div class="form-group col-xs-2 right-dot-margin">
	      				      										      					<strong>Status</strong>
	      				      										      				</div>
	      				      										      				<div class="form-group col-xs-3 right-dot-margin">
	      				      										      					<strong>Remarks</strong>
	      				      										      				</div>
	      				      										      				<div class="form-group col-xs-2 right-dot-margin">
	      				      										      					<strong>Location</strong>
	      				      										      				</div>
	      				      										      				<div class="form-group col-xs-3 right-dot-margin">
	      				      										      					<strong>Status Updated By</strong>
	      				      										      				</div>
	      				      										      				<div class="form-group col-xs-2">
	      				      										      					<strong>Timestamp</strong>
	      				      										      				</div>
	      				      										      			</div>
	      				      										      			<?php foreach($ex_rtime_updates[$service_name[$i]["ServiceName"]][$count] as $ex_rtime_update) { ?>
	      					      										      			<div class="col-xs-12 status-history-title-container">
	      					      										      				<div class="form-group col-xs-2 right-dot-margin">
	      					      										      					<strong><?php echo $ex_rtime_update[0]; ?></strong>
	      					      										      				</div>
	      					      										      				<div class="form-group col-xs-3 right-dot-margin">
	      					      										      					<strong><?php echo $ex_rtime_update[1]; ?></strong>
	      					      										      				</div>
	      					      										      				<div class="form-group col-xs-2 right-dot-margin">
	      					      										      					<strong><?php echo $ex_rtime_update[2]; ?></strong>
	      					      										      				</div>
	      					      										      				<div class="form-group col-xs-3 right-dot-margin">
	      					      										      					<strong><?php echo $ex_rtime_update[3]; ?></strong>
	      					      										      				</div>
	      					      										      				<div class="form-group col-xs-2">
	      					      										      					<strong><?php echo $ex_rtime_update[4]; ?></strong>
	      					      										      				</div>
	      					      										      			</div>
	      					      										      		<?php } ?>
	      				      										      		</div>
	      				      										      	</div>
	      				      										    </li>
	      				      										</ul>
	      											      		</div>
	      											      	</div>
	      												    <?php } } ?>
	      				    						      	<?php if(isset($ex_fup_updates[$service_name[$i]["ServiceName"]][$count]) && count($ex_fup_updates[$service_name[$i]["ServiceName"]][$count]) > 0) { { ?>
	      				    						      	<br/>
	      			    							      	<div class="row">
	      			    							      		<div class="col-xs-12">
	      			    							      			<ul class="collapsible" data-collapsible="accordion">
	      			    							      				<li>
	      			          										      	<div class="collapsible-header"><i class="material-icons">history</i><b>Follow-Up Status History</b></div>
	      			          										      	<div class="collapsible-body">
	      			          										      		<div class="row margin-top-10px">
	      			          										      			<div class="col-xs-12 status-history-title-container">
	      			          										      				<div class="form-group col-xs-2 right-dot-margin">
	      			          										      					<strong>Status</strong>
	      			          										      				</div>
	      			          										      				<div class="form-group col-xs-3 right-dot-margin">
	      			          										      					<strong>Remarks</strong>
	      			          										      				</div>
	      			          										      				<div class="form-group col-xs-2 right-dot-margin">
	      			          										      					<strong>Location</strong>
	      			          										      				</div>
	      			          										      				<div class="form-group col-xs-3 right-dot-margin">
	      			          										      					<strong>Status Updated By</strong>
	      			          										      				</div>
	      			          										      				<div class="form-group col-xs-2">
	      			          										      					<strong>Timestamp</strong>
	      			          										      				</div>
	      			          										      			</div>
	      			          										      			<?php foreach($ex_fup_updates[$service_name[$i]["ServiceName"]][$count] as $ex_fup_update) { ?>
	      			    	      										      			<div class="col-xs-12 status-history-title-container">
	      			    	      										      				<div class="form-group col-xs-2 right-dot-margin">
	      			    	      										      					<strong><?php echo $ex_fup_update[0]; ?></strong>
	      			    	      										      				</div>
	      			    	      										      				<div class="form-group col-xs-3 right-dot-margin">
	      			    	      										      					<strong><?php echo $ex_fup_update[1]; ?></strong>
	      			    	      										      				</div>
	      			    	      										      				<div class="form-group col-xs-2 right-dot-margin">
	      			    	      										      					<strong><?php echo $ex_fup_update[2]; ?></strong>
	      			    	      										      				</div>
	      			    	      										      				<div class="form-group col-xs-3 right-dot-margin">
	      			    	      										      					<strong><?php echo $ex_fup_update[3]; ?></strong>
	      			    	      										      				</div>
	      			    	      										      				<div class="form-group col-xs-2">
	      			    	      										      					<strong><?php echo $ex_fup_update[4]; ?></strong>
	      			    	      										      				</div>
	      			    	      										      			</div>
	      			    	      										      		<?php } ?>
	      			          										      		</div>
	      			          										      	</div>
	      			          										    </li>
	      			          										</ul>
	      			    							      		</div>
	      			    							      	</div>
	      				    							    <?php } } ?>
	      				    						      	<?php if(isset($fupstathistory[$service_name[$i]["ServiceName"]][$count]) && count($fupstathistory[$service_name[$i]["ServiceName"]][$count]) > 0) { { ?>
	      				    						      	<br/>
	      			    							      	<div class="row">
	      			    							      		<div class="col-xs-12">
	      			    							      			<ul class="collapsible" data-collapsible="accordion">
	      			    							      				<li>
	      			          										      	<div class="collapsible-header"><i class="material-icons">history</i><b>Admin Follow-Up History</b></div>
	      			          										      	<div class="collapsible-body">
	      			          										      		<div class="row margin-top-10px">
	      			          										      			<div class="col-xs-12 status-history-title-container">
	      			          										      				<div class="form-group col-xs-3 right-dot-margin">
	      			          										      					<strong>Status Name</strong>
	      			          										      				</div>
	      			          										      				<div class="form-group col-xs-3 right-dot-margin">
	      			          										      					<strong>Status Remarks</strong>
	      			          										      				</div>
	      			          										      				<div class="form-group col-xs-3 right-dot-margin">
	      			          										      					<strong>Status Updated By</strong>
	      			          										      				</div>
	      			          										      				<div class="form-group col-xs-3 right-dot-margin">
	      			          										      					<strong>Updated On</strong>
	      			          										      				</div>
	      			          										      			</div>
	      			          										      			<?php foreach($fupstathistory[$service_name[$i]["ServiceName"]][$count] as $fupstat) { ?>
	      			    	      										      			<div class="col-xs-12 status-history-title-container">
	      			    	      										      				<div class="form-group col-xs-3 right-dot-margin">
	      			    	      										      					<strong><?php echo $fupstat['Remarks']; ?></strong>
	      			    	      										      				</div>
	      			    	      										      				<div class="form-group col-xs-3 right-dot-margin">
	      			    	      										      					<strong><?php echo $fupstat['FupStatusName']; ?></strong>
	      			    	      										      				</div>
	      			    	      										      				<div class="form-group col-xs-3 right-dot-margin">
	      			    	      										      					<strong><?php echo $fupstat['UpdatedBy']; ?></strong>
	      			    	      										      				</div>
	      			    	      										      				<div class="form-group col-xs-3">
	      			    	      										      					<strong><?php echo $fupstat['Timestamp']; ?></strong>
	      			    	      										      				</div>
	      			    	      										      			</div>
	      			    	      										      		<?php } ?>
	      			          										      		</div>
	      			          										      	</div>
	      			          										    </li>
	      			          										</ul>
	      			    							      		</div>
	      			    							      	</div>
	      				    							    <?php } } ?>
	      				    						      	<?php if(isset($ex_pre_servicing_updates[$service_name[$i]["ServiceName"]][$count]) && count($ex_pre_servicing_updates[$service_name[$i]["ServiceName"]][$count]) > 0) { { ?>
	      				    						      	<br/>
	      			    							      	<div class="row">
	      			    							      		<div class="col-xs-12">
	      			    							      			<ul class="collapsible" data-collapsible="accordion">
	      			    							      				<li>
	      			          										      	<div class="collapsible-header"><i class="material-icons">history</i><b>Pre-Servicing Updates</b></div>
	      			          										      	<div class="collapsible-body">
	      			          										      		<div class="row margin-top-10px">
	      			          										      			<div class="col-xs-12 status-history-title-container">
	      			          										      				<div class="form-group col-xs-2 right-dot-margin">
	      			          										      					<strong>Est. Time</strong>
	      			          										      				</div>
	      			          										      				<div class="form-group col-xs-1 right-dot-margin">
	      			          										      					<strong>Est. Price</strong>
	      			          										      				</div>
	      			          										      				<div class="form-group col-xs-2 right-dot-margin">
	      			          										      					<strong>Location</strong>
	      			          										      				</div>
	      			          										      				<div class="form-group col-xs-2 right-dot-margin">
	      			          										      					<strong>SC Comments</strong>
	      			          										      				</div>
	      			          										      				<div class="form-group col-xs-2 right-dot-margin">
	      			          										      					<strong>User Comments</strong>
	      			          										      				</div>
	      			          										      				<div class="form-group col-xs-1 right-dot-margin">
	      			          										      					<strong>Status Updated By</strong>
	      			          										      				</div>
	      			          										      				<div class="form-group col-xs-2">
	      			          										      					<strong>Timestamp</strong>
	      			          										      				</div>
	      			          										      			</div>
	      			          										      			<?php foreach($ex_pre_servicing_updates[$service_name[$i]["ServiceName"]][$count] as $ex_pre_servicing_update) { ?>
	      			    	      										      			<div class="col-xs-12 status-history-title-container">
	      			    	      										      				<div class="form-group col-xs-2 right-dot-margin">
	      			    	      										      					<strong><?php echo $ex_pre_servicing_update[0]; ?></strong>
	      			    	      										      				</div>
	      			    	      										      				<div class="form-group col-xs-1 right-dot-margin">
	      			    	      										      					<strong><?php echo $ex_pre_servicing_update[1]; ?></strong>
	      			    	      										      				</div>
	      			    	      										      				<div class="form-group col-xs-2 right-dot-margin">
	      			    	      										      					<strong><?php echo $ex_pre_servicing_update[2]; ?></strong>
	      			    	      										      				</div>
	      			    	      										      				<div class="form-group col-xs-2 right-dot-margin">
	      			    	      										      					<strong><?php echo $ex_pre_servicing_update[3]; ?></strong>
	      			    	      										      				</div>
	      			    	      										      				<div class="form-group col-xs-2">
	      			    	      										      					<strong><?php echo $ex_pre_servicing_update[4]; ?></strong>
	      			    	      										      				</div>
	      			    	      										      				<div class="form-group col-xs-1">
	      			    	      										      					<strong><?php echo $ex_pre_servicing_update[5]; ?></strong>
	      			    	      										      				</div>
	      			    	      										      				<div class="form-group col-xs-2">
	      			    	      										      					<strong><?php echo $ex_pre_servicing_update[6]; ?></strong>
	      			    	      										      				</div>
	      			    	      										      			</div>
	      			    	      										      		<?php } ?>
	      			          										      		</div>
	      			          										      	</div>
	      			          										    </li>
	      			          										</ul>
	      			    							      		</div>
	      			    							      	</div>
	      				    							    <?php } } ?>
	      				    						      	<?php if(isset($ord_trans[$service_name[$i]["ServiceName"]][$count]) && count($ord_trans[$service_name[$i]["ServiceName"]][$count]) > 0) { { ?>
	      				    						      	<br/>
	      			    							      	<div class="row">
	      			    							      		<div class="col-xs-12">
	      			    							      			<ul class="collapsible" data-collapsible="accordion">
	      			    							      				<li>
	      			          										      	<div class="collapsible-header"><i class="material-icons">history</i><b>Order Transactions</b></div>
	      			          										      	<div class="collapsible-body">
	      			          										      		<div class="row margin-top-10px">
	      			          										      			<div class="col-xs-12 status-history-title-container">
	      			          										      				<div class="form-group col-xs-3 right-dot-margin">
	      			          										      					<strong>Transaction Id</strong>
	      			          										      				</div>
	      			          										      				<div class="form-group col-xs-3 right-dot-margin">
	      			          										      					<strong>Transaction Date</strong>
	      			          										      				</div>
	      			          										      				<div class="form-group col-xs-2 right-dot-margin">
	      			          										      					<strong>Transaction Amount</strong>
	      			          										      				</div>
	      			          										      				<div class="form-group col-xs-2 right-dot-margin">
	      			          										      					<strong>Transaction Mode</strong>
	      			          										      				</div>
	      			          										      				<div class="form-group col-xs-2">
	      			          										      					<strong>Transaction Status</strong>
	      			          										      				</div>
	      			          										      			</div>
	      			          										      			<?php foreach($ord_trans[$service_name[$i]["ServiceName"]][$count] as $trans) { ?>
	      			    	      										      			<div class="col-xs-12 status-history-title-container">
	      			    	      										      				<div class="form-group col-xs-3 right-dot-margin">
	      			    	      										      					<strong><?php echo $trans['TId']; ?></strong>
	      			    	      										      				</div>
	      			    	      										      				<div class="form-group col-xs-3 right-dot-margin">
	      			    	      										      					<strong><?php echo $trans['TimeStamp']; ?></strong>
	      			    	      										      				</div>
	      			    	      										      				<div class="form-group col-xs-2 right-dot-margin">
	      			    	      										      					<strong><?php echo $trans['PaymtAmt']; ?></strong>
	      			    	      										      				</div>
	      			    	      										      				<div class="form-group col-xs-2 right-dot-margin">
	      			    	      										      					<strong><?php echo $trans['PaymtMode']; ?></strong>
	      			    	      										      				</div>
	      			    	      										      				<div class="form-group col-xs-2">
	      			    	      										      					<strong><?php echo $trans['PaymtStatus']; ?></strong>
	      			    	      										      				</div>
	      			    	      										      			</div>
	      			    	      										      		<?php } ?>
	      			          										      		</div>
	      			          										      	</div>
	      			          										    </li>
	      			          										</ul>
	      			    							      		</div>
	      			    							      	</div>
	      				    							    <?php } } ?>
	      										      		<div class="addr-container3">
	      										      			<div class="user-addr-container">
	      										      				<div class="user-addr-title">User Address</div>
	      										      				<div class="user-addr-content">
	      										      					<?php echo $uaddresses[$service_name[$i]["ServiceName"]][$count]; ?>
	      										      				</div>
	      										      			</div>
	      										      			<div class="sc-addr-container">
	      										      				<?php if(isset($serid[$service_name[$i]["ServiceName"]][$count]) && $serid[$service_name[$i]["ServiceName"]][$count] != 3) { ?>
	      										      					<div class="sc-addr-title">Service Centre Address</div>
	      										      				<?php } else { ?>
	      										      					<div class="sc-addr-title">Service Centre(s) Details</div>
	      										      				<?php } ?>
	      										      				<div class="sc-addr-content">
	      										      					<?php
	      										      						if(isset($serid[$service_name[$i]["ServiceName"]][$count]) && $serid[$service_name[$i]["ServiceName"]][$count] != 3) {
	      										      							if(isset($scaddresses[$service_name[$i]["ServiceName"]][$count])) { echo $scaddresses[$service_name[$i]["ServiceName"]][$count]; }
	      										      						} else {
	      										      							if (isset($scenters[$service_name[$i]["ServiceName"]][$count])) {
	      										      								foreach ($scenters[$service_name[$i]["ServiceName"]][$count] as $sc) {
	      										      									echo '<div>' . convert_to_camel_case($sc['ScName']) . ' - Phone: ' . $sc['Phone'] . '</div>';
	      										      								}
	      										      							}
	      										      						}
	      										      					?>
	      										      				</div>
	      										      			</div>
	      										      		</div>
	      										      		<?php if(isset($serid[$service_name[$i]["ServiceName"]][$count]) && $serid[$service_name[$i]["ServiceName"]][$count] != 3) { ?>
	      										      		<div class="price-details-container1">
	      										      			<div class="service-title-container1">Price Details</div>
	      										      			<div class="price-title-container1">Price</div>
	      										      			<div class="price-map-container1">
	      										      				<?php if(isset($estprices[$service_name[$i]["ServiceName"]][$count]) && $estprices[$service_name[$i]["ServiceName"]][$count] !== NULL) { ?>
	      										      				<div class="sub-price-text">Service / Amenity Details - <span>Estimated Charges</span></div>
	      										      				<div class="service-list-container">
	      										      					<?php foreach($estprices[$service_name[$i]["ServiceName"]][$count] as $estprice) { if(isset($estprice['apdesc']) && isset($estprice['aprice'])) { ?>
	      										      						<div class="service-title"><?php echo convert_to_camel_case($estprice['apdesc']); ?></div>
	      										      						<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $estprice['aprice']; ?></div>
	      										      					<?php } } ?>
	      										      				</div>
	      										      				<div class="final-price-container">
	      										      					<i class="fa fa-inr"></i>&nbsp;<?php echo $estprices[$service_name[$i]["ServiceName"]][$count][count($estprices[$service_name[$i]["ServiceName"]][$count]) - 1]['ptotal']; ?>
	      										      				</div><br><br>
	      										      				<?php } ?>
	      										      				<?php if (isset($opriceses[$service_name[$i]["ServiceName"]][$count]) && count($opriceses[$service_name[$i]["ServiceName"]][$count]) > 0) { ?>
	      										      				<div class="sub-price-text">Additional Charges</div>
	      										      				<div class="service-list-container">
	      										      					<?php foreach($opriceses[$service_name[$i]["ServiceName"]][$count] as $oprice) { if(isset($oprice['opdesc']) && isset($oprice['oprice'])) { ?>
	      										      						<div class="service-title"><?php echo convert_to_camel_case($oprice['opdesc']); ?></div>
	      										      						<div class="price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $oprice['oprice']; ?></div>
	      										      					<?php } } ?>
	      										      				</div>
	      										      				<div class="final-price-container">
	      										      					<i class="fa fa-inr"></i>&nbsp;<?php echo $opriceses[$service_name[$i]["ServiceName"]][$count][count($opriceses[$service_name[$i]["ServiceName"]][$count]) - 1]['ptotal']; ?>
	      										      				</div>
	      										      				<?php } ?>
	      										      			</div>
	      										      		</div>
	      										      		<?php } else { ?><br><?php } ?>
	      										      	</div>
	      										    </li>
	      										<?php $count += 1; } ?>
	      									</ul>
	      									<?php } else { ?>
	      										<div class="row">
	      											<div class="col-xs-12 margin-bottom-10px margin-top-10px">
	      												<h3 class="center"><b>No Orders Yet</b></h3>
	      											</div>
	      										</div>
	      									<?php } ?>
							      		</div>
							      	</div>
							    </li>
							</ul>
						<?php } } } ?>
					</div>
				</div>
			</section>
		</aside>
	</div>
	<?php $this->load->view('admin/components/_foot'); ?>
	<?php } ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/highcharts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.multidatespicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/materialize.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/account.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/anotify.js?v=1.0'); ?>"></script>
<script type="text/javascript">
	$(function() {
		$('.collapsible').collapsible({
			accordion : false
	    });
	});
</script>
</body>
</html>