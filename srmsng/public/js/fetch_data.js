function fetchData() {
  $("#supertable").DataTable({
    // columnDefs: [{ orderable: false, targets: [20] }],
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
        targets: -1,
        data: 0,
        className: "fixed-col",
        searchable: false,
        render: function(data) {
          return '<a class="btn btn-primary" href="edit_asset?sng_code=' + data + '">Edit</a>';
        }
      },
      {
        targets: 0,
        data: 0,
        render: function(data) {
          return '<a href="work?sng_code=' + data + '">' + data + "</a>";
        }
      },
      {
        targets: 1,
        searchable: true,
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
        targets: 2,
        searchable: true,
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
        targets: 3,
        searchable: true,
        className: "td-with-details",
        data: function(row) {
          return row.slice(11, 13);
        },
        render: function(data) {
          return data[0] + makeDropArrow().outerHTML + makeCollapse(["Serial No"], data.slice(1)).outerHTML;
        }
      },
      {
        targets: 4,
        searchable: true,
        data: 13
      },
      {
        targets: 5,
        searchable: true,
        data: 14
      },
      {
        targets: 6,
        searchable: true,
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
        targets: 7,
        searchable: true,
        className: "td-with-details",
        data: function(row) {
          return row.slice(18, 29);
        },
        render: function(data) {
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
        targets: 8,
        searchable: true,
        className: "td-with-details",
        data: function(row) {
          return row.slice(29, 31);
        },
        render: function(data) {
          return data[1] + makeDropArrow().outerHTML + makeCollapse(["Contact Name"], [data[0]]).outerHTML;
        }
      },
      {
        targets: 9,
        searchable: true,
        data: 31
      },
      {
        targets: 10,
        searchable: true,
        className: "td-with-details",
        data: function(row) {
          return row.slice(32, 34);
        },
        render: function(data) {
          return data[0] + makeDropArrow().outerHTML + makeCollapse(["End Warranty"], [data[1]]).outerHTML;
        }
      },
      {
        targets: 11,
        searchable: true,
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
        targets: 12,
        searchable: true,
        data: 34
      },
      {
        targets: 13,
        searchable: true,
        data: 35
      },
      {
        targets: 14,
        searchable: true,
        data: 36
      }
    ],
    initComplete: function() {
      $("#supertable").addClass("display");
      setUpFixed();
    },
    drawCallback: function() {
      setUpFixed();
      //bindCollapse();
    }
  });
}

$(document).ready(function() {
  fetchData();
});

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

function makeDropArrow() {
  var wrapper = document.createElement("div");
  wrapper.setAttribute("class", "drop-arrow");

  var icon = document.createElement("i");
  icon.setAttribute("class", "fa fa-chevron-circle-down");

  wrapper.appendChild(icon);
  wrapper.setAttribute("onClick", "showCollapse.call(this)");

  return wrapper;
}
