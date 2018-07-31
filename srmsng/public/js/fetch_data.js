// Fetch Asset for admin
// at /asset

function fetchData() {
  $("#supertable").DataTable({
    stateSave: true,
    dom: '<lf<"table-wrapper"t>ip>',
    processing: true,
    serverSide: true,
    ajax: $.fn.dataTable.pipeline({
      url: "/srmsng/public/fetch-ajax/fetchAsset.php",
      pages: 5 // number of pages to cache,
    }),
    columnDefs: [
      {
        // Edit button
        targets: -1,
        data: 0,
        className: "fixed-col",
        searchable: false,
        render: function(data) {
          return '<a class="btn btn-primary" href="edit_asset?sng_code=' + data + '">Edit</a>';
        }
      },
      {
        // Link to view scheduled work of the asset
        targets: 0,
        data: 0,
        render: function(data) {
          return '<a href="work?sng_code=' + data + '" target="_blank">' + data + "</a>";
        }
      },
      {
        // Sale order dropdown
        targets: 1,
        className: "td-with-details",
        data: function(row) {
          return row.slice(1, 7);
        },
        render: function(data) {
          return (
            data[0] +
            makeDropArrow().outerHTML +
            makeCollapse(["Year", "Order Date", "PO Number", "PO Date", "D/O Number"], data.slice(1)).outerHTML
          );
        }
      },
      {
        // Customer dropdown
        targets: 2,
        className: "td-with-details",
        data: function(row) {
          return row.slice(7, 11);
        },
        render: function(data) {
          return (
            data[0] +
            makeDropArrow().outerHTML +
            makeCollapse(["Sale Team", "Product Sale", "Service Sale"], data.slice(1)).outerHTML
          );
        }
      },
      {
        // Item dropdown
        targets: 3,
        className: "td-with-details",
        data: function(row) {
          return row.slice(11, 13);
        },
        render: function(data) {
          return data[0] + makeDropArrow().outerHTML + makeCollapse(["Serial No"], data.slice(1)).outerHTML;
        }
      },
      {
        // Rate
        targets: 4,
        data: 13
      },
      {
        // Installed by
        targets: 5,
        data: 14
      },
      {
        // Battery dropdown
        targets: 6,
        className: "td-with-details",
        data: function(row) {
          return row.slice(15, 18);
        },
        render: function(data) {
          return (
            data[0] + makeDropArrow().outerHTML + makeCollapse(["Quantity", "Battery Date"], data.slice(1)).outerHTML
          );
        }
      },
      {
        // Location dropdown
        targets: 7,
        className: "td-with-details",
        data: function(row) {
          return row.slice(18, 29);
        },
        render: function(data) {
          // Generate Address
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
        // Contact dropdown
        targets: 8,
        className: "td-with-details",
        data: function(row) {
          return row.slice(29, 31);
        },
        render: function(data) {
          return data[1] + makeDropArrow().outerHTML + makeCollapse(["Contact Name"], [data[0]]).outerHTML;
        }
      },
      {
        // Type of Warranty
        targets: 9,
        data: 31
      },
      {
        // Start/End warranty
        targets: 10,
        className: "td-with-details",
        data: function(row) {
          return row.slice(32, 34);
        },
        render: function(data) {
          return data[0] + makeDropArrow().outerHTML + makeCollapse(["End Warranty"], [data[1]]).outerHTML;
        }
      },
      {
        // SLA
        targets: 11,
        className: "td-with-details",
        data: function(row) {
          return row.slice(37, 40);
        },
        render: function(data) {
          return (
            data[0] +
            makeDropArrow().outerHTML +
            makeCollapse(["Respond on Site", "Recovery Time"], [data[1] + " hr", data[2] + " hr"]).outerHTML
          );
        }
      },
      {
        // UPS Status
        targets: 12,
        data: 34
      },
      {
        // PM per year
        targets: 13,
        data: 35
      },
      {
        // Next PM
        targets: 14,
        data: 36
      }
    ],
    initComplete: function() {
      // Set up table styling
      $("#supertable").addClass("display");
      setUpFixed();
    },
    drawCallback: function() {
      // Set up table styling
      setUpFixed();
      //bindCollapse();
    }
  });
}

$(document).ready(function() {
  fetchData();
});

// Show the dropdown
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

// Create the dropdown from the data in the row
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

// Create the button in order to show the dropdown when clicked
function makeDropArrow() {
  var wrapper = document.createElement("div");
  wrapper.setAttribute("class", "drop-arrow");

  var icon = document.createElement("i");
  icon.setAttribute("class", "fa fa-chevron-circle-down");

  wrapper.appendChild(icon);
  wrapper.setAttribute("onClick", "showCollapse.call(this)");

  return wrapper;
}
