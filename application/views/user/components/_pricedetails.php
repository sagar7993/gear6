<?php if(isset($amprices[count($amprices) - 1]['ptotal'])) { $total_price = $amprices[count($amprices) - 1]['ptotal']; } else { $total_price = 0; } ?>
<div class="price-details-container1">
	<div class="price-map-container1">
		<?php if (isset($amprices)) { ?>
		<table class="light-gray-background">
			<tr class="light-gray-background">
				<td><b class="selected-options">Service / Amenity Description</b></td>
				<td class="r_price-text"><b class="selected-options">Cost</b></td>
				<td class="r_price-text"><b class="selected-options">Tax</b></td>
				<td class="r_price-text"><b class="selected-options">Total</b></td>
			</tr>
			<?php foreach($amprices as $amprice) { if(isset($amprice['apdesc']) && isset($amprice['aprice'])) { ?>
				<tr class="light-gray-background">
					<td><?php echo convert_to_camel_case($amprice['apdesc']); ?></td>
					<td class="r_price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $amprice['aprice']; ?></td>
					<td class="r_price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo $amprice['atprice']; ?></td>
					<td class="r_price-text"><i class="fa fa-inr"></i>&nbsp;<?php echo ($amprice['aprice'] + $amprice['atprice']); ?></td>
				</tr>
			<?php } } ?>
		</table>
		<?php } ?>
		<div class="service-list-container" id="all_coupon_block" style="display: none;width:92%;padding-left:5px;">
		</div>
		<div class="" style="border-top:1px solid #028cbc;width: 92%;margin-top: 20px;padding-left:5px;">
			<div style="float:left;clear:both;">Total Amount to be Paid</div>
			<div class="right"><i class="fa fa-inr"></i>&nbsp;<span id="total_price_label"><?php echo $total_price; ?></span></div>
		</div>
		<input type="hidden" id="total_price_value" value="<?php echo $total_price; ?>" />
		<?php if($total_price < 0.1) { echo '<input type="hidden" id="is_prices_null" value="1">'; } else { echo '<input type="hidden" id="is_prices_null" value="0">'; } ?>
	</div>
</div>