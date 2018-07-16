function setUpFixed() {
  $(".table-wrapper").addClass("fixed-right");
  // fixed-columns
  var fixedWidth = $("td.fixed-col").outerWidth();
  if (fixedWidth < 100) {
    fixedWidth = 100;
  }

  var fixedHeight = $("td.fixed-col").outerHeight();
  var fixedHeightHeader = fixedHeight - 20;

  $("tr").css("height", fixedHeight + "px");

  $("td.fixed-col").css("width", fixedWidth + "px");
  $("th.fixed-col").css("width", fixedWidth + "px");
  $(".fixed-right").css("marginRight", fixedWidth + "px");
  $(".fixed-left").css("marginLeft", fixedWidth + "px");

  $("th.fixed-col").css("lineHeight", fixedHeightHeader + "px");
}
