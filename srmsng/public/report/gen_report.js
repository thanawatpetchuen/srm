function genReport(form_data) {
  var title = form_data[0]["value"] + ": Workload " + form_data[1]["value"] + " to " + form_data[2]["value"];

  // Fonts
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
    { text: "Job Description", style: "tableHeader" },
    { text: "WH Time: 8:30-", style: "tableHeader" },
    { text: "OT 17:30-", style: "tableHeader" },
    { text: "OT 24:00-", style: "tableHeader" },
    { text: "Travel", style: "tableHeader" },
    { text: "Working", style: "tableHeader" },
    { text: "Total Hr", style: "tableHeader" },
    { text: "การทำงานซ้ำ", style: "tableHeader" },
    { text: "หมายเหตุการทำงานซ้ำ", style: "tableHeader" },
    { text: "Hotline", style: "tableHeader" },
    { text: "Remarks", style: "tableHeader" }
  ];
  var widths = [
    "auto",
    "auto",
    "auto",
    "auto",
    "*",
    "*",
    "*",
    "auto",
    "auto",
    "auto",
    "auto",
    "auto",
    "auto",
    "auto",
    "*",
    "auto",
    "auto"
  ];

  // Table Data
  var values = [
    "KS",
    "03-08-17",
    "KSO170801",
    "1107",
    "Lotus Express",
    "สุทธิสาร",
    "Repairing",
    "x",
    "",
    "x",
    "2:00",
    "3:00",
    "5:00",
    "",
    "",
    "",
    ""
  ];

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
      tableHeader: {
        bold: true,
        fontSize: 13,
        color: "black"
      }
    },
    content: [
      { text: title, alignment: "center", fontSize: 15, bold: "true" },
      {
        table: {
          headerRows: 1,
          widths: widths,
          body: [columnNames, values]
        },
        layout: {
          // Colors
          fillColor: function(i, node) {
            return i === 0 ? "#CCCCCC" : null;
          }
        }
      }
    ]
  };
  // Open PDF in browser
  pdfMake.createPdf(docDefinition).open();
}
