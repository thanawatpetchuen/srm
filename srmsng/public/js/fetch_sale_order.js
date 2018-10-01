// Fetch Asset for admin
// at /asset

function fetchData() {
    $("#supertable").DataTable({
      stateSave: true,
      dom: '<lf<"table-wrapper"t>ip>',
      processing: true,
      serverSide: true,
      ajax: $.fn.dataTable.pipeline({
        url: "/srmsng/public/fetch-ajax/fetchSaleOrder.php",
        pages: 5 // number of pages to cache,
      }),
      columnDefs: [
        {
          // Edit button
          targets: -1,
          data: 0,
          className: "fixed-col",
          searchable: false,
          render: function(data) {
            return '<a class="btn btn-primary" href="edit_asset?sng_code=' + data + '">Edit</a>';
          }
        },
        {
          // Link to view scheduled work of the asset
          targets: 0,
          data: 0,
          render: function(data) {
            return '<a href="work?sng_code=' + data + '" target="_blank">' + data + "</a>";
          }
        },
        
      ],
      initComplete: function() {
        // Set up table styling
        $("#supertable").addClass("display");
        setUpFixed();
      },
      drawCallback: function() {
        // Set up table styling
        setUpFixed();
        //bindCollapse();
      }
    });
  }
  
  $(document).ready(function() {
    fetchData();
  });
  
  // Show the dropdown
  function showCollapse() {
    var collapse = this.parentElement.getElementsByClassName("asset-collapse")[0];
    if (collapse.classList.contains("show")) {
      collapse.classList.remove("show");
      this.classList.remove("show");
    } else {
      collapse.classList.add("show");
      this.classList.add("show");
    }
  }
  
  // Create the dropdown from the data in the row
  function makeCollapse(keys, values) {
    var collapse = document.createElement("div");
    collapse.setAttribute("class", "asset-collapse border rounded");
  
    for (var i = 0; i < keys.length; i++) {
      var item = document.createElement("p");
      item.setAttribute("class", "asset-collapse-item");
  
      var header = document.createElement("span");
      header.setAttribute("class", "asset-collapse-item-header");
      header.appendChild(document.createTextNode(keys[i] + ": "));
  
      item.appendChild(header);
      item.appendChild(document.createTextNode(values[i]));
  
      collapse.appendChild(item);
    }
    return collapse;
  }
  
  // Create the button in order to show the dropdown when clicked
  function makeDropArrow() {
    var wrapper = document.createElement("div");
    wrapper.setAttribute("class", "drop-arrow");
  
    var icon = document.createElement("i");
    icon.setAttribute("class", "fa fa-chevron-circle-down");
  
    wrapper.appendChild(icon);
    wrapper.setAttribute("onClick", "showCollapse.call(this)");
  
    return wrapper;
  }
  