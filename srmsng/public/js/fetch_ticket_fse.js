// Fetch ticket for FSE
// This table is in the first page in the CM tab when logged in as an FSE

function fetchTable(fse_code) {
  $("#supertable-cm").DataTable({
    stateSave: true,
    deferRender: true,
    dom: '<lf<"table-wrapper"t>ip>',
    processing: true,
    serverSide: true,
    ajax: $.fn.dataTable.pipeline({
      url: "/srmsng/public/fetch-ajax/fetchTicketFSE.php?fse_code=" + fse_code,
      pages: 5 // number of pages to cache,
    }),
    columnDefs: [
      {
        targets: 1,
        data: 0
      },
      {
        // Link to view details of an asset
        targets: 2,
        data: 1,
        render: function(data) {
          return (
            '<a href="view_asset?sng_code=' +
            data +
            '" target="_blank">' +
            data +
            '<i class="fa fa-search" style="padding-left:5px;"></i></a>'
          );
        }
      },
      {
        targets: 11,
        className: "td-with-details",
        data: function(row) {
          return [row[10], row[11]];
        },
        render: function(data) {
          return data[0] + makeDropArrow().outerHTML + makeCollapse(["Details"], [data[1]]).outerHTML;
        }
      },
      {
        targets: 12,
        data: 12
      },
      {
        targets: 13,
        className: "td-with-details",
        data: function(row) {
          return [row[13], row[14]];
        },
        render: function(data) {
          return data[0] + makeDropArrow().outerHTML + makeCollapse(["Details"], [data[1]]).outerHTML;
        }
      },
      {
        targets: 14,
        className: "td-with-details",
        data: function(row) {
          return [row[15], row[16]];
        },
        render: function(data) {
          return data[0] + makeDropArrow().outerHTML + makeCollapse(["Details"], [data[1]]).outerHTML;
        }
      },
      {
        targets: 15,
        data: 17
      },
      {
        targets: 16,
        data: 18
      },
      {
        targets: 17,
        data: 19
      },
      {
        targets: 18,
        data: 20
      },
      {
        targets: 19,
        data: 21
      },
      {
        targets: 20,
        data: 22
      },
      {
        targets: 21,
        data: 23
      },
      {
        targets: 22,
        data: 24
      },
      {
        targets: 23,
        data: 25
      },
      {
        targets: 24,
        data: 26
      },
      {
        targets: 0,
        data: function(row) {
          return [row[0], row[21], row[20]];
        },
        render: function(data) {
          // Job Status
          var status = "";
          var type = "";
          if (data[1] == "Assigned") {
            status = "Acknowledge";
            type = "Ack";
          } else if (data[1] == "Acknowledged") {
            status = "Travel";
            type = "Travel";
          } else if (data[1] == "Travelling") {
            status = "Arrived";
            type = "Arrived";
          } else if (data[1] == "Arrived") {
            status = "Start Work";
            type = "Start";
          } else if (data[1] == "Working in Progress") {
            status = "Complete";
            status2 = "Incomplete";
            type = "Complete";
            type2 = "Incomplete";
            let btt = `<a href='#' class='btn btn-block btn-primary' style="width:auto; margin-right:2px" onClick="action${type}('${
              data[0]
            }', '${data[2]}')"> ${status} </a>`;
            let btt2 =
              `<a href='#' class='btn btn-primary' style="width:auto; margin-left:2px" data-toggle="modal" data-target="#note-modal" onClick="setModal('` +
              data[0] +
              `')"> ${status2} </a>`;
            return `<div class="form-group" style="margin-bottom:0">${btt}${btt2}</div>`;
          } else if (data[1] == "Pending Approve") {
            let btt = `<a href='#' class='btn btn-primary btn-block disabled'">Done</a>`;
            return btt;
          } else if (data[1] == "Incomplete") {
            let btt = `<a href='#' class='btn btn-primary btn-block disabled'">Incomplete</a>`;
            return btt;
          } else if (data[1] == "Completed") {
            let btt = `<a href='#' class='btn btn-primary btn-block disabled'">Done</a>`;
            return btt;
          }
          var btt = `<a href='#' class='btn btn-primary btn-block' onClick="action${type}('${data[0]}', '${
            data[2]
          }')"> ${status} </a>`;
          return btt;
        }
      }
    ],
    initComplete: function() {
      $("#supertable-cm").addClass("display");
    }
  });
}

$(document).ready(function() {
  let username = sessionStorage.getItem("username_unhash");
  fetch("/srmsng/public/index.php/api/admin/getsinglefse?username=" + username)
    .then(data => {
      return data.json();
    })
    .then(data2 => {
      fetchTable(data2[0].fse_code);
    });
});

// Show dropdown
function showCollapse() {
  var collapse = this.parentElement.getElementsByClassName("asset-collapse")[0];
  if (collapse.classList.contains("show")) {
    collapse.classList.remove("show");
    this.classList.remove("show");
  } else {
    collapse.classList.add("show");
    this.classList.add("show");
  }
}

// Create dropdown from data in the row
function makeCollapse(keys, values) {
  var collapse = document.createElement("div");
  collapse.setAttribute("class", "asset-collapse border rounded");

  for (var i = 0; i < keys.length; i++) {
    var item = document.createElement("p");
    item.setAttribute("class", "asset-collapse-item");

    var header = document.createElement("span");
    header.setAttribute("class", "asset-collapse-item-header");
    header.appendChild(document.createTextNode(keys[i] + ": "));

    item.appendChild(header);
    item.appendChild(document.createTextNode(values[i]));

    collapse.appendChild(item);
  }
  return collapse;
}

// Create button that shows the dropdown
function makeDropArrow() {
  var wrapper = document.createElement("div");
  wrapper.setAttribute("class", "drop-arrow");

  var icon = document.createElement("i");
  icon.setAttribute("class", "fa fa-chevron-circle-down");

  wrapper.appendChild(icon);
  wrapper.setAttribute("onClick", "showCollapse.call(this)");

  return wrapper;
}

function actionAck(cm_id, name) {
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/fse/acknowledge",
    data: "cm_id=" + cm_id + "&engname=" + name,
    success: data => {
      toastr.options = {
        positionClass: "toast-bottom-center"
      };
      toastr.success("<span>Please wait, this website is going to refresh...</span>");
      setTimeout(() => {
        window.location.reload();
      }, 2000);
    },
    error: data => {
      console.log("ERR" + data);
    }
  });
}

function actionTravel(cm_id, name) {
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/fse/starttravel",
    data: "cm_id=" + cm_id + "&engname=" + name,
    success: data => {
      toastr.options = {
        positionClass: "toast-bottom-center"
      };
      toastr.success("<span>Please wait, this website is going to refresh...</span>");
      setTimeout(() => {
        window.location.reload();
      }, 2000);
    },
    error: data => {
      console.log(data);
    }
  });
}

function actionArrived(cm_id, name) {
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/fse/arrivedsite",
    data: "cm_id=" + cm_id + "&engname=" + name,
    success: data => {
      toastr.options = {
        positionClass: "toast-bottom-center"
      };
      toastr.success("<span>Please wait, this website is going to refresh...</span>");
      setTimeout(() => {
        window.location.reload();
      }, 2000);
    },
    error: data => {
      console.log(data);
    }
  });
}

function actionStart(cm_id, name) {
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/fse/startwork",
    data: "cm_id=" + cm_id + "&engname=" + name,
    success: data => {
      toastr.options = {
        positionClass: "toast-bottom-center"
      };
      toastr.success("<span>Please wait, this website is going to refresh...</span>");
      setTimeout(() => {
        window.location.reload();
      }, 2000);
    },
    error: data => {
      console.log(data);
    }
  });
}

function actionComplete(cm_id, name) {
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/fse/finishwork",
    data: "cm_id=" + cm_id + "&engname=" + name,
    success: data => {
      toastr.options = {
        positionClass: "toast-bottom-center"
      };
      toastr.success("<span>Please wait, this website is going to refresh...</span>");
      setTimeout(() => {
        window.location.reload();
      }, 2000);
      // window.location.reload();
    },
    error: data => {
      console.log(data);
    }
  });
}
