host-tracker2

<? 
	
	include("conf/define-config.php");
	include("conf/icon-config.php");  
	include("conf/url-config.php"); 
	include("_libs/commons/mysql_class.php");
	include("_libs/commons/html_generator.class.php");	
	include("_libs/commons/date_class.php");	
	include("_libs/xmls/xml.class.php");
	
	$_connection = _connection();
	mysql_select_db($GLOBALS["__MYSQLDB"]["DB_NAME"]) or die(mysql_error());

	$_sql=" select * from crm_contract where expire_dt < now() AND contract_condition_code <> '0' AND contract_condition_desc <>'0 ไม่มีสัญญา(Automatic)'"; // AND contract_condition_code <> '0' ";
	echo "$_sql<br>";          
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	echo "crm_contract  have $num_rows rows<BR>";
	$_row_id = 1;
	while ($row = mysql_fetch_array($_sql_rets)){	
			
			$card_no = $row["card_no"];
			
			$_sql3="update crm_contract set contract_condition_code = '0',  contract_condition_desc  ='0 ไม่มีสัญญา(Automatic)'  where card_no = '$card_no' ";	 
			echo "$_sql3<br>";
			mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
			$_rows = mysql_query( $_sql3);  
			
			/*
			$_sql3="update crm_product_site set card_status = '0' where card_no = '$card_no' ";	 
			echo "$_sql3<br>";
			mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
			$_rows = mysql_query( $_sql3);  
			*/

	}
		
	$_sql=" SELECT distinct customer_code FROM crm_site where (customer_name_th is null OR customer_name_th = '') ";
	echo "$_sql<br>";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	echo "crm_site  have $num_rows rows<BR>";
	$_row_id = 1;
	while ($row = mysql_fetch_array($_sql_rets)){	
			
			$customer_code = $row["customer_code"];

			$_sql=" SELECT * FROM crm_customer where customer_code = '$customer_code' ";
			echo "$_sql<br>";
			mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
			$_sql2_rets = mysql_query( $_sql); 
			$customer_name_th = null;
			if ($row2 = mysql_fetch_array($_sql2_rets)){	
				
				$customer_name_th = $row2["customer_name_th"]; 
									
				$_sql3="update crm_site set customer_name_th = '$customer_name_th' where customer_code = '$customer_code' ";	 
				echo "$_sql3<br>";
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
				$_rows = mysql_query( $_sql3);  

			}


	}



	$_sql=" SELECT distinct site_code FROM crm_product_site where (customer_code is null OR customer_code = '') ";
	echo "$_sql<br>";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	echo "crm_product_site  have $num_rows rows<BR>";
	$_row_id = 1;
	while ($row = mysql_fetch_array($_sql_rets)){	
			
			$site_code = $row["site_code"];
			$_sql=" SELECT * FROM crm_site where site_code = '$site_code' ";
			echo "$_sql<br>";
			mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
			$_sql2_rets = mysql_query( $_sql); 
			$customer_code = null;
			if ($row2 = mysql_fetch_array($_sql2_rets)){	
				
				$customer_code = $row2["customer_code"];
				$customer_name_th = $row2["customer_name_th"];
									

				$_sql3="update crm_product_site set customer_code = '$customer_code', customer_name_th = '$customer_name_th' where site_code = '$site_code' ";	 
				echo "(2)$_sql3<br>";
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
				$_rows = mysql_query( $_sql3); 

				
			}


	}

	
	$_sql="SELECT * FROM crm_job_order _tab, rept_work_customer _tab2 where _tab.job_order_id = _tab2.job_order_id AND _tab.job_order_status_id = '5' AND _tab.approve_dt is null AND _tab2.approve_dt is not null ";
	echo "$_sql<br>";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	echo "crm_job_order  have $num_rows rows<BR>";
	$_row_id = 1;
	while ($row = mysql_fetch_array($_sql_rets)){	
				
				$_job_order_no = $row["job_order_no"];
				$_job_order_id = $row["job_order_id"];
				$approve_employee_id = $row["approve_employee_id"];
				$approve_dt = $row["approve_dt"];
				
				echo "$_job_order_id($_job_order_no)-($approve_employee_id)<br>";
								 
				$_sql2="update rept_work_customer set job_order_no = '$_job_order_no' where job_order_id = '$_job_order_id' ";	
								
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
				$_rows = mysql_query( $_sql2);
				echo "($_rows)$_sql2<br>"; 

				$_sql2="update crm_job_order set approve_employee_id = '$approve_employee_id', approve_dt = '$approve_dt' where job_order_id = '$_job_order_id' ";	
								
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
				$_rows = mysql_query( $_sql2);
				echo "($_rows)$_sql2<br>"; 
				
	}
	
	$_sql=" UPDATE crm_job_order set assign_dt = assign2_dt WHERE assign_dt IS NULL AND assign2_dt IS NOT NULL ";
	echo "$_sql<br>";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
		
	_close($_connection);

?>