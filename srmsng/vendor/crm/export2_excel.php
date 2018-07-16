<?
		
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/2.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header("Pragma: public");
	
	if(session_id()=="")
		session_start();
	
	if(!get_magic_quotes_gpc()){
		//$_REQUEST = array_map("addslashes", $_REQUEST);
	}
	
	include("conf/define-config.php");
	include("conf/icon-config.php");  
	include("conf/url-config.php"); 
	
	$__LANGUAGE = "TH";
	
	include("_libs/cache-kit.php");
	global $cache_folder;
	$cache_folder = "_cache/";
				 
	include("_classes/AbstractBaseService.class.php");
	include("_classes/users/UserService.class.php");
	
	include("_classes/companys/EmployeeService.class.php");
	include("_classes/masters/CustomerService.class.php");
	
	include("_libs/commons/mysql_class.php");
	include("_libs/commons/html_generator.class.php");	
	include("_libs/commons/date_class.php");	
	include("_libs/xmls/xml.class.php");
	

	$_compgrp = $_REQUEST['_compgrp'];
	if($_compgrp=="")
		$_compgrp = "news";
	$_component = $_REQUEST['_comp'];
	if($_component=="")
		$_component = "news";
	$_action = $_REQUEST['_action'];
	if($_action=="")
		$_action = "view";
		
	$_connection = _connection();
	mysql_select_db($GLOBALS["__MYSQLDB"]["DB_NAME"]) or die(mysql_error());
		
	require_once "_libs/phpexcel/class.writeexcel_workbook.inc.php";
	require_once "_libs/phpexcel/class.writeexcel_worksheet.inc.php";

	$__filebinder = "modules/{$GLOBALS["_compgrp"]}/{$GLOBALS["_component"]}/ui/metadata/{$_REQUEST["_bindername"]}.excel.defs.php";
	//echo "__filebinder[{$__filebinder}]<br>";
	
	include($__filebinder);
	$_sql = $__defs_config["query"]["sql"];
	
	$_conditions = $__defs_config["query"]["condition"];
	
			if ($_conditions)
				foreach ($_conditions as $key => $stmt){ 
						$_streval = "if($key){ \$_sql.=\"$stmt\"; }";
						//echo "[$_streval]<br>"; 

						eval($_streval);				    
				}
			
				$_default_orderby = $__defs_config["query"]["default-orderby"];
				if ($_default_orderby){
						$_streval = " \$_sql.=\"ORDER BY \$_default_orderby\";	";  
						eval($_streval);	
				}


	$__Fields = array_keys($__defs_config["fields"]);
	
	$token = md5(uniqid(rand(), true)); 
	
	$fname= $HTTP_SERVER_VARS['PATH_INFO']."_tmps/$token.xls";
    $workbook = new writeexcel_workbook($fname);                          
	
	$worksheet =& $workbook->addworksheet("pm_".str_replace("/", "-", $_REQUEST["start_date"]));
	
	$border1 =& $workbook->addformat();  
	$border1->set_align('vcenter');
	$border1->set_align('vcenter');
	$border1->set_border(1);
	
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$__databasename][$_sql]" . mysql_error());
	$_sql_rets = mysql_query($_sql) or die("Invalid query: [$sql]" . mysql_error());	
		
	$_rows = 1;
	
	/* แสดงผลจากข้อมูลที่ Query ขึ้นมา */
	
	$_metanames = array();

	while ($row = mysql_fetch_array($_sql_rets)){	  
					
					$i=0;
					
					while ($i < mysql_num_fields($_sql_rets)) { 
							
							$meta = mysql_fetch_field($_sql_rets, $i);			
							if($meta->type!="datetime" && $meta->type!="date")
								$worksheet->write($_rows, $i, trim($row[$meta->name]), $border1);
							else if($meta->type!="date")
								$worksheet->write($_rows, $i, mysql2Date($row[$meta->name]), $border1);
							else
								$worksheet->write($_rows, $i, trim($row[$meta->name]), $border1);
																	
							$_metanames[$i] = $meta->name;

							$i++;
							
					}      
					
					$_rows++; 
										
	}   
	
	mysql_free_result($_sql_rets);
	
	for($n=0;$n<sizeof($_metanames);$n++){
		if(!empty($__defs_config["fields"][$_metanames[$n]]["displayname"]))
			$worksheet->write(0, $n, $__defs_config["fields"][$_metanames[$n]]["displayname"], $border1);
		else
			$worksheet->write(0, $n, $_metanames[$n], $border1);
	}
	
	$workbook->close();
					
header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment; filename=".basename("pm_file.xls").";");
                header("Content-Transfer-Encoding: binary ");
                header("Content-Length: ".filesize($fname));
                readfile($fname); 
                unlink($fname);                        
                exit();             

?>