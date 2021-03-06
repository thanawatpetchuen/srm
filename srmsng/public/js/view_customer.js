// This file is loaded at /account/customer/view?id=

// Submissions for location
// Edit location
var toastDuration = 1500;
$("#edit-location-form").on("submit", () => {
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/admin/updatelocation",
    data: $("#edit-location-form").serialize(),
    success: data => {
      $("#edit-location-popup").modal("hide");
      toastr.options = {
        positionClass: "toast-top-center"
      };
      toastr.success("<span>Please wait, this website is going to refresh...</span>");
      setTimeout(() => {
        window.location =
        "/srmsng/public/account/customer/view?id=" + $("input[name='customer_no']").val() + "&update_success=true";
      }, toastDuration);
      
      document.getElementById("edit-location-form").reset();
    },
    error: err => {
      console.log(err);
    }
  });
});
// Add location
$("#add-location-form").on("submit", () => {
  $.ajax({
    type: "POST",
    url: "/srmsng/public/index.php/api/admin/addlocation",
    data: $("#add-location-form").serialize(),
    success: data => {
      //console.log(data);
      $("#add-location-popup").modal("hide");
      toastr.options = {
        positionClass: "toast-top-center"
      };
      toastr.success("<span>Please wait, this website is going to refresh...</span>");
      setTimeout(() => {
        window.location =
        "/srmsng/public/account/customer/view?id=" + $("input[name='customer_no']").val() + "&add_success=true";
      }, toastDuration);
     
      document.getElementById("add-location-form").reset();
    },
    error: err => {
      console.log(err);
    }
  });
});

$(document).ready(() => {
  var myElement = document.getElementById("mainn");
  var myVar = myElement.dataset.myVar;

  $.ajax({
    // Get details of the customer
    type: "GET",
    url: "/srmsng/public/index.php/api/admin/getsinglecustomer",
    data: "customer_no=" + myVar,
    dataType: "JSON",
    success: data => {
      // Display data
      if (data.length != 0) {
        document.getElementById("customer_number").innerHTML = data[0].customer_no;
        document.getElementById("customer_name").innerHTML = data[0].customer_name;
        document.getElementById("account_group").innerHTML = data[0].account_group;
        document.getElementById("primary_contact").innerHTML = data[0].primary_contact;
        document.getElementById("product_sale").innerHTML = data[0].account_owner;
        document.getElementById("tax_id").innerHTML = data[0].taxid;
      } else {
        $(".view").attr("style", "display: none");
      }

      // Fetch location for the customer
      $("#supertable").DataTable({
        stateSave: true,
        deferRender: true,
        dom: '<lf<"table-wrapper"t>ip>',
        processing: true,
        serverSide: true,
        ajax: $.fn.dataTable.pipeline({
          url: "/srmsng/public/fetch-ajax/fetchLocation.php?id=" + myVar,
          pages: 5 // number of pages to cache,
        }),
        columnDefs: [
          {
            // Edit location button
            targets: -1,
            data: function(row) {
              return row;
            },
            render: function(data) {
              var button = document.createElement("button");
              button.setAttribute("class", "btn btn-primary");
              button.setAttribute("data-target", "#edit-location-popup");
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
  });
});

// Fill the fields when the user clicks the edit button
function fillField(data) {
  document.getElementById("location_code").value = data[0];
  document.getElementById("customer_no").value = data[1];
  document.getElementById("sitename").value = data[2];
  document.getElementById("house_no").value = data[3];
  document.getElementById("village_no").value = data[4];
  document.getElementById("soi").value = data[5];
  document.getElementById("road").value = data[6];
  document.getElementById("sub_district").value = data[8];
  document.getElementById("district").value = data[7];
  document.getElementById("province").value = data[9];
  document.getElementById("postal_code").value = data[10];
  document.getElementById("region").value = data[11];
  document.getElementById("country").value = data[12];
  document.getElementById("store_phone").value = data[13];
  document.getElementById("latitude").value = data[14];
  document.getElementById("longitude").value = data[15];
}
