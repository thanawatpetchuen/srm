//
// DataTables initialisation
//
$(document).ready(function() {
  $("#supertable").DataTable({
    processing: true,
    serverSide: true,
    stateSave: true,
    // scrollX: true,
    dom: '<lf<"table-wrapper"t>ip>',
    ajax: $.fn.dataTable.pipeline({
      url: "/srmsng/public/fetch-ajax/fetchLog.php",
      pages: 5 // number of pages to cache,
    }),
    initComplete: function() {
      $("#supertable").addClass("display");
      setUpFixed();
    },
    drawCallback: function() {
      setUpFixed();
      //bindCollapse();
    }
  });
});
