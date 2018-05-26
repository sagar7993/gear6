<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="Keywords" content="bike servicing bangalore, bike service bangalore, book bike servicing slots, book bike service slots, online bike service bangalore, bike servicing online, bike service online, book bike service online, bike maintenance, bike maintenance online bangalore, bike insurance renewal, bike insurance renewal online, renew bike insurance online, online bike repairs" />
	<meta name="Description" content="Wanna register with us as a service provider? Provide your details and we will get back to you." />
	<meta name="author" content="gear6.in">
	<meta name="title" content="page-title">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Agent (Service Provider) Registration Request</title>
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
<main class="container">
	<div class="row">
		<div id="agent" class="col s12 m12 l12">
			<div class="col s12 m6 l6 margin-top-50">
				<div class="col s12 m12 l12 agentr-box">
					<form method="POST" id="agregs">
					<div class="col s12 m12 l12"><input type="text" name="scname" oninput="checkfield();" placeholder="Service Center" /></div>
					<div class="col s12 m6 l6"><input type="text" name="cperson" oninput="checkfield();" placeholder="Your name"/></div>
					<div class="col s12 m6 l6"><input type="text" name="phone" oninput="checkfield();" placeholder="Mobile Number"/></div>
					<div class="col s12 m6 l6"><input type="text" name="email" oninput="checkfield();" placeholder="e-Mail"/></div>
					<div class="col s12 m6 l6">
						<select id="vtype" name="sctype" onchange="checkfield();">
							<option value=""></option>
							<option value="Service Center">Service Center</option>
							<option value="Petrol Bunk">Petrol Bunk</option>
							<option value="Emission Test Control">Emission Test Control</option>
							<option value="Puncture Shop">Puncture Shop</option>
						</select>
					</div>
					<div class="col s12 m12 l12 offset-l2 margin-bottom-10px">
						<div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="6LcsnBwTAAAAAFnsJ-4pXxb4qcfH6ok2snamkjFn"></div>
					</div>
					<div class="col s12 m12 l12 margin-bottom-10px"><button type="button" id="sagregs" class="width100 btn waves-effect waves-light" disabled>Submit</button></div>
					</form>
					<div class="alert alert-danger modal-error-text" role="alert" id="agrg_message" style="display:none;"></div>
				</div>
			</div>
			<div class="col s12 m6 margin-top-50">
				<div class="card green-bg darken-1">
					<div class="card-content white-text">
						<span class="card-title">Connect with Us</span>
						<p>We will get back to you as soon as we get your registration request. We would initiate the further process, once the
						verification process is done. Looking forward to connect with you!
						</p>
					</div>
					<div class="card-action">
						<label style="color:#f6f6f5;">For more details :</label><p class="inline white-font margin-left-5px">support@gear6.in</p>
					</div>
				</div>
			</div>
		</div>
		<!-- <div id="testi" class="col s12">
			<div class="testi-container container">
				<div class="row">
					<div class="col s12 m6 right-grey-margin">
						<div class="col s12 m12 l2">
							<img src="/img/sattu.png" class="testi-img circle z-depth-2">
						</div>
						<div class="col s12 m12 l10 testi-text">
						<i class="fa fa-quote-left"></i>
							gear6.in offers the best platform for the bikes to get maintained.They surely do hit the 6th gear. Kudos to the team!
						<i class="fa fa-quote-right"></i><br/>
						<span class="right testi-job">&nbsp;SE : Cisco, Bangalore</span><span class="right"><b>-- Nunna Sathwik</b>,</span>
						</div>
					</div>
					<div class="col s12 m6">
						<div class="col s12 m12 l2">
							<img src="/img/shwetu.png" class="testi-img circle z-depth-2">
						</div>
						<div class="col s12 m12 l10 testi-text">
						<i class="fa fa-quote-left"></i>
							gear6.in serves as a one stop solution for all the bikes. In their words as they say, they truly get the bikes revive. Great Job!
						<i class="fa fa-quote-right"></i><br/>
						<span class="right testi-job">&nbsp;Snr.SE : NetApp, Bangalore</span><span class="right"><b>-- Shweta Suman</b>,</span>
						</div>
					</div>
				</div>
			</div>
		</div> -->
	</div>
</main>
<?php $this->load->view('user/components/_foot'); ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/materialize.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/select2.full.js'); ?>"></script>
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
	$('ul.tabs').tabs();
	$('#vtype').select2({
		placeholder: "Service Type",
		minimumResultsForSearch: 10
	});
	$('#sagregs').on('click', function(e) {
		e.preventDefault();
		$('#sagregs').hide('slow');
		$.ajax({
			type: "POST",
			url: "/user/userhome/agregs",
			data: $('#agregs').serialize(),
			dataType: "text",
			cache:false,
			success: function(data) {
				if(data == "1") {
					$('#agregs').hide('slow');
					$('#agrg_message').html('We received your request. We will get back to you soon..');
					$('#agrg_message').show('slow');
				} else if(data == "0") {
					$('#agregs').hide('slow');
					$('#agrg_message').html('Error processing your captcha. Please try again');
					$('#agrg_message').show('slow');
				}
			}
		});
	});
});
function recaptchaCallback() {
	captchaClicked = true;
	checkfield();
};
function checkfield() {
	var x = 0;
	var values = [];
	$.each($('#agregs').serializeArray(), function(i, field) {
		values[field.name] = field.value;
		if(field.value == "" || field.value === null) {
			x = 1;
		}
	});
	if(parseInt(values['phone']) < 7000000000 || parseInt(values['phone']) > 9999999999) {
		x = 1;
	}
	if(!IsEmail(values['email'])) {
		x = 1;
	}
	if(x == 0 && captchaClicked == true) {
		$("#sagregs").removeAttr('disabled');
	} else {
		$("#sagregs").attr('disabled','disabled');
	}
}
function IsEmail(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}
</script>
</body>
</html>