<? 
	function  __WRITE_LANGUAGE_CONFIG_FILE($_arrays, $_filename){
		ob_start();	
		
		echo "\t\t/*	create by __WRITE_CONFIG_FILE  ".date("d/m/Y h:i:s A")." */\n";
		
		$_keays = array_keys($_arrays);
		if(sizeof($_keays)==0){			
				echo "\t\t\$__LANGUAGES[0] = \"TH\";\n";
				echo "\t\t\$__LANGUAGE_NAVS[0] = array(\"TH\", \"ภาษาไทย\");\n";
				echo "\t\t\$__LANGUAGE_SERVICES = array(\"TH\"=>\"ภาษาไทย\");\n";
		}
		
		for($i=0;$i<sizeof($_keays);$i++){			
				echo "\t\t\$__LANGUAGES[".$i."] = \"".$_keays[$i]."\";\n";				
				echo "\t\t\$__LANGUAGE_NAVS[".$i."] = array(\"".$_keays[$i]."\", \"".$_arrays[$_keays[$i]]."\");\n";		
				if($i==0)
					$_text .= "\"".$_keays[$i]."\"=>\"".$_arrays[$_keays[$i]]."\"";	
				else
					$_text .= ", \"".$_keays[$i]."\"=>\"".$_arrays[$_keays[$i]]."\"";	
		}
		
		echo "\t\t\$__LANGUAGE_SERVICES = array($_text);\n";				
				
		$_configs = ob_get_contents();
		ob_end_clean();
				
		App_Template::forcePath($_filename);
		$file = fopen($_filename, 'w');
		fwrite($file, "<? \n$_configs\n?>"); fclose($file);
		
		return true;
	 }
	 function __WRITE_CONFIG_FILE($_objService, $_obj, $_filename){
		
		 if(get_class($_objService)==""){
			echo "Invalid \$_objService<br>";
			return false;
		 }
		 
		$__tableFields = $_objService->tableFields;
		if(sizeof($__tableFields)==0){
			echo "Invalid \$__tableFields<br>";
			return false;
		}

		if(get_class($_obj)==""){
			echo "Invalid \$_obj<br>";
			return false;
		}
			 
		ob_start();	
		
		echo "\t\t/*	create by __WRITE_CONFIG_FILE date(".date("d/m/Y").") 	*/\n";
		
		$class_vars = get_class_vars(get_class($_obj));
		foreach ($class_vars as $name => $value) {
			if($__tableFields[$name]=="string")
				echo "\t\t\$__".strtoupper($name)." = \"".$_obj->$name."\";\n";
			else if($__tableFields[$name]=="int")
				echo "\t\t\$__".strtoupper($name)." = ".($_obj->$name*1).";\n";
		}
		
		$_configs = ob_get_contents();
		ob_end_clean();
				
		App_Template::forcePath($_filename);
		$file = fopen($_filename, 'w');
		fwrite($file, "<? \n$_configs\n?>"); fclose($file);
		
		return true;
	 }

?>