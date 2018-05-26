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
	if (page_id == 'queried') {
		var column_data = [
			{ "class": "first" },
			null,
			{"contentPadding": "mmm","width":"220px"},
			{"contentPadding": "mmm"},
			{"class": "last"}
		];
	} else if(page_id == 'feedback') {
		var column_data = [
			{ "class": "first" },
			{"contentPadding": "mmm"},
			{"contentPadding": "mmm","width":"220px"},
			null,
			{ "class": "last" }
		];
	} else if(page_id == 'pickup') {
		var column_data = [
			{"class": "first", "width":"220px"},
			{"contentPadding": "mmm"},
			null,
			{ "class": "last" }
		];
	} else if(page_id == 'musers') {
		var column_data = [
			{"class": "first"},
			{"contentPadding": "mmm", "width":"220px"},
			null,
			{"class": "last", "width":"150px"}
		];
	} else if(page_id == 'aservices') {
		var column_data = [
			{"class": "first"},
			{"contentPadding": "mmm", "width":"220px"},
			null,
			{"contentPadding": "mmm"},
			null,
			{"contentPadding": "mmm", "width":"150px"},
			{"class": "last"}
		];
	} else if(page_id == 'price-chart') {
		var oTable2 = $('#example2').DataTable({
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
			"dom": '<"top"f>t<"bottom"lpri><"clear">',
			"columns": [
				{ "class": "first" },
				null,
				{"contentPadding": "mmm","width":"220px"},
				{"contentPadding": "mmm"},
				{"class": "last"}
			]
		});
		var oTable3 = $('#example3').DataTable({
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
			"dom": '<"top"f>t<"bottom"lpri><"clear">',
			"columns": [
				{ "class": "first" },
				{"contentPadding": "mmm","width":"220px"},
				{"class": "last"}
			]
		});
	} else if(page_id == 'onexcl') {
		var oTable2 = $('#example2').DataTable({
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
			"dom": '<"top"f>t<"bottom"lpri><"clear">',
			"columns": [
				{ "class": "first", "width":"60px"},
				{"contentPadding": "mmm","width":"220px"},
				{"class": "last"}
			]
		});
		var oTable3 = $('#example3').DataTable({
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
			"dom": '<"top"f>t<"bottom"lpri><"clear">',
			"columns": [
				{ "class": "first" },
				{"contentPadding": "mmm","width":"220px"},
				{"contentPadding": "mmm"},
				{"contentPadding": "mmm"},
				null,
				{"class": "last"}
			]
		});
	} else {
		var column_data = [
			{ "class": "first" },
			null,
			{"contentPadding": "mmm","width":"220px"},
			{"contentPadding": "mmm"},
			null,
			{"class": "last"}
		];
	}
	if($('#example1').length > 0) {
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
showValidation = function(id, message) {
	if(typeof message === "undefined") {
		message = 'Please fill valid ' + $('#' + id).data('error');
	}
	$('.error-text1').remove();
	$('#' + id).parent().append('<div class="error-text1">' + message + '</div>');
	$('html,body').animate({
		scrollTop: $($('#' + id)).offset().top
	}, 'slow');
	event.preventDefault();
	throw new Error('This is not an error. This is just to abort javascript');
	return false;
}