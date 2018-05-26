<html lang="en" style="min-height:0px !important;">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title><?php if(isset($site_name)) { echo $site_name; } ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/materialize.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/ionicons.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/module.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/custom-jquery.css'); ?>">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Raleway" type="text/css">
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="/nhome/js/lib/swal/sweetalert.css">
	<?php $this->load->view('user/components/_ucss'); ?>
</head>
<body style="background-color:#f6f6f5;min-height:0px !important;">
	<?php $this->load->view('user/components/__head'); ?>
	<?php if (isset($scenter) && isset($OId)) { foreach ($scenter as $sc) { if($sc['isFbNotified'] == 0) { ?>
	<div class="modal feedback-modal corderModal" id="<?php echo 'feedback_' . $OId; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header custom-modal-header">
					<button type="button" class="close ratingCloseEvent" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Feedback to <?php echo " ".convert_to_camel_case($sc['ScName']); ?></h4>
				</div>
				<div class="modal-body">
					<input type="hidden" class="question_count" value="<?php echo count($feedback); ?>" />
					<?php foreach($feedback as $row) { ?>
						<div class="col-xs-12 ratingContainer">
							<div class="col-xs-8 ratingTitle"><?php echo $row['FbQName']; ?></div>
							<div class="col-xs-4 feedbackRating ratingEvent" id="question_<?php echo $OId; ?>_<?php echo $row['ExecFbQId']; ?>"></div>
						</div>
					<?php } ?>
					<div class="col-xs-12">
						<textarea class="form-group col-xs-12 text-area fb_desc fb_desc_corder" placeholder="Enter the feedback description (Optional)" style="color:white;"></textarea>
					</div>
					<input type="hidden" class="fb_sc_id" value="<?php echo $sc['ScId']; ?>" />
					<input type="hidden" class="fb_oid" id="fb_oid" value="<?php echo $OId; ?>" />
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary fb_submit center" align="center" disabled="true" id="psuccessfbsubmit">Submit</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<?php } } } ?>
	<section class="section-center">
		<div class="section-header">
			<span class="confirm-title">Payment Details</span>
		</div>
		<div class="section-content">
			<?php if(isset($status) && $status == 'Success') { ?>
				<div class="congo-text">
					<span><?php echo 'Congratulations, Your payment with Transaction Id: ' . $txnid . ' was <strong>successful</strong>.'; ?> For details about your Order visit: </span>
				</div>
			<?php } elseif(isset($status) && $status == 'Failure') { ?>
				<div class="congo-text">
					<span><?php echo 'Your payment with Transaction Id: ' . $txnid . ' <strong>failed</strong> ' . 'with message: "' . $error_Message; ?>. For details about your Order visit: </span>
				</div>
			<?php } ?>
			<div class="price-details-container1">
				<div class="service-title-container1"><a href="<?php echo base_url('user/account/corders'); ?>"><?php echo $OId; ?></a></div>
			</div>
			<div class="terms-container">
				<div class="terms-title-text"><?php if(isset($site_name)) { echo $site_name; } ?> Terms &amp; Conditions</div><br>
				<div class="terms-detail">
					The page, page code, and any and all copyrights, trademarks, service marks, trade names 
					and all other intellectual property or material or property rights herein are
					proprietary to the Company and are owned by the Company and/or its licensors and 
					content providers, and are protected by applicable domestic and international 
					intellectual property laws. The depiction of such proprietary content does 
					not permit or allow any user or person (whatsoever) to make any use of the 
					same without prior written permission of the Company in any manner or reference or context.
				</div>
				<div class="terms-regards">
					<div class="thanks-text">Thanks and Regards</div><div class="team-text"><strong>Team <?php if(isset($site_name)) { echo $site_name; } ?></strong></div> 
				</div>
			</div>
		</div>
	</section>
	<br/>
	<?php $this->load->view('user/components/_foot'); ?>
	<?php $this->load->view('user/components/_ujs'); ?>
	<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/raty/jquery.raty.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/materialize.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
	<?php if(isset($is_logged_in) && $is_logged_in == 1) { ?>
	<script type="text/javascript" src="<?php echo site_url('js/feedback.js?v=1.1'); ?>"></script>
	<?php } ?>
	<script type="text/javascript" src="/nhome/js/lib/swal/sweetalert.min.js"></script>
	<script>
	var feedback = <?php if(isset($feedback)) { echo json_encode($feedback); } else echo json_encode(array()); ?>;
	$(function() {
		<?php if(!$this->input->cookie('CityId')) { echo "openCityModal();"; } ?>
		<?php if(isset($is_first_login) && $is_first_login == 1) { echo "openFirstTimeLoginModal();"; } ?>
		<?php
			if(isset($open_blklogin_modal) && $open_blklogin_modal == 1) {
				echo "openBlkLoginModal();";
			} elseif(isset($open_login_modal) && $open_login_modal == 1) {
				echo "openLoginModal();";
			}
		?>
	});
	<?php if (isset($OId) && $is_logged_in == 1) { ?>
		$(document).ready(function() {
			openFeedbackModal();
		});
	<?php } ?>
	</script>
</body>
</html>