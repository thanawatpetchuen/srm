function fetchData() {
  $("#supertable").DataTable({
    stateSave: true,
    processing: true,
    serverSide: true,
    dom: '<lf<"table-wrapper"t>ip>',
    ajax: {
      url: "/srmsng/public/fetch-ajax/fetchAsset2.php",
      type: "POST"
    },
    columnDefs: [
      {
        targets: -1,
        data: 0,
        className: "fixed-col",
        render: function(data) {
          return '<a class="btn btn-primary" href="edit_asset?sng_code=' + data + '">Edit</a>';
        }
      },
      {
        targets: 1,
        className: "td-with-details",
        data: function(row) {
          return row.slice(1, 6);
        },
        render: function(data) {
          return (
            data[0] +
            makeDropArrow().outerHTML +
            makeCollapse(["Year", "Order Date", "PO Number", "PO Date"], data.slice(1)).outerHTML
          );
        }
      },
      {
        targets: 2,
        data: 10
      },
      {
        targets: 3,
        data: 6
      },
      {
        targets: 4,
        className: "td-with-details",
        data: function(row) {
          return row.slice(7, 9);
        },
        render: function(data) {
          return data[0] + makeDropArrow().outerHTML + makeCollapse(["Serial No"], data.slice(1)).outerHTML;
        }
      },
      {
        targets: 5,
        data: 9
      },
      {
        targets: 6,
        className: "td-with-details",
        data: function(row) {
          return row.slice(11, 14);
        },
        render: function(data) {
          return (
            data[0] + makeDropArrow().outerHTML + makeCollapse(["Quantity", "Battery Date"], data.slice(1)).outerHTML
          );
        }
      },
      {
        targets: 7,
        className: "td-with-details",
        data: function(row) {
          return row.slice(14, 24);
        },
        render: function(data) {
          var address =
            data[1] +
            " Moo " +
            data[2] +
            ", " +
            data[3] +
            " Rd., \n" +
            data[4] +
            ", " +
            data[5] +
            ", \n" +
            data[6] +
            " " +
            data[7];
          return (
            data[0] +
            makeDropArrow().outerHTML +
            makeCollapse(["Location Code", "Address", "Region", "Country"], [data[1], address, data[8], data[9]])
              .outerHTML
          );
        }
      },
      {
        targets: 8,
        className: "td-with-details",
        data: function(row) {
          return row.slice(24, 26);
        },
        render: function(data) {
          return data[1] + makeDropArrow().outerHTML + makeCollapse(["Contact Name"], [data[0]]).outerHTML;
        }
      },
      {
        targets: 9,
        className: "td-with-details",
        data: function(row) {
          return row.slice(26, 28);
        },
        render: function(data) {
          return data[0] + makeDropArrow().outerHTML + makeCollapse(["End Warranty"], [data[1]]).outerHTML;
        }
      },
      {
        targets: 10,
        data: 28
      },
      {
        targets: 11,
        data: 29
      },
      {
        targets: 12,
        data: 30
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

// function bindCollapse() {
//   $(".td-with-details").on("click", function() {
//     var arrow = $(this);
//     var collapse = arrow.children(".asset-collapse");
//     if (collapse.css("display") == "none") {
//       collapse.css("display", "block");
//       arrow.addClass("show");
//     } else {
//       collapse.css("display", "none");
//       arrow.removeClass("show");
//     }
//   });
// }

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
