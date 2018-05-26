<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="Keywords" content="bike servicing bangalore, bike service bangalore, book bike servicing slots, book bike service slots, online bike service bangalore, bike servicing online, bike service online, book bike service online, bike maintenance, bike maintenance online bangalore, bike insurance renewal, bike insurance renewal online, renew bike insurance online, online bike repairs" />
	<meta name="Description" content="Contact us with any query or request and we will get back to you." />
	<meta name="author" content="gear6.in">
	<meta name="title" content="page-title">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Contact Us</title>
	<link rel="shortcut icon" href="/img/icons/favicon.png" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/materialize.css'); ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.css'); ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css?v=1.0'); ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/flatui.css'); ?>"/>
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Raleway" type="text/css">
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>" />
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
<?php $this->load->view('user/components/__head'); ?>
<!-- main content starts -->
<main class="container">
	<div class="row">
		<div id="contact" class="col s12">
			<div class="col s12 m6 margin-top-50">
				<div class="col s12 agentr-box">
					<form method="POST" id="ucontactus">
						<div class="col s12">
							<div class="col s12 m4">
								<input type="text" name="name" oninput="checkfield();" placeholder="Your Name"/>
							</div>
							<div class="col s12 m4">
								<input type="text" name="phone" oninput="checkfield();" placeholder="Mobile Number"/>
							</div>
							<div class="col s12 m4">
								<input type="text" name="email" oninput="checkfield();" placeholder="e-Mail"/>
							</div>
						</div>
						<div class="col s12" style="margin-bottom:10px;">
							<textarea name="message" oninput="checkfield();" placeholder="Your message here ..."></textarea>
						</div>
						<div class="col s8 offset-s3">
							<div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="6LcsnBwTAAAAAFnsJ-4pXxb4qcfH6ok2snamkjFn"></div>
						</div>
						<div class="col s12 margin-top-10px">
							<button type="button" disabled id="sucontactus" class="width100 btn waves-effect waves-light">Send</button>
						</div>
					</form>
					<div class="alert alert-danger modal-error-text" role="alert" id="ucus_message" style="display:none;"></div>
				</div>
			</div>
			<div class="col s12 m6 margin-top-50">
				<div class="card green-bg darken-1">
					<div class="card-content white-text">
						<span class="card-title">Connect with Us</span>
						<p>We will get back to you as soon as we receive your message. Feel free to drop a mail or call us for any queries. 
						Looking forward to connect with you!
						</p>
					</div>
					<div class="card-action">
						<label style="color:#f6f6f5;">For more details :</label><p class="inline white-font margin-left-5px">support@gear6.in</p>
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
<script type="text/javascript">
var captchaClicked = false;
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
	$('.content-list-item').hover(function() {
		var id = $(this).attr('id').split('-')[1];
		for(var i=1;i<=4;i++) {
			if (i == id) {
				$('#hover-block-'+id).css('display','block');
			} else {
				$('#hover-block-'+i).css('display','none');
			}
		}
	});
	$('.company-search').click(function() {
		$('#company').val($(this).text());
		$('html, body').animate({scrollTop: $(".search-box").offset().top}, 500);
	});
	$('#sucontactus').on('click', function(e) {
		e.preventDefault();
		$('#sucontactus').hide('slow');
		$.ajax({
			type: "POST",
			url: "/user/userhome/ucontactus",
			data: $('#ucontactus').serialize(),
			dataType: "text",
			cache:false,
			success: function(data) {
				if(data == "1") {
					$('#ucontactus').hide('slow');
					$('#ucus_message').html('We received your request. We will get back to you soon..');
					$('#ucus_message').show('slow');
				} else if(data == "0") {
					$('#ucontactus').hide('slow');
					$('#ucus_message').html('Error processing your captcha. Please try again');
					$('#ucus_message').show('slow');
				}
			},
			error: function(error) {
				console.log(error);
			}
		});
	});
});
</script>
</body>
</html>