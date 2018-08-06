// Fetch Service
// at /service

function fetchTable() {
  $("#supertable").DataTable({
    stateSave: true,
    deferRender: true,
    processing: true,
    serverSide: true,
    dom: '<lf<"table-wrapper"t>ip>',
    ajax: $.fn.dataTable.pipeline({
      url: "/srmsng/public/fetch-ajax/fetchService.php",
      pages: 5 // number of pages to cache,
    }),
    columnDefs: [
      {
        // Link to view the details of the service request
        targets: 0,
        data: function(row) {
          return [row[0]];
        },
        render: function(data) {
          return (
            '<a href="/srmsng/public/service/details?service_request_id=' +
            data[0] +
            '" target="_blank">' +
            data[0] +
            '<i class="fa fa-search" style="padding-left:5px;"></i></a>'
          );
        }
      },
      {
        // Title
        targets: 1,
        data: 1
      },
      {
        // Status
        targets: 2,
        data: 2
      },
      {
        // Contact (with dropdown)
        targets: 3,
        className: "td-with-details",
        data: function(row) {
          return [row[3], row[4], row[5]];
        },
        render: function(data) {
          return (
            data[0] +
            makeDropArrow().outerHTML +
            makeCollapse(["Contact Number", "Alternate Number"], [data[1], data[2]]).outerHTML
          );
        }
      },
      {
        // FSE
        targets: 4,
        data: 7
      },
      {
        // Work Class
        targets: 5,
        data: 8
      },
      {
        // Location (with dropdown)
        targets: 6,
        className: "td-with-details",
        data: function(row) {
          return [
            row[9],
            row[10],
            row[11],
            row[12],
            row[13],
            row[14],
            row[15],
            row[16],
            row[17],
            row[18],
            row[19],
            row[20]
          ];
        },
        render: function(data) {
          // Generate address
          var village = "";
          var road = "";
          var subdistrict = "";
          var district = "";
          var province = "";
          if (data[3] != "") {
            village = "หมู่ " + data[3] + " ";
          }
          if (data[4] != "") {
            road = "ถนน" + data[4] + ", ";
          }
          if (data[9] == "กรุงเทพมหานคร" || data[7] == "กรุงเทพมหานคร") {
            if (data[5] != "") {
              subdistrict = "แขวง" + data[5] + ", ";
            }
            if (data[6] != "") {
              district = "เขต" + data[6] + ", ";
            }
            province = data[7] + " ";
          } else {
            if (data[5] != "") {
              subdistrict = "ตำบล" + data[5] + ", ";
            }
            if (data[6] != "") {
              district = "อำเภอ" + data[6] + ", ";
            }
            province = "จังหวัด" + data[7] + " ";
          }
          var address = data[2] + " " + village + road + subdistrict + district + province + data[8];
          return (
            data[0] +
            makeDropArrow().outerHTML +
            makeCollapse(["Location Code", "Address", "Region", "Country"], [data[1], address, data[9], data[10]])
              .outerHTML
          );
        }
      },
      {
        // Due date
        targets: 7,
        data: 21
      },
      {
        // Update button
        targets: -1,
        data: function(row) {
          return [
            row[0],
            row[22]
          ];
        },
        className: "fixed-col",
        render: function(data) {
            if (data[1] === "1") {
                return "<a class='btn btn-primary' href='update?service_request_id=" + data[0] + "' target='_blank'>Update</a>";
            } else {
                return "<a class='btn btn-primary' href='update_without_sngcode?service_request_id=" + data[0] + "' target='_blank'>Update</a>";
            }
        }
      }
    ],
    initComplete: function() {
      // set up table styling
      setUpFixed();
      $("#supertable").addClass("display");
    },
    drawCallback: function() {
      // set up table styling
      setUpFixed();
    }
  });
}

$(document).ready(function() {
  fetchTable();
});

// Show dropdown
function showCollapse() {
  var collapse = this.parentElement.getElementsByClassName("asset-collapse")[0];
  if (collapse.classList.contains("show")) {
    collapse.classList.remove("show");
    this.classList.remove("show");
  } else {
    collapse.classList.add("show");
    this.classList.add("show");
  }
}

// Create dropdown from data in the row
function makeCollapse(keys, values) {
  var collapse = document.createElement("div");
  collapse.setAttribute("class", "asset-collapse border rounded");

  for (var i = 0; i < keys.length; i++) {
    var item = document.createElement("p");
    item.setAttribute("class", "asset-collapse-item");

    var header = document.createElement("span");
    header.setAttribute("class", "asset-collapse-item-header");
    header.appendChild(document.createTextNode(keys[i] + ": "));

    item.appendChild(header);
    item.appendChild(document.createTextNode(values[i]));

    collapse.appendChild(item);
  }
  return collapse;
}

// Create button to show the dropdown
function makeDropArrow() {
  var wrapper = document.createElement("div");
  wrapper.setAttribute("class", "drop-arrow");

  var icon = document.createElement("i");
  icon.setAttribute("class", "fa fa-chevron-circle-down");

  wrapper.appendChild(icon);
  wrapper.setAttribute("onClick", "showCollapse.call(this)");

  return wrapper;
}
