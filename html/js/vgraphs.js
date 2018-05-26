function last_ten_vendors_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/admin/vendors/last_ten_vendors_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
			var service_center = data.service_center; var series = []; var map = {};
			for(var k=0; k<data.sc_names.length; k++) {
				map[data.sc_names[k]] = {};
				for(var x=0; x<data.categories.length; x++) {
					map[data.sc_names[k]][data.categories[x]] = 0;
				}
			}
			for (var i = 0; i < service_center.length; i++) {
				for (var j = 0; j < service_center[i].length; j++) {
					if(atype !== 'Monthly') {
						map[service_center[i][j]['name']][service_center[i][j]['date']] = Number(service_center[i][j]['count']);
					} else {
						map[service_center[i][j]['name']][data.categories[i]] = Number(service_center[i][j]['count']);
					}
				}
			}
			for(var k=0; k<data.sc_names.length; k++) {
				var temp = map[data.sc_names[k]]; var values = [];
				for(key in temp) { values.push(Number(temp[key])); }
				series.push({
					type: 'column',
					name: data.sc_names[k],
					data: values
				});
			}
			$('#last_ten_vendors_analysis').highcharts({
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
				yAxis: {
					min: 0,
					title: {
						text: 'Orders Count'
					},
					allowDecimals: false
				},
				credits: {
					enabled: false
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
				series: series
			});
		}
	});
}
function top_ten_vendors_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/admin/vendors/top_ten_vendors_analysis",
		data: {atype: atype},
		dataType: "json",
		cache: false,
		success: function(data) {
			var service_center = data.service_center; var series = []; var map = {};
			for(var k=0; k<data.sc_names.length; k++) {
				map[data.sc_names[k]] = {};
				for(var x=0; x<data.categories.length; x++) {
					map[data.sc_names[k]][data.categories[x]] = 0;
				}
			}
			for (var i = 0; i < service_center.length; i++) {
				for (var j = 0; j < service_center[i].length; j++) {
					if(atype !== 'Monthly') {
						map[service_center[i][j]['name']][service_center[i][j]['date']] = Number(service_center[i][j]['count']);
					} else {
						map[service_center[i][j]['name']][data.categories[i]] = Number(service_center[i][j]['count']);
					}
				}
			}
			for(var k=0; k<data.sc_names.length; k++) {
				var temp = map[data.sc_names[k]]; var values = [];
				for(key in temp) { values.push(Number(temp[key])); }
				series.push({
					type: 'column',
					name: data.sc_names[k],
					data: values
				});
			}
			$('#top_ten_vendors_analysis').highcharts({
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
				yAxis: {
					title: {
						text: 'Orders Count'
					},
					allowDecimals: false
				},
				credits: {
					enabled: false
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
				series: series
			});
		}
	});
}
function delay_analysis(atype) {
	$.ajax({
		type: "POST",
		url: "/admin/vendors/delay_analysis",
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
				yAxis: {
					title: {
						text: 'Orders Count'
					},
					allowDecimals: false
				},
				credits: {
					enabled: false
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
		url: "/admin/vendors/dropped_analysis",
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
				yAxis: {
					title: {
						text: 'Orders Count'
					},
					allowDecimals: false
				},
				credits: {
					enabled: false
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
$(function() {
	try {
		dropped_analysis('Weekly');
		delay_analysis('Weekly');
		top_ten_vendors_analysis('Weekly');
		last_ten_vendors_analysis('Weekly');
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