$(function () {
	locationsPopulate();
	$(document).on('click','.area_edit',function(){
		$id = $(this).attr('id').split('_')[1];
		$('#area'+$id+'_name').attr('readonly',false );
		$('#area'+$id+'_price').attr('readonly',false );
		$('#area'+$id+'_name').removeClass('grey-bg');
		$('#area'+$id+'_price').removeClass('grey-bg');
		$('#area'+$id+'_type').attr('disabled', false);
		$('#area'+$id+'_type').removeClass('grey-bg');
		$('#area'+$id+'_name').focus();
	});
	$(document).on('click','.rad_edit',function(){
		$id = $(this).attr('id').split('_')[2];
		$('#rad'+$id+'_price').attr('readonly',false );
		$('#to_val_'+$id).attr('disabled',false );
		$('#opt_type_'+$id).attr('disabled',false );
		$('#rad'+$id+'_price').removeClass('grey-bg');
		$('#to_val_'+$id).removeClass('grey-bg');
		$('#opt_type_'+$id).removeClass('grey-bg');
		$('#rad'+$id+'_price').focus();
	});
	$('#rad_edit_last').on('click', function () {
		$('#rad_last_price').attr('readonly',false);
		$('#rad_last_price').removeClass('grey-bg');
	});
	$.each($('.secret_end_distance_select'), function() {
		$(this).next().val($(this).val());
	});
	$(document).on('change', '.to-val', function() {
		$id = parseInt($(this).attr('id').split('_')[2]);
		if(parseInt($(this).val()) >= 20) {
			$(this).val(20);
			$('#rad_add').remove();
			$('#rad_edit_last').hide();
			$.each($('.rad_edit'), function() {
				$temp_id = parseInt($(this).attr('id').split('_')[2]);
				if($temp_id > $id) {
					$(this).parent().remove();
				}
			});
			$('#to_val_last').val(20);
			$('#to_val_last').addClass('grey-bg');
			$('#to_val_last').attr('disabled', true);
			$('#rad_last_price').attr('readonly',false);
			$('#rad_last_price').removeClass('grey-bg');
			$('#rad_last_price').focus();
		} else if(parseInt($(this).val()) <= parseInt($('#rad'+$id+'_kms').val())) {
			$.each($('.rad_edit'), function() {
				$temp_id = parseInt($(this).attr('id').split('_')[2]);
				if($temp_id > $id) {
					$(this).parent().remove();
				}
			});
			$(this).val(parseInt($('#rad'+$id+'_kms').val()) + 1);
			$('#to_val_last').val(parseInt($('#rad'+$id+'_kms').val()) + 1);
			$('#rad'+($id + 1)+'_kms').val($(this).val());
			if($('#rad_add').length) {
			} else {
				$('#rad_edit_' + $id).after('<div class="col-xs-2 field-area-add" id="rad_add"></div>');
			}
			$('#rad_edit_last').show();
		} else {
			$('#rad'+($id + 1)+'_kms').val($(this).val());
			$('#to_val_last').val(parseInt($(this).val()));
			if($('#rad_add').length) {
			} else {
				$('#rad_edit_' + $id).after('<div class="col-xs-2 field-area-add" id="rad_add"></div>');
			}
			$('#rad_edit_last').show();
		}
		checkfield1();
	});
	$(document).on('click','#area_add',function() {
		$count = 1;
		$('.loc-container').each(function() {
			$count = $count + 1;
		});
		$('.area-box').append('<div class="col-xs-12 area-container loc-container">\
			<div class="col-xs-4">\
				<input type="text" class="col-xs-6 form-control grey-bg location" readonly name="area'+$count+'_name" id="area'+$count+'_name" placeholder="Area Location">\
			</div>\
			<div class="col-xs-2">\
				<select class="form-control styled-select2 grey-bg" onchange="checkfield();" disabled name="area'+$count+'_type" id="area'+$count+'_type">\
					<option value="pick">PickUp</option>\
					<option value="drop">Drop</option>\
					<option selected value="both">PickUp &amp; Drop</option>\
				</select>\
			</div>\
			<div class="col-xs-3">\
				<input type="text" class="col-xs-10 form-control grey-bg" readonly name="area'+$count+'_price" id="area'+$count+'_price" oninput="checkfield();" placeholder="Price in INR">\
			</div>\
			<div class="col-xs-2 field-area-edit area_edit" id="area_'+$count+'_edit"></div>\
			<div class="col-xs-2 field-area-add" id="area_add"></div>\
		</div>');
		$(this).remove();
		locationsPopulate();
	});
	$(document).on('click','#rad_add',function() {
		$count = 1;
		$('.rad-container').each(function() {
			$count = $count + 1;
		});
		if(parseInt($("#to_val_"+($count - 1)).val()) < 20) {
			$('.last-box').before('<div class="col-xs-12 area-container rad-container">\
				<div class="col-xs-1">\
					<input type="text" class="col-xs-6 form-control grey-bg from-val" readonly name="rad'+$count+'_kms" id="rad'+$count+'_kms" placeholder="kms" value="'+$("#to_val_"+($count - 1)).val()+'">\
				</div>\
				<div class="col-xs-2">\
					<div class="radius-from col-xs-2">to</div>\
					<div class="col-xs-8">\
						<select class="form-control styled-select2 to-val grey-bg" disabled id="to_val_'+$count+'" name="to_val_'+$count+'">\
							<option value="1">1</option>\
							<option value="2">2</option>\
							<option value="3">3</option>\
							<option value="4">4</option>\
							<option value="5">5</option>\
							<option value="6">6</option>\
							<option value="7">7</option>\
							<option value="8">8</option>\
							<option value="9">9</option>\
							<option value="10">10</option>\
							<option value="11">11</option>\
							<option value="12">12</option>\
							<option value="13">13</option>\
							<option value="14">14</option>\
							<option value="15">15</option>\
							<option value="16">16</option>\
							<option value="17">17</option>\
							<option value="18">18</option>\
							<option value="19">19</option>\
							<option value="20">20</option>\
						</select>\
					</div>\
				</div>\
				<div class="col-xs-4 width29">\
					<div class="radius-from">kms&nbsp;&nbsp;&nbsp;Radius From :</div>\
					<div class="radius-from-location">Marathahalli</div>\
				</div>\
				<div class="col-xs-3">\
					<input type="text" class="col-xs-6 form-control grey-bg" oninput="checkfield1();" readonly name="rad'+$count+'_price" id="rad'+$count+'_price" placeholder="Price in INR">\
				</div>\
				<div class="col-xs-2 field-area-edit rad_edit" id="rad_edit_'+$count+'"></div>\
				<div class="col-xs-2 field-area-add" id="rad_add"></div>\
			</div>');
			$('#to_val_'+$count).val(parseInt($('#rad'+$count+'_kms').val())+1);
			$('#to_val_last').val($('#to_val_'+$count).val());
		}
		$(this).remove();
		checkfield1();
	});
	$('#apupdate').on('click', function() {
		var area_names = new Array();
		var area_prices = new Array();
		var atypes = new Array();
		$.each($('.loc-container'), function(index, value) {
			var area_name = $('#area' + (index + 1) + '_name').val();
			var area_price = $('#area' + (index + 1) + '_price').val();
			var area_type = $('#area' + (index + 1) + '_type').val();
			if (area_name != "" && area_price != "" && area_type != "") {
				area_names.push(area_name);
				area_prices.push(area_price);
				atypes.push(area_type);
			}
		});
		area_names = area_names.toString();
		area_prices = area_prices.toString();
		atypes = atypes.toString();
		var form = '<form method="POST" action="/vendor/profile/apPrices/"><input name="anames" value="' + area_names + '" /><input name="aprices" value="' + area_prices + '" /><input name="atypes" value="' + atypes + '" /><input type="submit" name="apupdate" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
	$('#rpupdate').on('click', function() {
		var rad_froms = new Array();
		var rad_tos = new Array();
		var rad_prices = new Array();
		var rad_type = $('#type_for_radii').val();
		var lfrom = $('#to_val_last').val();
		var lprice = $('#rad_last_price').val();
		$.each($('.rad-container'), function(index, value) {
			var rad_from = $('#rad' + (index + 1) + '_kms').val();
			var rad_to = $('#to_val_' + (index + 1)).val();
			var rad_price = $('#rad' + (index + 1) + '_price').val();
			if (rad_from != "" && rad_to != "" && rad_price != "") {
				rad_froms.push(rad_from);
				rad_tos.push(rad_to);
				rad_prices.push(rad_price);
			}
		});
		rad_froms = rad_froms.toString();
		rad_tos = rad_tos.toString();
		rad_prices = rad_prices.toString();
		var form = '<form method="POST" action="/vendor/profile/rpPrices/"><input name="radfroms" value="' + rad_froms + '" /><input name="radtos" value="' + rad_tos + '" /><input name="rprices" value="' + rad_prices + '" /><input name="rtype" value="' + rad_type + '" /><input name="lfrom" value="' + lfrom + '" /><input name="lprice" value="' + lprice + '" /><input type="submit" name="rpupdate" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
});
function checkfield() {
	var check = true;
	var isonefieldfull = false;
	var ispricevalid = true;
	$.each($('.loc-container'), function(index, value) {
		var area_name = $('#area' + (index + 1) + '_name').val();
		var area_price = $('#area' + (index + 1) + '_price').val();
		var area_type = $('#area' + (index + 1) + '_type').val();
		if (area_name != "" && area_price != "") {
			isonefieldfull = true;
			if(!isValidLocation(area_name)) {
				check = false;
			}
			if (isNaN(area_price)) {
				ispricevalid = false;
			}
		} else if(area_name == "" && area_price == "") {
		} else {
			check = false;
		}
	});
	if (check && isonefieldfull && ispricevalid) {
		$("#apupdate").removeAttr('disabled');
	} else {
		$("#apupdate").attr('disabled', true);
	}
}
function checkfield1() {
	var check = true;
	$.each($('.rad-container'), function(index, value) {
		var rad_price = $('#rad' + (index + 1) + '_price').val();
		if (rad_price == "") {
			check = false;
		}
		if (isNaN(rad_price)) {
			check = false;
		}
	});
	var rad_last_price = $('#rad_last_price').val();
	if (rad_last_price == "") {
		check = false;
	}
	if (isNaN(rad_last_price)) {
		check = false;
	}
	if (check) {
		$("#rpupdate").removeAttr('disabled');
	} else {
		$("#rpupdate").attr('disabled', true);
	}
}
function isValidLocation(code) {
	return ($.inArray(code, availableLocations) > -1);
}
function locationsPopulate() {
	$(".location").autocomplete ({
		source: availableLocations,
		select: function (a, b) {
			$(this).val(b.item.value);
			checkfield();
		}
	});
}