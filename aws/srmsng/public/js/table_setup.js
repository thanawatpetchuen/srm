// Set up the fixed columns for each table

function setUpFixed(id) {
  // If id is provided, it sets up the table with the specified id
  if (id) {
    $("#" + id + "_wrapper .table-wrapper").addClass("fixed-right");
  } else {
    $(".table-wrapper").addClass("fixed-right");
  }

  // Prefix for id
  var prefix = "";
  if (id) {
    prefix = "#" + id;
  }
  // Calculate the width for the fixed columns
  var fixedWidth = 0;
  $(prefix + " td.fixed-col").each(function() {
    var curFixedWidth = $(this).outerWidth();
    if (curFixedWidth > fixedWidth) {
      fixedWidth = curFixedWidth;
    }
  });
  if (fixedWidth < 100 && fixedWidth != 0) {
    fixedWidth = 100;
  }

  // If the width is not 0, set the fixed columns to that width
  if (fixedWidth != 0) {
    var fixedHeight = $(prefix + " td.fixed-col").outerHeight();
    var fixedHeightHeader = fixedHeight - 20;

    $(prefix + " tr").css("height", fixedHeight + "px");

    $(prefix + " td.fixed-col").css("width", fixedWidth + "px");
    $(prefix + " th.fixed-col").css("width", fixedWidth + "px");
    if (id) {
      $(prefix + "_wrapper" + " .fixed-right").css("marginRight", fixedWidth + "px");
    } else {
      // Margin for the table
      $(".fixed-right").css("marginRight", fixedWidth + "px");
    }
    $(prefix + " th.fixed-col").css("lineHeight", fixedHeightHeader + "px");
  }
}
