<? 
	class App_Template{
		function __SERVICE_DOMAIN(){
			$__SERVICE_DOMAIN_FOLDER = trim($_SERVER["HTTP_HOST"]);
			//$serviceDomain->servicedomainid = $__SERVICE_DOMAIN_FOLDER;
			
			//$GLOBALS['serviceDomainService']->create( ,$GLOBALS['_connection']);
			
			//echo "1. \$__SERVICE_DOMAIN_FOLDER[$__SERVICE_DOMAIN_FOLDER]<br>";
			if($_SERVER['SERVER_ADDR']=="127.0.0.1"){
				$_stacks = array(".devcom", ".devnet", ".devco", "/");
				$_needle = array(".com", ".net", ".co", "");
				$__SERVICE_DOMAIN_FOLDER = str_replace($_stacks, $_needle, $__SERVICE_DOMAIN_FOLDER);
			}                   
			
			$dir = trim(dirname($GLOBALS['_WEBROOT_DIR']."/".$__SERVICE_DOMAIN_FOLDER."/index.html"),'/').'/';
			if(file_exists($dir)){
				App_Template::forcePath($dir);     
			}
			
			return str_replace("www.", "", $__SERVICE_DOMAIN_FOLDER);
		}
		function forcePath($path){
				//echo "!!!!!!!! path[$path]<br>";
				$dir = trim(dirname($path),'/').'/';
				if($dir!="/")
					App_Template::forceDirectory($dir);
		}
		
		function forceDirectory($dir){                      
				//echo "create[$dir]<br>";
			return is_dir($dir) or (App_Template::forceDirectory(dirname($dir)) and mkdir($dir, 0777) and chmod($dir, 0777));   
		} 
	
		
		function __PRODUCT_CATEGORY_SEO_URL($category){
			echo "/Product/".$category["category_id"]."/".App_Template::__url_rewrite($category["category_name"]).".html";
		}
		function __FORUM_CATEGORY_SEO_URL($category){
			echo "/Webboard/".$category["forum_category_id"]."/".App_Template::__url_rewrite($category["forum_category_name"]).".html";
		}
		function __ARTICLE_CATEGORY_SEO_URL($category){
			echo "/Articles/".$category["article_category_id"]."/".App_Template::__url_rewrite($category["article_category_name"]).".html";
		}

		function __GALLERY_CATEGORY_SEO_URL($category){
			echo "/Photo_Gallery/".$category["gallery_category_id"]."/".App_Template::__url_rewrite($category["gallery_category_name"]).".html";
		}
		
		function __url_rewrite($__str){
			$__str = str_replace("'", "", $__str);
			$__str = str_replace(" ", "_", $__str);
			return $__str;
		}

	}


?>