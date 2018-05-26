<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Profile - Order Feedback Details</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('vendor/components/_vcss'); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('js/raty/jquery.raty.css'); ?>">
</head>
<body>
	<?php $this->load->view('vendor/components/_head'); ?>
	<?php if(isset($v_is_logged_in) && $v_is_logged_in == 1) { ?>
		<div class="wrapper row-offcanvas row-offcanvas-left">
			<?php $this->load->view('vendor/components/_sidebar'); ?>
			<aside class="right-side">
				<section class="content-header">
					<h1>
						Feedback
						<small>Order ID&nbsp;: <?php if(isset($oid)) { echo $oid; } ?></small>
					</h1>
				</section>
				<section class="section-center1">
					<div class="section-header-2">
						<span class="confirm-title">Customer Rating and Feedback</span>
					</div>
					<div class="section-content1">
						<?php if(isset($od_fback) && $od_fback !== NULL) { ?>
						<div class="modal-body">
							<div class="col-xs-12 ratingContainer">
								<div class="col-xs-6 ratingTitle">Service Offered</div>
								<div class="col-xs-6 rate_it_i_say" id="serviceRating"><?php echo $od_fback[0]['RatingValue']; ?></div>
							</div>
							<div class="col-xs-12 ratingContainer">
								<div class="col-xs-6 ratingTitle">Customer Care Support</div>
								<div class="col-xs-6 rate_it_i_say" id="custcareRating"><?php echo $od_fback[4]['RatingValue']; ?></div>
							</div>
							<div class="col-xs-12 ratingContainer">
								<div class="col-xs-6 ratingTitle">Amenities Quality</div>
								<div class="col-xs-6 rate_it_i_say" id="amenitiesRating"><?php echo $od_fback[2]['RatingValue']; ?></div>
							</div>
							<div class="col-xs-12 ratingContainer">
								<div class="col-xs-6 ratingTitle">Price Worthiness</div>
								<div class="col-xs-6 rate_it_i_say" id="priceRating"><?php echo $od_fback[3]['RatingValue']; ?></div>
							</div>
							<div class="col-xs-12 lastRatingContainer">
								<div class="col-xs-6 ratingTitle">Offers Reilability</div>
								<div class="col-xs-6 rate_it_i_say" id="offersRating"><?php echo $od_fback[1]['RatingValue']; ?></div>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="col-xs-2 green-text margin-left-15"><strong>Description&nbsp;:</strong></div>
							<div class="col-xs-9"><?php if(isset($od_fback[0]['Feedback'])) { echo $od_fback[0]['Feedback']; } else { echo 'No description by the user for this feedback'; } ?></div>
						</div>
						<br><br>
						<div class="col-xs-12">
							<div class="col-xs-2 green-text margin-left-15"><strong>Remarks&nbsp;:</strong></div>
							<div class="col-xs-9"><?php if(isset($od_fback[0]['Remarks'])) { echo $od_fback[0]['Remarks']; } else { echo 'No remarks by the admin for this feedback'; } ?></div>
						</div>
						<?php } else { ?>
							<div class="col-xs-12">
								<div class="col-xs-12 green-text margin-left-15"><strong>No feedback by the user yet for this order&nbsp;</strong></div>
							</div>
						<?php } ?>
					</div>
				</section>
			</aside>
		</div>
	<?php $this->load->view('vendor/components/_foot'); ?>
	<?php } ?>
<?php $this->load->view('vendor/components/_vjs'); ?>
<script type="text/javascript" src="<?php echo site_url('js/raty/jquery.raty.js'); ?>"></script>
<script type="text/javascript">
</script>
</body>
</html>