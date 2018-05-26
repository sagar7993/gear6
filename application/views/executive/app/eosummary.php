<div class="main">
	<div class="">
		<nav>
			<div class="nav-wrapper">
				<a href="https://www.gear6.in/" id="nav-desktop" class="brand-logo center height100">
					<img src="https://www.gear6.in/img/mr_logo.png" class="right responsive-img">
				</a>
				<a href="javascript:;" data-activates="mobile-view" class="button-collapse"><i class="material-icons">menu</i></a>
				<ul class="side-nav" id="mobile-view">
					<li><a href="javascript:;" id="ex_myorders"><i class="material-icons">local_play</i><div class="inline-block">My Orders</div></a></li>
					<li><a href="javascript:;" id="ex_fbills"><i class="material-icons">local_gas_station</i>Fuel Billing</a></li>
					<li><a href="javascript:;" id="ex_ttasks"><i class="material-icons">restore</i>Today's Tasks</a></li>
					<li><a href="javascript:;" id="ex_signout"><i class="material-icons">power_settings_new</i>Sign Out</a></li>
				</ul>
			</div>
		</nav>
		<div>
			<table class="tcenter bordered">
				<thead style="background:#f6f6f5">
					<tr style="background:#f6f6f5">
						<th data-field="id">Customer</th>
						<th data-field="price">Time - Location</th>
						<th data-field="name">Service Center</th>
						<th data-field="price">Contact</th>
					</tr>
				</thead>
				<tbody>
					<?php if(isset($curr_orders)) { foreach($curr_orders as $curr_order) { ?>
					<tr>
						<td><a href="javascript:;" id="ex_osummary" data-oid="<?php echo $curr_order['OId']; ?>"><?php echo convert_to_camel_case($curr_order['UserName']); ?></a></td>
						<td><?php echo $curr_order['SlotHour']; ?> - <?php echo convert_to_camel_case($curr_order['UserLocation']); ?></td>
						<td><?php echo convert_to_camel_case($curr_order['ScName']); ?> - <?php echo convert_to_camel_case($curr_order['ScLocation']); ?></td>
						<td><a href="tel:<?php echo '+91' . $curr_order['Phone']; ?>"><?php echo '+91' . $curr_order['Phone']; ?></a></td>
					</tr>
					<?php } } else { ?>
					<tr><td colspan="4" style="text-align:center;">No Orders Assigned for you today</td></tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>