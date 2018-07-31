// Fetch Customer List
// at /account/customer

var data = null;
function fetchTable() {
  $("#supertable").DataTable({
    //columnDefs: [{ orderable: false, targets: [6, 7, 9] }],
    stateSave: true,
    deferRender: true,
    dom: '<lf<"table-wrapper"t>ip>',
    processing: true,
    serverSide: true,
    ajax: $.fn.dataTable.pipeline({
      url: "/srmsng/public/fetch-ajax/fetchCustomers.php",
      pages: 5 // number of pages to cache,
    }),
    columnDefs: [
      {
        // Create link to view the location and details of the customer
        targets: 2,
        data: function(row) {
          return [row[0], row[2]];
        },
        render: function(data) {
          return "<a href='/srmsng/public/account/customer/view?id=" + data[0] + "'>" + data[1] + "</a>";
        }
      },
      {
        // Edit button
        targets: -1,
        data: function(row) {
          return row;
        },
        render: function(data) {
          var button = document.createElement("button");
          button.setAttribute("class", "btn btn-primary");
          button.setAttribute("data-target", "#edit-customer-popup");
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

// Add customer submit
$("#add-customer-form").on("submit", () => {
  // Check for the length of Tax ID (13 digits only)
  if ($("#add-taxid").val().length != 13) {
    $("#add-taxid-warning").attr("class", "form-text text-danger");
    $("#add-taxid").addClass("is-invalid");
    return false;
  }
  // Add Customer
  $.ajax({
    type: "POST",
    url: "/srmsng/public/index.php/api/admin/addcustomer",
    data: $("#add-customer-form").serialize(),
    success: data => {
      $("#add-customer-popup").modal("hide");
      document.getElementById("add-customer-form").reset();
      window.location = "/srmsng/public/account/customer?add_success=true";
    },
    error: err => {
      console.log(err);
    }
  });
});

// Edit customer submit
$("#edit-customer-form").on("submit", () => {
  // Check for the length of Tax ID (13 digits only)
  if ($('input[name="taxid"]').val().length != 13) {
    $("#edit-taxid-warning").attr("class", "form-text text-danger");
    $("#edit-taxid").addClass("is-invalid");
    return false;
  }
  // Edit Customer
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/admin/updatecustomer",
    data: $("#edit-customer-form").serialize(),
    success: data => {
      $("#edit-customer-popup").modal("hide");
      document.getElementById("edit-customer-form").reset();
      window.location = "/srmsng/public/account/customer?update_success=true";
    },
    error: err => {
      console.log(err);
    }
  });
});

// Fill fields in the edit modal with the data in the row of the table
function fillField(data) {
  $('#edit-customer-form input[name="customer_no"]').val(data[0]);
  $('#edit-customer-form input[name="customer_name"]').val(data[1]);
  $('#edit-customer-form input[name="customer_eng_name"]').val(data[2]);
  $('#edit-customer-form input[name="taxid"]').val(data[7]);
  $('#edit-customer-form select[name="account_group"]').val(data[3]);
  $('#edit-customer-form input[name="sale_team"]').val(data[4]);
  $('#edit-customer-form input[name="product_sale"]').val(data[5]);
  $('#edit-customer-form input[name="service_sale"]').val(data[6]);
  $('#edit-customer-form input[name="primary_contact"]').val(data[8]);
}

$(document).ready(function() {
  fetchTable();
});
