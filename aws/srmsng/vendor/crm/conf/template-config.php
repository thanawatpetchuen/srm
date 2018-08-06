<? 
	
	$__SERVICE_DOMAIN_TEMPLATE_HEADER = "{$__SERVICE_DOMAIN_FOLDER}/template-includes/header.php";
	if(!file_exists($__SERVICE_DOMAIN_TEMPLATE_HEADER)){
		$__SERVICE_DOMAIN_TEMPLATE_HEADER = "{$__SERVICE_DOMAIN_APP_FOLDER}/template-includes/header.php";
	}
	
	$__SERVICE_DOMAIN_TEMPLATE_TOPNAV = "{$__SERVICE_DOMAIN_FOLDER}/template-includes/topnav.php";
	if(!file_exists($__SERVICE_DOMAIN_TEMPLATE_TOPNAV)){
		$__SERVICE_DOMAIN_TEMPLATE_TOPNAV = "{$__SERVICE_DOMAIN_APP_FOLDER}/template-includes/topnav.php";
	}
	
	$__SERVICE_DOMAIN_TEMPLATE_COLUME_LEFT = "{$__SERVICE_DOMAIN_FOLDER}/template-includes/colume_left.php";
	if(!file_exists($__SERVICE_DOMAIN_TEMPLATE_COLUME_LEFT)){
		$__SERVICE_DOMAIN_TEMPLATE_COLUME_LEFT = "{$__SERVICE_DOMAIN_APP_FOLDER}/template-includes/colume_left.php";
	}
	
	$__SERVICE_DOMAIN_TEMPLATE_COLUME_RIGHT = "{$__SERVICE_DOMAIN_FOLDER}/template-includes/colume_right.php";
	if(!file_exists($__SERVICE_DOMAIN_TEMPLATE_COLUME_RIGHT)){
		$__SERVICE_DOMAIN_TEMPLATE_COLUME_RIGHT = "{$__SERVICE_DOMAIN_APP_FOLDER}/template-includes/colume_right.php";
	}
	
	$__SERVICE_DOMAIN_TEMPLATE_FOOTER = "{$__SERVICE_DOMAIN_FOLDER}/template-includes/footer.php";
	if(!file_exists($__SERVICE_DOMAIN_TEMPLATE_FOOTER)){
		$__SERVICE_DOMAIN_TEMPLATE_FOOTER = "{$__SERVICE_DOMAIN_APP_FOLDER}/template-includes/footer.php";
	}
	
?>