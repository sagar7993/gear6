function order_type_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/business/bizhome/order_type_analysis",
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
		url: "/business/bizhome/pick_up_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
			var scale_value = Math.max(Math.ceil((Math.max.apply(null, data.nopick)) / 10) * -10, Math.ceil((Math.max.apply(null, data.pick)) / 10) * 10);
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
					min: scale_value * -1,
					max: scale_value
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
function delay_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/business/bizhome/delay_analysis",
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
		url: "/business/bizhome/order_summary_analysis",
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
		url: "/business/bizhome/status_type_analysis",
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
		}
	});
}
function future_orders_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/business/bizhome/future_orders_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
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
		url: "/business/bizhome/next_week_analysis",
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
});