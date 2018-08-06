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
	

	$_sql="SELECT * from end_cus  order by Order_Ref DESC ";	
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	echo "end_cus  have $num_rows rows<BR>";
	$_row_id = 1;
	$_errorrow_id = 0; 
	
	
	$customers = array();
	while ($row = mysql_fetch_array($_sql_rets)){	
			
			$card_no = $row["CODE"];
			$contract_condition_code = $row["TOC"];
			$effective_dt = $row["STARTMCD"]; 
			$expire_dt = $row["ENDMCD"]; 

			$ref_order_mc = $row["Ref_Order_MC"];
			$contract_no = $row["Order_Ref"]; 

			echo "<h1>\$contract_no[$contract_no]</h1>";

			$customer_name_th = $row["CUS_THAI"]; 
			$customer_name_en = $row["CUS"]; 
			
			$_sql2="SELECT customer_code, customer_id from crm_customer where customer_name_th = '".trim($row["CUS_THAI"])."' ";	
			echo "<h2>$_sql</h2><br>";
			mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
			$_sql2_rets = mysql_query( $_sql2);
			if($row2 = mysql_fetch_array($_sql2_rets)){	
				$customer_code = $row2["customer_code"]; 
				$customer_id = $row2["customer_id"]; 
			}
			
			echo "\$code[$code]\$toc[$toc]\$startmcd[$startmcd]\$endmcd[$endmcd]<br>";
			
			//$customer_code = $customerCodes[trim($row["CUS_THAI"]."-".$row["CUS"])];
			//$customer_id = $customers[$customer_code];			
			//echo "<h1>[".trim($row["CUS_THAI"]."-".$row["CUS"])."]\$customer_id($customer_id)\$customer_code($customer_code)</h1>";
			 

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
				
				$sent_plan_to_customer = $row["sent plan"]; 
				if($sent_plan_to_customer=="YES")
					$sent_plan_to_customer = "Y";
				else
					$sent_plan_to_customer = "N";

			$need_doc = $row["Doc"]; 
			$contract_condition_desc = $row["Note"]; 

			$service_time = "10";
			$service_peroid = $row["Period"]; 

			$_sql2 = " insert into crm_contract(customer_id, customer_code, customer_name_th, card_no, effective_dt, expire_dt, remote_ip, created, pm_1, pm_2, pm_3, pm_4, pm_5, pm_6, pm_7, pm_8, pm_9, pm_10, pm_11, pm_12, ald_pm_1, ald_pm_2, ald_pm_3, ald_pm_4, ald_pm_5, ald_pm_6, ald_pm_7, ald_pm_8, ald_pm_9, ald_pm_10, ald_pm_11, ald_pm_12, rs_pm_1, rs_pm_2, rs_pm_3, rs_pm_4, rs_pm_5, rs_pm_6, rs_pm_7, rs_pm_8, rs_pm_9, rs_pm_10, rs_pm_11, rs_pm_12, contract_condition_code, ref_order_mc, contract_no, part_included, include_capacitor, includ_batt, sent_plan_to_customer, need_doc, contract_condition_desc, service_time, service_peroid) value('$customer_id', '$customer_code', '$customer_name_th', '$card_no', '$effective_dt', '$expire_dt' , '127.0.0.1', NOW(), '$pm_1', '$pm_2', '$pm_3', '$pm_4', '$pm_5', '$pm_6', '$pm_7', '$pm_8', '$pm_9', '$pm_10', '$pm_11', '$pm_12', '$ald_pm_1', '$ald_pm_2', '$ald_pm_3', '$ald_pm_4', '$ald_pm_5', '$ald_pm_6', '$ald_pm_7', '$ald_pm_8', '$ald_pm_9', '$ald_pm_10', '$ald_pm_11', '$ald_pm_12', '$rs_pm_1', '$rs_pm_2', '$rs_pm_3', '$rs_pm_4', '$rs_pm_5', '$rs_pm_6', '$rs_pm_7', '$rs_pm_8', '$rs_pm_9', '$rs_pm_10', '$rs_pm_11', '$rs_pm_12', '$contract_condition_code', '$ref_order_mc', '$contract_no', '$part_included', '$include_capacitor', '$includ_batt', '$sent_plan_to_customer', '$need_doc', '$contract_condition_desc', '$service_time', '$service_peroid') ";	
			mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
			$rowret = mysql_query( $_sql2);
			$_contract_id = mysql_insert_id();
			echo "\$_customer_id($_contract_id)<BR>";
			echo "($rowret)$_sql2<BR>";
			
              if($rowret==1){
						
						echo "<h1>เพิ่มผู้ติดต่อ</h1>";
						
						$contact_phone_no = $row["TEL"];
						if($row["TEL2"]!="")
							$contact_phone_no = $row["TEL"].", ".$row["TEL2"];

						$contact_fax_no = $row["FAX"];
						if($row["FAX2"]!="")
							$contact_fax_no = $row["FAX"].", ".$row["FAX2"];
								
						if($row["Contact_Person"]!=""){
							$_sql3 = " insert into crm_contract_contact(contract_id, contact_name, contact_email, remote_ip, created, contact_phone_no, contact_fax_no) value('$_contract_id', '".trim($row["Contact_Person"])."', '".$row["email"]."', '127.0.0.1', NOW(), '$contact_phone_no',  '$contact_fax_no')";	
							mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
							$rowret = mysql_query( $_sql3); 
							echo "($rowret)$_sql3<BR>";
						}
						
						if($row["Contact_Person2"]!=""){
							$_sql3 = " insert into crm_contract_contact(contract_id, contact_name, contact_email, remote_ip, created, contact_phone_no, contact_fax_no) value('$_contract_id', '".trim($row["Contact_Person2"])."', '".$row["email"]."', '127.0.0.1', NOW(), '$contact_phone_no', '$contact_fax_no')";		
							mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
							$rowret = mysql_query( $_sql3); 
							echo "($rowret)$_sql3<BR>";
						} 
					
				     
			}
			

	}
	

	$_sql4 = "SELECT distinct CODE, SER FROM end_cus where SER <>'' AND SER is not null  ";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql4);
	while ($row = mysql_fetch_array($_sql_rets)){	
			
			$card_no = $row["CODE"];
			$SER = $row["SER"];
			
			echo "\$card_no($card_no)\$SER($SER)<br>";
			$_sql5 = "  update crm_product_site set card_no = '$card_no' where serial_no = '$SER' ";	
			mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
			$rowret = mysql_query( $_sql5);
			echo "($rowret)$_sql5<BR>";
			
	}
	


	_close($_connection);
	
	
?>

<script>
	window.location = "load_site.php";
</script>