$(document).ready(function() {
	$('#dontsendsms').on('ifChecked', function() {
		$('.sendsmsclass').val('0');
		send_sms_flag = 0;
	});
	$('#dontsendsms').on('ifUnchecked', function() {
		$('.sendsmsclass').val('1');
		send_sms_flag = 1;
	});
	enablePriceAdditions();
	enableStatusChanges();
	enableAutoField(1);
	$('#cf_price').on('focus', function() {
		var kms = parseFloat($('#cf_kms').val());
		var mins = parseInt($('#cf_mins').val());
		var serid = parseInt($('#cf_serid').val());
		var total_cf = 0;
		var basecharge = 0;
		if(kms != '' && kms > 0) {
			if(mins != '' && mins > 0) {
				basecharge = 200;
			} else {
				if(serid == 1 || serid == 2) {
					basecharge += 75.00;
				} else if (serid == 4) {
					basecharge += 0.00;
				}
			}
			total_cf += basecharge;
			var loop_check = true;
			if(mins != '' && mins > 0) {
				while(loop_check) {
					if(kms <= 2 && mins <= 60) {
						$('#cf_price').val(total_cf.toFixed(2));
						loop_check = false;
					}
					if(kms > 2) {
						total_cf += (kms - 2) * 20;
						kms = 2;
					}
					if(mins > 60) {
						total_cf += (mins - 60) * 2;
						mins = 60;
					}
				}
			} else {
				while(loop_check) {
					if(kms <= 3) {
						if(serid == 4) {
							total_cf = 150.00;
							var cf = total_cf.toFixed(2);
						} else {
							var cf = total_cf.toFixed(2);
						}
						$('#cf_price').val(cf);
						loop_check = false;
					} else if(kms > 3 && kms <= 12) {
						if(serid == 1 || serid == 2) {
							total_cf += (kms - 3) * 5;
						}
						kms = 3;
					} else if(kms > 12 && kms <= 20) {
						if(serid == 1 || serid == 2) {
							total_cf += (kms - 12) * 7.5;
						}
						kms = 12;
					} else if(kms > 20 && kms <= 25) {
						if(serid == 1 || serid == 2) {
							total_cf += (kms - 20) * 10;
						}
						kms = 20;
					} else if(kms > 25 && kms <= 30) {
						if(serid == 1 || serid == 2) {
							total_cf += (kms - 25) * 13;
						}
						kms = 25;
					} else if(kms > 30) {
						if(serid == 1 || serid == 2) {
							total_cf += (kms - 30) * 15;
						}
						kms = 30;
					}
				}
			}
		} else {
			$('#cf_price').val('0');
		}
	});
	$('.desc_txt_edit').on('click', function() {
		$(this).parent().find('#desc_txt').attr('readonly', false);
		$(this).parent().find('#desc_txt').removeClass('grey-bg');
		$(this).parent().find('#desc_txt').focus();
	});
	$('.stat_desc_c').on('input', function() {
		var scenter_id = $(this).attr('id').split('_')[3];
		checkfield(true, scenter_id);
	});
	$('.edit_oprice').on('click', function() {
		var opid = $(this).data("id");
		var opdesc = $(this).data("opdesc");
		var oprice = $(this).data("oprice");
		var ptype = $(this).data("type");
		$('#op_opid').val(opid);
		$('#op_opdesc').val(opdesc);
		$('#op_oprice').val(oprice);
		$('#p_ptype').val(ptype);
		$('#oprice_edit_block').show('slow');
		$('html, body').animate({
			scrollTop: $($('#oprice_edit_block')).offset().top
		}, 'slow');
	});
	$('.edit_aprice').on('click', function() {
		var apid = $(this).data("id");
		var apdesc = $(this).data("apdesc");
		var aprice = $(this).data("aprice");
		var ptype = $(this).data("type");
		var ttype = $(this).data("ttype");
		$('#op_opid').val(apid);
		$('#op_opdesc').val(apdesc);
		$('#op_oprice').val(aprice);
		$('#p_ptype').val(ptype);
		$('#p_ttype').val(ttype);
		$('#oprice_edit_block').show('slow');
		$('html, body').animate({
			scrollTop: $($('#oprice_edit_block')).offset().top
		}, 'slow');
	});
	$(document).on('click', '#price_add', function() {
		$count = 1;
		$('.spdetail').each(function() {
			$count = $count + 1;
		});
		$('.price-list-container').append('<div class="col-xs-12 price-container"><div class="form-group col-xs-5 width50"><input type="text" class="form-control spdetail" oninput="checkPriceDetails();" name="" id="spdetail'+$count+'" placeholder="Service Detail"></div><div class="form-group col-xs-4 width25 margin-left-n25"><input type="text" class="form-control" oninput="checkPriceDetails();" name="" id="sp'+$count+'" placeholder="Price Detail - eg. 350"></div>\
			<div class="form-group col-xs-4 width25">\
				<select class="form-control spttype" name="" id="spttype'+$count+'">\
					<option value="0">No Tax</option>\
					<option value="1">Service Tax</option>\
					<option value="2">VAT</option>\
					<option value="3">Discount</option>\
				</select>\
			</div>\
		<div class="col-xs-2 field-area-add" id="price_add"></div></div>');
		enableAutoField($count);
		$(this).remove();
	});
	$('#priceupdate').on('click', function() {
		var spdetails = new Array();
		var sps = new Array();
		var spttypes = new Array();
		$.each($('.price-container'), function(index, value) {
			var spdetail = $('#spdetail' + (index + 1)).val();
			var sp = $('#sp' + (index + 1)).val();
			var spttype = $('#spttype' + (index + 1)).val();
			if (spdetail != "" && sp != "" && spttype != "") {
				spdetails.push(spdetail);
				sps.push(sp);
				spttypes.push(spttype);
			}
		});
		spdetails = spdetails.join('||');
		sps = sps.join('||');
		spttypes = spttypes.join('||');
		var form = '<form method="POST" action="/admin/orders/updatePrices/"><input name="oid" value="' + univ_order_id + '" /><input name="sc_id" value="' + univ_sc_id + '" /><input name="spdetails" value="' + spdetails + '" /><input name="sps" value="' + sps + '" /><input name="spttypes" value="' + spttypes + '" /><input type="submit" name="priceupdate" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
	function enablePriceAdditions() {
		if(true || check_status_for_price == '4' || check_status_for_price == '10' || check_status_for_price == '25') {
			$('.price-list-container').show();
			$('#price-list-submit').show();
		}
	}
	function enableStatusChanges() {
		$('.stype-vendor').each(function() {
			var stypeval = $(this).val();
			var scenter_id = $(this).attr('id').split('-')[2];
			if(stypeval == '4' || stypeval == '10' || stypeval == '16' || stypeval == '25') {
				$('#stat_txt_optional_' + scenter_id).hide();
				$('#stat_txt_mandatory_' + scenter_id).show();
				if(stypeval != '16') {
					$('#desc_txt_mandatory_' + scenter_id).val($('#desc_txt_mandatory_' + scenter_id).attr('placeholder'));
				}
				checkfield(true, scenter_id);
			} else {
				$('#stat_txt_optional_' + scenter_id).show();
				$('#stat_txt_mandatory_' + scenter_id).hide();
				$('#desc_txt_mandatory_' + scenter_id).val("");
				checkfield(false, scenter_id);
			}
		});
	}
});
$('#cancel_order_href').on('click', function() {
	swal({
		title: "Are you sure?",
		text: "You are about to delete this order.",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Yes",
		cancelButtonText: "Cancel",
		closeOnConfirm: false,
		closeOnCancel: true,
		animation: "slide-from-top",
	}, function(isConfirm) {
		if (isConfirm) {
		    var cancel_url = $('#cancel_order_href').attr("data-cancel");
		    window.location.assign(cancel_url + '/' + send_sms_flag);
		} else {
			swal("Operation Aborted.", "You have cancelled this operation", "warning");
		}
	});
});
$('#send_invoice_href').on('click', function() {
	swal({
		title: "Are you sure?",
		text: "You are about to send the invoice to the user.",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Yes",
		cancelButtonText: "Cancel",
		closeOnConfirm: false,
		closeOnCancel: true,
		animation: "slide-from-top",
	}, function(isConfirm) {
		if (isConfirm) {
		    var send_invoice_url = $('#send_invoice_href').attr("data-send-invoice");
		    window.location.assign(send_invoice_url);
		} else {
			swal("Operation Aborted.", "You have cancelled this operation", "warning");
		}
	});
});
function enableAutoField(field_id) {
	$("#spdetail" + field_id).autocomplete ({
		source: function(request, response) {
			$.ajax({
				type: "POST",
				url: "/admin/orders/get_aprice_guesses",
				data: {query: request.term},
				dataType: "json",
				success: function (data) {
					response(data);
				}
			});
		},
		select: function (a, b) {
			$("#spdetail" + field_id).val(b.item.value);
		}
	});
}
function checkfield(iscompul, sc_id) {
	var str1 = new Array();
	str1[0] = $('#stype-vendor-' + sc_id).val();
	if(iscompul === true) {
		str1[1] = $('#desc_txt_mandatory_' + sc_id).val();
	}
	var x = 0;
	for (i = 0; i < str1.length; i++) {
		if (str1[i] == "" || str1[i] == undefined || str1[i] == null) {
			x = 1;
		}
	}
	if (x == 0) {
		$('#changeStatus_' + sc_id).removeAttr('disabled');
	} else {
		$('#changeStatus_' + sc_id).attr('disabled', true);
	}
}
function checkPriceDetails() {
	var check = true;
	var isonefieldfull = false;
	var ispricevalid = true;
	$('.price-container').each(function(index) {
		var spdetail = $('#spdetail' + (index + 1)).val();
		var sp = $('#sp' + (index + 1)).val();
		if (spdetail != "" && sp != "") {
			isonefieldfull = true;
		}
		if (isNaN(sp)) {
			ispricevalid = false;
		}
		if((spdetail != "" && sp == "") || (spdetail == "" && sp != "")) {
			check = false;
		}
	});
	if (check && isonefieldfull && ispricevalid) {
		$("#priceupdate").removeAttr('disabled');
	} else {
		$("#priceupdate").attr('disabled', true);
	}
}