<aside class="left-side sidebar-offcanvas" style="max-height:100%;overflow:auto;">
	<section class="sidebar">
		<div class="user-panel">
			<div class="pull-left image">
				<div class="glyphicon glyphicon-user" style="font-size:30px"></div>
			</div>
			<div class="pull-left info" style="font-family:open_sanssemibold;padding-left:10px">
				<p><?php echo convert_to_camel_case($this->session->userdata('a_name')); ?> - <?php if(isset($site_name)) { echo $site_name; } ?><?php echo convert_to_camel_case($this->session->userdata('a_role')); ?></p>
			</div>
		</div>
		<?php if(isset($page) && $page == 'admin' && !in_array('admin', $denied_pages)) { ?>
		<ul class="sidebar-menu">
			<?php if(!in_array('admin', $denied_pages)) { ?>
			<li class="side-menu-list side-menu-inactive" id="admin_dashboard">
				<a href="<?php echo base_url('admin'); ?>">
					<i class="fa fa-dashboard"></i><span>Admin Dashboard</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('manageAdmin', $denied_pages)) { ?>
			<li class="side-menu-list side-menu-inactive" id="admin_manage">
				<a href="<?php echo base_url('admin/manageadmin'); ?>">
					<i class="fa fa-dashboard"></i><span>Manage Admins</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('manageExecutive', $denied_pages)) { ?>
			<li class="side-menu-list side-menu-inactive" id="executive_manage">
				<a href="<?php echo base_url('admin/manageexecutive'); ?>">
					<i class="fa fa-dashboard"></i><span>Manage Executives</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('order', $denied_pages)) { ?>
			<li class="side-menu-list side-menu-inactive" id="order_dashboard">
				<a href="<?php echo base_url('admin/orders'); ?>">
					<i class="fa fa-sitemap"></i><span>Order Dashboard</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('vendor', $denied_pages)) { ?>
			<li class="side-menu-list side-menu-inactive" id="vendor_dashboard">
				<a href="<?php echo base_url('admin/vendors'); ?>">
					<i class="fa fa-building"></i><span>Vendor Dashboard</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('pbs', $denied_pages)) { ?>
			<li class="side-menu-list side-menu-inactive" id="pb_dashboard">
				<a href="<?php echo base_url('admin/petrolbunks'); ?>">
					<i class="fa fa-building"></i><span>Petrol Bunk Dashboard</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('ecs', $denied_pages)) { ?>
			<li class="side-menu-list side-menu-inactive" id="puc_dashboard">
				<a href="<?php echo base_url('admin/pucs'); ?>">
					<i class="fa fa-building"></i><span>PUC Dashboard</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('pts', $denied_pages)) { ?>
			<li class="side-menu-list side-menu-inactive" id="pt_dashboard">
				<a href="<?php echo base_url('admin/punctures'); ?>">
					<i class="fa fa-building"></i><span>Puncture Dashboard</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('apps', $denied_pages)) { ?>
			<li class="side-menu-list side-menu-inactive" id="app_dashboard">
				<a href="<?php echo base_url('admin/approvals/scapps'); ?>">
					<i class="fa fa-building"></i><span>Approval</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('user', $denied_pages)) { ?>
			<li class="side-menu-list side-menu-inactive" id="user_dashboard">
				<a href="<?php echo base_url('admin/users'); ?>">
					<i class="fa fa-users"></i><span>User Dashboard</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('mvendor', $denied_pages)) { ?>
			<li class="side-menu-list side-menu-inactive" id="mvendors">
				<a href="<?php echo base_url('admin/mvendors'); ?>">
					<i class="fa fa-user-plus"></i><span>Manage Vendors</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('manageOffer', $denied_pages)) { ?>
			<li class="side-menu-list side-menu-inactive" id="manageOffer">
				<a href="<?php echo base_url('admin/manageoffer'); ?>">
					<i class="fa fa-bookmark"></i><span>Manage Offers</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('vfeedback', $denied_uris)) { ?>
			<li class="side-menu-list side-menu-inactive" id="vfeedback">
				<a href="<?php echo base_url('admin/feedback/vendors'); ?>">
					<i class="fa fa-edit"></i><span>Feedback</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('ucus', $denied_pages)) { ?>
			<li class="side-menu-list side-menu-inactive" id="ucontactus">
				<a href="<?php echo base_url('admin/ucontactus'); ?>">
					<i class="fa fa-bookmark"></i><span>User Requests</span>
				</a>
			</li>
			<?php } ?>
			<?php if(!in_array('agregs', $denied_pages)) { ?>
			<li class="side-menu-list side-menu-inactive" id="agregs">
				<a href="<?php echo base_url('admin/agregs'); ?>">
					<i class="fa fa-edit"></i><span>Agent Requests</span>
				</a>
			</li>
			<?php } ?>
		</ul>
		<?php } elseif(isset($page) && $page == 'vendor' && !in_array('vendor', $denied_pages)) { ?>
		<ul class="sidebar-menu">
			<li class="side-menu-list side-menu-inactive" id="vendor_oview">
				<a href="<?php echo base_url('admin/vendors'); ?>">
					<i class="fa fa-dashboard"></i><span>Overview</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="vendor_list">
				<a href="<?php echo base_url('admin/vendors/vlist'); ?>">
					<i class="fa fa-users"></i><span>Vendor List</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="vuser_list">
				<a href="<?php echo base_url('admin/vendors/userlist'); ?>">
					<i class="fa fa-user"></i><span>Vendor User-List</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="prvendor_list">
				<a href="<?php echo base_url('admin/vendors/prvlist'); ?>">
					<i class="fa fa-users"></i><span>Privileged Vendor User List</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="vslotmgmt">
				<a href="<?php echo base_url('admin/vendors/slotmgmt'); ?>">
					<i class="fa fa-calendar"></i><span>Slot Management</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="vholidayadd">
				<a href="<?php echo base_url('admin/vendors/holidayadd'); ?>">
					<i class="fa fa-calendar"></i><span>Add Holidays</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="vholidaymgmt">
				<a href="<?php echo base_url('admin/vendors/holidaymgmt'); ?>">
					<i class="fa fa-calendar"></i><span>Manage Holiday</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="feedback">
				<a href="<?php echo base_url('admin/vendors/writeto'); ?>">
					<i class="fa fa-edit"></i><span>Write to Vendors</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="addPrivilegedVendor">
				<a href="<?php echo base_url('admin/vendors/addPrivilegedVendor'); ?>">
					<i class="fa fa-edit"></i><span>Add Privileged Vendor</span>
				</a>
			</li>
		</ul>
		<?php } elseif(isset($page) && $page == 'user' && !in_array('user', $denied_pages)) { ?>
		<ul class="sidebar-menu">
			<li class="side-menu-list side-menu-inactive" id="dashboard">
				<a href="<?php echo base_url('admin/users'); ?>">
					<i class="fa fa-dashboard"></i><span>Overview</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="user_list">
				<a href="<?php echo base_url('admin/users/ulist'); ?>">
					<i class="fa fa-users"></i><span>User List</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="reminders">
				<a href="<?php echo base_url('admin/users/reminders'); ?>">
					<i class="fa fa-bell-o"></i><span>Reminders</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="feedback_user">
				<a href="<?php echo base_url('admin/users/writeto'); ?>">
					<i class="fa fa-edit"></i><span>Write to Users</span>
				</a>
			</li>
		</ul>
		<?php } elseif(isset($page) && $page == 'order' && !in_array('order', $denied_pages)) { ?>
		<ul class="sidebar-menu">
			<li class="side-menu-list side-menu-inactive" id="dashboard">
				<a href="<?php echo base_url('admin/orders'); ?>">
					<i class="fa fa-dashboard"></i><span>Dashboard</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="upcoming">
				<a href="<?php echo base_url('admin/orders/upcoming'); ?>">
					<i class="fa fa-calendar"></i><span>Upcoming</span>
					<small class="badge badge-float bg-blue"><?php if(isset($nav_upcoming_count)) { echo $nav_upcoming_count; } ?></small>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="unallot">
				<a href="<?php echo base_url('admin/orders/unallotted'); ?>">
					<i class="fa fa-bell"></i><span>UnAllotted</span>
					<small class="badge badge-float bg-red"><?php if(isset($nav_unallotted_count)) { echo $nav_unallotted_count; } ?></small>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="allot">
				<a href="<?php echo base_url('admin/orders/allotted'); ?>">
					<i class="fa fa-cogs"></i><span>Allotted</span>
					<small class="badge badge-float bg-teal"><?php if(isset($nav_allotted_count)) { echo $nav_allotted_count; } ?></small>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="serviced">
				<a href="<?php echo base_url('admin/orders/serviced'); ?>">
					<i class="fa fa-history"></i><span>Serviced</span>
					<small class="badge badge-float bg-grey"><?php if(isset($nav_serviced_count)) { echo $nav_serviced_count; } ?></small>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="queried">
				<a href="<?php echo base_url('admin/orders/queried'); ?>">
					<i class="fa fa-share-square-o"></i><span>Queried</span>
					<small class="badge badge-float bg-yellow"><?php if(isset($nav_queried_count)) { echo $nav_queried_count; } ?></small>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="grievance">
				<a href="<?php echo base_url('admin/orders/grievance'); ?>">
					<i class="fa fa-share-square-o"></i><span>Grievances</span>
					<small class="badge badge-float bg-yellow"><?php if(isset($nav_grievance_count)) { echo $nav_grievance_count; } ?></small>
				</a>
			</li>			
			<li class="side-menu-list side-menu-inactive" id="feedbackReminders">
				<a href="<?php echo base_url('admin/orders/feedbackReminders'); ?>">
					<i class="fa fa-bell"></i><span>Feedback Reminder</span>
					<small class="badge badge-float bg-black"><?php if(isset($nav_feedback_reminders_count)) { echo $nav_feedback_reminders_count; } ?></small>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="serviceReminders">
				<a href="<?php echo base_url('admin/orders/serviceReminders'); ?>">
					<i class="fa fa-bell"></i><span>Service Reminders</span>
					<small class="badge badge-float bg-black"><?php if(isset($nav_service_reminders_count)) { echo $nav_service_reminders_count; } ?></small>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="emgorders">
				<a href="<?php echo base_url('admin/orders/emgorders'); ?>">
					<i class="fa fa-share-square-o"></i><span>Emergency Orders</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="ptorders">
				<a href="<?php echo base_url('admin/orders/ptorders'); ?>">
					<i class="fa fa-share-square-o"></i><span>Puncture Orders</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="ptportal">
				<a href="<?php echo base_url('admin/orders/ptportal'); ?>">
					<i class="fa fa-share-square-o"></i><span>Puncture Portal</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="pbportal">
				<a href="<?php echo base_url('admin/orders/pbportal'); ?>">
					<i class="fa fa-share-square-o"></i><span>Petrol Bunk Portal</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="drpdorders">
				<a href="<?php echo base_url('admin/orders/droppedorders'); ?>">
					<i class="fa fa-share-square-o"></i><span>Dropped Orders</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="orderPlan">
				<a href="<?php echo base_url('admin/orders/orderPlan'); ?>">
					<i class="fa fa-share-square-o"></i><span>Order Plan</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="orderDemography">
				<a href="<?php echo base_url('admin/orders/orderDemography'); ?>">
					<i class="fa fa-share-square-o"></i><span>Order Demography</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="orderBills">
				<a href="<?php echo base_url('admin/orders/orderBills'); ?>">
					<i class="fa fa-share-square-o"></i><span>Order Bills</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="executiveOrderTracking">
				<a href="<?php echo base_url('admin/orders/executiveOrderTracking'); ?>">
					<i class="fa fa-share-square-o"></i><span>Executive Order Tracking</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="spareParts">
				<a href="<?php echo base_url('admin/orders/spareParts'); ?>">
					<i class="fa fa-share-square-o"></i><span>Spare Parts</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="history">
				<a href="<?php echo base_url('admin/orders/archived'); ?>">
					<i class="fa fa-archive"></i><span>History</span>
				</a>
			</li>
			<?php if(!in_array('preleases', $denied_uris)) { ?>
			<li class="side-menu-list side-menu-inactive" id="preleases">
				<a href="<?php echo base_url('admin/orders/payment_releases'); ?>">
					<i class="fa fa-money"></i><span>Vendor Payments</span>
				</a>
			</li>
			<?php } ?>
			<li class="side-menu-list side-menu-inactive" id="neworder">
				<a href="<?php echo base_url('admin/orders/place_order'); ?>">
					<i class="fa fa-share-square-o"></i><span>Place Order</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="rating">
				<a href="<?php echo base_url('admin/orders/rating'); ?>">
					<i class="fa fa-share-square-o"></i><span>NPS Rating</span>
				</a>
			</li>
		</ul>
		<?php } elseif(isset($page) && $page == 'mvendor' && !in_array('mvendor', $denied_pages)) { ?>
		<ul class="sidebar-menu">
			<li class="side-menu-list side-menu-inactive" id="add_vendor">
				<a href="<?php echo base_url('admin/mvendors'); ?>">
					<i class="fa fa-user-plus"></i><span>Add Vendor</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="mod_vendor">
				<a href="<?php echo base_url('admin/mvendors/vedit'); ?>">
					<i class="fa fa-users"></i><span>Modify Vendor</span>
				</a>
			</li>
		</ul>
		<?php } elseif(isset($page) && $page == 'manageOffer' && !in_array('manageOffer', $denied_pages)) { ?>
		<ul class="sidebar-menu">
			<li class="side-menu-list side-menu-inactive" id="add_offer">
				<a href="<?php echo base_url('admin/manageoffer'); ?>">
					<i class="fa fa-gift"></i><span>Add Offer</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="edit_offer">
				<a href="<?php echo base_url('admin/manageoffer/editoffer'); ?>">
					<i class="fa fa-gift"></i><span>Modify Offer</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="add_referral">
				<a href="<?php echo base_url('admin/manageoffer/addreferral'); ?>">
					<i class="fa fa-gift"></i><span>Add Referral Code</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="edit_referral">
				<a href="<?php echo base_url('admin/manageoffer/editreferral'); ?>">
					<i class="fa fa-gift"></i><span>Modify Referral Code</span>
				</a>
			</li>
		</ul>
		<?php } elseif(isset($page) && $page == 'manageAdmin' && !in_array('manageAdmin', $denied_pages)) { ?>
		<ul class="sidebar-menu">
			<li class="side-menu-list side-menu-inactive" id="add_admin">
				<a href="<?php echo base_url('admin/manageadmin'); ?>">
					<i class="fa fa-user-plus"></i><span>Add Admin</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="edit_admin">
				<a href="<?php echo base_url('admin/manageadmin/editadmin'); ?>">
					<i class="fa fa-users"></i><span>Modify Admin</span>
				</a>
			</li>
			<?php if(!in_array('addadminreminder', $denied_uris)) { ?>
				<li class="side-menu-list side-menu-inactive" id="add_admin_reminder">
					<a href="<?php echo base_url('admin/manageadmin/addadminreminder'); ?>">
						<i class="fa fa-user-plus"></i><span>Add Admin Reminders</span>
					</a>
				</li>
				<li class="side-menu-list side-menu-inactive" id="edit_admin_reminder">
					<a href="<?php echo base_url('admin/manageadmin/editadminreminder'); ?>">
						<i class="fa fa-users"></i><span>View Admin Reminders</span>
					</a>
				</li>
			<?php } ?>
		</ul>
		<?php } elseif(isset($page) && $page == 'manageExecutive' && !in_array('manageExecutive', $denied_pages)) { ?>
		<ul class="sidebar-menu">
			<li class="side-menu-list side-menu-inactive" id="add_executive">
				<a href="<?php echo base_url('admin/manageexecutive'); ?>">
					<i class="fa fa-user-plus"></i><span>Add Executive</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="edit_executive">
				<a href="<?php echo base_url('admin/manageexecutive/editexecutive'); ?>">
					<i class="fa fa-users"></i><span>Modify Executive</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="petrol_claims">
				<a href="<?php echo base_url('admin/manageexecutive/petrol_claims'); ?>">
					<i class="fa fa-users"></i><span>Petrol Claims</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="pettyCash">
				<a href="<?php echo base_url('admin/manageexecutive/pettyCash'); ?>">
					<i class="fa fa-users"></i><span>Petty Cash</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="addExecutiveRewards">
				<a href="<?php echo base_url('admin/manageexecutive/addExecutiveRewards'); ?>">
					<i class="fa fa-users"></i><span>Add Executive Reward</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="viewExecutiveRewards">
				<a href="<?php echo base_url('admin/manageexecutive/viewExecutiveRewards'); ?>">
					<i class="fa fa-users"></i><span>View Executive Rewards</span>
				</a>
			</li>
			<?php if(!in_array('executiveLeave', $denied_uris)) { ?>
				<li class="side-menu-list side-menu-inactive" id="executiveLeave">
					<a href="<?php echo base_url('admin/manageexecutive/executiveLeave'); ?>">
						<i class="fa fa-users"></i><span>Executive Leave Panel</span>
					</a>
				</li>
			<?php } ?>
		</ul>
		<?php } elseif(isset($page) && $page == 'pbs' && !in_array('pbs', $denied_pages)) { ?>
		<ul class="sidebar-menu">
			<li class="side-menu-list side-menu-inactive" id="pb_oview">
				<a href="<?php echo base_url('admin/petrolbunks/pblist'); ?>">
					<i class="fa fa-dashboard"></i><span>Overview</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="pb_list">
				<a href="<?php echo base_url('admin/petrolbunks/pblist'); ?>">
					<i class="fa fa-users"></i><span>Bunks List</span>
				</a>
			</li>
		</ul>
		<?php } elseif(isset($page) && $page == 'ecs' && !in_array('ecs', $denied_pages)) { ?>
		<ul class="sidebar-menu">
			<li class="side-menu-list side-menu-inactive" id="ec_oview">
				<a href="<?php echo base_url('admin/pucs/eclist'); ?>">
					<i class="fa fa-dashboard"></i><span>Overview</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="ec_list">
				<a href="<?php echo base_url('admin/pucs/eclist'); ?>">
					<i class="fa fa-users"></i><span>PUCs List</span>
				</a>
			</li>
		</ul>
		<?php } elseif(isset($page) && $page == 'pts' && !in_array('pts', $denied_pages)) { ?>
		<ul class="sidebar-menu">
			<li class="side-menu-list side-menu-inactive" id="pt_oview">
				<a href="<?php echo base_url('admin/punctures/ptlist'); ?>">
					<i class="fa fa-dashboard"></i><span>Overview</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="pt_list">
				<a href="<?php echo base_url('admin/punctures/ptlist'); ?>">
					<i class="fa fa-users"></i><span>Puncture Centers List</span>
				</a>
			</li>
		</ul>
		<?php } elseif(isset($page) && $page == 'apps' && !in_array('apps', $denied_pages)) { ?>
		<ul class="sidebar-menu">
			<li class="side-menu-list side-menu-inactive" id="scapps">
				<a href="<?php echo base_url('admin/approvals/scapps'); ?>">
					<i class="fa fa-dashboard"></i><span>Service Centers</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="pbapps">
				<a href="<?php echo base_url('admin/approvals/pbapps'); ?>">
					<i class="fa fa-dashboard"></i><span>Petrol Bunks</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="ecapps">
				<a href="<?php echo base_url('admin/approvals/ecapps'); ?>">
					<i class="fa fa-dashboard"></i><span>PUC Centers</span>
				</a>
			</li>
			<li class="side-menu-list side-menu-inactive" id="ptapps">
				<a href="<?php echo base_url('admin/approvals/ptapps'); ?>">
					<i class="fa fa-dashboard"></i><span>Puncture Centers</span>
				</a>
			</li>
		</ul>
		<?php } elseif(isset($page) && $page == 'agregs' && !in_array('agregs', $denied_pages)) { ?>
		<ul class="sidebar-menu">
			<li class="side-menu-list side-menu-inactive" id="agrg_oview">
				<a href="<?php echo base_url('admin/agregs'); ?>">
					<i class="fa fa-dashboard"></i><span>Overview</span>
				</a>
			</li>
		</ul>
		<?php } elseif(isset($page) && $page == 'ucus' && !in_array('ucus', $denied_pages)) { ?>
		<ul class="sidebar-menu">
			<li class="side-menu-list side-menu-inactive" id="ucus_oview">
				<a href="<?php echo base_url('admin/ucontactus'); ?>">
					<i class="fa fa-dashboard"></i><span>Overview</span>
				</a>
			</li>
		</ul>
		<?php } ?>
	</section>
	<input type="hidden" id="active" value="<?php if(isset($active)) { echo $active; } ?>">
</aside>