var mainCounter = 0;
function showG6Notification(url, body) {
	var options = {
	    body: body,
	    vibrate: [200, 100, 200],
	    icon: "https://www.gear6.in/img/icons/favicon.png"
	}
	var notification = new Notification("Gear6 Admin", options);
    notification.onclick = function () { window.open(url); };
	var alert = new Audio("https://www.gear6.in/sound/alert.mp3"); alert.play();
}
function requestG6Notification() {
	Notification.requestPermission(function (permission) {
		if (permission === "granted") {
			//Do Nothing
		}
	});
}
$(function() {
	if ("Notification" in window) {
		notificationSupport = true;
		if (Notification.permission === "granted") {
			//Do Nothing
		}
		else if (Notification.permission !== 'denied') {
			requestG6Notification();
		}
	} else {
		notificationSupport = false;
	}
	if (typeof univ_base_uri !== "undefined" && univ_base_uri !== null && univ_base_uri != "") {
		if(typeof(EventSource) !== "undefined") {
			var get_notifications = new EventSource(univ_base_uri + "admin/adminnotify/get_notifications");
			get_notifications.onmessage = function(event) {
				var temp = JSON.parse(event.data); mainCounter += 1;
				var count = Number(temp.feedback.count); var oldCount = Number($("#adminNotificationNewFeedbackCounter").html());
				if(notificationSupport && ((count - oldCount) == 1) && (mainCounter > 1)) { showG6Notification(temp.feedback.url, "You have received " + Number(count - oldCount) + " new feedback"); }
				else if (notificationSupport && ((count - oldCount) > 1) && (mainCounter > 1)) { showG6Notification(temp.feedback.general_url, "You have received " + Number(count - oldCount) + " new feedback"); }
				$("#adminNotificationNewFeedback").html(temp.feedback.html);
				$("#adminNotificationNewFeedbackCounter").html(temp.feedback.count);
				var count = Number(temp.usercontactus.count); var oldCount = Number($("#adminNotificationNewUserContactUsCounter").html());
				if(notificationSupport && (count > oldCount) && (mainCounter > 1)) { showG6Notification(temp.usercontactus.url, "You have received " + Number(count - oldCount) + " user contact us request"); }
				$("#adminNotificationNewUserContactUs").html(temp.usercontactus.html);
				$("#adminNotificationNewUserContactUsCounter").html(temp.usercontactus.count);
				var count = Number(temp.agentcontactus.count); var oldCount = Number($("#adminNotificationNewAgentContactUsCounter").html());
				if(notificationSupport && (count > oldCount) && (mainCounter > 1)) { showG6Notification(temp.agentcontactus.url, "You have received " + Number(count - oldCount) + " agent contact us request"); }
				$("#adminNotificationNewAgentContactUs").html(temp.agentcontactus.html);
				$("#adminNotificationNewAgentContactUsCounter").html(temp.agentcontactus.count);
				var count = Number(temp.payment.count); var oldCount = Number($("#adminNotificationNewPaymentCounter").html());
				if(notificationSupport && ((count - oldCount) == 1) && (mainCounter > 1)) { showG6Notification(temp.payment.url, "You have received " + Number(count - oldCount) + " customer payment"); }
				else if (notificationSupport && ((count - oldCount) > 1) && (mainCounter > 1)) { showG6Notification(temp.payment.general_url, "You have received " + Number(count - oldCount) + " customer payment"); }
				$("#adminNotificationNewPayment").html(temp.payment.html);
				$("#adminNotificationNewPaymentCounter").html(temp.payment.count);
				var count = Number(temp.pickup.count); var oldCount = Number($("#adminNotificationNewPickupCounter").html());
				if(notificationSupport && ((count - oldCount) == 1) && (mainCounter > 1)) { showG6Notification(temp.pickup.url, "You have received " + Number(count - oldCount) + " bike pickup"); }
				else if (notificationSupport && ((count - oldCount) > 1) && (mainCounter > 1)) { showG6Notification(temp.pickup.general_url, "You have received " + Number(count - oldCount) + " bike pickup"); }
				$("#adminNotificationNewPickup").html(temp.pickup.html);
				$("#adminNotificationNewPickupCounter").html(temp.pickup.count);
				var count = Number(temp.neworder.count); var oldCount = Number($("#adminNotificationNewOrderCounter").html());
				if(notificationSupport && ((count - oldCount) == 1) && (mainCounter > 1)) { showG6Notification(temp.neworder.url, "You have received " + Number(count - oldCount) + " new order"); }
				else if (notificationSupport && ((count - oldCount) > 1) && (mainCounter > 1)) { showG6Notification(temp.neworder.general_url, "You have received " + Number(count - oldCount) + " new order"); }
				$("#adminNotificationNewOrder").html(temp.neworder.html);
				$("#adminNotificationNewOrderCounter").html(temp.neworder.count);
				var count = Number(temp.delayedorder.count); var oldCount = Number($("#adminNotificationNewDelayedOrderCounter").html());
				if(notificationSupport && ((count - oldCount) == 1) && (mainCounter > 1)) { showG6Notification(temp.delayedorder.url, Number(count - oldCount) + " order has been delayed"); }
				else if (notificationSupport && ((count - oldCount) > 1) && (mainCounter > 1)) { showG6Notification(temp.delayedorder.general_url, Number(count - oldCount) + " order has been delayed"); }
				$("#adminNotificationNewDelayedOrder").html(temp.delayedorder.html);
				$("#adminNotificationNewDelayedOrderCounter").html(temp.delayedorder.count);
				var count = Number(temp.emergency.count); var oldCount = Number($("#adminNotificationNewEmergencyOrderCounter").html());
				if(notificationSupport && (count > oldCount) && (mainCounter > 1)) { showG6Notification(temp.emergency.url, "You have received " + Number(count - oldCount) + " new emergency order"); }
				$("#adminNotificationNewEmergencyOrder").html(temp.emergency.html);
				$("#adminNotificationNewEmergencyOrderCounter").html(temp.emergency.count);
				var count = Number(temp.puncture.count); var oldCount = Number($("#adminNotificationNewPunctureOrderCounter").html());
				if(notificationSupport && (count > oldCount) && (mainCounter > 1)) { showG6Notification(temp.puncture.url, "You have received " + Number(count - oldCount) + " new puncture order"); }
				$("#adminNotificationNewPunctureOrder").html(temp.puncture.html);
				$("#adminNotificationNewPunctureOrderCounter").html(temp.puncture.count);
				var count = Number(temp.renewal.count); var oldCount = Number($("#adminNotificationNewRenewalCounter").html());
				// if(notificationSupport && ((count - oldCount) == 1) && (mainCounter > 1)) { showG6Notification(temp.renewal.url, "You need to update renewal dates for " + Number(count - oldCount) + " order"); }
				// else if (notificationSupport && ((count - oldCount) > 1) && (mainCounter > 1)) { showG6Notification(temp.renewal.general_url, "You need to update renewal dates for " + Number(count - oldCount) + " order"); }
				$("#adminNotificationNewRenewal").html(temp.renewal.html);
				$("#adminNotificationNewRenewalCounter").html(temp.renewal.count);
			};
		} else {
			$('#adminNotificationNewOrderCounter').html('0');
			$('#adminNotificationNewDelayedOrderCounter').html('0');
			$('#adminNotificationNewFeedbackCounter').html('0');
			$('#adminNotificationNewPaymentCounter').html('0');
			$('#adminNotificationNewEmergencyOrderCounter').html('0');
			$('#adminNotificationNewPunctureOrderCounter').html('0');
			$('#adminNotificationNewUserContactUsCounter').html('0');
			$('#adminNotificationNewAgentContactUsCounter').html('0');
			$('#adminNotificationNewRenewalCounter').html('0');
			$('#adminNotificationNewPickupCounter').html('0');
			$("#adminNotificationNewOrder").html('<li class="header">You have no new orders</li><li><ul class="menu"><li><a href="#"><i class="ion ion-ios7-people info"></i> Sorry, your browser does not support server-sent events.</a></li></ul></li><li class="footer"><a href="' + univ_base_uri + 'admin/orders">View all Orders</a></li>');
			$("#adminNotificationNewDelayedOrder").html('<li class="header">You have no new delayed orders</li><li><ul class="menu"><li><a href="#"><i class="ion ion-ios7-people info"></i> Sorry, your browser does not support server-sent events.</a></li></ul></li><li class="footer"><a href="' + univ_base_uri + 'admin/orders">View all Orders</a></li>');
			$("#adminNotificationNewFeedback").html('<li class="header">You have no new feedback</li><li><ul class="menu"><li><a href="#"><i class="ion ion-ios7-people info"></i> Sorry, your browser does not support server-sent events.</a></li></ul></li><li class="footer"><a href="' + univ_base_uri + 'admin/feedback/vendors">View all Feedbacks</a></li>');
			$("#adminNotificationNewPayment").html('<li class="header">You have no new payment</li><li><ul class="menu"><li><a href="#"><i class="ion ion-ios7-people info"></i> Sorry, your browser does not support server-sent events.</a></li></ul></li><li class="footer"><a href="' + univ_base_uri + 'vendor/orders">View all Orders</a></li>');
			$("#adminNotificationNewEmergencyOrder").html('<li class="header">You have no new emergency orders</li><li><ul class="menu"><li><a href="#"><i class="ion ion-ios7-people info"></i> Sorry, your browser does not support server-sent events.</a></li></ul></li><li class="footer"><a href="' + univ_base_uri + 'admin/orders">View all Orders</a></li>');
			$("#adminNotificationNewPunctureOrder").html('<li class="header">You have no new puncture orders</li><li><ul class="menu"><li><a href="#"><i class="ion ion-ios7-people info"></i> Sorry, your browser does not support server-sent events.</a></li></ul></li><li class="footer"><a href="' + univ_base_uri + 'admin/orders">View all Orders</a></li>');
			$("#adminNotificationNewUserContactUs").html('<li class="header">You have no new user contact us requests</li><li><ul class="menu"><li><a href="#"><i class="ion ion-ios7-people info"></i> Sorry, your browser does not support server-sent events.</a></li></ul></li><li class="footer"><a href="' + univ_base_uri + 'admin/ucontactus">View all User Contact Us Requests</a></li>');
			$("#adminNotificationNewAgentContactUs").html('<li class="header">You have no new agent contact us requests</li><li><ul class="menu"><li><a href="#"><i class="ion ion-ios7-people info"></i> Sorry, your browser does not support server-sent events.</a></li></ul></li><li class="footer"><a href="' + univ_base_uri + 'admin/agregs">View all Agent Contact Us Requests</a></li>');
			$("#adminNotificationNewPickup").html('<li class="header">You have no new bike updates</li><li><ul class="menu"><li><a href="#"><i class="ion ion-ios7-people info"></i> Sorry, your browser does not support server-sent events.</a></li></ul></li><li class="footer"><a href="' + univ_base_uri + 'admin/orders">View all Orders</a></li>');
			$("#adminNotificationNewRenewal").html('<li class="header">You have no new renewal dates</li><li><ul class="menu"><li><a href="#"><i class="ion ion-ios7-people info"></i> Sorry, your browser does not support server-sent events.</a></li></ul></li><li class="footer"><a href="' + univ_base_uri + 'admin/orders">View all Orders</a></li>');
		}
	}
});
$(document).ready(function() {
	var login_section = $('#login_modal').hasClass("show");
	if (login_section) {
		$('html').css({
			height: '100%'
		});
		$('body').css({
			height: '100%'
		});
		var animating = false, submitPhase1 = 1100, submitPhase2 = 400, logoutPhase1 = 800, $login = $(".login"), $app = $(".app");
		function ripple(elem, e) {
			$(".ripple").remove();
			var elTop = elem.offset().top, elLeft = elem.offset().left, x = e.pageX - elLeft, y = e.pageY - elTop;
			var $ripple = $("<div class='ripple'></div>");
			$ripple.css({
				top: y,	left: x
			});
			elem.append($ripple);
		};
		$(document).on("click", ".login__submit", function(e) {
			if (animating) return;
			animating = true; var that = this; ripple($(that), e); $(that).addClass("processing");
			setTimeout(function() {
				$(that).addClass("success");
				setTimeout(function() {
					$app.show(); $app.css("top"); $app.addClass("active");
				}, submitPhase2 - 70);
				setTimeout(function() {
					$login.hide(); $login.addClass("inactive"); animating = false; $(that).removeClass("success processing");
				}, submitPhase2);
			}, submitPhase1);
		});
		$(document).on("click", ".app__logout", function(e) {
			if (animating) return;
			$(".ripple").remove(); animating = true; var that = this; $(that).addClass("clicked");
			setTimeout(function() {
				$app.removeClass("active"); $login.show(); $login.css("top"); $login.removeClass("inactive");
			}, logoutPhase1 - 120);
			setTimeout(function() {
				$app.hide(); animating = false; $(that).removeClass("clicked");
			}, logoutPhase1);
		});
	} else {
		// Do Nothing
	}
});