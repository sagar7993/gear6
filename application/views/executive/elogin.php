<!DOCTYPE html>
<html class="no-scroll">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Executive Login</title>
	<link rel="shortcut icon" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/jQueryUI/jquery-ui.css'); ?>">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Raleway"  type="text/css">
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/materialize.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/estyle.css'); ?>">
</head>
<body class="elogin-body">
<div class="row center bcenter">
	<div class="col s12 m6 l4 elogin-main">
		<div class="card elogin-box darken-1">
			<div class="card-content white-text padb-zero">
				<span class="card-title">Executive Login</span>
				<form>
				<div class="row marginb-zero">
					<div class="input-field col s12">
						<input id="ex_phone" name="ex_phone" type="text" class="login-inpt" autocomplete="off">
						<label for="first_name" style="color:#d7d7d7">Mobile Number</label>
					</div>
					<div class="input-field col s12">
						<input type="password" style="display:none;" id="fke_password" />
						<input id="ex_password" name="ex_password" type="password" class="login-inpt" autocomplete="off">
						<label for="last_name" style="color:#d7d7d7">Password</label>
					</div>
					<div class="s12">
						<span style="color:#FFC107" id="err_message"></span>
					</div>
				</div>
				</div>
				<div class="card-action">
					<button class="waves-effect waves-light btn login-btn width100" id="ex_login">Login</button>
				</div>
			</form>
		</div>
	</div>
</div>
</body>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/materialize.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/executive.js'); ?>"></script>
<script>
</script>
</html>