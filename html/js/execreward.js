var isClicked = {};
$(function() {
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
	$("input[name='search-filter']").icheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
	$('.odstatusfilter').on("ifChecked", function() {
		var query = $(this).val();
		oTable.fnFilter(query, 0, true, false);
	});
	$('#osclearfilter').on('click', function() {
		for (var i = 1; i <= 2; i++) {
			$('#radio_' + i).icheck('unchecked');
		}
		oTable.fnFilter('', 0, true, false);
	});
	$('#radio_2').trigger('click');
	try {
		$('#dob').datepicker ({
			'dateFormat': "yy-mm-dd",
			'minDate': new Date(1940, 1 - 1, 1),
			'maxDate': new Date(2001, 1 - 1, 1),
			"setDate": new Date(),
			'autoclose': true
		});
	} catch(err) {}
	try {
		var select2a = $('#executive').select2({
			placeholder: "Select Executive",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2a.val(null).trigger("change");
		var select2b = $('#type').select2({
			placeholder: "Select Reward Type",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2b.val(null).trigger("change");
		var select2c = $('#ClearFrequency').select2({
			placeholder: "Clear Frequency",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2c.val('2').trigger("change");
	} catch(err) {}
});
function addReward() {
	var str1 = new Array(4);
	str1[0] = $("#executive").val();
	str1[1] = $("#amount").val();
	str1[2] = $("#type").val();
	str1[3] = $("#description").val();
	str1[4] = $("#ClearFrequency").val();
	var x = 0;
	for (var i = 0; i < str1.length; i++) {
		if(str1[i] == null || str1[i] == undefined || str1[i] == '') {
			x = 1;
		}
	}
	if (str1[1] < 0 || isNaN(str1[1])) {
		x = 1;
	}
	if(x == 0) {
		$("#reward_add").removeAttr('disabled');
	} else {
		$("#reward_add").attr('disabled','disabled');
	}
}
$('#reward_add').on('click', function(e) {
	var executive  = $("#executive").val();
	var oid  = $("#oid").val();
	var amount  = $("#amount").val();
	var type  = $("#type").val();
	var description  = $("#description").val();
	var ClearFrequency  = $("#ClearFrequency").val();
	var form_input_array = ["ExecId", "OId", "Amount", "Type", "Description", "ClearFrequency"];
	var input_objects = {ExecId: executive, OId: oid, Amount: amount, Type: type, Description: description, ClearFrequency: ClearFrequency};
	var form = '<form action="/admin/manageexecutive/create_reward" method="POST">';
	for (i = 0; i < form_input_array.length; i++) {
		form += '<input type="hidden" name="' + form_input_array[i] + '" value="' + input_objects[form_input_array[i]] + '" />';
	}
	form += '<input type="submit" name="reward_submit" value="submit" /></form>';
	var created_form = $(form).appendTo('body'); created_form.submit();
});
$('.dpDate2').datepicker ({
    'dateFormat': "yy-mm-dd",
    "setDate": new Date(),
    'autoclose': true
});
function validate_rewards() {
	var startDate = $("#startDate").val(); var endDate = $("#endDate").val(); var x = 1;
	if(startDate == "" || startDate == null || startDate == undefined || endDate == "" || endDate == null || endDate == undefined) {
		x = 1;
	} else {
		if(new Date(startDate) > new Date(endDate)) {
			x = 1;
		} else {
			x = 0;
		}
	}		
	if(x == 0) {
	    $("#filter").removeAttr('disabled');
	} else {
	    $("#filter").attr('disabled','disabled');
	}
}
function validate_tracking() {
	var startDate = $("#startDate").val(); var endDate = $("#endDate").val(); var x = 1;
	if(startDate == "" || startDate == null || startDate == undefined || endDate == "" || endDate == null || endDate == undefined) {
		x = 1;
	} else {
		if(new Date(startDate) > new Date(endDate)) {
			x = 1;
		} else {
			x = 0;
		}
	}		
	if(x == 0) {
	    $("#filterTracking").removeAttr('disabled');
	} else {
	    $("#filterTracking").attr('disabled','disabled');
	}
}
$("#filter").on('click', function() {
	var startDate = $("#startDate").val(); var endDate = $("#endDate").val();
	if($("#active").val() == 'viewExecutiveRewards') {
		var form = '<form action="/admin/manageexecutive/viewExecutiveRewards" method="POST">';
	} else if($("#active").val() == 'pettyCash') {
		var form = '<form action="/admin/manageexecutive/pettyCash" method="POST">';
	}	
	form += '<input type="hidden" name="startDate" value="' + startDate + '" />';
	form += '<input type="hidden" name="endDate" value="' + endDate + '" />';
	form += '<input type="submit" name="tracking_submit" value="submit" /></form>';
	var created_form = $(form).appendTo('body'); created_form.submit();
});
$("#filterTracking").on('click', function() {
	var startDate = $("#startDate").val(); var endDate = $("#endDate").val();
	var form = '<form action="/admin/orders/executiveOrderTracking" method="POST">';
	form += '<input type="hidden" name="startDate" value="' + startDate + '" />';
	form += '<input type="hidden" name="endDate" value="' + endDate + '" />';
	form += '<input type="submit" name="tracking_submit" value="submit" /></form>';
	var created_form = $(form).appendTo('body'); created_form.submit();
});
$('.clear').on('click', function(e) {
	var elem = $(this); var value = Number(elem.val()); var checked = elem.attr("data-checked");
	isClicked[value] = { "old" : Number(checked), "new" : Number($(this).is(":checked")) }; $("#reward_update").removeAttr('disabled');
});
$('#reward_update').on('click', function(e) {
	var form = '<form action="/admin/manageexecutive/clear_rewards" method="POST">';
	form += '<input type="hidden" name="reward" value="' + encodeURI(JSON.stringify(isClicked)) + '" />';
	form += '<input type="submit" name="reward_submit" value="submit" /></form>';
	var created_form = $(form).appendTo('body'); created_form.submit();
});
$(document).on('ifChanged', 'input[name=reject]', function() {
	var elem = $(this); var pettyCashId = elem.val(); var checked = elem.attr('data-checked');
	if(checked == '1') { elem.icheck('checked'); } else {
		showDenySwal(pettyCashId);
	}
});
$(document).on('ifChanged', 'input[name=approve]', function() {
	var elem = $(this); var pettyCashId = elem.val(); var checked = elem.attr('data-checked');
	if(checked == '1') { elem.icheck('checked'); } else {
		showApproveSwal(pettyCashId);
	}
});
function showApproveSwal(pettyCashId) {
	var currentPage = oTable.api().page.info().page;
	swal({
		title: "Are you sure?",
		text: "You are about to approve this petty cash claim.",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Yes",
		cancelButtonText: "Cancel",
		closeOnConfirm: false,
		closeOnCancel: true,
		showLoaderOnConfirm: true,
		animation: "slide-from-top"
	}, function(isConfirm) {
		if (isConfirm) {
			$.ajax({
				type: "POST",
				url: "/admin/manageexecutive/petty_cash_status",
				data: { "id": pettyCashId, "status": "Approved" }, 
				dataType: "text",
				success: function(data) {
					data = parseInt(data);
					if(data == 1) {
						$('#check_reject_' + pettyCashId).attr("data-checked", '0');
						$('#check_reject_' + pettyCashId).icheck('unchecked');
						$('#check_approve_' + pettyCashId).attr("data-checked", '1');
						$('#search_' + pettyCashId).attr("data-search", '1');
						oTable.fnFilter('', 0, true, false);
						oTable.fnPageChange(currentPage);
						sweetAlert("Success!", "Updated Successfully", "success");
					} else if(data == 0) {
						sweetAlert("Oops!", "Update Failed! Refresh Page & Try Again", "warning");
					}
				},
				error: function() {
					sweetAlert("Oops!", "Update Failed! Refresh Page & Try Again", "warning");
				}
			});
		} else {
			$('#check_approve_' + pettyCashId).attr("data-checked", '0');
			$('#check_approve_' + pettyCashId).icheck('unchecked');
		}
		return true;
	});
}
function showDenySwal(pettyCashId) {
	var currentPage = oTable.api().page.info().page;
	swal({
		title: "Are you sure?",
		text: "You are about to deny this petty cash claim.",
		type: "input",
		showCancelButton: true, 
		closeOnConfirm: false,
		closeOnCancel: true,
		animation: "slide-from-top",
		inputPlaceholder: "Write something",
		showLoaderOnConfirm: true
	}, function(DeniedReason) {
		if (DeniedReason === false) {
			$('#check_reject_' + pettyCashId).attr("data-checked", '0');
			$('#check_reject_' + pettyCashId).icheck('unchecked');
			return false;
		}
		if (DeniedReason === "") {
			swal.showInputError("Please state the reason for denying this petty cash claim.");
			return false;
		} else if (DeniedReason.length < 5) {
			swal.showInputError("The reason should be atleast 5 or more characters.");
			return false;
		} else {
			$.ajax({
				type: "POST",
				url: "/admin/manageexecutive/petty_cash_status",
				data: { "id": pettyCashId, "status": 'Rejected', "rejection_reason": DeniedReason }, 
				dataType: "text",
				success: function(data) {
					data = parseInt(data);
					if(data == 1) {
						$('#check_reject_' + pettyCashId).attr("data-checked", '1');
						$('#check_approve_' + pettyCashId).attr("data-checked", '0');
						$('#check_approve_' + pettyCashId).icheck('unchecked');
						$('#search_' + pettyCashId).attr("data-search", '2');
						oTable.fnFilter('', 0, true, false);
						oTable.fnPageChange(currentPage);
						sweetAlert("Success!", "Updated Successfully", "success");
					} else if(data == 0) {
						sweetAlert("Oops!", "Update Failed! Refresh Page & Try Again", "warning");
					}
				},
				error: function() {
					sweetAlert("Oops!", "Update Failed! Refresh Page & Try Again", "warning");
				}
			});
			return true;
		}
	});
}