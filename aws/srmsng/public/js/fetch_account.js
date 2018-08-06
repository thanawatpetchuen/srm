// Fetch account for admins
// at /account

function fetchTable() {
  var online = 0;
  $("#supertable").DataTable({
    ajax: $.fn.dataTable.pipeline({
      url: "/srmsng/public/fetch-ajax/fetchAccount.php",
      processing: true,
      serverSide: true,
      pages: 5 // number of pages to cache,
    }),
    dom: '<lf<"table-wrapper"t>ip>',
    stateSave: true,
    columnDefs: [
      {
        orderable: false,
        targets: [0, 5]
      }
    ],
    columns: [
      { data: 0 },
      { data: 1 },
      { data: 2 },
      { data: 3 },
      {
        // Shows if the account is locked or unlock
        data: 4,
        render: function(data) {
          if (data == "0") {
            return "<span class='td-lock'>No</span>";
          }
          return "<span class='td-lock is-locked'>Locked</span>";
        }
      },
      { data: 5 },
      {
        // Lock/Unlock button depending on lock status of the account
        data: function(row) {
          return [row[2], row[4]];
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
      // Set up table styling
      $("#supertable").addClass("display");
      setUpFixed();
    },
    drawCallback: function() {
      // Set up table styling
      setUpFixed();
    }
  });
  // console.log("ONLINE: " + online);
}

function toggleLock(locked, user) {
  // Show different alerts when a user is locked or unlocked
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
    // Request lock/unlock account
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

function getOnlineUser() {
  // Get amount of online users
  var len = document.getElementById("supertable_length");
  $.ajax({
    type: "GET",
    url: "/srmsng/public/api.php/getOnlineUser",
    success: data => {
      var countDiv = document.createElement("div");
      countDiv.setAttribute("class", "online");
      var count = document.createTextNode("Online: " + data + " users");
      countDiv.appendChild(count);
      len.appendChild(countDiv);
    }
  });
}

$(document).ready(function() {
  fetchTable();
  getOnlineUser();
});
