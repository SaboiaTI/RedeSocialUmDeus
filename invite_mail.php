<?php 
    
	$adminmail="christian@saboia.com.br"; 
	
	$useremail="relacionamento@saboia.com.br"; 
	
    $subject = "Test email - saboia"; 
	
    $content = "<!doctype html>";
	$content = "<html>";
	$content = "<body style='background-color:#f4f4f4'>";
	$content = "	<div style='width: 600px; margin:0px auto; font-family:Arial'>";
	$content = "		<div style='height:18px; background-color:#6e2b60; padding:10px'>";
	$content = "			<h1 style='color:#FFF; font-size:16px; margin:0px; padding:0px'>";
	$content = "				<a href='http://lab.saboia.info/umdeus-redesocial/social' target='_blank' style='color:#FFF; text-decoration:none'>Rede Social - Um Deus</a>";
	$content = "			</h1>";
	$content = "		</div>";
	$content = "		<div style='font-size:12px; color:#666; padding:20px; padding-top:10px; background-color:#FFF; border:1px solid #e4e4e4'>";
	$content = "			<div>";
	$content = "				<p><h3 style='margin:0px; padding:0px'>Olá ".$para.", </h3></p>";
	$content = "				você recebeu uma convite de <a href="" target='_blank'  style='color:#666; font-weight:bold; font-size:14px'>".$de."</a> para fazer parte da Rede Social Um Deus, com a seguinte mensagem:"
	$content = "				".$sMessage."";
	$content = "			</div>";
	$content = "		</div>";
	$content = "	</div>";
	$content = "</body>";
	$content = "</html>"; 
	
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
