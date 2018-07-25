function fetchTablePM(fse_code) {
  $("#supertable-pm").DataTable({
    //columnDefs: [{ orderable: false, targets: [6, 7, 9] }],
    stateSave: true,
    deferRender: true,
    dom: '<lf<"table-wrapper"t>ip>',
    processing: true,
    serverSide: true,
    ajax: $.fn.dataTable.pipeline({
      url: "/srmsng/public/fetch-ajax/fetchPMFSE.php?fse_code=" + fse_code,
      pages: 5 // number of pages to cache,
    }),
    columnDefs: [
      {
        targets: 1,
        data: function(row) {
          return [row[0]];
        },
        render: function(data) {
          return (
            '<a href="/srmsng/public/service/details?service_request_id=' +
            data[0] +
            '" target="_blank">' +
            data[0] +
            '<i class="fa fa-search" style="padding-left:5px;"></i></a>'
          );
        }
      },
      {
        targets: 2,
        data: 1,
        render: function(data) {
          return '<span class="span-message">' + data + "</span>";
        }
      },
      {
        targets: 3,
        data: 2
      },
      {
        targets: 4,
        className: "td-with-details",
        data: function(row) {
          return [row[3], row[4], row[5]];
        },
        render: function(data) {
          return (
            data[0] +
            makeDropArrow().outerHTML +
            makeCollapse(["Contact Number", "Alternate Number"], [data[1], data[2]]).outerHTML
          );
        }
      },
      {
        targets: 5,
        data: 6
      },
      {
        targets: 6,
        data: 7
      },
      {
        targets: 7,
        className: "td-with-details",
        data: function(row) {
          return [
            row[8],
            row[9],
            row[10],
            row[11],
            row[12],
            row[13],
            row[14],
            row[15],
            row[16],
            row[17],
            row[18],
            row[19]
          ];
        },
        render: function(data) {
          var village = "";
          var road = "";
          var subdistrict = "";
          var district = "";
          var province = "";
          if (data[3] != "") {
            village = "หมู่ " + data[3] + " ";
          }
          if (data[4] != "") {
            road = "ถนน" + data[4] + ", ";
          }
          if (data[9] == "กรุงเทพมหานคร" || data[7] == "กรุงเทพมหานคร") {
            if (data[5] != "") {
              subdistrict = "แขวง" + data[5] + ", ";
            }
            if (data[6] != "") {
              district = "เขต" + data[6] + ", ";
            }
            province = data[7] + " ";
          } else {
            if (data[5] != "") {
              subdistrict = "ตำบล" + data[5] + ", ";
            }
            if (data[6] != "") {
              district = "อำเภอ" + data[6] + ", ";
            }
            province = "จังหวัด" + data[7] + " ";
          }
          var address = data[2] + " " + village + road + subdistrict + district + province + data[8];
          return (
            data[0] +
            makeDropArrow().outerHTML +
            makeCollapse(["Location Code", "Address", "Region", "Country"], [data[1], address, data[9], data[10]])
              .outerHTML
          );
        }
      },
      {
        targets: 8,
        data: 20
      },
      {
        targets: 0,
        data: function(row) {
          return [row[0], row[2], row[6]];
        },
        render: function(data) {
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
              `<a href='#' class='btn btn-block btn-primary' style="width:auto; margin-left:2px" data-toggle="modal" data-target="#note-modal" onClick="setModal('` +
              data[0] +
              `')"> ${status2} </a>`;
            return `<div class="form-group" style="margin-bottom:0">${btt}${btt2}</div>`;
          } else if (data[1] == "Pending Approve") {
            let btt = `<a href='#' class='btn btn-block btn-primary disabled'">Done</a>`;
            return btt;
          } else if (data[1] == "Incomplete") {
            let btt = `<a href='#' class='btn btn-block btn-primary disabled'">Incomplete</a>`;
            return btt;
          } else if (data[1] == "Completed") {
            let btt = `<a href='#' class='btn btn-block btn-primary disabled'">Done</a>`;
            return btt;
          }
          // return data[2];
          // return "<a href='#' class='btn btn-primary'"+ "onClick=action"+data[2]+">"+status+"</a>";
          var btt = `<a href='#' class='btn btn-block btn-primary' onClick="action${type}('${data[0]}', '${
            data[2]
          }')"> ${status} </a>`;
          return btt;
          // return "<a href='#' class='btn btn-primary'"+ "onClick=action"+type+"("+"'"+data[2]+"'"+")"+">"+status+"</a>";
        }
      }
    ],
    initComplete: function() {
      $("#supertable-pm").addClass("display");
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
      console.log(data2[0].fse_code);
      fetchTablePM(data2[0].fse_code);
    });
});

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
  console.log("Ack");
  console.log(cm_id);

  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/fse/acknowledge",
    data: "cm_id=" + cm_id + "&engname=" + name,
    success: data => {
      console.log(data);
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
  console.log("Travel");
  console.log(cm_id + name);
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/fse/starttravel",
    data: "cm_id=" + cm_id + "&engname=" + name,
    success: data => {
      console.log(data);
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
  console.log("Arrived");
  console.log(cm_id);
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/fse/arrivedsite",
    data: "cm_id=" + cm_id + "&engname=" + name,
    success: data => {
      console.log(data);
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
  console.log("Start");
  console.log(cm_id);
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/fse/startwork",
    data: "cm_id=" + cm_id + "&engname=" + name,
    success: data => {
      console.log(data);
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
  console.log("Complete");
  console.log(cm_id);
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/fse/finishwork",
    data: "cm_id=" + cm_id + "&engname=" + name,
    success: data => {
      console.log(data);
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
