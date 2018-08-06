// Month Names
const abbrMonthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

function genKPIGraph(order, isquarter, achieved, total, target, title, objective, totalName, form_data) {
  // PARAMETERS
  // order: (int) Number 1-5, tells the order of the report. This number will be visible on the top left
  // of the page
  //
  // isquarter: (bool) Tells if the report should be by month(false) or by quarter(true)
  //
  // achieved: (array) The amount of work that the objective is completed. A number in array represents
  // number of achieved work in each month or quarter.
  // ex. if !isquarter: [3,null,4,5,...] means there are 3 achieved work in January and there is
  // no data available for February, so on...
  // if isquarter: [10,33,21,33] means there are 10 achieved work in the first quarter, so on...
  //
  // total: (array) The amount of total work in each month or quarter.
  //
  // target: (int) The target percentage of the objective
  //
  // title: (string) The title of the report
  //
  // objective: (string) The objective in which its completeness is being measured
  //
  // totalName: (string) ex. Total Customer Complaints, Total Project Management with No Delay, etc.
  //
  // form_data: (array) SerializeArray() of the form. Comes in this order:
  // form_data[0] = month
  // form_data[1] = year
  // form_data[2] = filter (by-fse, by-customer, overview)
  // form_data[3] = FSE name and code (ex. 119_Chamin Inthidet)
  // form_data[4] = Customer Name
  // form_data[5] = Customer Code

  var graphReportContent = [];

  // Calculate Percentage and Total
  var percentAchieved = [];
  var totalAchieved = 0;
  var totalWork = 0;
  for (var i in achieved) {
    if (achieved[i]) {
      totalAchieved += achieved[i];
      percentAchieved.push(Math.floor((achieved[i] / total[i]) * 100));
    } else {
      percentAchieved.push(null);
    }
  }
  for (var i in total) {
    if (total[i]) {
      totalWork += total[i];
    }
  }
  var totalPercent = Math.floor((totalAchieved / totalWork) * 100);

  ////////////////////////////////////////////
  //                 TITLE                  //
  ////////////////////////////////////////////

  // Title
  graphReportContent.push({
    text: "Statistical Report of Objective/KPI",
    alignment: "center",
    fontSize: 12,
    bold: "true",
    absolutePosition: { x: 0, y: 30 },
    pageBreak: "before"
  });
  graphReportContent.push({
    text: title,
    alignment: "center",
    fontSize: 15,
    bold: "true",
    absolutePosition: { x: 0, y: 45 }
  });

  // Info
  // isQuarter
  if (isquarter) {
    var freq = "Quarterly";
  } else {
    var freq = "Monthly";
  }

  // Month/Year
  if (form_data[0].value != 0) {
    var monthyear = form_data[0].value + " " + form_data[1].value;
  } else {
    var monthyear = form_data[1].value;
  }
  graphReportContent.push({
    text: "Department: Service and Project " + order + "/5",
    fontSize: 12,
    bold: "true",
    absolutePosition: { x: 20, y: 20 }
  });
  graphReportContent.push({
    text: "Frequency: " + freq,
    fontSize: 12,
    bold: "true",
    absolutePosition: { x: 20, y: 30 }
  });
  graphReportContent.push({
    text: "Month/Year: " + monthyear,
    fontSize: 12,
    bold: "true",
    absolutePosition: { x: 20, y: 40 }
  });

  ////////////////////////////////////////////
  //                ANALYSIS                //
  ////////////////////////////////////////////
  // Info
  graphReportContent.push({
    text: "Data Analysis (การวิเคราะห์ข้อมูล)",
    fontSize: 12,
    bold: "true",
    absolutePosition: { x: 600, y: 70 }
  });
  graphReportContent.push({
    text: "In case target is not achieved (กรณีที่ไม่บรรลุเป้าหมาย)",
    fontSize: 12,
    bold: "true",
    absolutePosition: { x: 600, y: 160 }
  });
  graphReportContent.push({
    text: "Investigation Cause (สาเหตุ)",
    fontSize: 12,
    absolutePosition: { x: 600, y: 170 }
  });
  graphReportContent.push({
    text: "Summary of Action Taken (แนวทางการป้องกันและแก้ไข)",
    fontSize: 12,
    absolutePosition: { x: 600, y: 260 }
  });
  graphReportContent.push({
    text: "Remarks",
    fontSize: 12,
    absolutePosition: { x: 600, y: 350 }
  });

  // Approved by
  graphReportContent.push({
    text: "Approved by",
    fontSize: 12,
    bold: "true",
    absolutePosition: { x: 600, y: 400 }
  });
  graphReportContent.push({
    text: "...........................",
    fontSize: 12,
    absolutePosition: { x: 600, y: 425 }
  });
  graphReportContent.push({
    text: "..../ .... / ....",
    fontSize: 12,
    absolutePosition: { x: 600, y: 440 }
  });

  // Issued by
  graphReportContent.push({
    text: "Issued by",
    fontSize: 12,
    bold: "true",
    absolutePosition: { x: 700, y: 400 }
  });
  graphReportContent.push({
    text: "...........................",
    fontSize: 12,
    absolutePosition: { x: 700, y: 425 }
  });
  graphReportContent.push({
    text: ".... / .... / ....",
    fontSize: 12,
    absolutePosition: { x: 700, y: 440 }
  });

  ////////////////////////////////////////////
  //                 CHART                  //
  ////////////////////////////////////////////

  // Canvas
  canvas = [];

  // Specify Canvas Size
  var size = {
    x: 450,
    y: 150
  };
  // Specify Canvas Origin (The coord of the top left corner)
  var origin = {
    x: 20,
    y: 40
  };
  // Max scale on y axis (starting from 0)
  var maxScale = 120;
  // Bar Width
  var barWidth = 15;
  // Chart Margin (space on the top and left for labels)
  var margins = {
    x: 30,
    y: 40
  };
  // Label Size
  var labelSize = 12;

  // The leftmost and bottommost coords
  var endCoord = {
    x: origin.x + size.x + margins.x,
    y: origin.y + size.y + margins.y
  };

  for (var y = 0, i = 0; y <= size.y; y = y + Math.floor(size.y / 6), i = i + 1) {
    // Guides
    canvas.push({
      // SVG path
      type: "path",
      lineWidth: 0.5,
      d: "M " + (origin.x + margins.x) + "," + (endCoord.y - y) + " L " + endCoord.x + "," + (endCoord.y - y),
      lineColor: "#aaa"
    });
    // Labels on the y axis
    graphReportContent.push({
      text: (120 / 6) * i + "%",
      absolutePosition: { x: origin.x, y: endCoord.y - y - labelSize },
      bold: true,
      fontSize: labelSize
    });
  }

  // Bars

  // Number of bars
  if (isquarter) {
    var noOfBars = 4;
  } else {
    var noOfBars = 12;
  }
  for (var i in percentAchieved) {
    var barOriginX = origin.x + (size.x / noOfBars) * i + margins.x;
    var barHeight = (percentAchieved[i] * size.y) / maxScale;
    if (percentAchieved[i]) {
      canvas.push({
        // SVG Rectangle
        type: "rect",
        x: barOriginX,
        // Workaround: the page margins (15) has to be removed
        y: endCoord.y - barHeight - 15,
        w: barWidth,
        h: barHeight,
        color: "#5070bb"
      });
    }
    if (!isquarter) {
      // Month labels
      graphReportContent.push({
        text: abbrMonthNames[i],
        absolutePosition: { x: barOriginX + barWidth, y: endCoord.y + 10 },
        bold: true,
        fontSize: labelSize
      });
    } else {
      // Quarter labels
      graphReportContent.push({
        text: "Quarter " + (parseInt(i) + 1),
        absolutePosition: { x: barOriginX + barWidth, y: endCoord.y + 10 },
        bold: true,
        fontSize: labelSize
      });
    }
  }

  // Target line
  canvas.push({
    // SVG Path
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
  graphReportContent.push({
    text: "Target: " + target + "%",
    absolutePosition: { x: endCoord.x + 10, y: endCoord.y - (target * size.y) / maxScale - labelSize },
    bold: true,
    fontSize: labelSize
  });

  // Add Chart to Page
  graphReportContent.push({
    canvas: canvas
  });

  ////////////////////////////////////////////
  //                 TABLE                  //
  ////////////////////////////////////////////
  var targetRow = [];
  for (var i = 0; i <= noOfBars; i++) {
    targetRow.push(target + "%");
  }
  if (!isquarter) {
    // Table for monthly
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
            {},
            { text: "YTD", rowSpan: 2 }
          ],
          [{}, ...abbrMonthNames, {}],
          [objective, ...achieved, totalAchieved],
          [totalName, ...total, totalWork],
          ["%Achievement", ...percentAchieved, totalPercent],
          ["Target", ...targetRow]
        ],
        headerRows: 2
      },
      layout: {
        // Color for the table header
        fillColor: function(i) {
          return i < 2 ? "#CCCCCC" : null;
        }
      }
    };
    console.log(table);
  } else {
    // Table for quarterly
    table = {
      table: {
        body: [
          [
            { text: "Objective Result" },
            { text: "Quarter 1" },
            { text: "Quarter 2" },
            { text: "Quarter 3" },
            { text: "Quarter 4" },
            { text: "YTD" }
          ],
          [objective, ...achieved, totalAchieved],
          [totalName, ...total, totalWork],
          ["%Achievement", ...percentAchieved, totalPercent],
          ["Target", ...targetRow]
        ],
        headerRows: 1
      },
      layout: {
        // Color for the table header
        fillColor: function(i) {
          return i === 0 ? "#CCCCCC" : null;
        }
      }
    };
  }
  // Table Margin
  graphReportContent.push({
    text: " ",
    marginTop: 30
  });
  // Add Table to Page
  graphReportContent.push(table);

  return graphReportContent;
}
