function fetchTable() {
  $("#supertable").DataTable({
    //columnDefs: [{ orderable: false, targets: [6, 7, 9] }],
    stateSave: true,
    deferRender: true,
    dom: '<lf<"table-wrapper"t>ip>',
    processing: true,
    serverSide: true,
    ajax: $.fn.dataTable.pipeline({
      url: "/srmsng/public/fetch-ajax/fetchTicket.php",
      pages: 5 // number of pages to cache,
    }),
    
    columnDefs: [
      {
        searchable: true
      },
      {
        targets: 1,
        data: 1,
        width: "100%",
        render: function(data) {
          var dataQ = "'" + data + "'";
          return (
            '<a href="/srmsng/public/asset/view_asset?sng_code=' +
            data +
            '" onClick="getAssetDetails(' +
            dataQ +
            ')" target="_blank">' +
            data +
            '<i class="fa fa-search" style="padding-left:5px;"></i></a>'
          );
        }
      },
      {
        targets: 10,
        className: "td-with-details",
        data: function(row) {
          return [row[10], row[11]];
        },
        render: function(data) {
          return data[0] + makeDropArrow().outerHTML + makeCollapse(["Details"], [data[1]]).outerHTML;
        }
      },
      {
        targets: 11,
        data: 12
      },
      {
        targets: 12,
        className: "td-with-details",
        data: function(row) {
          return [row[13], row[14]];
        },
        render: function(data) {
          return data[0] + makeDropArrow().outerHTML + makeCollapse(["Details"], [data[1]]).outerHTML;
        }
      },
      {
        targets: 13,
        className: "td-with-details",
        data: function(row) {
          return [row[15], row[16]];
        },
        render: function(data) {
          return data[0] + makeDropArrow().outerHTML + makeCollapse(["Details"], [data[1]]).outerHTML;
        }
      },
      {
        targets: 14,
        data: 17
      },
      {
        targets: 15,
        data: 18
      },
      {
        targets: 16,
        data: 19
      },
      {
        targets: 17,
        data: 20
      },
      {
        targets: 18,
        data: 21
      },
      {
        targets: 19,
        data: 22
      },
      {
        targets: 20,
        data: 23
      },
      {
        targets: 21,
        data: 24
      },
      {
        targets: 22,
        data: 25
      },
      {
        targets: -1,
        searchable: false,
        data: 0,
        className: "fixed-col",
        render: function(data) {
          return "<a class='btn btn-primary' href='ticket_update?cm_id=" + data + "'>Update</a>";
        }
      }
    ],
    initComplete: function() {
      setUpFixed();
      $("#supertable").addClass("display");
    },
    drawCallback: function() {
      setUpFixed();
    }
  });
  $("#supertable-approve").DataTable({
    //columnDefs: [{ orderable: false, targets: [6, 7, 9] }],
    stateSave: true,
    deferRender: true,
    dom: '<lf<"table-wrapper"t>ip>',
    processing: true,
    serverSide: true,
    ajax: $.fn.dataTable.pipeline({
      url: "/srmsng/public/fetch-ajax/fetchTicketApprove.php",
      pages: 5 // number of pages to cache,
    }),
    columnDefs: [
      {
        searchable: true
      },
      {
        targets: 1,
        data: 1,
        render: function(data) {
          var dataQ = "'" + data + "'";
          return (
            '<a href="/srmsng/public/asset/view_asset?sng_code=' +
            data +
            '" onClick="getAssetDetails(' +
            dataQ +
            ')" target="_blank">' +
            data +
            '<i class="fa fa-search" style="padding-left:5px;"></i></a>'
          );
        }
      },
      {
        targets: 10,
        className: "td-with-details",
        data: function(row) {
          return [row[10], row[11]];
        },
        render: function(data) {
          return data[0] + makeDropArrow().outerHTML + makeCollapse(["Details"], [data[1]]).outerHTML;
        }
      },
      {
        targets: 11,
        data: 12
      },
      {
        targets: 12,
        className: "td-with-details",
        data: function(row) {
          return [row[13], row[14]];
        },
        render: function(data) {
          return data[0] + makeDropArrow().outerHTML + makeCollapse(["Details"], [data[1]]).outerHTML;
        }
      },
      {
        targets: 13,
        className: "td-with-details",
        data: function(row) {
          return [row[15], row[16]];
        },
        render: function(data) {
          return data[0] + makeDropArrow().outerHTML + makeCollapse(["Details"], [data[1]]).outerHTML;
        }
      },
      {
        targets: 14,
        data: 17
      },
      {
        targets: 15,
        data: 18
      },
      {
        targets: 16,
        data: 19
      },
      {
        targets: 17,
        data: 20
      },
      {
        targets: 18,
        data: 21
      },
      {
        targets: 19,
        data: 22
      },
      {
        targets: 20,
        data: 23
      },
      {
        targets: 21,
        data: 24
      },
      {
        targets: 22,
        data: 25
      },
      {
        targets: -1,
        searchable: false,
        data: function(row) {
          return [row[0], row[21]];
        },
        className: "fixed-col",
        render: function(data) {
          render_data = data[1] == "Completed" ? `<a class='btn btn-primary text-center disabled' href='#'">Completed</a>`: `<a class='btn btn-primary text-center' href='#' onClick="approve('${data[0]}')">Approve</a>`;
          return render_data;
        }
      }
    ],
    initComplete: function() {
      setUpFixed();
      $("#supertable-approve").addClass("display");
    },
    drawCallback: function() {
      setUpFixed();
    }
  });
}

$(document).ready(function() {
  
  fetchTable();
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

function approve(cm_id){
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/admin/approvecm",
    data: "cm_id=" + cm_id,
    success: data => {
      console.log(data);
      setTimeout(() => {
        window.location.href = "/srmsng/public/ticket/?approve=true";
      }, 2000);
      // window.location.reload();
    },
    error: data => {
      console.log(data);
    }
  });
}
