<html>
<head>
<title>PHPMailer - Mail() basic test</title>
</head>
<body>

<? 

require_once('_libs/PHPMailer_v5.0.2/class.phpmailer.php');

$mail             = new PHPMailer(); // defaults to using php "mail()"

$body             = file_get_contents('test descrption');
$body             = eregi_replace("[\]",'',$body);

$mail->AddReplyTo("noreply@unitriocrm.com","www.unitrio.co.th");

$mail->SetFrom('noreply@unitriocrm.com', 'www.unitrio.co.th');

$mail->AddReplyTo("noreply@unitriocrm.com","www.unitrio.co.th");

$address = "whoto@otherdomain.com";
$mail->AddAddress($address, "John Doe");

$mail->Subject    = "PHPMailer Test Subject via mail(), basic";

$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

$mail->MsgHTML($body);

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}

?>

</body>
</html>
