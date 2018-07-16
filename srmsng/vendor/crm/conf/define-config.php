<? 
	
	// This config for windows
	if(!defined('__DOCUMENT_ROOT'))   define('__DOCUMENT_ROOT',  $_SERVER["DOCUMENT_ROOT"]);
	
	$__MYSQLDB['DB_USR'] = "synergize_serv";
	$__MYSQLDB['DB_PSW'] = "12qwaszx";
	$__MYSQLDB['DB_HOST'] = "localhost";
	$__MYSQLDB['DB_NAME'] = "synergize_serv";
	
	$__MYSQLDB['RESULT_CHARSET'] = "SET character_set_results=tis620";  
	$__MYSQLDB['CHARSET'] = "set names tis620";
	
	/*
	echo "DB_USR >>".$GLOBALS["__MYSQLDB"]["DB_USR"]."<br>";
	echo "DB_PSW >>".$GLOBALS["__MYSQLDB"]["DB_PSW"]."<br>";
	echo "DB_HOST >>".$GLOBALS["__MYSQLDB"]["DB_HOST"]."<br>";
	echo "DB_NAME >>".$GLOBALS["__MYSQLDB"]["DB_NAME"]."<br>";
	echo "CHARSET >>".$GLOBALS["__MYSQLDB"]["RESULT_CHARSET"]."<br>";
	echo "CHARSET >>".$GLOBALS["__MYSQLDB"]["CHARSET"]."<br>";
	*/
	
	$__DOMAIN_ROOT = "_domains";
	$__APPS_ROOT = "_apps";
	
	$__APP_VERSIONS["testdrive.com"] = "appv1.1.1";
	$__APP_VERSIONS["testdrive1.com"] = "appv1.0.1";
	$__APP_VERSIONS["test1.sme24hour.com"] = "appv1.1.1";
	
	$_LANGUAGE = "TH";
	
	/* กำหนดค่าของรูป thumbnail */
	$__THUMBNAIL_WIDTH = 120;
	$__THUMBNAIL_HEIGHT = 120;
	
	$__RECORDPERPAGE = 100;

	$orderStatusLabels["01"] = "รอตรวจสอบสินค้าพร้อมส่ง";
	
	$orderStatusLabels["02"] = "สินค้าพร้อมส่ง รอการชำระเงิน";
	$orderStatusLabels["03"] = "สินค้าหมด stock ยกเลิกใบสั่งซื้อ";
	
	$orderStatusLabels["04"] = "แจ้งชำระเงินค่าสินค้า";
	$orderStatusLabels["05"] = "รับชำระเงินค่าสินค้า";
	$orderStatusLabels["06"] = "ไม่ได้รับชำระเงินค่าสินค้า";
	
	$orderStatusLabels["07"] = "จัดส่งสินค้าแล้ว";
	$orderStatusLabels["08"] = "รับสินค้าแล้ว";
	
	$bankNameLists["01"] = "ธนาคารไทยพาณิชย์ เลขที่บัญชี 173-207-186-0";
	$bankNameLists["02"] = "ธนาคารกรุงเทพจำกัด เลขที่บัญชี 173-207-186-0";
	$bankNameLists["03"] = "ธนาคารกสิกรไทย เลขที่บัญชี 173-207-186-0";
		
		
?>