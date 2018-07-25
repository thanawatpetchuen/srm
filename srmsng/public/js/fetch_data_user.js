function fetchData() {
  //  /srmsng/public/index.php/api/customer/asset?account_no=22

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
        data: 0,
        render: function(data) {
          return `<a href="/srmsng/public/customer/work?sng_code=${data}" target="_blank">${data}</a>`;
        }
        // render: function(data) {
        //   return '<a href="/srmsng/public/asset/work?sng_code=' + data + '" target="_blank">' + data + "</a>";
        // }
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
        data: function(row) {
          return [row[0], row[11]];
        }
      }
    ],
    columnDefs: [
      {
        targets: -1,
        className: "fixed-col",
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
        }
      }
    ],
    initComplete: function() {
      $("#supertable").addClass("display");
      setUpFixed();
    },
    drawCallback: setUpFixed()
    //   url: "/srmsng/public/index.php/api/customer/asset?account_no=22",
    //   pages: 5 // number of pages to cache,
    // })
  });
}

function setUpModal(code) {
  document.getElementById("sng-code-modal").setAttribute("value", code);
}

$(document).ready(function() {
  $("#supertable").addClass("display");
  fetchData();
});
