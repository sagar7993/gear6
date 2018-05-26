$(function () {
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
});
var icons = {
	header : "accordionFilterHeader accordionFilterHeaderExapndIcon",
	activeHeader : "accordionFilterHeader accordionFilterHeaderCollapseIcon"
};
var quantity = 0; var str2 = []; var daysOn = 0; var select2 = [];
$('#quantityButton').on('click', function(e) {
	$("#accordionsContainer").html(""); 
	quantity = $('#quantity').val();
	var radio = '<br/><div class="light-box auto-overflow"><div class="msg-radio-grp"><div class="col-xs-12 ">';
	for (var i = 0; i < reminderTypes.length; i++) {
		radio += 	'<div class="col-xs-3" style="margin-top:15px!important;"><div class="form-group col-xs-12"><div class="checkbox" style="">';
		radio += 	'<label class="paymode"><input type="radio" name="reminder_id" value="' + reminderTypes[i].reminder_id + '" onchange="validation();">';
		radio += 	'<span style="margin-left:5px">' + reminderTypes[i].reminder_name + '</span></label></div></div></div>';
	}
	radio += 	'</div></div></div><br/>';
	$("#accordionsContainer").append(radio);
	for(var i=0; i<quantity; i++) {
		var html = '<div class="col-xs-12 fields-update-container"><div class="col-xs-12 fields-update-container"><div class="col-xs-6 form-group fields-container">';
		html += '<br/><select class="form-control styled-select" style="font-size:1em!important;height:40px!important;margin-top:-10px!important;" onchange="showTextBox('+i+');validation();" name="days_'+i+'" id="days_'+i+'">';
		html += '<option value="Before">Before</option><option value="After">After</option><option value="On">On Next Renewal / Service Date</option></select></div>';
		html += '<div class="col-xs-6 form-group fields-container" style="display:none;margin-top:20px!important;" id="daysBefore_' + i + '">';
		html += '<input type="number" style="height:46px!important;" oninput="validation();" class="form-control" id="daysBeforeValue_' + i + '" placeholder="Days Before Next Renewal / Service Date" min="0"/></div>';
		html += '<div class="col-xs-6 form-group fields-container" style="display:none;margin-top:20px!important;" id="daysAfter_' + i + '">';
		html += '<input type="number" style="height:46px!important;" oninput="validation();" class="form-control" id="daysAfterValue_' + i + '" placeholder="Days After Next Renewal / Service Date" min="0"/></div>';
		html += '</div><br/><br/><h4 style="margin-left:3%!important;">Email Template :</h4><div class="col-xs-12 form-group fields-container"><div class="col-xs-12 field-box">';
		html += '<textarea type="text" oninput="validation();" class="form-control tinymce" id="email_message_'+i+'" name="email_message_'+i+'"></textarea></div>';
		html += '</div></div><div class="col-xs-12 fields-update-container"><h4 style="margin-left:3%!important;">SMS Message :</h4><div class="col-xs-12 form-group fields-container">';
		html += '<div class="col-xs-12 field-box"><input type="text" oninput="validation();" class="form-control" id="sms_message_'+i+'" name="sms_message_'+i+'" placeholder="SMS Message"></div></div></div>';
		var accordionHTML = '<ul class="collapsible" data-collapsible="accordion"><li><div class="collapsible-header"><i class="material-icons">alarm_on</i>';
	    accordionHTML += '<b>Reminder '+(i+1)+'</b></div><div class="collapsible-body">' + html + '</div></li></ul>';
		$("#accordionsContainer").append(accordionHTML);
		try {
			select2[i] = $("#days_" +i).select2({
				placeholder: "No. of Days Before / After / On",
				minimumResultsForSearch: 10,
				containerCssClass: "cityCombo12"
			});
			select2[i].val(null).trigger("change");
		} catch(err) {
			//Do Nothing
		}
	}
	$('.collapsible').collapsible({
		accordion : false
    });
	var button = "<div class='button-box-contact col-xs-12'><div class='button-container col-xs-6 col-xs-offset-5'>";
	button += "<button class='next btn btn-primary btnUpdate-pu' id='reminder_add' disabled>Add Reminder</button></div></div>";
	$("input[type='checkbox'], input[type='radio']").icheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
	$(document).on('ifChecked', 'input[name=admin_id]', function() {
		validation();
	});
	$("#accordionsContainer").append(button);
	initializeAccordion();
	$("#reminder_add").on('click', function(e) {
		var form = '<form action="/admin/users/save_reminder" method="POST">';
		form += '<input type="hidden" name="reminder" value="' + encodeURI(JSON.stringify(str2)) + '" />';
		form += '<input type="submit" name="reminder_add_submit" value="submit" /></form>';
		var created_form = $(form).appendTo('body'); created_form.submit();
	});
	// for(var i=0; i<quantity; i++) {
	// 	tinyMCE.get('email_message_'+i).setContent('This is to remind you that your next {{reminder_type}} is due on {{date}}.');
	// }
});
function initializeAccordion() {
	$(".accordion").accordion({
		autoHeight : false,
		navigation : false,
		speed : "slow",
		active : false,
		collapsible : true,
		icons : icons,
		header : 'h3'
	});
	initializeTinyMCE();
}
function initializeTinyMCE() {
	tinymce.init({
		selector:'.tinymce',
		setup : function(e) {
		    e.on('input', function(e) {
		        validation();
		    });
		}
	});
}
function validation() {
	var count = 0; str2 = [];
	for (var i = 0; i < quantity; i++) {
		var emailMessage = tinyMCE.get('email_message_'+i).getContent();
		var smsMessage = $("#sms_message_"+i).val();
		var daysBefore = $("#daysBeforeValue_"+i).val();
		var daysAfter = $("#daysAfterValue_"+i).val();
		var reminder_id = $('input[name=reminder_id]:checked').val();
		if (daysAfter == '' || daysAfter.length == 0 || daysAfter == null || daysAfter == undefined) {
			daysAfter = null;
		}
		if (daysBefore == '' || daysBefore.length == 0 || daysBefore == null || daysBefore == undefined) {
			daysBefore = null;
		}
		var str1 = {}; var x = 0;
		str1 = {
			emailMessage: emailMessage,
			smsMessage: smsMessage,
			daysBefore: daysBefore,
			daysAfter: daysAfter,
			reminder_id: reminder_id
		};
		for (var key in str1) {
			if(key != 'daysAfter' && key != 'daysBefore' && (str1[key] == null || str1[key] == undefined || str1[key] == '')) {
				x = 1;
			}
			if ((daysAfter == null && daysBefore == null) || (daysAfter == undefined && daysBefore == undefined) || (daysAfter == '' && daysBefore == '')) {
				if (daysOn == 0) {
					x = 1;
				} else if (daysOn == 1) {
					str1['daysAfter'] = null; str1['daysBefore'] = null;
				}
			}
		}
		if (x == 0) {
			var temp = emailMessage; var tempEmailMessage = temp.substring(3, (temp.length-4));
			if(tempEmailMessage.length >= 10 && smsMessage.length >= 10) {
				str1["emailMessage"] = emailMessage; str2.push(str1); count++;
			}
		}
	}
	if(count == quantity) {
		$("#reminder_add").removeAttr('disabled');
	} else {
		$("#reminder_add").attr('disabled','disabled');
	}
}

function showTextBox(i) {
	var option = $('select[name="days_'+i+'"]').val();
	if(option == "After") {
		document.getElementById('daysBeforeValue_' + i).value = "";
		document.getElementById('daysAfterValue_' + i).value = "";
		document.getElementById('daysBefore_' + i).style.display =  "none";
		document.getElementById('daysAfter_' + i).style.display =  "block";
		daysOn = 0;
	} else if(option == "Before") {
		document.getElementById('daysBeforeValue_' + i).value = "";
		document.getElementById('daysAfterValue_' + i).value = "";
		document.getElementById('daysBefore_' + i).style.display =  "block";
		document.getElementById('daysAfter_' + i).style.display =  "none";
		daysOn = 0;
	} else if(option == "On") {
		document.getElementById('daysBeforeValue_' + i).value = "";
		document.getElementById('daysAfterValue_' + i).value = "";
		document.getElementById('daysBefore_' + i).style.display =  "none";
		document.getElementById('daysAfter_' + i).style.display =  "none";
		daysOn = 1;
	}
}
function quantityValidate() {
	var quantity = Number($('#quantity').val());
	document.getElementById('quantity').value = quantity;
	if(quantity > 0) {
		$("#quantityButton").removeAttr('disabled');
	} else {
		$("#quantityButton").attr('disabled','disabled');
	}
}
quantityValidate();