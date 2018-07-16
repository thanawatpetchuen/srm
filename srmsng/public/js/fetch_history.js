var data = null;

function fetchTable(sng_code) {
  $("#supertable-history").DataTable({
    //columnDefs: [{ orderable: false, targets: [6, 7, 9] }],
    stateSave: true,
    deferRender: true,
    processing: true,
    serverSide: true,
    dom: '<lf<"table-wrapper"t>ip>',
    ajax: $.fn.dataTable.pipeline({
      url: "/srmsng/public/fetch-ajax/fetchPlanHistory.php?sng_code=" + sng_code,
      pages: 5 // number of pages to cache,
    }),
    initComplete: function() {
      $("#supertable-history").addClass("display");
    }
  });
}

$(document).ready(function() {
  var myElement = document.getElementById("main-container");
  var sng = myElement.dataset.sngCode;
  fetchTable(sng);
});
