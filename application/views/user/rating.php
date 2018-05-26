<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="Keywords" content="bike servicing bangalore, bike service bangalore, book bike servicing slots, book bike service slots, online bike service bangalore, bike servicing online, bike service online, book bike service online, bike maintenance, bike maintenance online bangalore, bike insurance renewal, bike insurance renewal online, renew bike insurance online, online bike repairs" />
	<meta name="Description" content="gear6.in's privacy policy" />
	<meta name="author" content="gear6.in">
	<meta name="title" content="page-title">
	<title>gear6.in - Bike Maintenance Made Easy</title>
	<link rel="shortcut icon" href="/img/icons/favicon.png" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/materialize.css'); ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.css'); ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css?v=1.0'); ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/flatui.css'); ?>"/>
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Raleway" type="text/css">
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="/nhome/js/lib/swal/sweetalert.css">
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
<?php $this->load->view('user/components/__head'); ?>
	<!-- main content starts -->
	<main class="container">
		<div class="row" align="center">
			<h3>Rating recorded successfully :-)</h3>
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
	<script src="<?php echo site_url('nhome/js/lib/swal/sweetalert.min.js'); ?>"></script>
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
		swal({
			title: "Success",
			text: "Thank you for your feedback",
			type: "success",
			showCancelButton: false,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Ok",
			closeOnConfirm: false
		}, function() {
			window.location.assign("https://www.gear6.in");
		});
	});
	</script>
	</body>
</html>