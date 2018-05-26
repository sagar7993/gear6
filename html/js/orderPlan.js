var sources = []; var destinations = []; var directionsDisplay = []; var map; var bounds; var sourceMarker = []; var routePoints = []; var sourceIw = []; var date;
var userNames = []; var orderIds = []; var serviceCenterNames = []; var directionsService; var destinationMarker = []; var points = []; var destinationIw = [];
var colors = ['#F44336', '#e91e63', '#9c27b0', '#3f51b5', '#448AFF', '#18ffff', '#4CAF50', '#cddc39',
 '#76ff03', '#ffca28', '#fb8c00', '#795548', '#dd2c00', '#78909c', '#37474f', '#3e2723', '#1B5E20'];
map = new google.maps.Map(document.getElementById("map"), {
  center: { lat: 12.9119557, lng: 77.6343610 }, scrollwheel: true, zoom: 11, mapTypeId: google.maps.MapTypeId.ROADMAP
});
directionsService = new google.maps.DirectionsService(); date = orderPlan[Object.keys(orderPlan)[0]];
var nextWeekDate = Object.keys(orderPlan)[Object.keys(orderPlan).length-1]; nextWeekDate = new Date(nextWeekDate);
var d = nextWeekDate.getDate() + 1; nextWeekDate.setDate(d); var m = nextWeekDate.getMonth() + 1; var y = nextWeekDate.getFullYear();
if(String(d) == 1) { d = "0" + d; } if(String(m) == 1) { m = "0" + m; } var nextWeekDateString = y + "-" + m + "-" + d;
var previousWeekDate = nextWeekDate; previousWeekDate.setDate(previousWeekDate.getDate() - 17);
var d = previousWeekDate.getDate() + 1; previousWeekDate.setDate(d); var m = previousWeekDate.getMonth() + 1; var y = previousWeekDate.getFullYear();
if(String(d) == 1) { d = "0" + d; } if(String(m) == 1) { m = "0" + m; } var previousWeekDateString = y + "-" + m + "-" + d;
$("#nextWeekOrderPlan").on('click', function(e) {
  var created_form = $('<form method="POST" action="/admin/orders/orderPlan"><input type="hidden" name="startDate" value="' + nextWeekDateString + '"/></form>').appendTo('body');
  created_form.submit();
});
$("#previousWeekOrderPlan").on('click', function(e) {
  var created_form = $('<form method="POST" action="/admin/orders/orderPlan"><input type="hidden" name="startDate" value="' + previousWeekDateString + '"/></form>').appendTo('body');
  created_form.submit();
});
function kickstart(date) {
  if(date != null && date != undefined && date != "") {
    for(var slot in date) {
      for(var i = 0; i < date[slot].length; i++) {
        sources.push(new google.maps.LatLng(Number(date[slot][i]["ULatitude"]), Number(date[slot][i]["ULongitude"])));
        destinations.push(new google.maps.LatLng(Number(date[slot][i]["DLatitude"]), Number(date[slot][i]["DLongitude"])));
        userNames.push(date[slot][i]["UserName"]); orderIds.push(date[slot][i]["OId"]); serviceCenterNames.push(date[slot][i]["ScName"]);
      }
    }
    if(sources.length > 0 && destinations.length > 0 && sources.length || destinations.length) {
      bounds = new google.maps.LatLngBounds(); plotMarkers(map, sources, destinations, 0, sources.length);
    }
  }
}
kickstart(date);
function plotMarkers(map, sources, destinations, index, length) {
  sourceMarker.push(new google.maps.Marker({
    map: map, clickable: true, icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=|028cbc|000000',
    position: sources[index], title: "Customer Name : " + userNames[index] + " (" + orderIds[index] + ")"
  }));
  sourceIw.push(new google.maps.InfoWindow({
    position: sources[index], content: "Customer Name : " + userNames[index] + " (" + orderIds[index] + ")", pixelOffset: new google.maps.Size(-2,-50)
  }));
  destinationMarker.push(new google.maps.Marker({
    map: map, clickable: true, icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=|ff0000|ffffff',
    position: destinations[index], title: "Service Center : " + serviceCenterNames[index] + " (" + orderIds[index] + ")"
  }));
  destinationIw.push(new google.maps.InfoWindow({
    position: destinations[index], content: "Service Center : " + serviceCenterNames[index] + " (" + orderIds[index] + ")", pixelOffset: new google.maps.Size(-2,-50)
  }));
  index ++;
  if(index < length) {
    plotMarkers(map, sources, destinations, index, sources.length);
  } else {
    for(i=0;i<sourceMarker.length;i++) {
      bounds.extend(sourceMarker[i].getPosition());
    }
    for(i=0;i<destinationMarker.length;i++) {
      bounds.extend(destinationMarker[i].getPosition());
    }
    plotLines(map, sources, destinations, 0, sources.length, bounds);
  }
}
function plotLines(map, sources, destinations, index, length, bounds) {
  var request = {
    origin: sources[index], destination: destinations[index], travelMode: google.maps.TravelMode.DRIVING, optimizeWaypoints: true
  };
  directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      var myRoute = response.routes[0].legs[0]; points = [];
      for (var i = 0; i < myRoute.steps.length; i++) {
        for (var j = 0; j < myRoute.steps[i].lat_lngs.length; j++) {
          points.push(myRoute.steps[i].lat_lngs[j]);
        }
      }
      index ++; drawRoute(points, index);
      if(index < length) {
        plotLines(map, sources, destinations, index, length, bounds);
      } else {
        setMapCenter(map, bounds);
      }
    } else {
      alert("Directions Request from " + sources[index].toUrlValue(6) + " to " + destinations[index].toUrlValue(6) + " failed: " + status);
    }
  });
}
function setMapCenter(map, bounds) {
  map.setCenter(bounds.getCenter()); map.fitBounds(bounds);
  if(map.getZoom()> 15) { map.setZoom(15); }
}
function drawRoute(points, index) {
  var color = colors[Math.floor(Math.random() * colors.length)];
  var routeLine = new google.maps.Polyline({ path: points, strokeColor: color, strokeOpacity: 1, strokeWeight: 4 });
  routeLine.setMap(map);
  google.maps.event.addListener(routeLine, 'mouseover', function() { this.setOptions({ strokeColor: "red", strokeWeight: 10 });
    sourceMarker[index-1].setAnimation(google.maps.Animation.BOUNCE);
    destinationMarker[index-1].setAnimation(google.maps.Animation.BOUNCE);
    sourceIw[index-1].open(map); destinationIw[index-1].open(map);
  });
  google.maps.event.addListener(routeLine, 'mouseout', function() { this.setOptions({ strokeColor: color, strokeWeight: 4 });
    sourceMarker[index-1].setAnimation(null); destinationMarker[index-1].setAnimation(null);
    sourceIw[index-1].close(map); destinationIw[index-1].close(map);
  });
  sourceMarker[index-1].addListener('mouseover', function() {
    this.setAnimation(google.maps.Animation.BOUNCE);
    destinationMarker[index-1].setAnimation(google.maps.Animation.BOUNCE);
    routeLine.setOptions({ strokeColor: "red", strokeWeight: 10 });
    sourceIw[index-1].open(map); destinationIw[index-1].open(map);
  });
  sourceMarker[index-1].addListener('mouseout', function() {
    this.setAnimation(null); destinationMarker[index-1].setAnimation(null);
    routeLine.setOptions({ strokeColor: color, strokeWeight: 4 });
    sourceIw[index-1].close(map); destinationIw[index-1].close(map);
  });
  destinationMarker[index-1].addListener('mouseover', function() {
    this.setAnimation(google.maps.Animation.BOUNCE);
    sourceMarker[index-1].setAnimation(google.maps.Animation.BOUNCE);
    routeLine.setOptions({ strokeColor: "red", strokeWeight: 10 });
    sourceIw[index-1].open(map); destinationIw[index-1].open(map);
  });
  destinationMarker[index-1].addListener('mouseout', function() {
    this.setAnimation(null); sourceMarker[index-1].setAnimation(null);
    routeLine.setOptions({ strokeColor: color, strokeWeight: 4 });
    sourceIw[index-1].close(map); destinationIw[index-1].close(map);
  });
}
$(".refresh").on('click', function(e) {
  var date = $(this).attr("id"); date = orderPlan[date];
  sources = []; destinations = []; directionsDisplay = []; bounds; sourceMarker = []; routePoints = []; sourceIw = [];
  userNames = []; orderIds = []; serviceCenterNames = []; destinationMarker = []; points = []; destinationIw = [];
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: 12.9119557, lng: 77.6343610 }, scrollwheel: true, zoom: 11, mapTypeId: google.maps.MapTypeId.ROADMAP
  });
  directionsService = new google.maps.DirectionsService(); kickstart(date);
});