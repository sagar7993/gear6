<?php $this->load->view('user/components/_modals'); ?>
<!-- header starts -->
<header class="header"<?php if($_GET && $_GET['app'] && $_GET['app'] == 'true') { echo ' style="display:none;"'; } ?>>
	<nav>
		<!-- Sidebar toggle button-->
		<div class="nav-wrapper container">
			<a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
			<a href="<?php echo site_url(); ?>" class="left hide-on-med-and-down padding-5px clear-hover-bg">
				<img src="<?php echo site_url('img/mr_logo.png'); ?>" class="left header-logo" />
			</a>
			<a href="<?php echo site_url(); ?>" id="nav-desktop" class="right hide-on-large-only padding-5px clear-hover-bg" href="<?php echo site_url(); ?>">
				<img src="<?php echo site_url('img/mr_logo.png'); ?>" class="right header-logo-mob" />
			</a>
			<ul id="nav-desktop" class="right hide-on-med-and-down">
				<li>
					<a href="https://www.gear6.in/">
						<i class="nav-icon material-icons left">home</i>
						<span class="margin-left-n5px">Home
						</span>
					</a>
				</li>
				<li>
					<a>
						<i class="nav-icon material-icons left">location_on</i>
						<span class="margin-left-n5px"><?php if (isset($city_name)) { echo convert_to_camel_case($city_name); } else { echo 'Choose Your City'; } ?>
						</span>
					</a>
				</li>
				<li>
					<a id="nav-desktop" class="left hide-on-med-and-down">
						<i class="nav-icon material-icons left">perm_phone_msg</i>
						<span class="">9494845111
							<span class=""></span>
						</span>
					</a>
				</li>
				<li>
					<?php if(isset($is_logged_in) && $is_logged_in == 1) { ?>
						<a href="#" id="af_login_dw" class='dropdown-button' data-activates='user_menu'>
							<i class="nav-icon material-icons left">settings_power</i>
							<span><?php echo convert_to_camel_case($this->session->userdata('name')); ?></span>
						</a>
						<ul class="dropdown-content custom-dd" id="user_menu">
							<li role="presentation"><a href="<?php echo base_url('user/account/corders'); ?>"><i class="nav-icon material-icons left">loyalty</i><span>My Orders</span></a></li>
							<li role="presentation"><a href="<?php echo base_url('user/account/uprofile'); ?>"><i class="nav-icon material-icons left">account_circle</i><span>My Profile</span></a></li>
							<li role="presentation" class="divider"></li>
							<li role="presentation"><a href="/home/user_logout/<?php echo base64_encode(current_url()); ?>"><i class="nav-icon material-icons left">open_in_browser</i><span>Logout</span></a></li>
						</ul>
						<?php } else { ?>
						<a>
							<i class="nav-icon material-icons left">input</i>
							<span class="modal-trigger sign-link" data-target="login" id="psignIn">Sign In</span>
							<span>/</span>
							<span class="modal-trigger sign-link" data-target="signup" id="psignUp">Sign Up</span>
						</a>
					<?php } ?>
				</li>
				<?php if(isset($is_logged_in) && $is_logged_in == 1) { ?>
				<li class="pointer">
					<a class='dropdown-button' data-activates='noti_ofeed1'>
						<i class="nav-icon material-icons left">notifications</i>
						<span class="label label-success notify-num" id="noti_ocount1">0</span>
					</a>
					<ul class="dropdown-content custom-dd custom-udd notify-scroll" id="noti_ofeed1">
					</ul>
				</li>
				<?php } ?>
			</ul>
			<ul id="nav-mobile" class="side-nav">
				<?php if(isset($is_logged_in) && $is_logged_in == 1) { ?>
				<li>
					<a href="<?php echo base_url('user/account/corders'); ?>">
					<i class="nav-icon material-icons left">loyalty</i>
					<span>My Orders</span>
					</a>
				</li>
				<li>
					<a href="<?php echo base_url('user/account/uprofile'); ?>">
					<i class="nav-icon material-icons left">account_circle</i>
					<span>My Profile</span>
					</a>
				</li>
				<?php } else { ?>
				<li>
					<a>
					<i class="nav-icon nav-icon-mob material-icons left">input</i>
					<span class="modal-trigger sign-link" data-target="login" id="msignIn">SignIn</span>
					</a>
				</li>
				<li>
					<a>
						<i class="nav-icon nav-icon-mob material-icons left">send</i>
						<span class="modal-trigger sign-link" data-target="signup" id="msignUp">SignUp</span>
					</a>
				</li>
				<?php } ?>
				<li>
					<a>
						<i class="nav-icon nav-icon-mob material-icons left">location_on</i>
						<span><?php if(isset($city_name)) { echo convert_to_camel_case($city_name); } ?></span>
					</a>
				</li>
				<li>
					<a id="nav-desktop" class="">
						<i class="nav-icon nav-icon-mob material-icons left">perm_phone_msg</i>
						<span class="">9494845111
						</span>
					</a>
				</li>
				<?php if(isset($is_logged_in) && $is_logged_in == 1) { ?>
				<li>
					<a href="/home/user_logout/<?php echo base64_encode(current_url()); ?>">
						<i class="nav-icon nav-icon-mob material-icons left">open_in_browser</i>
						<span>Logout</span>
					</a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</nav>
</header>
<!-- header ends -->