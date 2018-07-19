var data = null;

function fetchTable(sng_code) {
  $("#supertable-plan").DataTable({
    //columnDefs: [{ orderable: false, targets: [6, 7, 9] }],
    stateSave: true,
    deferRender: true,
    processing: true,
    serverSide: true,
    dom: '<lf<"table-wrapper"t>ip>',
    ajax: $.fn.dataTable.pipeline({
      url: "/srmsng/public/fetch-ajax/fetchPlan.php?sng_code=" + sng_code,
      pages: 5 // number of pages to cache,
    }),
    columnDefs: [
      {
        targets: -1,
        data: function(row) {
          return row[0];
        },
        render: function(data) {
          var type = data.substring(0, 3);
          if (type === "JOB") {
            return (
              '<a class="btn btn-primary" href="../service/edit_service?cm_id=' + data + '&from_plan=true">Edit</a>'
            );
          }
          return '<a class="btn btn-primary" href="../ticket/ticket_update?cm_id=' + data + '&from_plan=true">Edit</a>';
        },
        className: "fixed-col"
      }
    ],
    initComplete: function() {
      setUpFixed("#supertable-plan");
      $("#supertable-plan").addClass("display");
    },
    drawCallback: function() {
      setUpFixed("#supertable-plan");
    }
  });

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
