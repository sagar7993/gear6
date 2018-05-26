var oTable; var bPaginate = true;
$(function () {
	var page_id = $("#active").val();
	$('#'+page_id).removeClass('side-menu-inactive');
	$('#'+page_id).addClass('side-menu-active');
	try {
		$('#container11').highcharts({
			chart: {
				type: 'bar'
			},
			title: {
				text: 'Customer Order Statistic Report for Next 6 days'
			},
			subtitle: {
				text: 'Source: gear6.in'
			},
			xAxis: {
				categories: ['21/07', '22/07', '23/07', '24/07', '25/07' ,'26/07'],
				title: {
					text: null
				}
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Orders (Scaled)',
					align: 'high'
				},
				labels: {
					overflow: 'justify'
				}
			},
			tooltip: {
				valueSuffix: 'Orders'
			},
			plotOptions: {
				bar: {
					dataLabels: {
						enabled: true
					}
				}
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -40,
				y: 100,
				floating: true,
				borderWidth: 1,
				backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor || '#FFFFFF'),
				shadow: true
			},
			credits: {
				enabled: false
			},
			series: [{
				name: 'UnAllotted',
				data: [107, 31, 635, 203, 2,35]
			}, {
				name: 'Upcoming',
				data: [133, 156, 947, 408, 6,123]
			}, {
				name: 'Allotted',
				data: [973, 914, 405, 732, 34,1234]
			}]
		});
		$('#orders_dash').highcharts({
			chart: {
				type: 'bar'
			},
			title: {
				text: 'Comparison Graph for Date Range Selected of the Order Type and Status Opted'
			},
			subtitle: {
				text: 'Source: gear6.in'
			},
			xAxis: {
				categories: ['21/07', '22/07', '23/07', '24/07', '25/07' ,'26/07'],
				title: {
					text: null
				}
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Orders (Scaled)',
					align: 'high'
				},
				labels: {
					overflow: 'justify'
				}
			},
			tooltip: {
				valueSuffix: 'Orders'
			},
			plotOptions: {
				bar: {
					dataLabels: {
						enabled: true
					}
				}
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -40,
				y: 100,
				floating: true,
				borderWidth: 1,
				backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor || '#FFFFFF'),
				shadow: true
			},
			credits: {
				enabled: false
			},
			series: [{
				name: 'Insurance',
				data: [107, 31, 635, 203, 212,315]
			}, {
				name: 'Repair',
				data: [133, 156, 947, 408, 316,423]
			}, {
				name: 'Query',
				data: [133, 156, 947, 408, 256,323]
			}, {
				name: 'Servicing',
				data: [973, 914, 405, 732, 34,1234]
			}]
		});
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
		$(".sparkline").each(function() {
			var $this = $(this);
			$this.sparkline('html', $this.data());
		});
	} catch(err) {
		// Do Nothing
	}
	if (page_id == 'queried') {
		var column_data = [
			{ "class": "first" },
			{"contentPadding": "mmm"},
			{"contentPadding": "mmm"},
			{"contentPadding": "mmm"},
			{"contentPadding": "mmm"},
			{"contentPadding": "mmm"},
			{"class": "last"}
		];
	} else if(page_id == 'pb_list' || page_id == 'ec_list' || page_id == 'ucus_oview' || page_id == 'vuser_list' || page_id == 'prvuser_list') {
		var column_data = [
			{ "class": "first" },
			{"contentPadding": "mmm"},
			null,
			{"contentPadding": "mmm","width":"220px"},
			{ "class": "last" }
		];
	} else if(page_id == 'orderBills') {
		var column_data = [null, null, null, null, null];
	}
	else if(page_id == 'pbapps' || page_id == 'ecapps') {
		var column_data = [
			{ "class": "first" },
			{"contentPadding": "mmm"},
			null,
			{"contentPadding": "mmm","width":"220px"},
			null,
			{ "class": "last" }
		];
	} else if(page_id == 'pt_list' || page_id == 'getPickupDetails') {
		var column_data = [null, null, null, null];
	} else if(page_id == 'feedback' || page_id == 'ptapps' || page_id == 'agrg_oview') {
		var column_data = [
			{ "class": "first" },
			{"contentPadding": "mmm"},
			{"contentPadding": "mmm","width":"220px"},
			null,
			{ "class": "last" }
		];
	} else if(page_id == 'preleases' || page_id == 'vendor_list' || page_id == 'unallot'
	 || page_id == 'user_list' || page_id == 'feedbackReminders') {
		var column_data = [
			{ "class": "first" },
			null,
			{"contentPadding": "mmm","width":"220px"},
			{"contentPadding": "mmm"},
			{"contentPadding": "mmm"},
			null,
			{"class": "last"}
		];
	} else if(page_id == 'serviceReminders') {
		var column_data = [
			{ "class": "first" }, null, null, null, null, null, null, {"class": "last"}
		];
	} else if(page_id == 'upcoming' || page_id == 'grievance' || page_id == 'allot') {
		var column_data = [
			{ "class": "first" },
			null,
			{"contentPadding": "mmm","width":"220px"},
			{"contentPadding": "mmm"},
			{"contentPadding": "mmm"},
			null,
			null,
			{"class": "last"}
		];
	} else if(page_id == 'history' || page_id == 'drpdorders' || page_id == 'executiveLeave') {
		var column_data = [null, null,	null, null,	null, null, null];
	} else if(page_id == 'executiveBills' || page_id == 'editadminreminder' || page_id == 'edit_executive') {
		var column_data = [null, null, null, null, null, null, null, null, null]; bPaginate = false;
	} else if(page_id == 'ptorders' || page_id == 'viewExecutiveRewards') {
		var column_data = [null, null, null, null, null, null, null, null, null, null, null];
	} else if(page_id == 'emgorders') {
		var column_data = [null, null, null, null, null, null, null, null];
	} else if(page_id == 'edit_admin' || page_id == 'rating') {
		var column_data = [null, null, null, null, null, null];
	} else if(page_id == 'edit_offer' || page_id == 'pettyCash') {
		var column_data = [null, null, null, null, null, null, null, null, null, null, null, null, null];
	} else if(page_id == 'edit_referral' || page_id == 'ptportal' || page_id == 'pbportal') {
		var column_data = [null, null, null, null, null];
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
		$('#example1 tfoot th').each( function () {
	        var title = $(this).text();
	        $(this).html( '<input class="tfoot-search" type="text" placeholder="'+title+'" />' );
	    });
		oTable = $('#example1').dataTable({
			bSearchable: true,
			bSortable: true,
			bInfo: true,
			bLengthChange: true,
			bPaginate: bPaginate,
			bFilter: true,
			aaSorting: [],
			buttons: [
				{
					extend: 'excelHtml5',
					text: 'Export Data to Excel Document',
					className: 'btn btn-primary'
				}
			],
			"oLanguage": {
				"sEmptyTable": "No data available for your query.",
				"sSearch": ""
			},
			"dom": 'Bfrtip',
			"columns": column_data,
			"fnDrawCallback": add_callbacks()
		});
		oTable.api().columns().every( function () {
		    var that = this;
		    $('input', this.footer()).on('keyup change', function () {
		        if (that.search() !== this.value) {
		            that.search(this.value).draw();
		        }
		    });
		});
		$('#example1').css('width', '100%');
		$('#example1').css('overflow-x', 'scroll');
		$('.tfoot-search').css('width', '100px');
		$('.tfoot-search').css('align', 'left');
	}
});
function add_callbacks() {
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
	try {
		$("#example1 input[type='checkbox'], #example1 input[type='radio']").icheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green'
		});
	} catch(err) {}
}