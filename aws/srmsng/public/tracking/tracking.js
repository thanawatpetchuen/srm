// Map Initialization
var map;

// Object containing all markers.
// The key is the location_code (for locations) and fse_code (for FSEs).
var markers = {};

// Tells the page if FSE selection is taking place
var onSelect = false;

// Popup telling the information of locations/FSEs
var infowindow;

// Assign FSE Modal
// This function sets up the modal by filling in the sitename, problem_type, and asset_problem
// of the selected location into the corresponding fields.
// This function does not open the modal.
function setUpModal() {
  var code = $(this).data("entry-id");

  $("input[name='sitename']").val(markers[code].title);
  $('input[name="problem_type"]').val(markers[code].problem_type);
  $('textarea[name="asset_problem"]').val(markers[code].asset_problem);

  // Remove the previous location's selected fse
  $(".selected-fse .fse").remove();
  $("#fse-code-input").html("");
}

// Cancel button in the modal
$("#assign-cancel").on("click", function() {
  // Clear modal data on cancel
  $("#assign-modal-site input").val("");

  // Deselect the FSEs
  for (var code in markers) {
    markers[code].selected = false;
  }
});

// Select from map
// Bind a function to #select-form-map button in Assign FSE Modal
$("#select-from-map").on("click", function() {
  onSelect = true;

  // Display the current list of selected FSEs at the bottom of the page
  $(".tracking-select").css("display", "block");

  // Close infowindow
  infowindow.close();

  // Change the icon of the FSEs that are assigned to that location to a checkmark.
  for (var code in markers) {
    if (markers[code].selected) {
      markers[code].setIcon("/srmsng/src/image/track-icons/fse-available-check.png");
    }
  }
});

// Done Selection
$("#select-done").on("click", function() {
  onSelect = false;

  // Hides the current list of selected FSEs from the bottom of the page
  $(".tracking-select").css("display", "none");

  $("#selected-fse").html($("#selected-fse-on-select").html());

  // Change the icon of the selected FSEs back to normal
  for (var code in markers) {
    if (markers[code].selected) {
      markers[code].setIcon(markers[code].formericon);
    }
  }
});

// Toggle Selection of FSE
function toggleSelect() {
  var code = $(this).data("entry-id");
  if (!markers[code].selected) {
    // Adds an invisible input with the FSE code filled in to gather data for submission
    $("#fse-code-input").append('<input type="hidden" name="fse_code[]" value="' + code + '"/>');
    // Add FSE's name to the list
    $("#selected-fse-on-select").append('<span class="fse" id="' + code + '">' + markers[code].title + "</span>");
    // Set icon to checkmark
    markers[code].setIcon("/srmsng/src/image/track-icons/fse-available-check.png");
    // Set selected status of the marker to true
    markers[code].selected = true;
  } else {
    // Remove the input
    $('#fse-code-input input[value="' + code + '"]').remove();
    // Remove FSE from list
    $("#selected-fse-on-select #" + code).remove();
    // Set icon back to former icon
    markers[code].setIcon(markers[code].formericon);
    // Set selected status of the marker to false
    markers[code].selected = false;
  }
  infowindow.close();
}

// Map Initialization
function initMap() {
  // Init map with options
  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 6,
    center: new google.maps.LatLng(13.08798433, 100.5838692),
    mapTypeId: "roadmap",
    gestureHandling: "greedy"
  });

  // Set Marker Icons based on the type
  var iconBase = "/srmsng/src/image/track-icons/";
  var icons = {
    available: {
      icon: iconBase + "fse-available.png"
    },
    busy: {
      icon: iconBase + "fse-busy.png"
    },
    low_work: {
      icon: iconBase + "fse-l-work.png"
    },
    high_work: {
      icon: iconBase + "fse-h-work.png"
    },
    not_available: {
      icon: iconBase + "fse-not-available.png"
    },
    site: {
      icon: iconBase + "site.png"
    }
  };

  // Fetch location of all FSEs
  fetch("/srmsng/public/index.php/api/admin/getfse")
    .then(resp => {
      return resp.json();
    })
    .then(data_json => {
      // Info Window
      infowindow = new google.maps.InfoWindow({
        content: ""
      });
      data_json.forEach(element => {
        // Status Text
        var getstatus = element["status"];

        // Match status gotten from database with the corresponding icons
        var status = "";
        switch (getstatus) {
          case "Available":
            status = "available";
            break;
          case "Sick":
            status = "busy";
            break;
          case "On Personal Leave":
            status = "busy";
            break;
          case "Medium Workload":
            status = "low_work";
            break;
          case "High Workload":
            status = "high_work";
            break;
          case "Not Available":
            status = "not_available";
            break;
          default:
            status = "available";
            break;
        }

        // Add Marker to markers
        var marker = new google.maps.Marker({
          icon: icons[status].icon,
          map: map,
          title: element["engname"],
          code: element["fse_code"],
          for: "fse",
          type: status,
          statustext: getstatus,
          formericon: icons[status].icon,
          selected: false
        });
        marker.addListener("click", toggleDetails);
        markers[marker.code] = marker;

        // Toggle Details
        // This function is binded to each marker on click. It toggles infowindow with the
        // appropriate content
        function toggleDetails() {
          // Open the info window
          infowindow.open(map, marker);

          var contentString = "<h5>" + marker.title + "</h5>";

          contentString += "<p>Status: " + marker.statustext + "</p>";

          // The content of the infowindow is different with FSE selection is taking place.
          if (onSelect) {
            if (marker.type != "not_available" && marker.type != "busy") {
              if (!marker.selected) {
                // Select Button
                contentString +=
                  '<button class="btn btn-primary" id="select-button" onClick="toggleSelect.call(this)" data-entry-id="' +
                  marker.code +
                  '"><i class="fa fa-check"></i> Select</button>';
              } else {
                // Deselect Button
                contentString +=
                  '<button class="btn btn-danger" id="select-button" onClick="toggleSelect.call(this)" data-entry-id="' +
                  marker.code +
                  '"><i class="fa fa-times"></i> Deselect</button>';
              }
            } else {
              // Disable select button if the FSE is busy or unavailable
              contentString += '<button class="btn btn-primary" disabled><i class="fa fa-check"></i> Select</button>';
            }
          }

          // Set the content of the info window
          infowindow.setContent(contentString);
        }

        // Connect to firebase
        var db = firebase.database();
        var ref = db.ref("locations");
        // Update Location
        ref.child(marker.code).on("value", snapshot => {
          var result = snapshot.val(); // Return data
          if (result) {
            marker.setPosition(new google.maps.LatLng(result.latitude, result.longitude));
          }
        });
      });
    });

  // Fetch the latitudes and longitudes of the location of each ticket
  fetch("/srmsng/public/index.php/api/admin/getlatlon")
    .then(resp => {
      return resp.json();
    })
    .then(data_json => {
      // Info Window
      infowindow = new google.maps.InfoWindow({
        content: ""
      });

      // Add marker to map for each location
      data_json.forEach(element => {
        var marker = new google.maps.Marker({
          icon: icons["site"].icon,
          map: map,
          title: element["sitename"],
          code: element["location_code"],
          for: "site",
          type: "site",
          problem_type: element["problem_type"],
          asset_problem: element["asset_problem"],
          formericon: icons["site"].icon,
          selected: false
        });
        marker.addListener("click", toggleDetails);
        markers[marker.code] = marker;
        marker.setPosition(new google.maps.LatLng(element["latitude"], element["longitude"]));

        // Toggle Details
        // This function is binded to each marker on click. It toggles infowindow with the
        // appropriate content
        function toggleDetails() {
          infowindow.open(map, marker);
          var contentString = "<h5>" + marker.title + "</h5>";

          // Disable the Assign FSE button if FSE selection is taking place
          if (onSelect) {
            contentString +=
              '<button class="btn btn-primary" data-toggle="modal" data-target="#assign-modal-site" data-entry-id="' +
              marker.code +
              '" onClick="setUpModal.call(this)" disabled>Assign FSE</button>';
          } else {
            contentString +=
              '<button class="btn btn-primary" data-toggle="modal" data-target="#assign-modal-site" data-entry-id="' +
              marker.code +
              '" onClick="setUpModal.call(this)">Assign FSE</button>';
          }
          infowindow.setContent(contentString);
        }
      });
    });
}
