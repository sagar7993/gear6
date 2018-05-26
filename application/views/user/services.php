<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="Keywords" content="bike servicing bangalore, bike service bangalore, book bike servicing slots, book bike service slots, online bike service bangalore, bike servicing online, bike service online, book bike service online, bike maintenance, bike maintenance online bangalore, bike insurance renewal, bike insurance renewal online, renew bike insurance online, online bike repairs" />
	<meta name="Description" content="Checkout the order flows for Periodic Servicing, Insurance Renewals, Repairs and Queries." />
	<meta name="author" content="gear6.in">
	<meta name="title" content="page-title">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Order Flow for Different Services Offered</title>
	<link rel="shortcut icon" href="/img/icons/favicon.png" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/materialize.css'); ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/select2.css'); ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css?v=1.0'); ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/flatui.css'); ?>"/>
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Raleway" type="text/css">
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/green.css'); ?>" />
	<style type="text/css">
		.collapsible-header {
			background-color: #028cbc!important;
			color: #ffffff!important;
		}
		.collection-item {
			background-color: #028cbc!important;
		}
		.collection-item a {
			color: #ffffff!important;
		}
	</style>
</head>
<body>
<?php $this->load->view('user/components/__head'); ?>
<main class="container">
	<div class="row">
		<div id="flows" class="col s12">
			<div class="col s12 m10">
				<ul class="collapsible" data-collapsible="expandable">
					<li id="ps_flow">
						<div class="collapsible-header active"><i class="material-icons">settings_overscan</i><b class="white-text">Periodic Servicing</b></div>
						<div class="collapsible-body">
							<!-- <img class="materialboxed" width="100%" src="/img/ps_flow.png"> -->
						</div>
					</li>
					<li id="rp_flow">
						<div class="collapsible-header active"><i class="material-icons">settings_overscan</i><b class="white-text">Repairs</b></div>
						<div class="collapsible-body">
							<!-- <img class="materialboxed" width="100%" src="/img/rp_flow.png"> -->
						</div>
					</li>
					<li id="ir_flow">
						<div class="collapsible-header active"><i class="material-icons">settings_overscan</i><b class="white-text">Insurance Renewal</b></div>
						<div class="collapsible-body">
							<!-- <img class="materialboxed" width="100%" src="/img/ir_flow.png"> -->
						</div>
					</li>
					<li id="qy_flow">
						<div class="collapsible-header active"><i class="material-icons">settings_overscan</i><b class="white-text">Query</b></div>
						<div class="collapsible-body">
							<!-- <img class="materialboxed" width="100%" src="/img/qy_flow.png"> -->
						</div>
					</li>
					<li id="ac_flow">
						<div class="collapsible-header active"><i class="material-icons">settings_overscan</i><b class="white-text">Accidental / Emergency</b></div>
						<div class="collapsible-body">
							<!-- <img class="materialboxed" width="100%" src="/img/ac_flow.png"> -->
						</div>
					</li>
					<li id="pb_flow">
						<div class="collapsible-header active"><i class="material-icons">settings_overscan</i><b class="white-text">Pertrol Bunks</b></div>
						<div class="collapsible-body">
							<!-- <img class="materialboxed" width="100%" src="/img/pb_flow.png"> -->
						</div>
					</li>
					<li id="ec_flow">
						<div class="collapsible-header active"><i class="material-icons">settings_overscan</i><b class="white-text">Emission Check</b></div>
						<div class="collapsible-body">
							<!-- <img class="materialboxed" width="100%" src="/img/ec_flow.png"> -->
						</div>
					</li>
					<li id="pt_flow">
						<div class="collapsible-header active"><i class="material-icons">settings_overscan</i><b class="white-text">Puncture Repairs</b></div>
						<div class="collapsible-body">
							<!-- <img class="materialboxed" width="100%" src="/img/pt_flow.png"> -->
						</div>
					</li>
				</ul>
			</div>
			<div class="col m2 hide-on-small-only">
				<div class="tabs-wrapper">
					<div class="row">
						<ul class="collection">
							<li class="collection-item"><a href="#ps_flow">Periodic Servicing</a></li>
							<li class="collection-item"><a href="#rp_flow">Repairs</a></li>
							<li class="collection-item"><a href="#ir_flow">Insurance Renewal</a></li>
							<li class="collection-item"><a href="#qy_flow">Query</a></li>
							<li class="collection-item"><a href="#ac_flow">Accidental / Emergency</a></li>
							<li class="collection-item"><a href="#pb_flow">Pertrol Bunks</a></li>
							<li class="collection-item"><a href="#ec_flow">Emission Check</a></li>
							<li class="collection-item"><a href="#pt_flow">Puncture Repairs</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php $this->load->view('user/components/_foot'); ?>
<script type="text/javascript" src="//code.jquery.com/jquery.js"></script>
<script type="text/javascript" src="<?php echo site_url('js/materialize.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/icheck.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/signup.js?v=1.0'); ?>"></script>
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
		$('ul.tabs').tabs();
		$('.tabs-wrapper .row').pushpin({ top: $('.tabs-wrapper').offset().top });
	});
</script>
</body>
</html>