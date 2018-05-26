<!DOCTYPE html>
<html class="no-scroll">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - User Bike profile (<?php echo convert_to_camel_case($this->session->userdata('name')); ?>)</title>
	<link rel="shortcut icon" href="<?php echo site_url('img/icons/favicon.png'); ?>" type="image/ico">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('fonts/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/materialize.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/style.css?v=1.0'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/ionicons.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/module.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/custom-jquery.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/jQueryUI/jquery-ui.css'); ?>">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/ustyle.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('css/flatui.css'); ?>">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Raleway"  type="text/css">
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
	<div class="load-wrap">
		<div class="preloader-wrapper big active center loader1">
			<div class="spinner-layer spinner-green-only">
				<div class="circle-clipper left">
					<div class="circle"></div>
				</div>
				<div class="gap-patch">
					<div class="circle"></div>
				</div>
				<div class="circle-clipper right">
					<div class="circle"></div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('user/components/__head'); ?>
	<?php $this->load->view('user/components/_sidebar'); ?>
	<aside class="right-side1">
		<section class="user-header-container">
			<div class="uhBox">
				<div class="uhNL">
					<div class="uhName">
						<i class="material-icons left">account_circle</i>
						<span class="user-profile-name"><?php echo convert_to_camel_case($this->session->userdata('name')); ?></span>
					</div>
					<?php if($user_addresses[0]['AddrLine1'] != "") { ?>
					<div class="uhLoc clearfix">
						<i class="material-icons">pin_drop</i>
						<span><?php echo convert_to_camel_case($user_addresses[0]['AddrLine1']) . " , " . convert_to_camel_case($user_addresses[0]['LocationName']); ?></span>
					</div>
					<?php } ?>
				</div>
				<div class="uhPE">
					<div class="uhPhone">
						<i class="material-icons">phonelink_ring</i>
						<span>&nbsp;<?php echo $user_addresses[0]['Phone']; ?></span>
					</div>
					<div class="uhPhone">
						<i class="material-icons">email</i>
						<span>&nbsp;<?php echo $user_addresses[0]['Email']; ?></span>
					</div>
				</div>
				<div class="uhEB">
					<div class="uhEd">
						<div class="uhEdit">
							<i class="material-icons">border_color</i>
							<span><a href="<?php echo base_url('user/account/uprofile'); ?>" class="white-font">Edit</a></span>
						</div>
						<div class="uhEdit">
							<i class="material-icons">loupe</i>
							<span><a class="white-font" href="<?php echo site_url('user/userhome'); ?>">Book New</a></span>
						</div>
					</div>
				</div>
				<div></div>
				<div></div>
				<div></div>
			</div>
		</section>
		<div class="bc">
			<div class="bcItem"><a href="<?php echo site_url('user/userhome'); ?>">Home</a></div>
			<span class="arrw">>></span>
			<div class="bcItem active-bc">Bike Profile</div>
		</div>
		<div class="user-order-block">
			<?php if(isset($reg_nums) && count($reg_nums) > 0) { foreach($reg_nums as $number => $reg_num) { ?>
				<ul class="collapsible" data-collapsible="accordion">
					<li>
					  	<div class="collapsible-header active"><i class="material-icons">directions_bike</i><b><?php echo $number; ?></b></div>
					  	<div class="collapsible-body padding-10px">
							<div class="service-container">
								<ul class="collapsible" data-collapsible="accordion">
									<li>
									  	<div class="collapsible-header"><i class="material-icons">history</i><b>Bike History</b></div>
									  	<div class="collapsible-body padding-10px">
											<div class="service-container">
												<?php if(isset($reg_num) && count($reg_num) > 0) { foreach($reg_num as $serviceName => $service) { ?>
													<?php
														if($serviceName == 'Repair') { $icon = 'build'; } elseif($serviceName == 'Periodic Servicing') { $icon = 'alarm'; } elseif($serviceName == 'Insurance Renewal') { $icon = 'autorenew'; } elseif($serviceName == 'Emission Test') { $icon = 'perm_data_setting'; } else { $icon = 'motorcycle'; }
													?>
													<ul class="collapsible" data-collapsible="accordion" style="margin:0px 0px 0px 0px;">
														<li>
														  	<div class="collapsible-header"><i class="material-icons"><?php echo $icon; ?></i><b><?php echo $serviceName; ?></b></div>
														  	<div class="collapsible-body padding-10px">
																<div class="service-container">
																	<div class="row padding-10px">
																		<div class="col s12 m3 center-align">
																			<label>Service Center</label>
																			<?php if(isset($service) && count($service) > 0) { foreach($service as $order) { ?>	
																				<div class="margin-top-10px"><?php echo $order['ScName']; ?></div>
																			<?php } } ?>
																		</div>
																		<div class="col s12 m3 center-align">
																			<label>Date</label>
																			<?php if(isset($service) && count($service) > 0) { foreach($service as $order) { ?>	
																				<div class="margin-top-10px"><?php echo $order['ODate']; ?></div>
																			<?php } } ?>
																		</div>
																		<div class="col s12 m3 center-align">
																			<label>Total Price</label>
																			<?php if(isset($service) && count($service) > 0) { foreach($service as $order) { ?>	
																				<div class="margin-top-10px"><?php echo $order['FinalPrice']; ?> INR</div>
																			<?php } } ?>
																		</div>
																		<div class="col s12 m3 center-align">
																			<label>Order ID</label>
																			<?php if(isset($service) && count($service) > 0) { foreach($service as $order) { ?>	
																				<div class="pointer margin-top-10px"><a class="green-text"><?php echo $order['OId']; ?></a></div>
																			<?php } } ?>
																		</div> 
																	</div>
																</div>
															</div>
														</li>
													</ul>
												<?php } } ?>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>
						<?php if(isset($reg_num) && count($reg_num) > 0) { foreach($reg_num as $serviceName => $service) { if($serviceName  != 'Query') { ?>
							<?php if(($serviceName == 'Periodic Servicing' || $serviceName == 'Repair') && isset($service[0]['service_reminder_date'])) { ?>
								<div class="collapsible-body padding-10px">
							  		<div class="service-container">
										<h6 class="font-bold"><?php echo $serviceName; ?></h6>
										<div class="row">
											<div class="col s12 m3 center-align">
												<label>Last Serviced</label>
												<div><?php echo $service[0]['ODate']; ?></div>
											</div>
											<div class="col s12 m3 center-align">
												<label>Next Servicing Date</label>
												<div><?php echo $service[0]['service_reminder_date']; ?></div>
											</div>
											<?php
												$date1=date_create("now");
												$date2=date_create($service[0]['service_reminder_date']);
												$date3=date_create($service[0]['ODate']);
												$now_diff=date_diff($date1,$date2);
												$now_diff = intval($now_diff->format("%a"));
												$order_diff=date_diff($date3,$date2);
												$order_diff = intval($order_diff->format("%a"));
												$remaining_days = intval($order_diff - $now_diff);
												$percentage = intval(($remaining_days / $order_diff) * 100);
											?>
											<div class="col s12 m3 center-align">
												<label>Due In</label>
												<div><?php if($now_diff > 0) { echo $now_diff . ' Days'; } else { echo $now_diff . ' Days Ago'; } ?></div>
											</div>
											<div class="col s12 m3 center-align">
												<label>Action</label>
												<div class="pointer"><a class="green-text" href="/">Book Now</a></div>
											</div>
											<div class="progress-container padding-20px col s12">
												<label>0 Days</label>
												<label class="right"><?php echo $order_diff; ?> Days</label>
												<div class="progress mprogress">
												  <div class="progress-bar mprogress-bar mprogress-red" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $percentage; ?>%">
												  </div>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
							<?php if($serviceName == 'Insurance Renewal' && isset($service[0]['insurance_renewal_date'])) { ?>
								<div class="collapsible-body padding-10px">
							  		<div class="service-container">
										<h6 class="font-bold"><?php echo $serviceName; ?></h6>
										<div class="row">
											<div class="col s12 m3 center-align">
												<label>Last Serviced</label>
												<div><?php echo $service[0]['ODate']; ?></div>
											</div>
											<div class="col s12 m3 center-align">
												<label>Next Servicing Date</label>
												<div><?php echo $service[0]['insurance_renewal_date']; ?></div>
											</div>
											<?php
												$date1=date_create("now");
												$date2=date_create($service[0]['insurance_renewal_date']);
												$date3=date_create($service[0]['ODate']);
												$now_diff=date_diff($date1,$date2);
												$now_diff = intval($now_diff->format("%a"));
												$order_diff=date_diff($date3,$date2);
												$order_diff = intval($order_diff->format("%a"));
												$remaining_days = intval($order_diff - $now_diff);
												$percentage = intval(($remaining_days / $order_diff) * 100);
											?>
											<div class="col s12 m3 center-align">
												<label>Due In</label>
												<div><?php if($now_diff > 0) { echo $now_diff . ' Days'; } else { echo $now_diff . ' Days Ago'; } ?></div>
											</div>
											<div class="col s12 m3 center-align">
												<label>Action</label>
												<div class="pointer"><a class="green-text" href="/">Book Now</a></div>
											</div>
											<div class="progress-container padding-20px col s12">
												<label>0 Days</label>
												<label class="right"><?php echo $order_diff; ?> Days</label>
												<div class="progress mprogress">
												  <div class="progress-bar mprogress-bar mprogress-blue" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $percentage; ?>%">
												  </div>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
							<?php if($serviceName == 'PUC (Emission Control)' && isset($service[0]['puc_renewal_date'])) { ?>
								<div class="collapsible-body padding-10px">
							  		<div class="service-container">
										<h6 class="font-bold"><?php echo $serviceName; ?></h6>
										<div class="row">
											<div class="col s12 m3 center-align">
												<label>Last Serviced</label>
												<div><?php echo $service[0]['ODate']; ?></div>
											</div>
											<div class="col s12 m3 center-align">
												<label>Next Servicing Date</label>
												<div><?php echo $service[0]['puc_renewal_date']; ?></div>
											</div>
											<?php
												$date1=date_create("now");
												$date2=date_create($service[0]['puc_renewal_date']);
												$date3=date_create($service[0]['ODate']);
												$now_diff=date_diff($date1,$date2);
												$now_diff = intval($now_diff->format("%a"));
												$order_diff=date_diff($date3,$date2);
												$order_diff = intval($order_diff->format("%a"));
												$remaining_days = intval($order_diff - $now_diff);
												$percentage = intval(($remaining_days / $order_diff) * 100);
											?>
											<div class="col s12 m3 center-align">
												<label>Due In</label>
												<div><?php if($now_diff > 0) { echo $now_diff . ' Days'; } else { echo $now_diff . ' Days Ago'; } ?></div>
											</div>
											<div class="col s12 m3 center-align">
												<label>Action</label>
												<div class="pointer"><a class="green-text" href="/">Book Now</a></div>
											</div>
											<div class="progress-container padding-20px col s12">
												<label>0 Days</label>
												<label class="right"><?php echo $order_diff; ?> Days</label>
												<div class="progress mprogress">
												  <div class="progress-bar mprogress-bar mprogress-blue" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $percentage; ?>%">
												  </div>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						<?php } } } ?>
					</li>
				</ul>
			<?php } } ?>
		</div>
	</aside>
	<?php $this->load->view('user/components/_foot'); ?>
<script type="text/javascript" src="<?php echo site_url('js/jquery-2.1.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/raty/jquery.raty.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/materialize.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/account.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/feedback.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/signup.js?v=1.0'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('js/jquery.knob.js'); ?>"></script>
<script>
$(function() {
	<?php if(!$this->input->cookie('CityId')) { echo "openCityModal();"; } ?>
	<?php if(isset($is_first_login) && $is_first_login == 1) { echo "openFirstTimeLoginModal();"; } ?>
	$(window).load(function(){$('.load-wrap').hide();$('html').removeClass('no-scroll');});
	if($('.knob').length > 0) {
		$(".knob").knob({
			draw: function() {
				if (this.$.data('skin') == 'tron') {
					var a = this.angle(this.cv)				// Angle
							, sa = this.startAngle			// Previous start angle
							, sat = this.startAngle			// Start angle
							, ea 							// Previous end angle
							, eat = sat + a 				// End angle
							, r = true;
					this.g.lineWidth = this.lineWidth;
					this.o.cursor
							&& (sat = eat - 0.3)
							&& (eat = eat + 0.3);
					if (this.o.displayPrevious) {
						ea = this.startAngle + this.angle(this.value);
						this.o.cursor
								&& (sa = ea - 0.3)
								&& (ea = ea + 0.3);
						this.g.beginPath();
						this.g.strokeStyle = this.previousColor;
						this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
						this.g.stroke();
					}
					this.g.beginPath();
					this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
					this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
					this.g.stroke();
					this.g.lineWidth = 2;
					this.g.beginPath();
					this.g.strokeStyle = this.o.fgColor;
					this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
					this.g.stroke();
					return false;
				}
			}
		});
	}
});
</script>
</body>
</html>