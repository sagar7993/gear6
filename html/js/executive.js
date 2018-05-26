var valid_mimes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
$(function() {
	check_the_checked_checklist();
	check_the_checked_bikeparts();
	$('#ex_phone').blur();
	$('#ex_password').blur();
	$('#jc_update').on('click', function(e) {
		e.preventDefault();
		$('#jc_update').hide();
		$('#jclistupld_btncont').append('<div class="preloader-wrapper small active center" id="jclist_mat_loader"><div class="spinner-layer spinner-blue-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div>');
		$.ajax({
			type: "POST",
			url: "/executive/exechome/updt_jobcard",
			data: $('#exjcform').serialize(), 
			dataType: "text",
			success: function(data) {
				data = parseInt(data);
				if(data == 1) {
					Materialize.toast('Updated Successfully.', 3000);
				} else if(data == 0) {
					Materialize.toast('Update failed. Check connection!', 5000);
				}
				$('#jclist_mat_loader').remove();
				$('#jc_update').show();
			},
			error: function() {
				Materialize.toast('Update failed. Check connection!', 5000);
				$('#jclist_mat_loader').remove();
				$('#jc_update').show();
			}
		});
	});
	$('#pbclaim_update').on('click', function(e) {
		e.preventDefault();
		$('#pbclaim_update').hide();
		$('#pbclaimupdt_cont').append('<div class="preloader-wrapper small active center" id="pbclaimupdt_loader"><div class="spinner-layer spinner-blue-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div>');
		$.ajax({
			type: "POST",
			url: "/executive/exechome/updt_pbclaim",
			data: $('#pbclaimform').serialize(), 
			dataType: "json",
			success: function(data) {
				data.status = parseInt(data.status);
				if(data.status == 1) {
					Materialize.toast('Updated Successfully.', 3000);
					$('#pbclaimupdt_cont').parents('form')[0].reset();
				} else if(data.status == 0) {
					Materialize.toast('All fields are required', 5000);
				}
				populate_pbill_tabledata(data.pbills);
				$('#pbclaimupdt_loader').remove();
				$('#pbclaim_update').show();
			},
			error: function() {
				Materialize.toast('Update failed. Check connection!', 5000);
				$('#pbclaimupdt_loader').remove();
				$('#pbclaim_update').show();
			}
		});
	});
	$('#ex_chk_claims').on('click', function(e) {
		e.preventDefault();
		var sdate = $('#pbillsdate').val();
		var edate = $('#pbilledate').val();
		$('#ex_chk_claims').hide();
		$('#exchk_claims_content').append('<div class="preloader-wrapper small active center" id="exchk_claims_loader"><div class="spinner-layer spinner-blue-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div>');
		$.ajax({
			type: "POST",
			url: "/executive/exechome/fetch_pbill_by_dates",
			data: {sdate: sdate, edate: edate}, 
			dataType: "json",
			success: function(data) {
				if(data.status == 1) {
					Materialize.toast('Fetched Successfully.', 3000);
				} else if(data.status == 0) {
					Materialize.toast('Fetch failed. Check connection!', 5000);
				}
				populate_pbill_tabledata(data.pbills);
				$('#exchk_claims_loader').remove();
				$('#ex_chk_claims').show();
			},
			error: function() {
				Materialize.toast('Update failed. Check connection!', 5000);
				$('#exchk_claims_loader').remove();
				$('#ex_chk_claims').show();
			}
		});
	});
	$('#ex_login').on('click', function(e) {
		e.preventDefault();
		$('#ex_login').hide();
		var phone = $('#ex_phone').val();
		var password = $('#ex_password').val();
		$.ajax({
			type: "POST",
			url: "/executive/exechome/elogin",
			data: {phone: phone, password: password}, 
			dataType: "text",
			cache: false,
			success: function(data) {
				data = parseInt(data);
				if(data == 1) {
					location.reload();
				} else if(data == 2) {
					$('#ex_login').show('slow');
					$('#err_message').html('Your Executive Account is De-Activated');
				} else {
					$('#ex_login').show('slow');
					$('#err_message').html('Invalid Login Details');
				}
			},
			error: function() {
				$('#err_message').html('Login Failed. Check connection!');
				$('#ex_login').show('slow');
			}
		});
	});
	$('.execlchecks').on('change', function() {
		var current_cb = $(this);
		var checked_vals = $(".execlchecks:checked").map(function() {
			return $(this).val();
		}).get();
		var checkedstr = checked_vals.join("||");
		$.ajax({
			type: "POST",
			url: "/executive/exechome/updt_checklist",
			data: {execlvals: checkedstr, oid: univ_order_id}, 
			dataType: "text",
			cache: false,
			error: function() {
				Materialize.toast('Update failed. Check connection!', 5000);
				if (current_cb.is(':checked')) {
					current_cb.prop("checked", false);
				} else {
					current_cb.prop("checked", true);
				}
			},
			success: function(data) {
				data = parseInt(data);
				if(data == 0) {
					Materialize.toast('Update failed. Check connection!', 5000);
					if (current_cb.is(':checked')) {
						current_cb.attr("checked", true);
					} else {
						current_cb.attr("checked", false);
					}
				} else if(data == 1) {
					Materialize.toast('Checklist updated successfully.', 3000);
				}
			}
		});
	});
	$('#ex_jcimg_upload').on('click', function(e) {
		e.preventDefault();
		$('#ex_jcimg_upload').hide();
		$('#jcimgupld_btncont').append('<div class="preloader-wrapper small active center" id="img_mat_loader"><div class="spinner-layer spinner-blue-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div>');
		if(checkMediaValidity()) {
			var formData = new FormData($('#jc_image').parents('form')[0]);
			 $.ajax({
				type: 'post',
				url: '/executive/exechome/jcimg_upload',
				data: formData,
				dataType: 'json',
				success: function (data) {
					if(data.status == 0) {
						Materialize.toast('Image upload failed. Please try again.', 5000);
					} else if(data.status == 1) {
						var el = $('<div class="col s6"><img class="materialboxed" width="auto" height="100px" src="' + data.imgurl + '"></div>');
						$('#uploaded_jcimages').append(el);
						el.find('.materialboxed').materialbox();
						Materialize.toast('Image uploaded successfully.', 3000);
						$('#jc_image').parents('form')[0].reset();
					}
					$('#img_mat_loader').remove();
					$('#ex_jcimg_upload').show();
				},
				error: function() {
					Materialize.toast('Image upload failed. Please try again.', 5000);
					$('#img_mat_loader').remove();
					$('#ex_jcimg_upload').show();
				},
				cache: false,
				processData: false,
				contentType: false
			});
		} else {
			$('#img_mat_loader').remove();
			$('#ex_jcimg_upload').show();
			Materialize.toast('Upload a valid image file.', 5000);
		}
	});
});
function checkMediaValidity() {
	if($('#jc_image').val()) {
		if(($('#jc_image')[0].files[0].size) / (1024 * 1024) > 5 || $.inArray($('#jc_image')[0].files[0].type, valid_mimes) == -1) {
			return false;
		}
		return true;
	} else {
		return false;
	}
}
function check_the_checked_checklist() {
	if(typeof execlscatsckd !== "undefined") {
		var arr_length = execlscatsckd.length;
		for(var i = 0; i < arr_length; i++) {
			$('#execl_' + execlscatsckd[i]).prop('checked', true);
		}
	}
}
function check_the_checked_bikeparts() {
	if(typeof bikepartsckd !== "undefined") {
		var arr_length = bikepartsckd.length;
		for(var i = 0; i < arr_length; i++) {
			$('#bpn_' + bikepartsckd[i]).prop('checked', true);
		}
	}
}
function populate_pbill_tabledata(pbills) {
	var retreived_data = '';
	if(pbills !== null) {
		var arrlength = pbills.length;
		for(var i = 0; i < arrlength; i++) {
			retreived_data += '<tr>';
			retreived_data += '<td>' + pbills[i].SLocation + '</td>';
			retreived_data += '<td>' + pbills[i].ELocation + '</td>';
			retreived_data += '<td>' + pbills[i].Kms + '</td>';
			retreived_data += '<td>' + pbills[i].Date + '</td>';
			retreived_data += '<td>' + pbills[i].Purpose + '</td>';
			retreived_data += '<td>' + pbills[i].isApproved + '</td>';
			retreived_data += '</tr>';
		}
	} else {
		retreived_data += '<tr><td colspan="6" style="text-align:center;">No data available</td></tr>';
	}
	$('#pbillquerydata').html(retreived_data);
}