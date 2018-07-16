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
      url: "/srmsng/public/fetch-ajax/fetchItem.php",
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
        data: function(row) {
          return row;
        },
        render: function(data) {
          var button = document.createElement("button");
          button.setAttribute("class", "btn btn-primary");
          button.setAttribute("data-target", "#edit-item-popup");
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

// Add/Edit
$("#add-item-form").on("submit", () => {
  console.log("ADD");
  $.ajax({
    type: "POST",
    url: "/srmsng/public/index.php/api/admin/additem",
    data: $("#add-item-form").serialize(),
    success: data => {
      //console.log(data);
      $("#add-item-popup").modal("hide");
      document.getElementById("add-item-form").reset();
      window.location = "/srmsng/public/account/item?add_success=true";
    },
    error: err => {
      console.log(err);
    }
  });
});

$("#edit-item-form").on("submit", () => {
  console.log($("#edit-item-form").serialize());
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/admin/updateitem",
    data: $("#edit-item-form").serialize(),
    success: data => {
      console.log(data);
      //console.log($("#edit-item-form").serialize());
      $("#edit-item-popup").modal("hide");
      document.getElementById("edit-item-form").reset();
      window.location = "/srmsng/public/account/item?update_success=true";
    },
    error: err => {
      console.log(err);
    }
  });
});

function fillField(data) {
  document.getElementById("item_no").value = data[0];
  document.getElementById("model").value = data[1];
  document.getElementById("power").value = data[2];
  document.getElementById("item_class").value = data[3];
  document.getElementById("category").value = data[4];
  if (data[5] == "Y") {
    document.getElementById("is_lot_yes").checked = true;
  } else {
    document.getElementById("is_lot_no").checked = true;
  }
  if (data[6] == "Y") {
    document.getElementById("is_serial_yes").checked = true;
  } else {
    document.getElementById("is_serial_no").checked = true;
  }
  if (data[7] == "Y") {
    document.getElementById("is_warranty_yes").checked = true;
  } else {
    document.getElementById("is_warranty_no").checked = true;
  }
}

$(document).ready(function() {
  fetchTable();
});
