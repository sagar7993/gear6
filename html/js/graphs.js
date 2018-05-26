function order_type_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/admin/orders/order_type_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
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
		url: "/admin/orders/pick_up_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
			var scale_value = Math.max(Math.ceil((Math.max.apply(null, data.nopick)) / 10) * -10, Math.ceil((Math.max.apply(null, data.pick)) / 10) * 10);
			$('#pick_up_analysis').highcharts({
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
					name: 'Pickup',
					data: data.pick
				}, {
					name: 'Drop',
					data: data.drop
				}, {
					name: 'Pickup & Drop',
					data: data.pickup_drop
				}]
			});
		}
	});
}
function emg_pt_order_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/admin/orders/emg_pt_order_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
			$('#emg_pt_order_analysis').highcharts({
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
					categories: data.categories
				},
				credits: {
					enabled: false
				},
				yAxis: {
					title: {
						text: 'Emergency / Puncture Orders Count'
					},
					allowDecimals: false
				},
				series: [{
					name: 'Emergency Orders',
					data: data.emergency
				}, {
					name: 'Puncture Orders',
					data: data.puncture
				}]
			});
		}
	});
}
function delay_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/admin/orders/delay_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
			$('#delay_analysis').highcharts({
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
function dropped_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/admin/orders/dropped_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
			$('#dropped_analysis').highcharts({
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
					name: 'Total Dropped Orders',
					data: data.total_dropped
				}]
			});
		}
	});
}
function order_summary_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/admin/orders/order_summary_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
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
		url: "/admin/orders/status_type_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
			$('#status_type_analysis').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: data.name1
				},
				subtitle: {
					text: 'Source: gear6.in'
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
				subtitle: {
					text: 'Source: gear6.in'
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
		url: "/admin/orders/future_orders_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
			$('#future_orders_analysis').highcharts({
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
		url: "/admin/orders/next_week_analysis",
		data: {atype: null},
		dataType: "json",
		cache: false,
		success: function(data) {
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
	try {
		emg_pt_order_analysis('Weekly');
		dropped_analysis('Weekly');
		order_type_analysis('Weekly');
		pick_up_analysis('Weekly');
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
	} catch (err) {
		// Do Nothing
	}
});