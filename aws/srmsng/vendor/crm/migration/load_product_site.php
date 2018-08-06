<? 
	
	include("../conf/define-config.php");
	include("../conf/icon-config.php");  
	include("../conf/url-config.php"); 
	include("../_libs/commons/mysql_class.php");
	include("../_libs/commons/html_generator.class.php");	
	include("../_libs/commons/date_class.php");	
	include("../_libs/xmls/xml.class.php");
	
	$_connection = _connection();
		
	$_sql = "TRUNCATE TABLE crm_product_site ";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	echo "delete($_sql_rets)<br>"; 

	
	$_sql="SELECT * from crm_customer  ";	
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	echo "end_cus  have $num_rows rows<BR>";
	$_row_id = 1;
	$_errorrow_id = 0; 
	
	$customers = array();
	while ($row = mysql_fetch_array($_sql_rets)){	
			
			//$product_brand_code = $row["product_brand_id"];
			$customers[trim($row["customer_name_th"])."-".trim($row["customer_name_en"])] = $row["customer_id"];
			$customerCodes[trim($row["customer_name_th"])."-".trim($row["customer_name_en"])] = $row["customer_code"];
	}
	

	print_r($customers);
	echo "<hr>";

	$_sql="SELECT * from crm_site  ";	
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	echo "end_cus  have $num_rows rows<BR>";
	$_row_id = 1;
	$_errorrow_id = 0;  

	$sites = array();
	$siteCodes = array();
	while ($row = mysql_fetch_array($_sql_rets)){	
			
			//$product_brand_code = $row["product_brand_id"];
			$sites[trim($row["site_name_th"])] = $row["site_id"];
			$siteCodes[trim($row["site_name_th"])] = $row["site_code"];
	}
	
	print_r($sites);
	echo "<hr>";
		
	$_sql="SELECT * from end_cus  where CUS is not null AND CUS <> '' AND CUS_THAI <> '' AND SER is not null ";	
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	echo "product site end_cus  have $num_rows rows<BR>";
	$_row_id = 1;
	$_errorrow_id = 0;

	while ($row = mysql_fetch_array($_sql_rets)){	
				
								
				$brand = $row["Brand"];
				$rated =$row["Rate"];
				//$capacity = 
				$product_type_id = 1;
				$product_brand_id = 1;


				$ce_code = $row["Area"];
				$serial_no = $row["SER"];
				$install_dt = $row["Batt_Install_Date"];
				if(empty($install_dt))
					$install_dt = "1990-01-01 00:00:00";

				$model_capacity = $row["model_capacity"];
				//$install_dt = $row["SER"];
				
				$kva =$row["KVA"]*1;
				$volt =$row["VOL"]*1;
				$ah_watt =$row["AH"]*1;
				$backup_timer =$row["BACKUP"]*1;
				$no_of_unix_block =$row["NOB"]*1;
				
				
				
				$customer_id = $customers[trim($row["CUS_THAI"])."-".trim($row["CUS"])];
				$customer_code = $customerCodes[trim($row["CUS_THAI"])."-".trim($row["CUS"])];

				if($customer_id==0 || $customer_id==""){
					$customer_id = -1;
					$customer_code = "CUSTMP-001";
				}
				
				echo "\$customer_id - {$row["CUS_THAI"]} >>$customer_id<BR>";

				$site_id = $sites[trim($row["ADDRESS"])];
				$site_code = $siteCodes[trim($row["ADDRESS"])];
				if($site_id==0 || $site_id==""){
					$site_id = -1;
					$site_code = "TMP-001";
				}
				
				echo "\$site_id - {$row["ADDRESS"]} >>$site_id<BR>";

				$part_included = $row["Part included"];
				if($part_included=="YES")
					$part_included = "Y";
				else
					$part_included = "N";
				$include_capacitor = $row["INCLUDE CAPACITOR"];

				if($include_capacitor=="YES")
					$include_capacitor = "Y";
				else
					$include_capacitor = "N";
				
				$includ_batt = $row["INCLUD BATT"];
				if($includ_batt=="YES")
					$includ_batt = "Y";
				else
					$includ_batt = "N";
				
				$tele_service = $row["Tele Service"];
				if($tele_service=="YES")
					$tele_service = "Y";
				else
					$tele_service = "N";

								
				$_sql4 = " insert into crm_product_site(site_id, site_code, customer_id, customer_code, ce_code, serial_no, install_dt, model_capacity, remote_ip, created, part_included, include_capacitor, includ_batt, tele_service, kva, volt, ah_watt, backup_timer, no_of_unix_block) ";
				$_sql4 .= " value('$site_id', '$site_code', '$customer_id', '$customer_code', '$ce_code', '$serial_no', '$install_dt', '$model_capacity', '127.0.0.1', NOW(), '$part_included', '$include_capacitor', '$includ_batt', '$tele_service', '$kva', '$volt', '$ah_watt', '$backup_timer', '$no_of_unix_block')";	
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
				$rowret = mysql_query( $_sql4);
				$_product_site_id = mysql_insert_id();
				
				echo "\$_product_site_id($_product_site_id)<BR>";
				echo "($rowret)$_sql4<BR>";
				
								
				/* เพิ่ม Customer Code ++ */
				$_errorrow_id ++;
				
					 

	} 
	

	echo "<h1>ERROR >>$_errorrow_id</h1>";

	_close($_connection);
 

?>

<script>
	//window.location = "load_product_site.php";
</script>