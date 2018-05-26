function order_type_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/vendor/vendorhome/order_type_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
			if(atype == 'Monthly') {
				data.servicing = [3, 2, 1, 3];
				data.repair = [2, 3, 5, 7];
				data.insurance = [4, 3, 3, 9];
				data.query = [3, 2, 1, 3];
				data.average = [3, 2.67, 3, 6.33];
			} else {
				data.servicing = [3, 2, 1, 3, 2, 1, 3];
				data.repair = [2, 3, 5, 7, 3, 5, 7];
				data.insurance = [4, 3, 3, 9, 3, 3, 9];
				data.query = [3, 2, 1, 3, 2, 1, 3];
				data.average = [3, 2.67, 3, 6.33, 2.67, 3, 6.33];
			}
			$('#order_type_analysis').highcharts({
				title: {
					text: data.atype.name
				},
				xAxis: {
					categories: data.categories
				},
				labels: {
					items: [{
						style: {
							left: '50px',
							top: '18px',
							color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
						}
					}]
				},
				series: [{
					type: 'column',
					name: 'Servicing',
					data: data.servicing
				}, {
					type: 'column',
					name: 'Repair',
					data: data.repair
				}, {
					type: 'column',
					name: 'Insurance',
					data: data.insurance
				}, {
					type: 'column',
					name: 'Query',
					data: data.query
				}, {
					type: 'spline',
					name: 'Average',
					data: data.average,
					marker: {
						lineWidth: 2,
						lineColor: Highcharts.getOptions().colors[3],
						fillColor: 'white'
					}
				}],
				yAxis: {
					min: 0,
					title: {
						text: 'Orders Count'
					},
					allowDecimals: false
				}
			});
		}
	});
}
function pick_up_analysis(atype) {
	function get_negs(input) {
		return input * -1;
	}
	$.ajax({
		type: "POST",
		url: "/vendor/vendorhome/pick_up_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
			if(atype == 'Monthly') {
				data.pick = [5, 20, 7, 1];
				data.nopick = [3, 7, 15, 20];
			} else {
				data.pick = [1, 4, 2, 6, 5, 10, 7];
				data.nopick = [5, 20, 7, 1, 4, 8, 15];
			}
			$('#pick_up_analysis').highcharts({
				chart: {
					type: 'bar'
				},
				title: {
					text: data.atype.name
				},
				subtitle: {
					text: 'Source: gear6.in'
				},
				xAxis: [{
					categories: data.categories,
					reversed: false,
					labels: {
						step: 1
					}
				}, {
					opposite: true,
					reversed: false,
					categories: data.categories,
					linkedTo: 0,
					labels: {
						step: 1
					}
				}],
				yAxis: {
					title: {
						text: null
					},
					labels: {
						formatter: function () {
							return (Math.abs(this.value));
						}
					},
					allowDecimals: false,
					min: Math.ceil((Math.max.apply(null, data.nopick)) / 10) * -10,
					max: Math.ceil((Math.max.apply(null, data.nopick)) / 10) * 10
				},
				plotOptions: {
					series: {
						stacking: 'normal'
					}
				},
				tooltip: {
					formatter: function () {
						return '<b>' + this.series.name + ', Date :' + this.point.category + '</b><br/>' + 'Count : ' + Highcharts.numberFormat(Math.abs(this.point.y), 0);
					}
				},
				series: [{
					name: 'Pick Up',
					data: data.pick
				}, {
					name: 'No Pick Up',
					data: data.nopick.map(get_negs)
				}]
			});
		}
	});
}
function slot_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/vendor/vendorhome/slot_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
			if(atype == 'Monthly') {
				data.filled_slots = [20, 30, 10, 50];
				data.ufilled_slots = [50, 10, 20, 30];
			} else {
				data.filled_slots = [3, 2, 1, 3, 2, 1, 3];
				data.ufilled_slots = [2, 3, 5, 7, 3, 5, 7];
			}
			$('#slot_analysis').highcharts({
				chart: {
					type: 'column'
				},
				title: {
					text: data.atype.name
				},
				subtitle: {
					text: 'Source: gear6.in'
				},
				xAxis: {
					categories: data.categories,
					crosshair: true
				},
				yAxis: {
					min: 0,
					title: {
						text: 'Slots'
					}
				},
				tooltip: {
					headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
					pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
						'<td style="padding:0"><b>{point.y}</b></td></tr>',
					footerFormat: '</table>',
					shared: true,
					useHTML: true
				},
				plotOptions: {
					column: {
						pointPadding: 0.2,
						borderWidth: 0
					}
				},
				series: [{
					name: 'Filled Slots',
					data: data.filled_slots
				}, {
					name: 'Un-Filled Slots',
					data: data.ufilled_slots
				}]
			});
		}
	});
}
function delay_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/vendor/vendorhome/delay_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
			if(atype == 'Monthly') {
				data.total_orders = [100, 50, 30, 80];
				data.total_delays = [30, 10, 5, 50];
			} else {
				data.total_orders = [30, 20, 10, 30, 20, 10, 30];
				data.total_delays = [10, 9, 5, 8, 4, 10, 20];
			}
			$('#delay_analysis').highcharts({
				chart: {
					type: 'column'
				},
				title: {
					text: data.atype.name
				},
				xAxis: {
					categories: data.categories
				},
				credits: {
					enabled: false
				},
				yAxis: {
					title: {
						text: 'Orders Count'
					},
					allowDecimals: false
				},
				series: [{
					name: 'Total Orders',
					data: data.total_orders
				}, {
					name: 'Total Delays',
					data: data.total_delays
				}]
			});
		}
	});
}
function order_summary_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/vendor/vendorhome/order_summary_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
			if(atype == 'Monthly') {
				data.orders = [1000, 500, 300, 800];
			} else {
				data.orders = [30, 120, 110, 320, 120, 101, 303];
			}
			var chart = new Highcharts.Chart({
				chart: {
					renderTo: 'order_summary_analysis',
					type: 'column',
					margin: 75,
					options3d: {
						enabled: true,
						alpha: 35,
						beta: 15,
						depth: 50,
						viewDistance: 25
					}
				},
				title: {
					text: data.atype.name
				},
				subtitle: {
					text: 'Servicing + Repair + Insurance'
				},
				plotOptions: {
					column: {
						depth: 25
					}
				},
				yAxis: {
					title: {
						text: 'Orders Count'
					},
					allowDecimals: false
				},
				xAxis: {
					categories: data.categories
				},
				series: [{
					name: 'Total Orders',
					data: data.orders
				}]
			});
		}
	});
}
function status_type_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/vendor/vendorhome/status_type_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
			if(atype == 'Monthly') {
				data.allot = 33;
				data.ans = 67;
			} else {
				data.allot = 40;
				data.ans = 75;
			}
			$('#status_type_analysis').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: data.name1
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: true,
							format: '<b>{point.name}</b>: {point.percentage:.1f} %',
							style: {
								color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
							}
						}
					}
				},
				series: [{
					type: 'pie',
					name: 'of total orders -',
					data: [
					['Allotted', data.allot],
						{
							name: 'Un-Allotted',
							y: (100 - data.allot),
							sliced: true,
							selected: true
						},
					]
				}]
			});
			$('#query_type_analysis').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: data.name2
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: true,
							format: '<b>{point.name}</b>: {point.percentage:.1f} %',
							style: {
								color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
							}
						}
					}
				},
				series: [{
					type: 'pie',
					name: 'of total orders -',
					data: [
					['Answered', data.ans],
						{
							name: 'Un-Answered',
							y: (100 - data.ans),
							sliced: true,
							selected: true
						},
					]
				}]
			});
		}
	});
}
function future_orders_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/vendor/vendorhome/future_orders_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
			if(atype == 'Monthly') {
				data.allot = [100, 50, 30, 80];
				data.unallot = [30, 10, 5, 50];
			} else {
				data.allot = [30, 20, 10, 30, 20, 10, 30];
				data.unallot = [10, 9, 5, 8, 4, 10, 20];
			}
			$('#future_orders_analysis').highcharts({
				chart: {
					type: 'bar'
				},
				title: {
					text: data.atype.name
				},
				subtitle: {
					text: 'Source: gear6.in'
				},
				xAxis: {
					categories: data.categories,
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
					},
					allowDecimals: false,
				},
				tooltip: {
					valueSuffix: ' Orders'
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
					data: data.unallot
				}, {
					name: 'Allotted',
					data: data.allot
				}]
			});
		}
	});
}
function next_week_analysis() {
	$.ajax({
		type: "POST",
		url: "/vendor/vendorhome/next_week_analysis",
		data: {atype: null},
		dataType: "json",
		cache: false,
		success: function(data) {
			data.reser = [3, 2, 1, 3, 2, 1, 3];
			data.ins = [2, 3, 5, 7, 3, 5, 7];
			$('#next_week_analysis').highcharts({
				chart: {
					type: 'spline'
				},
				title: {
					text: data.atype.name
				},
				subtitle: {
					text: 'Source: gear6.in'
				},
				xAxis: {
					categories: data.categories
				},
				yAxis: {
					title: {
						text: 'Orders Count'
					},
					labels: {
						formatter: function () {
							return this.value;
						}
					},
					allowDecimals: false,
				},
				tooltip: {
					crosshairs: true,
					shared: true
				},
				plotOptions: {
					spline: {
						marker: {
							radius: 4,
							lineColor: '#666666',
							lineWidth: 1
						}
					}
				},
				series: [{
					name: 'Servicing + Repair',
					marker: {
						symbol: 'square'
					},
					data: data.reser
				}, {
					name: 'Insurance',
					marker: {
						symbol: 'diamond'
					},
					data: data.ins
				}]
			});
		}
	});
}
$(function() {
	order_type_analysis('Weekly');
	pick_up_analysis('Weekly');
	slot_analysis('Weekly');
	delay_analysis('Weekly');
	order_summary_analysis('Weekly');
	status_type_analysis('Weekly');
	future_orders_analysis('Weekly');
	next_week_analysis();
	$('.btn-toggle').on('click', function() {
		$(this).find('.btn').toggleClass('active');
		$(this).find('.btn').toggleClass('btn-primary');
		$(this).find('.btn').toggleClass('btn-default');
		var analysis_type = $(this).find('.active').text().trim();
		var analysis_id = $(this).parent().parent().next().attr('id');
		window[analysis_id](analysis_type);
	});
});