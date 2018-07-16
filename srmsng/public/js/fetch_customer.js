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
        searchable: true
      },
      {
        targets: 2,
        data: function(row) {
          return [row[0], row[2]];
        },
        render: function(data) {
          return "<a href='/srmsng/public/account/customer/view?id=" + data[0] + "'>" + data[1] + "</a>";
        }
      },
      {
        targets: -1,
        data: function(row) {
          return row;
        },
        searchable: false,
        render: function(data) {
          var button = document.createElement("button");
          button.setAttribute("class", "btn btn-primary");
          button.setAttribute("data-target", "#edit-customer-popup");
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

$("#add-customer-form").on("submit", () => {
  if ($("#add-taxid").val().length != 13) {
    $("#add-taxid-warning").attr("class", "form-text text-danger");
    $("#add-taxid").addClass("is-invalid");
    return false;
  }
  $.ajax({
    type: "POST",
    url: "/srmsng/public/index.php/api/admin/addcustomer",
    data: $("#add-customer-form").serialize(),
    success: data => {
      //console.log(data);
      $("#add-customer-popup").modal("hide");
      document.getElementById("add-customer-form").reset();
      window.location = "/srmsng/public/account/customer?add_success=true";
    },
    error: err => {
      console.log(err);
    }
  });
});

$("#edit-customer-form").on("submit", () => {
  if ($('input[name="taxid"]').val().length != 13) {
    $("#edit-taxid-warning").attr("class", "form-text text-danger");
    $("#edit-taxid").addClass("is-invalid");
    return false;
  }
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/admin/updatecustomer",
    data: $("#edit-customer-form").serialize(),
    success: data => {
      //console.log(data);
      //console.log($("#edit-customer-form").serialize());
      $("#edit-customer-popup").modal("hide");
      document.getElementById("edit-customer-form").reset();
      window.location = "/srmsng/public/account/customer?update_success=true";
    },
    error: err => {
      console.log(err);
    }
  });
});

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
