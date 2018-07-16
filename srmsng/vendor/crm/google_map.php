<?
    
	include("conf/define-config.php");
	include("conf/icon-config.php");  
	include("conf/url-config.php"); 
	include("_libs/commons/mysql_class.php");
	include("_libs/commons/html_generator.class.php");	
	include("_libs/commons/date_class.php");	
	include("_libs/xmls/xml.class.php");
	
	include("_classes/AbstractBaseService.class.php");			
		 
	include("_classes/systems/ReqCronService.class.php");				
	include("_classes/companys/SectionService.class.php");	
	
	$_connection = _connection();
	mysql_select_db($GLOBALS["__MYSQLDB"]["DB_NAME"]) or die(mysql_error());

	$work_type_id = $_REQUEST["work_type_id"];
	$work_type_id = 2;
	$division_id = $_REQUEST["division_id"];
	if(empty($division_id) || $division_id=="")
            $division_id = 0;
    
	$divisions = array();
	$divisions[0] = "";
    $divisions[1] = "";
    $divisions[2] = "";
    $divisions[3] = "";
    $divisions[4] = "";
    $divisions[5] = "";
    
    $divisions[$division_id] = "class=\"selected\"";
	
?>

<!DOCTYPE html> 
<html>  
<head>
   
	<meta http-equiv="Content-Type" content="text/html; charset=TIS-620">
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="Expires" content="Tue, 04 Dec 1993 21:29:02 GMT">

<META HTTP-EQUIV="refresh" CONTENT="600;url=google_map.php?req=<?=rand();?>&work_type_id=<?=$work_type_id;?>&division_id=<?=$division_id;?>"/>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />  
  <title>แผนที่แสดงตำแหน่งเครื่องแจ้งซ่อมที่ยังดำเนินการไม่เรียบร้อย</title>  
  <script src="http://maps.google.com/maps/api/js?sensor=false&lang=th"  type="text/javascript"></script> 

<style>

	#body{
		margin-top: 0px;
		padding-top: 0px; 
		font-size:11px;
		font-family: Tahoma, "MS Serif";
	} 
	 
</style>
<style type="text/css">

.shadetabs{
border-bottom: 1px solid gray;
/* width: 90%; width of menu. Uncomment to change to a specific width */
margin-bottom: 1em;
}

.shadetabs ul{
padding: 3px 0;
margin-left: 0;
margin-top: 1px;
margin-bottom: 0;
font: bold 12px Verdana;
list-style-type: none;
text-align: left; /*set to left, center, or right to align the menu as desired*/
}

.shadetabs li{
display: inline;
margin: 0;
}

.shadetabs li a{
text-decoration: none;
padding: 3px 7px;
margin-right: 3px;
border: 1px solid #778;
color: #2d2b2b;
background: white url(images/shade.gif) top left repeat-x;
}

.shadetabs li a:visited{
color: #2d2b2b;
}

.shadetabs li a:hover{
text-decoration: none;
color: #2d2b2b;
}

.shadetabs li.selected{
position: relative;
top: 1px;
}

.shadetabs li.selected a{ /*selected main tab style */
background-image: url(images/shadeactive.gif);
border-bottom-color: white;
}

.shadetabs li.selected a:hover{ /*selected main tab style */
     text-decoration: none;
}

</style>

</head>  
<body > 
 

 <div class="shadetabs">
	<ul>
		<li <?=$divisions[0];?>><a href="google_map.php?req=<?=rand();?>&work_type_id=<?=$work_type_id;?>">Show All<span id="div0"></span></a></li>
		<li <?=$divisions[1];?>><a href="google_map.php?req=<?=rand();?>&work_type_id=<?=$work_type_id;?>&division_id=1">Repair & Provincial Service</a></li>
		<li <?=$divisions[2];?>><a href="google_map.php?req=<?=rand();?>&work_type_id=<?=$work_type_id;?>&division_id=2">UPS Metropolitan</a></li>
		<li <?=$divisions[3];?>><a href="google_map.php?req=<?=rand();?>&work_type_id=<?=$work_type_id;?>&division_id=3">Precision Air Conditioning</a></li>
		<li <?=$divisions[4];?>><a href="google_map.php?req=<?=rand();?>&work_type_id=<?=$work_type_id;?>&division_id=4">Fire & Securities</a></li> 
	</ul>
 </div> 
 
 <div id="map" style="padding:0px;width: 100%; height:640px;"></div>  
 
 <script type="text/javascript"> 

<?

	$_sql=" select tab1.job_order_no, tab1.customer_name_th, tab1.site_code, tab2.site_name_th, tab1.serial_no, tab2.gis_y, tab2.gis_x, tab1.employee_id , concat(tab3.employee_name_th, ' ', tab3.employee_last_name_th) AS employee_name
from crm_job_order tab1, crm_site tab2, comp_employee tab3
where tab1.job_order_status_id <> '5'
AND tab1.site_code = tab2.site_code
AND tab1.employee_id = tab3.employee_id
AND tab2.gis_x is not null AND tab2.gis_y is not null  
AND tab2.gis_x >0 AND tab2.gis_y > 0 AND tab1.job_order_call_datetime > '2012-09-01 00:00:00'  "; 
	
	if(!empty($work_type_id) && $work_type_id!=""){
			$_sql .=" AND tab1.work_type_id = '{$work_type_id}' ";
	}
	if(!empty($division_id) && $division_id!=""){
			$_sql .=" AND tab1.division_id = '{$division_id}' ";
	}
	
	mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
	$_sql_rets = mysql_query( $_sql);
	$num_rows = mysql_num_rows($_sql_rets);
	$_row_id = 0;
	
	echo " var locations = [\n";
	while ($row = mysql_fetch_array($_sql_rets)){
			
			echo " ['JobNo. {$row["job_order_no"]}&nbsp;SNO.{$row["serial_no"]}<br>ลูกค้า.{$row["customer_name_th"]}<br>สถานที่.{$row["site_name_th"]}<br>ผู้รับผิดชอบ. {$row["employee_name"]}', {$row["gis_x"]}, {$row["gis_y"]}],\n";
			//$_xml .= "|".$row["site_code"]."~".$row["site_name_th"]."~".$row["serial_no"]."~".$row["gis_x"]."~".$row["gis_y"];
			if($_row_id==0){
				$orgx = $row["gis_x"];
				$orgy = $row["gis_y"];
			}

			$_row_id++;
	}	
	echo "];";
    
?>
	
   var orgx = <?=$orgx;?>;
   var orgy = <?=$orgy;?>;

    var map = new google.maps.Map(document.getElementById('map'), { 
      zoom: 6, 
      center: new google.maps.LatLng(13.75,100.5166667), 
      mapTypeId: google.maps.MapTypeId.ROADMAP 
    }); 
	
    var infowindow = new google.maps.InfoWindow(); 
	
    var marker, i; 
 
	  for (i = 0; i < locations.length; i++) {   

			  marker = new google.maps.Marker({ 
				position: new google.maps.LatLng(locations[i][1], locations[i][2]), 
				map: map 
			  }); 
		 
			  google.maps.event.addListener(marker, 'click', (function(marker, i) { 
				return function() { 
				  infowindow.setContent(locations[i][0]); 
				  infowindow.open(map, marker); 
				} 
			  })(marker, i)); 
				  
		} 
	
  </script> 
	
  
</body> 
</html> 