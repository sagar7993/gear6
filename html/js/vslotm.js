$(document).ready(function() {
	var calendar = $('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'year,month'
		},
		selectable: true,
		selectHelper: true,
		select: function(start, end, allDay) {
			if(!start.isBefore(moment())) {
				$('#slotseldate').val(moment(start).format('YYYY-MM-DD'));
				var dialog = $('#dialog').dialog({title:"Add/Modify Slots",
					height: 350,
					width: 400,
					modal: true,
					resizable: true,
					dialogClass: 'no-close success-dialog',
					open: function() {
						fillDialogSlots(moment(start).format('YYYY-MM-DD'));
					},
					close: function() {
						$('.slot-time').icheck('unchecked');
						$('#select_all').icheck('unchecked');
						$('#slots').val('0');
						$("#SlotSubmitBtn").attr('disabled','disabled');
					}
				});
			}
		},
		events: {
			url: univ_base_uri + 'vendor/profile/getSlotEventsData',
			type: 'POST',
		},
		editable: true,
		cache:false
	});
	$('#cancel').on('click', function(e) {
		e.preventDefault();
		$('#dialog').dialog('close');
	});
	$(document).on('ifChecked', '#select_all', function(event) {
		$('.slot-time').icheck('checked');
		checkSlotsCount();
	});
	$(document).on('click', '.reset', function(event) {
		event.preventDefault();
		$('.slot-time').icheck('unchecked');
		checkSlotsCount();
	});
	$(document).on('ifUnchecked', '.slot-time', function(event) {
		$('#select_all').icheck('unchecked');
		checkSlotsCount();
	});
	$(document).on('ifChecked', '.slot-time', function(event) {
		checkSlotsCount();
	});
	$(document).on('change','#slots',function(){
		checkSlotsCount();
	});
});
function checkSlotsCount() {
	var str2  = $("input[name='slotids[]']:checked");
	var x = 0;
	if(str2.length < 1) {
		x = 1;
	}
	if(x == 0) {
		$('#SlotSubmitBtn').removeAttr('disabled');
	} else {
		$("#SlotSubmitBtn").attr('disabled','disabled');
	}
}
function fillDialogSlots(date) {
	$.ajax({
		type: "POST",
		url: "/vendor/profile/dialog_slots_data",
		data: {date: date},
		dataType: "text",
		cache: false,
		success: function(data) {
			$('#dialog_slots_content').html(data);
			$('#dialog_slots_content').each(function() {
				$(this).find('input').icheck({
					checkboxClass: 'icheckbox_square-green',
					radioClass: 'iradio_square-green slotRadio'
				});
			});
		}
	});
}