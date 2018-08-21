function getCurrentMonth() {
  // Get current month
  var d = new Date();
  var m = d.getMonth();
  $('select[name="month"]').val(m + 1);
}

function initFSE() {
  // FSE
  fetch("/srmsng/public/index.php/api/admin/getfse")
    .then(resp => {
      return resp.json();
    })
    .then(data_json => {
      data_json.forEach(element => {
        if (element["fse_code"] != 0) {
          var option = document.createElement("option");
          option.setAttribute("value", element["fse_code"] + "_" + element["engname"]);
          option.innerHTML = element["engname"];
          document.getElementById("fse-code-dropdown").appendChild(option);
        }
      });
    });
}

function initCustomerSearch() {
  // Search Customer
  $("#customer-search-field").on("keydown", function() {
    // Press enter to search
    if (event.keyCode == 13) {
      $("#customer-search").trigger("click");
    }
    // Clear data when the user starts typing
    $("#customer-choose .sub-field, #customer-code-search-field").val("");
    document.getElementById("customer-dropdown").innerHTML = "";
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
}
