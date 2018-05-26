<?php if(isset($v_is_first_time) && $v_is_first_time == 1) { ?>
<!-- Password change modal starts -->
	<div class="modal show" id="pwdreset_modal">
		<div class="modal-dialog">
			<div class="modal-content reset-box">
				<div class="modal-header">
					<h4 class="modal-title">Reset your password</h4>
				</div>
				<div class="modal-body">
					<form role="form" method="POST">
						<div class="form-group">
							<label>Enter a new password</label>
							<input class="form-control" type="password" id="pswd1" name="pswd1" placeholder="Enter a new password" />
						</div>
						<div class="form-group">
							<label>Re-Enter password</label>
							<input class="form-control" type="password" id="pswd2" name="pswd2" placeholder="ReEnter the above password" />
						</div>
						<div class="form-group center">
							<button type="submit" name="pwdreset" id="pwdreset" class="btn btn-primary" >
								Change Password
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<!-- Password change modal ends-->
<?php } ?>
<!-- header starts -->
<header class="header">
	<nav role="navigation">
		<!-- Sidebar toggle button-->
		<div class="nav-wrapper">
			<a href="<?php echo base_url('vendor/vendorhome'); ?>" class="left hide-on-med-and-down padding-10px clear-hover-bg">
				<img src="<?php echo site_url('img/mr_logo.png'); ?>" class="left header-logo" />
			</a>
			<a href="<?php echo base_url('vendor/vendorhome'); ?>" id="nav-desktop" class="right hide-on-large-only padding-10px clear-hover-bg">
				<img src="<?php echo site_url('img/mr_logo.png'); ?>" class="right header-logo-mob" />
			</a>
			<?php if(isset($v_is_logged_in) && $v_is_logged_in == 1) { ?>
			<ul class="right hide-on-med-and-down">
				<li class="pointer">
					<a href="<?php echo base_url('vendor/placeorder'); ?>" class="padding-right-0">
						Place Order
					</a>
				</li>
				<li class="pointer">
					<a href="<?php echo base_url('vendor/vendorhome'); ?>">
						<i class="nav-icon material-icons left">home</i>
					</a>
				</li>
				<li class="pointer">
					<a class='dropdown-button' data-activates='noti_ffeed'>
						<i class="nav-icon material-icons left">mail</i>
						<span class="label label-success" id="noti_fcount">0</span>
					</a>
					<ul class="dropdown-content custom-dd custom-vdd notify-scroll" id="noti_ffeed">
					</ul>
				</li>
				<li class="pointer">
					<a class='dropdown-button' data-activates='notif_warning'>
						<i class="nav-icon material-icons left">alarm</i>
						<span class="label label-warning">0</span>
					</a>
					<ul class="dropdown-content custom-dd custom-vdd notify-scroll" id="notif_warning">
						<li class="header">You have no notifications</li>
						<li class="footer vnav-footer"><a href="#">View all</a></li>
					</ul>
				</li>
				<li class="pointer">
					<a class='dropdown-button' data-activates='noti_ofeed'>
						<i class="nav-icon material-icons left">dvr</i>
						<span class="label label-danger" id="noti_ocount">0</span>
					</a>
					<ul class="dropdown-content custom-dd custom-vdd notify-scroll" id="noti_ofeed">
					</ul>
				</li>
				<li>
					<a href="#" id="af_login_dw" class='dropdown-button' data-activates='user_menu'>
						<i class="nav-icon material-icons left">account_circle</i>
						<span><?php echo convert_to_camel_case($this->session->userdata('v_name')); ?></span>
					</a>
					<ul class="dropdown-content custom-dd" id="user_menu">
						<li role="presentation">
							<a href="<?php echo base_url('vendor/vendorhome'); ?>" ><i class="nav-icon material-icons left">dashboard</i><span>Dashboard</span></a>
						</li>
						<li>
							<?php if($this->session->userdata('v_role') != "Admin") { ?>
								<a href="<?php echo base_url('vendor/profile/settings'); ?>" ><i class="nav-icon material-icons left">camera_front</i><span>Profile</span></a>
							<?php } else { ?>
								<a href="<?php echo base_url('vendor/profile'); ?>" ><i class="nav-icon material-icons left">camera_front</i><span>Profile</span></a>
							<?php } ?>
						</li>
						<li role="presentation"><a href="<?php echo base_url('vendor/profile/settings'); ?>"><i class="nav-icon material-icons left">settings</i><span>Settings</span></a></li>
						<li role="presentation" class="divider"></li>
						<li role="presentation"><a href="/home/vendor_logout/<?php echo base64_encode(current_url()); ?>"><i class="nav-icon material-icons left">open_in_browser</i><span>Logout</span></a></li>
					</ul>
				</li>
			</ul>
			<?php } else { ?>
			<ul id="nav-mobile" class="right hide-on-med-and-down">
				<li>
					<a id="nav-desktop" class="left hide-on-med-and-down">
						<i class="nav-icon material-icons left">perm_phone_msg</i>
						<span class="">080- 42296199
							<span class="">[9am - 6pm]</span>
						</span>
					</a>
				</li>
			</ul>
			<?php } ?>
		</div>
	</nav>
</header>
<!-- header ends -->