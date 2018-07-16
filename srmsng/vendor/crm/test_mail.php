<? 

	$to      = 'ragkarns@gmail.com';
	$subject = 'the subject';
	$message = 'hello';
	$headers = 'From: no_reply@unitriocrm.com' . "\r\n" .
		'Reply-To: no_reply@unitriocrm.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	
	mail($to, $subject, $message, $headers);
	
?> 

success ++