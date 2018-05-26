<?php if(isset($prv_is_first_time) && $prv_is_first_time == 1) { ?>
<!-- Password change modal starts -->
	<div class="modal show" id="pwdreset_modal">
		<div class="modal-dialog">
			<div class="modal-content reset-box">
				<div class="modal-header center">
					<h4 class="modal-title">Reset your password</h4>
				</div>
				<div class="modal-body">
					<form role="form" method="POST">
						<div class="form-group">
							<label>Enter a new password</label>
							<input class="form-control ph-color" type="password" id="pswd1" name="pswd1" placeholder="Enter a new password" />
						</div>
						<div class="form-group">
							<label>Re-Enter password</label>
							<input class="form-control ph-color" type="password" id="pswd2" name="pswd2" placeholder="ReEnter the above password" />
						</div>
						<div class="form-group center">
							<button type="submit" name="pwdreset" id="pwdreset" class="btn btn-primary" style="background-color: #2c2c2c !important">
								Change Password
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<style>
.ph-color {
	border: 1px solid #9e9e9e !important;
}
.ph-color::-webkit-input-placeholder { color: #f6f6f5 !important; }
.ph-color::-moz-placeholder { color: #f6f6f5 !important; }
.ph-color:-ms-input-placeholder { color: #f6f6f5 !important; }
</style>
<!-- Password change modal ends-->
<?php } ?>
<!-- header starts -->
<header class="header">
	<nav role="navigation">
		<!-- Sidebar toggle button-->
		<div class="nav-wrapper">
			<a href="<?php echo base_url('prvendor/bizhome'); ?>" class="left hide-on-med-and-down padding-10px clear-hover-bg">
				<img src="<?php echo site_url('img/mr_logo.png'); ?>" class="left header-logo" />
			</a>
			<a href="<?php echo base_url('prvendor/bizhome'); ?>" id="nav-desktop" class="right hide-on-large-only padding-10px clear-hover-bg">
				<img src="<?php echo site_url('img/mr_logo.png'); ?>" class="right header-logo-mob" />
			</a>
			<?php if(isset($prv_loggedin) && $prv_loggedin == 1) { ?>
			<ul class="right hide-on-med-and-down">
				<li class="pointer">
					<a href="<?php echo base_url('prvendor/bizhome'); ?>">
						<i class="nav-icon material-icons left">home</i>
					</a>
				</li>
				<li>
					<a href="#" id="af_login_dw" class='dropdown-button' data-activates='user_menu'>
						<i class="nav-icon material-icons left">account_circle</i>
						<span><?php echo convert_to_camel_case($this->session->userdata('prv_name')); ?></span>
					</a>
					<ul class="dropdown-content custom-dd" id="user_menu">
						<li role="presentation">
							<a href="<?php echo base_url('prvendor/bizhome'); ?>" ><i class="nav-icon material-icons left">dashboard</i><span>Dashboard</span></a>
						</li>
						<li role="presentation" class="divider"></li>
						<li role="presentation"><a href="/home/prv_logout/<?php echo base64_encode(current_url()); ?>"><i class="nav-icon material-icons left">open_in_browser</i><span>Logout</span></a></li>
					</ul>
				</li>
			</ul>
			<?php } else { ?>
			<ul id="nav-mobile" class="right hide-on-med-and-down">
				<li>
					<a id="nav-desktop" class="left hide-on-med-and-down">
						<i class="nav-icon material-icons left">perm_phone_msg</i>
						<span class="">+91-9148417661
							<span class="">[9 AM - 6 PM]</span>
						</span>
					</a>
				</li>
			</ul>
			<?php } ?>
		</div>
	</nav>
</header>
<!-- header ends -->