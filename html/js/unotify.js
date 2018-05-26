$(function() {
	if(typeof univ_base_uri !== "undefined" && univ_base_uri !== null && univ_base_uri != "") {
		if(typeof(EventSource) !== "undefined") {
			var noti_ocount = new EventSource(univ_base_uri + "user/unotify/nocount");
			noti_ocount.onmessage = function(event) {
				$("#noti_ocount1").html(event.data);
			};
			var noti_ofeed = new EventSource(univ_base_uri + "user/unotify/nofeed");
			noti_ofeed.onmessage = function(event) {
				$("#noti_ofeed1").html(event.data);
				odetailBinder();
			};
		} else {
			$('#noti_ocount1').html('0');
			$("#noti_ofeed1").html('<li class="header">You have no new notifications</li><li><ul class="menu"><li><a href="#"><i class="ion ion-ios7-people info"></i> Sorry, your browser does not support server-sent events.</a></li></ul></li><li class="footer"><a href="' + univ_base_uri + 'user/account/corders">View all active orders</a></li>');
		}
	}
});
function odetailBinder() {
	$('.set_active_oid_cookie').on('click', function() {
		var oid = $(this).data("oid");
		var form = '<form method="POST" action="/user/account/corders"><input name="oid" value="' + oid + '" /><input type="submit" name="chactorder" value="submit" /></form>';
		var created_form = $(form).appendTo('body');
		created_form.submit();
	});
}