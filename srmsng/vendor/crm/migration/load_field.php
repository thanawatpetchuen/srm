<? 
	
	include("../conf/define-config.php");
	include("../conf/icon-config.php");  
	include("../conf/url-config.php"); 
	include("../_libs/commons/mysql_class.php");
	include("../_libs/commons/html_generator.class.php");	
	include("../_libs/commons/date_class.php");	
	include("../_libs/xmls/xml.class.php");
	
	$_connection = _connection();
		
	$_sql="SELECT * from end_cus  where 1 ";	
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	echo "end_cus  have $num_rows rows<BR>";
	$_row_id = 1;
	$_errorrow_id = 0;
	
	if ($row = mysql_fetch_array($_sql_rets)){	
		
			$i=0;
			while ($i < mysql_num_fields($_sql_rets)) { 

					$meta = mysql_fetch_field($_sql_rets, $i);		
					$_name = strtolower($meta->name);
					echo $meta->name."(".str_replace(" ", "_", $_name).")\n<br>";
					
					$i++;
			}

	} 
	
	_close($_connection);
	
?> 