$(function () {
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
	$('.date-field').datepicker ({
		'dateFormat': "yy-mm-dd",
		"setDate": new Date(),
		'autoclose': true
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
	$(document).on('ifChanged', 'input[name=petrolbills_deny]', function() {
		var elem = $(this); var petrolBillId = elem.val(); var checked = elem.attr('data-checked');
		if(checked == '1') { elem.icheck('checked'); } else {
			showDenySwal(petrolBillId);
		}
	});
	$(document).on('ifChanged', 'input[name=petrolbills_approve]', function() {
		var elem = $(this); var petrolBillId = elem.val(); var checked = elem.attr('data-checked');
		if(checked == '1') { elem.icheck('checked'); } else {
			showApproveSwal(petrolBillId);
		}
	});
	var column_data = [null, null,	null, null,	null, null,	null, null, null, null,	null, null, null];
	$('#example1 tfoot th').each( function () {
		var title = $(this).text();
		$(this).html( '<input class="tfoot-search" type="text" placeholder="'+title+'" />' );
	});
	oTable = $('#example1').dataTable({
		bSearchable: true,
		bSortable: true,
		bInfo: true,
		bLengthChange: true,
		bPaginate: true,
		bFilter: true,
		aaSorting: [],
		"oLanguage": {
			"sEmptyTable": "No data available for your query.",
			"sSearch": ""
		},
		"dom": 'Bfrtip',
		"columns": column_data
	});
	oTable.api().columns().every( function () {
		var that = this;
		$('input', this.footer()).on('keyup change', function () {
			if (that.search() !== this.value) {
				that.search(this.value).draw();
			}
		});
	});
	$('#example1').css('width', '100%');
	$('#example1').css('overflow-x', 'scroll');
	$('.tfoot-search').css('width', '100px');
	$('.tfoot-search').css('align', 'left');
});
function showApproveSwal(petrolBillId) {
	var currentPage = oTable.api().page.info().page;
	swal({
		title: "Are you sure?",
		text: "You are about to approve this petrol claim.",
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
				url: "/admin/manageexecutive/petrol_claim_status",
				data: { "PetrolBillsId": petrolBillId, "IsApproved": 1 }, 
				dataType: "text",
				success: function(data) {
					data = parseInt(data);
					if(data == 1) {
						$('#check_deny_' + petrolBillId).attr("data-checked", '0');
						$('#check_deny_' + petrolBillId).icheck('unchecked');
						$('#check_approve_' + petrolBillId).attr("data-checked", '1');
						$('#search_' + petrolBillId).attr("data-search", '1');
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
			$('#check_approve_' + petrolBillId).attr("data-checked", '0');
			$('#check_approve_' + petrolBillId).icheck('unchecked');
		}
		return true;
	});
}
function showDenySwal(petrolBillId) {
	var currentPage = oTable.api().page.info().page;
	swal({
		title: "Are you sure?",
		text: "You are about to deny this petrol claim.",
		type: "input",
		showCancelButton: true, 
		closeOnConfirm: false,
		closeOnCancel: true,
		animation: "slide-from-top",
		inputPlaceholder: "Write something",
		showLoaderOnConfirm: true
	}, function(DeniedReason) {
		if (DeniedReason === false) {
			$('#check_deny_' + petrolBillId).attr("data-checked", '0');
			$('#check_deny_' + petrolBillId).icheck('unchecked');
			return false;
		}
		if (DeniedReason === "") {
			swal.showInputError("Please state the reason for denying this petrol claim.");
			return false;
		} else if (DeniedReason.length < 5) {
			swal.showInputError("The reason should be atleast 5 or more characters.");
			return false;
		} else {
			$.ajax({
				type: "POST",
				url: "/admin/manageexecutive/petrol_claim_status",
				data: { "PetrolBillsId": petrolBillId, "IsApproved": 2, "DeniedReason": DeniedReason }, 
				dataType: "text",
				success: function(data) {
					data = parseInt(data);
					if(data == 1) {
						$('#check_deny_' + petrolBillId).attr("data-checked", '1');
						$('#check_approve_' + petrolBillId).attr("data-checked", '0');
						$('#check_approve_' + petrolBillId).icheck('unchecked');
						$('#search_' + petrolBillId).attr("data-search", '2');
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
function checkfield() {
	var x = 0;
	var startDate = $('#startDate').val();
	var endDate = $('#endDate').val();
	if(startDate === "" || startDate == "" || startDate === null || startDate == null) {
		x = 1;
	}
	if(endDate === "" || endDate == "" || endDate === null || endDate == null) {
		x = 1;
	}
	if(x == 0) {
		$("#search").removeAttr('disabled');
	} else {
		$("#search").attr('disabled','disabled');
	}
}
$('#search').on('click', function(e) {
	var form = '<form action="/admin/manageexecutive/petrol_claims" method="POST">';
	form += '<input type="hidden" name="startDate" value="' + $('#startDate').val() + '" />';
	form += '<input type="hidden" name="endDate" value="' + $('#endDate').val() + '" />';
	form += '<input type="submit" name="admin_submit" value="submit" /></form>';
	var created_form = $(form).appendTo('body'); created_form.submit();
});