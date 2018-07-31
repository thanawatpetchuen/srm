// Fetch work plan for an asset for customers

var data = null;

function fetchTable(sng_code) {
  // Plan
  $("#supertable-plan").DataTable({
    stateSave: true,
    deferRender: true,
    processing: true,
    serverSide: true,
    dom: '<lf<"table-wrapper"t>ip>',
    ajax: $.fn.dataTable.pipeline({
      url: "/srmsng/public/fetch-ajax/fetchPlan.php?sng_code=" + sng_code,
      pages: 5 // number of pages to cache,
    }),
    initComplete: function() {
      $("#supertable-plan").addClass("display");
    }
  });

  // History
  $("#supertable-history").DataTable({
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
