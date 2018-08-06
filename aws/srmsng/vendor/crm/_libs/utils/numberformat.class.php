<? 
	
	function limitDoubleDigit($amt, $digit){ 
		/*
		if(fmod($amt, 1)!=0){  
			return round($amt * pow(10, (double) $digit)) / pow(10,(double) $digit);
		}
		return $amt;
		*/
		return round($amt * pow(10, (double) $digit)) / pow(10,(double) $digit);
	}  

?>