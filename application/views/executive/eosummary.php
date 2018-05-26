<?php $this->load->view('executive/components/_head'); ?>
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
						<td><a href="/executive/exechome/eodetail/<?php echo $curr_order['OId']; ?>"><?php echo convert_to_camel_case($curr_order['UserName']); ?></a></td>
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
<?php $this->load->view('executive/components/_foot'); ?>
<script>
$(document).ready(function(){
	$(".button-collapse").sideNav();
});
</script>