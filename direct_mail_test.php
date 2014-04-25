<?php 

	$send_html_messages = "no"; 
    
	$adminmail="christian@saboia.com.br"; 
	$useremail="relacionamento@saboia.com.br"; 
    $subject = "Test email - saboia"; 
    $content = "test content"; 
	
//	$xheaders = "From: ". '"' . $adminmail . '"' . " <" . $adminmail . ">\n"; 
	$xheaders = "From: ". $adminmail . "\n"; 
	$xheaders .= "X-Sender: <" . $adminmail . ">\n"; 
	$xheaders .= "X-Mailer: PHP\n"; // mailer 
	$xheaders .= "X-Priority: 6\n"; // Urgent message! 

	if ($send_html_messages == "yes") { 
			$xheaders .= 'MIME-Version: 1.0' . "\n"; 
			$xheaders .= "Content-Type: text/html; charset=iso-8859-1\n"; // Mime type 
	} 
	
	$mailStatus = mail("$useremail","$subject","$content",$xheaders); 
	
	$message = $mailStatus ? "Email enviado. Verifique a caixa postal $useremail e veja se chegou o email $subject".'.' : "Server error - PHP has not been configured to send out emails yet, sorry.";
	$message = date("F j, Y, g:i a").'<br>'.$message
	
	
?>
<!doctype html>
<html>
<body>
	<div>
		<div>Nome você recebeu uma convite de para fazer parte da Rede Social Um Deus</div>
	</div>
</body>
</html>