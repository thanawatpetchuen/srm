<? 
	
	include("../conf/define-config.php");
	include("../conf/icon-config.php");  
	include("../conf/url-config.php"); 
	include("../_libs/commons/mysql_class.php");
	include("../_libs/commons/html_generator.class.php");	
	include("../_libs/commons/date_class.php");	
	include("../_libs/xmls/xml.class.php");
	
	$_connection = _connection();
	
	$_sql = "TRUNCATE TABLE crm_product_brand ";
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	echo "delete($_sql_rets)<br>";
	


	$_sql="SELECT distinct Brand from end_cus  where Brand is not null ";	
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	echo "end_cus  have $num_rows rows<BR>";
	$_row_id = 1;
	$_errorrow_id = 0;
	
	while ($row = mysql_fetch_array($_sql_rets)){	
			
			$product_brand_code = trim($row["Brand"]);
			$product_brand_name_th = trim($row["Brand"]);
			$product_brand_name_en = trim($row["Brand"]);
			//$rated =$row["Rate"];
			//$capacity = 
			$product_type_id = 1;
			$product_brand_id = 1;

			$_sql5 = " insert into crm_product_brand(product_brand_code,product_brand_name_th, product_brand_name_en, product_type_id, remote_ip, created) value('$product_brand_code', '$product_brand_name_th', '$product_brand_name_en', '$product_type_id',  '127.0.0.1', NOW())";	
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
				$rowret = mysql_query( $_sql5); 
				if($rowret==1)
					echo "<h2>($rowret)$_sql5</h2><BR>";
				else
					echo "($rowret)$_sql5<BR>";
			$_row_id++;
			
	} 
	

	echo "<h1>ERROR >>$_errorrow_id</h1>";

	_close($_connection);

?>

<script>
	window.location = "load_product.php";
</script>
