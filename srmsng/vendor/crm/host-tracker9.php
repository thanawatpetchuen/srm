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
	$_sql = " select * from crm_job_order where  job_order_status_id = '5' AND rept_symtom is null  order by job_order_id DESC LIMIT 0, 10  ";
	echo "$_sql<br>";
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	while ($row = mysql_fetch_array($_sql_rets)){	
		
		$job_order_id = $row['job_order_id'];
		$supervisor_comment = "";
		for($i=1;$i<=20;$i++){
				
				$_sql = " select  * from rept_work_".$i." where job_order_id = '".$job_order_id."'  ";
				echo "$_sql<br>";
				$_sql3_rets = mysql_query( $_sql); 
				$report_employees = "";
				if($row3 = mysql_fetch_array($_sql3_rets)){	
							$supervisor_comment .=	$row3['supervisor_comment'];
				}
										
		}
		
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

		$_sql2 = "update crm_job_order set rept_site_contracts = '$site_contract' , rept_site_telephone = '$rept_telephone', rept_site_report_employees = '$report_employee_names' where job_order_id = '$job_order_id'  ";
		$_row_rets = mysql_query($_sql2);
		echo "($_row_rets)$_sql2<br>";
		

			
	}
	

		
?>