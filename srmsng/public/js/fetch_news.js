function fetchTable() {
    $("#supertable-news").DataTable({
      ajax: $.fn.dataTable.pipeline({
        url: "/srmsng/public/fetch-ajax/fetchNews.php",
        pages: 5, // number of pages to cache,
        processing: true,
        serverSide: true,
      }),
      dom: '<lf<"table-wrapper"t>ip>',
      order: [[3, "desc"]],
      
      stateSave: true,
      columnDefs: [
        {
          searchable: true
        },
      ],
      columns: [
        {
          data: 0,
          className: "td-check",
          render: function(data) {
            return (
              '<div class="form-check form-check-inline"><input type="checkbox" value="' +
              data +
              '" class="checkbox" /></div>'
            );
          }
        },
        { data: 0 },
        // { data: 1 },
        {
          data: 1},
          { data: 2 },
          { data: 3 },
          {
            data: 4,
          },
          {
            data: 5,
          },
        {
          data: 0,
          className: "fixed-col",
          render: function(data) {
            return `<button class="btn btn-outline-danger" onClick="deleteNews('${data}')">Delete</button>`;
          }
        }
      ],
      initComplete: function() {
        $("#supertable-news").addClass("display");
        setUpFixed();
      },
      drawCallback: function() {
        setUpFixed();
      }
    });
    $("#supertable-notices").DataTable({
      ajax: $.fn.dataTable.pipeline({
        url: "/srmsng/public/fetch-ajax/fetchNotices.php",
        pages: 5, // number of pages to cache,
        processing: true,
        serverSide: true,
      }),
      dom: '<lf<"table-wrapper"t>ip>',
      order: [[3, "desc"]],
      
      stateSave: true,
      columnDefs: [
        {
          searchable: true
        },
      ],
      columns: [
        {
          data: 0,
          className: "td-check",
          render: function(data) {
            return (
              '<div class="form-check form-check-inline"><input type="checkbox" value="' +
              data +
              '" class="checkbox" /></div>'
            );
          }
        },
        { data: 0 },
        // { data: 1 },
        {
          data: 1},
          { data: 2 },
          { data: 3 },
        {
          data: 0,
          className: "fixed-col",
          render: function(data) {
            return `<button class="btn btn-outline-danger" onClick="deleteNotice('${data}')">Delete</button>`;
          }
        }
      ],
      initComplete: function() {
        $("#supertable-notices").addClass("display");
        setUpFixed();
      },
      drawCallback: function() {
        setUpFixed();
      }
    });
  }
  
  function deleteNews(id) {
    
      $.ajax({
        type: "DELETE",
        url: "/srmsng/public/announcement/news/api/deleteNews",
        data: "id=" + id,
        success: data => {
          window.location.reload();
        }
      });
    
  }

  function deleteNotice(id) {
    
    $.ajax({
      type: "DELETE",
      url: "/srmsng/public/announcement/news/api/deleteNotice",
      data: "id=" + id,
      success: data => {
        window.location.reload();
      }
    });
  
}
  
  $("#deleteSelectedNewsBtn").on("click", e => {
    e.preventDefault();
    var inputs = document.getElementsByTagName("input");
    // var deleteConfirm = confirm("Are you sure you want to DeAuth the user(s)?");
    // if (deleteConfirm) {
      for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].type == "checkbox" && inputs[i].checked == true) {
          // inputs[i].checked = true;
          $.ajax({
            type: "DELETE",
            data: "id=" + inputs[i].value,
            url: "/srmsng/public/announcement/news/api/deleteNews",
            success: data => {
              
            },
            error: err => {
              alert(err);
            }
          });
        }
      }
      window.location.reload();
    // }
  });

  $("#deleteSelectedNoticesBtn").on("click", e => {
    e.preventDefault();
    var inputs = document.getElementsByTagName("input");
    // var deleteConfirm = confirm("Are you sure you want to DeAuth the user(s)?");
    // if (deleteConfirm) {
      for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].type == "checkbox" && inputs[i].checked == true) {
          // inputs[i].checked = true;
          $.ajax({
            type: "DELETE",
            data: "id=" + inputs[i].value,
            url: "/srmsng/public/announcement/news/api/deleteNotice",
            success: data => {
              
            },
            error: err => {
              alert(err);
            }
          });
        }
      }
      window.location.reload();
    // }
  });
  
  
  $(document).ready(function() {
    fetchTable();
  });
  
  