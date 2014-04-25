<?php 
    
	function sendMail($to, $content, $subject) {
	
		$adminmail = "engenharia@saboia.com.br"; 
		
//		$xheaders = "From: ". '"' . $adminmail . '"' . " <" . $adminmail . ">\n"; 
		$xheaders = "From: ". $adminmail . "\n"; 
		$xheaders .= "X-Sender: <" . $adminmail . ">\n";
		$xheaders .= "X-Mailer: PHP\n"; // mailer 
		$xheaders .= "X-Priority: 6\n"; // Urgent message! 
		
		$xheaders .= 'MIME-Version: 1.0' . "\n"; 
		$xheaders .= "Content-Type: text/html; charset=iso-8859-1\n"; // Mime type 
		
	//	$mailStatus = mail("$to","$subject","$content",$xheaders); 
		$mailStatus = mail($to,$subject,$content,$xheaders); 
		
		if($mailStatus == true) {
			return true;
		} else if($mailStatus == false) {
			return false;
		}
		
	}



	function sendMail2($to, $content, $subject) {
	
		$adminmail = "engenharia@saboia.com.br"; 
		
		$xheaders = "From: ". $adminmail . "\n"; 
		$xheaders .= "X-Sender: <" . $adminmail . ">\n";
		$xheaders .= "X-Mailer: PHP\n";		
		$xheaders .= "X-Priority: 6\n";
		
		$xheaders .= 'MIME-Version: 1.0' . "\n"; 
		$xheaders .= "Content-Type: text/html; charset=iso-8859-1\n";
		
		
		$messageContent	 = "";
		$messageContent	.= "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
		$messageContent	.= "<html>\n";
		$messageContent	.= "<head>\n";
		$messageContent	.= 	"<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>\n";
		$messageContent	.= 	"<meta property='og:title' content='".$subject."'>\n";
		$messageContent	.= 	"<title>".$subject."</title>\n";
		$messageContent	.= "</head>\n";
		$messageContent	.= "<body leftmargin='0' marginwidth='0' topmargin='0' marginheight='0' offset='0' style='font-family:Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;background-color:#FAFAFA;width:100% !important;'>\n";
		$messageContent	.= 	"<center>\n";
		$messageContent	.= 		"<table border='0' cellpadding='0' cellspacing='0' height='100%' width='100%' style='margin:0;padding:0;height:100% !important;width:100% !important;'>\n";
		$messageContent	.= 		"<tr><td align='center' valign='top'><br />\n";
		$messageContent	.= 		"<table border='0' cellpadding='0' cellspacing='0' width='600' id='templateContainer'>\n";
		$messageContent	.= 			"<tr><td style='height:18px;background-color:#6E2B60;padding:10px;font-family:Arial,Helvetica,sans-serif;'>\n";
		$messageContent	.= 				"<h1 style='color:#FFF;font-size:16px;font-family:Arial,Helvetica,sans-serif;margin:0px;padding:0px'><a href='http://lab.saboia.info/' target='_blank' style='color:#FFF;text-decoration:none'>Rede Social - Um Deus</a></h1>\n";
		$messageContent	.= 			"</td></tr>\n";
		$messageContent	.= 			"<tr><td style='background-color:#EEEEEE;padding:30px 10px;font-family:Arial,Helvetica,sans-serif;font-size:12px;'>\n";
		$messageContent	.= 				"<div id='logo'><a href='http://lab.saboia.info/'><img src='http://lab.saboia.info/img/logo.png' alt='Rede Social Um Deus'></a></div>\n";
		$messageContent	.= 			"</td></tr>\n";
		$messageContent	.= 			"<tr><td style='padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:12px;'>\n";
		
		$messageContent	.= 				$content;
		
		$messageContent	.= 			"</td></tr>\n";
		$messageContent	.= 		"</table>\n";
		$messageContent	.= 		"</td></tr>\n";
		$messageContent	.= 		"</table>\n";
		$messageContent	.= 	"</center>\n";
		$messageContent	.= "</body>\n";
		$messageContent	.= "</html>\n";
		
		
		
		$mailStatus = mail($to, $subject, $messageContent, $xheaders); 
		
		if ($mailStatus == true) {
			return true;
		} else if ($mailStatus == false) {
			return false;
		}
		
	}
	
?>
