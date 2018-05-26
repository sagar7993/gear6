<aside class="left-side1 sidebar-offcanvas">
	<section class="sidebar">
		<ul class="sidebar-menu">
			<li class="side-menu-list side-menu-<?php if(isset($myorders) && $myorders == 1) { echo 'active'; } else { echo 'inactive'; } ?> dashboard">
				<a href="<?php echo base_url('user/account/corders'); ?>">
					<span class="big-icon"><i class="material-icons">loyalty</i></span><br> <span >My Orders</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-<?php if(isset($uprofile) && $uprofile == 1) { echo 'active'; } else { echo 'inactive'; } ?>">
				<a href="<?php echo base_url('user/account/uprofile'); ?>">
					<span class="big-icon"><i class="material-icons">folder_shared</i></span><br> <span >My Profile</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-<?php if(isset($bprofile) && $bprofile == 1) { echo 'active'; } else { echo 'inactive'; } ?>">
				<a href="<?php echo base_url('user/account/bprofile'); ?>" >
					<span class="big-icon"><i class="material-icons">directions_bike</i></span><br> <span >Bike Profile</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-<?php if(isset($ohistory) && $ohistory == 1) { echo 'active'; } else { echo 'inactive'; } ?>">
				<a href="<?php echo base_url('user/account/porders'); ?>" >
					<span class="big-icon"><i class="material-icons">history</i></span><br> <span >History</span>
				</a>
			</li>
		</ul>
	</section>
</aside>