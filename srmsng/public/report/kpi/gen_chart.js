// Month Names
const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

// Content
content = [];

// Put in Data Here
var achieved = [32, 43, 22, 33, 11, 32, 45, 23, 55, 12, 44, 23];
var total = [33, 43, 22, 34, 12, 32, 47, 23, 58, 15, 44, 27];

// Target
var target = 100;

// Config
var title = "Customer Complaint Response ≤ 1 Day";
var objective = "Response on Time";
var totalName = "Total Customer Complaints";

var data = [];
for (var i in achieved) {
  data.push(Math.floor((achieved[i] / total[i]) * 100));
}

function genChart() {
  // Canvas
  canvas = [];

  // Specify Canvas Size
  var size = {
    x: 500,
    y: 150
  };
  // Specify Canvas Origin (The coord of the top left corner)
  var origin = {
    x: 15,
    y: 15
  };
  // Max scale on y axis (starting from 0)
  var maxScale = 120;
  // Bar Width
  var barWidth = 20;
  // Chart Margin (space on the top and left for labels)
  var margins = {
    x: 30,
    y: 40
  };
  // Label Size
  var labelSize = 12;

  var endCoord = {
    x: origin.x + size.x + margins.x,
    y: origin.y + size.y + margins.y
  };
  for (var y = 0, i = 0; y <= size.y; y = y + Math.floor(size.y / 6), i = i + 1) {
    // Guides
    canvas.push({
      type: "path",
      lineWidth: 0.5,
      d: "M " + (origin.x + margins.x) + "," + (endCoord.y - y) + " L " + endCoord.x + "," + (endCoord.y - y),
      lineColor: "#aaa"
    });
    // Labels
    content.push({
      text: (120 / 6) * i + "%",
      absolutePosition: { x: origin.x, y: endCoord.y - y - labelSize },
      bold: true,
      fontSize: labelSize
    });
  }

  // Bars
  for (var i in data) {
    var barOriginX = origin.x + (size.x / 12) * i + margins.x;
    var barHeight = (data[i] * size.y) / maxScale;
    canvas.push({
      type: "rect",
      x: barOriginX,
      y: size.y - barHeight + margins.y,
      w: barWidth,
      h: barHeight,
      color: "#5070bb"
    });

    // Month labels
    content.push({
      text: monthNames[i],
      absolutePosition: { x: barOriginX + barWidth, y: endCoord.y + 10 },
      bold: true,
      fontSize: labelSize
    });
  }

  // Target
  canvas.push({
    type: "path",
    d:
      "M " +
      (origin.x + margins.x) +
      "," +
      (endCoord.y - (target * size.y) / maxScale) +
      " L " +
      endCoord.x +
      "," +
      (endCoord.y - (target * size.y) / maxScale),
    lineWidth: 1.5,
    lineColor: "#00dd00"
  });
  content.push({
    text: "Target: " + target + "%",
    absolutePosition: { x: endCoord.x + 10, y: endCoord.y - (target * size.y) / maxScale - labelSize },
    bold: true,
    fontSize: labelSize
  });

  content.push({
    canvas: canvas
  });
}

function genTable() {
  genChart();
  // Specify Origin (The coord of the top left corner)
  var origin = {
    x: 15,
    y: 15
  };
  table = {
    table: {
      body: [
        [
          { text: "Objective Result", rowSpan: 2 },
          { text: "Quarter 1", colSpan: 3 },
          {},
          {},
          { text: "Quarter 2", colSpan: 3 },
          {},
          {},
          { text: "Quarter 3", colSpan: 3 },
          {},
          {},
          { text: "Quarter 4", colSpan: 3 },
          {},
          {}
        ],
        [{}, "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        [objective].concat(achieved),
        [totalName].concat(total),
        ["%Achievement"].concat(data)
      ],
      absolutePosition: { x: origin.x, y: origin.y },
      headerRows: 2
    },
    layout: {
      // Color for the table header
      fillColor: function(i) {
        return i < 2 ? "#CCCCCC" : null;
      }
    }
  };
  content.push(table);

  createPDF("hello", "hello", content);
}

// function genPDF() {
//   genTable();
//   createPDF("hello", "hello", content);
// }

function createPDF(filename, title, content) {
  // Thai Fonts
  pdfMake.fonts = {
    THSarabunNew: {
      normal: "THSarabunNew.ttf",
      bold: "THSarabunNew-Bold.ttf",
      italics: "THSarabunNew-Italic.ttf",
      bolditalics: "THSarabunNew-BoldItalic.ttf"
    },
    Roboto: {
      normal: "Roboto-Regular.ttf",
      bold: "Roboto-Medium.ttf",
      italics: "Roboto-Italic.ttf",
      bolditalics: "Roboto-MediumItalic.ttf"
    }
  };

  // Document Setup
  var docDefinition = {
    info: {
      title: title
    },
    pageSize: "A4",
    pageOrientation: "landscape",
    pageMargins: [15, 15],
    defaultStyle: {
      font: "THSarabunNew"
    },
    content: content
  };
  // Download the PDF
  pdfMake.createPdf(docDefinition).download(filename + ".pdf");
}
