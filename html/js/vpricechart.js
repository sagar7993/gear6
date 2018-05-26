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
	$('#srp_can').on('click', function(e) {
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
	var e1 = $("#stype").val();
	var e2 = $("#company").val();
	var e3 = $("input[name='bmodels[]']:checked").val();
	var e4 = $("#sr_price").val();
	var x = 0;
	if(e1 == "" || e2 == "" || typeof e3 === "undefined" || e4 == "" || isNaN(e4)) {
		x = 1;
	}
	if(x == 0) {
		$("#srp_sub").removeAttr('disabled');
	} else {
		$("#srp_sub").attr('disabled','disabled');
	}
}
function checkfield() {
	var e1 = $("#am_id").val();
	var e2 = $("#am_price").val();
	var x = 0;
	if(e1 == "" || e2 == "" || isNaN(e2)) {
		x = 1;
	}
	if(x == 0) {
		$("#amp_sub").removeAttr('disabled');
	} else {
		$("#amp_sub").attr('disabled','disabled');
	}
}