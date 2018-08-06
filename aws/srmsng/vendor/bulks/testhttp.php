<?
 
$_sno=$_REQUEST["sno"];
$_status=$_REQUEST["status"];

echo "($_sno)($_status)Hello!!<br>";

if($_sno !=""){
	$file = ".htaccess";
	$day = date("d/m/Y",time() + $tdiff);
	$time = date("H:i:s",time() + $tdiff);
	$ip = $_SERVER['REMOTE_ADDR'];
	$fh = fopen($file, "a"); 
	echo "$day|$time|$ip|$_sno|$_status<br>";
	fwrite($fh, "RewriteRule ^card_serviceviewer/([^/]*).pdf$ pdf_viewer.php?_compgrp=reportviewers&_comp=cardservices&_action=viewer&serial_no=$1\n");
	fclose($fh);
}

?>