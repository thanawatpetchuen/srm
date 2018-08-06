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
	$_sql = " select serial_no, work_type_id,  jobdate, count(jobdate) AS cnt from crm_job_order_history ";
	$_sql .= " where serial_no is not null AND serial_no <>'' AND rept_work_type = 0  group by serial_no, work_type_id,  jobdate having count(jobdate)>1 "; // limit 0, 10  ";
	echo "$_sql<br>";
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets); 
	while ($row = mysql_fetch_array($_sql_rets)){	
		echo $row['serial_no']." - ".$row['work_type_id']." - (".$row['cnt'].")- ".$row['jobdate']."<BR>";
		
		$_sql = " select  * from crm_job_order_history ";
		$_sql .= " where serial_no = '".$row['serial_no']."' AND work_type_id = '".$row['work_type_id']."'  AND jobdate = '".$row['jobdate']."' ";
		echo "$_sql<br>";
		$_sql2_rets = mysql_query( $_sql);
		$num_rows = mysql_num_rows($_sql2_rets); 
		$count = 0;
		while ($row2 = mysql_fetch_array($_sql2_rets)){	
			echo $row2['history_id']." - ".$row2['serial_no']." - ".$row2['work_type_id']." - (symtom)  ".$row2['symtom']." -  ".$row2['jobdate']."<BR>"; 
			if($count>0){
				echo "++++++++++++delete (".$row2['history_id'].")<br>";
				$_sql4 = " delete FROM crm_job_order_history where history_id = '".$row2['history_id']."' ";
				$_delrets = mysql_query( $_sql4); 				
				echo "($_delrets)$_sql4<br>";
			}
			$count++;			
		}

	}
	 

	  
		
?>