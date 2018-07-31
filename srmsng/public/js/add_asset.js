// This file is loaded in the add and edit asset pages. It handles
// searches, the flow when adding/editing assets, and form submissions
// for adding/editing a customer, location, sale order, and item.

// Check required fields in a div
function checkFilled(id) {
  var filled = true;
  $(id + " input:required").each(function() {
    if (!$(this).val()) {
      filled = false;
      return false;
    }
  });
  return filled;
}

// Front-end search
// inputid = id of the input field
// dropdownid = id of the dropdown with content to search from (the content
// of the dropdown comes from the server)
function filterFunction(inputid, dropdownid) {
  var input, filter, span, i;
  input = document.getElementById(inputid);
  filter = input.value.toUpperCase();
  div = document.getElementById(dropdownid);
  span = div.getElementsByClassName("autofill-item");
  var found = false;
  for (i = 0; i < span.length; i++) {
    if (span[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
      span[i].style.display = "";
      found = true;
    } else {
      span[i].style.display = "none";
    }
  }
  if (!found) {
    $("#" + dropdownid + " .autofill-item-default").css("display", "block");
  } else {
    $("#" + dropdownid + " .autofill-item-default").css("display", "none");
  }
}

// 0 = add; 1 = edit
// Tells the add/edit forms to submit the form correctly
var isEdit = 0;

$(document).ready(function() {
  // Prevent users from hitting enter and accidentally submitting a form
  $(window).keydown(function(event) {
    if (event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
  /* ================================ Customer ================================ */
  // Search Customer
  $("#customer-search-field").on("keydown", function() {
    // Press enter to search
    if (event.keyCode == 13) {
      $("#customer-search").trigger("click");
    }
    // Clear data when the user starts typing
    $("#customer-choose .sub-field, #customer-code-search-field").val("");
    document.getElementById("customer-dropdown").innerHTML = "";

    // Disable edit button
    $("#customer-edit-button").addClass("disabled");

    // Disable location fieldset
    $("#location-choose").addClass("disabled");
  });
  // Search Customer Code
  $("#customer-code-search-field").on("keydown", function() {
    // Press enter to search
    if (event.keyCode == 13) {
      $("#customer-code-search").trigger("click");
    }
    // Clear data when the user types
    $("#customer-choose .sub-field, #customer-search-field").val("");
    document.getElementById("customer-code-dropdown").innerHTML = "";

    // Disable edit button
    $("#customer-edit-button").addClass("disabled");

    // Disable location fieldset
    $("#location-choose").addClass("disabled");
  });

  // Begin searching for customer
  $("#customer-search").on("click", function() {
    if ($("#customer-search-field").val().length >= 3) {
      // Add data to dropdown and display
      fetch("/srmsng/public/index.php/api/admin/customername?customer_name=" + $("#customer-search-field").val())
        .then(resp => {
          return resp.json();
        })
        .then(data_json => {
          // clear previous data from dropdown
          document.getElementById("customer-dropdown").innerHTML =
            '<span class="autofill-item-default">No Data Found</span>';

          // Add item to dropdown
          data_json.forEach(element => {
            var item = document.createElement("span");
            item.setAttribute("class", "autofill-item");
            item.innerHTML = element["customer_name"];
            item.onclick = function() {
              // Fills in data to the corresponding fields when a dropdown item is clicked
              $("input[name='customer_name']").val(element["customer_name"]);
              // Hide dropdown
              $("#customer-dropdown")
                .attr("tabindex", -1)
                .focusout();
              $("input[name='customer_no']").val(element["customer_no"]);
              $("#customer-no-add").val(element["customer_no"]);

              // enable edit button
              $("#customer-edit-button").removeClass("disabled");
            };
            document.getElementById("customer-dropdown").appendChild(item);
          });
        });

      // Show the dropdown
      $("#customer-dropdown").addClass("show");
      $("#customer-dropdown")
        .attr("tabindex", -1)
        .focus();
    }
  });
  // Begin searching for customer code
  $("#customer-code-search").on("click", function() {
    if ($("#customer-code-search-field").val().length >= 3) {
      // Add data to dropdown and display
      fetch("/srmsng/public/index.php/api/admin/customercode?customer_code=" + $("#customer-code-search-field").val())
        .then(resp => {
          return resp.json();
        })
        .then(data_json => {
          // clear dropdown
          document.getElementById("customer-code-dropdown").innerHTML =
            '<span class="autofill-item-default">No Data Found</span>';

          data_json.forEach(element => {
            var item = document.createElement("span");
            item.setAttribute("class", "autofill-item");
            item.innerHTML = element["customer_name"];
            item.onclick = function() {
              // Fills in data to the corresponding fields when a dropdown item is clicked
              $("input[name='customer_no']").val(element["customer_no"]);
              // Hide dropdown
              $("#customer-code-dropdown")
                .attr("tabindex", -1)
                .focusout();
              $("input[name='customer_name']").val(element["customer_name"]);
              $("#customer-no-add").val(element["customer_no"]);
              // enable edit button
              $("#customer-edit-button").removeClass("disabled");
            };
            document.getElementById("customer-code-dropdown").appendChild(item);
          });
        });

      // Show the dropdown
      $("#customer-code-dropdown").addClass("show");
      $("#customer-code-dropdown")
        .attr("tabindex", -1)
        .focus();
    }
  });

  // Hide dropdown on focusout (when the user clicks outside of the dropdown area)
  $("#customer-dropdown, #customer-code-dropdown").on("focusout", function() {
    $(this).removeClass("show");
  });

  // Choose customer button
  $("#customer-choose-button").on("click", function() {
    // clear location and fill with new info
    $("#location-choose input, #location-choose select").val("");

    // if both customer name and code in #customer-choose are filled, begin querying location
    if (checkFilled("#customer-choose")) {
      // fetch location and sale order for that customer
      fetch("/srmsng/public/index.php/api/admin/location?customer_no=" + $('input[name="customer_no"]').val())
        .then(resp => {
          return resp.json();
        })
        .then(data_json => {
          // clear dropdown
          document.getElementById("location-dropdown").innerHTML +=
            '<span class="autofill-item-default">No Data Found</span>';
          document.getElementById("location-code-dropdown").innerHTML +=
            '<span class="autofill-item-default">No Data Found</span>';
          data_json.forEach(element => {
            // fetch location
            var item = document.createElement("span");
            item.setAttribute("class", "autofill-item");
            item.innerHTML = element["sitename"];
            item.onclick = function() {
              $("input[name='sitename']").val(element["sitename"]);
              $("#location-dropdown")
                .attr("tabindex", -1)
                .focusout();
              $("input[name='location_code']").val(element["location_code"]);
              for (var value in data_json[0]) {
                $("#location-choose .sub-field[name='" + value + "']").val(data_json[0][value]);
              }

              // Lat lng
              $("#location-choose .sub-field[name='lat']").val(data_json[0]["latitude"]);
              $("#location-choose .sub-field[name='lon']").val(data_json[0]["longitude"]);

              // enable edit button
              $("#location-edit-button").removeClass("disabled");
            };

            // fetch location code
            var codeitem = document.createElement("span");
            codeitem.setAttribute("class", "autofill-item");
            codeitem.innerHTML = element["location_code"];
            codeitem.onclick = function() {
              $("input[name='location_code']").val(element["location_code"]);
              $("#location-code-dropdown")
                .attr("tabindex", -1)
                .focusout();
              $("input[name='sitename']").val(element["sitename"]);
              for (var value in data_json[0]) {
                $("#location-choose .sub-field[name='" + value + "']").val(data_json[0][value]);
              }
              // Lat lng
              $("#location-choose .sub-field[name='lat']").val(data_json[0]["latitude"]);
              $("#location-choose .sub-field[name='lon']").val(data_json[0]["longitude"]);
              // enable edit button
              $("#location-edit-button").removeClass("disabled");
            };

            // The name and code of the locations are added to #location-dropdown.
            // Location searches are done front-end
            document.getElementById("location-dropdown").appendChild(item);
            document.getElementById("location-code-dropdown").appendChild(codeitem);
          });
        });
      // Enabled location fieldset
      $("#location-choose").removeClass("disabled");
      $("#customer-warning").addClass("hidden");
    } else {
      // If the customer name and customer code fields aren't both filled in,
      // the location fieldset is disabled
      $("#location-choose").addClass("disabled");
      $("#customer-warning").removeClass("hidden");
    }
  });

  /* ================================ Location ================================ */
  // Search Location
  // Location searches are done front-end
  $("#location-search-field").on("keydown", function() {
    // Hit enter to search
    if (event.keyCode == 13) {
      $("#location-search").trigger("click");
    }
    // Clear data when the user types
    $("#location-choose .sub-field, #location-code-search-field").val("");
    // disable edit button
    $("#location-edit-button").addClass("disabled");
  });
  // Search location code
  $("#location-code-search-field").on("keydown", function() {
    if (event.keyCode == 13) {
      $("#location-code-search").trigger("click");
    }
    // Clear data when the user types
    $("#location-choose .sub-field, #location-search-field").val("");
    // disable edit button
    $("#location-edit-button").addClass("disabled");
  });

  // search and show dropdown with filtered results
  $("#location-search").on("click", function() {
    if ($("#location-search-field").val().length >= 3) {
      // Searching begins here
      filterFunction("location-search-field", "location-dropdown");
      $("#location-dropdown").addClass("show");
      $("#location-dropdown")
        .attr("tabindex", -1)
        .focus();
    }
  });
  // search show dropdown with filtered results
  $("#location-code-search").on("click", function() {
    if ($("#location-code-search-field").val().length >= 3) {
      // Searching begins here
      filterFunction("location-code-search-field", "location-code-dropdown");
      $("#location-code-dropdown").addClass("show");
      $("#location-code-dropdown")
        .attr("tabindex", -1)
        .focus();
    }
  });

  // hide dropdowns on focusout (when the user clicks outside of the dropdown area)
  $("#location-dropdown").on("focusout", function() {
    $(this).removeClass("show");
  });
  $("#location-code-dropdown").on("focusout", function() {
    $(this).removeClass("show");
  });

  /* ================================ Sale Order ================================ */
  // Search Sale Order
  $("#sale-order-search-field").on("keydown", function() {
    // Press enter to search
    if (event.keyCode == 13) {
      $("#sale-order-search").trigger("click");
    }
    // Clear data when the user types
    $("#sale-order-choose .sub-field").val("");
    document.getElementById("sale-order-dropdown").innerHTML = "";
    // disable edit button
    $("#sale-order-edit-button").addClass("disabled");
  });

  $("#sale-order-search").on("click", function() {
    if ($("#sale-order-search-field").val().length >= 3) {
      // Add data to dropdown and display
      // Begin querying
      fetch("/srmsng/public/index.php/api/admin/getsaleorder?sale_order_no=" + $("#sale-order-search-field").val())
        .then(resp => {
          return resp.json();
        })
        .then(data_json => {
          // clear dropdown
          document.getElementById("sale-order-dropdown").innerHTML =
            '<span class="autofill-item-default">No Data Found</span>';

          data_json.forEach(element => {
            var item = document.createElement("span");
            item.setAttribute("class", "autofill-item");
            item.innerHTML = element["sale_order_no"];
            item.onclick = function() {
              $("#sale-order-search-field").val(element["sale_order_no"]);
              $("#sale-order-dropdown")
                .attr("tabindex", -1)
                .focusout();
              for (var value in data_json[0]) {
                $("#sale-order-choose .sub-field[name='" + value + "']").val(data_json[0][value]);
              }
              // enable edit button
              $("#sale-order-edit-button").removeClass("disabled");
            };
            document.getElementById("sale-order-dropdown").appendChild(item);
          });
        });

      // Display dropdown
      $("#sale-order-dropdown").addClass("show");
      $("#sale-order-dropdown")
        .attr("tabindex", -1)
        .focus();
    }
  });
  // hide dropdown on focusout (when the user clicks outside of the dropdown)
  $("#sale-order-dropdown").on("focusout", function() {
    $(this).removeClass("show");
  });

  /* ================================ Item ================================ */
  // Search Item
  $("#item-search-field").on("keydown", function() {
    // Press enter to search
    if (event.keyCode == 13) {
      $("#item-search").trigger("click");
    }
    // Clear data when the user types
    $("#item-choose .sub-field, #item-number-search-field").val("");
    document.getElementById("item-dropdown").innerHTML = "";
    // disable edit button
    $("#item-edit-button").addClass("disabled");
  });
  // Search Item Number
  $("#item-number-search-field").on("keydown", function() {
    // Press enter to search
    if (event.keyCode == 13) {
      $("#item-number-search").trigger("click");
    }
    // Clear data when the user types
    $("#item-choose .sub-field, #item-search-field").val("");
    document.getElementById("item-number-dropdown").innerHTML = "";
    // disable edit button
    $("#item-edit-button").addClass("disabled");
  });

  $("#item-search").on("click", function() {
    if ($("#item-search-field").val().length >= 3) {
      // Add data to dropdown and display
      // Begin querying
      fetch("/srmsng/public/index.php/api/admin/item?model=" + $("#item-search-field").val())
        .then(resp => {
          return resp.json();
        })
        .then(data_json => {
          // clear dropdown
          document.getElementById("item-dropdown").innerHTML =
            '<span class="autofill-item-default">No Data Found</span>';

          data_json.forEach(element => {
            var item = document.createElement("span");
            item.setAttribute("class", "autofill-item");
            item.innerHTML = element["model"];
            item.onclick = function() {
              $("#item-search-field").val(element["model"]);
              $("#item-dropdown")
                .attr("tabindex", -1)
                .focusout();
              $("#item-number-search-field").val(element["itemnumber"]);
              for (var value in data_json[0]) {
                $("#item-choose .sub-field[name='" + value + "']").val(data_json[0][value]);
              }
              // enable edit button
              $("#item-edit-button").removeClass("disabled");
            };
            document.getElementById("item-dropdown").appendChild(item);
          });
        });

      // Display dropdown
      $("#item-dropdown").addClass("show");
      $("#item-dropdown")
        .attr("tabindex", -1)
        .focus();
    }
  });

  // Hide dropdown on focus out (when the user clicks outside of the dropdown area)
  $("#item-dropdown").on("focusout", function() {
    $(this).removeClass("show");
  });

  // Search for item using item number
  $("#item-number-search").on("click", function() {
    if ($("#item-number-search-field").val().length >= 3) {
      // Add data to dropdown and display
      // Begin querying
      fetch("/srmsng/public/index.php/api/admin/itemnumber?itemnumber=" + $("#item-number-search-field").val())
        .then(resp => {
          return resp.json();
        })
        .then(data_json => {
          // clear dropdown
          console.log(data_json);
          document.getElementById("item-number-dropdown").innerHTML =
            '<span class="autofill-item-default">No Data Found</span>';

          data_json.forEach(element => {
            var item = document.createElement("span");
            item.setAttribute("class", "autofill-item");
            item.innerHTML = element["model"];
            item.onclick = function() {
              $("#item-number-search-field").val(element["itemnumber"]);
              $("#item-number-dropdown")
                .attr("tabindex", -1)
                .focusout();
              $("#item-search-field").val(element["model"]);
              for (var value in data_json[0]) {
                $("#item-choose .sub-field[name='" + value + "']").val(data_json[0][value]);
              }
              // enable edit button
              $("#item-edit-button").removeClass("disabled");
            };

            document.getElementById("item-number-dropdown").appendChild(item);
          });
        });

      // Show dropdown
      $("#item-number-dropdown").addClass("show");
      $("#item-number-dropdown")
        .attr("tabindex", -1)
        .focus();
    }
  });

  // Hide dropdown on focusout (when the user clicks outside of the dropdown area)
  $("#item-number-dropdown").on("focusout", function() {
    $(this).removeClass("show");
  });

  /* ================================ FSE ================================ */
  // Fetch FSE and put their names into <select>
  fetch("/srmsng/public/index.php/api/admin/getfse")
    .then(resp => {
      return resp.json();
    })
    .then(data_json => {
      data_json.forEach(element => {
        if (element["fse_code"] != 0) {
          var option = document.createElement("option");
          option.setAttribute("value", element["fse_code"]);
          option.innerHTML = element["engname"];
          document.getElementById("fse-dropdown").appendChild(option);
        }
      });
    });
  /* ================================ Batteries ================================ */
  // Fetch battery specifications and put into <select>
  fetch("/srmsng/public/index.php/api/admin/batterytype")
    .then(resp => {
      return resp.json();
    })
    .then(data_json => {
      data_json.forEach(element => {
        var option = document.createElement("option");
        option.setAttribute("value", element["battery_type"]);
        option.innerHTML = element["battery_type"];
        document.getElementById("battery-dropdown").appendChild(option);
      });
    });

  /* ============================================================================== */
  /* ================================ EDIT BUTTONS ================================ */

  // Bind add and edit submit actions
  // Submission behavior depends if the user is adding or editing
  $("#add-customer-form").on("submit", function(e) {
    e.preventDefault();
    addEditCustomer();
  });
  $("#add-location-form").on("submit", function(e) {
    e.preventDefault();
    addEditLocation();
  });
  $("#add-sale-order-form").on("submit", function(e) {
    e.preventDefault();
    addEditSaleOrder();
  });
  $("#add-item-form").on("submit", function(e) {
    e.preventDefault();
    addEditItem();
  });

  // ================== Add/Edit Customer ================= //
  // Add button
  $("#add_customer").on("click", function() {
    // Set up the modal
    $("#add-customer-popup .modal-title").text("Add New Customer");
    $("#add-customer-form input").val("");
    isEdit = 0;
  });
  // Edit button
  $("#customer-edit-button").on("click", function() {
    // Set up the modal
    $("#add-customer-popup .modal-title").text("Edit Customer");
    $("#add-customer-popup input[name='customer_no']").prop("readonly", true);

    // Get information from the customer being edited
    fetch("/srmsng/public/index.php/api/admin/getsinglecustomer?customer_no=" + $('input[name="customer_no"]').val())
      .then(resp => {
        return resp.json();
      })
      .then(data_json => {
        isEdit = 1;
        for (var value in data_json[0]) {
          $("#add-customer-form input[name='" + value + "']").val(data_json[0][value]);
        }
        $("#add-customer-form select[name='account_group']").val(data_json[0]["account_group"]);
      });
  });

  // ================== Add/Edit Location ================= //
  // Add Button
  $("#add_location").on("click", function() {
    // Set up the modal
    $("#add-location-popup .modal-title").text("Add New Location");
    $("#add-location-form input.clear").val("");
    $("#add-location-form input[name='country']").val("Thailand");
    $("#add-location-popup input[name='location_code']").prop("readonly", false);
    isEdit = 0;

    // Lat Lng
    $("#add-location-form input[name='lat']").val("");
    $("#add-location-form input[name='lon']").val("");
  });
  // Edit button
  $("#location-edit-button").on("click", function() {
    // Set up the modal
    $("#add-location-popup .modal-title").text("Edit Location");
    $("#add-location-popup input[name='location_code']").prop("readonly", true);

    // Get information of the location the user is editing
    fetch(
      "/srmsng/public/index.php/api/admin/getsinglelocation?location_code=" + $('input[name="location_code"]').val()
    )
      .then(resp => {
        return resp.json();
      })
      .then(data_json => {
        isEdit = 1;
        for (var value in data_json[0]) {
          $("#add-location-form input[name='" + value + "']").val(data_json[0][value]);
        }
        // Lat lng
        $("#add-location-form input[name='lat']").val(data_json[0]["latitude"]);
        $("#add-location-form input[name='lon']").val(data_json[0]["longitude"]);
      });
  });

  // ================== Add/Edit Sale Order ================= //
  // Add button
  $("#add_sale_order").on("click", function() {
    // Set up the modal
    $("#add-sale-order-popup .modal-title").text("Add New Sale Order");
    $("#add-sale-order-form input").val("");
    $("#add-sale-order-popup input[name='sale_order_no']").prop("readonly", false);
    isEdit = 0;
  });
  // Edit button
  $("#sale-order-edit-button").on("click", function() {
    // Set up the modal
    $("#add-sale-order-popup .modal-title").text("Edit Sale Order");
    $("#add-sale-order-popup input[name='sale_order_no']").prop("readonly", true);

    // Get the information of the sale order the user is editing
    fetch("/srmsng/public/index.php/api/admin/getsaleorder?sale_order_no=" + $('input[name="sale_order_no"]').val())
      .then(resp => {
        return resp.json();
      })
      .then(data_json => {
        isEdit = 1;
        for (var value in data_json[0]) {
          $("#add-sale-order-form input[name='" + value + "']").val(data_json[0][value]);
        }
        $("#add-sale-order-form select[name='since']").val(data_json[0]["since"]);
      });
  });

  // ================== Add/Edit Item ================= //
  // Add Button
  $("#add_item").on("click", function() {
    // Set up the modal
    $("#add-item-popup .modal-title").text("Add New Item");
    $("#add-item-form input").val("");
    $("#add-item-popup input[name='itemnumber']").prop("readonly", false);
    isEdit = 0;
  });
  // Edit button
  $("#item-edit-button").on("click", function() {
    // Set up the modal
    $("#add-item-popup .modal-title").text("Edit Item");
    $("#add-item-popup input[name='itemnumber']").prop("readonly", true);

    // Get the information of the item the user is editing
    fetch("/srmsng/public/index.php/api/admin/getsingleitem?itemnumber=" + $('input[name="itemnumber"]').val())
      .then(resp => {
        return resp.json();
      })
      .then(data_json => {
        isEdit = 1;
        for (var value in data_json[0]) {
          $("#add-item-form input[name='" + value + "']").val(data_json[0][value]);
        }
      });
  });
});

/* ========================================================================== */
/* ========================== ADD/EDIT SUBMISSION =========================== */
//update fields after editing or adding
function updateFields(fieldnames, serializedData) {
  for (i = 0; i < fieldnames.length; i++) {
    $('input[name="' + fieldnames[i] + '"]').val(serializedData[i].value);
  }
}

// Add/edit customer
function addEditCustomer() {
  let serialData = $("#add-customer-form").serializeArray();
  if (isEdit) {
    // Editing customer
    $.ajax({
      type: "PUT",
      url: "/srmsng/public/index.php/api/admin/updatecustomer",
      data: $("#add-customer-form").serialize(),
      success: data => {
        console.log(data);
        updateFields(["customer_no", "customer_name"], serialData);
        $("#add-customer-popup").modal("hide");
        document.getElementById("add-customer-form").reset();
      },
      error: err => {
        console.log(err);
      }
    });
  } else {
    // Adding customer
    $.ajax({
      type: "POST",
      url: "/srmsng/public/index.php/api/admin/addcustomer",
      data: $("#add-customer-form").serialize(),
      success: data => {
        console.log(data);
        // enable edit button
        $("#customer-edit-button").removeClass("disabled");
        updateFields(["customer_no", "customer_name"], serialData);
        $("#add-customer-popup").modal("hide");
        document.getElementById("add-customer-form").reset();
      },
      error: err => {
        alert(err);
      }
    });
  }
  return false;
}

// Add/edit location
function addEditLocation() {
  let serialData = $("#add-location-form").serializeArray();
  if (isEdit) {
    // Adding location
    $.ajax({
      type: "PUT",
      url: "/srmsng/public/index.php/api/admin/updatelocation",
      data: $("#add-location-form").serialize(),
      success: data => {
        updateFields(
          [
            "location_code",
            "sitename",
            "house_no",
            "village_no",
            "soi",
            "road",
            "sub_district",
            "district",
            "province",
            "postal_code",
            "region",
            "country",
            "store_phone",
            "lat",
            "lon"
          ],
          serialData
        );
        $("#add-location-popup").modal("hide");
      },
      error: err => {
        alert(err);
      }
    });
  } else {
    // Adding location
    $.ajax({
      type: "POST",
      url: "/srmsng/public/index.php/api/admin/addlocation",
      data: $("#add-location-form").serialize(),
      success: data => {
        // enable edit button
        $("#location-edit-button").removeClass("disabled");
        updateFields(
          [
            "location_code",
            "sitename",
            "house_no",
            "village_no",
            "soi",
            "road",
            "sub_district",
            "district",
            "province",
            "postal_code",
            "region",
            "country",
            "store_phone",
            "lat",
            "lon"
          ],
          serialData
        );
        $("#add-location-popup").modal("hide");
        document.getElementById("add-location-form").reset();
      },
      error: err => {
        alert(err);
      }
    });
  }
  return false;
}

// Add/edit sale order
function addEditSaleOrder() {
  let serialData = $("#add-sale-order-form").serializeArray();
  if (isEdit) {
    // Edit sale order
    $.ajax({
      type: "PUT",
      url: "/srmsng/public/index.php/api/admin/updatesaleorder",
      data: $("#add-sale-order-form").serialize(),
      success: data => {
        console.log(data);
        updateFields(["sale_order_no", "date_order", "since", "po_number", "po_date", "do_number"], serialData);
        $("#add-sale-order-popup").modal("hide");
        document.getElementById("add-sale-order-form").reset();
      },
      error: err => {
        alert(err);
      }
    });
  } else {
    // Add sale order
    $.ajax({
      type: "POST",
      url: "/srmsng/public/index.php/api/admin/addsaleorder",
      data: $("#add-sale-order-form").serialize(),
      success: data => {
        console.log(data);
        // enable edit button
        $("#sale-order-edit-button").removeClass("disabled");
        updateFields(["sale_order_no", "date_order", "since", "po_number", "po_date", "do_number"], serialData);
        $("#add-sale-order-popup").modal("hide");
        document.getElementById("add-sale-order-form").reset();
      },
      error: err => {
        alert(err);
      }
    });
  }

  return false;
}

// Add/edit item
function addEditItem() {
  let serialData = $("#add-item-form").serializeArray();
  if (isEdit) {
    // Edit item
    $.ajax({
      type: "PUT",
      url: "/srmsng/public/index.php/api/admin/updateitem",
      data: $("#add-item-form").serialize(),
      success: data => {
        console.log(data);
        updateFields(["itemnumber", "model", "power"], serialData);
        $("#add-item-popup").modal("hide");
        document.getElementById("add-item-form").reset();
      },
      error: err => {
        alert(err);
      }
    });
  } else {
    // Add item
    $.ajax({
      type: "POST",
      url: "/srmsng/public/index.php/api/admin/additem",
      data: $("#add-item-form").serialize(),
      success: data => {
        console.log(data);
        // enable edit button
        $("#item-edit-button").removeClass("disabled");
        updateFields(["itemnumber", "model", "power"], serialData);
        $("#add-item-popup").modal("hide");
        document.getElementById("add-item-form").reset();
      },
      error: err => {
        alert(err);
      }
    });
  }
  return false;
}
