// Fetch asset for customers (has to be logged in as a customer)

function fetchData() {
  $("#supertable").DataTable({
    stateSave: true,
    dom: '<lf<"table-wrapper"t>ip>',
    processing: true,
    serverSide: true,
    ajax: $.fn.dataTable.pipeline({
      url: "/srmsng/public/fetch-ajax/fetchAssetUser.php",
      pages: 5 // number of pages to cache,
    }),
    columns: [
      {
        // Link to view the work for the asset
        data: 0,
        render: function(data) {
          return `<a href="/srmsng/public/customer/work?sng_code=${data}" target="_blank">${data}</a>`;
        }
      },
      { data: 1 },
      { data: 2 },
      { data: 3 },
      { data: 4 },
      { data: 5 },
      { data: 6 },
      { data: 7 },
      { data: 8 },
      { data: 9 },
      { data: 10 },
      { data: 11 },
      {
        // Request button
        data: function(row) {
          return [row[0], row[11]];
        },
        render: function(data) {
          var sngcode = "'" + data[0] + "'";
          if (data[1] != "Trade") {
            return (
              '<button class="btn btn-primary" onClick="setUpModal(' +
              sngcode +
              ')" data-toggle="modal" data-target="#popup">Request</button>'
            );
          }
          return '<button class="btn btn-primary disabled">Request</button>';
        },
        className: "fixed-col"
      }
    ],
    initComplete: function() {
      // Set up table styling
      $("#supertable").addClass("display");
      setUpFixed();
    },
    drawCallback: setUpFixed()
  });
}

// Set up the modal when the user clicks the request button
function setUpModal(code) {
  // Fills in the sng code field (readonly)
  document.getElementById("sng-code-modal").setAttribute("value", code);
}

$(document).ready(function() {
  $("#supertable").addClass("display");
  fetchData();
});
