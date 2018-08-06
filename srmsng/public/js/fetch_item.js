// Fetch Item from Material Master Record
// at /account/item

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
      url: "/srmsng/public/fetch-ajax/fetchItem.php",
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
          button.setAttribute("data-target", "#edit-item-popup");
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

// Add/Edit form Submission
$("#add-item-form").on("submit", () => {
  // Add Item
  $.ajax({
    type: "POST",
    url: "/srmsng/public/index.php/api/admin/additem",
    data: $("#add-item-form").serialize(),
    success: data => {
      //console.log(data);
      $("#add-item-popup").modal("hide");
      document.getElementById("add-item-form").reset();
      toastr.options = {
        positionClass: "toast-top-center"
      };
      toastr.success("<span>Please wait, this website is going to refresh...</span>");
      setTimeout(() => {
        window.location = "/srmsng/public/account/item?add_success=true";
      }, toastDuration);
      
    },
    error: err => {
      console.log(err);
    }
  });
});

$("#edit-item-form").on("submit", () => {
  // Edit Item
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/admin/updateitem",
    data: $("#edit-item-form").serialize(),
    success: data => {
      console.log(data);
      $("#edit-item-popup").modal("hide");
      document.getElementById("edit-item-form").reset();
      toastr.options = {
        positionClass: "toast-top-center"
      };
      toastr.success("<span>Please wait, this website is going to refresh...</span>");
      setTimeout(() => {
        window.location = "/srmsng/public/account/item?update_success=true";
      }, toastDuration);
      
    },
    error: err => {
      console.log(err);
    }
  });
});

// Fill fields when the edit button is clicked
function fillField(data) {
  document.getElementById("item_no").value = data[0];
  document.getElementById("model").value = data[1];
  document.getElementById("power").value = data[2];
  document.getElementById("item_class").value = data[3];
  document.getElementById("category").value = data[4];

  // Check the correct radio input
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
