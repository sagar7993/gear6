$(function() {
	$('#adv-open').on('click', function() {
		if($('#adv-search-box').css('display') == 'none') {
			$('#adv-search-box').show("slow", function() {
				$('.top').css('margin-top','-60px');
				$('html, body').animate({scrollTop: $("#adv-open").offset().top}, 500);
			});
		} else {
			$('#adv-search-box').hide("slow", function() {
				$('.top').css('margin-top','-5px');
			});
		}
	});
	$('#stypeDD').on('change', function() {
		if($('#stypeDD').val() == 'BM') {
			$('#txtSearch').hide();
			$('#bikeModel').show();
			$('#oType').hide();
		} else if($('#stypeDD').val() == 'OT') {
			$('#txtSearch').hide();
			$('#bikeModel').hide();
			$('#oType').show();
		} else {
			$('#txtSearch').show();
			$('#bikeModel').hide();
			$('#oType').hide();
		}
	});
	$(document).on('ifChecked', '.drCB', function(event) {
		$id = $(this).attr('id').split('_')[1];
		$('#dateRangeBox_' + $id).show();
	});
	$(document).on('ifUnchecked', '.drCB', function(event) {
		$id = $(this).attr('id').split('_')[1];
		$('#dateRangeBox_' + $id).hide();
	});
	$(document).on('click','#adv_add',function() {
		$count = 1;
		$('.stype').each(function() {
			$count = $count + 1;
		});
		$('.review-options-container').append('<div class="clear-both"></div>\
				<div class="form-group col-xs-1 width10 connType" id="connType_' + $count + '">\
					<select class="form-control styled-select2" name="connType_' + $count + '" id="connType_'+$count+'">\
						<option selected value="and">AND</option>\
						<option value="or">OR</option>\
					</select>\
				</div>\
				<div class="clear-both"></div>\
				<div class="advSearch-container">\
					<div class="form-group col-xs-4 stype" id="stype">\
						<select class="form-control styled-select2" name="searchby_' + $count + '" id="stypeDD_' + $count + '">\
							<option disabled selected style="display:none;">Search By</option>\
							<option value="oid">Order ID</option>\
							<option value="BM">Bike Model</option>\
							<option value="OD">Order Date</option>\
							<option value="OT">Order Type</option>\
							<option value="CT">Contact</option>\
							<option value="CN">Customer Name</option>\
						</select>\
					</div>\
					<div class="form-group col-xs-4  margin-left-10px">\
						<select class="form-control styled-select2" name="filterby_' + $count + '" id="filterBy_' + $count + '">\
							<option disabled selected style="display:none;">Filter By</option>\
							<option value="Periodic Servicing">Begins With</option>\
							<option value="Repair">Ends With</option>\
							<option value="Query">Exactly is</option>\
							<option value="Insurance">Like</option>\
						</select>\
					</div>\
					<div class="form-group col-xs-4  margin-left-10px" id="txtSearch_' + $count + '">\
						<input type="text" class="form-control" name="txtsearch_' + $count + '" id="txt-search_' + $count + '" placeholder="Enter Search Text">\
					</div>\
					<div class="form-group col-xs-4  margin-left-10px" id="bikeModel_' + $count + '" style="display:none">\
						<select class="form-control styled-select2" name="bikemodel_' + $count + '" id="bikeModelDD_' + $count + '">\
							<option disabled selected style="display:none;">Bike Model</option>\
							<option value="Periodic Servicing">Hero CBZ</option>\
							<option value="Repair">Hero Karizma</option>\
							<option value="Query">Acheiver</option>\
							<option value="Insurance">Discover</option>\
						</select>\
					</div>\
					<div class="form-group col-xs-4  margin-left-10px" id="oType_1" style="display:none">\
						<select class="form-control styled-select2" name="otype_' + $count + '" id="oTypeDD_' + $count + '">\
							<option disabled selected style="display:none;">Select Order Type</option>\
							<option value="Periodic Servicing">Periodic Servicing</option>\
							<option value="Repair">Repair</option>\
							<option value="Query">Query</option>\
							<option value="Insurance">Insurance Renewal</option>\
						</select>\
					</div>\
					<div class="col-xs-4">\
						<div class="form-group col-xs-6 margin-left-n40">\
							<div class="checkbox dynCB">\
								<label class="ignoreCase">\
									<input type="checkbox" name="" checked value="" style="margin-right:5px">\
									<span style="margin-left:5px">Ignore Case</span>\
								</label>\
							</div>\
						</div>\
						<div class="form-group col-xs-6 date-range-cb">\
							<div class="checkbox dynCB">\
								<label class="dateRangeCB" id="dr_' + $count + '">\
									<input type="checkbox" name="" value="" id="dateRangeCB_' + $count + '" class="drCB" style="margin-right:5px">\
									<span style="margin-left:5px" id="checkMe">Select Date Range</span>\
								</label>\
							</div>\
						</div>\
					</div>\
					<div class="date-range-container" id="dateRangeBox_' + $count + '" style="display:none">\
						<div class="col-xs-4" >\
							<div class="form-group">\
								<p>\
									<input type="text" onchange=""  id="startDate_' + $count + '" class="dpDate3" name="date_" readonly style="cursor:pointer;"placeholder="Start Date">\
								</p>\
							</div>\
						</div>\
						<div class="col-xs-4" >\
							<div class="form-group">\
								<p>\
									<input type="text" onchange=""  id="endDate_' + $count + '" class="dpDate3" name="date_" readonly style="cursor:pointer;"placeholder="End Date">\
								</p>\
							</div>\
						</div>\
					</div>\
				</div>\
			</div>\
			<div class="col-xs-2 field-area-add" id="adv_add"></div>\
		</div>');
		$('.dynCB').each(function() {
			$(this).find('input').icheck({checkboxClass: 'icheckbox_minimal'});
		});
		$(this).remove();
	});
});