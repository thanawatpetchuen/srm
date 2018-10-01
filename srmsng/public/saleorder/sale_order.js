function genReportPDF() {
    // form_data: SerializeArray() of the form. Comes in this order:
    // form_data[0] = month
    // form_data[1] = year
    // form_data[2] = filter (by-fse, by-customer, overview)
    // form_data[3] = FSE name and code (ex. 119_Chamin Inthidet)
    // form_data[4] = Customer Name
    // form_data[5] = Customer Code
  
    var content = [
      {text: 'บริษัท ซินเนอร์ไจซ์ โปรไวด์ เซอร์วิส จำกัด', style: 'brandHeader', margin: [25, 0, 0, 0]},
      {text: '31/14 หมู่ 10 ตำบลลาดสวาย อำเภอลำลูกกา จังหวัดปทุมธานี 12150', style:"brandText", margin: [25, 0, 0, 0]},
      {text: '02-157-1325 FAX: 02-157-1328 www.synergizeth.com', style:"brandText", margin: [25, 0, 0, 0]},
      {text: 'เลขประจำตัวผู้เสียภาษี(Tax ID)   0135551007447   สำนักงานใหญ่', style:"brandText", margin: [25, 0, 0, 0]},

      
    ];
    let filename = "test";
    let title = "title+test";
   
  
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
      pageOrientation: "portrait",
      pageMargins: [15, 15],
      defaultStyle: {
        font: "THSarabunNew"
      },
      styles: {
        // Style for text in table header
        brandHeader: {
          bold: true,
          fontSize: 20,
          color: "black"
        },
        brandText: {
          fontSize: 16,
        }
        
      },
      content: content
    };
    // Download the PDF
    pdfMake.createPdf(docDefinition).open()
    // pdfMake.createPdf(docDefinition).download(filename + ".pdf");
  }
  