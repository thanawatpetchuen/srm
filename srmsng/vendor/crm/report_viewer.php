<? 
	
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header("Pragma: public");
	
	if(session_id()=="")
		session_start();
	
	include("conf/define-config.php");
	include("conf/icon-config.php");
	
	include("_libs/cache-kit.php");
	include("_libs/commons/pagenavigator_class.php");
	
	global $cache_folder;	
	$cache_folder = "_cache/".date("d_m_Y")."/";
	
	include("_classes/AbstractBaseService.class.php");
		
	include("_libs/commons/mysql_class.php");
	include("_libs/commons/html_generator.class.php");	
	include("_libs/commons/date_class.php");
	
	$_connection = _connection();

	$_compgrp = $_REQUEST['_compgrp'];
	if($_compgrp=="")
		$_compgrp = "reportviewers";
	$_component = $_REQUEST['_comp'];
	if($_component=="")
		$_component = "hotlines";
	$_action = $_REQUEST['_action'];
	if($_action=="")
		$_action = "viewer";
	
	$__SERVICE_DOMAIN_ID = 1;
	$_SSO_USER_SECURITY = 1;
	$_LANGUAGE = "TH";

	$__ACTION_COMMON_FILE = "modules/$_compgrp/$_component/action/common.inc.php";
	if(!file_exists($__ACTION_COMMON_FILE)){
		forcePath($__ACTION_COMMON_FILE);        
		$file = fopen($__ACTION_COMMON_FILE, 'w');	
		fwrite($file, "<? \n\n?>"); fclose($file);
	}

	$__ACTION_FILE = "modules/$_compgrp/$_component/action/$_action.php";
	if(!file_exists($__ACTION_FILE)){
		forcePath($__ACTION_FILE);        
		$file = fopen($__ACTION_FILE, 'w');	
		fwrite($file, "<? \n\n?>"); fclose($file);
	}

	$__PAGE = "modules/$_compgrp/$_component/ui/$_action.html";
	if(!file_exists($__PAGE)){
		forcePath($__PAGE);        
		$file = fopen($__PAGE, 'w');	
		fwrite($file, $__PAGE); fclose($file);
	}
	
	$__PAGE_HEADER = "modules/$_compgrp/$_component/ui/report_header.html";
	if(!file_exists($__PAGE_HEADER)){
		forcePath($__PAGE_HEADER);        
		$file = fopen($__PAGE_HEADER, 'w');	
		fwrite($file, $__PAGE_HEADER); fclose($file);
	}

	$__PAGE_FOOTER = "modules/$_compgrp/$_component/ui/report_footer.html";
	if(!file_exists($__PAGE_FOOTER)){
		forcePath($__PAGE_FOOTER);        
		$file = fopen($__PAGE_FOOTER, 'w');	
		fwrite($file, $__PAGE_FOOTER); fclose($file);
	}

	 /**
	 * Logiciel : exemple d'utilisation de HTML2PDF
	 * 
	 * Convertisseur HTML => PDF, utilise TCPDF 
	 * Distribu้ sous la licence LGPL. 
	 *
	 * @author		Laurent MINGUET <webmaster@html2pdf.fr>
	 * 
	 * isset($_GET['vuehtml']) n'est pas obligatoire
	 * il permet juste d'afficher le r้sultat au format HTML
	 * si le param่tre 'vuehtml' est pass้ en param่tre _GET
	 */
	
	/*
 	// r้cup้ration du contenu HTML
 	$content = file_get_contents(dirname(__FILE__).'/utf8test.html');
 	
	//$content = '<page style="font-family: freeserif"><br />'.nl2br($content).'</page>'; 
	$content = nl2br($content);
	$content = UTF8ToEntities($content);
	
	$content = '<page style="font-family: freeserif;font-size:18px;">'.$content.'</page>';
	*/
	
	ob_start();

	include($__ACTION_COMMON_FILE);
	include($__ACTION_FILE);
	
	$pageNavigator1 = new PageNavigator(1, $_recordPerPage, 5, 1, array());
	$pageNavigator1->setRecordCount(sizeof($report_lists));
	
	//echo "getPagecount ".$pageNavigator1->getPagecount()."<BR>";
	echo "<style>";
	include("reportviewer/_templates/styles.css");
	echo "</style>";
	
	$_display_items = 0;
	if($pageNavigator1->getPagecount()>0){
		
		for($_page = 1;$_page<=$pageNavigator1->getPagecount();$_page++){	 			
			
			$pageNavigator2 = new PageNavigator($_page, $_recordPerPage, 5, 1, array());
			$pageNavigator2->setRecordCount(sizeof($report_lists));

			echo "<page style=\"font-family: freeserif;font-size:14px;\">"; 
			include($__PAGE_HEADER);		 
			include($__PAGE);		
			include($__PAGE_FOOTER);		
			echo "</page>";
		}
	
	}else{
		echo "<page style=\"font-family: freeserif;font-size:14px;\">"; 
		include($__PAGE_HEADER);		
		//include($__PAGE);		
		include($__PAGE_FOOTER);		
		echo "</page>";
	}
	
	$_html = ob_get_contents();
	
	ob_end_clean(); 
	
	//echo $_html;
	//exit;

	$_html = UTF8ToEntities(trim($_html));
		
	//$content = "<page style=\"font-family: freeserif;font-size:14px;\">".trim($_html)."</page>";
	$content = trim($_html);
	
	require_once(dirname(__FILE__).'/reportviewer/html2pdf.class.php');
	try{
		$html2pdf = new HTML2PDF('L','A4','th', true, 'UTF-8');
		$html2pdf->pdf->SetDisplayMode('real'); 
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output('utf8.pdf');
	}
	catch(HTML2PDF_exception $e) { echo $e; }
		
	_close($_connection);
	
	function UTF8ToEntities ($string) {
    /* note: apply htmlspecialchars if desired /before/ applying this function
    /* Only do the slow convert if there are 8-bit characters */
    /* avoid using 0xA0 (\240) in ereg ranges. RH73 does not like that */
    if (! preg_match("[\200-\237]", $string) and ! preg_match("[\241-\377]", $string))
        return $string;
    
    // reject too-short sequences
    $string = preg_replace("/[\302-\375]([\001-\177])/", "&#65533;\\1", $string); 
    $string = preg_replace("/[\340-\375].([\001-\177])/", "&#65533;\\1", $string); 
    $string = preg_replace("/[\360-\375]..([\001-\177])/", "&#65533;\\1", $string); 
    $string = preg_replace("/[\370-\375]...([\001-\177])/", "&#65533;\\1", $string); 
    $string = preg_replace("/[\374-\375]....([\001-\177])/", "&#65533;\\1", $string); 
    
    // reject illegal bytes & sequences
        // 2-byte characters in ASCII range
    $string = preg_replace("/[\300-\301]./", "&#65533;", $string);
        // 4-byte illegal codepoints (RFC 3629)
    $string = preg_replace("/\364[\220-\277]../", "&#65533;", $string);
        // 4-byte illegal codepoints (RFC 3629)
    $string = preg_replace("/[\365-\367].../", "&#65533;", $string);
        // 5-byte illegal codepoints (RFC 3629)
    $string = preg_replace("/[\370-\373]..../", "&#65533;", $string);
        // 6-byte illegal codepoints (RFC 3629)
    $string = preg_replace("/[\374-\375]...../", "&#65533;", $string);
        // undefined bytes
    $string = preg_replace("/[\376-\377]/", "&#65533;", $string); 

    // reject consecutive start-bytes
    $string = preg_replace("/[\302-\364]{2,}/", "&#65533;", $string); 
    
    // decode four byte unicode characters
    $string = preg_replace(
        "/([\360-\364])([\200-\277])([\200-\277])([\200-\277])/e",
        "'&#'.((ord('\\1')&7)<<18 | (ord('\\2')&63)<<12 |" .
        " (ord('\\3')&63)<<6 | (ord('\\4')&63)).';'",
    $string);
    
    // decode three byte unicode characters
    $string = preg_replace("/([\340-\357])([\200-\277])([\200-\277])/e",
"'&#'.((ord('\\1')&15)<<12 | (ord('\\2')&63)<<6 | (ord('\\3')&63)).';'",
    $string);
    
    // decode two byte unicode characters
    $string = preg_replace("/([\300-\337])([\200-\277])/e",
    "'&#'.((ord('\\1')&31)<<6 | (ord('\\2')&63)).';'",
    $string);
    
    // reject leftover continuation bytes
    $string = preg_replace("/[\200-\277]/", "&#65533;", $string);
    
    return $string;
}


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

?>