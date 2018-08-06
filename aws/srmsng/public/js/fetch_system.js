// Fetch reset password
// at /system/passwordreset

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
        // Reset button
        targets: -1,
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
        orderable: false
      }
    ],
    stateSave: true,
    dom: '<lf<"table-wrapper"t>ip>',
    initComplete: function() {
      // Set up table styling
      $("#supertable").addClass("display");
      setUpFixed();
    },
    // Set up table styling
    drawCallback: setUpFixed()
  });
}

// Set up the modal for password reset
function resetPassSetUp(username, email) {
  $("#email-field").attr("value", email);
  $("#customer-no-field").attr("value", username);
}

$(document).ready(function() {
  fetchTable();
});
