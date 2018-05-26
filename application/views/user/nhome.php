<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="apple-itunes-app" content="app-id=1101618850">
    <meta name="google-play-app" content="app-id=in.gear6">
    <!-- <meta name="msApplication-PackageFamilyName" content="microsoft.build_8wekyb3d8bbwe"/> -->
    <link rel="apple-touch-icon" href="https://www.gear6.in/img/icons/favicon.png"/>
    <link rel="android-touch-icon" href="https://www.gear6.in/img/icons/favicon.png"/>
    <link rel="windows-touch-icon" href="https://www.gear6.in/img/icons/favicon.png"/>
	<meta name="Keywords" content="bike servicing bangalore, bike service bangalore, book bike servicing slots, book bike service slots, online bike service bangalore, bike servicing online, bike service online, book bike service online, bike maintenance, bike maintenance online bangalore, bike insurance renewal, bike insurance renewal online, renew bike insurance online, online bike repairs" />
	<meta name="Description" content="gear6.in is an on-demand bike maintenance solution which offers services for bike repairs, periodic maintenance, insurance renewal and more." />
	<meta name="author" content="gear6.in">
	<meta name="title" content="gear6.in - Online Bike Service and Repair, Bike Insurance Renewal | Bangalore">
	<meta property="og:title" content="gear6.in - Online Bike Service and Repair, Bike Insurance Renewal | Bangalore" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="https://www.gear6.in/img/ogimage.jpg" />
	<meta property="og:url" content="https://www.gear6.in" />
	<meta property="og:description" content="gear6.in is an on-demand bike maintenance solution which offers services for bike repairs, periodic maintenance, insurance renewal and more." />
	<meta property="og:site_name" content="gear6.in" />
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Online Bike Service and Repair, Pickup and Drop for Bikes, Bike Insurance Renewal | Bangalore</title>
	<link rel="shortcut icon" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" href="/nhome/css/g6styles.css?v=1.0">
	<link rel="stylesheet" href="//fonts.googleapis.com/icon?family=Material+Icons">
	<style>
	@media screen and (min-width: 993px){		
		.margin-left-10-pc{
			margin-left: 10%!important;
		}
	}
	.btn a {
		color: #fff !important;
	}
	.input-bd {
		border: none !important;
		border-bottom: 1px solid #9e9e9e !important;
	}
	#inthenewsslider {
		height: 250px !important;
	}
	#inthenewsslider ul {
		height: 200px !important;
	}
	#inthenewsslider ul.indicators {
		height: 50px !important;
		margin-top: 50px;
	}
	#inthenewsslider .indicators {
		position: inherit !important;
	}
	#offerslider {
		height: 240px !important;
	}
	#offerslider ul {
		height: 120px !important;
	}
	#offerslider li {
		overflow: visible;
	}
	@media only screen and (min-width: 601px) {
		nav, nav .nav-wrapper i, nav a.button-collapse, nav a.button-collapse i {
	    	line-height: 63px;
	    }
	}
	.select2-container {
		height: 47px;
	}
	.select2-container--default .select2-selection--single .select2-selection__rendered {
		line-height: 36px;
	}
	.select2-container--default .select2-selection--single .select2-selection__arrow b {
		margin-top: 7px;
	}
	</style>
</head>
<body>
	<?php $this->load->view('user/components/_modals'); ?>
	<div id="accidentModal" class="modal" role="dialog" style="background-color: #f6f6f5 !important;"> 
		<form class="col s12" method="" action="" id = "form" name = "form">
			<div class="modal-content">
				<div class="modal-header custom-modal-header margin-top-5px">
					<a class="close sClose modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></a>
				</div>
				<div class="center"><h5 class="blue-color-text-gear6-heading">Accidental / Emergency Request - Enter your details</h5></div>
				<div class="row remove-margin-bottom">
					<div class="row remove-margin-bottom">
						<div class="input-field col s2"><label class="dark-text" for="accidentPhoneNumber">Phone Number</label><br/></div> 
						<div class="input-field col s8"> 
							<input type="text" id="accidentPhoneNumber" name = "accidentPhoneNumber" placeholder="Enter your 10 digit mobile number" class="validate form-control input-bd"<?php if(isset($current_user)) { echo ' value="' . $current_user->Phone . '" readonly="true"'; } ?>> 
						</div> 
						<div class="input-field col s2"></div> 
					</div>
					<div class="row remove-margin-bottom">
						<div class="input-field col s2"><label class="dark-text" for="accidentEmail">Email</label><br/></div> 
						<div class="input-field col s8"> 
							<input type="text" id="accidentEmail" name = "accidentEmail" placeholder="Enter your Email Id" class="validate form-control input-bd"<?php if(isset($current_user)) { echo ' value="' . $current_user->Email . '" readonly="true"'; } ?>> 
						</div> 
						<div class="input-field col s2"></div> 
					</div>
					<div class="row remove-margin-bottom"> 
						<div class="input-field col s2"><label class="dark-text" for="accidentText">Description</label><br/></div> 
						<div class="input-field col s8">
							<textarea id="accidentText" name="accidentText" placeholder="Provide additional details" class="materialize-textarea input-bd" rows="4" cols="200"></textarea>
						</div> 
						<div class="input-field col s2"></div> 
					</div>
					<?php if(isset($is_logged_in) && $is_logged_in == 1) { ?>
						<div class="row remove-margin-bottom" id="placeEmgOrderOTP">
							<div class="input-field col s6 m6" style="margin-left:26%">
								<button class="btn white-text waves-effect waves-light search-btn" type="button" onclick="placeEmgOrder(false)">Place Order</button>
							</div>
						</div>
					<?php } else { ?>
						<div class="row remove-margin-bottom" id="placeEmgOrderOTP">
							<div class="input-field col s6 m6" style="margin-left:26%">
								<button class="btn white-text waves-effect waves-light search-btn" type="button" onclick="sendAccidentOTP(true)">Send OTP</button>
							</div>
						</div>
						<div class="row remove-margin-bottom" id="placeEmgOrder" style="display:none;">
							<div class="input-field col s4 m4" style="margin-left:10%;padding-top:26px;">
								<input type="number" id="accidentOTP" name = "accidentOTP" placeholder="Enter OTP Received" class="validate form-control input-bd">
							</div>
							<div class="input-field col s3 m3">
								<button class="btn white-text waves-effect waves-light search-btn" type="button" onclick="placeEmgOrder(true)">Place Order</button>
							</div>
							<div class="input-field col s3 m3" style="padding-top:45px;cursor:pointer;">
								<p onclick="sendAccidentOTP('true')">(Resend OTP)</button>
							</div>
						</div>
					<?php } ?>
				</div> 
			</div>
		</form>
	</div>
	<div id="punctureModal" class="modal" role="dialog" style="background-color: #f6f6f5 !important;"> 
		<form class="col s12" method="" action="" id = "form" name = "form">
			<div class="modal-content">
				<div class="modal-header custom-modal-header margin-top-5px">
					<a class="close sClose modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></a>
				</div>
				<div class="center"><h5 class="blue-color-text-gear6-heading">Puncture Repair Request - Enter your details</h5></div>
				<div class="row remove-margin-bottom">
					<div class="row remove-margin-bottom">
						<div class="input-field col s2"><label class="dark-text" for="puncturePhoneNumber">Phone Number</label><br/></div> 
						<div class="input-field col s8">
							<input type="text" id="puncturePhoneNumber" name = "puncturePhoneNumber" placeholder="Enter your 10 digit mobile number" class="validate form-control input-bd"<?php if(isset($current_user)) { echo ' value="' . $current_user->Phone . '" readonly="true"'; } ?>>
						</div> 
						<div class="input-field col s2"></div> 
					</div>
					<div class="row remove-margin-bottom">
						<div class="input-field col s2"><label class="dark-text" for="puncturePhoneEmail">Email</label><br/></div> 
						<div class="input-field col s8">
							<input type="text" id="punctureEmail" name = "punctureEmail" placeholder="Enter your Email Id" class="validate form-control input-bd"<?php if(isset($current_user)) { echo ' value="' . $current_user->Email . '" readonly="true"'; } ?>>
						</div> 
						<div class="input-field col s2"></div> 
					</div>
					<div class="row remove-margin-bottom">
						<div class="input-field col s2"><label class="dark-text">Type of Tyre</label><br/></div>
						<div class="input-field col s8">
							<p>
								<input name="pttype" type="radio" id="tubeless" value="Tubeless" class="with-gap noticheck" />
								<label for="tubeless">Tubeless</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input name="pttype" type="radio" id="tubed" value="Tubed" class="with-gap noticheck" />
								<label for="tubed">Tubed</label>
							</p>
						</div>
					</div>
					<div class="row remove-margin-bottom">
						<div class="input-field col s2"><label class="dark-text">Punctered Tyre</label><br/></div>
						<div class="input-field col s8">
							<p>
								<input name="pttyre" type="radio" id="fronttyre" value="Front" class="with-gap noticheck" />
								<label for="fronttyre">Front</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input name="pttyre" type="radio" id="reartyre" value="Rear" class="with-gap noticheck" />
								<label for="reartyre">Rear</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input name="pttyre" type="radio" id="bothtyres" value="Both Front & Rear" class="with-gap noticheck" />
								<label for="bothtyres">Both Front & Rear</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							</p>
						</div>
					</div>
					<div class="row remove-margin-bottom"> 
						<div class="input-field col s2"><label class="dark-text" for="punctureText">Description</label><br/></div> 
						<div class="input-field col s8">
							<textarea id="punctureText" name="punctureText" placeholder="Provide additional details" class="materialize-textarea input-bd" rows="4" cols="200"></textarea>
						</div> 
						<div class="input-field col s2"></div> 
					</div>
					<?php if(isset($is_logged_in) && $is_logged_in == 1) { ?>
						<div class="row remove-margin-bottom" id="placePunctureOrderOTP">
							<div class="input-field col s6 m6" style="margin-left:26%">
								<button class="btn white-text waves-effect waves-light search-btn" type="button" onclick="placePunctureOrder(false)">Place Order</button>
							</div>
						</div>
					<?php } else { ?>
						<div class="row remove-margin-bottom" id="placePunctureOrderOTP">
							<div class="input-field col s6 m6" style="margin-left:26%">
								<button class="btn white-text waves-effect waves-light search-btn" type="button" onclick="sendPunctureOTP(true)">Send OTP</button>
							</div>
						</div>
						<div class="row remove-margin-bottom" id="placePunctureOrder" style="display:none;">
							<div class="input-field col s4 m4" style="margin-left:10%;padding-top:26px;">
								<input type="number" id="punctureOTP" name = "punctureOTP" placeholder="Enter OTP Received" class="validate form-control input-bd">
							</div>
							<div class="input-field col s3 m3">
								<button class="btn white-text waves-effect waves-light search-btn" type="button" onclick="placePunctureOrder(true)">Place Order</button>
							</div>
							<div class="input-field col s3 m3" style="padding-top:45px;cursor:pointer;">
								<p onclick="sendPunctureOTP('true')">(Resend OTP)</button>
							</div>
						</div>
					<?php } ?>
				</div> 
			</div>
		</form>
	</div>
	<main class="container-home main">
		<section class="page-container" id="bookyourservice">
			<ul id="city-dropdown" class="dropdown-content">
				<li onclick="changeCity('1', 'Bangalore');"><a href="javascript:;">Bangalore</a></li>
			</ul>
			<ul id="city-dropdown-menu" class="dropdown-content">
				<li onclick="changeCity('1', 'Bangalore');"><a href="javascript:;">Bangalore</a></li>
			</ul>
			<div class="navbar-fixed">
				<nav class="nav-style">
				<div class="nav-wrapper">
				<a href="" class="brand-logo"><img src="<?php echo site_url('img/mr_logo.png'); ?>" class="responsive-img logo-img"></a>
				<a href="" data-activates="mobile-demo" class="button-collapse" style="margin-left:2%;color: #028cbc!important;"><i class="material-icons">menu</i></a>
				<ul class="right hide-on-med-and-down">
					<?php if(isset($is_logged_in) && $is_logged_in == 1) { ?>
						<li>
							<a href="<?php echo base_url('user/account/uprofile#show_referral_url'); ?>" class=" waves-effect waves-light nav-g6link">
								<i class="material-icons left g6nav-icon">card_giftcard</i>
								<span>Refer A Friend
								</span>
							</a>
						</li>
						<?php } ?>
					<li>
						<a href="tel:+919494845111" class=" waves-effect waves-light nav-g6link"><i class="material-icons left g6nav-icon">phone</i>+91-9494845111</a>
					</li>
					<li>
						<a class="dropdown-button  waves-effect waves-light nav-g6link"><i class="material-icons left g6nav-icon">location_on</i><span id="selected-city-dropdown">Bangalore</span>
					</a>
					</li>
					<li class="pointer">
						<?php if(isset($is_logged_in) && $is_logged_in == 1) { ?>
						<a id="af_login_dw" class='dropdown-button  nav-g6link' data-activates='user_menu'>
							<i class="material-icons left g6nav-icon">settings_power</i>
							<span><?php echo convert_to_camel_case($this->session->userdata('name')); ?></span>
						</a>
						<ul class="dropdown-content custom-dd" id="user_menu">
							<li role="presentation"><a href="<?php echo base_url('user/account/corders'); ?>" class="blue-color-text-gear6"><i class="nav-icon material-icons left">loyalty</i><span>My Orders</span></a></li>
							<li role="presentation"><a href="<?php echo base_url('user/account/uprofile'); ?>" class="blue-color-text-gear6"><i class="nav-icon material-icons left">account_circle</i><span>My Profile</span></a></li>
							<li role="presentation" class="divider"></li>
							<li role="presentation"><a href="/home/user_logout/<?php echo base64_encode('/user/userhome'); ?>" class="blue-color-text-gear6"><i class="nav-icon material-icons left">open_in_browser</i><span>Logout</span></a></li>
						</ul>
						<?php } else { ?>
						<a class="waves-effect waves-light modal-trigger sign-link" data-target="login" id="psignIn">
							<i class="material-icons left g6nav-icon">input</i>
							<span>Sign In</span>
						</a>
					<?php } ?>
					</li>
				</ul>
				<ul class="side-nav" id="mobile-demo">
					<li>
					<a href="tel:+919494845111" class="blue-color-text-gear6 waves-effect waves-light"><i style="line-height:64px;" class="material-icons left">phone</i><b>+91-9494845111</b></a>
					</li>
					<li>
					<a class="dropdown-button blue-color-text-gear6 waves-effect waves-light" href="" data-activates=""><i class="material-icons left">location_on</i><span id="selected-city-dropdown-menu"><b>Bangalore</b></span>
					</a>
					</li>
					<li>
						<?php if(isset($is_logged_in) && $is_logged_in == 1) { ?>
						<a href="" id="af_login_dw" class='dropdown-button' data-activates='user_menu_mobile'>
							<i class="nav-icon material-icons left">settings_power</i>
							<span><?php echo convert_to_camel_case($this->session->userdata('name')); ?></span>
						</a>
						<ul class="dropdown-content custom-dd" id="user_menu_mobile">
							<li role="presentation"><a href="<?php echo base_url('user/account/corders'); ?>"><i class="nav-icon material-icons left">loyalty</i><span>My Orders</span></a></li>
							<li role="presentation"><a href="<?php echo base_url('user/account/uprofile'); ?>"><i class="nav-icon material-icons left">account_circle</i><span>My Profile</span></a></li>
							<li role="presentation" class="divider"></li>
							<li role="presentation"><a href="/home/user_logout/<?php echo base64_encode(current_url()); ?>"><i class="nav-icon material-icons left">open_in_browser</i><span>Logout</span></a></li>
						</ul>
						<?php } else { ?>
						<a class="blue-text-no-underline waves-effect waves-light">
							<i class="material-icons left">input</i>
							<span class="modal-trigger sign-link" data-target="login" id="psignIn">Sign In</span>
							<span>/</span>
							<span class="modal-trigger sign-link" data-target="signup" id="psignUp">Sign Up</span>
						</a>
					<?php } ?>
					</li>
				</ul>
				</div>
			</nav>
			</div>
			<div class="row remove-margin-bottom">
				<div class="col s12 m12 l6 offset-l1 heading-box">
					<h5 class="darl-gray-color-text-gear6">Bike Maintenance Made Easy</h5>
					<h6 class="light-gray-color-text-gear6">Your Bike is Precious. Your Time is Priceless.</h6>
				</div>
			</div>
			<div class="row remove-margin-bottom service-box-parent">
				<div class="col s12 m12 l5 offset-m1 service-box z-depth-1">
					<img class="responsive-img center" src="<?php echo site_url('nhome/images/Logo-loop.gif'); ?>" style="display:none;height:75px;width:75px;vertical-align:middle;margin-left:42%!important;" id="loading"></img>
					<div id="services-box" class="animate-div" align="center" style="display:none;">
						<div class="row remove-margin-bottom">
							<div class="input-field col s12 m12 service-location-search dark-gray-color-text-gear6">
								<i class="material-icons prefix service-icon service-location-icon dark-gray-color-text-gear6">location_on</i>
								<input id="searchLocation" name="searchLocation" type="text" placeholder="Search your locality" class="search-field-main">
								<input style="display:none;" id="ulatitude" name="qlati">
								<input style="display:none;" id="ulongitude" name="qlongi">
							</div>
						</div>
						<div class="search-divider z-depth-1"></div>
						<div class="row service-icon-box center remove-margin-bottom">
							<div class="row" id="services-box-container" class="remove-margin-bottom">
							</div>
						</div>
					</div>
					<div id="bike-brands-box" class="animate-div brands-box-container" style="display:none;">
						<div class="row" id="brands-box-container" class="remove-margin-bottom">
							<div class="row  remove-margin-bottom">
								<div class="col s12 brands-title">What's your vehicle brand?</div><br/>
								<div class="col s10 m10 l10 offset-s1 offset-m1 offset-l1 remove-margin-bottom brands-icon-box-container" id="brands-icon-box-container">
									<div class="slick-center" id="slick-slider-container"></div>
								</div>
							</div>
							<div class="row" id="brands-date-box-container" class="remove-margin-bottom">
								<div class="input-field col s6 m6 l6 service-date">
									<input id="date" type="text" name="date" class="datepicker calendar-icon form-control no-border-input" placeholder="Pick your date"></input>
								</div>
								<div class="input-field col s6 m6 l6">
									<select class="form-control styled-select" id="selected-bike-dropdown-value" onchange="selectBikeModel()">
										<option></option>
									</select>
								</div>
								<div class="brands-foot">
									<div class="input-field col s2 m2 l2 dark-gray-color-text-gear6 remove-margin-bottom mtop-4">
										<a href="javascript:;" id="backButton" name="backButton" class="waves-effect waves-light">
											<img src="<?php echo site_url('nhome/images/icons/back.png'); ?>" class="responsive-img">
										</a>
									</div>
									<div class="input-field col s6 m6 l4 offset-l6 dark-gray-color-text-gear6 remove-margin-bottom">
										<button class="btn white-text waves-effect waves-light search-btn" onclick="fetchServiceCenters()" id="final_action">Search</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col s12 m12 l4 offset-l1 offers-box center">
					<br/>
					<div class="video-title">
						How it works ?
					</div>
					<div class="video-container z-depth-1">
						<iframe width="853" height="480" src="//www.youtube.com/embed/ciBXJTYygJM?autoplay=0&rel=0&modestbranding=0&showinfo=0&fs=0" frameborder="0" allowfullscreen></iframe>
					</div>
					<div class="slider" id="offerslider">
						<ul class="slides">
							<li class="">
								<div class="offer-container z-depth-1 row">
									<div class="col s10">
										<div class="offer-title">Referral Offer</div>
										<div class="offer-desc">You get flat Rs. 75 off on your service</div>
									</div>
									<div class="col s2">
										<i class="material-icons offer-icon">loyalty</i>
									</div>
									<div class="offer-desc col s12">Now make your bike and your friend happy at the same time. Just refer us to your friends and family. Click To <a href="javascript:;" id="showReOffer">Know More</a></div>
								</div>
							</li>
							<li class="">
								<div class="offer-container z-depth-1 row">
									<div class="col s10">
										<div class="offer-title"> Flat Rs.100 Off</div>
										<div class="offer-desc">Now make your bike and your wallet happy! </div>
									</div>
									<div class="col s2">
										<i class="material-icons offer-icon">loyalty</i>
									</div>
									<div class="offer-desc col s12">Use coupon code <b>GSIX100</b> to get flat Rs.100 off on your first order with us</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<section class="page-container light-background center section-pad" id="howitworks">
			<h5 class="blue-color-text-gear6-heading" style="width:80%;margin-left:10%;">You Book, We Service!</h5>
			<h6 class="light-gray-color-text-gear6" style="width:80%;margin-left:10%;">We’ll pick up your bike, wave a magic wand over it and hand it back to you as good as new!</h6>
			<br/><br/> 
			<div class="row how-padding-content"> 
				<div class="col s12 m12">
					<div class="row">
						<div class="col s12 m3">
							<img src="<?php echo site_url('nhome/images/icons/bookaservice.png'); ?>" class="responsive-img process-flow"></img><br/>
							<span class="blue-color-text-gear6 font-bold">Book A Service</span><br/>
							<span class="light-gray-color-text-gear6">Just hit gear6.in and select the service that you need.We'll pick your bike from home/office.</span>
						</div>
						<div class="col s2 m1 mobile-hidden" style="margin-top:7%!important;">
							<img src="<?php echo site_url('nhome/images/icons/howitworks/line.png'); ?>" class="responsive-img"></img>
						</div>
						<div class="col s12 m4">
							<img src="<?php echo site_url('nhome/images/icons/getitserviced.png'); ?>" class="responsive-img process-flow"></img><br/>
							<span class="blue-color-text-gear6 font-bold">Get It Serviced</span><br/>
							<span class="light-gray-color-text-gear6">We’ll wave a magic wand over her and get your bike looking and feeling fabulous</span>
						</div>
						<div class="col s2 m1 mobile-hidden" style="margin-top:7%!important;">
							<img src="<?php echo site_url('nhome/images/icons/howitworks/line.png'); ?>" class="responsive-img"></img>
						</div>
						<div class="col s12 m3">
							<img src="<?php echo site_url('nhome/images/icons/getservicing.png'); ?>" class="responsive-img process-flow"></img><br/>
							<span class="blue-color-text-gear6 font-bold">We’ll Deliver your Ride</span><br/>
							<span class="light-gray-color-text-gear6">We’ll drop your bike right to your doorstep. You won’t even know it was gone.</span>
						</div>
					</div>
					<div>
						<button class="btn waves-effect waves-light" id="goTop">Book A Service Now!</button>
					</div>
				</div>
			</div>
		</section>
		<section class="page-container section-pad" id="whyyouneed">
			<br/>
			<h5 class="blue-color-text-gear6-heading center">More Convenience, Lower Cost!</h5>
			<h6 class="light-gray-color-text-gear6 center" style="width:80%;margin-left:10%;">Need a reason to book on gear6.in? We give you 6!</h6>
			<br/><br/>
			<div class="row why-padding-content">
				<div class="col s12 m6 l4 add-margin-bottom">
					<div class="row">
						<div class="col s6 m6 l6 center">
							<img src="<?php echo site_url('nhome/images/icons/guaranteeddelivery.png'); ?>" class="responsive-img features"></img>
						</div>
						<div class="col s6 m6 l6">
							<p class="blue-color-text-gear6 font-bold">Guaranteed Delivery</p>
							<p class="light-gray-color-text-gear6">We take you and your time seriously. When we say we’ll be there, we mean it.</p>
						</div>
					</div>
				</div>
				<div class="col s12 m6 l4 add-margin-bottom">
					<div class="row">
						<div class="col s6 m6 l6 center">
							<img src="<?php echo site_url('nhome/images/icons/tracking.png'); ?>" class="responsive-img features"></img>
						</div>
						<div class="col s6 m6 l6">
							<p class="blue-color-text-gear6 font-bold">Real Time Tracking</p>
							<p class="light-gray-color-text-gear6">Track your bike on-the-go with our real-time tracking system. You can be closer to your bike than ever before.</p>
						</div>
					</div>
				</div>
				<div class="col s12 m6 l4 add-margin-bottom">
					<div class="row">
						<div class="col s6 m6 l6 center">
							<img src="<?php echo site_url('nhome/images/icons/doorstepservice.png'); ?>" class="responsive-img features"></img>
						</div>
						<div class="col s6 m6 l6">
							<p class="blue-color-text-gear6 font-bold">Pick Up &amp; Dropoff</p>
							<p class="light-gray-color-text-gear6">You could be holidaying in a different city and it wouldn’t matter. We’ll pick up your bike, spruce it up and drop it right back.</p>
						</div>
					</div>
				</div>	
				<div class="col s12 m6 l4 add-margin-bottom">
					<div class="row">
						<div class="col s6 m6 l6 center">
							<img src="<?php echo site_url('nhome/images/icons/queries.png'); ?>" class="responsive-img features"></img>
						</div>
						<div class="col s6 m6 l6">
							<p class="blue-color-text-gear6 font-bold">Repairs &amp; Queries</p>
							<p class="light-gray-color-text-gear6">Punctures? Dents? Overheating? It doesn’t matter what it is. If your bike is down, we know how to fix it.</p>
						</div>
					</div>
				</div>
				<div class="col s12 m6 l4 add-margin-bottom">
					<div class="row">
						<div class="col s6 m6 l6 center">
							<img src="<?php echo site_url('nhome/images/icons/breakdown.png'); ?>" class="responsive-img features"></img>
						</div>
						<div class="col s6 m6 l6">
							<p class="blue-color-text-gear6 font-bold">Breakdown Services</p>
							<p class="light-gray-color-text-gear6">Anytime, anywhere, we’ll be there. You can count on us.</p>
						</div>
					</div>
				</div>
				<div class="col s12 m6 l4 add-margin-bottom">
					<div class="row">
						<div class="col s6 m6 l6 center">
							<img src="<?php echo site_url('nhome/images/icons/onlinepay.png'); ?>" class="responsive-img features"></img>
						</div>
						<div class="col s6 m6 l6">
							<p class="blue-color-text-gear6 font-bold">Pay Online</p>
							<p class="light-gray-color-text-gear6">Forget about fishing out the exact change. Go the cash-less way with our online payment options.</p>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="page-container light-background section-pad center mobile-hidden" id="whatuserssay">
			<br/>
			<h5 class="blue-color-text-gear6-heading">Customer Diaries...</h5>
			<h6 class="light-gray-color-text-gear6" style="width:80%;margin-left:10%;">Take a look at what customers have to say about us!</h6>
			<div class="row">
				<div class="slider">
					<ul class="slides">
						<li class="">
							<div class="col s12 m12 l4 center">
								<div class="row add-margin-top-testimonial">
									<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2 testimonial-container">
										<div class="testimonial-profile-pic-1"></div>
										<h5 style="margin-top:25%;" class="testimonial-large-text mobile-testimonial-margin">Amar Yadav<span class="testimonial-small-text">&nbsp;CustTap, Bangalore</span></h6>
										<i class="fa fa-quote-left"></i>  I had become too lazy to get my bike serviced, and had been putting it off it for 3 months. Around the same time, I stumbled upon gear6.in and found the whole experience so easy! I've become a loyal customer since then.<i class="fa fa-quote-right"></i>
									</div>
								</div>
							</div>
							<div class="col s12 m12 l4 center">
								<div class="row add-margin-top-testimonial">
									<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2 testimonial-container">
										<div class="testimonial-profile-pic-2"></div>
										<h5 style="margin-top:25%;" class="testimonial-large-text mobile-testimonial-margin">Shivarama Bhat<span class="testimonial-small-text">&nbsp;Bgt Rd,Bangalore</span></h6>
										<i class="fa fa-quote-left"></i> Really great service by gear6.in. The executives are very prompt and courteous. One of my friends referred gear6.in to me, but initially, I was a bit hesitant as I had never tried the service before. When I did try it, I loved it. I will be using gear6.in going forward, and will refer it to my friends too! <i class="fa fa-quote-right"></i>
									</div>
								</div>
							</div>
							<div class="col s12 m12 l4 center">
								<div class="row add-margin-top-testimonial">
									<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2 testimonial-container">
										<div class="testimonial-profile-pic-3"></div>
										<h5 style="margin-top:25%;" class="testimonial-large-text mobile-testimonial-margin">Vydehi Chinta<span class="testimonial-small-text">&nbsp;Oracle, Bangalore</span></h6>
										<i class="fa fa-quote-left"></i>  Insurance renewal done in half an hour! They picked my bike up from office and dropped it back, all in a matter of minutes. It saved my time and energy and I guess bike servicing hassles are a thing of the past now. Kudos to the gear6.in team. Thank you! :)  <i class="fa fa-quote-right"></i>
									</div>
								</div>
							</div>
						</li>
						<li>
							<div class="col s12 m12 l4 center">
								<div class="row add-margin-top-testimonial">
									<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2 testimonial-container">
										<div class="testimonial-profile-pic-2"></div>
										<h5 style="margin-top:25%;" class="testimonial-large-text mobile-testimonial-margin">Shivarama Bhat<span class="testimonial-small-text">&nbsp;Bgt Rd,Bangalore</span></h6>
										<i class="fa fa-quote-left"></i> Really great service by gear6.in. The executives are very prompt and courteous. One of my friends referred gear6.in to me, but initially, I was a bit hesitant as I had never tried the service before. When I did try it, I loved it. I will be using gear6.in going forward, and will refer it to my friends too! <i class="fa fa-quote-right"></i>
									</div>
								</div>
							</div>
							<div class="col s12 m12 l4 center">
								<div class="row add-margin-top-testimonial">
									<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2 testimonial-container">
										<div class="testimonial-profile-pic-3"></div>
										<h5 style="margin-top:25%;" class="testimonial-large-text mobile-testimonial-margin">Vydehi Chinta<span class="testimonial-small-text">&nbsp;Oracle, Bangalore</span></h6>
										<i class="fa fa-quote-left"></i>  Insurance renewal done in half an hour! They picked my bike up from office and dropped it back, all in a matter of minutes. It saved my time and energy and I guess bike servicing hassles are a thing of the past now. Kudos to the gear6.in team. Thank you! :)  <i class="fa fa-quote-right"></i>
									</div>
								</div>
							</div>
							<div class="col s12 m12 l4 center">
								<div class="row add-margin-top-testimonial">
									<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2 testimonial-container">
										<div class="testimonial-profile-pic-4"></div>
										<h5 style="margin-top:25%;" class="testimonial-large-text mobile-testimonial-margin">Gulmohar Khan<span class="testimonial-small-text">&nbsp;E-City, Bangalore</span></h6>
										<i class="fa fa-quote-left"></i>You can blindly trust these guys. They are super sincere and responsible, and there's no difference between what they say and what they deliver. Don't look anywhere else if you are looking for a hassle-free experience and complete peace of mind. gear6.in rocks!<i class="fa fa-quote-right"></i>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</section>
		<section class="section-pad" id="associations">
			<div class="center association-container">
				<br/>
				<h5 class="blue-color-text-gear6-heading">Our Service Partners</h5>
				<h6 class="light-gray-color-text-gear6" style="width:80%;margin-left:10%;">With our wide network of service partners, we'll ensure that your bike is serviced to perfection!</h6>
				<div class="row association-image">
					<div class="col s10 m10 l10 offset-s1 offset-m1 offset-l1 association-banner"></div>
				</div>
			</div>
			<div class="center">
				<br/>
				<h5 class="blue-color-text-gear6-heading">In the news...</h5>
				<div class="row">
					<div class="slider" id="inthenewsslider">
						<ul class="slides">
							<li class="">
								<div class="col s12 m12 l3 center">
									<div class="row add-margin-top-testimonial">
										<a target="_blank" href="http://yourstory.com/2016/04/gear6/">
											<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2">
												<img class="responsive-img" src="<?php echo site_url('img/icons/pr/ys.png'); ?>" style="width:50%!important;">
											</div>
										</a>
									</div>
								</div>
								<div class="col s12 m12 l3 center">
									<div class="row add-margin-top-testimonial">
										<a target="_blank" href="http://inc42.com/flash-feed/gear6-in-raises-seed-funding/">
											<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2">
												<img class="responsive-img" src="<?php echo site_url('img/icons/pr/inc42.png'); ?>" style="width:50%!important;">
											</div>
										</a>
									</div>
								</div>
								<div class="col s12 m12 l3 center">
									<div class="row add-margin-top-testimonial">
										<a target="_blank" href="http://www.nextbigwhat.com/bike-service-gear6-secures-500k-seed-funding-ninestarter-297/">
											<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2">
												<img class="responsive-img" src="<?php echo site_url('img/icons/pr/nbw.png'); ?>" style="width:50%!important;">
											</div>
										</a>
									</div>
								</div>
								<div class="col s12 m12 l3 center">
									<div class="row add-margin-top-testimonial">
										<a target="_blank" href="https://dl.dropboxusercontent.com/u/11354160/8cb21240-112c-4d83-9be1-42eb4dae620e.jpg">
											<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2">
												<img class="responsive-img" src="<?php echo site_url('img/icons/pr/toi.png'); ?>" style="width:50%!important;">
											</div>
										</a>
									</div>
								</div>
							</li>
							<li class="">
								<div class="col s12 m12 l3 center">
									<div class="row add-margin-top-testimonial">
										<a target="_blank" href="http://www.iamwire.com/2016/04/fundingwire-goldman-sachs-backs-persado-gear6-in-raises-500k-ninestarter/134267">
											<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2">
												<img class="responsive-img" src="<?php echo site_url('img/icons/pr/iaw.png'); ?>" style="width:50%!important;">
											</div>
										</a>
									</div>
								</div>
								<div class="col s12 m12 l3 center">
									<div class="row add-margin-top-testimonial">
										<a target="_blank" href="http://www.rednewswire.com/gear6-bangalore-based-online-bike-service-startup-raises-500k-seed-funding-ninestarter/">
											<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2">
												<img class="responsive-img" src="<?php echo site_url('img/icons/pr/rnw.png'); ?>" style="width:50%!important;">
											</div>
										</a>
									</div>
								</div>
								<div class="col s12 m12 l3 center">
									<div class="row add-margin-top-testimonial">
										<a target="_blank" href="http://knowstartup.com/2016/04/online-bike-service-platform-gear6-in-raises-seed-funding/">
											<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2">
												<img class="responsive-img" src="<?php echo site_url('img/icons/pr/ks.png'); ?>" style="width:50%!important;">
											</div>
										</a>
									</div>
								</div>
								<div class="col s12 m12 l3 center">
									<div class="row add-margin-top-testimonial">
										<a target="_blank" href="https://www.instagram.com/p/BDx5LdYy3oO/">
											<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2">
												<img class="responsive-img" src="<?php echo site_url('img/icons/pr/tfc.png'); ?>" style="width:50%!important;">
											</div>
										</a>
									</div>
								</div>
							</li>
							<li class="">
								<div class="col s12 m12 l3 center">
									<div class="row add-margin-top-testimonial">
										<a target="_blank" href="http://www.wikinewsindia.com/gossip/your-story/bits-pilani-alumnis-online-bike-service-platform-raises-500k-to-go-into-overdrive/">
											<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2">
												<img class="responsive-img" src="<?php echo site_url('img/icons/pr/wiki.png'); ?>" style="width:50%!important;">
											</div>
										</a>
									</div>
								</div>
								<div class="col s12 m12 l3 center">
									<div class="row add-margin-top-testimonial">
										<a target="_blank" href="http://www.indianweb2.com/2016/04/05/gear6-bangalore-based-online-bike-service-platform-raises-500k-seed-funding/">
											<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2">
												<img class="responsive-img" src="<?php echo site_url('img/icons/pr/iw2.png'); ?>" style="width:50%!important;">
											</div>
										</a>
									</div>
								</div>
								<div class="col s12 m12 l3 center">
									<div class="row add-margin-top-testimonial">
										<a target="_blank" href="http://www.mydreamway.in/gear6-in-bike-service-and-repairs-startup-raised-a-seed-funding/">
											<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2">
												<img class="responsive-img" src="<?php echo site_url('img/icons/pr/dw.png'); ?>" style="width:50%!important;">
											</div>
										</a>
									</div>
								</div>
								<div class="col s12 m12 l3 center">
									<div class="row add-margin-top-testimonial">
										<a target="_blank" href="https://citynewsforu.wordpress.com/2016/02/26/gearing-up-the-growth-story-of-gear6-in/">
											<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2">
												<img class="responsive-img" src="<?php echo site_url('img/icons/pr/cnfu.png'); ?>" style="width:50%!important;">
											</div>
										</a>
									</div>
								</div>
							</li>
							<li class="">
								<div class="col s12 m12 l3 center">
									<div class="row add-margin-top-testimonial">
										<a target="_blank" href="https://dl.dropboxusercontent.com/u/11354160/12049496_1132412193476009_2723639282808448521_n.jpg">
											<div class="col s8 offset-s2 m8 offset-m2 l8 offset-l2">
												<img class="responsive-img" src="<?php echo site_url('img/icons/pr/een.png'); ?>" style="width:50%!important;">
											</div>
										</a>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<br/>
		</section>
		<?php $this->load->view('user/components/_foot'); ?>
		<a id="scrollNext" style="position:fixed;bottom:20px;right:20px;" class="btn-floating btn-large waves-effect waves-light red laptop-hidden mobile-hidden" href="javascript:;"><i class="material-icons nav-arrws">keyboard_arrow_down</i></a>
		<a id="scrollPrev" style="position:fixed;bottom:80px;right:20px;" class="btn-floating btn-large waves-effect waves-light red laptop-hidden mobile-hidden" href="javascript:;"><i class="material-icons nav-arrws">keyboard_arrow_up</i></a>
	</main>
<script>
var univ_base_uri = "<?php echo base_url(); ?>";
</script>
<script type="text/javascript" src="<?php echo site_url('nhome/js/g6scripts.js?v=1.4'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('nhome/js/home.js?v=1.4'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/signup.js?v=1.4'); ?>"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCZ126reFV784ZQTqw_JfD08mnS0jI7nWo&libraries=places"></script>
<script>
<?php if(isset($city_row)) { ?>
	var swlati = <?php echo $city_row->SwLati; ?>;
	var swlongi = <?php echo $city_row->SwLongi; ?>;
	var nelati = <?php echo $city_row->NeLati; ?>;
	var nelongi = <?php echo $city_row->NeLongi; ?>;
<?php } ?>
var picker;
$(document).ready(function() {
	$(document).on('click', '#scrollNext', function () { scrollNext(); });
	$(document).on('click', '#scrollPrev', function () { scrollPrev(); });
	$(document).on('click', '#backButton', function () { backToBrands(); });
	$('#showReOffer').on('click', function() {
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
	<?php if(!isset($city_id)) { echo "openCityModal();"; } ?>
	<?php if(isset($is_first_login) && $is_first_login == 1) { echo "openFirstTimeLoginModal();"; } ?>
	<?php if(isset($ref_signup_flag)) { echo "openSignUpModal();"; } ?>
	<?php
		if(isset($open_blklogin_modal) && $open_blklogin_modal == 1) {
			echo "openBlkLoginModal();";
		} elseif(isset($open_login_modal) && $open_login_modal == 1) {
			echo "openLoginModal();";
		}
	?>
	<?php
		if(isset($open_login_modal) && $open_login_modal == 1)
		{
			echo "openLoginModal();";
		}
	?>
	$("input[type='checkbox'], input[type='radio']").not('.noticheck').icheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
	<?php if(isset($show_succ) && $show_succ == 1) { ?>
		sweetAlert("Success!", "We have received your request, our support team will contact you soon.", "success")
	<?php } ?>
	$('.carousel').carousel();
	$('.slider').slider({
		full_width: true
	});
	$(".dropdown-button").dropdown();
	$('.datepicker').pickadate({
		min: <?php if (isset($adv_time)) { echo '+' . $adv_time; } else { echo '0'; } ?>,
		max: 45,
		format: 'dddd, dd mmm, yyyy',
		closeOnSelect: true,
		container: 'body',
		onOpen: function() {
			$('.datepicker').val('');
		},
		onSet: function() {
			if($('.datepicker').val() != "" ) {
				$(this).close();
			}
		}
	}).attr("tabindex", "1");
	$('.button-collapse').sideNav({
		menuWidth: 300,
		edge: 'left',
		closeOnClick: false
	});
	$('#goTop').on('click', function() {
		$('html, body').animate({scrollTop: $('body').offset().top}, 500);
	});
});
</script>
</body>
</html>