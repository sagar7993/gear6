<aside class="left-side sidebar-offcanvas">
	<section class="sidebar">
		<section>
			<div class="sidebar-title">
				<div class="vendor-logo">
					<a class="text-center">
						<?php if(isset($sc_logo)) { ?>
							<img src="<?php echo get_awss3_url('uploads/scmedia/sc/img/' . $sc_logo); ?>">
						<?php } else { ?>
							<img src="<?php echo site_url('img/icons/favicon.png'); ?>">
						<?php } ?>
					</a>
				</div>
				<div>
					&nbsp;<?php echo convert_to_camel_case($this->session->userdata('v_role')); ?>
				</div>
			</div>
		</section>
		<?php if(isset($page) && $page == 'profile') { ?>
		<ul class="sidebar-menu">
			<?php if(!in_array('contact', $denied_tabs)) { ?>
			<li class="side-menu-list side-menu-inactive dashboard" id="contact">
				<a href="<?php echo base_url('vendor/profile'); ?>">
					<span class="big-icon"><i class="material-icons">contact_phone</i></span><br> <span >Contact Info</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('payment', $denied_tabs)) { ?>
			<li class="side-menu-list side-menu-inactive" id="payment">
				<a href="<?php echo base_url('vendor/profile/payments'); ?>">
					<span class="big-icon"><i class="material-icons">payment</i></span><br> <span>Payments Info</span>
				</a>
			</li>
			<?php } ?>
			<li class="side-menu-list side-menu-inactive" id="settings">
				<a href="<?php echo base_url('vendor/profile/settings'); ?>">
					<span class="big-icon"><i class="material-icons">settings</i></span><br>
					<span>Account Settings</span>
				</a>
			</li>
			<?php if(!in_array('services', $denied_tabs)) { ?>
			<li class="side-menu-list side-menu-inactive" id="services">
				<a href="<?php echo base_url('vendor/profile/services'); ?>">
					<span class="big-icon"><i class="material-icons">folder_special</i></span><br>
					<span>Services</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('aservices', $denied_tabs)) { ?>
			<li class="side-menu-list side-menu-inactive" id="aservices">
				<a href="<?php echo base_url('vendor/profile/aservices'); ?>">
					<span class="big-icon"><i class="material-icons">tab_unselected</i></span><br>
					<span>Addon Services</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('onexcl', $denied_tabs)) { ?>
			<li class="side-menu-list side-menu-inactive" id="onexcl">
				<a href="<?php echo base_url('vendor/profile/offersnexclusives'); ?>">
					<span class="big-icon"><i class="material-icons">stars</i></span><br>
					<span>Offers and Exclusives</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('price-chart', $denied_tabs)) { ?>
			<li class="side-menu-list side-menu-inactive" id="price-chart">
				<a href="<?php echo base_url('vendor/profile/pricechart'); ?>">
					<span class="big-icon"><i class="material-icons">local_atm</i></span><br>
					<span>Price Chart</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('slotm', $denied_tabs)) { ?>
			<li class="side-menu-list side-menu-inactive" id="slotm">
				<a href="<?php echo base_url('vendor/profile/slotmgmt'); ?>" target="_blank">
					<span class="big-icon"><i class="material-icons">developer_board</i></span><br>
					<span>Slot Management</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('musers', $denied_tabs)) { ?>
			<li class="side-menu-list side-menu-inactive" id="musers">
				<a href="<?php echo base_url('vendor/profile/manage_users'); ?>">
					<span class="big-icon"><i class="material-icons">supervisor_account</i></span><br>
					<span>Manage Users</span>
				</a>
			</li>
			<?php } ?>
		</ul>
		<?php } else { ?>
		<ul class="sidebar-menu">
			<li class="side-menu-list side-menu-inactive dashboard" id="dashboard">
				<a href="<?php echo base_url('vendor/vendorhome'); ?>" >
					<span class="big-icon"><i class="material-icons">settings_input_svideo</i></span><br> <span >Dashboard</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="upcoming">
				<a href="<?php echo base_url('vendor/upcoming'); ?>">
					<span class="big-icon"><i class="material-icons">perm_data_setting</i></span> <br>
					<span>Today's<small class="badge badge-float bg-blue"><?php if(isset($nav_upcoming_count)) { echo $nav_upcoming_count; } ?></small></span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="unallot">
				<a href="<?php echo base_url('vendor/unallotted'); ?>">
					<span class="big-icon"><i class="material-icons">new_releases</i></span><br><span>UnAllotted</span>
					<small class="badge badge-float bg-red"><?php if(isset($nav_unallotted_count)) { echo $nav_unallotted_count; } ?></small>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="allot">
				<a href="<?php echo base_url('vendor/allotted'); ?>">
					<span class="big-icon"><i class="material-icons">event</i></span> <br><span>Allotted</span>
					<small class="badge badge-float bg-teal"><?php if(isset($nav_allotted_count)) { echo $nav_allotted_count; } ?></small>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="queried">
				<a href="<?php echo base_url('vendor/queried'); ?>">
					<span class="big-icon"><i class="material-icons">trending_up</i></span> <br><span>Queried</span>
					<small class="badge badge-float bg-yellow"><?php if(isset($nav_queried_count)) { echo $nav_queried_count; } ?></small>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="serviced">
				<a href="<?php echo base_url('vendor/serviced'); ?>">
					<span class="big-icon"><i class="material-icons">alarm_on</i></span> <br><span >Serviced</span>
					<small class="badge badge-float bg-grey"><?php if(isset($nav_serviced_count)) { echo $nav_serviced_count; } ?></small>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="history">
				<a href="<?php echo base_url('vendor/archived'); ?>">
					<span class="big-icon"><i class="material-icons">restore</i></span> <br><span>Order History</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="feedback">
				<a href="<?php echo base_url('vendor/feedback'); ?>">
					<span class="big-icon"><i class="material-icons">border_color</i></span><br>
					<span>User Feedbacks</span>
				</a>
			</li>
		</ul>
		<?php } ?>
	</section>
	<!-- /.sidebar -->
	<input type="hidden" id="active" value="<?php if(isset($active)) { echo $active; } ?>">
</aside>