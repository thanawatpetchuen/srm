var data = null;
function fetchTable() {
  $("#supertable").DataTable({
    //columnDefs: [{ orderable: false, targets: [6, 7, 9] }],
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
      //   {
      //     targets: 2,
      //     data: function(row) {
      //       return row.slice(1, 3);
      //     },
      //     render: function(data) {
      //       return "<a href='/srmsng/public/account/customer/view?id=" + data[0] + "'>" + data[1] + "</a>";
      //     }
      //   },
      {
        searchable: true
      },
      {
        targets: -1,
        searchable: false,
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
          // console.log(data);
          // data = data;

          // return "<button class='btn btn-primary' data-target='#edit-customer-popup' data-toggle='modal' onClick='fillField()'>Edit</button>";
        },
        className: "fixed-col"
      }
    ],
    initComplete: function() {
      setUpFixed();
      $("#supertable").addClass("display");
    },
    drawCallback: function() {
      setUpFixed();
    }
  });
}

$("#add-fse-form").on("submit", () => {
  $.ajax({
    type: "POST",
    url: "/srmsng/public/index.php/api/admin/addfse",
    data: $("#add-fse-form").serialize(),
    success: data => {
      console.log(data);
      $("#add-fse-popup").modal("hide");
      document.getElementById("add-fse-form").reset();
      window.location = "/srmsng/public/account/fse?add_success=true";
    },
    error: err => {
      console.log(err);
    }
  });
});

$("#edit-fse-form").on("submit", () => {
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/admin/updatefse",
    data: $("#edit-fse-form").serialize(),
    success: data => {
      console.log(data);
      console.log($("#edit-fse-form").serialize());
      $("#edit-fse-popup").modal("hide");
      document.getElementById("edit-fse-form").reset();
      window.location = "/srmsng/public/account/fse?update_success=true";
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
