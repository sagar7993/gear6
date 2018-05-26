<aside class="left-side sidebar-offcanvas">
	<section class="sidebar">
		<section>
			<div class="sidebar-title">
				<div class="vendor-logo">
					<a class="text-center">
						<?php if(isset($b_logo)) { ?>
							<img src="<?php echo site_url('img/business/img/' . $b_logo); ?>">
						<?php } else { ?>
							<img src="<?php echo site_url('img/icons/favicon.png'); ?>">
						<?php } ?>
					</a>
				</div>
				<div>
					&nbsp;<?php echo convert_to_camel_case($this->session->userdata('b_role')); ?>
				</div>
			</div>
		</section>
		<ul class="sidebar-menu">
			<li class="side-menu-list side-menu-inactive dashboard" id="dashboard">
				<a href="<?php echo base_url('business/bizhome'); ?>" >
					<span class="big-icon"><i class="material-icons">settings_input_svideo</i></span><br> <span >Dashboard</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="upcoming">
				<a href="<?php echo base_url('business/upcoming'); ?>">
					<span class="big-icon"><i class="material-icons">perm_data_setting</i></span> <br>
					<span>Today's<small class="badge badge-float bg-blue"><?php if(isset($nav_upcoming_count)) { echo $nav_upcoming_count; } ?></small></span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="unallot">
				<a href="<?php echo base_url('business/unallotted'); ?>">
					<span class="big-icon"><i class="material-icons">new_releases</i></span><br><span>UnAllotted</span>
					<small class="badge badge-float bg-red"><?php if(isset($nav_unallotted_count)) { echo $nav_unallotted_count; } ?></small>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="allot">
				<a href="<?php echo base_url('business/allotted'); ?>">
					<span class="big-icon"><i class="material-icons">event</i></span> <br><span>Allotted</span>
					<small class="badge badge-float bg-teal"><?php if(isset($nav_allotted_count)) { echo $nav_allotted_count; } ?></small>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="serviced">
				<a href="<?php echo base_url('business/serviced'); ?>">
					<span class="big-icon"><i class="material-icons">alarm_on</i></span> <br><span >Serviced</span>
					<small class="badge badge-float bg-grey"><?php if(isset($nav_serviced_count)) { echo $nav_serviced_count; } ?></small>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="history">
				<a href="<?php echo base_url('business/archived'); ?>">
					<span class="big-icon"><i class="material-icons">restore</i></span> <br><span>Order History</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- /.sidebar -->
	<input type="hidden" id="active" value="<?php if(isset($active)) { echo $active; } ?>">
</aside>