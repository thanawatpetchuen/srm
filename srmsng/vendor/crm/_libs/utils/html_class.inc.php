<? 
	
	function html_select($arrayObjs, $_name, $_id, $_kVal, $_kLabel, $_autoSelected="", $_msg="-  Please Select  -", $_script=""){
		
		echo "<select name=\"$_name\"  class=\"normaltxt\" id=\"$_id\" $_script>\r\n";
		
		if($_autoSelected=="")
			echo "    <option value=\"\" selected>$_msg</option>\r\n";
		else
			echo "    <option value=\"\">$_msg</option>\r\n";
		
		for($k=0;$k<sizeof($arrayObjs); $k++){
			
			if(is_array($_kVal)){
				
				for($k2=0;$k2<sizeof($_kVal);$k2++){
					//echo "Arribute[".$_kVal[$k2]."]<br>";
					if($k2==0)
						$_sValue = ($arrayObjs[$k]->$_kVal[$k2]);
					else
						$_sValue = ",".($arrayObjs[$k]->$_kVal[$k2]);

				}
			}else{
				$_sValue = trim($arrayObjs[$k]->$_kVal);
			}
			
			

			$_value = ($_autoSelected);

			$_label = "";
			if(is_array($_kLabel)){
				for($i=0;$i<sizeof($_kLabel);$i++){
					if($i==0)
						$_label = $arrayObjs[$k]->$_kLabel[$i];
					else
						$_label .= " - ".$arrayObjs[$k]->$_kLabel[$i];
				}
			}else{
				$_label = trim($arrayObjs[$k]->$_kLabel);
			}

			
			if($_value!=$_sValue)
				echo "    <option value=\"".$_sValue."\">".$_label."</option>\r\n";
			else
				echo "    <option value=\"".$_sValue."\" selected>".$_label."</option>\r\n";
			
		}
		
		echo "</select>\r\n";

		/*
        
		if($_autoSelected!=""){
			echo "<script>\r\n";
			echo "	fn_AutoSelected(_$('$_id'), '$_autoSelected');\r\n";
			echo "</script>\r\n";
		}
		*/		
	}

	function html_search_select($arrayObjs, $_name, $_id, $_kVal, $_kLabel, $_autoSelected="", $_msg="-  Please Select  -", $_script=""){
		
		echo "<select name=\"$_name\"  class=\"normaltxt\" id=\"$_id\" $_script>\r\n";
		
		if($_autoSelected=="")
			echo "    <option value=\"\" selected>$_msg</option>\r\n";
		else
			echo "    <option value=\"\">$_msg</option>\r\n";
		
		for($k=0;$k<sizeof($arrayObjs); $k++){
			
			$_sValue = trim($arrayObjs[$k]->$_kVal);
			
			if(substr($_kVal, -3)=="_id")
				$_value = ($_autoSelected);
			else
				$_value = ($_autoSelected);
			
			$_label = "";
			if(is_array($_kLabel)){
				for($i=0;$i<sizeof($_kLabel);$i++){
					if($i==0)
						$_label = $arrayObjs[$k]->$_kLabel[$i];
					else
						$_label .= " - ".$arrayObjs[$k]->$_kLabel[$i];
				}
			}else{
				$_label = trim($arrayObjs[$k]->$_kLabel);
			}


			if($_value!=$arrayObjs[$k]->$_kVal)
				echo "    <option value=\"".$_sValue."\">".$_label."</option>\r\n";
			else
				echo "    <option value=\"".$_sValue."\" selected>".$_label."</option>\r\n";

		}
		
		echo "</select>\r\n";

		/*
        
		if($_autoSelected!=""){
			echo "<script>\r\n";
			echo "	fn_AutoSelected(_$('$_id'), '$_autoSelected');\r\n";
			echo "</script>\r\n";
		}
		*/		
	}

?>