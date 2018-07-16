$("#edit-location-form").on("submit", () => {
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/admin/updatelocation",
    data: $("#edit-location-form").serialize(),
    success: data => {
      $("#edit-location-popup").modal("hide");
      window.location =
        "/srmsng/public/account/customer/view?id=" + $("input[name='customer_no']").val() + "&update_success=true";
      document.getElementById("edit-location-form").reset();
    },
    error: err => {
      console.log(err);
    }
  });
});

$("#add-location-form").on("submit", () => {
  $.ajax({
    type: "POST",
    url: "/srmsng/public/index.php/api/admin/addlocation",
    data: $("#add-location-form").serialize(),
    success: data => {
      //console.log(data);
      $("#add-location-popup").modal("hide");
      window.location =
        "/srmsng/public/account/customer/view?id=" + $("input[name='customer_no']").val() + "&add_success=true";
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
    type: "GET",
    url: "/srmsng/public/index.php/api/admin/getsinglecustomer",
    data: "customer_no=" + myVar,
    dataType: "JSON",
    success: data => {
      if (data.length != 0) {
        //console.log(data);
        document.getElementById("customer_number").innerHTML = data[0].customer_no;
        document.getElementById("customer_name").innerHTML = data[0].customer_name;
        document.getElementById("account_group").innerHTML = data[0].account_group;
        document.getElementById("primary_contact").innerHTML = data[0].primary_contact;
        document.getElementById("product_sale").innerHTML = data[0].account_owner;
        document.getElementById("tax_id").innerHTML = data[0].taxid;
      } else {
        $(".view").attr("style", "display: none");
      }
      $("#supertable").DataTable({
        //columnDefs: [{ orderable: false, targets: [6, 7, 9] }],
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
          setUpFixed();
          $("#supertable").addClass("display");
        },
        drawCallback: function() {
          setUpFixed();
        }
      });
    }
  });
});

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
