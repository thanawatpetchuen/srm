function fetchTable() {
  $("#supertable").DataTable({
    stateSave: true,
    deferRender: true,
    processing: true,
    serverSide: true,
    dom: '<lf<"table-wrapper"t>ip>',
    ajax: $.fn.dataTable.pipeline({
      data: "id=" + myVar,
      url: "/srmsng/public/fetch-ajax/fetchLocation.php",
      pages: 5 // number of pages to cache,
    }),
    columnDefs: [
      {
        // Edit button
        targets: -1,
        data: 1,
        render: function(data) {
          return "<a class='btn btn-primary' href='view?id=" + data + "'>Edit</a>";
        },
        className: "fixed-col"
      }
    ],
    initComplete: function() {
      // Set up table styling
      setUpFixed();
      $("#supertable").addClass("display");
    },
    drawCallback: function() {
      // Set up table styling
      setUpFixed();
    }
  });
}

$(document).ready(function() {
  fetchTable();
});
