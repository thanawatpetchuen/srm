<? 
	
	include("../conf/define-config.php");
	include("../conf/icon-config.php");  
	include("../conf/url-config.php"); 
	include("../_libs/commons/mysql_class.php");
	include("../_libs/commons/html_generator.class.php");	
	include("../_libs/commons/date_class.php");	
	include("../_libs/xmls/xml.class.php");
	
	$_connection = _connection();
	
	
	$_sql = "TRUNCATE TABLE crm_contract ";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	echo "delete($_sql_rets)<br>";
	$_sql = "TRUNCATE TABLE crm_contract_condition ";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	echo "delete($_sql_rets)<br>";
	$_sql = "TRUNCATE TABLE crm_contract_contact ";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	echo "delete($_sql_rets)<br>";
	
	/*
	
	$_sql="SELECT * from end_cus  where CUS is not null AND CUS <> '' AND CUS_THAI <> ''  group by cus_thai ";	
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	echo "end_cus  have $num_rows rows<BR>";
	$_row_id = 1;
	$_errorrow_id = 0;

	while ($row = mysql_fetch_array($_sql_rets)){	
			
			$cus_code = "CUS-".str_pad($_row_id, 4, "0", STR_PAD_LEFT);
			
			echo "CUS_THAI >>".$row["CUS_THAI"]."<br>";
			echo "CUS >>".$row["CUS"]."<br>";
			
			//echo "Province >>".$row["Province"]."<br>";
			
			$_sql2 = " insert into crm_customer(customer_code, customer_name_th, customer_name_en, remote_ip, created, province_code) value('$cus_code', '".addslashes($row["CUS_THAI"])."', '".addslashes($row["CUS"])."', '127.0.0.1', NOW(), '".$row["Province"]."')";	
			mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
			$rowret = mysql_query( $_sql2);
			$_customer_id = mysql_insert_id();
			echo "\$_customer_id($_customer_id)<BR>";
			echo "($rowret)$_sql2<BR>";

			if($rowret==1){
				
				
				if($row["Contact_Person"]!=""){
					$_sql3 = " insert into crm_customer_contact(customer_id, contact_name, contact_email, remote_ip, created) value('$_customer_id', '".$row["Contact_Person"]."', '".$row["email"]."', '127.0.0.1', NOW())";	
					mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
					$rowret = mysql_query( $_sql3); 
					echo "($rowret)$_sql3<BR>";
				}
				
				if($row["Contact_Person2"]!=""){
					$_sql3 = " insert into crm_customer_contact(customer_id, contact_name, contact_email, remote_ip, created) value('$_customer_id', '".$row["Contact_Person2"]."', '".$row["email"]."', '127.0.0.1', NOW())";	
					mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
					$rowret = mysql_query( $_sql3); 
					echo "($rowret)$_sql3<BR>";
				}
				
				$site_code = "SITE-".str_pad($_row_id, 4, "0", STR_PAD_LEFT);
				
				$site_name_th = $row["ADDRESS"];
				$site_name_en = "";
				
				$_sql4 = " insert into crm_site(site_code, site_name_th, site_name_en, customer_id, customer_name_th, remote_ip, created, province_code) value('$site_code', '$site_name_th','$site_name_en' , '$_customer_id', '".addslashes($row["CUS_THAI"])."', '127.0.0.1', NOW(), '".$row["Province"]."')";	
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
				$rowret = mysql_query( $_sql4);
				$_site_id = mysql_insert_id();
				echo "\$_site_id($_site_id)<BR>";
				echo "($rowret)$_sql4<BR>";
				
				if($rowret==1){
								
					if($row["Contact_Person"]!=""){
						$_sql3 = " insert into crm_site_contact(site_id, contact_name, contact_email, remote_ip, created) value('$_site_id', '".$row["Contact_Person"]."', '".$row["email"]."', '127.0.0.1', NOW())";	
						mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
						$rowret = mysql_query( $_sql3); 
						echo "($rowret)$_sql3<BR>";
					}
					
					if($row["Contact_Person2"]!=""){
						$_sql3 = " insert into crm_site_contact(site_id, contact_name, contact_email, remote_ip, created) value('$_site_id', '".$row["Contact_Person2"]."', '".$row["email"]."', '127.0.0.1', NOW())";	
						mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
						$rowret = mysql_query( $_sql3); 
						echo "($rowret)$_sql3<BR>";
					}
					
				}

				$ce_code = $row["Area"];
				$serial_no = $row["SER"];
				$install_dt = $row["Batt_Install_Date"];
				$model_capacity = $row["MOD"];
				//$install_dt = $row["SER"];
				
				$pm_1 = $row["Jan MC"];
				$pm_2 = $row["Feb MC"];
				$pm_3 = $row["Mar MC"];
				$pm_4 = $row["Apr MC"];
				$pm_5 = $row["May MC"];
				$pm_6 = $row["Jun MC"];
				$pm_7 = $row["Jul MC"];
				$pm_8 = $row["Aug MC"];
				$pm_9 = $row["Sep MC"];
				$pm_10 = $row["Oct MC"];
				$pm_11 = $row["Nov MC"];
				$pm_12 = $row["Dec MC"];

				$ald_pm_1 = $row["Jan1"];
				$ald_pm_2 = $row["Feb1"];
				$ald_pm_3 = $row["Mar1"];
				$ald_pm_4 = $row["Apr1"];
				$ald_pm_5 = $row["May1"];
				$ald_pm_6 = $row["Jun1"];
				$ald_pm_7 = $row["Jul1"];
				$ald_pm_8 = $row["Aug1"];
				$ald_pm_9 = $row["Sep1"];
				$ald_pm_10 = $row["Oct1"];
				$ald_pm_11 = $row["Nov1"];
				$ald_pm_12 = $row["Dec1"];

				$rs_pm_1 = $row["Jan"];
				$rs_pm_2 = $row["Feb"];
				$rs_pm_3 = $row["Mar"];
				$rs_pm_4 = $row["Apr"];
				$rs_pm_5 = $row["May"];
				$rs_pm_6 = $row["Jun"];
				$rs_pm_7 = $row["Jul"];
				$rs_pm_8 = $row["Aug"];
				$rs_pm_9 = $row["Sep"];
				$rs_pm_10 = $row["Oct"];
				$rs_pm_11 = $row["Nov"];
				$rs_pm_12 = $row["Dec"];
								
				$_sql4 = " insert into crm_product_site(model_capacity, remote_ip, created) ";
				$_sql4 .= " value('$_site_id', '$site_code', '$_customer_id', '$cus_code', '$ce_code', '$serial_no', '$install_dt', '$model_capacity', '$pm_1', '$pm_2', '$pm_3', '$pm_4', '$pm_5', '$pm_6', '$pm_7', '$pm_8', '$pm_9', '$pm_10', '$pm_11', '$pm_12', '$ald_pm_1', '$ald_pm_2', '$ald_pm_3', '$ald_pm_4', '$ald_pm_5', '$ald_pm_6', '$ald_pm_7', '$ald_pm_8', '$ald_pm_9', '$ald_pm_10', '$ald_pm_11', '$ald_pm_12', '$rs_pm_1', '$rs_pm_2', '$rs_pm_3', '$rs_pm_4', '$rs_pm_5', '$rs_pm_6', '$rs_pm_7', '$rs_pm_8', '$rs_pm_9', '$rs_pm_10', '$rs_pm_11', '$rs_pm_12', '127.0.0.1', NOW())";	
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
				$rowret = mysql_query( $_sql4);
				$_site_id = mysql_insert_id();
				echo "\$_site_id($_site_id)<BR>";
				echo "($rowret)$_sql4<BR>";
				
				$brand = $row["Brand"];
				$rated =$row["Rate"];
				//$capacity = 
				$product_type_id = 1;
				$product_brand_id = 1;
				
				$_sql5 = " insert into crm_product(model_capacity, product_type_id, brand, product_brand_id, rated, remote_ip, created) value('$model_capacity', '$product_type_id', '$brand', '$product_brand_id', '$rated', '127.0.0.1', NOW())";	
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
				$rowret = mysql_query( $_sql5); 
				if($rowret==1)
					echo "<h2>($rowret)$_sql5</h2><BR>";
				else
					echo "($rowret)$_sql5<BR>";
				
				/* เพิ่ม Customer Code ++ */
				$_errorrow_id ++;
				
			} 
			
			$_row_id ++;

	} 
	

	*/


	echo "<h1>ERROR >>$_errorrow_id</h1>";

	_close($_connection);
 

?>

<script>
	window.location = "load_product_site.php";
</script>