<? 
		
		function googleMailer(){
			//echo "1. process send mail !!";
			if(!empty($GLOBALS['phpMailer']) 
				&& $GLOBALS['_google_mailto'] && !empty($GLOBALS['_google_subject'])  && !empty($GLOBALS['_google_desc'])){
					$GLOBALS['phpMailer']->IsHTML(true); // กำหนดให้ ส่งเป็น html
					$GLOBALS['phpMailer']->IsSMTP();
					$GLOBALS['phpMailer']->SMTPAuth   = true;                  // enable SMTP authentication
					$GLOBALS['phpMailer']->CharSet = "tis-620"; //กำหนด charset ของภาษาในเมล์ให้ถูกต้อง เช่น tis-620 หรือ utf-8
					// $GLOBALS['phpMailer']->SMTPSecure = "ssl";                 // sets the prefix to the servier
					// $GLOBALS['phpMailer']->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
					// $GLOBALS['phpMailer']->Port       = 465;                   // set the SMTP port for the GMAIL server
					$GLOBALS['phpMailer']->Host = 'ssl://smtp.gmail.com:465';  // รวมเป็น ตัวแปลเดียวแบบนี้ก็ได้
					$GLOBALS['phpMailer']->Username   = "noreply@tossakan.co.th";  // GMAIL username
					$GLOBALS['phpMailer']->Password   = "12qwaszx";            // GMAIL password
					$GLOBALS['phpMailer']->From       = "ragkarns@hotmail.com"; // "name@yourdomain.com";
					$GLOBALS['phpMailer']->FromName    = "noreply tossakan professional";
					$GLOBALS['phpMailer']->Subject    = $GLOBALS['_google_subject'];
					$GLOBALS['phpMailer']->Body = $GLOBALS['_google_desc'];
					$GLOBALS['phpMailer']->AddAddress($GLOBALS['_google_mailto']);  // ใส่ email ผู้รับอย่างเดียวก็ได้
					$GLOBALS['phpMailer']->Send(); // ส่งเมลออก
					//echo "2. process send mail !!";
			}  
		}  
		
?>
