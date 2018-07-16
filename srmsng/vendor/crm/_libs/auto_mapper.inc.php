<? 
	
	function mapperUtils($_objs, $_REQUEST){
		//echo "get_class (".get_class($_objs).")<BR>";
		$_keys = array_keys($_REQUEST);
		$class_vars = get_class_vars(get_class($_objs));
		foreach ($class_vars as $name => $value) {
			//echo "mapper to [$name] : $value<BR>";
			if($name=="created_by" || $name=="updated_by"){
					$_objs->$name = $_COOKIE["SES_userid"];
			}else if($name=="created_on" || $name=="updated_on"){
				$_objs->$name = date("d/m")."/".date("Y");
				//echo "date >>".date("d/m")."/".(date("Y")+543)."<br>";
			}else if(in_array($name, $_keys)){
				//echo "name val >>[".$name."]<br>";
				if(substr($name, -3)=="_dt")				
					$_objs->$name =  InsertFromThaiDate($_REQUEST[$name]);
				else if(substr($name, -4)=="_amt")
					$_objs->$name =  ($_REQUEST[$name]*1);
				else if(substr($name, -4)=="_qty" || substr($name, -8)=="_qty_div")
					$_objs->$name =  ($_REQUEST[$name]*1);
				else if(substr($name, -6)=="_money" || substr($name, -10)=="_money_div")
					$_objs->$name =  ($_REQUEST[$name]*1);
				else if(substr($name, -6)=="_other")
					$_objs->$name =  ($_REQUEST[$name]*1);				
				else
					$_objs->$name =  $_REQUEST[$name];
			}
		}
		
		/*
		$class_vars = get_class_vars(get_class($_objs));
		foreach ($class_vars as $name => $value) {
			echo "$name : (".$_objs->$name.")<BR>";
		} 
		*/

		return $_objs;
	} 
	function mapperobject_to_object($_objs, $_objdests){
		$class_vars = get_class_vars(get_class($_objs));
		//print_r($class_vars);
		//echo "<hr>";
		$classdest_vars = get_class_vars(get_class($_objdests));
		//print_r($classdest_vars);
		$_keys = array_keys($classdest_vars);
		//echo "<hr>";
		foreach ($class_vars as $name => $value) {
			if($name=="created_by" || $name=="updated_by"){
					$_objdests->$name = $_COOKIE["SES_userid"];
			}else if($name=="created_on" || $name=="updated_on"){
				$_objdests->$name = date("d/m")."/".date("Y");
			}else if(in_array($name, $_keys)){ 				
					//echo "mapper to [$name] : $value<BR>";
					$_objdests->$name =  $_objs->$name;
			}
		}
		return $_objdests;
	} 
	function InsertFromThaiDate($str_dt){        
			if(strlen($str_dt)!=10)
				return "";
			$str_dt = explode("/", $str_dt); 
			
			$str_dt[2] = $str_dt[2]-543;

			if(strtolower($str_dt)=="sysdate")
				return $str_dt;
			
			$firstDay = $str_dt[0];
			$firstMonth = $str_dt[1];
			$firstYear = $str_dt[2];

			if(!checkDate($firstMonth,$firstDay,$firstYear)){
				return "";
			}else{
				if(strlen($firstMonth)==1) $firstMonth="0".$firstMonth;
				if(strlen($firstDay)==1) $firstDay="0".$firstDay;                      
				return "$firstDay/$firstMonth/$firstYear";	 
			}     
			return "";
	}          
	
?>