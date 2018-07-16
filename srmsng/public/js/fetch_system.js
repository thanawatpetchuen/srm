function fetchTable() {
  $("#supertable").DataTable({
    processing: true,
    serverSide: true,
    ajax: $.fn.dataTable.pipeline({
      url: "/srmsng/public/fetch-ajax/fetchSystem.php",
      pages: 5 // number of pages to cache,
    }),
    columnDefs: [
      {
        searchable: true
      },
      {
        targets: -1,
        searchable: false,
        className: "fixed-col",
        data: function(row) {
          return [row[1], row[2], row[4]];
        },
        render: function(data) {
          if (data[2] === "READ") {
            return '<button class="btn btn-primary disabled">Reset</button>';
          }
          var params = "'" + data[0] + "','" + data[1] + "'";
          return (
            '<button class="btn btn-primary reset-btn" data-target="#reset-popup" data-toggle="modal" onClick="resetPassSetUp(' +
            params +
            ')">Reset</button>'
          );
        },
        defaultContent: '<button class="btn btn-primary disabled">Reset</button>',
        orderable: false, targets: [6] 
      }
    ],
    stateSave: true,
    dom: '<lf<"table-wrapper"t>ip>',
    initComplete: function() {
      $("#supertable").addClass("display");
      setUpFixed();
    },
    drawCallback: setUpFixed()
  });
}

function resetPassSetUp(username, email) {
  $("#email-field").attr("value", email);
  $("#customer-no-field").attr("value", username);
}

$(document).ready(function() {
  fetchTable();
});
