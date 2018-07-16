// Init Map
var map;
var markers = [];

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 6,
    center: new google.maps.LatLng(13.08798433, 100.5838692),
    mapTypeId: "roadmap",
    gestureHandling: "greedy"
  });

  // Create the search box and link it to the UI element.
  var input = document.getElementById("map-search-field");
  var searchBox = new google.maps.places.SearchBox(input);

  // Bias the SearchBox results towards current map's viewport.
  map.addListener("bounds_changed", function() {
    searchBox.setBounds(map.getBounds());
  });

  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.

  searchBox.addListener("places_changed", function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markers.
    markers.forEach(marker => {
      marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    //   places.forEach(function(place) {
    place = places[0];
    if (!place.geometry) {
      console.log("Returned place contains no geometry");
      return;
    }
    var icon = {
      url: place.icon,
      size: new google.maps.Size(71, 71),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(17, 34),
      scaledSize: new google.maps.Size(25, 25)
    };

    // Create a marker for each place.
    markers.push(
      new google.maps.Marker({
        map: map,
        title: place.name,
        position: place.geometry.location,
        draggable: true
      })
    );

    if (place.geometry.viewport) {
      // Only geocodes have viewport.
      bounds.union(place.geometry.viewport);
    } else {
      bounds.extend(place.geometry.location);
    }
    //   });
    map.fitBounds(bounds);
  });
}

function initMarker(latitude, longitude) {
  if (latitude && longitude) {
    // Clear out the old markers.
    markers.forEach(marker => {
      marker.setMap(null);
    });
    markers = [];

    var myLatlng = new google.maps.LatLng(latitude, longitude);
    // Create Initial Marker
    markers.push(
      new google.maps.Marker({
        map: map,
        title: "Location Name",
        position: myLatlng,
        draggable: true
      })
    );
    map.panTo(markers[0].getPosition());
    map.setZoom(16);
  }
}

$(document).ready(function() {
  $("#mark-location").on("click", function() {
    if (markers[0]) {
      $('input[name="lat"]').val(markers[0].getPosition().lat());
      $('input[name="lon"]').val(markers[0].getPosition().lng());
    }
  });
});
