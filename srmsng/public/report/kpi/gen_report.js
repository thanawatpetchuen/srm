////////////////////////////////////////////////////
//                     PDF                        //
////////////////////////////////////////////////////

function genReportPDF(form_data) {
  // Get data from form_data (serializeArray)
  if (form_data[2].value === "by-fse") {
    var name = form_data[3]["value"].split("_")[1];
    // var code = form_data[3]["value"].split("_")[0];
  } else if (form_data[2].value === "by-customer") {
    var name = form_data[4]["value"];
    // var code = form_data[5]["value"];
  } else {
    var name = "";
  }

  var month = form_data[0]["value"];
  var year = form_data[1]["value"];

  // Convert month number to month name
  var monthName = "";
  const monthNames = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December"
  ];
  if (month != 0) {
    monthName = monthNames[month - 1];
  } else {
    monthName = "Year";
  }

  // Title and file name for the PDF file
  if (name != "") {
    var title = name + ": KPI " + monthName + " " + year;
    var filename = name + "_KPI_" + monthName + "_" + year;
  } else {
    var title = "KPI Overview " + monthName + " " + year;
    var filename = "KPI_Overview_" + monthName + "_" + year;
  }

  // ========= Customer Complain Response ========== //
  var row1 = [
    {
      text: "1. ความรวดเร็วในการตอบสนองเพื่อแก้ไขปัญหาเบื้องต้นให้กับลูกค้า (Customer Complain Reponse)",
      style: "tableHeader"
    },
    "เดือน",
    "100%",
    "99",
    "",
    ""
  ];
  // ========= First Time Fix Rate ========== //
  var row2 = [
    {
      text: "2. เข้าปัฏิบัติการแก้ไขให้จบในครั้งเดียว จากบันทึกใน Hotline (First Time Fix Rate)",
      style: "tableHeader",
      rowSpan: 2
    },
    {
      text: "ไตรมาส",
      rowSpan: 2
    },
    {
      text: "95%",
      rowSpan: 2
    },
    {
      text: "24",
      rowSpan: 2
    },
    {
      text: "จำนวนที่จบเพียงครั้งเดียว",
      style: "tableHeader"
    },
    {
      text: "จำนวนที่ไม่จบเพียงครั้งเดียว",
      style: "tableHeader"
    }
  ];
  var row2content = [{}, {}, {}, "24", "23", "1"];
  // ========= Service and Project Implement on Time ========== //
  var row3 = [
    {
      text: "3. การดำเนินโครงการตามระยะเวลา (Service and Project Implement on Time)",
      style: "tableHeader",
      rowSpan: 2
    },
    {
      text: "ไตรมาส",
      rowSpan: 2
    },
    {
      text: "100%",
      rowSpan: 2
    },
    {
      text: "50",
      rowSpan: 2
    },
    {
      text: "ตามระยะเวลา",
      style: "tableHeader"
    },
    {
      text: "ไม่เป็นไปตามระยะเวลา",
      style: "tableHeader"
    }
  ];
  var row3content = [{}, {}, {}, {}, "50", "0"];
  // ========= Waste from Project Implementation ========== //
  var row4 = [
    {
      text: "4. ความเสียหายที่เกิดจากการปฏิบัติงาน (Waste from Project Implementation)",
      style: "tableHeader",
      rowSpan: 2
    },
    {
      text: "ไตรมาส",
      rowSpan: 2
    },
    {
      text: "97%",
      rowSpan: 2
    },
    {
      text: "50",
      rowSpan: 2
    },
    {
      text: "ไม่เกิดการเสียหาย",
      style: "tableHeader"
    },
    {
      text: "เกิดการเสียหาย",
      style: "tableHeader"
    }
  ];
  var row4content = [{}, {}, {}, {}, "50", "0"];
  // ========= Accident from working, take leave at least 1 day ========== //
  var row5 = [
    {
      text:
        "5. ไม่เกิดอุบัติเหตุที่เกิดขึ้นจากการปฏิบัติงาน จนถึงขั้นหยุดงานตั้งแต่ 1 วัน (Accident from working, take leave at least 1 day)",
      style: "tableHeader",
      rowSpan: 2
    },
    {
      text: "เดือน",
      rowSpan: 2
    },
    {
      text: "0%",
      rowSpan: 2
    },
    {
      text: "19",
      rowSpan: 2
    },
    {
      text: "ไม่เกิดอุบัติเหตุ",
      style: "tableHeader"
    },
    {
      text: "เกิดอุบัติเหตุ",
      style: "tableHeader"
    }
  ];
  var row5content = [{}, {}, {}, {}, "19", "0"];

  var tableData = [row1, row2, row2content, row3, row3content, row4, row4content, row5, row5content];

  createPDF(filename, title, tableData);
}

function createPDF(filename, title, rows) {
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

  // Table Columns
  var columnNames = [
    [
      { text: "หัวข้อในการวัด KPIs", style: "tableHeader", rowSpan: 2 },
      { text: "ความถี่", style: "tableHeader", rowSpan: 2 },
      { text: "เป้าหมาย", style: "tableHeader", rowSpan: 2 },
      { text: "จำนวนทั้งสิ้นที่วัด", style: "tableHeader", rowSpan: 2 },
      { text: "จำนวนที่เปิด", style: "tableHeader", colSpan: 2 },
      {}
    ],
    [
      {},
      {},
      {},
      {},
      { text: "ระยะเวลาไม่เกิน 1 วัน", style: "tableHeader" },
      { text: "ระยะเวลาเกิน 1 วัน", style: "tableHeader" }
    ]
  ];

  var widths = ["*", "auto", "auto", "auto", "auto", "auto"];

  // Table Data
  var data = columnNames;

  // pdfmake recieves data as an array of arrays
  // The first array is the data for the column names (table header). The others are the
  // data for each row
  data = data.concat(rows);

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
    styles: {
      // Style for text in table header
      tableHeader: {
        bold: true,
        fontSize: 13,
        color: "black"
      }
    },
    content: [
      // Title
      { text: title, alignment: "center", fontSize: 15, bold: "true" },

      // Table
      {
        table: {
          headerRows: 2,
          widths: widths,
          body: data
        },
        layout: {
          // Color for the table header
          fillColor: function(i) {
            return i < 2 ? "#CCCCCC" : null;
          }
        }
      }
    ]
  };
  // Download the PDF
  pdfMake.createPdf(docDefinition).download(filename + ".pdf");
}
