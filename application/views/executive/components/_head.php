<!DOCTYPE html>
<html class="no-scroll">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Executive Orders</title>
	<link rel="shortcut icon" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/jQueryUI/jquery-ui.css'); ?>">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Raleway"    type="text/css">
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/materialize.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/estyle.css'); ?>">
</head>
<body>
<div class="main">
	<div class="">
		<nav>
			<div class="nav-wrapper">
				<a href="https://www.gear6.in/" id="nav-desktop" class="brand-logo center height100">
					<img src="https://www.gear6.in/img/mr_logo.png" class="right responsive-img">
				</a>
				<a href="#" data-activates="mobile-view" class="button-collapse"><i class="material-icons">menu</i></a>
				<ul class="left hide-on-med-and-down">
					<li><a href="/executive/exechome/eosummary">My Orders</a></li>
					<li><a href="/executive/exechome/petrolbills">Fuel Billing</a></li>
					<li><a href="/executive/exechome/todaytasks">Today's Tasks</a></li>
					<li><a href="/executive/exechome/esignout">Sign Out</a></li>
				</ul>
				<ul class="side-nav" id="mobile-view">
					<li><a href="/executive/exechome/eosummary"><i class="material-icons">local_play</i><div class="inline-block">My Orders</div></a></li>
					<li><a href="/executive/exechome/petrolbills"><i class="material-icons">local_gas_station</i>Fuel Billing</a></li>
					<li><a href="/executive/exechome/todaytasks"><i class="material-icons">restore</i>Today's Tasks</a></li>
					<li><a href="/executive/exechome/esignout"><i class="material-icons">power_settings_new</i>Sign Out</a></li>
				</ul>
			</div>
		</nav>