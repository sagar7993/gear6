$(function() {
	$(document).on('ifChecked', '#select_all', function(event) {
		$('.bm').icheck('checked');
	});
	$(document).on('ifUnchecked', '#select_all', function(event) {
		$('.bm').icheck('unchecked');
	});
	$(document).on('ifChanged', '.bm', function() {
		$("#bmSubmit").removeAttr('disabled');
	});
	$(document).on('ifChecked', '.amty', function() {
		var id = $(this).attr('id').split('_')[1];
		$('#amdesc_' + id).attr('readonly', false);
		$('#amdesc_' + id).removeClass('grey-bg');
		$('#amdesc_' + id).focus();
		checkfield();
	});
	$(document).on('ifUnchecked', '.amty', function() {
		var id = $(this).attr('id').split('_')[1];
		$('#amdesc_' + id).attr('readonly', true);
		$('#amdesc_' + id).addClass('grey-bg');
		$('#amdesc_' + id).val('');
		checkfield();
	});
	$(document).on('ifChanged', '.scs', function() {
		$("#serviceSubmit").removeAttr('disabled');
	});
	$('#bmCancel').on('click', function(e) {
		e.preventDefault();
		$('#bike-models').slideUp("slow");
	});
	$('#company').on('change', function() {
		if($('#company').val() != '') {
			var company = $('#company').val();
			$.ajax({
				type: "POST",
				url: "/vendor/profile/scBikeModels",
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
					var bcs = data.bcs;
					var selbcs = data.selbcs;
					if(bcs != null) {
						for (var i = 0; i < bcs.length; i++) {
							if($.inArray(parseInt(bcs[i].BikeModelId), selbcs) > -1) {
								bikelist += '<div class="col-xs-3">\
									<div class="checkbox" style="">\
										<label class="">\
											<input type="checkbox" class="bm" id="addr_' + bcs[i].BikeModelId + '" name="bmodels[]" value="' + bcs[i].BikeModelId + '" checked> <span style="margin-left:5px">' + bcs[i].BikeModelName +'</span>\
										</label>\
									</div>\
								</div>';
							} else {
								bikelist += '<div class="col-xs-3">\
									<div class="checkbox" style="">\
										<label class="">\
											<input type="checkbox" class="bm" id="addr_' + bcs[i].BikeModelId + '" name="bmodels[]" value="' + bcs[i].BikeModelId + '"> <span style="margin-left:5px">' + bcs[i].BikeModelName +'</span>\
										</label>\
									</div>\
								</div>';
							}
						}
					}
					$('#bm_container').html(bikelist);
					$("#bmSubmit").attr('disabled', 'disabled');
					$('.bm').icheck({checkboxClass: 'icheckbox_minimal'});
					$('#bike-models').slideDown("slow");
				}
			});
		} else {
			$('#bike-models').slideUp("slow");
		}
	});
});
function checkfield() {
	var check = true;
	$.each($("input[name='amenity[]']:checked"), function() {
		var id = $(this).attr('id').split('_')[1];
		var am_desc = $('#amdesc_' + id).val();
		if (am_desc == "" || am_desc.length < 5) {
			check = false;
		}
	});
	if (check) {
		$("#amtySubmit").removeAttr('disabled');
	} else {
		$("#amtySubmit").attr('disabled', true);
	}
}