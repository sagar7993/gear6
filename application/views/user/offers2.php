<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="Keywords" content="bike servicing bangalore, bike service bangalore, book bike servicing slots, book bike service slots, online bike service bangalore, bike servicing online, bike service online, book bike service online, bike maintenance, bike maintenance online bangalore, bike insurance renewal, bike insurance renewal online, renew bike insurance online, online bike repairs" />
	<meta name="Description" content="gear6.in's Team" />
	<meta name="author" content="gear6.in">
	<meta name="title" content="page-title">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Offers</title>
	<link rel="shortcut icon" href="/img/icons/favicon.png" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/materialize.css'); ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css?v=1.0'); ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/flatui.css'); ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('nhome/js/lib/swal/sweetalert.css'); ?>"/>
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Raleway" type="text/css">
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<style>
		.team-member {
		    text-align: center;
		    margin-bottom: 50px;
		}
		.team-member img {
		    margin: 0 auto;
		    border: 7px solid #fff;
		}
		.team-member h4 {
		    margin-top: 25px;
		    margin-bottom: 0;
		    text-transform: none;
		}
		.team-member p {
		    margin-top: 0;
		}
		.text-muted {
		    color: #777;
		}
		ul.social-buttons {
		    margin-bottom: 0;
		}
		.list-inline {
		    padding-left: 0;
		    margin-left: -5px;
		    list-style: none;
		}
		.img-responsive, .thumbnail a>img, .thumbnail>img {
		    display: block;
		    max-width: 100%;
		    height: auto;
		}
		.outer-box {
		  display: block;
		  position: relative;
		  opacity: 1;
		}
		.outer-box .inner-box {
		  height: inherit;
		  width: inherit;
		  opacity: 0;
		  top: 0;
		  left: 0;
		  right: 0;
		  position: absolute;
		  padding: 0;
		  transition: opacity .5s linear, transform .5s ease-in;
		}
		.outer-box .inner-box p {
		  color: #fff;
		  margin-top: 40%;
		  text-align: center;
		  vertical-align: middle;
		}
		.outer-box:hover .inner-box {
		  opacity: 1;
		  transform: rotateY(180deg);
		}
	</style>
</head>
<body>
<?php $this->load->view('user/components/__head'); ?>
<!-- main content starts -->
	<main class="container">
		<div class="row">
			<div id="privacy" class="col s12">
				<div class="row container margin-top-50 green-text">
					<h3 class="center">Offers</h3>
					<div class="col s12 m12 l12 margin-top-50">
						<div class="row">
							<div class="col s12 m6 l6">
								<div class="team-member outer-box">
								    <img src="<?php echo site_url("img/team/rakesh.jpg"); ?>" class="responsive-img" alt="Flat 100 Rs. Off">
								    <h4>GSIX100</h4>
								    <img src="https://www.placehold.it/300x300" class="responsive-img inner-box" alt="Flat 100 Rs. Off"></img>
								    <p class="text-muted">Flat 100 Rs. Off</p>
								</div>
							</div>
							<div class="col s12 m6 l6">
								<div class="team-member outer-box">
								    <img src="<?php echo site_url("img/team/rakesh.jpg"); ?>" class="responsive-img" alt="75 Rs. Off">
								    <h4>Referral Offer</h4>
								    <img src="https://www.placehold.it/300x300" class="responsive-img inner-box" alt="75 Rs. Off"></img>
								    <p class="text-muted">75 Rs. Off</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	<!-- main content ends -->
	<?php $this->load->view('user/components/_foot'); ?>
	<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/jquery.timepicker.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/jquery.ui.datepicker.validation.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/materialize.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/signup.js?v=1.0'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('nhome/js/lib/swal/sweetalert.min.js'); ?>"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		$("input[type='checkbox'], input[type='radio']").icheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green'
		});
		<?php if(!isset($city_id)) { echo "openCityModal();"; } ?>
		<?php if(isset($is_first_login) && $is_first_login == 1) { echo "openFirstTimeLoginModal();"; } ?>
		<?php
			if(isset($open_blklogin_modal) && $open_blklogin_modal == 1) {
				echo "openBlkLoginModal();";
			} elseif(isset($open_login_modal) && $open_login_modal == 1) {
				echo "openLoginModal();";
			}
		?>
		$('.outer-box').on('click', function() {
			swal({
				title: "Referral Offer - T & C",
				text: '\
					<h5 style="clear:left;text-align:left;padding-top:12px;">Steps to Redeem</h5>\
					<div style="clear:left;" class="col s12 m12">\
						<ol style="float:left;text-align:left;padding-left:20px;">\
							<li>As soon as you sign up, go to “My Profile” page.</li>\
							<li>Your referral URL will be available at the bottom of the page. Share the URL with your friends and family.</li>\
							<li>As soon as they sign up with your referral URL they will receive a discount coupon of Rs.75</li>\
							<li>As soon as your referred friend places an order with us, you will receive a discount coupon of Rs.75</li>\
							<li>Offer valid only on online payment methods</li>\
						</ul>\
					</div>\
				',
				html: true
			});
		});
	});
	</script>
	</body>
</html>