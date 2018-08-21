function genReportPDF(form_data) {
    // form_data: SerializeArray() of the form. Comes in this order:
    // form_data[0] = month
    // form_data[1] = year
    // form_data[2] = filter (by-fse, by-customer, overview)
    // form_data[3] = FSE name and code (ex. 119_Chamin Inthidet)
    // form_data[4] = Customer Name
    // form_data[5] = Customer Code
  
    var content = [];
  
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
  
    // Insert Title to File
    content.push({
      text: "KPIs - Service and Project",
      fontSize: 23,
      bold: "true"
    });
    content.push({
      text: "For " + monthName + " " + year,
      fontSize: 17,
      bold: "true"
    });
  
    // Table Rows
    // For the first row, put all the data in row1
    // For the the second row, put the total in row2, and achieved, unachieved work in row2content
    // For the other rows, do the same as in the second row
  
    // ========= Customer Complain Response ========== //
    var row1 = [
      {
        text: "1. ความรวดเร็วในการตอบสนองเพื่อแก้ไขปัญหาเบื้องต้นให้กับลูกค้า (Customer Complain Reponse)",
        style: "tableHeader"
      },
      "เดือน",
      "100%",
      "99",
      "99",
      "0"
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
        // TOTAL HERE //
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
    // ACHIEVED WORK AND UNACHIEVED WORK HERE //
    var row2content = [{}, {}, {}, {}, "23", "1"];
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
        // TOTAL HERE //
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
    // ACHIEVED WORK AND UNACHIEVED WORK HERE //
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
        // TOTAL HERE //
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
    // ACHIEVED WORK AND UNACHIEVED WORK HERE //
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
        // TOTAL HERE //
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
    // ACHIEVED WORK AND UNACHIEVED WORK HERE //
    var row5content = [{}, {}, {}, {}, "19", "0"];
  
    // Construct Table
    // Table Data
    var tableData = [row1, row2, row2content, row3, row3content, row4, row4content, row5, row5content];
  
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
  
    var data = columnNames;
  
    // pdfmake recieves data as an array of arrays
    // The first array is the data for the column names (table header). The others are the
    // data for each row
    data = data.concat(tableData);
  
    var widths = ["*", "auto", "auto", "auto", "auto", "auto"];
  
    content.push({
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
    });
  
    // Generate Charts for each KPI Objective
    // First Chart (Monthly)
    var achieved1 = [33, 43, 56, 33, 45, 43, 42, 47, 38, 45, 43, 44];
    var total1 = [34, 43, 56, 35, 47, 44, 43, 49, 39, 45, 46, 44];
    content = content.concat(
      genKPIGraph(
        1,
        false,
        achieved1,
        total1,
        100,
        "Customer Complain Response ≤ 1 Day",
        "Response on Time",
        "Total Customer Complaints",
        form_data
      )
    );
  
    // Second Chart (Quarterly)
    var achieved2 = [33, 43, 56, 33];
    var total2 = [34, 43, 56, 35];
    content = content.concat(
      genKPIGraph(
        2,
        true,
        achieved2,
        total2,
        95,
        "First Time Fix Rate",
        "เข้าปฏิบัติงานให้จบในครั้งเดียว",
        "จำนวนการเสียทั้งสิ้น",
        form_data
      )
    );
  
    // Create PDF File and Download
    createPDF(filename, title, content);
  }
  
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
      styles: {
        // Style for text in table header
        tableHeader: {
          bold: true,
          fontSize: 13,
          color: "black"
        }
      },
      content: content
    };
    // Download the PDF
    pdfMake.createPdf(docDefinition).download(filename + ".pdf");
  }
  