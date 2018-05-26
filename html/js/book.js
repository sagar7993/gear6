var togg = 1;
var clicked = true;
var oTable = '';
var key = [];
var query = '';
var min = 100;
var max = 10000;
var max_temp = 10000;
var flag = true;
var cbCount = 0;
var am = [];
var price = [];
var loc = [];
var ftypes = [];
var splist = [];
var sweetAlertCount = 0;
jQuery.extend(jQuery.fn.dataTableExt.oSort, {
	"title-numeric-pre": function ( a ) {
		return parseFloat(a);
	},
	"title-numeric-asc": function ( a, b ) {
		return ((a < b) ? -1 : ((a > b) ? 1 : 0));
	},
	"title-numeric-desc": function ( a, b ) {
		return ((a < b) ? 1 : ((a > b) ? -1 : 0));
	}
});
$(window).load(function() {
	fillgrid();
	$('.switch').icheck('destroy');
});
$(document).on({
	ajaxStart: function() { $('#loader-gif').show();
		$('html').addClass('no-scroll'); },
	ajaxStop: function() { $('#loader-gif').hide();
		$('html').removeClass('no-scroll'); }
});
$('#company').select2({
	placeholder: "Bike Company",
	minimumResultsForSearch: 12
});
$('#bikediv').select2({
	placeholder: "Bike Model",
	minimumResultsForSearch: 10
});
$('#querytype').select2({
	placeholder: "Query Type",
	minimumResultsForSearch: 10
});
$('#stype').select2({
	placeholder: "Service Type",
	minimumResultsForSearch: 10
});
function getRatings(ScId) {
	$.ajax({
		type: "POST",
		url: "/user/book/getScRatings",
		data: {'ScId' : ScId}, 
		dataType: "text",
		cache: false,
		success: function(data) {
			data = JSON.parse(data); var template = "";
			var ratersCount = Number(data['overall']['RatersCount']);
			$('#rating_n').html("Based on " + data['overall']['RatersCount'] + " ratings");
			$('#rating_0').html(data['overall']['Rating']);
			$('#rating_sc').html(data['overall']['ScName']);
			for (var i = 1; i <= 5; i++) {
				if(isNaN(parseInt(data['ratings'][i]))) { data['ratings'][i] = 0; }
				$('#rating_' + i).html(data['ratings'][i] + " Users");
				var raters = Number(data['ratings'][i]); var percent = ((raters / ratersCount) * 100).toFixed(2);
				document.getElementById('rating_' + i + '_width').style.width = String(percent) + "%";
			}
			if(data['reviews'].length > 0) { document.getElementById('review_sc').style.display = 'block'; }
			for (var i = 0; i < data['reviews'].length; i++) {
				template += '<div class="col s12 m12 l12"><div class="row"><div class="col s4 m4 l4"><div class="row">';
				template += '<div class="col s12 m12 l12">' + data['reviews'][i]['name'] + '</div>';
				template += '<div class="col s12 m12 l12 userRating" id="userRating_' + i + '"></div>';
				template += '</div></div><div class="col s8 m8 l8">' + data['reviews'][i]['remarks'];
				template += '</div><span style="float:right;color:#028cbc;"><b>' + data['reviews'][i]['company'];
				template += ' ' + data['reviews'][i]['model'] + '</b></span><span style="float:right;">';
				template += 'Serviced bike model &nbsp; </span></div></div>';
			}
			$('#reviewContainer').html(template);
			for (var i = 0; i < data['reviews'].length; i++) {
				$('#userRating_' + i).raty({readOnly: true, score: data['reviews'][i]['rating']});	
			}
			$('#ratingModal').openModal();
		},
		error: function(error) {
			console.log(error)
		}
	});
}
function viewAll() {
	togg = 0; fillgrid(); $("#switch").prop("checked", false);
}
function fillgrid() {
	var dist__ = 3;
	if(togg == 1) {
		togg = 0;
	} else {
		togg = 1;
		dist__ = 50;
	}
	var pre = '<table id="example1" border="0" cellpadding="0" cellspacing="0" class="table custom-table">\
	<thead><tr><th class="first">Service Provider</th>';
	if ($.cookie('servicetype') == 9) {
		pre += '<th style="width:15%">Amenities</th>';
	} else if($.cookie('servicetype') == 10) {
		pre += '<th style="width:15%">PUC / EC Type</th>';
	} else if($.cookie('servicetype') == 11) {
		pre += '<th style="width:15%">Contact</th>';
	} else {
		pre += '<th style="width:15%">Rating</th>';
	}
	pre += '<th>Location</th>';
	if ($.cookie('servicetype') == 1 || $.cookie('servicetype') == 4) {
		pre += '<th>Price&nbsp;&nbsp;</th>';
	}
	if($.cookie('servicetype') == 7) {
		pre += '<th class="locate">Distance&nbsp;(Km)</th>';
	}
	if ($.cookie('servicetype') == 2 || $.cookie('servicetype') == 3) {
		pre += '<th class="last">Select</th></tr></thead><tbody>';
	} else if($.cookie('servicetype') == 7) {
		pre += '<th class="last">Connect</th></tr></thead><tbody>';
	} else if($.cookie('servicetype') == 9 || $.cookie('servicetype') == 10 || $.cookie('servicetype') == 11) {
		pre += '<th class="last">Details</th></tr></thead><tbody>';
	} else {
		pre += '<th class="last hide-on-small-only">Slots</th></tr></thead><tbody>';
	}
	var post = '</tbody></table>';
	if(dist__ == 3)
	{
		post += '<br/><button name="viewAllButton" id="viewAllButton" class="btn waves-effect waves-light hide-on-small-only" onclick="viewAll()">View All</button>';
	}
	var postData = {dist: dist__};
	$.ajax({
		type: "POST",
		url: "/user/book/get_show_room_data",
		data: postData, 
		dataType: "text",
		cache: false,
		success: function(data) {
			if((data == "" || data.length == 0 || data == null || data == undefined) && togg == 1) {
				sweetAlert("Sorry", "No service centers found.", "warning"); $("#switch").prop("checked", false);
			}
			else if((data == "" || data.length == 0 || data == null || data == undefined) && togg == 0)
			{
				fillgrid(); $("#switch").prop("checked", false);
				if(sweetAlertCount == 1)
				{
					sweetAlert("Sorry", "No nearby service centers found.", "warning");
				}				
				sweetAlertCount = 1;
			}
			if ($.cookie('servicetype') == 2 || $.cookie('servicetype') == 3 || $.cookie('servicetype') == 9 || $.cookie('servicetype') == 10 || $.cookie('servicetype') == 11) {
				var tableColumnData = [
					{ "class": "first" },
					null,
					null,
					{ "class": "last" }
				];
			} else {
				var tableColumnData = [
					{ "class": "first" },
					null,
					null,
					null,
					{ "class": "last" }
				];
			}
			if($.cookie('servicetype') == 7) {
				var columnDefs = [
					{ type: 'title-numeric', targets: 3 }
				];
				var sortColumn = [[3, "asc"]];
			} else {
				var columnDefs = [
					{ type: 'title-numeric', targets: 2 }
				];
				var sortColumn = [[2, "asc"]];
			}
			$('#example1').dataTable().fnDestroy();
			$('#detail_tab').empty();
			$('#detail_tab').html(pre + data + post);
			oTable = $('#example1').dataTable({
				bSearchable: true,
				bSortable: true,
				bInfo: true,
				bLengthChange: true,
				bFilter: true,
				bPaginate: false,
				bDestroy: true,
				"oLanguage": {
					"sEmptyTable": "No showrooms available for your query. Modify your search or click View All to view all showrooms",
					"sSearch": ""
				},
				"dom": '<"top"f>t<"clear">',
				"columns": tableColumnData,
				"columnDefs": columnDefs,
				"order": sortColumn,
				"fnDrawCallback": tableCallback()
			});
			if($.cookie('servicetype') == 3) {
				$(".top").css("display", "none");
			} else {
				$(".top").css("display", "none");
			}
			if($.cookie('servicetype') == 7) {
				$('.modal-trigger').leanModal({
					ready: function() { 
						$('html').css('overflow','hidden')
					},
					complete: function() { 
						$('html').css('overflow','auto')}
					}
				);
			}
			$('.query_cb1').each(function() {
				$(this).find('input').icheck({checkboxClass: 'icheckbox_square-green'});
			});
			$('.query_checkb').on('ifChanged', function() {
				queryCheck($(this).val());
			});
			$('#search_servicer').on('keyup change', function () {
				oTable.fnFilter(this.value, 0, true, false);
			});
			$('#example1').on('click','.cancelRow',function() {
				var tr = $(this).closest('tr');
				$row_id = $(this).attr('id').split('_')[1];
				var parent_tr = $('#sc_'+$row_id+'_'+$.cookie('servicetype')).closest('tr');
				oTable.fnClose(parent_tr); 
			});
			$('.tooltipped').tooltip({delay: 50});
			if ($.cookie('servicetype') == 1 || $.cookie('servicetype') == 4 ) {
				$.fn.dataTableExt.afnFiltering.push(function( oSettings, aData, iDataIndex ) {
					var num = [].reduce.call(aData[3],function(r,v){ return v==+v?+v+r*10:r },0);
					var iVersion = num == "-" ? 0 : num*1;
					if ( min == "" && max == "" ) {
						return true;
					} else if ( min == "" && iVersion < max ) {
						return true;
					} else if ( min < iVersion && "" == max ) {
						return true;
					} else if ( min < iVersion && iVersion < max ) {
						return true;
					}
					return false;
				});
			}
		},
		error: function(error) {
			console.log(error.responseText);
		}
	});
}
function tableCallback() {
	if($.cookie('servicetype') != 9 && $.cookie('servicetype') != 10 && $.cookie('servicetype') != 11) {
		showDetailsAction();
		rateit();
		showEmergency();
	} else {
		showPbEcPtDetails();
	}
}
function showPbEcPtDetails() {
	$('#example1 tbody tr td .details').on('click', function () {
		var tr = $(this).closest('tr');
		var id = tr.attr('id');
		if (oTable.fnIsOpen(tr)) {
			oTable.fnClose(tr);
		} else {
			var id_dump = id;
			var arrSlots = id_dump.split('_');
			var sc_id = arrSlots[1];
			if($.cookie('servicetype') == 9) {
				oTable.fnOpen( tr, '<div class="child_slider row center" id="' + id + '">\
					<div class="col s12 m5 l4 padding-right-0">\
						<div class="map" id="googleMap_' + id + '">\
						</div>\
					</div>\
					<div class="col s12 m7 l8">\
						<div class="row">\
							<div class="col s12 m6">\
								<div class="card map blue-grey darken-1">\
									<div class="card-content white-text" id="address_' + id + '">\
									</div>\
								</div>\
							</div>\
							<div class="col s12 m6">\
								<div class="card map blue-grey darken-1">\
									<div class="card-content white-text" id="contactdetails_' + id + '">\
									</div>\
								</div>\
							</div>\
						</div>\
					</div>\
					<div class="col s12">\
						<ul class="collection" id="pdlprices_' + id + '">\
						</ul>\
					</div>\
					<div class="col s12" id="amty_' + id + '">\
						<div class="col s12 m6">\
							<ul class="collection" id="amenities1_' + id + '">\
							</ul>\
						</div>\
						<div class="col s12 m6" id="amty1_' + id + '">\
							<ul class="collection" id="amenities2_' + id + '">\
							</ul>\
						</div>\
					</div>\
					<div class="col s12">\
						<ul class="collection" id="timings_' + id + '">\
						</ul>\
					</div>\
				</div>', 'info_row ' + id);
				$('div.child_slider').slideDown(800, function() {$('html, body').animate({scrollTop: $("#"+id).offset().top}, 1000);});
				fetchPbDetails(sc_id, id);
			} else if($.cookie('servicetype') == 10) {
				oTable.fnOpen( tr, '<div class="child_slider row center" id="' + id + '">\
					<div class="col s12 m5 l4 padding-right-0">\
						<div class="map" id="googleMap_' + id + '">\
						</div>\
					</div>\
					<div class="col s12 m7 l8">\
						<div class="row">\
							<div class="col s12 m6">\
								<div class="card map blue-grey darken-1">\
									<div class="card-content white-text" id="address_' + id + '">\
									</div>\
								</div>\
							</div>\
							<div class="col s12 m6">\
								<div class="card map blue-grey darken-1">\
									<div class="card-content white-text" id="contactdetails_' + id + '">\
									</div>\
								</div>\
							</div>\
						</div>\
					</div>\
					<div class="col s12">\
						<ul class="collection" id="pdlprices_' + id + '">\
						</ul>\
					</div>\
					<div class="col s12">\
						<ul class="collection" id="timings_' + id + '">\
						</ul>\
					</div>\
				</div>', 'info_row '+ id);
				$('div.child_slider').slideDown(800, function() {$('html, body').animate({scrollTop: $("#"+id).offset().top}, 1000);});
				fetchEcDetails(sc_id, id, arrSlots[2]);
			} else {
				if(arrSlots[2] == 1 || arrSlots[2] == '1') {
					var badge = '<div class="badge-container">\
						<div class="left-badge badge-text "></div>\
					</div>';
				} else {
					var badge = '';
				}
				oTable.fnOpen( tr, '<div class="child_slider row center" id="' + id + '">\
					' + badge + '<div class="col s12 m5 l4 padding-right-0">\
						<div class="map" id="googleMap_' + id + '">\
						</div>\
					</div>\
					<div class="col s12 m7 l8">\
						<div class="row">\
							<div class="col s12 m6">\
								<div class="card map blue-grey darken-1">\
									<div class="card-content white-text" id="address_' + id + '">\
									</div>\
								</div>\
							</div>\
							<div class="col s12 m6">\
								<div class="card map blue-grey darken-1">\
									<div class="card-content white-text" id="contactdetails_' + id + '">\
									</div>\
								</div>\
							</div>\
						</div>\
					</div>\
					<div class="col s12">\
						<ul class="collection" id="pdlprices_' + id + '">\
						</ul>\
					</div>\
					<div class="col s12">\
						<ul class="collection" id="timings_' + id + '">\
						</ul>\
					</div>\
				</div>', 'info_row '+ id);
				$('div.child_slider').slideDown(800, function() {$('html, body').animate({scrollTop: $("#"+id).offset().top}, 1000);});
				fetchPtDetails(sc_id, id, arrSlots[2]);
			}
		}
	});
}
function fetchPtDetails(sc_id, id, serid) {
	$.ajax({
		type: "POST",
		url: "/user/book/getPtDetails",
		data: {sc_id: sc_id, serid: serid},
		dataType: "json",
		success: function(data) {
			$('#address_' + id).html(data.address);
			$('#contactdetails_' + id).html(data.cdetails);
			if(data.pdlprices == 0 || data.pdlprices == '0') {
				$('#pdlprices_' + id).hide();
			} else {
				$('#pdlprices_' + id).html(data.pdlprices);
			}
			if(data.timings == 0 || data.timings == '0') {
				$('#timings_' + id).hide();
			} else {
				$('#timings_' + id).html(data.timings);
			}
			showMarker(data.lat, data.lon, id);
		}
	});
}
function fetchEcDetails(ec_id, id, serid) {
	$.ajax({
		type: "POST",
		url: "/user/book/getEcDetails",
		data: {ec_id: ec_id, serid: serid},
		dataType: "json",
		success: function(data) {
			$('#address_' + id).html(data.address);
			$('#contactdetails_' + id).html(data.cdetails);
			$('#pdlprices_' + id).html(data.pdlprices);
			$('#timings_' + id).html(data.timings);
			showMarker(data.lat, data.lon, id);
		}
	});
}
function fetchPbDetails(pb_id, id) {
	$.ajax({
		type: "POST",
		url: "/user/book/getPbDetails",
		data: {pb_id: pb_id},
		dataType: "json",
		success: function(data) {
			$('#address_' + id).html(data.address);
			$('#contactdetails_' + id).html(data.cdetails);
			$('#pdlprices_' + id).hide();
			if(data.amenities1) {
				$('#amenities1_' + id).html(data.amenities1);
			} else {
				$('#amty_' + id).hide();
			}
			if(data.amenities2) {
				$('#amenities2_' + id).html(data.amenities2);
			} else {
				$('#amty1_' + id).hide();
			}
			if(data.timings) {
				$('#timings_' + id).html(data.timings);
			} else {
				$('#timings_' + id).hide();
			}
			showMarker(data.lat, data.lon, id);
		}
	});
}
function showEmergency() {
	if($.cookie('servicetype') == 7) {
		$('#example1 tbody tr td .map-click').on('click', function() {
			var tr = $(this).closest('tr');
			var id = tr.attr('id');
			var id_dump = id;
			var arrSlots = id_dump.split('_');
			var sc_id = arrSlots[1];
			if (oTable.fnIsOpen(tr)) {
				oTable.fnClose(tr);
			} else {
				$.ajax({
					type: "POST",
					url: "/user/book/getEmgDetails",
					data: {sc_id: sc_id},
					dataType: "json",
					cache: false,
					success: function(data) {
						oTable.fnOpen(tr, data.html, "info_row");
						showMarker(data.lati, data.longi, sc_id);
					}
				});
			}
		});
	}
}
isPopVisible = function() {
	$('#location, #amenityList, #priceList, #vendor, #ftypeList, #sProvider').each(function() {
		if($(this).is(':visible')) {
			$retVal = true;
			return false;
		}
	});
	return $retVal;
}
addAreaFilters = function() {
	var area_facets = '';
	for(var i = 1; i <= availableLocations.length; i++) {
		area_facets +='<li class="filterList filterListLI">\
			<input type="checkbox" class="ckb loc_ckb" id="'+ i + '" data-item="location" data-val="' + availableLocations[i-1] + '" data-id="' + i + '">\
			<span class="margin-left-5px">' + availableLocations[i-1] + '</span>\
		</li>';
	}
	$('#location').html(area_facets);
}
hidePopUp = function() {
	$('#location, #amenityList, #priceList, #vendor, #ftypeList').hide();
}
createFacet = function(val,id) {
	$('.facetContainer').append('<div id="facet_'+id+'" class="facetBox">'+val+'\
		<span class="close facetClose" data-id="'+id+'"><b>&times;</b></span>\
	</div');
}
function showDetailsAction() {
	$('#example1 tbody tr td .details ,#example1 tbody .details').on('click', function () {
		var tr = $(this).closest('tr');
		var id = tr.attr('id');
		if (oTable.fnIsOpen(tr)) {
		} else {
			if($.cookie('servicetype') == 1 || $.cookie('servicetype') == 2 || $.cookie('servicetype') == 4) {
				var id_dump = id;
				var arrSlots = id_dump.split('_');
				var sc_id = arrSlots[1];
				if (is_logged_in === true) {
					oTable.fnOpen( tr, '<div class="child_slider row" id="' + id + '">\
						<div class="col s12 m12">\
						<form action="/user/book/book_order" method="POST">\
						<div class="col s12 m5 l4 padding-right-0">\
						<div class="map-text">Locate your Service Center</div>\
						<div id="googleMap_' + sc_id + '" class="map"></div>\
						</div>\
						<div class="col s12 m7 l8">\
						<div class="right"><span class="close facetClose cancelRow" id="cancel_'+ sc_id +'"><b>&times;</b></span></div>\
						<ul id="shwRum_buk_slots_' + sc_id + '" class="col s12 slotsContainer"></ul>\
						<div class="col s12"><div class="col s12 m6 center-align">\
						<input type="text" value="' + univ_user_phone + '" disabled><div><a href="/user/account/uprofile/3" style="cursor: pointer;">Change Account Phone Number</a></div></div>\
						<div class="col s12 m4 right">\
						<button type="submit" name="submitDet" class="btn waves-effect waves-light" id="proceed_' + sc_id + '" disabled="true">Proceed</button>\
						</div></div></form></div></div>\
						<div class="col s12 m6"></div>\
						<div class="col s12 m6"></div>\
						<div class="col s12 m6"></div>\
						<div class="col s12 m6"></div>\
						<div class="col s12 m6">\
							<ul class="collection with-header z-depth-1" id="offers_' + sc_id + '">\
							</ul>\
						</div>\
						<div class="col s12 m6">\
							<ul class="collection with-header z-depth-1" id="exclusives_' + sc_id + '">\
							</ul>\
						</div>\
						<div class="row" id="media_details">\
							<div class="col s12">\
								<label>Media</label>\
							</div>\
							<div class="col s12 lgrey-bg padding-20px" id="sc_media_' + sc_id + '">\
							</div>\
						</div>\
						</div>', 'info_row '+ id);
				} else {
					oTable.fnOpen( tr, '<div class="child_slider row" id="' + id + '">\
						<div class="col s12 m12">\
						<form action="/user/book/book_order" method="POST">\
						<div class="col s12 m5 l4 padding-right-0">\
						<div class="map-text">Locate your Service Center</div>\
						<div id="googleMap_' + sc_id + '" class="map"></div>\
						</div>\
						<div class="col s12 m7 l8">\
						<div class="right"><span class="close facetClose cancelRow" id="cancel_'+ sc_id +'"><b>&times;</b></span></div>\
						<ul id="shwRum_buk_slots_' + sc_id + '" class="col s12 slotsContainer"></ul>\
						<div class="col s12"><div class="col s12 m6 center-align">\
						<input type="text" maxlength="10" name="phone" id="phNum_' + sc_id + '" placeholder="Mobile Number"></div>\
						<div class="col s12 m4 right">\
						<button type="submit" name="submitDet" class="btn waves-effect waves-light" id="proceed_' + sc_id + '" disabled="true">Proceed</button>\
						</div></div></form></div></div>\
						<div class="col s12 m6">\
							<ul class="collection with-header z-depth-1" id="offers_' + sc_id + '">\
							</ul>\
						</div>\
						<div class="col s12 m6">\
							<ul class="collection with-header z-depth-1" id="exclusives_' + sc_id + '">\
							</ul>\
						</div>\
						<div class="row" id="media_details">\
							<div class="col s12">\
								<label>Media</label>\
							</div>\
							<div class="col s12 lgrey-bg padding-20px" id="sc_media_' + sc_id + '">\
							</div>\
						</div>\
						</div>', 'info_row '+ id);
				}
				$('div.child_slider').slideDown(800, function() {$('html, body').animate({scrollTop: $("#"+id).offset().top}, 1000);});
				shwSlots(sc_id);
			}
		}
	});
}
function shwSlots(sc_id) {
	$.ajax({
		type: "POST",
		url: "/user/book/getSlots",
		data: {sc_id: sc_id},
		dataType: "json",
		success: function(data) {
			$('#shwRum_buk_slots_' + sc_id).prepend(data.html);
			showMarker(data.lat, data.lon, sc_id);
			showOffers(data.offers, sc_id);
			showExclusives(data.exclusives, sc_id);
			showMedia(data.sc_media, sc_id);
			$('.slotsContainer').each(function() {
				$(this).find('input').icheck({
					checkboxClass: 'icheckbox_square-green',
					radioClass: 'iradio_square-green slotRadio'
				});
			});
			$('#phNum_' + sc_id).on('input', function() {
				var ph = $("#phNum_" + sc_id).val();
				if (is_logged_in === false) {
					if (ph >= 7000000000) {
						checkslots(sc_id);
					} else {
						$("#proceed_" + sc_id).attr('disabled','disabled');
					}
				} else {
					checkslots(sc_id);
				}
			});
			$('.slot_radio_event').on('ifChecked', function() {
				var sc_id = $(this).val();
				var sc_id = parseInt(sc_id.split('_')[1]);
				checkslots(sc_id);
			});
		}
	});
}
function showExclusives(exclusives, sc_id) {
	if(exclusives != "" && exclusives !== null && typeof exclusives !== "undefined") {
		var html = '<li class="collection-header"><h5>Exclusives</h5></li>';
		for(var i = 0; i < exclusives.length; i++) {
			html += '<li class="collection-item">\
				<div class="row">\
					<div class="col s12"><b>' + exclusives[i].ETitle + '</b></div>\
					<div class="col s12 margin-top-10px">' + exclusives[i].EDesc + '</div>\
				</div>\
			</li>';
		}
		$('#exclusives_' + sc_id).html(html);
	} else {
		$('#exclusives_' + sc_id).hide();
	}
}
function showOffers(offers, sc_id) {
	if(offers != "" && offers !== null && typeof offers !== "undefined") {
		var html = '<li class="collection-header"><h5>Offers</h5></li>\
			<li class="collection-item hide-on-small-only" style="max-height:40px;">\
			<div class="row">\
				<div class="col s12 m4">Offer Title</div>\
				<div class="col s12 m4">Valid Upto</div>\
				<div class="col s12 m4">Price</div>\
			</div></li>';
		for(var i = 0; i < offers.length; i++) {
			html += '<li class="collection-item">\
				<div class="row">\
					<div class="col s12 m4"><b>' + offers[i].OTitle + '</b></div>\
					<div class="col s12 m4"><b>' + offers[i].OTill + '</b></div>\
					<div class="col s12 m4"><b>Rs. ' + offers[i].Price + '</b></div>\
					<div class="col s12 margin-top-10px">' + offers[i].ODesc + '</div>\
				</div>\
			</li>';
		}
		$('#offers_' + sc_id).html(html);
	} else {
		$('#offers_' + sc_id).hide();
	}
}
function showMedia(sc_media, sc_id) {
	if(sc_media != "" && sc_media !== null && typeof sc_media !== "undefined") {
		var html = '';
		for(var i = 0; i < sc_media.length; i++) {
			html += '<div class="col s12 m4 center-align">\
				<img class="materialboxed" height="150px" width="150px" src="' + sc_media[i] + '">\
			</div>';
		}
		$('#sc_media_' + sc_id).html(html);
		$('.materialboxed').materialbox();
	} else {
		$('#sc_media_' + sc_id).hide();
		$('#media_details').hide();
	}
}
function queryCheck(present_value) {
	var ph = $("#phNum").val();
	var qtype = $("#querytype").val();
	var select_count = 0;
	var count = 0;
	var e3 = 0;
	var e4 = 0;
	if (is_logged_in === false) {
		if (ph >= 7000000000  && ph <= 9999999999 && !isNaN(ph)) {
		} else {
			e3 = 1;
		}
	}
	$.each($("input[name='query[]']:checked"), function() {
		count += 1;
	});
	if (count >= 4) {
		if (e3 == 1) {
			e4 = 1;
			alert("You can only select 3 Service Centres. Also, Enter a valid phone number to continue.");
			setTimeout(function() { $("input[name='query[]'][value='" + present_value + "']").icheck('unchecked'); }, 100);
		} else {
			alert("You can only select 3 Service Centres.");
			setTimeout(function() { $("input[name='query[]'][value='" + present_value + "']").icheck('unchecked'); }, 100);
		}
	} else if (count == 0) {
		e4 = 1;
	}
	if(qtype === "" || qtype == "" || qtype === null || qtype == null) {
		e3 = 1;
	}
	if (e3 == 0 && e4 == 0) {
		$("#query_proceed").removeAttr('disabled');
	} else {
		$("#query_proceed").attr('disabled', 'disabled');
	}
}
function rateit() {
	$('.rate_it_i_say').each(function(i, obj) {
		var score = $(this).html();
		$(this).html('');
		$(this).raty({
			readOnly: true,
			score: score,
			showHalf: true
		});
	});
}
function showMarker(lat, lon, sc_id) {
	var gMarker = null;
	var center = null;
	var Coba1center = new google.maps.LatLng(lat, lon);
	var marker = new google.maps.Marker({
		position:Coba1center,
	});
	gMarker = marker;
	center = Coba1center;
	google.maps.event.trigger(gMarker, 'click', {
		latLng: center
	});
	var mapCenter = new google.maps.LatLng(lat,lon);
	var infowindow = null;
	var mapProp = {
		center:mapCenter,
		zoom:17,
		mapTypeId:google.maps.MapTypeId.ROADMAP
	};
	var map = new google.maps.Map(document.getElementById("googleMap_" + sc_id), mapProp);
	google.maps.event.addListener(marker, 'click', function() {
		map.setZoom(20);
		map.setCenter(marker.getPosition());
		if (infowindow) {
			infowindow.close();
		}
		infowindow = new google.maps.InfoWindow();
		infowindow.setContent("Coba1")
		infowindow.open(map,marker);
	});
	marker.setMap(map);
}
function checkslots(sc_id) {
	var e1 = $("#phNum_" + sc_id).val();
	var x = 0;
	var e2 = 0;
	var e3 = 0;
	$(".slot_" + sc_id).each(function () {
		if($(this).is(':checked')) {
			e2 = 1;
		}
	});
	if (is_logged_in === true) {
		if(e2 == 1) {
			$("#proceed_" + sc_id).removeAttr('disabled');
		} else {
			$("#proceed_" + sc_id).attr('disabled','disabled');
		}
	} else {
		if(e1 == "") {
			e3 = 0;
		} else {
			if (e1 < 7000000000 || e1 > 9999999999 || isNaN(e1)) {
				alert("Please Enter a valid mobile number");
			} else {
				e3 = 1;
			}
		}
		if(e3 == 0 || e2 == 0) {
			x = 1;
		}
		if(x == 0) {
			$("#proceed_" + sc_id).removeAttr('disabled');
		} else {
			$("#proceed_" + sc_id).attr('disabled','disabled');
		}
	}
}
$(function() {
	$(document).keyup(function(e) {
		if (e.keyCode == 27) {
			if(isPopVisible()) {
				hidePopUp();
			}
		}
	});
	$('#locationFilter, #vendorFilter, #amFilter, #priceFilter, #ftypeFilter, #spFilter').hover(function() {
		if(!$('#'+$(this).attr('data-filter')).is(':visible')) {
			hidePopUp();
		}
		$('#'+$(this).attr('data-filter')+'-ch').toggle(10, "linear");
		$('#'+$(this).attr('data-filter')).slideToggle(10, "linear");
		if($('#'+$(this).attr('data-filter')).hasClass('vendorSearch')) {
			$('#search_servicer').focus();
		}
	});
	$(document).on('ifChecked', '.ckb', function(event) {
		cbCount ++ ;
		if(cbCount > 1) {
			$('#resetFilters').show();
		}
		createFacet($(this).attr('data-val'),$(this).attr('data-id'));
		var facet = $(this).attr('data-val');
		if($(this).attr('data-item') == 'amenity') {
			am.push(facet);
			var query = '';
			for (var i = 0; i < am.length; i++) {
				if (i > 0) { query += '|'; }
				query += am[i];
			}
			if($.cookie('servicetype') == 9) {
				oTable.fnFilter(query, 1, true, false);
			} else {
				oTable.fnFilter(query, 0, true, false);
			}
		} else if($(this).attr('data-item') == 'splist') {
			splist.push(facet);
			var query = '';
			for (var i = 0; i < splist.length; i++) {
				if (i > 0) { query += '|'; }
				query += splist[i];
			}
			oTable.fnFilter(query, 0, true, false);
		} else if($(this).attr('data-item') == 'ftype') {
			ftypes.push(facet);
			var query = '';
			for (var i = 0; i < ftypes.length; i++) {
				if (i > 0) { query += '|'; }
				query += ftypes[i];
			}
			oTable.fnFilter(query, 1, true, false);
		} else if($(this).attr('data-item') == 'pr') {
			price.push(parseInt($(this).attr('data-max'), 10));
			price.push(parseInt($(this).attr('data-min'), 10));
			max = Math.max.apply(Math,price);
			min = Math.min.apply(Math,price);
			oTable.fnDraw();
		} else if($(this).attr('data-item') == 'location') {
			loc.push(facet);
			var loc_query = '';
			for (var i = 0; i < loc.length; i++) {
				if (i > 0) { loc_query += '|'; }
				loc_query += loc[i];
			}
			oTable.fnFilter(loc_query, 2, true, false);
		}
	});
	$(document).on('ifUnchecked', '.ckb', function(event) {
		cbCount -- ;
		if(cbCount == 0) {
			$('#resetFilters').hide();
		}
		$('#facet_'+$(this).attr('data-id')).remove();
		var facet = $(this).attr('data-val');
		if($(this).attr('data-item') == 'amenity') {
			am.splice(am.indexOf(facet), 1);
			var query = '';
			for (var i = 0; i < am.length; i++) {
				if (i > 0) { query += '|'; }
				query += am[i];
			}
			if($.cookie('servicetype') == 9) {
				oTable.fnFilter(query, 1, true, false);
			} else {
				oTable.fnFilter(query, 0, true, false);
			}
		} else if($(this).attr('data-item') == 'splist') {
			splist.splice(splist.indexOf(facet), 1);
			var query = '';
			for (var i = 0; i < splist.length; i++) {
				if (i > 0) { query += '|'; }
				query += splist[i];
			}
			oTable.fnFilter(query, 0, true, false);
		} else if($(this).attr('data-item') == 'ftype') {
			ftypes.splice(ftypes.indexOf(facet), 1);
			var query = '';
			for (var i = 0; i < ftypes.length; i++) {
				if (i > 0) { query += '|'; }
				query += ftypes[i];
			}
			oTable.fnFilter(query, 1, true, false);
		} else if($(this).attr('data-item') == 'pr') {
			price.splice(price.indexOf(parseInt($(this).attr('data-max'), 10)), 1);
			price.splice(price.indexOf(parseInt($(this).attr('data-min'), 10)), 1);
			if(price.length == 0) {
				min = 0;
				max = 10000;
			} else {
				max = Math.max.apply(Math,price);
				min = Math.min.apply(Math,price);
			}
			oTable.fnDraw();
		} else if($(this).attr('data-item') == 'location') {
			loc.splice(loc.indexOf(facet),1);
			loc_query = '';
			for (var i = 0; i < loc.length; i++) {
				if (i > 0) { loc_query += '|'; }
				loc_query += loc[i];
			}
			oTable.fnFilter(loc_query, 2, true, false);
		}
	});
	$('#resetFilters').on('click', function() {
		$('.ckb').icheck('unchecked');
	});
	$(document).on('click', '.facetClose', function() {
		$('#'+$(this).attr('data-id')).icheck('unchecked');
		$('#facet_'+$(this).attr('data-id')).remove();
	});
	$('.switch').on('click',function(){
		if($('#switch').is(':checked')){
			fillgrid();
		}
	});
	$('#open').on('click', function() {
		is_query_modified = true;
		$('#search-box').slideToggle("slow");
		$('#area').val($.cookie('area'));
		$("#stype").select2("val", $.cookie('servicetype'));
		$("#company").select2("val", $.cookie('company'));
		$('#datepicker').val($.cookie('date_'));
		$('#ulatitude').val($.cookie('qlati'));
		$('#ulongitude').val($.cookie('qlongi'));
		initiateGooglePlaces();
	});
	$('#close').on('click', function(e) {
		e.preventDefault();
		$('#search-box').slideToggle("slow");
		$('#div-text-container').css("display","block");
	});
	$('#query_proceed').on('click', function() {
		var sc_ids = new Array();
		var ph = $("#phNum").val();
		var qtype = $("#querytype").val();
		$.each($("input[name='query[]']:checked"), function() {
			sc_ids.push($(this).val());
		});
		sc_ids.toString();
		var created_form = $('<form action="/user/book/book_order" method="POST">\
			<input type="hidden" name="phone" value="' + ph + '" /><input type="hidden" name="qtype" value="' + qtype + '" />\
			<input type="hidden" name="sc_ids" value="' + sc_ids + '" />\
			<input type="submit" name="query_submit" value="submit" /></form>').appendTo('body');
		created_form.submit();
	});
	$('#phNum').on('input', function() {
		queryCheck();
	});
});
function format (name, value) {
	return '<div>Name: ' + name + '<br />Value: ' + value + '</div>';
}