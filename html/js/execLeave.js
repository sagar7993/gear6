$(function() {
	$("input[type='radio']").icheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
	$('.odstatusfilter').on("ifChecked", function() {
		var query = $(this).val();
		oTable.fnFilter(query, 6, true, false);
	});
	$('#osclearfilter').on('click', function() {
		for (var i = 1; i <= 2; i++) {
			$('#radio_' + i).icheck('unchecked');
		}
		oTable.fnFilter('', 6, true, false);
	});
	$(document).on('ifChanged', 'input[name=approved]', function() {
		var elem = $(this); var execLeaveId = elem.val(); var checked = elem.attr('data-checked');
		if(checked == '1') { elem.icheck('checked'); } else {
			showApproveSwal(execLeaveId);
		}
	});
	$(document).on('ifChanged', 'input[name=rejected]', function() {
		var elem = $(this); var execLeaveId = elem.val(); var checked = elem.attr('data-checked');
		if(checked == '1') { elem.icheck('checked'); } else {
			showDenySwal(execLeaveId);
		}
	});
	function showApproveSwal(execLeaveId) {
		swal({
			title: "Are you sure?",
			text: "You are about to approve this leave request.",
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
			    var form = '<form action="/admin/manageexecutive/change_leave_status" method="POST">';
			    form += '<input type="hidden" name="id" value="' + execLeaveId + '" />';
			    form += '<input type="hidden" name="status" value="Approved" />';
			    form += '<input type="submit" name="admin_submit" value="submit" /></form>';
			    var created_form = $(form).appendTo('body'); created_form.submit();
			} else {
				$('#check_approve_' + execLeaveId).icheck('unchecked');
			}
		});
	}
	function showDenySwal(execLeaveId) {
		swal({
			title: "Are you sure?",
			text: "You are about to reject this leave request.",
			type: "input",
			showCancelButton: true, 
			closeOnConfirm: false,
			closeOnCancel: true,
			animation: "slide-from-top",
			inputPlaceholder: "Write something"
		}, function(reason) {
			if (reason === false) {
				$('#check_reject_' + execLeaveId).icheck('unchecked');
				return false;
			}
			if (reason === "") {
				swal.showInputError("Please state the reason for denying this petrol claim.");
				return false;
			}
			if (reason.length < 10) {
				swal.showInputError("The reason should be atleast 10 or more characters.");
				return false;
			}
		    var form = '<form action="/admin/manageexecutive/change_leave_status" method="POST">';
		    form += '<input type="hidden" name="id" value="' + execLeaveId + '" />';
		    form += '<input type="hidden" name="status" value="Rejected" />';
		    form += '<input type="hidden" name="reason" value="' + reason + '" />';
		    form += '<input type="submit" name="admin_submit" value="submit" /></form>';
		    var created_form = $(form).appendTo('body'); created_form.submit();
		});
	}
});