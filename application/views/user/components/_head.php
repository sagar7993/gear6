<?php $this->load->view('user/components/_modals'); ?>
<!-- header starts -->
<header class="header">
	<nav>
		<!-- Sidebar toggle button-->
		<div class="nav-wrapper container">
			<a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
			<a id="nav-desktop" class="left hide-on-med-and-down">
				<i class="nav-icon material-icons left">&#xE8A8;</i>
				<span class="">9494845111
				</span>
			</a>
			<a id="nav-desktop" class="right hide-on-large-only">
				<i class="nav-icon material-icons left">perm_phone_msg</i>
				<span class="">9494845111
					<span class=""></span>
				</span>
			</a>
			<ul id="nav-desktop" class="right hide-on-med-and-down">
				<?php if(isset($is_logged_in) && $is_logged_in == 1) { ?>
				<li>
					<a href="<?php echo base_url('user/account/uprofile#show_referral_url'); ?>">
						<i class="nav-icon material-icons left">card_giftcard</i>
						<span class="margin-left-n5px">Refer A Friend
						</span>
					</a>
				</li>
				<?php } ?>
				<li>
					<a href="#flow_web">
						<i class="nav-icon material-icons left">perm_data_setting</i>
						<span class="margin-left-n5px">How It Works ?
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
						<span class="modal-trigger sign-link" data-target="login" id="signIn">Sign In</span>
<!-- 						<span>/</span>
						<span class="modal-trigger sign-link" data-target="signup">Sign Up</span> -->
					</a>
					<?php } ?>
				</li>
				<li>
					<a>
						<i class="nav-icon material-icons left">location_on</i>
						<span class="margin-left-n5px"><?php if (isset($city_name)) { echo convert_to_camel_case($city_name); } else { echo 'Choose Your City'; } ?>
						</span>
					</a>
				</li>
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
						<span class="modal-trigger sign-link" data-target="login" id="signIn">SignIn</span>
					</a>
				</li>
				<li>
					<a>
						<i class="nav-icon nav-icon-mob material-icons left">send</i>
						<span class="modal-trigger sign-link" data-target="signup">SignUp</span>
					</a>
				</li>
				<?php } ?>
				<li>
					<a>
						<i class="nav-icon nav-icon-mob material-icons left">location_on</i>
						<span><?php if(isset($city_name)) { echo convert_to_camel_case($city_name); } ?></span>
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