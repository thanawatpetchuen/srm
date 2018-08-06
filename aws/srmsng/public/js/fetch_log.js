//
// DataTables initialisation
//

// Fetch System Log
// at /system/log
$(document).ready(function() {
  $("#supertable").DataTable({
    processing: true,
    serverSide: true,
    stateSave: true,
    dom: '<lf<"table-wrapper"t>ip>',
    ajax: $.fn.dataTable.pipeline({
      url: "/srmsng/public/fetch-ajax/fetchLog.php",
      pages: 5 // number of pages to cache,
    }),
    initComplete: function() {
      // Set up table styling
      $("#supertable").addClass("display");
    }
  });
});
