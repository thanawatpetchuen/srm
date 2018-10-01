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

function cmChange(code){
  $("#fse-leader").empty();
  var this_cm = $("#cm_id").val()
  for (var code in markers) {
    markers[code].selected = false;
    markers[code].setIcon(markers[code].formericon);
  }
  fetch('/srmsng/public/index.php/api/admin/getcminfo?cm_id='+this_cm).then((data) => {return data.json()}).then((data) => {
    $("#fse-code-input").html("");
    $(".selected-fse .fse").remove();
    console.log(data)

    //SET VALUE on fetch
    $("input[name='sitename']").val(data[0].sitename);
    $('input[name="cm_id"]').val(this_cm);
    $('input[name="cm_time"]').val(data[0].cm_time);
    $('input[name="sng_code"]').val(data[0].sng_code);

    // IF data have cm_time set checkbox on
    if(data[0].cm_time){
      $('input[name="assign-cm-time"]').click();
    }else{
      $('input[name="assign-cm-time"]').prop('checked', false);
      $('input[name="cm-time"]').attr("disabled", true);
      $('#cm-time-field').addClass("disabled");
    }

    // parsing groupFSE to JSON
    var t = data[0].groupFSE;
    console.log(`code is: ${t}`);
    var ap = JSON.parse(t);

    // Append FSE to modal
    for(var each in ap){

      for (var code in markers) {
    
        if (markers[code].title == ap[each]) {
          markers[code].selected = true;
          // markers[code].setIcon("/srmsng/src/image/track-icons/fse-available-check.png");
        }
        
      }

      if(ap[each] != "Undefined"){
        $("#fse-code-input").append('<input type="hidden" name="fse_code[]" value="' + each + '"/>');
        $("#selected-fse").append('<span class="fse" id="' + each + '">' + ap[each] + "</span>");
        $("#selected-fse-on-select").append('<span class="fse" id="' + each + '">' + ap[each] + "</span>");
        $('select[name="leader"]').append(`<option value="${each}">${ap[each]}</option>`);
        $('#fse-leader-dropdown').css("display", "block");
        $('select[name="leader"]').attr("disabled", false);
      }else{
        $('#fse-leader-dropdown').css("display", "none");
        $('select[name="leader"]').attr("disabled", true);
      }


      // markers[code].setIcon("/srmsng/src/image/track-icons/fse-available-check.png");
      // // Set selected status of the marker to true
      // markers[code].selected = true;
    }

    
    // for (var code in markers) {
    //   if (markers[code].selected) {
    //     console.log(`Marker: ${code}, selected: ${markers[code].selected}`);
    //     markers[code].setIcon(markers[code].formericon);
    //   }
    // }


  });

}

function setUpModal() {
  var code = $(this).data("entry-id");
  
  console.log(`Code = ${code}`);
  console.log("CM_TIME: "+markers[code].cm_id+markers[code].groupFSE);

  // //SET VALUE on fetch
  // $("input[name='sitename']").val(markers[code].title);
  // $('input[name="problem_type"]').val(markers[code].problem_type);
  // $('textarea[name="asset_problem"]').val(markers[code].asset_problem);
  // $('input[name="cm_id"]').val(markers[code].cm_id);
  // $('input[name="cm_time"]').val(markers[code].cm_time);

  var list = markers[code].groupCM;
  console.log(`List: ${list}`);
  list = list.split(",");
  // list = ["Asd", "ASd"];
  list.forEach((each) => {
    console.log(each);
    $("#cm_id").append($("<option></option>")
    .attr("value",each)
    .text(each)); ;
  })

  var job_type = markers[code].job_type;
  $('select[name="job_type"]').val(job_type).change();
  if(job_type == "On site"){
    $("#selected-fse").css("display", "");
    $("#fse-dropdown").css("display", "none");
    $('input[type="hidden"]').attr("disabled", false);
  }

  // // parsing groupFSE to JSON
  // var t = markers[code].groupFSE;
  // console.log(`code is: ${code}`);
  // var ap = JSON.parse(t);
  // console.log(markers[code].groupFSE);
  // console.log(JSON.parse(markers[code].groupFSE));
  // console

  $("#fse-code-input").html("");
  $(".selected-fse .fse").remove();

  
  // // Append FSE to modal
  // for(var each in ap){

  //   for (var code in markers) {
      
  //     if (markers[code].title == ap[each]) {
  //       markers[code].selected = true;
  //       // markers[code].setIcon("/srmsng/src/image/track-icons/fse-available-check.png");
  //     }
      
  //   }

  //   $("#fse-code-input").append('<input type="hidden" name="fse_code[]" value="' + each + '"/>');
  //   $("#selected-fse").append('<span class="fse" id="' + each + '">' + ap[each] + "</span>");
  //   $("#selected-fse-on-select").append('<span class="fse" id="' + each + '">' + ap[each] + "</span>");


  //   // markers[code].setIcon("/srmsng/src/image/track-icons/fse-available-check.png");
  //   // // Set selected status of the marker to true
  //   // markers[code].selected = true;
  // }

  
  for(var code in markers){
    if(markers[code].selected){
      console.log(`code: ${code}, selected: ${markers[code].selected}`);
    }
  }

  // // Append Value
  // ap.forEach((each) => {
  //   console.log(each);
  //   // $("#fse-code-input").append('<input type="hidden" name="fse_code[]" value="' + each + '"/>');
  //   // $("#selected-fse").append('<span class="fse" id="' + each + '">' + t.each + "</span>");
  // })

  

  // fetch(`/srmsng/public/index.php/api/admin/request/single?cm_id=${markers[code].cm_id}`).then((data) => {return data.json()}).then((data) => {console.log(data)});
  // markers[code].groupFSE.split(",").forEach((each) => {
  //   $("#fse-code-input").append('<input type="hidden" name="fse_code[]" value="' + markers[code].fse_code + '"/>');
  //   $("#selected-fse").append('<span class="fse" id="' + markers[code].fse_code + '">' + each + "</span>");
  // })

  // //  Add FSE's name to the list
  // $("#selected-fse-on-select").append('<span class="fse" id="' + code + '">' + markers[code].title + "</span>");
  // // Remove the previous location's selected fse
  
  
}

function testCall(id){
  // console.log(id);
  $("#fse-leader-input").empty();
  $(`span.fse[id="${id}"]`).css('background-color', "gold");
  $(`span.fse`).not(`#${id}`).css('background-color', "rgba(0, 123, 255, 0.25)");
  $(`#fse-leader-input`).append(`<input type="hidden" name="leader" value="${id}">`);
  $(`span.fse`).each(function(){
    // console.log($(this).attr('id'));
    // console.log("CO");
    if($(this).attr('id') != id){
      // console.log(`id= ${id},,${$(this).attr('id')}`)
      // $(`span[id="${id}"]`).css('background-color', "gold");
      // $(`span[id="${id}"]`).css('background-color', "rgba(0, 123, 255, 0.25)");
    }
    // }else{
      // console.log("Ese")
    // }
  })
}

function initModal(){
  console.log(`THIS: ${this}`);
  var code = $(this).data("entry-id");
  $("#cm_id").html("");
  var list = markers[code].groupCM;
  console.log(`List: ${this_cm}`);
  list = list.split(",");
  list.forEach((each) => {
    $("#cm_id").append($("<option></option>")
    .attr("value",each)
    .text(each)); ;
  })
  var this_cm = $("#cm_id").val()
  fetch('/srmsng/public/index.php/api/admin/getcminfo?cm_id='+this_cm).then((data) => {return data.json()}).then((data) => {
    $("#fse-code-input").html("");
    $(".selected-fse .fse").remove();
    console.log(data)
    console.log(`Sitename: ${data[0].sitename}`)

    //SET VALUE on fetch
    $("input[name='sitename']").val(data[0].sitename);
    $('input[name="cm_id"]').val(this_cm);
    $('input[name="cm_time"]').val(data[0].cm_time);
    $('input[name="sng_code"]').val(data[0].sng_code);

    if(data[0].cm_time){
      $('input[name="assign-cm-time"]').click();
    }else{
      $('input[name="assign-cm-time"]').prop('checked', false);
      $('input[name="cm-time"]').attr("disabled", true);
      $('#cm-time-field').addClass("disabled");
    }


    // parsing groupFSE to JSON
    var t = data[0].groupFSE;
    console.log(`code is: ${t}`);
    var ap = JSON.parse(t);
    console.log(ap);
    $('select[name="leader"]').empty();
    for(var each in ap){
      console.log(`each: ${ap[each]}`);
      for (var code in markers) {
        
        if (markers[code].title == ap[each]) {
          markers[code].selected = true;
          // markers[code].setIcon("/srmsng/src/image/track-icons/fse-available-check.png");
        }
        
      }

      if(ap[each] != "Undefined"){
        $("#fse-code-input").append('<input type="hidden" name="fse_code[]" value="' + each + '"/>');
        // $("#selected-fse").append(`<span class="fse" id="${each}" onClick="testCall('${each}')" data-entry-id="${each}">${ap[each]}</span>`);
        $("#selected-fse").append('<span class="fse" id="' + each + '">' + ap[each] + "</span>");
        $("#selected-fse-on-select").append('<span class="fse" id="' + each + '">' + ap[each] + "</span>");
        // $("#selected-fse-on-select").append(`<span class="fse" id="${each}" onClick="testCall('${each}')" data-entry-id="${each}">${ap[each]}</span>`);
        $('select[name="leader"]').append(`<option value="${each}">${ap[each]}</option>`);
        $('#fse-leader-dropdown').css("display", "block");
        $('select[name="leader"]').attr("disabled", false);
      }else{
        $('#fse-leader-dropdown').css("display", "none");
        $('select[name="leader"]').attr("disabled", true);
      }
      

      // markers[code].setIcon("/srmsng/src/image/track-icons/fse-available-check.png");
      // // Set selected status of the marker to true
      // markers[code].selected = true;
    }

    var code = $(this).data("entry-id");
    // console.log(`Code = ${code}`);

 

  $("#cm_id").on("change", () => {
    cmChange(code);
  })
  
  });
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
  console.log($("#selected-fse-on-select").html());
  // Hides the current list of selected FSEs from the bottom of the page
  $(".tracking-select").css("display", "none");

  $("#selected-fse").html($("#selected-fse-on-select").html());

  // $(`span.fse[id="${id}"]`).css('background-color', "gold");
  // $(`span.fse`).not(`#${id}`).css('background-color', "rgba(0, 123, 255, 0.25)");
  // $(`#fse-leader-input`).append(`<input type="hidden" name="leader" value="${id}">`);
  // Change the icon of the selected FSEs back to normal
  for (var code in markers) {
    if (markers[code].selected) {
      console.log(`Marker: ${code}, selected: ${markers[code].selected}`);
      markers[code].setIcon(markers[code].formericon);
    }
  }

  $('#fse-leader-dropdown').css("display", "block");
  $('select[name="leader"]').attr("disabled", false);

});

// Toggle Selection of FSE
function toggleSelect() {
  var code = $(this).data("entry-id");
  if (!markers[code].selected) {
    console.log(`Got you! who: ${markers[code].title}, selected: ${markers[code].selected}`);
    // Adds an invisible input with the FSE code filled in to gather data for submission
    $("#fse-code-input").append('<input type="hidden" name="fse_code[]" value="' + code + '"/>');
    $(`#fse-leader-input`).append(`<input type="hidden" name="leader" value="${code}">`);
    // Add FSE's name to the list
    $("#selected-fse-on-select").append('<span class="fse" id="' + code + '">' + markers[code].title + "</span>");
    $('select[name="leader"]').append(`<option value="${code}">${markers[code].title}</option>`);
    // $("#selected-fse-on-select").append(`<span class="fse" id="${code}" onClick="testCall('${code}')" data-entry-id="${code}">${markers[code].title}</span>`);
    // Set icon to checkmark
    markers[code].setIcon("/srmsng/src/image/track-icons/fse-available-check.png");
    // Set selected status of the marker to true
    markers[code].selected = true;
  } else {
    // Remove the input
    console.log(`Got youuuuu! who: ${markers[code]}, selected: ${markers[code].selected}`);
    $('#fse-code-input input[value="' + code + '"]').remove();
    // Remove FSE from list
    $("#selected-fse-on-select #" + code).remove();
    $(`#fse-leader option[value="${code}"]`).remove();
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
          selected: false,
          
        });
        marker.addListener("click", toggleDetails);
        markers[marker.code] = marker;

        // var jp = JSON.parse(marker.groupFSE);
        // if(
          // markers[marker.code].setIcon("/srmsng/src/image/track-icons/fse-available-check.png");
        // }

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
          selected: false,
          cm_time: element["cm_time"],
          groupFSE: element["groupFSE"],
          groupCM: element["groupCM"],
          fse_code: element["fse_code"],
          job_type: element["job_type"],
          sng_code: element["sng_code"],
        });
        marker.addListener("click", toggleDetails);
        // var jp = JSON.parse(marker.groupFSE);
        // for(var each in jp){
        //   console.log(`each is ${each}`);
        //   markers[each].selected = true;
        // }
        markers[marker.code] = marker;

        marker.setPosition(new google.maps.LatLng(element["latitude"], element["longitude"]));
        
        
        // if(
          // markers[marker.code].setIcon("/srmsng/src/image/track-icons/fse-available-check.png");
        // }

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
              '" onClick="initModal.call(this)" disabled>Assign FSE</button>';
          } else {
            contentString +=
              '<button class="btn btn-primary" data-toggle="modal" data-target="#assign-modal-site" data-entry-id="' +
              marker.code +
              '" onClick="initModal.call(this)">Assign FSE</button>';
          }
          infowindow.setContent(contentString);
        }
      });
    });
}
