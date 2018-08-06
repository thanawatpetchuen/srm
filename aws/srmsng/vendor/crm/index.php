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
	include("_classes/systems/ListOfValueService.class.php");
	include("_classes/users/UserService.class.php");
	
	include("_classes/companys/EmployeeService.class.php");
	include("_classes/masters/CustomerService.class.php");
	
	include("_libs/commons/mysql_class.php");
	include("_libs/commons/html_generator.class.php");	
	include("_libs/commons/date_class.php");	
	include("_libs/xmls/xml.class.php");
		
	$__EXPIRE_DATE = "2500/01/01";
	
	$__SERVICE_DOMAIN_ID = 1;
			
	/*	��˹�����ͧ����	*/
	if(!empty($_REQUEST["_language"]))
		$_SESSION["SES_language"] = $_REQUEST["_language"];
	if(empty($_SESSION["SES_language"]))
		$_SESSION["SES_language"] = $__LANGUAGE;	
	$_LANGUAGE = $_SESSION["SES_language"];
	//echo "SES_language >>($_LANGUAGE) >>".$_SESSION["SES_language"]."<br>";
	
	$_connection = _connection();
	mysql_select_db($GLOBALS["__MYSQLDB"]["DB_NAME"]) or die(mysql_error());
		 
	/* ��ҹ table ������� �к� */
	$show_tables = "show tables ";       
	$table_result = mysql_query( $show_tables) or die(mysql_error());          
	//echo "�� TABLE �ӹǹ ".count($table_result)."<BR>";
	$_tabcnt = 0; 
	$__APP_TABLES = array();
	while ($row_table_result = mysql_fetch_array($table_result)){	      
		$__APP_TABLES[$_tabcnt] = $row_table_result[0];
		//echo "ALTER TABLE ".$row_table_result[0]." CHANGE remote_ip remote_ip  VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ";
		$_tabcnt++;
	}   

	/*
	//������Ѻ�ó�����¹ service domain id ������١������ server
	print_r($__APP_TABLES);
	for($i=0;$i<sizeof($__APP_TABLES);$i++){
		$_sql_for_domain = "UPDATE  ".$__APP_TABLES[$i]."  SET for_domain_id= $__SERVICE_DOMAIN_ID ";      		
		echo "table[".$__APP_TABLES[$i]."][$_sql_for_domain]<br>";
		$table_result = mysql_query($GLOBALS['__mysqldb']['mysql_dbname'], $_sql_for_domain) or die(mysql_error());          
	}
	*/


	/* End ��ҹ table ������� �к� */
	
	//$_REQUEST = array_map("strip_tags", $_REQUEST);
	
		 
	$_compgrp = $_REQUEST['_compgrp'];
	if($_compgrp=="")
		$_compgrp = "news";
	$_component = $_REQUEST['_comp'];
	if($_component=="")
		$_component = "news";
	$_action = $_REQUEST['_action'];
	if($_action=="")
		$_action = "view";
	
	include("sso_admincp_signin.inc.php");
	
	include("sidenav.inc.php");
		
	$__ACTION_FILE = "modules/$_compgrp/$_component/action/$_action.php";
	if(!file_exists($__ACTION_FILE)){
		forcePath($__ACTION_FILE);        
		$file = fopen($__ACTION_FILE, 'w');	
		fwrite($file, "<? \n\n?>"); fclose($file);
	}
	
	$__ACTION_COMMON = "modules/$_compgrp/$_component/action/common.inc.php";
	if(!file_exists($__ACTION_COMMON)){
		forcePath($__ACTION_COMMON);        
		$file = fopen($__ACTION_COMMON, 'w');	
		fwrite($file, "<? \n\n?>"); fclose($file);
	}
	
	$__LANGUAGE_PAGE = "modules/$_compgrp/$_component/language/language_$_LANGUAGE.php";
	if(!file_exists($__LANGUAGE_PAGE)){
		forcePath($__LANGUAGE_PAGE);        
		$file = fopen($__LANGUAGE_PAGE, 'w');	
		fwrite($file, "<? \n\n?>"); fclose($file);
	}
	
	$__PAGE = "modules/$_compgrp/$_component/ui/$_action.html";
	//echo "[$__PAGE]<br>";
	if(!file_exists($__PAGE)){
		forcePath($__PAGE);        
		$file = fopen($__PAGE, 'w');	
		fwrite($file, $__PAGE); fclose($file);
	}
	
	$_script = "modules/$_compgrp/$_component/scripts/$_component.js";
	if(!file_exists($_script)){
		forcePath($_script);        
		$file = fopen($_script, 'w');	
		fwrite($file, ""); fclose($file);
	}
	
	//ob_start();
	$_layout = "_themes/admincp/index.html";
	
	//echo "\$__ACTION_FILE[$__ACTION_FILE]<br>";

	include($__ACTION_COMMON);
	include($__ACTION_FILE);
	include($__LANGUAGE_PAGE); 
	
	//echo "\$__PAGE[$__PAGE]<br>";
	
	if(!empty($_layout))
		include($_layout);
	else if(empty($_layout) && !empty($__PAGE))
		include($__PAGE);
	
	//echo "\$__PAGE[$__PAGE]<br>";

	if(!empty($_layout)){
				 
		//echo "\$__ACTION_FILE[$__ACTION_FILE]<br>";
		//echo "\$__PAGE[$__PAGE]<br>";
				
		/*
		//$_html = ob_get_contents();
		//ob_end_clean();
		//echo $_html;
		
		
		print_r($_SESSION);
		echo "<br>";
		print_r($_REQUEST);
		echo "<br>";
		print_r($__LANGUAGES);
		echo "<br>";
		echo "memory[".memory_get_usage() . "]<br>";
		*/

	}
	
	if($__SSO_USER_SECURITY>0){
				
				$_sql = "select * from  sys_user_log where created_by ='$__SSO_USER_SECURITY' AND session_id = '".session_id()."' ";
				$_sql_rets = mysql_query( $_sql);
				$num_rows = mysql_num_rows($_sql_rets);
				if($num_rows==1){
					$_sql = "update sys_user_log  set logout_datetime = now(), created_by ='$__SSO_USER_SECURITY' where session_id = '".session_id()."' ";
					$rows = mysql_query( $_sql);
					//echo "($rows)$_sql<br>";
				}else{ 
					$_sql = "INSERT INTO sys_user_log (
			 user_log_id ,
			 session_id ,
			 login_datetime ,
			 logout_datetime ,
			 order_no ,
			 for_domain_id ,
			 publish_flag ,
			 approve_flag ,
			 global_flag ,
			 read_only_flag ,
			 sys_del_flag ,
			 remote_ip ,
			 language ,
			 created_by ,
			 created ,
			 last_upd_by ,
			 last_upd 
			)
			VALUES (
			 NULL ,'".session_id()."', NOW(), NOW(),'1','1','Y','N','N','Y','N','".$_SERVER['REMOTE_ADDR']."','TH','$__SSO_USER_SECURITY', NOW(),'$__SSO_USER_SECURITY', NOW() 
			)	 ";
					//echo "$_sql<br>";
					mysql_query($_sql);
				}

	}


	_close($_connection);
	

	function forcePath($path){
			//echo "!!!!!!!! path[$path]<br>";
			$dir = trim(dirname($path),'/').'/';
			if($dir!="/")
				forceDirectory($dir);
	}
		
	function forceDirectory($dir){                      
			//echo "create[$dir]<br>";
		return is_dir($dir) or (forceDirectory(dirname($dir)) and mkdir($dir, 0777) and chmod($dir, 0777));   
	} 
	function fileList($directory) { 
		$results = array();	 
		$handler = opendir($directory); 
		while ($file = readdir($handler)){
			if ($file != '.' && $file != '..'){ 
				if(is_file($directory."/".$file)){
					$results[] = "$directory/$file";
				}else{ 
					$results = array_merge((array)dirList($directory."/".$file),$results);
				} 
			}
		}			 
		closedir($handler);
		return $results;
} 
	
function dirList ($directory) {
		// create an array to hold directory list
		$results = array();	
		// create a handler for the directory
		$handler = opendir($directory);
		// keep going until all files in directory have been read
		while ($file = readdir($handler)){
			// if $file isn't this directory or its parent,
			// add it to the results array
							
			if ($file != '.' && $file != '..' && strpos($directory, "svn")==""){
				//echo "\$file - ".is_file($directory."/".$file)."<BR>";
				
				if(is_file($directory."/".$file)){
					$results[] = "$directory/$file";
				}else{
					//$results[] = array_merge((array)dirList($directory."/".$file),$results);
					$results = array_merge((array)dirList($directory."/".$file),$results);
				} 
			}
		}			 
		// tidy up: close the handler
			closedir($handler);
			return $results;
	}
	

?>