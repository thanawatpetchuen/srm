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
    // Clear data when the user types
    $("#customer-choose .sub-field, #customer-code-search-field").val("");
    document.getElementById("customer-dropdown").innerHTML = "";

    // Disable edit button
    $("#customer-edit-button").addClass("disabled");

    // Disable location
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

    // Disable location
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
          // clear dropdown
          document.getElementById("customer-dropdown").innerHTML =
            '<span class="autofill-item-default">No Data Found</span>';

          data_json.forEach(element => {
            var item = document.createElement("span");
            item.setAttribute("class", "autofill-item");
            item.innerHTML = element["customer_name"];
            item.onclick = function() {
              $("input[name='customer_name']").val(element["customer_name"]);
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
              $("input[name='customer_no']").val(element["customer_no"]);
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

      $("#customer-code-dropdown").addClass("show");
      $("#customer-code-dropdown")
        .attr("tabindex", -1)
        .focus();
    }
  });
  $("#customer-dropdown, #customer-code-dropdown").on("focusout", function() {
    $(this).removeClass("show");
  });

  // When user chooses a customer
  $("#customer-choose-button").on("click", function() {
    // clear location and fill with new info
    $("#location-choose input, #location-choose select").val("");

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
            document.getElementById("location-dropdown").appendChild(item);
            document.getElementById("location-code-dropdown").appendChild(codeitem);
          });
        });
      $("#location-choose").removeClass("disabled");
      $("#customer-warning").addClass("hidden");
    } else {
      $("#location-choose").addClass("disabled");
      $("#customer-warning").removeClass("hidden");
    }
  });

  /* ================================ Location ================================ */
  // Search Location
  $("#location-search-field").on("keydown", function() {
    if (event.keyCode == 13) {
      $("#location-search").trigger("click");
    }
    // Clear data when the user types
    $("#location-choose .sub-field, #location-code-search-field").val("");
    // disable edit button
    $("#location-edit-button").addClass("disabled");
  });
  // Search Location Code
  $("#location-code-search-field").on("keydown", function() {
    if (event.keyCode == 13) {
      $("#location-code-search").trigger("click");
    }
    // Clear data when the user types
    $("#location-choose .sub-field, #location-search-field").val("");
    // disable edit button
    $("#location-edit-button").addClass("disabled");
  });

  $("#location-search").on("click", function() {
    if ($("#location-search-field").val().length >= 3) {
      // show dropdown with filtered results
      filterFunction("location-search-field", "location-dropdown");
      $("#location-dropdown").addClass("show");
      $("#location-dropdown")
        .attr("tabindex", -1)
        .focus();
    }
  });
  $("#location-code-search").on("click", function() {
    if ($("#location-code-search-field").val().length >= 3) {
      // show dropdown with filtered results
      filterFunction("location-code-search-field", "location-code-dropdown");
      $("#location-code-dropdown").addClass("show");
      $("#location-code-dropdown")
        .attr("tabindex", -1)
        .focus();
    }
  });
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

      $("#sale-order-dropdown").addClass("show");
      $("#itesale-orderm-dropdown")
        .attr("tabindex", -1)
        .focus();
    }
  });
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

      $("#item-dropdown").addClass("show");
      $("#item-dropdown")
        .attr("tabindex", -1)
        .focus();
    }
  });
  $("#item-dropdown").on("focusout", function() {
    $(this).removeClass("show");
  });

  $("#item-number-search").on("click", function() {
    if ($("#item-number-search-field").val().length >= 3) {
      // Add data to dropdown and display
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

      $("#item-number-dropdown").addClass("show");
      $("#item-number-dropdown")
        .attr("tabindex", -1)
        .focus();
    }
  });
  $("#item-number-dropdown").on("focusout", function() {
    $(this).removeClass("show");
  });

  /* ================================ FSE ================================ */
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

  // Bind add actions
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
  $("#add_customer").on("click", function() {
    $("#add-customer-popup .modal-title").text("Add New Customer");
    $("#add-customer-form input").val("");
    isEdit = 0;
  });
  $("#customer-edit-button").on("click", function() {
    $("#add-customer-popup .modal-title").text("Edit Customer");
    $("#add-customer-popup input[name='customer_no']").prop("readonly", true);
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
  $("#add_location").on("click", function() {
    $("#add-location-popup .modal-title").text("Add New Location");
    $("#add-location-form input.clear").val("");
    $("#add-location-form input[name='country']").val("Thailand");
    $("#add-location-popup input[name='location_code']").prop("readonly", false);
    isEdit = 0;
    $("#add-location-form input[name='lat']").val("");
    $("#add-location-form input[name='lon']").val("");
  });
  $("#location-edit-button").on("click", function() {
    $("#add-location-popup .modal-title").text("Edit Location");
    $("#add-location-popup input[name='location_code']").prop("readonly", true);
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
  $("#add_sale_order").on("click", function() {
    $("#add-sale-order-popup .modal-title").text("Add New Sale Order");
    $("#add-sale-order-form input").val("");
    $("#add-sale-order-popup input[name='sale_order_no']").prop("readonly", false);
    isEdit = 0;
  });
  $("#sale-order-edit-button").on("click", function() {
    $("#add-sale-order-popup .modal-title").text("Edit Sale Order");
    $("#add-sale-order-popup input[name='sale_order_no']").prop("readonly", true);
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
  $("#add_item").on("click", function() {
    $("#add-item-popup .modal-title").text("Add New Item");
    $("#add-item-form input").val("");
    $("#add-item-popup input[name='itemnumber']").prop("readonly", false);
    isEdit = 0;
  });
  $("#item-edit-button").on("click", function() {
    $("#add-item-popup .modal-title").text("Edit Item");
    $("#add-item-popup input[name='itemnumber']").prop("readonly", true);
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
/* ================================ ADD/EDIT ================================ */
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
    console.log(serialData);
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
    $.ajax({
      type: "POST",
      url: "/srmsng/public/index.php/api/admin/addlocation",
      data: $("#add-location-form").serialize(),
      success: data => {
        //console.log(data);
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
