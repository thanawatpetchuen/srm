<?
 
$_sno=$_REQUEST["sno"];
$_status=$_REQUEST["status"];

echo "($_sno)($_status)Transaction Alert Success";

if($_sno !=""){
	$file = "producttracker.txt";
	$day = date("d/m/Y",time() + $tdiff);
	$time = date("H:i:s",time() + $tdiff);
	$ip = $_SERVER['REMOTE_ADDR'];
	$fh = fopen($file, "a"); 
	//echo "$day|$time|$ip|$_sno|$_status<br>";
	fwrite($fh, "$day|$time|$ip|$_sno|$_status\n");
	fclose($fh);
}

?>