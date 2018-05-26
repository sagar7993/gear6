$(function () {
	$("input[type='checkbox'], input[type='radio']").icheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
	var page_id = $("#active").val();
	$('#' + page_id).removeClass('side-menu-inactive');
	$('#' + page_id).addClass('side-menu-active');
	if($('.knob').length > 0) {
		$(".knob").knob({
			draw: function() {
				if (this.$.data('skin') == 'tron') {
					var a = this.angle(this.cv)				// Angle
							, sa = this.startAngle			// Previous start angle
							, sat = this.startAngle			// Start angle
							, ea 							// Previous end angle
							, eat = sat + a 				// End angle
							, r = true;
					this.g.lineWidth = this.lineWidth;
					this.o.cursor
							&& (sat = eat - 0.3)
							&& (eat = eat + 0.3);
					if (this.o.displayPrevious) {
						ea = this.startAngle + this.angle(this.value);
						this.o.cursor
								&& (sa = ea - 0.3)
								&& (ea = ea + 0.3);
						this.g.beginPath();
						this.g.strokeStyle = this.previousColor;
						this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
						this.g.stroke();
					}
					this.g.beginPath();
					this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
					this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
					this.g.stroke();
					this.g.lineWidth = 2;
					this.g.beginPath();
					this.g.strokeStyle = this.o.fgColor;
					this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
					this.g.stroke();
					return false;
				}
			}
		});
	}
	if($('.sparkline').length > 0) {
		$(".sparkline").each(function() {
			var $this = $(this);
			$this.sparkline('html', $this.data());
		});
	}
	var oTable;
	if($('#example1').length > 0) {
		var column_data = [
			{ "class": "first" },
			null,
			{"contentPadding": "mmm","width":"220px"},
			{"contentPadding": "mmm"},
			null,
			{"class": "last"}
		];
		oTable = $('#example1').DataTable({
			bSearchable: true,
			bSortable: true,
			bInfo: true,
			bLengthChange: true,
			bPaginate: true,
			bFilter: true,
			aaSorting: [],
			"oLanguage": {
				"sEmptyTable": "No data available for your query.",
				"sSearch": "",
				"oPaginate": {
					"sPrevious": "Prev"
				}
			},
			"dom": '<"top"f>t<"bottom"lpri><"clear">',
			"columns": column_data,
			"fnDrawCallback": rate_it()
		});
	}
});
function rate_it() {
	$('.rate_it_i_say').each(function(i, obj) {
		var score = $(this).html();
		$(this).html('');
		$(this).raty({
			readOnly: true,
			precision: true,
			score: score,
			half: true
		});
	});
}