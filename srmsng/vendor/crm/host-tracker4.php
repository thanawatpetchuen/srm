host-tracker4

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
	
	/*
	$_sql3 = " delete FROM crm_job_order_history WHERE job_order_id > 0  ";
	echo "<h1>$_sql3</h1>";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$num_rows = mysql_query( $_sql3);

	$_sql3 = " update  rept_work_customer set  upd_crm_history = 0 ";
	echo "<h1>$_sql3</h1>";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$num_rows = mysql_query( $_sql3);
	*/
	
	/* เริ่มต้นสำหรับ Battery CELL SHEET */
	$_sql = " SELECT * ,  _tab2.site_code, _tab2.customer_code, _tab2.work_type_id, _tab3.product_type_code, _tab4.product_brand_code, _tab5.rated FROM rept_work_customer _tab, crm_job_order _tab2, crm_product_type _tab3, crm_product_brand  _tab4, crm_product  _tab5  WHERE _tab.job_order_id = _tab2.job_order_id AND _tab3.product_type_id = _tab2.product_type_id AND _tab4.product_brand_id = _tab2.product_brand_id AND _tab2.model_capacity = _tab5.model_capacity AND _tab2.job_order_status_id = '5'  AND _tab.upd_crm_history = '0' AND _tab.approve_employee_id > 0 AND _tab2.job_order_comp_datetime  is not NULL ORDER BY _tab2.job_order_id DESC  LIMIT 0, 100 "; //AND _tab.rept_work_type in ('4', '6', '8', '13', '14', '15', '16', '17', '18', '19')   AND _tab.job_order_no in (201203-01-0465')   AND _tab.job_order_no in ('201203-01-0465')  
	echo "$_sql<br>";
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	echo "crm_job_order  have $num_rows rows<BR>";
	$_rows = 0;
	while ($row = mysql_fetch_array($_sql_rets)){	
			
			$job_order_id = $row["job_order_id"];
			$job_order_no = $row["job_order_no"];
			$serial_no = $row["serial_no"];
			$job_order_desc = $row["job_order_desc"];
			$job_order_call_datetime = $row["job_order_call_datetime"];
			
			$rept_work_type = $row["rept_work_type"];

			$division_id = $row["division_id"];
			$section_id = $row["section_id"];
			
			$job_order_year = $row["job_order_year"];
			$job_order_month = $row["job_order_month"];
			$work_type_id = $row["work_type_id"];
			
			echo "\$work_type_id[$work_type_id]<br>";
						
			$time_start = $row["job_order_visit_datetime"];
			$time_stop = $row["job_order_comp_datetime"];

			$model_capacity = $row["model_capacity"];
			$report_datetime = $row["report_datetime"];
			

			$rept_work_customer_id = $row["rept_work_customer_id"];

			$customer_code = $row["customer_code"];
			$customer_name_th = $row["customer_name_th"];
			$site_code = $row["site_code"];
			$site_name_th = $row["site_name_th"];
			$product_type_code = $row["product_type_code"];
			$product_brand_code = $row["product_brand_code"];
			$card_no = $row["card_no"]; 			
			$rate = $row["rated"]; 			
			
			$_sql2 = "select * from rept_work_customer _tab, rept_work_{$rept_work_type} _tab2  where _tab.rept_work_id = _tab2.rept_work_{$rept_work_type}_id AND _tab.rept_work_type = '$rept_work_type' AND _tab.job_order_id = '$job_order_id'  ";
			//echo "$_sql2<br>";
			$_sql2_rets = mysql_query( $_sql2); 
			$_rows = 0;
			if($row2 = mysql_fetch_array($_sql2_rets)){	
								
				$supervisor_comment = $row2["supervisor_comment"];
				
				$solution = "";
				$message = "";
				$other_report = "";
								
				$hidden_condition = $row2["hidden_condition"];
				echo "<h1>\$hidden_condition[$hidden_condition]</h1>";
				
				if($rept_work_type==4 || $rept_work_type==6 || $rept_work_type==8){ 
						$message = $row2["comment"];  
				}else  if($rept_work_type==5){ 
				}else  if($rept_work_type==7){ 
				}else  if($rept_work_type==8){ 
						$other_report .= $row2["other_report"]; 	
				}else  if($rept_work_type==9){ 
						$other_report .= $row2["other_report"]; 	
				}else  if($rept_work_type==10){
				}else  if($rept_work_type==11){
						$solution .= $row2["other_report"]; 						
						echo "<h1>battery($serial_no)$solution</h1>";
				}else  if($rept_work_type==12){
				}else  if($rept_work_type==13){
						$message = $row2["detail"]; 
				}else  if($rept_work_type==14){
						$message = $row2["detail"]; 
				}else if($rept_work_type==15){ 
						$message = $row2["detail_repair"];  
				}else if($rept_work_type==16){ 
						$message = $row2["detail_repair"];  
				}else if($rept_work_type==17){ 
						$message = $row2["detail_repair"];  
				}else if($rept_work_type==18){ 
						$message = $row2["detail_repair"];  
				}else if($rept_work_type==19){ 
						$message = $row2["edit_report"];						
				}else if($rept_work_type==20){  
				}
								       
				$_sql3 = "INSERT INTO crm_job_order_history(customer_code, customer_name, site_name, product_type_code, product_brand_code, rate, job_order_id, job_order_no, serial_no, docwork, symtom, rept_work_type, job_order_year, job_order_month, work_type_id, engineercomment, time_start , time_stop, jobdate, division_id, section_id, solution, message, model_capacity, site_code, report_datetime, hidden_condition, other_report) ";
				$_sql3 .= "VALUES ('$customer_code', '$customer_name_th', '$site_name_th', '$product_type_code', '$product_brand_code', '$rate', '$job_order_id', '$job_order_no', '$serial_no', '$job_order_desc', '$job_order_desc', '$rept_work_type', '$job_order_year', '$job_order_month', '$work_type_id', '$supervisor_comment', '$time_start', '$time_stop', '$job_order_call_datetime', '$division_id', '$section_id', '$solution' , '$message', '$model_capacity', '$site_code', '$report_datetime', '$hidden_condition', '$other_report')		";
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
				$num_rows = mysql_query( $_sql3);
				echo "($num_rows)$_sql3<br>";
				
				if($num_rows==1){
					$_sql3 = "update  rept_work_customer set  upd_crm_history = '1' where rept_work_customer_id = '$rept_work_customer_id' ";
					//echo "<h1>$_sql3</h1>";
					mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
					$num_rows = mysql_query( $_sql3);

					$_sql3 = "update  crm_job_order_history set  model_capacity = '$model_capacity', customer_code = '$customer_code', customer_name = '$customer_name_th', site_code = '$site_code', site_name = '$site_name_th', product_type_code = '$product_type_code', product_brand_code = '$product_brand_code', rate = '$rate' where serial_no = '$serial_no' ";
					echo "<b>$_sql3</b>";
					mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
					$num_rows = mysql_query( $_sql3);

				}
				
			}
			

				
	}
	
	$_sql3 = "update  crm_job_order_history set  work_type_id = '1' where worktype = 'PM-บริการ' ";
	//echo "<h1>$_sql3</h1>";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$num_rows = mysql_query( $_sql3);
	
	$_sql3 = "update  crm_job_order_history set  work_type_id = '2' where worktype = 'CC=รับแจ้งเครื่องขัดข้อง' ";
	//echo "<h1>$_sql3</h1>";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$num_rows = mysql_query( $_sql3);

	$_sql3 = "update  crm_job_order_history set  work_type_id = '5' where worktype = 'CM=ซ่อม' ";
	//echo "<h1>$_sql3</h1>";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$num_rows = mysql_query( $_sql3);

	$_sql3 = "update  crm_job_order_history set  work_type_id = '6' where worktype = 'O=อื่น ๆ' ";
	//echo "<h1>$_sql3</h1>";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$num_rows = mysql_query( $_sql3);

	$_sql3 = "update  crm_job_order_history set  work_type_id = '3' where worktype = 'IS=ติดตั้ง' ";
	//echo "<h1>$_sql3</h1>";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$num_rows = mysql_query( $_sql3);
	
	$_sql3 = "update  crm_job_order_history set  work_type_id = '4' where worktype = 'PC=Per Call' ";
	//echo "<h1>$_sql3</h1>";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$num_rows = mysql_query( $_sql3);
		
	echo "SUCCESS++.";
	
	//include("host-tracker5.php");
	
	echo "<h1>host-tracker8.php</h1>";
	include("host-tracker8.php");
	
	
	_close($_connection);
	

	exit;
	
?>