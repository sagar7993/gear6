$(function() {
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
	try {
		$('#reminder_date').datepicker ({
			'dateFormat': "yy-mm-dd",
			"setDate": new Date(),
			'autoclose': true
		});
		$('#reminder_time').timepicker({timeFormat: 'HH:mm:ss'});
	} catch(err) {}
	try {
		var select2a = $('#send_sms').select2({
			placeholder: "Send SMS",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2a.val(null).trigger("change");
	} catch(err) {}
	try {
		var select2b = $('#user_type').select2({
			placeholder: "User Type",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2b.val(null).trigger("change");
	} catch(err) {}
	try {
		var data = "";
		if($("#user_type").val() == "Admin") {
			for (var i = 0; i < admin.length; i++) {
				data += "<option value='" + admin[i]["AdminId"] + "'>" + admin[i]["AdminName"] + "</option>"
			}
		}
		$('#remind_to').html(data);
		var select2c = $('#remind_to').select2({
			placeholder: "Remind To",
			minimumResultsForSearch: 10,
			containerCssClass: "cityCombo12"
		});
		select2c.val(null).trigger("change");
	} catch(err) {}
	$('#add_reminder').on('click', function(e) {
		var reminder_date  = $("#reminder_date").val();
		var reminder_time  = $("#reminder_time").val();
		var description  = $("#description").val();
		var user_type  = $("#user_type").val();
		var remind_to  = $("#remind_to").val().sort().join(", ");
		var send_sms  = $("#send_sms").val();
		var form_input_array = ["reminder_date", "reminder_time", "description", "user_type", "remind_to", "send_sms"];
		var input_objects = {reminder_date: reminder_date, reminder_time: reminder_time, description: description, user_type: user_type, remind_to: remind_to, send_sms: send_sms};
		var form = '<form action="/admin/manageadmin/create_admin_reminder" method="POST">';
		for (i = 0; i < form_input_array.length; i++) {
			form += '<input type="hidden" name="' + form_input_array[i] + '" value="' + input_objects[form_input_array[i]] + '" />';
		}
		form += '<input type="submit" name="admin_reminder_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body'); created_form.submit();
	});
});
function checkfieldAdmin() {
	var str1 = new Array(6);
	str1[0] = $("#reminder_date").val();
	str1[1] = $("#reminder_time").val();
	str1[2] = $("#description").val();
	str1[3] = $("#user_type").val();
	str1[4] = $("#remind_to").val();
	str1[5] = $("#send_sms").val();
	var x = 0;
	for (var i = 0; i < str1.length; i++) {
		if(str1[i] == null || str1[i] == undefined || str1[i] == '' || str1[i].length == 0) {
			x = 1;
		}
	}
	if(x == 0) {
		$("#add_reminder").removeAttr('disabled');
	} else {
		$("#add_reminder").attr('disabled','disabled');
	}
}
function get_remind_to() {
	var data = "";
	if($("#user_type").val() == "Admin") {
		for (var i = 0; i < admin.length; i++) {
			data += "<option value='" + admin[i]["AdminId"] + "'>" + admin[i]["AdminName"] + "</option>"
		}
	} else if($("#user_type").val() == "Executive") {
		for (var i = 0; i < executive.length; i++) {
			data += "<option value='" + executive[i]["ExecId"] + "'>" + executive[i]["ExecName"] + "</option>"
		}
	}
	$('#remind_to').html(data);
	var select2d = $('#remind_to').select2({
		placeholder: "Remind To",
		minimumResultsForSearch: 10,
		containerCssClass: "cityCombo12",
	});
	select2d.val(null).trigger("change");
	checkfieldAdmin();
}