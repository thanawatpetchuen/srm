<? 

	class Init_Template{
	
		function initFileInclude($_SERVICE_DOMAIN, $objectService, $_filename){
								
				$_initfile = "_domains/".Init_Template::serviceDomain()."/".$_filename; 
				if(!file_exists($_initfile)){
					Init_Template::forcePath($_initfile);
					$file = fopen($_initfile, 'w');				
					fwrite($file, $_str); fclose($file);
					chmod($_initfile, 0777);
				}
				
				$file = fopen($_initfile, 'w');					
		
				if(!empty($objectService)){
					$_keys = array_keys($objectService->tableFields);
					if(!is_array($_keys)){
						echo "Error \$objectService >>".get_class($objectService)."<br>";
						exit;
					}
				}

				$_splitarray = array("order_no", "publish_flag", "global_flag", "read_only_flag", "for_domain_id", "remote_ip", "sys_del_flag", "created_by", "created", "last_upd_by", "last_upd", "access_level", "language");
				
				$_str = "<? PHP\n\n";
				for($i=0;$i<sizeof($_keys);$i++){
					if(!in_array($_keys[$i], $_splitarray))
						$_str .= "\t\$_REQUEST[\"".$_keys[$i]."\"]=\"".$_REQUEST[$_keys[$i]]."\";\n";
				}
				$_str .= "\n?>";
				
				fwrite($file, $_str); fclose($file);
				chmod($_initfile, 0777);
				
		}
		
		function initPageLanguage($_SERVICE_DOMAIN, $languageLists, $_filename){
				
				$_initfile = "_domains/".Init_Template::serviceDomain()."/".$_filename; 
				if(!file_exists($_initfile)){
					Init_Template::forcePath($_initfile);
					$file = fopen($_initfile, 'w');				
					fwrite($file, $_str); fclose($file);
					chmod($_initfile, 0777);
				}
				
				$file = fopen($_initfile, 'w');				
				
				if(!empty($objectLists)){
					$_keys = array_keys($objectLists);
					if(!is_array($_keys)){ 
						exit;
					}
				}
											
				$_str = "<? PHP\n\n";
				$_cnt = 0; 
				for($i=0;$i<sizeof($languageLists);$i++){   
					if($languageLists[$i]->publish_flag=="Y"){
						$_LANGUAGES[$_cnt] = $languageLists[$i]->servicelanguage_code;
						$_LANGUAGENAMES[$_cnt] = $languageLists[$i]->servicelanguage_name;
						$_cnt++;
					}
				}				 
				
				if(is_array($_LANGUAGEs)){
					$_str .= "\t\$_LANGUAGES = array(\"".implode("\",\"", $_LANGUAGEs)."\");	\n";
					$_str .= "\t\$_LANGUAGENAMES = array(\"".implode("\",\"", $_LANGUAGEnames)."\");	\n";
				}else{
					$_str .= "\t\$_LANGUAGES = array(\"TH\");	\n";
					$_str .= "\t\$_LANGUAGENAMES = array(\"ไทย\");	\n";
				}
				
				$_str .= "\n?>";
				
				fwrite($file, $_str); fclose($file);
				chmod($_initfile, 0777);
				
		}

		function initPageStyleInclude($_SERVICE_DOMAIN, $objectLists, $_filename){
				
				$_initfile = "_domains/".Init_Template::serviceDomain()."/".$_filename; 
				if(!file_exists($_initfile)){
					Init_Template::forcePath($_initfile);
					$file = fopen($_initfile, 'w');				
					fwrite($file, $_str); fclose($file);
					chmod($_initfile, 0777);
				}
				
				$file = fopen($_initfile, 'w');				
				
				if(!empty($objectLists)){
					$_keys = array_keys($objectLists);
					if(!is_array($_keys)){ 
						exit;
					}
				}
											
				$_str = "<? PHP\n\n";
				for($i=0;$i<sizeof($_keys);$i++){  
					if(is_array($objectLists[$_keys[$i]])){   
						$_key2s = array_keys($objectLists[$_keys[$i]]);
						for($k=0;$k<sizeof($_key2s);$k++){		 
							$_str .= "\t\$__PAGEStyles[\"".$_keys[$i]."\"][\"".$_key2s[$k]."\"] = \"".$objectLists[$_keys[$i]][$_key2s[$k]]."\";\n";
						}
					}
				}
				
				$_str .= "\n?>";
				
				fwrite($file, $_str); fclose($file);
				chmod($_initfile, 0777);
				
		}
		
		function serviceDomain(){
				$__SERVICE_DOMAIN_FOLDER = trim($_SERVER["HTTP_HOST"]);
				
				//$serviceDomain->servicedomainid = $__SERVICE_DOMAIN_FOLDER;
				
				//$GLOBALS['serviceDomainService']->create( ,$GLOBALS['_connection']);
				
				//echo "1. \$__SERVICE_DOMAIN_FOLDER[$__SERVICE_DOMAIN_FOLDER]<br>";
				if($_SERVER['SERVER_ADDR']=="127.0.0.1"){
					$_stacks = array(".devcom", ".devnet", ".devco", "/");
					$_needle = array(".com", ".net", ".co", "");
					$__SERVICE_DOMAIN_FOLDER = str_replace($_stacks, $_needle, $__SERVICE_DOMAIN_FOLDER);
				}                   
				//$dir = trim(dirname($GLOBALS['_WEBROOT_DIR']."/".$__SERVICE_DOMAIN_FOLDER."/index.html"),'/').'/';
				//forcePath($dir);     
				return $__SERVICE_DOMAIN_FOLDER;
		}

		function forcePath($path){
					//echo "!!!!!!!! path[$path]<br>";
					$dir = trim(dirname($path),'/').'/';
					if($dir!="/")
						Init_Template::forceDirectory($dir);
		}
			
		function forceDirectory($dir){                      
				//echo "create[$dir]<br>";
				return is_dir($dir) or (Init_Template::forceDirectory(dirname($dir)) and mkdir($dir, 0777) and chmod($dir, 0777));   
		} 
	
	}

?>