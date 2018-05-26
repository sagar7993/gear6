<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Panel - Offers and Exclusives</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('vendor/components/_vcss'); ?>
</head>
<body>
	<?php $this->load->view('vendor/components/_head'); ?>
	<?php if(isset($v_is_logged_in) && $v_is_logged_in == 1) { ?>
		<div class="wrapper row-offcanvas row-offcanvas-left">
			<?php $this->load->view('vendor/components/_sidebar'); ?>
			<aside class="right-side">
				<section class="content-header">
					<h1>
						Offers and Exclusives
						<small>Offers and Exclusive deals</small>
					</h1>
				</section>
				<form method="POST" id="offer" action="/vendor/profile/create_offer">
				<section class="area-content">
					<div class="col-xs-12 area-box">
						<div class="pickup-title teal-bold">Offers</div>
						<div class="sub-title-grey">Add a new offer that you would like to provide</div>
						<div class="col-xs-12 area-container">
							<div class="col-xs-12 form-group fields-container">
								<div class="col-xs-6 field-box">
									<input data-type="text" data-mandatory="true" data-error="Offer Title" type="text" class="form-control" name="otitle" id="otitle" placeholder="Offer Title">
								</div>
								<div class="col-xs-6 field-box">
									<input data-type="price" data-mandatory="true" data-error="Offer Price" type="text" class="form-control" name="oprice" id="oprice" placeholder="Offer Price">
								</div>
							</div>
							<div class="col-xs-12 form-group fields-container">
								<div class="col-xs-6 field-box">
									<input data-type="text" data-mandatory="true" data-error="Offer Start Date" type="text" class="form-control" name="osdate" id="osdate" placeholder="Offer Start Date" style="cursor:pointer;" readonly>
								</div>
								<div class="col-xs-6 field-box">
									<input data-type="text" data-mandatory="true" data-error="Offer End Date" type="text" class="form-control" name="oedate" id="oedate" placeholder="Offer End Date" style="cursor:pointer;" readonly>
								</div>
							</div>
							<div class="col-xs-12 form-group fields-container">
								<div class="col-xs-6 field-box">
									<textarea data-type="text" data-mandatory="true" data-error="Offer Description" type="text" class="form-control" name="odesc" id="odesc" placeholder="Offer Description"></textarea>
								</div>
							</div>
							<div class="col-xs-6 form-group" style="margin-left: 40%;">
								<button type="submit" name="save_offer" id="save_offer" class="margin-top-5px btn waves-effect waves-light btn-flat left">
									Create Offer
								</button>
							</div>
						</div>
					</div>
				</section>
				</form>
				<section class="radius-content">
					<div class="col-xs-12 radius-box">
						<div class="pickup-title teal-bold">Exclusives</div>
						<div class="sub-title-grey">Add a new exclusive deal your service center provides</div>
						<form method="POST" id="excl" action="/vendor/profile/create_exclusive">
						<div class="col-xs-12 area-container">
							<div class="col-xs-12 form-group fields-container">
								<div class="col-xs-6 field-box">
									<input data-type="text" data-mandatory="true" data-error="Excusive Deal Title" type="text" class="form-control" name="etitle" id="etitle" placeholder="Excusive Deal Title">
								</div>
							</div>
							<div class="col-xs-12 form-group fields-container">
								<div class="col-xs-6 field-box">
									<textarea data-type="text" data-mandatory="true" data-error="Deal Description" type="text" class="form-control" name="edesc" id="edesc" placeholder="Deal Description"></textarea>
								</div>
							</div>
							<div class="col-xs-6 form-group" style="margin-left: 14%;">
								<button type="submit" name="save_excl" id="save_excl" class="margin-top-5px btn waves-effect waves-light btn-flat">
									Create Exclusive Deal
								</button>
							</div>
						</div>
						</form>
					</div>
				</section><!-- /.content -->
				<div class='callout callout-info' style="margin-top: 22px;">
					<h5>Offers List</h5>
					<p>List of all the orders</p>
				</div>  
				<div id="detail_tab1" class="table-margin-top margin-bottom-0">
					<table id="example3" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-cogs"></i> &nbsp;&nbsp;Offer Title</th>
								<th><i class="fa fa-institution"></i> &nbsp;&nbsp;Offer Description</th>
								<th><i class="fa fa-calender"></i> &nbsp;&nbsp;Valid From</th>
								<th><i class="fa fa-calender"></i> &nbsp;&nbsp;Valid Till</th>
								<th><i class="fa fa-inr"></i> &nbsp;&nbsp;Offer Price</th>
								<th><i class="fa fa-cogs"></i> &nbsp;&nbsp;Delete Offer</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($offers)) { foreach($offers as $offer) { ?>
							<tr id="<?php echo 'o_' . $offer['OfferId']; ?>">
								<td><?php echo convert_to_camel_case($offer['OTitle']); ?></td>
								<td><?php echo $offer['ODesc']; ?></td>
								<td><?php echo $offer['OFrom']; ?></td>
								<td><?php echo $offer['OTill']; ?></td>
								<td><?php echo $offer['Price']; ?></td>
								<td><a href="<?php echo base_url('vendor/profile/delete_offer/' . $offer['OfferId']); ?>">Delete</a></td>
							</tr>
							<?php } } ?>
						</tbody>
					</table>
				</div>
				<div class='callout callout-info margin-top-50'>
					<h5>Exclusives List</h5>
					<p>List of all exclusive deals provided by your service center</p>
				</div>
				<div id="detail_tab" class="table-margin-top margin-bottom-0">
					<table id="example2" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-cogs"></i> &nbsp;&nbsp;Title for Deal</th>
								<th><i class="fa fa-institution"></i> &nbsp;&nbsp;Description of the Deal</th>
								<th style="width:12%"><i class="fa fa-inr"></i> &nbsp;&nbsp;Delete Exclusive</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($exclusives)) { foreach($exclusives as $exclusive) { ?>
							<tr id="<?php echo 'e_' . $exclusive['ExclId']; ?>">
								<td><?php echo convert_to_camel_case($exclusive['ETitle']); ?></td>
								<td><?php echo $exclusive['EDesc']; ?></td>
								<td><a href="<?php echo base_url('vendor/profile/delete_exclusive/' . $exclusive['ExclId']); ?>">Delete</a></td>
							</tr>
							<?php } } ?>
						</tbody>
					</table>
				</div>
			</aside><!-- /.right-side -->
		</div>
	<?php $this->load->view('vendor/components/_foot'); ?>
	<?php } ?>
<?php $this->load->view('vendor/components/_vjs'); ?>
<script type="text/javascript">
$(function() {
	$('#osdate').pickadate({
		max: 365,
		formatSubmit: 'yyyy-mm-dd',
		closeOnSelect: true,
		container: 'body',
		onOpen: function() {
			$('#osdate').val('');	
		},
		onSet: function() {
			if($('#osdate').val() != "" ) {
				$(this).close();
			}
		}
	});
	$('#oedate').pickadate({
		max: 365,
		formatSubmit: 'yyyy-mm-dd',
		closeOnSelect: true,
		container: 'body',
		onOpen: function() {
			$('#oedate').val('');	
		},
		onSet: function() {
			if($('#oedate').val() != "" ) {
				$(this).close();
			}
		}
	});
	$('#save_excl, #save_offer').on('click', function(event) {
		var form_id = $(this).attr('id').split("_")[1];
		$('form#' + form_id + ' input, form#' + form_id + ' textarea').each(function() {
			if($(this).data('mandatory') == true) {
				if($(this).data('type') == 'text') {
					if($(this).val() == "" || $(this).val().length < 3) {
						showOnExclValidation($(this).attr('id'));
					} else if($(this).attr('id') == 'oedate') {
						var d1 = new Date($('#osdate').val());
						var d2 = new Date($('#oedate').val());
						if(d1 > d2) {
							showOnExclValidation($(this).attr('id'), 'Offer end date cannot be before the start date.');
						}
					}
				} else if($(this).data('type') == 'price') {
					if(isNaN($(this).val()) || $(this).val() == "") {
						showOnExclValidation($(this).attr('id'));
					}
				}
			}
		});
	});
});
showOnExclValidation = function(id, message) {
	if(typeof message === "undefined") {
		message = 'Please fill valid ' + $('#' + id).data('error');
	}
	$('.error-text').remove();
	if(id == "edesc" || id == "odesc") {
		$('#' + id).parent().append('<div class="error-text" style="margin-top: 0px;">' + message + '</div>');
	} else {
		$('#' + id).parent().append('<div class="error-text">' + message + '</div>');
	}
	$('html,body').animate({
		scrollTop: $($('#' + id)).offset().top
	}, 'slow');
	event.preventDefault();
	throw new Error('This is not an error. This is just to abort javascript');
	return false;
}
</script>
</body>
</html>