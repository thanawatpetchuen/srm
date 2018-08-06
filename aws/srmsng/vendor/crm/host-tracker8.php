<?
	 

	$_sql = " select * from comp_employee  ";
	echo "$_sql<br>";
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets); 
	$_name_employees = array();
	while ($row = mysql_fetch_array($_sql_rets)){	
		
		$_name_employees[$row['employee_id']] = $row['employee_name_th']." ".$row['employee_last_name_th'];
		
	}
	
	$_serial_nos = array();
	$_sql = " select * from crm_job_order where  job_order_status_id = '5' AND  rept_operation is null  order by job_order_id DESC LIMIT 0, 500  ";

	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	echo "<h1>[".$GLOBALS["__MYSQLDB"]["DB_NAME"]."]($num_rows)$_sql</h1><br>";
	while ($row = mysql_fetch_array($_sql_rets)){	
		
		$job_order_id = $row['job_order_id'];
		$job_order_desc = $row['job_order_desc'];
		$job_order_no = $row['job_order_no'];

		echo "<h1>($job_order_id)$job_order_desc</h1>";

		$supervisor_comment = "";
		
		$months = array(2, 8, 9 , 19, 13, 15);
		
		
		$report_employees = "";
		$rept_symtom = "";
		$rept_solution = "";
		
		$hidden_condition = "";
		$rept_operation = "";

		for($i=0; $i<sizeof($months); $i++){
				
				$_sql = " select  * from rept_work_".$months[$i]." where job_order_id = '".$job_order_id."'  ";
				echo "($job_order_no)$_sql<br>";
				$_sql3_rets = mysql_query( $_sql); 
											
				if($row3 = mysql_fetch_array($_sql3_rets)){	
							
							echo "+++++(".$months[$i].")rectifier_status >>".$row3['rectifier_status']."<br>";
																												
							if($months[$i]==2){ 
									$rept_operation = $row3['operation']; 

							}else if($months[$i]==19){ 
									
									if($row3['rectifier_status']==10)
										$rept_operation = "ทำงานปกติ";
									else if($row3['rectifier_status']==11)
										$rept_operation = "ขัดข้อง (แก้ไขให้เสร็จเรียบร้อยก่อนทดสอบต่อไป)"; 

							}else if($months[$i]==8 || $months[$i]==9){ 

									//echo "<h1>No Detail++</h1>";
									
									$rept_other_report = $row3['other_report'];
									
									if($row3['operation']==0)
										$rept_operation = "ทำงานปกติ";
									else if( $row3['operation']==1)
										$rept_operation = "ขัดข้อง";
									else if( $row3['operation']==2)
										$rept_operation = "ไม่พบเครื่อง";
									else if( $row3['operation']==3)
										$rept_operation = "เครื่องไม่ได้ใช้งาน";
									
										
									echo "<h1>(".$row3['operation'].")\$rept_operation [$rept_operation ]</h1>";

							}else if($months[$i]==13){
									$rept_symtom = $job_order_desc;
									$rept_solution .= $row3['detail'];
							}else if($months[$i]==15){
									$rept_symtom .= $row3['detail_error'];
									$rept_solution .= $row3['detail_repair'];
													

							}
														
							echo "(".$months[$i].")\$rept_operation >>".$rept_operation."<br>";
							
							$supervisor_comment .=	$row3['supervisor_comment'];

							if(!empty($row3["hidden_condition"]))
								$hidden_condition = $row3["hidden_condition"];
							
											
				}
							
		}
		
		echo "($job_order_no)\$rept_symtom[$rept_symtom]\$rept_solution[$rept_solution]<br>";
		echo "\$supervisor_comment[$supervisor_comment]<br>";
		
		$_sql = " select  * from rept_work_customer where job_order_id = '".$job_order_id."'  ";
		echo "$_sql<br>";
		$_sql2_rets = mysql_query( $_sql);
		$site_contract_customer = array();
		$telephones = array();
		$report_employees = "";
		while ($row2 = mysql_fetch_array($_sql2_rets)){	

			echo "Hello++<br>";
			$site_contract_customer[$row2['site_contract_customer']] = $row2['site_contract_customer'];
			$telephones[$row2['telephone']] = $row2['telephone'];
			$report_employees .= ",".$row2['report_employees'];
			$report_employees .= ",".$row2['report_employee_id'];

			$report_datetime  = $row2['report_datetime'];
			 
		}

		echo "\$report_employees =>$report_employees<br>";
		
		$cnt_employees = explode(",", $report_employees);
		print_r($cnt_employees);
		echo "<hr>";
		$_key_employees = array();
		for($k=0;$k<sizeof($cnt_employees);$k++){
			if($cnt_employees[$k]!="")
				$_key_employees[$cnt_employees[$k]] = $cnt_employees[$k];
		}
		print_r($_key_employees);
		echo "<hr>";
		
		$cnt_employees = array_keys($_key_employees);
		$report_employee_names = NULL;
		for($k=0;$k<sizeof($cnt_employees);$k++){
			if(!empty($_name_employees[$cnt_employees[$k]]) && $_name_employees[$cnt_employees[$k]]!=""){
				echo "!!!!!!!(".$_name_employees[$cnt_employees[$k]].")append++<BR>";
				if(empty($report_employee_names) || $report_employee_names=="")
					$report_employee_names .= $_name_employees[$cnt_employees[$k]];
				else
					$report_employee_names .= ", ".$_name_employees[$cnt_employees[$k]];
			} 
		}	

		print_r($report_employee_arrays);
		echo "<hr>";
		 
		$_keys = array_keys($site_contract_customer);
		$site_contract = implode("," ,$_keys);
		$_keys = array_keys($telephones);
		$rept_telephone = implode("," ,$_keys);
		
		echo "($job_order_id)$site_contract ($rept_telephone)<BR>";

		$_sql2 = "update crm_job_order set rept_site_contracts = '$site_contract' , rept_site_telephone = '$rept_telephone', rept_site_report_employees = '$report_employee_names', rept_supervisor_comments = '$supervisor_comment', rept_symtom ='$rept_symtom', rept_solution = '$rept_solution', rept_datetime = '$report_datetime', rept_other_report ='$rept_other_report', rept_operation = '$rept_operation', rept_hidden_condition = '$hidden_condition' where job_order_id = '$job_order_id'  ";
		$_row_rets = mysql_query($_sql2);
		echo "($_row_rets)$_sql2<br>";
	
					
	}
	

		
?>