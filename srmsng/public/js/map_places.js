// Init Map
var map;
// Place marker
var marker;

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 6,
    center: new google.maps.LatLng(13.08798433, 100.5838692),
    mapTypeId: "roadmap",
    gestureHandling: "greedy",
    disableDoubleClickZoom: true
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
    marker.setMap(null);

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();

    place = places[0];
    if (!place.geometry) {
      console.log("Returned place contains no geometry");
      return;
    }

    // Create a marker for the location
    marker = new google.maps.Marker({
      map: map,
      title: place.name,
      position: place.geometry.location,
      draggable: true
    });

    if (place.geometry.viewport) {
      // Only geocodes have viewport.
      bounds.union(place.geometry.viewport);
    } else {
      bounds.extend(place.geometry.location);
    }
    map.fitBounds(bounds);
  });
  // Double Click to Mark
  google.maps.event.addListener(map, "dblclick", function(e) {
    var latLng = e.latLng;
    // Clear existing markers (if any)
    if (marker) {
      marker.setMap(null);
    }
    // Add Marker to the clicked position
    marker = new google.maps.Marker({
      map: map,
      position: latLng,
      draggable: true
    });
  });
}

function initMarker(latitude, longitude) {
  if (latitude && longitude) {
    var myLatlng = new google.maps.LatLng(latitude, longitude);
    // Create Initial Marker
    if (marker) {
      // Clear existing markers (if any)
      marker.setMap(null);
    }
    marker = new google.maps.Marker({
      map: map,
      title: "Location Name",
      position: myLatlng,
      draggable: true
    });

    // Pan and zoom to marker
    map.panTo(marker.getPosition());
    map.setZoom(16);
  } else {
    if (marker) {
      // Clear existing markers (if any)
      marker.setMap(null);
    }
    // Pan the map back to its initial position and zoom.
    map.setZoom(6);
    map.setCenter(new google.maps.LatLng(13.08798433, 100.5838692));
  }
}

$(document).ready(function() {
  // Set the value of the input in modal when the location is marked
  $("#mark-location").on("click", function() {
    if (marker) {
      $('#add-location-form input[name="lat"]').val(marker.getPosition().lat());
      $('#add-location-form input[name="lon"]').val(marker.getPosition().lng());
    }
  });
});
