// Fetch request history for the user

function fetchData() {
  $("#supertable").DataTable({
    stateSave: true,
    dom: '<lf<"table-wrapper"t>ip>',
    processing: true,
    serverSide: true,
    ajax: $.fn.dataTable.pipeline({
      url: "/srmsng/public/fetch-ajax/fetchTicketUser.php",
      pages: 5 // number of pages to cache,
    }),
    columns: [
      { data: 0 },
      { data: 1 },
      {
        // Allows the text to be on multple lines
        data: 2,
        render: function(data) {
          return '<span class="span-message">' + data + "</span>";
        }
      },
      { data: 3 },
      { data: 4 },
      { data: 5 },
      { data: 6 }
    ],
    initComplete: function() {
      // Set up table styling
      $("#supertable").addClass("display");
    }
  });
}

$(document).ready(function() {
  $("#supertable").addClass("display");
  fetchData();
});
