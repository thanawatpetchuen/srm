<? 		
		
		//header('Content-Type: text/css');  
		header("Cache-Control: no-cache, must-revalidate"); // HTTP/2.1
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		header("Pragma: public");
				
		if(session_id()=="")
			session_start();
		
		include("conf/define-config.php");
		include("App_Template.inc.php");
		
		include("_libs/template_class.php");

		include("_libs/commons/mysql_class.php");
		include("_libs/commons/html_generator.class.php");	
		include("_libs/commons/date_class.php");

		include("_libs/cache-kit.php");
		global $cache_folder;
		$cache_folder = "_cache/";
		
		include("_classes/AbstractBaseService.class.php");
		include("_classes/layouts/LayoutStyleService.class.php");
		
		$__SERVICE_DOMAIN_FOLDER =App_Template::__SERVICE_DOMAIN();
		//echo "\$__SERVICE_DOMAIN_FOLDER[$__SERVICE_DOMAIN_FOLDER]<br>";
		$__SERVICE_DOMAIN_FOLDER = "{$__DOMAIN_ROOT}/{$__SERVICE_DOMAIN_FOLDER}";
		$__DEFINE_CONFIG = "{$__SERVICE_DOMAIN_FOLDER}/conf/servicedomain-define-config.php"; 
	
		/*กำหนดค่าเริ่มต้นสำหรับแต่ละ __SERVICE_DOMAIN */
		include($__DEFINE_CONFIG);
		
		$layoutStyle_Lists = acmeCache::fetch("{$__SERVICE_DOMAIN_ID}LAYOUT_STYLE_LISTS", 60*60*24*30);
		if(empty($layoutStyle_Lists) || sizeof($layoutStyle_Lists)==0){
			$_connection = _connection();
			$layoutStyle_Lists = $layoutStyleService->getProperties(NULL, $_connection);
			acmeCache::save("{$__SERVICE_DOMAIN_ID}LAYOUT_STYLE_LISTS", $layoutStyle_Lists);
			_close($_connection);
		}
		
		//ob_start();
		//echo "/*	$__APP_LAYOUT	*/\n";
		//include("_themes/weblayouts/$__APP_LAYOUT/style.css");
		//$_html = ob_get_contents();
		//ob_end_clean();
		/*
		$_stack = array("\n", "   ");
		$_neddle = array("", " ");
		echo str_replace($_stack, $_neddle, $_html);
		*/
		
		 
		$_html = acmeCache::fetch("{$__SERVICE_DOMAIN_ID}LAYOUT_STYLE_LAYOUT", 60*60*24*30);
		if(empty($_html) || sizeof($_html)==0){
			$_html = "/*	  generate date ".date("d/m/Y h:i:s A")."	*/";
			$_keys = array_keys($layoutStyle_Lists);
	
			$page_class = new page_class();
			$page_class->get_language();
			for($k=0;$k<sizeof($_keys);$k++){
				$page_class->blocks[$_keys[$k]]   = $layoutStyle_Lists[$_keys[$k]]->layout_style_value;
			}
			$page_class->template = "_themes/weblayouts/$__APP_LAYOUT/style.css";
			$page_class->construct_page(false);
			$_html .= $page_class->string_page();                			
			acmeCache::save("{$__SERVICE_DOMAIN_ID}LAYOUT_STYLE_LAYOUT", $_html);
		}
		echo trim($_html);
?>