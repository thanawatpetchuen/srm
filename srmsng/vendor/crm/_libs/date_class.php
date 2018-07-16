<? 

	function date_filter($mydate){
		global $configs;
		return date("j F Y", strtotime($mydate));
	}
	function dateDiff($str_dt,$end_dt){
			$daysDiff = abs(strtotime($str_dt))-abs(strtotime($end_dt));
			return round(abs($daysDiff)/(24*60*60))-1; 
	}
	function selectMysqlDate($str_dt){        
			global $_LANGUAGE; 
			if(strlen($str_dt)!=10)
				return NULL;
			$str_dt = explode("-", $str_dt);   
			if($_LANGUAGE=="TH")
				$str_dt[0] = $str_dt[0]+543;

			if(!checkDate($str_dt[1], $str_dt[2], $str_dt[0])){
				print_r($str_dt);
				return "now()";
			}else{	     
				return implode("/", array_reverse($str_dt));
			}          

			return;		 
	} 
	function sys_cal_date($str_dt){      
			if(strlen($str_dt)!=10)
				return "";
			$str_dt = explode("/", $str_dt);
			if($GLOBALS['_LANGUAGE']=="TH")
				$str_dt[2] = $str_dt[2]-543;

			if($str_dt=="now()")
				return $str_dt;
			
			$firstDay = $str_dt[0];
			$firstMonth = $str_dt[1];
			$firstYear = $str_dt[2];

			if(!checkDate($firstMonth,$firstDay,$firstYear)){
				return "now()";
			}else{
				if(strlen($firstMonth)==1) $firstMonth="0".$firstMonth;
				if(strlen($firstDay)==1) $firstDay="0".$firstDay;                      
				return "$firstYear/$firstMonth/$firstDay";	 
			}           
			return ""; 
	}         
	function InsertMysqlDate($str_dt, $_hour="00", $_minute=="00"){        
			
			if(strlen($str_dt)!=10)
				return "NULL";
			$str_dt = explode("/", $str_dt);
			if($GLOBALS['_LANGUAGE']=="TH")
				$str_dt[2] = $str_dt[2]-543;

			if($str_dt=="now()")
				return $str_dt;
			
			$firstDay = $str_dt[0];
			$firstMonth = $str_dt[1];
			$firstYear = $str_dt[2];
			
			if(!checkDate($firstMonth,$firstDay,$firstYear)){
				return "now()";
			}else{
				if(strlen($firstMonth)==1) $firstMonth="0".$firstMonth;
				if(strlen($firstDay)==1) $firstDay="0".$firstDay;                      
				return "'$firstYear-$firstMonth-$firstDay $_hour:$_minute:00'";	 
			}           
			return "NULL";
	}         
	function mysql2Date($str_dt){                 
  
			$firstDay = substr($str_dt,8,2);
			$firstMonth = substr($str_dt,5,2);      
			$firstYear = substr($str_dt,0,4);            
			
			if($GLOBALS['_LANGUAGE']=="TH")
				$firstYear = $firstYear+543;
			
			//echo "input date is [$str_dt]<BR>";               
			//echo "[$firstMonth] [$firstDay] [$firstYear]<BR>";               
                                            
			if(!checkDate($firstMonth,$firstDay,$firstYear)){
				//echo "Invalid Input Date."; 
				//exit;
				return "N/A"; 
			} 
			$n_dt = JDToGregorian(GregorianToJD($firstMonth,$firstDay,$firstYear));
			$ret_tmp_dt = explode("/",$n_dt);                                      
			if(strlen($ret_tmp_dt[1])==1) $ret_tmp_dt[1]="0".$ret_tmp_dt[1];
			if(strlen($ret_tmp_dt[0])==1) $ret_tmp_dt[0]="0".$ret_tmp_dt[0];              
			//return $ret_tmp_dt[1]."/".$ret_tmp_dt[0]."/".($ret_tmp_dt[2]+543);
			return $ret_tmp_dt[1]."/".$ret_tmp_dt[0]."/".($ret_tmp_dt[2]);
	} 
	function display_MySQL_Date($str_dt){            
			if($str_dt==NULL || $str_dt=="")
				return "";
			
			$firstDay = substr($str_dt,8,2);
			$firstMonth = substr($str_dt,5,2);      
			$firstYear = substr($str_dt,0,4);                               
			                                      
			if(!checkDate($firstMonth,$firstDay,$firstYear)){
				return "";
			} 
			$n_dt = JDToGregorian(GregorianToJD($firstMonth,$firstDay,$firstYear));
			$ret_tmp_dt = explode("/",$n_dt);                                      
			if(strlen($ret_tmp_dt[1])==1) $ret_tmp_dt[1]="0".$ret_tmp_dt[1];
			if(strlen($ret_tmp_dt[0])==1) $ret_tmp_dt[0]="0".$ret_tmp_dt[0];              
			//return $ret_tmp_dt[1]."/".$ret_tmp_dt[0]."/".($ret_tmp_dt[2]+543);
			if(strtoupper($GLOBALS['_LANGUAGE']) == 'TH')
				return $ret_tmp_dt[1]."/".$ret_tmp_dt[0]."/".($ret_tmp_dt[2]+543);
			else  
				return $ret_tmp_dt[1]."/".$ret_tmp_dt[0]."/".($ret_tmp_dt[2]);
	}
	/*
	function displayDate($_LANGUAGE,$str_dt){            
			if($str_dt==NULL || $str_dt=="")
				return "";
			
			$firstDay = substr($str_dt,8,2);
			$firstMonth = substr($str_dt,5,2);      
			$firstYear = substr($str_dt,0,4);                               
			                                      
			if(!checkDate($firstMonth,$firstDay,$firstYear)){
				echo "[$str_dt] - [$firstMonth][$firstDay][$firstYear] Invalid Input Date."; 
				exit;
			} 
			$n_dt = JDToGregorian(GregorianToJD($firstMonth,$firstDay,$firstYear));
			$ret_tmp_dt = explode("/",$n_dt);                                      
			if(strlen($ret_tmp_dt[1])==1) $ret_tmp_dt[1]="0".$ret_tmp_dt[1];
			if(strlen($ret_tmp_dt[0])==1) $ret_tmp_dt[0]="0".$ret_tmp_dt[0];              
			//return $ret_tmp_dt[1]."/".$ret_tmp_dt[0]."/".($ret_tmp_dt[2]+543);
			if($_LANGUAGE=='th')
				return $ret_tmp_dt[1]."/".$ret_tmp_dt[0]."/".($ret_tmp_dt[2]+543);
			else  
				return $ret_tmp_dt[1]."/".$ret_tmp_dt[0]."/".($ret_tmp_dt[2]);
	}
	*/
	function thai_2Eng($_date){
		if(strlen($_date)!=10)
			return "";
		$_date = explode("/", $_date);
		$_date[2] = $_date[2]-543;
		return implode("/", $_date);
	}
	function mySQL_2Thai($_date){
		if(strlen($_date)!=10)
			return "";
		$_date = explode("-", $_date);
		$_date[0] = $_date[0]+ 543;
		return implode("/", array_reverse($_date));
	}

	function dateNow(){
		global $_LANGUAGE;
		if(strtolower($_LANGUAGE)=="th"){
			$date = date('d/m/Y');
			$_dates = explode("/", $date);
			$_dates[2] = $_dates[2]+543;
			return implode("/", $_dates);
		}else if(strtolower($_LANGUAGE)=="en"){
			return date('d/m/Y');
		}
	}
	function dateNowInterval($_intv){
		global $_LANGUAGE; 
		if(strtolower($_LANGUAGE)=="th"){
			$date = date('d/m/Y');
			$_dates = explode("/", $date);
			$_dates[2] = $_dates[2]+543+$_intv;
			return implode("/", $_dates);
		}else if(strtolower($_LANGUAGE)=="en"){
			return date('d/m/Y');
		}
	}
	
	$thai_day_arr=array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");   
	$thai_month_arr=array(   
													"0"=>"",   
													"1"=>"มกราคม",   
													"2"=>"กุมภาพันธ์",   
													"3"=>"มีนาคม",   
													"4"=>"เมษายน",   
													"5"=>"พฤษภาคม",   
													"6"=>"มิถุนายน",    
													"7"=>"กรกฎาคม",   
													"8"=>"สิงหาคม",   
													"9"=>"กันยายน",
													"10"=>"ตุลาคม",   
													"11"=>"พฤศจิกายน",   
													"12"=>"ธันวาคม"                     
												);   

function thai_date($time){   
	global $thai_day_arr,$thai_month_arr;   
	$thai_date_return="วัน".$thai_day_arr[date("w",$time)];   
	$thai_date_return.= "ที่ ".date("j",$time);   
	$thai_date_return.=" เดือน".$thai_month_arr[date("n",$time)];   
	$thai_date_return.= " พ.ศ.".(date("Yํ",$time)+543);   
	$thai_date_return.= "  ".date("H:i",$time)." น.";   
	return $thai_date_return;   
}    

function DateThai($strDate){
	$strYear = date("Y",strtotime($strDate))+543;
	$strMonth= date("n",strtotime($strDate)); 
	$strDay= date("j",strtotime($strDate)); 
	$strHour= date("H",strtotime($strDate)); 
	$strMinute= date("i",strtotime($strDate)); 
	$strSeconds= date("s",strtotime($strDate)); 
	$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค."); 
	$strMonthThai=$strMonthCut[$strMonth];  
	return "$strDay $strMonthThai $strYear<BR>เวลา $strHour:$strMinute น.";
}

//$strDate = "2008-08-14 13:42:44";  
//echo "ThaiCreate.Com Time now : ".DateThai($strDate); 

//$eng_date=time(); // แสดงวันที่ปัจจุบัน   
//echo thai_date($eng_date); 

?>