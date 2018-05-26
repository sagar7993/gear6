<div class="col-xs-4">
	<select class="styled-select2 form-control" id="slots" name="num_slots">
		<option selected readonly style="display:none" value="0">0</option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
		<option value="9">9</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
		<option value="13">13</option>
		<option value="14">14</option>
		<option value="15">15</option>
		<option value="16">16</option>
		<option value="17">17</option>
		<option value="18">18</option>
		<option value="19">19</option>
		<option value="20">20</option>
	</select>
</div>
<div>
	<div class="col-xs-8">
		<div class="col-xs-6">
			<div class="checkbox left-0" style=""><label class="price"><input type="checkbox" id="select_all" value="1"><span style="margin-left:5px">All</span></label></div>
		</div>
		<div class="col-xs-6 reset"><a href="">Reset</a></div>
	</div>
</div>
<div>
	<div class="col-xs-12">
		<?php
			$count = 0;
			foreach($slots as $slot) {
				if ($slot['Hour'] > 12) {
					$temp_hr = intval($slot['Hour'] - 12);
					$temp = (intval($slot['Hour'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$slot_hour = $temp_hr . ":" . $temp . "&nbsp;PM";
				} elseif ($slot['Hour'] == 12) {
					$slot_hour = intval($slot['Hour']) . ":00&nbsp;PM";
				} else {
					$temp = (intval($slot['Hour'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$slot_hour = intval($slot['Hour']) . ":" . $temp . "&nbsp;AM";
				}
				if(isset($slot['EHour']) && $slot['EHour'] != 0) {
					if ($slot['EHour'] > 12) {
						$slot_hour .= " - ";
						$temp_hr = intval($slot['EHour'] - 12);
						$temp = (intval($slot['EHour'] * 60) % 60);
						if($temp == 0) {
							$temp = '00';
						}
						$slot_hour .= $temp_hr . ":" . $temp . "&nbsp;PM";
					} elseif ($slot['EHour'] == 12) {
						$slot_hour .= " - ";
						$slot_hour .= intval($slot['EHour']) . ":00&nbsp;PM";
					} else {
						$slot_hour .= " - ";
						$temp = (intval($slot['EHour'] * 60) % 60);
						if($temp == 0) {
							$temp = '00';
						}
						$slot_hour .= intval($slot['EHour']) . ":" . $temp . "&nbsp;AM";
					}
				}
				if($count > 0 && $count % 3 == 0) {
					echo '</div><div class="col-xs-12">';
				}
		?>
		<div class="col-xs-4"><div class="checkbox left-0" style=""><label class="price"><input type="checkbox" name="slotids[]" class="slot-time" value="<?php echo $slot['SlotId']; ?>"><span style="margin-left:5px"><?php echo $slot_hour; ?></span></label></div></div>
		<?php $count++; } ?>
	</div>
</div>