<? 
	
	$DBServer = "localhost";
	$DBUsername = "unitrio_crm";
	$DBPassword = "12qwaszx";
	
	$DBName = "unitrio_crm";
	
	$conn = mysql_connect($DBServer, $DBUsername, $DBPassword) or die("Could not connect to dbserver");
	
	mysql_select_db($DBName, $conn);
	
?>