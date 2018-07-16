<? 
	
	$user = acmeCache::fetch(md5(session_id())."_ADMIN_SSO_USERID", 60*30);
	$employee = acmeCache::fetch(md5(session_id())."_EMPLOYEE_SSO_EMPLOYEEID", 60*30);
			
	//$labelEmployeeTitles = acmeCache::fetch("LABEL_CUSTOMERTITLES_TH", 60*60*60);
	if(empty($labelEmployeeTitles) || sizeof($labelEmployeeTitles)==0){		
		if(get_class($listOfValueService)!="ListOfValueService")
			include("_classes/systems/ListOfValueService.class.php");
		$listEmployeeTitles = $listOfValueService->getLists(array("list_type"=>"customer_title_lv"), $_connection);
		
		for($i=0;$i<sizeof($listEmployeeTitles);$i++){
			$labelEmployeeTitles[$listEmployeeTitles[$i]->code] = $listEmployeeTitles[$i]->name_t;
		}
		
		acmeCache::save("LABEL_CUSTOMERTITLES_TH", $labelEmployeeTitles);

	}
	
	if(empty($user)){

		if(empty($_SESSION[md5(session_id())."-".$_SERVICE_DOMAIN_ID."_ADMIN_SSO_USERID"])){
			if($_compgrp!="ssos" && $_component!="users" && $_action!="signin"){
				header("Location: index.php?_compgrp=ssos&_comp=users&_action=signin");
			}
		}else{
			$user = $userService->get(($_SESSION[md5(session_id())."-".$_SERVICE_DOMAIN_ID."_ADMIN_SSO_USERID"]),$_connection);
		}
	}else{
		$_SESSION[md5(session_id())."-".$_SERVICE_DOMAIN_ID."_ADMIN_SSO_USERID"] = ($user->user_id);
		if($user->user_type!="C" && empty($employee) && $user->user_id!=1){
				$employee = $employeeService->get($user->employee_id,	$_connection);
				acmeCache::save(md5(session_id())."_EMPLOYEE_SSO_EMPLOYEEID",  $employee);
		}else if($user->user_type=="C"){
				$customer = $customerService->get($user->employee_id,	$_connection);
				acmeCache::save(md5(session_id())."_CUSTOMER_SSO_CUSTOMERID",  $customer);
		}
	}
	
	acmeCache::save(md5(session_id())."_ADMIN_SSO_USERID",  $user);
	$__SSO_USER_SECURITY = ($_SESSION[md5(session_id())."-".$_SERVICE_DOMAIN_ID."_ADMIN_SSO_USERID"]);
	//echo "1. __SSO_USER_SECURITY >>".$GLOBALS["__SSO_USER_SECURITY"]."<BR>";
	
	if($user->user_type=="C")
		$_REQUEST["CUSTOMER_CODE"] = $user->user_code;
	
	$_REQUEST["SSO_DEPARTMENT_ID"] = $employee->department_id;
	$_REQUEST["SSO_DIVISION_ID"] = $employee->division_id;
	$_REQUEST["SSO_SECTION_ID"] = $employee->section_id;
	$_REQUEST["SSO_POSITION_ID"] = $employee->position_id;
	$_REQUEST["SSO_EMPLOYEE_ID"] = $employee->employee_id; 

	/*
	echo "SSO_DIVISION_ID >>".$_REQUEST["SSO_DIVISION_ID"]."<BR>";
	echo "division_id >>".$employee->division_id."<BR>";
	
	print_r($employee);

	$_REQUEST["SSO_DIVISION_ID"] = $employee->division_id;
	echo "SSO_DIVISION_ID >>".$_REQUEST["SSO_DIVISION_ID"]."<BR>";
	echo "division_id >>".$employee->division_id."<BR>";
	*/
		

?>