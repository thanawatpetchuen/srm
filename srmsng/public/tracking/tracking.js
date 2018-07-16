// Assign Modal
function setUpModal() {
  var markerName = $(this).data("entry-id");
  $("input[name='sitename']").val(markers[markerName].title);
  $('input[name="problem_type"]').val(markers[markerName].problem_type);
  $('textarea[name="asset_problem"]').val(markers[markerName].asset_problem);

  $(".selected-fse .fse").remove();
}
$("#assign-cancel").on("click", function() {
  $("#assign-modal-site input").val("");
  for (var name in markers) {
    markers[name].selected = false;
  }
});

// Select from map
$("#select-from-map").on("click", function() {
  onSelect = true;
  $(".tracking-select").css("display", "block");
  infowindow.close();

  for (var name in markers) {
    if (markers[name].selected) {
      markers[name].setIcon("/srmsng/src/image/track-icons/fse-available-check.png");
    }
  }
});
$("#select-done").on("click", function() {
  onSelect = false;
  $(".tracking-select").css("display", "none");
  infowindow.close();

  for (var name in markers) {
    if (markers[name].selected) {
      markers[name].setIcon(markers[name].formericon);
    }
  }
});
function toggleSelect() {
  var markerName = $(this).data("entry-id");

  var fselabel = document.createElement("span");
  fselabel.setAttribute("class", markers[markerName].code + " fse");
  fselabel.appendChild(document.createTextNode(markers[markerName].title));

  if (markers[markerName].selected) {
    markers[markerName].selected = false;

    // Remove FSE from List
    [...document.getElementsByClassName(markers[markerName].code)].forEach(function(fsetag) {
      fsetag.outerHTML = "";
    });
    $('#fse-dropdown input[value="' + markers[markerName].code + '"]').prop("checked", false);

    markers[markerName].setIcon(markers[markerName].formericon);
  } else {
    markers[markerName].selected = true;

    // Add FSE to List
    [...document.getElementsByClassName("selected-fse")].forEach(function(panel) {
      panel.appendChild(fselabel.cloneNode(true));
    });
    $('#fse-dropdown input[value="' + markers[markerName].code + '"]').prop("checked", true);

    markers[markerName].setIcon("/srmsng/src/image/track-icons/fse-available-check.png");
  }

  infowindow.close();
}

// Map Initialization
var map;
var markers = {};
var onSelect = false;
var infowindow;

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 6,
    center: new google.maps.LatLng(13.08798433, 100.5838692),
    mapTypeId: "roadmap",
    gestureHandling: "greedy"
  });

  // Marker Icons
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
        // Configure Status
        var getstatus = element["status"];
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
        markers[marker.title] = marker;

        // Toggle Details
        function toggleDetails() {
          infowindow.open(map, marker);
          var contentString = "<h5>" + marker.title + "</h5>";

          contentString += "<p>Status: " + marker.statustext + "</p>";
          if (onSelect) {
            if (marker.type != "not_available" && marker.type != "busy") {
              if (!marker.selected) {
                contentString +=
                  '<button class="btn btn-primary" id="select-button" onClick="toggleSelect.call(this)" data-entry-id="' +
                  marker.title +
                  '"><i class="fa fa-check"></i> Select</button>';
              } else {
                contentString +=
                  '<button class="btn btn-danger" id="select-button" onClick="toggleSelect.call(this)" data-entry-id="' +
                  marker.title +
                  '"><i class="fa fa-times"></i> Deselect</button>';
              }
            } else {
              contentString += '<button class="btn btn-primary" disabled><i class="fa fa-check"></i> Select</button>';
            }
          }
          infowindow.setContent(contentString);
        }

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

  fetch("/srmsng/public/index.php/api/admin/getlatlon")
    .then(resp => {
      return resp.json();
    })
    .then(data_json => {
      // Info Window
      infowindow = new google.maps.InfoWindow({
        content: ""
      });
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
        markers[marker.title] = marker;
        marker.setPosition(new google.maps.LatLng(element["latitude"], element["longitude"]));

        // Toggle Details
        function toggleDetails() {
          infowindow.open(map, marker);
          var contentString = "<h5>" + marker.title + "</h5>";

          if (onSelect) {
            contentString +=
              '<button class="btn btn-primary" data-toggle="modal" data-target="#assign-modal-site" data-entry-id="' +
              marker.title +
              '" onClick="setUpModal.call(this)" disabled>Assign FSE</button>';
          } else {
            contentString +=
              '<button class="btn btn-primary" data-toggle="modal" data-target="#assign-modal-site" data-entry-id="' +
              marker.title +
              '" onClick="setUpModal.call(this)">Assign FSE</button>';
          }
          infowindow.setContent(contentString);
        }
      });
    });
}
