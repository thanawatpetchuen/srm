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
	
	/* ��˹���Ңͧ�ٻ thumbnail */
	$__THUMBNAIL_WIDTH = 120;
	$__THUMBNAIL_HEIGHT = 120;
	
	$__RECORDPERPAGE = 100;

	$orderStatusLabels["01"] = "�͵�Ǩ�ͺ�Թ��Ҿ������";
	
	$orderStatusLabels["02"] = "�Թ��Ҿ������ �͡�ê����Թ";
	$orderStatusLabels["03"] = "�Թ������ stock ¡��ԡ���觫���";
	
	$orderStatusLabels["04"] = "�駪����Թ����Թ���";
	$orderStatusLabels["05"] = "�Ѻ�����Թ����Թ���";
	$orderStatusLabels["06"] = "������Ѻ�����Թ����Թ���";
	
	$orderStatusLabels["07"] = "�Ѵ���Թ�������";
	$orderStatusLabels["08"] = "�Ѻ�Թ�������";
	
	$bankNameLists["01"] = "��Ҥ���¾ҳԪ�� �Ţ���ѭ�� 173-207-186-0";
	$bankNameLists["02"] = "��Ҥ�á�ا෾�ӡѴ �Ţ���ѭ�� 173-207-186-0";
	$bankNameLists["03"] = "��Ҥ�á�ԡ��� �Ţ���ѭ�� 173-207-186-0";
		
		
?>