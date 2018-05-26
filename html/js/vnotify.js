$(function() {
	if(typeof univ_base_uri !== "undefined" && univ_base_uri !== null && univ_base_uri != "") {
		if(typeof(EventSource) !== "undefined") {
			var noti_ocount = new EventSource(univ_base_uri + "vendor/notify/nocount");
			noti_ocount.onmessage = function(event) {
				$("#noti_ocount").html(event.data);
			};
			var noti_ofeed = new EventSource(univ_base_uri + "vendor/notify/nofeed");
			noti_ofeed.onmessage = function(event) {
				$("#noti_ofeed").html(event.data);
			};
			var noti_fcount = new EventSource(univ_base_uri + "vendor/notify/nfcount");
			noti_fcount.onmessage = function(event) {
				$("#noti_fcount").html(event.data);
			};
			var noti_ffeed = new EventSource(univ_base_uri + "vendor/notify/nffeed");
			noti_ffeed.onmessage = function(event) {
				$("#noti_ffeed").html(event.data);
			};
		} else {
			$('#noti_ocount').html('0');
			$("#noti_ofeed").html('<li class="header">You have no new notifications</li><li><ul class="menu"><li><a href="#"><i class="ion ion-ios7-people info"></i> Sorry, your browser does not support server-sent events.</a></li></ul></li><li class="footer"><a href="' + univ_base_uri + 'vendor/unallotted">View all Orders</a></li>');
			$('#noti_fcount').html('0');
			$("#noti_ffeed").html('<li class="header">You have no new messages</li><li><ul class="menu"><li><a href="#"><i class="ion ion-ios7-people info"></i> Sorry, your browser does not support server-sent events.</a></li></ul></li><li class="footer"><a href="' + univ_base_uri + 'vendor/profile/feedbacks">View all Messages</a></li>');
		}
	}
});