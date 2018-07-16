<? 
	
	include("../conf/define-config.php");
	include("../conf/icon-config.php");  
	include("../conf/url-config.php"); 
	include("../_libs/commons/mysql_class.php");
	include("../_libs/commons/html_generator.class.php");	
	include("../_libs/commons/date_class.php");	
	include("../_libs/xmls/xml.class.php");
	
	$_connection = _connection();
	
	$_sql = "TRUNCATE TABLE crm_customer ";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	echo "delete($_sql_rets)<br>";
	$_sql = "TRUNCATE TABLE crm_customer_contact ";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	echo "delete($_sql_rets)<br>";
	
	
	$_sql = "TRUNCATE TABLE crm_contract ";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	echo "delete($_sql_rets)<br>";
			
	$_sql=" select distinct CUS_THAI, CUS, Province from end_cus where CUS_THAI  is not null AND CUS is not null order by CUS_THAI ";	
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	echo "end_cus  have $num_rows rows<BR>";
	$_row_id = 1;
	$_errorrow_id = 0;
	
	while ($row = mysql_fetch_array($_sql_rets)){	
			
			$cus_code = "CUS-".str_pad($_row_id, 4, "0", STR_PAD_LEFT);
			
			echo "CUS_THAI >>".trim($row["CUS_THAI"])."<br>";
			echo "CUS >>".trim($row["CUS"])."<br>"; 
			
			$_sql2 = " insert into crm_customer(customer_code, customer_name_th, customer_name_en, remote_ip, created, province_code) value('$cus_code', '".addslashes(trim($row["CUS_THAI"]))."', '".addslashes(trim($row["CUS"]))."', '127.0.0.1', NOW(), '".$row["Province"]."')";	
			mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
			$rowret = mysql_query( $_sql2);
			$_customer_id = mysql_insert_id();
			echo "\$_customer_id($_customer_id)<BR>";
			echo "($rowret)$_sql2<BR>";
			
			if($rowret==1){

					$_sql2=" select * from end_cus where CUS_THAI = '{$row["CUS_THAI"]}' AND CUS = '{$row["CUS"]}' ";	
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
							$_sql3 = " insert into crm_customer_contact(customer_id, contact_name, contact_email, remote_ip, created, contact_phone_no, contact_fax_no) value('$_customer_id', '".trim($row2["Contact_Person"])."', '".$row2["email"]."', '127.0.0.1', NOW(), '$contact_phone_no',  '$contact_fax_no')";	
							mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
							$rowret = mysql_query( $_sql3); 
							echo "($rowret)$_sql3<BR>";
						}
						
						if($row2["Contact_Person2"]!=""){
							$_sql3 = " insert into crm_customer_contact(customer_id, contact_name, contact_email, remote_ip, created, contact_phone_no, contact_fax_no) value('$_customer_id', '".trim($row2["Contact_Person2"])."', '".$row2["email"]."', '127.0.0.1', NOW(), '$contact_phone_no',  '$contact_fax_no')";	
							mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
							$rowret = mysql_query( $_sql3); 
							echo "($rowret)$_sql3<BR>";
						} 
						
					}
			}

			$_row_id++;

	} 
	

	echo "<h1>ERROR >>$_errorrow_id</h1>";

	_close($_connection);

?>

<script>
	window.location = "load_contract.php";
</script>  
