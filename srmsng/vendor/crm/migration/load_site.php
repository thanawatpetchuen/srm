<? 
	
	include("../conf/define-config.php");
	include("../conf/icon-config.php");  
	include("../conf/url-config.php"); 
	include("../_libs/commons/mysql_class.php");
	include("../_libs/commons/html_generator.class.php");	
	include("../_libs/commons/date_class.php");	
	include("../_libs/xmls/xml.class.php");
	
	$_connection = _connection();
	
	$_sql = "TRUNCATE TABLE crm_site ";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	echo "delete($_sql_rets)<br>";
	$_sql = "TRUNCATE TABLE crm_site_contact ";
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
	
	$_sql=" SELECT distinct ADDRESS, CUS, CUS_THAI, Province from end_cus  where CUS is not null AND CUS <> '' AND CUS_THAI <> ''  AND ADDRESS is not NULL ";	 
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	echo "ADDRESS  have $num_rows rows<BR>";
	$_row_id = 1;
	$_errorrow_id = 0;
	
	while ($row = mysql_fetch_array($_sql_rets)){	
				
				$site_code = "SITE-".str_pad($_row_id, 4, "0", STR_PAD_LEFT);
				
				$site_name_th = trim($row["ADDRESS"]);
				$site_name_en = trim($row["ADDRESS"]);
				
				$customer_id = $customers[trim($row["CUS_THAI"]."-".$row["CUS"])];
				$customer_code = $customerCodes[trim($row["CUS_THAI"]."-".$row["CUS"])];
				echo "($site_name_th) - ($site_name_en) - (".trim($row["CUS_THAI"])."-".trim($row["CUS"]).")\$customer_id >>($customer_id)<br>";
				
				$_sql4 = " insert into crm_site(site_code, site_name_th, site_name_en, customer_id, customer_code, customer_name_th, remote_ip, created, province_code) value('$site_code', '$site_name_th','$site_name_en' , '$customer_id', '$customer_code', '".addslashes($row["CUS_THAI"])."', '127.0.0.1', NOW(), '".$row["Province"]."')";	
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
				$rowret = mysql_query( $_sql4);
				$_site_id = mysql_insert_id();
				echo "\$_site_id($_site_id)<BR>";
				echo "($rowret)$_sql4<BR>";
			
			
				if($rowret==1){

					$_sql2=" select * from end_cus where CUS_THAI = '{$row["CUS_THAI"]}' AND CUS = '{$row["CUS"]}' AND ADDRESS = '{$row["ADDRESS"]}' ";	
					mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
					$_sql2_rets = mysql_query( $_sql2);
					$num2_rows = mysql_num_rows($_sql2_rets);
					echo "contact pereson  have $num2_rows rows<BR>"; 
					
							while ($row2 = mysql_fetch_array($_sql2_rets)){	

								$contact_phone_no = $row2["TEL"];
								if($row2["TEL2"]!="")
									$contact_phone_no = $row2["TEL"].", ".$row2["TEL2"];

								$contact_fax_no = $row2["FAX"];
								if($row2["FAX2"]!="")
									$contact_fax_no = $row2["FAX"].", ".$row2["FAX2"];
																		
								if($row2["Contact_Person"]!=""){
									$_sql3 = " insert into crm_site_contact(site_id, contact_name, contact_email, remote_ip, created, contact_phone_no, contact_fax_no) value('$_site_id', '".trim($row2["Contact_Person"])."', '".$row2["email"]."', '127.0.0.1', NOW(), '$contact_phone_no',  '$contact_fax_no')";	
									mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
									$rowret = mysql_query( $_sql3); 
									echo "($rowret)$_sql3<BR>";
								}
								
								if($row2["Contact_Person2"]!=""){
									$_sql3 = " insert into crm_site_contact(site_id, contact_name, contact_email, remote_ip, created, contact_phone_no, contact_fax_no) value('$_site_id', '".trim($row2["Contact_Person2"])."', '".$row2["email"]."', '127.0.0.1', NOW(), '$contact_phone_no',  '$contact_fax_no')";	
									mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
									$rowret = mysql_query( $_sql3); 
									echo "($rowret)$_sql3<BR>";
								} 
							
							}

									
				}
			
			
			$_row_id ++;

	} 
	

	echo "<h1>ERROR >>$_errorrow_id</h1>";

	_close($_connection);
 

?>

<script>
	window.location = "load_product_brand.php";
</script>