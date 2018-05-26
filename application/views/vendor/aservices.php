<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php if(isset($site_name)) { echo $site_name; } ?> - Vendor Panel - Price Chart</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<?php $this->load->view('vendor/components/_vcss'); ?>
	<style type="text/css">
		.styled-select {
			font-size: 1rem;
		}
		.form-control {
			height: 47px;
		}
	</style>
</head>
<body>
	<?php $this->load->view('vendor/components/_head'); ?>
	<?php if(isset($v_is_logged_in) && $v_is_logged_in == 1) { ?>
		<div class="wrapper row-offcanvas row-offcanvas-left">
			<?php $this->load->view('vendor/components/_sidebar'); ?>
			<aside class="right-side">
				<section class="content-header">
					<h1>
						Addon Services
						<small>Addon Services - For Periodic Servicing Orders - Price Update</small>
					</h1>
				</section>
				<form method="POST" action="/vendor/profile/save_asrprice">
				<section class="area-content">
					<div class="col-xs-12 area-box">
						<div class="pickup-title teal-bold">Addon Services</div>
						<div class="sub-title-grey">Add/Edit desired price for respective addon service.</div>
						<div class="col-xs-12 area-container">
							<div class="col-xs-12 area-container">
								<div class="col-xs-3">
									<div class="form-group" style="">
										<select class="form-control styled-select" onchange="checkfield1();" name="astype" id="astype">
											<option value="" selected style='display:none;'>Addon Service</option>
											<?php if(isset($aservices)) { foreach($aservices as $aservice) { ?>
												<option value="<?php echo $aservice->AServiceId; ?>"><?php echo $aservice->AServiceName; ?></option>
											<?php } } ?>
										</select>
									</div>
								</div>
								<div class="col-xs-3">
									<div class="form-group" style="">
										<select class="form-control styled-select" name="company" id="company">
											<option value="" selected style='display:none;'>Select Company</option>
											<?php if(isset($bcompanies)) { foreach($bcompanies as $bcompany) { ?>
												<option value="<?php echo $bcompany['BikeCompanyId']; ?>"><?php echo convert_to_camel_case($bcompany['BikeCompanyName']); ?></option>
											<?php } } ?>
										</select>
									</div>
								</div>
								<div class="col-xs-3">
									<input type="text" class="col-xs-6 form-control " oninput="checkfield1();" name="asr_price" id="asr_price" placeholder="Price in INR">
								</div>
								<div class="col-xs-3">
									<div class="checkbox" style="">
										<label class=""><input type="checkbox" name="ismandatory" value="1">
											<span style="margin-left:5px">Is Mandatory Service?</span>
										</label>
									</div> 
								</div>
								<div class="col-xs-6">
									<div class="checkbox" style="">
										<label class=""><input type="radio" class="taxtype" name="taxtype" value="1">
											<span style="margin-left:5px">Service Charge</span>
										</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<label class=""><input type="radio" class="taxtype" name="taxtype" value="2">
											<span style="margin-left:5px">VAT</span>
										</label>
									</div>
								</div>
								<button type="submit" name="asrp_sub" id="asrp_sub" class="margin-top-5px btn waves-effect waves-light btn-flat left" disabled>
									Update Price
								</button> 
								<button type="button" id="asrp_can" class="margin-top-5px margin-left-10px btn waves-effect waves-light btn-flat left" >
									Cancel
								</button>
							</div>
						</div>
					</div>
				</section>
				<section class="bike-models radius-content" id="bike-models" style="display:none">
					<div class="col-xs-12 radius-box">
						<div class="pickup-title teal-bold">Select Bike Models</div>
						<div class="sub-title-grey">Select the Bike Models to which you want to apply the price</div>
						<div class="col-xs-12 area-container" id="bm_container">
						</div>
					</div>
				</section><!-- /.content -->
				</form>
				<div class='callout callout-info' style="margin-top: 22px;">
					<h5>Addon Services Price Chart</h5>
					<p>List of all the Addon Services and the corresponding prices allotted</p>
				</div>
				<div id="detail_tab" class="table-margin-top margin-bottom-0">
					<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">
						<thead>
							<tr>
								<th class="first"><i class="fa fa-cogs"></i> &nbsp;&nbsp;Service Name</th>
								<th style="width:12%"><i class="fa fa-motorcycle"></i> &nbsp;&nbsp;Bike Model</th>
								<th><i class="fa fa-inr"></i> &nbsp;&nbsp;Price</th>
								<th><i class="fa fa-institution"></i> &nbsp;&nbsp;Tax Type</th>
								<th><i class="fa fa-institution"></i> &nbsp;&nbsp;Mandatory</th>
								<th><i class="fa fa-calendar"></i> &nbsp;&nbsp;Last Updated</th>
								<th><i class="fa fa-pencil"></i> &nbsp;&nbsp;Delete</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($asrprices)) { foreach($asrprices as $srprice) { ?>
							<tr id="asp_<?php echo $srprice['ASPriceId']; ?>">
								<td><?php echo $srprice['AServiceName']; ?></td>
								<td><?php echo convert_to_camel_case($srprice['BikeCompanyName'] . ' ' . $srprice['BikeModelName']); ?></td>
								<td><?php echo $srprice['Price']; ?></td>
								<td><?php echo $srprice['TaxType']; ?></td>
								<td><?php echo $srprice['IsMand']; ?></td>
								<td><?php echo $srprice['Timestamp']; ?></td>
								<td><a href="<?php echo site_url('vendor/profile/delete_asprice/' . $srprice['ASPriceId']); ?>">Delete</a></td>
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
	$(document).on('ifChecked', '#select_all', function(event) {
		$('.bm').icheck('checked');
	});
	$(document).on('ifUnchecked', '#select_all', function(event) {
		$('.bm').icheck('unchecked');
	});
	$(document).on('ifChanged', '.bm', function() {
		checkfield1();
	});
	$('.taxtype').on('ifChanged', function() {
		checkfield1();
	});
	$('#asrp_can').on('click', function(e) {
		e.preventDefault();
		$('#bike-models').slideUp("slow");
	});
	$('#company').on('change', function() {
		if($('#company').val() != '') {
			var company = $('#company').val();
			$.ajax({
				type: "POST",
				url: "/vendor/profile/bikeList",
				data: {company: company},
				dataType: "json",
				cache:false,
				success: function(data) {
					var bikelist = '<div class="col-xs-3">\
						<div class="checkbox" style="">\
							<label class=""><input type="checkbox" class="bm" id="select_all">\
								<span style="margin-left:5px">Select All</span>\
							</label>\
						</div>\
					</div>';
					if (data != null) {
						for (i = 0; i < data.length; i++) {
							bikelist += '<div class="col-xs-3">\
								<div class="checkbox" style="">\
									<label class="">\
										<input type="checkbox" class="bm" id="addr_' + data[i].BikeModelId + '" name="bmodels[]" value="' + data[i].BikeModelId + '"> <span style="margin-left:5px">' + data[i].BikeModelName +'</span>\
									</label>\
								</div>\
							</div>';
						}
					}
					$('#bm_container').html(bikelist);
					$('.bm').icheck({checkboxClass: 'icheckbox_minimal'});
					$('#bike-models').slideDown("slow");
					checkfield1();
				}
			});
		} else {
			$('#bike-models').slideUp("slow");
		}
	});
});
function checkfield1() {
	var e1 = $("#astype").val();
	var e2 = $("#company").val();
	var e3 = $("input[name='bmodels[]']:checked").val();
	var e4 = $("#asr_price").val();
	var e5 = $("input[name='taxtype']:checked").val();
	var x = 0;
	if(e1 == "" || e2 == "" || typeof e3 === "undefined" || typeof e5 === "undefined" || e4 == "" || isNaN(e4)) {
		x = 1;
	}
	if(x == 0) {
		$("#asrp_sub").removeAttr('disabled');
	} else {
		$("#asrp_sub").attr('disabled','disabled');
	}
}
</script>
</body>
</html>