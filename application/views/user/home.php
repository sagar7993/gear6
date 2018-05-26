<html lang="en" class="no-scroll">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="Keywords" content="bike servicing bangalore, bike service bangalore, book bike servicing slots, book bike service slots, online bike service bangalore, bike servicing online, bike service online, book bike service online, bike maintenance, bike maintenance online bangalore, bike insurance renewal, bike insurance renewal online, renew bike insurance online, online bike repairs" />
	<meta name="Description" content="gear6.in brings all service centers(authorized) under a virtual roof, enabling customers to pick from the best service centers. The idea is to reduce the waiting time in the service centers and provide a hassle free service booking, management and maintenance of bikes for people." />
	<meta name="author" content="gear6.in">
	<meta name="title" content="gear6.in - On Demand Bike Services">
	<meta property="og:title" content="gear6.in - On Demand Bike Services" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="https://www.gear6.in/img/services_web.png" />
	<meta property="og:url" content="https://www.gear6.in" />
	<meta property="og:description" content="gear6.in brings all service centers(authorized) under a virtual roof, enabling customers to pick from the best service centers. The idea is to reduce the waiting time in the service centers and provide a hassle free service booking, management and maintenance of bikes for people." />
	<meta property="og:site_name" content="gear6.in" />
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Online Bike Service and Repair, Pickup and Drop for Bikes, Bike Insurance Renewal | Bangalore</title>
	<?php $this->load->view('user/components/_ucss'); ?>
</head>
<body>
	<?php $this->load->view('user/components/_head'); ?>
	<!-- main content starts -->
	<main class="main">
		<!-- search box starts -->
		<section class="parallax-container">
			<div class="logo-header z-depth-1">
				<div class="logo-box ">
					<img src="<?php echo site_url('img/mr_logo.png'); ?>" align="middle" class="responsive-img" />
				</div>
			</div>
			<div class="container">
				<div class="content-header">
					<div class="header-text container">
						<h2 class="flow-text">Bike Maintenance Made Easy<br/> </h2>
						<h2 class="header-small">
							<small>
								<span class="caption-header flow-text">..&nbsp;Your Bike is Precious. Your Time is Priceless&nbsp;..</span>
							</small>
						</h2>
					</div>
				</div> 
				<div class="select-box center">
					<div class="boxShadow">
						<!-- form start -->
						<form role="form" action ="/user/book/" method="POST">
							<div class="row no-row margin-top-15px">
								<div class="col s12 m6" >
									<input type="text" tab-index="1" data-error="Location" class="form-control area" title="Enter your Area..." name="area" id="area" placeholder="Your area eg. Domlur">
								</div>
								<div class="col s12 m6 sc-hme" >
									<select class="form-control styled-select" data-error="Service" tab-index="2"  name="servicetype" id="stype" title="Choose your Service Type">
										<option></option>
										<?php
											foreach ($services as $service) {
												echo '<option value="'.$service->ServiceId.'">'.$service->ServiceName.'</option>';
											}
										?>
									</select>
								</div>
							</div>
							<div class="row lowerSection">
								<div class="col s12 m4">
									<input type="text" tab-index="3" data-error="Date" title="Select the Date of Service"  id="datepicker" class="form-control dpDate" name="date_" readonly='true' placeholder="Service Date">
									<input type="hidden" id="datepicker_query" class="form-control dpDate" name="date_query">
								</div>
								<div class="col s12 m4">
									<select class="form-control styled-select" tab-index="4" data-error="Company" title="Select your Bike company" id="company" name="company" onchange="bikelist(this.value);">
										<option></option>
										<?php
											foreach ($bikecompanies as $bikecompany) {
												echo '<option value="'.$bikecompany->BikeCompanyId.'">'.convert_to_camel_case($bikecompany->BikeCompanyName).'</option>';
											}
										?>
									</select>
								</div>
								<div class="col s12 m4" >
									<select class="form-control styled-select" data-error="Model" tab-index="5" title="Select your Bike's Model" id="bikediv"  name="model">
										<option></option>
									</select>
								</div>
							</div>
						<!-- /.box -->
						</div>
						<div class="bkService">
							<input type="hidden" id="ulatitude" name="qlati">
							<input type="hidden" id="ulongitude" name="qlongi">
							<button type="submit" name="book" id="submit" tab-index="6" class="z-depth-1 btn btn-primary waves-effect waves-light" title="Click to proceed">
								Book&nbsp;a&nbsp;Service
							</button>
						</div>
					</form>
				</div>
			</div>
		</section>
		<!-- search box ends -->
		<!-- Content Details Starts -->
		<section class="contentDetails container col m12 hide-on-small-only">
			<div class="row"><!-- Media -->
				<div id="" class="content-list">
					<a class="content-list-item left col m4" id="feature-1">
					<i class="large material-icons">alarm_on</i>
					</a>
					<a class="content-list-item left col m4" id="feature-2">
					<i class="large material-icons">track_changes</i>
					</a>
					<a class="content-list-item left col m4" id="feature-3">
					<i class="large material-icons">home</i>
					</a>
				</div>
			</div>
		</section>
		<div class="clearfix"></div>
		<section class="content-strip1 hide-on-small-only">
			<div class="strip-content1 container center-align fadeInBlock" style="">
				<div class="hover-container row">
					<div class="hover-block-1 center-align" id="hover-block-1" style="display:block">
						<div class="hover-text-block">
							<div class="hover-text-title">
								Guaranteed Delivery On Time
							</div>
							<div class="hover-text-main">
								Book your desired time slot and collect your bike by the start of next time slot for any of your free or paid periodic servicing.
							</div>
						</div>
					</div>
					<div class="hover-block-1 center-align" id="hover-block-2">
						<div class="hover-text-block">
							<div class="hover-text-title">
								Real Time Tracking
							</div>
							<div class="hover-text-main">
								You can track your service status in your order profile anytime, it will be updated in real-time to let you know the status,extra repairs/charges.You will receive SMS and e-mail notifications as well.
							</div>
						</div>
					</div>
					<div class="hover-block-1 center-align" id="hover-block-3">
						<div class="hover-text-block">
							<div class="hover-text-title">
								Pick Up and Drop Off
							</div>
							<div class="hover-text-main">
								You can opt for pick up or drop off at the available time slot. Your bike's pick up and drop off are done safely by our professional bikers. 
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="contentDetails container col m12 hide-on-small-only">
			<div class="row"><!-- Media -->
				<div id="" class="content-list">
					<a class="content-list-item left col m4" id="feature-1">
					<i class="large material-icons">build</i>
					</a>
					<a class="content-list-item left col m4" id="feature-2">
					<i class="large material-icons">local_shipping</i>
					</a>
					<a class="content-list-item left col m4" id="feature-3">
					<i class="large material-icons">account_balance_wallet</i>
					</a>
				</div>
			</div>
		</section>
		<div class="clearfix"></div>
		<section class="content-strip1 hide-on-small-only">
			<div class="strip-content1 container center-align fadeInBlock" style="-webkit-transition-delay: 100ms;transition-delay: 100ms;">
				<div class="hover-container row">
					
					<div class="hover-block-1 center-align" id="hover-block-2">
						<div class="hover-text-block">
							<div class="hover-text-title">
								Repair &amp; Query
							</div>
							<div class="hover-text-main">
								Your addressed repairs will be fixed by forwarding to desired service center Cost of service, nature of repair, spares etc will be replied by service center executives to you on query.
							</div>
						</div>
					</div>
					<div class="hover-block-1 center-align" id="hover-block-3">
						<div class="hover-text-block">
							<div class="hover-text-title">
								Accidental &amp; Emergency
							</div>
							<div class="hover-text-main">
								Flat tyre? Gears stuck? Broken chain? Empty fuel? Be it a petty issue. No panic .We guide to the nearest puncture centers and petrol bunk.
							</div>
						</div>
					</div>
					<div class="hover-block-1 center-align" id="hover-block-1" style="display:block">
						<div class="hover-text-block">
							<div class="hover-text-title">
								Pay Online
							</div>
							<div class="hover-text-main">
								You can pay online for all types of servicing,repairs and insurance renewal. You can pay any other charges updated by vendor after receiving your bike as well online.
							</div>
						</div>
					</div> 
				</div>
			</div>
		</section>
		<div class="fixed-action-btn hide-on-med-and-down" id="#mob_fixed" style="bottom: 25px; right: 130px;">
			<a class="btn-floating btn-large teal" href="#offers_images">
				<i class="large material-icons">redeem</i>
			</a>
			<div style="margin-top: 10px;font-weight: bold;text-align: center;">OFFERS</div>
			<ul style="left:-80px;">
				<li><a class="btn-floating " style="background:#ff8300;width:260px;border-radius:0;"><b class="">Flat Rs.100 OFF - Code: GSIX100</b></a></li>
			</ul>
		</div>
		<div class="col-xs-12 go-top waves-effect waves-light z-depth-2" id="goTop">
			<span></span>
			<i></i>
		</div>
		<section class="content-strip2">
			<!-- <div class="strip-content centerM">
				<div class="video-box">
					<video id="landing_video" class="video-js vjs-default-skin" controls preload="none" width="100%" height="350" poster="<?php echo site_url('img/mrbg.jpg'); ?>" data-setup='{"customControlsOnMobile": true}'>
						<source src="<?php echo site_url('video/merc.mp4#t=,54'); ?>" type='video/mp4' />
						<source src="<?php echo site_url('video/merc.webm#t=,54'); ?>" type='video/webm' />
					</video>
				</div>
			</div> -->
			<div class="row center margin-auto" id="offers_images">
				<div class="col s12 center margin-top-22px">
					<img src="<?php echo site_url('img/services_web.png'); ?>" alt="Offers" class="responsive-img z-depth-2 materialboxed">
				</div>
				<div class="col s12 center margin-top-22px" id="flow_web">
					<img src="<?php echo site_url('img/order-flow-g6.png'); ?>" alt="Offers" class="responsive-img z-depth-2 materialboxed">
				</div>
			</div>
			<div class="center blog-container hide-on-small-only">
				<div class="row"> 
					<div class="col m3 margin-top-22px">
						<img src="<?php echo site_url('img/blog.png'); ?>" alt="" class="circle responsive-img z-depth-2">
					</div>
					<div class="col m9">
						<div class="col m12 blog-block">
							<h3 class="mr-title">Here is our blog !!!</h3>
						</div>
						<div class="col m12 blog-block1 mr-tag-content">
							We assist you in maintaining the bike by providing riding, fuel, traffic tips in an exciting visual way. This is for all the bike riders out there to have a joyous ride on the road ahead.
							Get updated with the latest news about bike maintenance and trending topics.	</div>
						<div class="blog-block1 read-more">
							<a href="http://blog.gear6.in" target="_blank">Read More...</a>
						</div>
					</div>
				</div>
			</div>
			<div class="slider hide-on-med-and-down">
				<ul class="slides">
					<li>
					<div class="caption left-align row">
						<div class="col s9">
							<h4 class="left">Amar Yadav</h4><span class="left testi-job margin-top-30">&nbsp;CustTap, Bangalore</span>
							<h5 class="light grey-text text-lighten-3 slider-slogan clearfix">
								<i class="fa fa-quote-left"></i>
								Was too lazy to get my bike serviced and was delaying it for last 3 months and 
								then somehow stumbled across you guys...finally servicing done...great job guys...you have earned one more happy customer !!
								<i class="fa fa-quote-right"></i>
							</h5>
						</div>
						<div class="slider-img col s3">
							<img src="<?php echo site_url('img/cf2.jpg'); ?>" class="circle responsive-img z-depth-2">
						</div>
					</div>
					</li>
					<li>
					<div class="caption left-align row">
						<div class="col s9">
							<h4 class="left">Shivarama Bhat</h4><span class="left testi-job margin-top-30">&nbsp;Bannerghatta Rd,Bangalore</span>
							<h5 class="clearfix light grey-text text-lighten-3 slider-slogan">
								<i class="fa fa-quote-left"></i>
								Really great service. Very prompt and courteous people. 
								One of my friend referred me to Gear6.
								Was a bit hesitant in the morning as I was using your service for the first time. 
								But going forward will always use and refer the service provided by you people. Thank You :)
								<i class="fa fa-quote-right"></i>
							</h5>
						</div>
						<div class="slider-img col s3">
							<img src="<?php echo site_url('img/cf4.jpg'); ?>" class="circle responsive-img z-depth-2">
						</div>
					</div>
					</li>
					<li>
					<div class="caption left-align row">
						<div class="col s9">
							<h4 class="left">Vydehi Chinta</h4><span class="left testi-job margin-top-30">&nbsp;Oracle, Bangalore</span>
							<h5 class="clearfix light grey-text text-lighten-3 slider-slogan">
								<i class="fa fa-quote-left"></i>
								Insurance Renewal done in half an hour. 
								They have done pick up &amp; drop off to my office directly. 
								Saved my time and energy. I guess there won't be any hassles for bike services anymore. Kudos to the team.
								Thank You :)
								<i class="fa fa-quote-right"></i>
							</h5>
						</div>
						<div class="slider-img col s3">
							<img src="<?php echo site_url('img/cf1.jpg'); ?>" class="circle responsive-img z-depth-2">
						</div>
					</div>
					</li>
					<li>
					<div class="caption left-align row">
						<div class="col s9">
							<h4 class="left">Gulmohar Khan</h4><span class="left testi-job margin-top-30">&nbsp;E-City, Bangalore</span>
							<h5 class="clearfix light grey-text text-lighten-3 slider-slogan">
								<i class="fa fa-quote-left"></i>
								You can blindly trust these guys... 
								They are super sincere and responsible.... No difference between what they say and what they deliver... 
								Don't look anywhere if you are looking for a hasslefree and peace of mind service... you guys rock...!!
								<i class="fa fa-quote-right"></i>
							</h5>
						</div>
						<div class="slider-img col s3">
							<img src="<?php echo site_url('img/cf3.jpg'); ?>" class="circle responsive-img z-depth-2">
						</div>
					</div>
					</li>
				</ul>
			</div>
		</section>
		<div class="clearfix"></div>
<!-- 		<div style="text-align:center;">Total Site Visitors :&nbsp;<script type="text/javascript" src="http://services.webestools.com/cpt_visitors/32473-8-5.js"></script></div>
 -->	</main>
	<!-- main content ends -->
	<?php $this->load->view('user/components/_foot'); ?>
	<?php $this->load->view('user/components/_ujs'); ?>
	<script type="text/javascript" src="<?php echo site_url('js/video.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('js/home.js'); ?>"></script>
	<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCZ126reFV784ZQTqw_JfD08mnS0jI7nWo&libraries=places"></script>
	<script>
		<?php if(isset($city_row)) { ?>
			var swlati = <?php echo $city_row->SwLati; ?>;
			var swlongi = <?php echo $city_row->SwLongi; ?>;
			var nelati = <?php echo $city_row->NeLati; ?>;
			var nelongi = <?php echo $city_row->NeLongi; ?>;
		<?php } ?>
		$(function() {
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
			$('.parallax').parallax();
			$(window).scroll(function() {
				checkFLoatDiv();
			});
			$('.slider').slider();
			$(".button-collapse").sideNav();
			$('#city').select2({
				placeholder: "Select Your City",
				minimumResultsForSearch: 10,
				containerCssClass: "cityCombo"
			});
			$('#datepicker').pickadate({
				min: <?php if (isset($adv_time)) { echo '+' . $adv_time; } else { echo '0'; } ?>,
				max: 45,
				format: 'dddd, dd mmm, yyyy',
				formatSubmit: 'dddd, dd mmm, yyyy',
				closeOnSelect: true,
				container: 'body',
				onOpen: function() {
					$('#datepicker').val('');
				},
				onSet: function() {
					if($('#datepicker').val() != "" ) {
						$(this).close();
					}
				}
			}).attr("tabindex", "1");
			$('#closeMobApp').click(function() {
				$('#mobApp').hide();
			});
		});
	</script>
</body>
</html>