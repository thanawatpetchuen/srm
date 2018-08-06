host-tracker6

<?
	set_time_limit(0);
	
	include("conf/define-config.php");
	include("conf/icon-config.php");  
	include("conf/url-config.php"); 
	include("_libs/commons/mysql_class.php");
	include("_libs/commons/html_generator.class.php");	
	include("_libs/commons/date_class.php");	
	include("_libs/xmls/xml.class.php");
	
	include("_classes/AbstractBaseService.class.php");			
		 
	include("_classes/systems/ReqCronService.class.php");				
	include("_classes/companys/SectionService.class.php");	
	
	$_connection = _connection();
	mysql_select_db($GLOBALS["__MYSQLDB"]["DB_NAME"]) or die(mysql_error());

	$_serial_nos = array();
	$_sql = " select * from crm_job_order_history where site_code is NULL  ";
	echo "$_sql<br>";
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets); 
	while ($row = mysql_fetch_array($_sql_rets)){	
		$_serial_nos[$row['serial_no']] = $row['serial_no'];
	}
	
	print_r($_serial_nos);

	$__serial_nos = array_keys($_serial_nos);
	
	for($i=0;$i<sizeof($__serial_nos);$i++){
			
			$_sql = " select * from crm_product_site  where serial_no = '".$__serial_nos[$i]."' ";
			echo "$_sql<br>";
			$_sql_rets = mysql_query( $_sql);
			$num2_rows = mysql_num_rows($_sql_rets);
			while ($row3 = mysql_fetch_array($_sql_rets)){	
			
				$site_code = $row3['site_code'];
				$model_capacity = $row3['model_capacity'];
				$serial_no = $row3['serial_no'];

				$_sql4 = " update crm_job_order_history set site_code = '$site_code', model_capacity = '$model_capacity'  where serial_no = '$serial_no' ";
				$_delrets = mysql_query( $_sql4); 
				echo "($_delrets)$_sql4<br>";			
			
			}

	}

	  
		
?>