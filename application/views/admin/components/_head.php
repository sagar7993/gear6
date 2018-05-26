<?php if(!isset($a_is_logged_in) || (isset($a_is_logged_in) && $a_is_logged_in == 0)) { ?>
<!-- Login modal starts -->
<div id="login_container_section" class="login_container_section">
	<div class="cont<?php if(isset($open_login_modal) && $open_login_modal == 1) { echo ' show'; } ?>" id="login_modal">
		<div class="demo">
	    	<div class="login">
		      	<div class="login__check"><img src="/img/social_logo.png"></img></div>
		      	<div class="login__form">
		      		<form role="form" method="POST">
				        <div class="login__row">
				        	<svg class="svg login__icon name svg-icon" viewBox="0 0 20 20">
				            	<path d="M0,20 a10,8 0 0,1 20,0z M10,0 a4,4 0 0,1 0,8 a4,4 0 0,1 0,-8" />
				          	</svg>
				          	<input type="text" class="login__input name" id="phone" name="phone" placeholder="Enter Phone Number"/>
				        </div>
				        <div class="login__row">
				        	<svg class="svg login__icon pass svg-icon" viewBox="0 0 20 20">
				            	<path d="M0,20 20,20 20,8 0,8z M10,13 10,16z M4,8 a6,8 0 0,1 12,0" />
				          	</svg>
				          	<input type="password" class="login__input pass" id="password" name="password" placeholder="Enter Password"/>
				        </div>
				        <button type="submit" class="login__submit" name="login" id="login">Sign in</button>
			       	</form>
		      	</div>
	    	</div>
	  	</div>
	</div>
</div>
<?php } ?>
<!-- Login modal ends-->
<!-- header starts -->
<?php if(isset($a_is_logged_in) && $a_is_logged_in == 1) { ?>
<header class="header">
	<nav class="navbar navbar-static-top " role="navigation">
		<div class="navbar-left header1-nav-left">
			<ul class="nav navbar-nav">
				<li class="dropdown user user-menu header1-nav-logo">
					<a href="/admin/" class="dropdown-toggle left-pad logo-margin-top">
						<img src="<?php echo site_url('img/mr_logo.png'); ?>" class="logo-block logo-block-header1">
					</a>
				</li>
			</ul>
		</div>
		<div class="navbar-right">
			<ul class="nav navbar-nav">
				<li class="dropdown notifications-menu">
					<a href="#" class="dropdown-toggle  font-white ver-top" data-toggle="dropdown">
						<i class="material-icons">feedback</i>
						<span class="label label-warning" id="adminNotificationNewFeedbackCounter"></span>
					</a>
					<ul class="dropdown-menu" id="adminNotificationNewFeedback">
					</ul>
				</li>
				<li class="dropdown notifications-menu">
					<a href="#" class="dropdown-toggle  font-white ver-top" data-toggle="dropdown">
						<i class="material-icons">textsms</i>
						<span class="label label-warning" id="adminNotificationNewUserContactUsCounter"></span>
					</a>
					<ul class="dropdown-menu" id="adminNotificationNewUserContactUs">
					</ul>
				</li>
				<li class="dropdown notifications-menu">
					<a href="#" class="dropdown-toggle  font-white ver-top" data-toggle="dropdown">
						<i class="material-icons">perm_contact_calendar</i>
						<span class="label label-warning" id="adminNotificationNewAgentContactUsCounter"></span>
					</a>
					<ul class="dropdown-menu" id="adminNotificationNewAgentContactUs">
					</ul>
				</li>
				<li class="dropdown notifications-menu">
					<a href="#" class="dropdown-toggle  font-white ver-top" data-toggle="dropdown">
						<i class="material-icons">loyalty</i>
						<span class="label label-danger" id="adminNotificationNewOrderCounter"></span>
					</a>
					<ul class="dropdown-menu" id="adminNotificationNewOrder">
					</ul>
				</li>
				<li class="dropdown notifications-menu">
					<a href="#" class="dropdown-toggle  font-white ver-top" data-toggle="dropdown">
						<i class="material-icons">watch_later</i>
						<span class="label label-danger" id="adminNotificationNewDelayedOrderCounter"></span>
					</a>
					<ul class="dropdown-menu" id="adminNotificationNewDelayedOrder">
					</ul>
				</li>
				<li class="dropdown notifications-menu">
					<a href="#" class="dropdown-toggle  font-white ver-top" data-toggle="dropdown">
						<i class="material-icons">warning</i>
						<span class="label label-danger" id="adminNotificationNewEmergencyOrderCounter"></span>
					</a>
					<ul class="dropdown-menu" id="adminNotificationNewEmergencyOrder">
					</ul>
				</li>
				<li class="dropdown notifications-menu">
					<a href="#" class="dropdown-toggle  font-white ver-top" data-toggle="dropdown">
						<i class="material-icons">album</i>
						<span class="label label-danger" id="adminNotificationNewPunctureOrderCounter"></span>
					</a>
					<ul class="dropdown-menu" id="adminNotificationNewPunctureOrder">
					</ul>
				</li>
				<li class="dropdown notifications-menu">
					<a href="#" class="dropdown-toggle  font-white ver-top" data-toggle="dropdown">
						<i class="material-icons">monetization_on</i>
						<span class="label label-danger" id="adminNotificationNewPaymentCounter"></span>
					</a>
					<ul class="dropdown-menu" id="adminNotificationNewPayment">
					</ul>
				</li>
				<li class="dropdown notifications-menu">
					<a href="#" class="dropdown-toggle  font-white ver-top" data-toggle="dropdown">
						<i class="material-icons">motorcycle</i>
						<span class="label label-danger" id="adminNotificationNewPickupCounter"></span>
					</a>
					<ul class="dropdown-menu" id="adminNotificationNewPickup">
					</ul>
				</li>
				<li class="dropdown notifications-menu">
					<a href="#" class="dropdown-toggle  font-white ver-top" data-toggle="dropdown">
						<i class="material-icons">update</i>
						<span class="label label-danger" id="adminNotificationNewRenewalCounter"></span>
					</a>
					<ul class="dropdown-menu" id="adminNotificationNewRenewal">
					</ul>
				</li>
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle  font-white ver-top" data-toggle="dropdown">
						<i class="glyphicon glyphicon-user"></i>
						<span><?php echo convert_to_camel_case($this->session->userdata('a_name')); ?><i class="caret"></i></span>
					</a>
					<ul class="dropdown-menu">
						<li class="user-header bg-light-blue">
							<img src="https://www.placehold.it/100x100" class="img-circle" alt="User Image"></img>
							<p>
								<?php echo convert_to_camel_case($this->session->userdata('a_role')); ?> - <?php if(isset($site_name)) { echo $site_name; } ?>
								<small><?php echo convert_to_camel_case($this->session->userdata('a_city')); ?></small>
							</p>
						</li>
						<li class="user-footer">
							<div class="pull-left">
								<?php if(isset($page) && isset($sadmin_city_id) && $page == 'admin' && intval($sadmin_city_id) < 0) { ?>
									<a class='dropdown-button btn btn-default btn-flat' href='#' data-activates='select-city-dropdown'>Select City</a>
									<ul id='select-city-dropdown' class='dropdown-content'>
									<?php if(isset($cities)) { foreach($cities as $city) { ?>
									    <li><a id="city_<?php echo $city['CityId']; ?>" onclick="changeCity(this.id);" href="#"><?php echo $city['CityName']; ?></a></li>
								  	<?php } } ?>
								  	</ul>
								<?php } elseif(isset($page) && isset($sadmin_city_id) && $page == 'admin' && intval($sadmin_city_id) > 0) { ?>
									<a href="<?php echo base_url('admin/adminhome'); ?>" class="btn btn-default btn-flat">Profile</a>
								<?php } else { ?>
									<a href="<?php echo base_url('admin/adminhome'); ?>" class="btn btn-default btn-flat">Dashboard</a>
								<?php } ?>
							</div>
							<div class="pull-right">
								<a href="/home/admin_logout/<?php echo base64_encode(current_url()); ?>" class="btn btn-default btn-flat">Sign out</a>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>
<?php } ?>
<!-- header ends -->