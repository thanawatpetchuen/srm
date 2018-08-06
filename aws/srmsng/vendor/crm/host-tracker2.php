host-tracker2

<? 
	
	ignore_user_abort(true);
	set_time_limit(0);
	
	include("conf/define-config.php");
	include("conf/icon-config.php");  
	include("conf/url-config.php"); 
	include("_libs/commons/mysql_class.php");
	include("_libs/commons/html_generator.class.php");	
	include("_libs/commons/date_class.php");	
	include("_libs/xmls/xml.class.php");
	
	include("_classes/AbstractBaseService.class.php");			
		 
	include("_classes/systems/ReqCronService.class.php");				
	include("_classes/companys/SectionService.class.php");	
	
	$_connection = _connection();
	mysql_select_db($GLOBALS["__MYSQLDB"]["DB_NAME"]) or die(mysql_error());

	$today = getdate();
	print_r($today);

	if((date("d")*1)==23 && ($today["hours"]>=18 || $today["hours"]<=7)){
		//include("cronjobs/contracts/1.php"); 
	}else{
		echo "<h1>1.php Don't RUN....</h1>";
	}
		
	if($today["hours"]>=18 || $today["hours"]<=7){
		echo "include 2.php<br>";
		//include("cronjobs/contracts/2.php"); 
	}else{
		echo "Don't RUN....";
	}
	
	
	/*
	update FROM crm_job_order set assign_dt = assign2_dt
WHERE assign_dt IS NULL 
AND assign2_dt IS NOT NULL 

	*/
	
	echo "SUCCESS++.";
	
	_close($_connection);
	
	exit;
	
?>