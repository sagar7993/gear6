var feedbackArray = []; var fb_order_id = null; var fb_desc = ""; var question_count = null;
$(function() {
	question_count = $('.question_count').val();
	$('.feedbackRating').raty();
	$('.rateItLink').on('click', function(s) {
		$('.feedbackRating').raty(); $('.feedbackRatingPorder').raty(); fb_order_id = null; fb_desc = null; feedbackArray = [];
		$('.fb_submit').attr('disabled','disabled');
		var OId = $(this).attr('data-order');
		$.ajax({
			type: "POST",
			url: "/user/account/get_order_ratings",
			data: { OId : OId },
			dataType: "json",
			cache: false,
			success: function(data) {
				$('#feedback_' + OId).openModal({
					dismissible : true,
					ready: function() {
						var overlay = $('<div id="lean-overlay" class="lean-overlay"></div>');
						$("body").append(overlay);
						$('#lean-overlay').css({'opacity':'0.5','display':'block'});
					}
				});
				fb_desc = data.remarks; $('.fb_desc').val(fb_desc);
				if(data.ratings.length > 0) {
					for(var i = 0; i < question_count; i++) {
						if(data['ratings'][i] == null || data['ratings'][i] == undefined) {
							data['ratings'][i] = {}; data['ratings'][i]['ExecFbAnswer'] = 0;
							data['ratings'][i]['ExecFbQId'] = feedback[i]['ExecFbQId'];
						}
						$('#question_' + OId + '_' + data['ratings'][i]['ExecFbQId']).raty({ score: data['ratings'][i]['ExecFbAnswer']});
					}
				}
			},
			error: function(error) {
				console.log(error);
			}
		});
	});
	$('.fb_desc').on('input', function(e) {
		fb_order_id = $(this).parent().parent().find('.fb_oid').val();
		fb_desc = $(this).val();
	});
	$('.fb_desc_corder').on('input', function(e) {
		fb_order_id = $(this).parent().parent().find('.fb_oid').val();
		checkFeedback($(this));
	});
	$('.fb_desc_porder').on('input', function(e) {
		fb_order_id = $(this).parent().parent().find('.fb_oid').val();
		checkFeedbackPorder($(this), fb_order_id);
	});
	$('.feedbackRatingPorder').on('click', function(e) {
		fb_order_id = $(this).parent().parent().find('.fb_oid').val();
		checkFeedbackPorder($(this), fb_order_id);
	});
	$('.feedbackRating').on('click', function(e) {
		fb_order_id = $(this).parent().parent().find('.fb_oid').val();
		checkFeedback($(this));
	});
	$('.ratingCloseEvent').on('click', function(e) {
		$('.feedbackRating').raty(); fb_order_id = null; fb_desc = null; feedbackArray = [];
		$('.fb_submit').attr('disabled','disabled'); $('.corderModal').closeModal();
	});
	$('.fb_submit').on('click', function(e) {
		var OId = feedbackArray.pop(); var questionArray = [];
		feedbackArray = feedbackArray.join(", ");
		for(var i = 0; i < feedback.length; i++) {
			questionArray.push(feedback[i]['ExecFbQId']);
		}
		questionArray = questionArray.join(", ");
		jQuery.ajax({
			type: "POST",
			url: "/user/account/updateUserFeedback",
			data: { feedbackArray : feedbackArray, questionArray : questionArray, OId : OId, remarks : fb_desc },
			dataType: 'text',
			success: function(data) {
				$('.ratingCloseEvent').trigger('click');
				swal("Success", "Thank you for your feedback", "success");
			}
		});
	});
});
function checkFeedbackPorder(element, oid) {
	var array = [];
	for (var i = 1; i <= question_count; i++) {
		var val = $('#question_' + fb_order_id + '_' + i).find('input').val();
		if(val == null || val == undefined || val == '') {
			array.push(null);
		} else {
			array.push(val);
		}
	}
	array.push(fb_order_id); var x = 0;
	for (i = 0; i < array.length; i++) {
		if(array[i] === null || typeof array[i] === "undefined" || array[i] == "") {
			x = 1;
		}
	}
	if(x == 0) {
		element.parent().parent().parent().find('.fb_submit').removeAttr('disabled'); feedbackArray = array;
	} else {
		element.parent().parent().parent().find('.fb_submit').attr('disabled','disabled'); feedbackArray = [];
	}
}
function checkFeedback(element) {
	var array = [];
	for (var i = 1; i <= question_count; i++) {
		var val = $('#question_' + fb_order_id + '_' + i).find('input').val();
		if(val == null || val == undefined || val == '') {
			array.push(null);
		} else {
			array.push(val);
		}
	}
	array.push(fb_order_id); var x = 0;
	for (i = 0; i < array.length; i++) {
		if(array[i] === null || typeof array[i] === "undefined" || array[i] == "") {
			x = 1;
		}
	}
	if(x == 0) {
		element.parent().parent().parent().find('.fb_submit').removeAttr('disabled'); feedbackArray = array;
	} else {
		element.parent().parent().parent().find('.fb_submit').attr('disabled','disabled'); feedbackArray = [];
	}
}
openFeedbackModal = function() {
	$('.feedbackRating').raty(); $('.feedbackRatingPorder').raty(); fb_order_id = null; fb_desc = null; feedbackArray = [];
	$('.fb_submit').attr('disabled', 'disabled');
	var OId = $('#fb_oid').val();
	$.ajax({
		type: "POST",
		url: "/user/account/get_order_ratings",
		data: { OId : OId },
		dataType: "json",
		cache: false,
		success: function(data) {
			$('.feedback-modal').openModal({
				dismissible : true,
				ready: function() {
					var overlay = $('<div id="lean-overlay" class="lean-overlay"></div>');
					$("body").append(overlay);
					$('#lean-overlay').css({'opacity':'0.5','display':'block'});
				}
			});
			fb_desc = data.remarks; $('.fb_desc').val(fb_desc);
			if(data.ratings.length > 0) {
				for(var i = 0; i < question_count; i++) {
					if(data['ratings'][i] == null || data['ratings'][i] == undefined) {
						data['ratings'][i] = {}; data['ratings'][i]['ExecFbAnswer'] = 0;
						data['ratings'][i]['ExecFbQId'] = feedback[i]['ExecFbQId'];
					}
					$('#question_' + OId + '_' + data['ratings'][i]['ExecFbQId']).raty({ score: data['ratings'][i]['ExecFbAnswer']});
				}
			}
			checkFeedback($('#psuccessfbsubmit'));
		},
		error: function(error) {
			console.log(error);
		}
	});
}