<?php $this->load->view('executive/components/_head'); ?>
<div>
	<ul class="collapsible" data-collapsible="accordion">
		<li>
			<div class="collapsible-header"><i class="material-icons">alarm_on</i>Claims History</div>
			<div class="collapsible-body ">
				<div class="row" style="margin-top:10px;">
					<div class="col s12">
						<input type="date" class="datepicker" id="pbillsdate" placeholder="Start Date">
					</div>
					<div class="col s12">
						<input type="date" class="datepicker" id="pbilledate" placeholder="End Date">
					</div>
					<div class="col s12" id="exchk_claims_content" style="text-align:center;margin-top:12px;">
						<button class="waves-effect waves-light btn login-btn width100" id="ex_chk_claims">Check Claims</button>
					</div>
				</div>
				<table class="tcenter tbordered responsive-table">
					<thead style="background:#f6f6f5">
						<tr style="background:#f6f6f5">
							<th data-field="id">Start</th>
							<th data-field="price">End</th>
							<th data-field="name">Kms</th>
							<th data-field="price">Date</th>
							<th data-field="price">Purpose</th>
							<th data-field="price">Status</th>
						</tr>
					</thead>
					<tbody id="pbillquerydata">
						<?php if(isset($ltstpbills) && count($ltstpbills) > 0) { foreach($ltstpbills as $ltstpbill) { ?>
						<tr>
							<td><?php echo $ltstpbill['SLocation']; ?></td>
							<td><?php echo $ltstpbill['ELocation']; ?></td>
							<td><?php echo $ltstpbill['Kms']; ?></td>
							<td><?php echo $ltstpbill['Date']; ?></td>
							<td><?php echo $ltstpbill['Purpose']; ?></td>
							<td><?php echo $ltstpbill['isApproved']; ?></td>
						</tr>
						<?php } } else { ?>
						<tr><td colspan="6" style="text-align:center;">No data available</td></tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</li>
		<li>
			<div class="collapsible-header active"><i class="material-icons">receipt</i>New Claim</div>
			<div class="collapsible-body ">
				<div class="review-options-container row">
					<form id="pbclaimform">
						<div class="input-field col s12">
							<input id="" name="slocation" type="text" class="input-form" autocomplete="off">
							<label for="first_name" class="input-label">Start Location</label>
						</div>
						<div class="input-field col s12">
							<input type="password" style="display:none;" id="fke_password" />
							<input id="" name="elocation" type="text" class="input-form" autocomplete="off">
							<label for="last_name" class="input-label">End Location</label>
						</div>
						<div class="input-field col s12">
							<input type="password" style="display:none;" id="fke_password" />
							<input id="" name="pbkms" type="text" class="input-form" autocomplete="off">
							<label for="last_name" class="input-label">Kms Covered</label>
						</div>
						<div class="row">
							<div class="row">
								<div class="input-field col s12">
									<textarea id="icon_prefix2" name="purpose" class="materialize-textarea "></textarea>
									<label for="icon_prefix2" class="input-label">Purpose</label>
								</div>
								<div class="input-field col s12" id="pbclaimupdt_cont" style="text-align:center;margin-top:12px;">
									<button class="waves-effect waves-light btn login-btn width100" id="pbclaim_update">Claim Now</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</li>
	</ul>
</div>
<?php $this->load->view('executive/components/_foot'); ?>
<script>
$(document).ready(function() {
	$(".button-collapse").sideNav();
	$("select").material_select();
	$('.datepicker').pickadate({
		selectMonths: true,
		selectYears: 15
	});
});
</script>