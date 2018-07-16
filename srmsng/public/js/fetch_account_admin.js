function fetchTable() {
    var online = 0;
    $("#supertable").DataTable({
      ajax: $.fn.dataTable.pipeline({
        url: "/srmsng/public/fetch-ajax/fetchAccountAdmin.php",
        pages: 5 // number of pages to cache,
      }),
      dom: '<lf<"table-wrapper"t>ip>',
      processing: true,
      serverSide: true,
      stateSave: true,
      columnDefs: [
        {
          searchable: true
        },
        {
          orderable: false,
          targets: [0, 6]
        }
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
        { data: 1 },
        // { data: 1 },
        {
          data: 2},
          { data: 3 },
          { data: 4 },
          {
            data: 5,
            render: function(data) {
              if (data == "0") {
                return "<span class='td-lock'>No</span>";
              }
              return "<span class='td-lock is-locked'>Locked</span>";
            }
          },
          { data: 6 },
        {
          data: function(row) {
            return [row[1], row[3]];
          },
          className: "fixed-col",
          render: function(data) {
            var user = "'" + data[0] + "'";
  
            if (data[1] === "0") {
              return (
                '<button class="btn btn-outline-danger lock-btn" onClick="toggleLock(0,' +
                user +
                ')"><i class="fa fa-lock"></i><span> Lock</span></button>'
              );
            }
            return (
              '<button class="btn btn-primary unlock-btn" onClick="toggleLock(1,' +
              user +
              ')"><i class="fa fa-unlock-alt"></i><span> Unlock</span></button>'
            );
          }
        }
      ],
      initComplete: function() {
        $("#supertable").addClass("display");
        setUpFixed();
      },
      drawCallback: function() {
        setUpFixed();
      }
    });
    console.log("ONLINE: "+online);
  }
  
  function toggleLock(locked, user) {
    var lockConfirm = false;
    var url = "";
  
    if (locked) {
      lockConfirm = true;
      url = "/srmsng/public/account?unlock_success=true";
    } else {
      lockConfirm = confirm("Are you sure you want to lock this account?");
      url = "/srmsng/public/account?lock_success=true";
    }
  
    if (lockConfirm) {
      $.ajax({
        type: "PUT",
        url: "/srmsng/public/index.php/api/admin/account",
        data: "username=" + user,
        success: data => {
          window.location = url;
        }
      });
    }
  }
  
  
  function getOnlineUser(){
    var len = document.getElementById("supertable_length");
    $.ajax({
      type: "GET",
      url: "/srmsng/public/api.php/getOnlineUser",
      success: data => {
        var countDiv = document.createElement("div");
        countDiv.setAttribute("class", "online");
        var count = document.createTextNode("Online: "+data+" users");
        countDiv.appendChild(count);
        len.appendChild(countDiv);
      }
    })
  }
  
  $(document).ready(function() {
    fetchTable();
    getOnlineUser();
  });
  
  