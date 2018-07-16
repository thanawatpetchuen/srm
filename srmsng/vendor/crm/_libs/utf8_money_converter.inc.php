<? 
	$moneyConverter = new MoneyConverter();
	//echo "7,788.00 >>".$moneyConverter->convert2ThaiBath("7,788.00")."<br>";
	//echo "7,700.00 >>".$moneyConverter->convert2ThaiBath("7,700.00")."<br>";
	//echo "7,701.00 >>".$moneyConverter->convert2ThaiBath("7,001.00")."<br>";
	
	class MoneyConverter{
		
		function convert2ThaiBath($__number){			
			$__number = str_replace(",", "", $__number);
			if($__number>9999999)
				return "number out of length !!<BR>";
			
			$_pref = array("9"=>"เก้า", "8"=>"แปด", "7"=>"เจ็ด", "6"=>"หก", "5"=>"ห้า", "4"=>"สี่", "3"=>"สาม", "2"=>"สอง", "1"=>"หนึ่ง", "0"=>"");
			$_pref1 = array("ล้าน", "แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
			$_pref2 = array("สิบ", "สตางค์");
						
			$__numbers = explode(".", $__number);			
			$__numbers[0] = str_pad($__numbers[0], 7, "0", STR_PAD_LEFT);			
			$__numbers[1] = str_pad($__numbers[1], 2, "0", STR_PAD_RIGHT);
			//print_r($__numbers);
			$__str = array();
			$__str_cnt = 0;

			
			for($i=0;$i<strlen($__numbers[0]);$i++){
				
				if($i==(strlen($__numbers[0])-2)){					
					//echo "ตรวจสอบหลักสิบ(".$__numbers[0][$i].")<BR>";
					if(($__numbers[0][$i]*1)==2){
						//echo "เพิ่มคำอ่านเลขหลักสิบ<BR>";
						$__str[$__str_cnt] = "ยี่";
						$__str_cnt++;
					}else if(($__numbers[0][$i]*1)>2){
						//echo "เพิ่มคำอ่านเลขหลักสิบ<BR>";
						$__str[$__str_cnt] = $_pref[$__numbers[0][$i]];
						$__str_cnt++;
					}
				}else if($i==(strlen($__numbers[0])-1)){
					//echo "1. ทำงานรอบที่ ($i) ตรวจสอบหลักหน่วย >>(".$__numbers[0][$i-1].")(".$__numbers[0][$i].")<BR>";
					if(($__numbers[0][$i-1])>0 && ($__numbers[0][$i])==1){						
						$__str[$__str_cnt] = "เอ็ด";  
					}else{						 
						$__str[$__str_cnt] = $_pref[$__numbers[0][$i]];  
					}
					$__str_cnt++;
				}else{
					//echo "2. ทำงานรอบที่ ($i)<BR>"; 
					$__str[$__str_cnt] = $_pref[$__numbers[0][$i]];
					$__str_cnt++;				
				}			
				
				if(($__numbers[0][$i])>0){
					//echo "($i)(".($i%7).")[".$_pref1[($i%7)]."]<BR>";
					$__str[$__str_cnt] = $_pref1[($i%7)];
					$__str_cnt++;
				}

			}

			$__str[$__str_cnt] = "บาท";
			$__str_cnt++;
			
			/*	เลขหลักสตางค์ */
			if(sizeof($__numbers)==2 && ($__numbers[1]*1)>0){
				if(strlen($__numbers[1])==2 && ($__numbers[1]*1)>0){
					
					if(($__numbers[1][0])==2){						
						$__str[$__str_cnt] = "ยี่สิบ";
						$__str_cnt++;
					}else if(($__numbers[1][0])==1){						
						$__str[$__str_cnt] = "สิบ";
						$__str_cnt++;
					}else if(($__numbers[1][0])>2){							
						$__str[$__str_cnt] = $_pref[$__numbers[1][0]];  			  			
						$__str_cnt++;
					}  

					if(($__numbers[1][1])==1 && ($__numbers[1][0])>0){						
						$__str[$__str_cnt] = "เอ็ด";
						$__str_cnt++;
					}else{						
						$__str[$__str_cnt] = $_pref[$__numbers[1][1]];  			  			
						$__str_cnt++;
					}  
					$__str[$__str_cnt] = "สตางค์";
				}
			}else if(($__numbers[1]*1)==0){
				$__str[$__str_cnt] = "ถ้วน";
			}
			
			return implode("", $__str);
		}
	
	}

?>