// Fetch FSE table
// At /account/fse

var data = null;
var toastDuration = 1500;
function fetchTable() {
  $("#supertable").DataTable({
    stateSave: true,
    deferRender: true,
    processing: true,
    serverSide: true,
    dom: '<lf<"table-wrapper"t>ip>',
    ajax: $.fn.dataTable.pipeline({
      url: "/srmsng/public/fetch-ajax/fetchFSE.php",
      pages: 5 // number of pages to cache,
    }),
    columnDefs: [
      {
        // Edit button
        targets: -1,
        data: function(row) {
          return row;
        },
        render: function(data) {
          var button = document.createElement("button");
          button.setAttribute("class", "btn btn-primary");
          button.setAttribute("data-target", "#edit-fse-popup");
          button.setAttribute("data-toggle", "modal");
          button.setAttribute("onClick", "fillField(" + JSON.stringify(data) + ")");
          button.appendChild(document.createTextNode("Edit"));
          return button.outerHTML;
        },
        className: "fixed-col"
      }
    ],
    initComplete: function() {
      // Set up table styling
      setUpFixed();
      $("#supertable").addClass("display");
    },
    drawCallback: function() {
      // Set up table styling
      setUpFixed();
    }
  });
}

// Form submissions
$("#add-fse-form").on("submit", () => {
  // Add FSE
  $.ajax({
    type: "POST",
    url: "/srmsng/public/index.php/api/admin/addfse",
    data: $("#add-fse-form").serialize(),
    success: data => {
      $("#add-fse-popup").modal("hide");
      document.getElementById("add-fse-form").reset();
      toastr.options = {
        positionClass: "toast-top-center"
      };
      toastr.success("<span>Please wait, this website is going to refresh...</span>");
      setTimeout(() => {
        window.location = "/srmsng/public/account/fse?add_success=true";
      }, toastDuration);
      
    },
    error: err => {
      console.log(err);
    }
  });
});

$("#edit-fse-form").on("submit", () => {
  // Edit FSE
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/admin/updatefse",
    data: $("#edit-fse-form").serialize(),
    success: data => {
      console.log(data);
      console.log($("#edit-fse-form").serialize());
      $("#edit-fse-popup").modal("hide");
      document.getElementById("edit-fse-form").reset();
      toastr.options = {
        positionClass: "toast-top-center"
      };
      toastr.success("<span>Please wait, this website is going to refresh...</span>");
      setTimeout(() => {
        window.location = "/srmsng/public/account/fse?update_success=true";
      }, toastDuration);
      
    },
    error: err => {
      console.log(err);
    }
  });
});

function fillField(data) {
  console.log(data);
  document.getElementById("fse_code").value = data[0];
  document.getElementById("thainame").value = data[1];
  document.getElementById("engname").value = data[2];
  document.getElementById("abbr").value = data[3];
  document.getElementById("company").value = data[4];
  document.getElementById("position").value = data[5];
  document.getElementById("service_center").value = data[6];
  document.getElementById("section").value = data[7];
  document.getElementById("team").value = data[8];
  document.getElementById("status").value = data[9];
  document.getElementById("email").value = data[10];
  document.getElementById("phone").value = data[11];
}

$(document).ready(function() {
  fetchTable();
});
