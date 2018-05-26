var sources = []; var serviceCenterLocations = []; var places = []; var count = []; var userNames = [];
var sourceMarker = []; var serviceCenterMarker = []; var sourceIw = []; var serviceCenterIW = [];
var serviceCenterPlaces = []; var serviceCenterCount = []; var serviceCenterNames = [];
$('.dpDate2').datepicker ({
    'dateFormat': "yy-mm-dd",
    "setDate": new Date(),
    'autoclose': true
});
var select2a = $('#bikeBrands').select2({
    placeholder: "Select Bike Brands",
    minimumResultsForSearch: 10,
    containerCssClass: "cityCombo12"
});
select2a.val(null).trigger("change");
var select2b = $('#serviceCenters').select2({
    placeholder: "Select Service Centers",
    minimumResultsForSearch: 10,
    containerCssClass: "cityCombo12"
});
select2b.val(null).trigger("change");
for(var order in orderMap) {
    order = order.split(",");
    sources.push(new google.maps.LatLng(Number(order[0]), Number(order[1])));
    count.push(orderMap[order].length); places.push(order[2]); userNames.push(order[3]);
}
for(var serviceCenterLocation in serviceCenterLocationMap) {
    serviceCenterLocation = serviceCenterLocation.split(",");
    serviceCenterLocations.push(new google.maps.LatLng(Number(serviceCenterLocation[0]), Number(serviceCenterLocation[1])));
    serviceCenterCount.push(serviceCenterLocationMap[serviceCenterLocation]); serviceCenterPlaces.push(serviceCenterLocation[2]);
    serviceCenterNames.push(serviceCenterLocation[3]);
}
function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 12.9119557, lng: 77.6343610},
        scrollwheel: true,
        zoom: 10
    });
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });
    searchBox.addListener('places_changed', function() {
        var place = searchBox.getPlaces(); var latitude = place[0].geometry.location.lat();
        var longitude = place[0].geometry.location.lng(); var place_name = place[0].name;
        var tempLocation = []; tempLocation.push(new google.maps.LatLng(latitude, longitude));
        var searchMarker = new google.maps.Marker({
            map: map,
            icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=' + place_name.charAt(0).toUpperCase() + '|00ff00|000000',
            position: tempLocation[0],
            animation:google.maps.Animation.BOUNCE,
        });
        var searchInfoWindow = new google.maps.InfoWindow({
            position: tempLocation[0],
            content: place_name
        });
        searchMarker.addListener('click', function() {
            searchInfoWindow.open(map);
        });
        input.value = "";
    });
    for(var i = 0; i < sources.length; i++) {
        sourceMarker.push(new google.maps.Marker({
            map: map,
            icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=' + count[i] + '|028cbc|000000',
            position: sources[i]
        }));
        sourceIw.push(new google.maps.InfoWindow({
            position: sources[i],
            content: userNames[i] + " (" + places[i] + ") : " + count[i] + " Orders"
        }));
    }
    if(sources.length > 0) {
        attachIWListeners(0, sources.length, map);
    }
    for(var i = 0; i < serviceCenterLocations.length; i++) {
        serviceCenterMarker.push(new google.maps.Marker({
            map: map,
            icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=' + serviceCenterCount[i] + '|ff0000|ffffff',
            position: serviceCenterLocations[i]
        }));
        serviceCenterIW.push(new google.maps.InfoWindow({
            position: serviceCenterLocations[i],
            content: serviceCenterNames[i] + " (" + serviceCenterPlaces[i] + ") : " + serviceCenterCount[i] + " Orders"
        }));
    }
    if(serviceCenterLocations.length > 0) {
        attachSCIWListeners(0, serviceCenterLocations.length, map);
    }
}
function attachIWListeners(index, loopCount, map) {
    sourceMarker[index].addListener('click', function() {
        sourceIw[index].open(map);
    });
    if((index + 1) < loopCount) {
        attachIWListeners((index + 1), loopCount, map);
    }
}
function attachSCIWListeners(index, loopCount, map) {
    serviceCenterMarker[index].addListener('click', function() {
        serviceCenterIW[index].open(map);
    });
    if((index + 1) < loopCount) {
        attachSCIWListeners((index + 1), loopCount, map);
    }
}
initMap();
function validate_order_map() {
    var bikeBrands = $("#bikeBrands").val(); var serviceCenters = $("#serviceCenters").val();
    var startDate = $("#startDate").val(); var endDate = $("#endDate").val(); var x = 0;
    if(startDate == null || startDate == undefined || startDate == "" || startDate == []) {
        x = 1;
    }
    if(endDate == null || endDate == undefined || endDate == "" || endDate == []) {
        x = 1;
    }
    if(new Date(startDate) > new Date(endDate)) {
        x = 1;
    }
    if(x == 0) {
        $("#filter").removeAttr('disabled');
    } else {
        $("#filter").attr('disabled','disabled');
    }
}
$('#filter').on('click', function(e) {
    var serviceCenters = $("#serviceCenters").val();
    if(serviceCenters != null && serviceCenters != undefined && serviceCenters != "" && serviceCenters != []) {
        serviceCenters = serviceCenters.join(",");
    } else {
        serviceCenters = "";
    }
    var bikeBrands = $("#bikeBrands").val();
    if(bikeBrands != null && bikeBrands != undefined && bikeBrands != "" && bikeBrands != []) {
        bikeBrands = bikeBrands.join(",");
    } else {
        bikeBrands = "";
    }
    var startDate = $("#startDate").val(); var endDate = $("#endDate").val();
    var form = '<form action="/admin/orders/orderDemography" method="POST">';
    form += '<input type="hidden" name="bikeBrands" value="' + bikeBrands + '" />';
    form += '<input type="hidden" name="serviceCenters" value="' + serviceCenters + '" />';
    form += '<input type="hidden" name="startDate" value="' + startDate + '" />';
    form += '<input type="hidden" name="endDate" value="' + endDate + '" />';
    form += '<input type="submit" name="orderMap" value="submit" /></form>';
    var created_form = $(form).appendTo('body');
    created_form.submit();
});
function populate_service_centers() {
    var bikeBrands = $("#bikeBrands").val();
    if(bikeBrands != null && bikeBrands != undefined && bikeBrands != "" && bikeBrands != []) {
        bikeBrands = bikeBrands.join(","); var html = ""; var sel_options = '';
        $.ajax({
            type: "POST",
            url: "/admin/orders/get_service_centers_by_bike_brands",
            data: {bikeBrands: bikeBrands},
            dataType: "json",
            success: function (data) {
                for(var i = 0; i < data.length; i++) {
                    sel_options += '<option value="' + data[i]['ScId'].trim() + '">' + data[i]['ScName'].trim() + '</option>';
                }
                $('#serviceCenters').html(sel_options);
                var select2b = $('#serviceCenters').select2({
                    placeholder: "Select Service Centers",
                    minimumResultsForSearch: 10,
                    containerCssClass: "cityCombo12"
                });
                select2b.val(null).trigger("change");
            }
        });
    } 
}