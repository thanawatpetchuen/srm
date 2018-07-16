function fetchData() {
  //  /srmsng/public/index.php/api/customer/asset?account_no=22

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
      $("#supertable").addClass("display");
    }
    //   url: "/srmsng/public/index.php/api/customer/asset?account_no=22",
    //   pages: 5 // number of pages to cache,
    // })
  });
}

$(document).ready(function() {
  $("#supertable").addClass("display");
  fetchData();
});
