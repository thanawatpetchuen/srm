function setUpFixed(id) {
  if (id) {
    $("#" + id + "_wrapper .table-wrapper").addClass("fixed-right");
  } else {
    $(".table-wrapper").addClass("fixed-right");
  }

  var prefix = "";
  if (id) {
    prefix = "#" + id;
  }
  // fixed-columns
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
  if (fixedWidth != 0) {
    var fixedHeight = $(prefix + " td.fixed-col").outerHeight();
    var fixedHeightHeader = fixedHeight - 20;

    $(prefix + " tr").css("height", fixedHeight + "px");

    $(prefix + " td.fixed-col").css("width", fixedWidth + "px");
    $(prefix + " th.fixed-col").css("width", fixedWidth + "px");
    if (id) {
      $(prefix + "_wrapper" + " .fixed-right").css("marginRight", fixedWidth + "px");
    } else {
      $(".fixed-right").css("marginRight", fixedWidth + "px");
    }
    $(prefix + " th.fixed-col").css("lineHeight", fixedHeightHeader + "px");
  }
}
