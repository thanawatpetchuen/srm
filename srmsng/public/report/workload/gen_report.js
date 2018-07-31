////////////////////////////////////////////////////
//                     PDF                        //
////////////////////////////////////////////////////

function genReportPDF(form_data) {
  // Get data from form_data (serializeArray)
  var fse_code = form_data[0]["value"].split("_")[0];
  var month = form_data[1]["value"];
  var year = form_data[2]["value"];

  // Create array to hold data of all rows
  var tableData = [];

  // Get data
  fetch("/srmsng/public/index.php/api/admin/workload?fse_code=" + fse_code + "&month=" + month + "&year=" + year)
    .then(resp => {
      return resp.json();
    })
    .then(data_json => {
      data_json.forEach(element => {
        // Put data in each row into an array
        var tableRow = [];
        for (var key in element) {
          tableRow.push(element[key]);
        }
        // Add row to tableData
        tableData.push(tableRow);
      });
    })
    .then(() => {
      // Create PDF file using pdfmake
      createPDF(form_data, tableData);
    });
}
function createPDF(form_data, rows) {
  var fse_name = form_data[0]["value"].split("_")[1];
  var month = form_data[1]["value"];
  var year = form_data[2]["value"];

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
  monthName = monthNames[month - 1];

  // Title and file name for the PDF file
  var title = fse_name + ": Workload " + monthName + " " + year;
  var filename = fse_name + "_Workload_" + monthName + "_" + year;

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
    { text: "Name", style: "tableHeader" },
    { text: "DD-MM-YY", style: "tableHeader" },
    { text: "Job No.", style: "tableHeader" },
    { text: "Store", style: "tableHeader" },
    { text: "Customer Name", style: "tableHeader" },
    { text: "Site Name", style: "tableHeader" },
    { text: "Work Class", style: "tableHeader" },
    { text: "WH Time: 8:30-", style: "tableHeader" },
    { text: "OT 17:30-", style: "tableHeader" },
    { text: "OT 24:00-", style: "tableHeader" },
    { text: "Travel", style: "tableHeader" },
    { text: "Working", style: "tableHeader" },
    { text: "Total Hr", style: "tableHeader" },
    { text: "การทำงานซ้ำ", style: "tableHeader" },
    { text: "Job Status", style: "tableHeader" },
    { text: "Remarks", style: "tableHeader" }
  ];
  // Widths of the columns
  // * = the width of the column is widest as it can be
  var widths = [
    "auto",
    "auto",
    "auto",
    "auto",
    "auto",
    "*",
    "auto",
    "auto",
    "auto",
    "auto",
    "auto",
    "auto",
    "auto",
    "auto",
    "auto",
    "auto"
  ];

  // Table Data
  var data = [columnNames];

  // pdfmake recieves data as an array of arrays
  // The first array is the data for the column names (table header). The others are the
  // data for each row
  data = data.concat(rows);

  // Document Setup
  var docDefinition = {
    info: {
      title: title
    },
    pageSize: "A3",
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
          headerRows: 1,
          widths: widths,
          body: data
        },
        layout: {
          // Color for the table header
          fillColor: function(i) {
            return i === 0 ? "#CCCCCC" : null;
          }
        }
      }
    ]
  };
  // Download the PDF
  pdfMake.createPdf(docDefinition).download(filename + ".pdf");
}

////////////////////////////////////////////////////
//                     CSV                        //
////////////////////////////////////////////////////
function genReportCSV(form_data) {
  // Get data from form_data (serializeArray)
  var fse_code = form_data[0]["value"].split("_")[0];
  var fse_name = form_data[0]["value"].split("_")[1];

  var month = form_data[1]["value"];
  var year = form_data[2]["value"];

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
  monthName = monthNames[month - 1];

  // File Name
  var filename = fse_name + "_Workload_" + monthName + "_" + year;

  // Create string to hold data of all rows
  var tableData = "";

  // Table Columns
  var columnNames = [
    "Name",
    "DD-MM-YY",
    "Job No.",
    "Store",
    "Customer Name",
    "Site Name",
    "Work Class",
    "WH Time: 8:30-",
    "OT 17:30-",
    "OT 24:00-",
    "Travel",
    "Working",
    "Total Hr",
    "การทำงานซ้ำ",
    "Job Status",
    "Remarks"
  ];
  // Put data string into tableData
  for (var i in columnNames) {
    tableData += columnNames[i] + ",";
  }
  tableData = tableData.slice(0, -1);
  tableData += "\n";

  // Get data
  fetch("/srmsng/public/index.php/api/admin/workload?fse_code=" + fse_code + "&month=" + month + "&year=" + year)
    .then(resp => {
      return resp.json();
    })
    .then(data_json => {
      data_json.forEach(element => {
        // Put data in each row into a string
        var tableRow = "";
        for (var key in element) {
          tableRow += element[key] + ",";
        }
        tableRow = tableRow.slice(0, -1);
        tableRow += "\n";
        // Add row to tableData
        tableData += tableRow;
      });
    })
    .then(() => {
      // Encode tableData and download file as .csv
      var uri = "data:text/plain;charset=utf-8,\uFEFF" + encodeURI(tableData);

      // Create an invisible link on the page
      var element = document.createElement("a");
      element.setAttribute("href", uri);
      element.setAttribute("download", filename + ".csv");

      element.style.display = "none";
      document.body.appendChild(element);

      // Trigger link click to download the file
      element.click();

      // Remove the link from the page
      document.body.removeChild(element);
    });
}
