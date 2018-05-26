var parameters = [];
$(function() {
	$("input[type='checkbox'], input[type='radio']").icheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
	$('.parameters').on('ifChecked', function() {
		var elem = $(this); var value = elem.val();
		parameters.push(value);
		validation();
	});
	$('.parameters').on('ifUnchecked', function() {
		var elem = $(this); var value = elem.val();
		parameters = $.grep(parameters, function(val) {
		  return val != value;
		});
		validation();
	});
	$('#select_all').on('ifChecked', function() {
		$('.parameters').icheck('checked');
		validation();
	});
	$('#select_all').on('ifUnchecked', function() {
		$('.parameters').icheck('unchecked');
		validation();
	});
	try {
		var select2a = $('#admin_followup').select2({
			placeholder: "Admin Followup",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2a.val(null).trigger("change");
		var select2b = $('#executive_followup').select2({
			placeholder: "Executive Followup",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2b.val(null).trigger("change");
		var select2c = $('#service_center').select2({
			placeholder: "Service Center",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2c.val(null).trigger("change");
		var select2d = $('#tie_up').select2({
			placeholder: "Tie Up",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2d.val(null).trigger("change");
	} catch(err) {}
});
function validation() {
	if(parameters.length > 0) {
		var admin_followup = $('#admin_followup').val();
		var executive_followup = $('#executive_followup').val();
		var service_center = $('#service_center').val();
		var tie_up = $('#tie_up').val();
		if(admin_followup == "" || admin_followup == null || admin_followup == undefined) { admin_followup = null; }
		if(executive_followup == "" || executive_followup == null || executive_followup == undefined) { executive_followup = null; }
		if(service_center == "" || service_center == null || service_center == undefined) { service_center = null; }
		if(tie_up == "" || tie_up == null || tie_up == undefined) { tie_up = null; }
		$("#getOrderHistoryCSV").removeAttr('disabled');
	} else {
		$("#getOrderHistoryCSV").attr('disabled','disabled');
	}
}