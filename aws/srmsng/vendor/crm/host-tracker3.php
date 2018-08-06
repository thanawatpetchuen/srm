host-tracker3

<? 
	
	ignore_user_abort(true);
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

	$_sql3 = " delete FROM crm_battery WHERE job_order_id > 0  ";
	echo "<h1>$_sql3</h1>";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$num_rows = mysql_query( $_sql3);
	
	$_sql3 = "update  rept_work_11 set  upd_crm_battery = 0 ";
	echo "<h1>$_sql3</h1>";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$num_rows = mysql_query( $_sql3);
	 
	/* เริ่มต้นสำหรับ Battery CELL SHEET */
	$_sql = " SELECT *, _tab3.job_order_comp_datetime, _tab3.work_type_id FROM rept_work_11 _tab, rept_work_customer _tab2 , crm_job_order _tab3
WHERE _tab.rept_work_11_id = _tab2.rept_work_id AND _tab2.rept_work_type = '11' AND _tab.job_order_status_id = '5'  AND _tab2.job_order_id = _tab3.job_order_id  AND _tab.upd_crm_battery = '0' order by  _tab3.job_order_id DESC "; //AND _tab3.serial_no = 'MA862880018'	 ";  /*	  */
	echo "$_sql<br>";
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	echo "crm_job_order  have $num_rows rows<BR>";
	while ($row = mysql_fetch_array($_sql_rets)){	
		
		$job_order_id = $row["job_order_id"];
		$job_order_no = $row["job_order_no"];
		$rept_work_11_id = $row["rept_work_11_id"];
		
		$serial_no = $row["serial_no"];
		$battery_brand = $row["battery_brand"];
		$battery_model = $row["battery_model"];
		
				echo "\$job_order_id($job_order_id)\$rept_work_11_id($rept_work_11_id)<br>";
				
				$battery_present  = $row["battery_present"];
				$battery_install  = $row["battery_install"];
				$battery_change  = $row["battery_change"];
				$battery_disconnect  = $row["battery_disconnect"];

				echo "<h1>battery_install[$battery_install]battery_present[$battery_present]</h1>";
				
				$job_order_comp_datetime  = $row["job_order_comp_datetime"];
				$work_type_id = $row["work_type_id"];
				echo "<h3>\$work_type_id - ($work_type_id)</h3>";
				
				$report_datetime  = $row["report_datetime"];
				$begin_dt  = $row["begin_dt"];
				$end_dt  = $row["end_dt"];
				
				/*
				if($battery_present==0){
					
					$_sql31 = " select * FROM rept_work_11_1 where rept_work_11_id = '$rept_work_11_id' ";
					mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
					$_sql31_rets = mysql_query( $_sql31);
					$num_rows = mysql_num_rows($_sql31_rets);
					$battery_present = $num_rows;
					$_sql4 = "UPDATE rept_work_11 set battery_present = '$num_rows' where rept_work_11_id = '$rept_work_11_id' ";
					mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
					$num3_rows = mysql_query( $_sql4);
					echo "($num3_rows)$_sql4<br>";
					
				}
				*/
				
				if($battery_present>0){
					/* update Status Present */
					$_sql3 = "INSERT INTO crm_battery(job_order_id, job_order_no, serial_no, bat_brand, bat_model, bat_amount, status_batt, install_dt, battery_last_upd, order_no) ";
					$_sql3 .= "VALUES ('$job_order_id', '$job_order_no', '$serial_no', '$battery_brand', '$battery_model', '$battery_present', 'P=Present', '$report_datetime', '$report_datetime', '2')	";
					mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
					$num_rows = mysql_query( $_sql3);
					echo "($num_rows)$_sql3<br>";
				}
				
				/* update Status Install */
				if($battery_install>0){
					$_sql3 = "INSERT INTO crm_battery(job_order_id,  job_order_no, serial_no, bat_brand, bat_model, bat_amount, status_batt,  battery_last_upd, install_dt, order_no, batbegin_dt, batend_dt) ";
					$_sql3 .= "VALUES ('$job_order_id', '$job_order_no', '$serial_no', '$battery_brand', '$battery_model', '$battery_install', 'I=Install', '$job_order_comp_datetime', '$report_datetime', '1', '$begin_dt', '$end_dt')	";
					mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
					$num_rows = mysql_query( $_sql3);
					echo "($num_rows)$_sql3<br>";
				}
				
				/* update Status Change */
				if($battery_change>0){
					$_sql3 = "INSERT INTO crm_battery(job_order_id,  job_order_no, serial_no, bat_brand, bat_model, bat_amount, status_batt, battery_last_upd, install_dt, order_no) ";
					$_sql3 .= "VALUES ('$job_order_id', '$job_order_no', '$serial_no', '$battery_brand', '$battery_model', '$battery_change', 'C=Change', '$job_order_comp_datetime', '$report_datetime', '3')	";
					mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
					$num_rows = mysql_query( $_sql3);
					echo "($num_rows)$_sql3<br>";
				}
				
				/* update Status Disconnect */
				if($battery_disconnect>0){
					$_sql3 = "INSERT INTO crm_battery(job_order_id,  job_order_no, serial_no, bat_brand, bat_model, bat_amount, status_batt, battery_last_upd, install_dt, order_no) ";
					$_sql3 .= "VALUES ('$job_order_id', '$job_order_no', '$serial_no', '$battery_brand', '$battery_model', '$battery_disconnect', 'D=Disconnect', '$job_order_comp_datetime', '$report_datetime', '4')	";
					mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
					$num_rows = mysql_query( $_sql3);
					echo "($num_rows)$_sql3<br>";
				}
				
				$_sql4 = "UPDATE rept_work_11 set upd_crm_battery = 1 where rept_work_11_id = '$rept_work_11_id' ";
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
				$num_rows = mysql_query( $_sql4);
				echo "($num_rows)$_sql4<br>";
				
				$_sql4 = "UPDATE crm_battery set install_dt  = '$report_datetime' where install_dt is NULL ";
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
				$num_rows = mysql_query( $_sql4);
				echo "($num_rows)$_sql4<br>";
				

	}

	$_sql4 = "UPDATE crm_battery set order_no  = 1  where status_batt = 'I=Install' ";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$num_rows = mysql_query( $_sql4);
	echo "($num_rows)$_sql4<br>";
	$_sql4 = "UPDATE crm_battery set order_no  = 2  where status_batt = 'P=Present' ";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$num_rows = mysql_query( $_sql4);
	echo "($num_rows)$_sql4<br>";
	$_sql4 = "UPDATE crm_battery set order_no  = 3  where status_batt = 'C=Change' ";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$num_rows = mysql_query( $_sql4);
	echo "($num_rows)$_sql4<br>";
	$_sql4 = "UPDATE crm_battery set order_no  = 4  where status_batt = 'D=Disconnect' ";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$num_rows = mysql_query( $_sql4);
	echo "($num_rows)$_sql4<br>";

	echo "SUCCESS++.";
	
	_close($_connection);
	
	exit;
	
?>