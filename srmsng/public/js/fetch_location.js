function fetchTable() {
  $("#supertable").DataTable({
    //columnDefs: [{ orderable: false, targets: [6, 7, 9] }],
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
        searchable: true
      },
      {
        targets: -1,
        searchable: false,
        data: 1,
        render: function(data) {
          return "<a class='btn btn-primary' href='view?id=" + data + "'>Edit</a>";
        },
        className: "fixed-col"
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
}

$(document).ready(function() {
  fetchTable();
});
