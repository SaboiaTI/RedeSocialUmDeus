<?php
	
	// bibliotecas compartilhadas:
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/libSocial.php");
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/AppConnect.php");
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/sendMail.php");


	$sLogin = $_REQUEST["sLogin"];
	$sLogin = !isset($sLogin) ? '' : $sLogin;


	$rows = array();
	if ($sLogin == '') {
		$rows['success'] = false;
		echo json_encode(to_utf8($rows));
	}

	
	$query = 'SELECT 
				o.id,
				opp.sValue AS sPassword,
				COUNT(o.id) AS success
				
			FROM tbSOCIALObject o 
			
			LEFT OUTER JOIN tbSOCIALProperty pl 
			ON pl.sKey = "sEmail" 
			
			LEFT OUTER JOIN tbSOCIALObjectProperty opl 
			ON  opl.idSOCIALProperty = pl.id 
			AND opl.idSOCIALObject = o.id
			
			LEFT OUTER JOIN tbSOCIALProperty pp 
			ON pp.sKey = "sPassword" 
			
			LEFT OUTER JOIN tbSOCIALObjectProperty opp
			ON  opp.idSOCIALProperty = pp.id 
			AND opp.idSOCIALObject = o.id
			
			WHERE 	opl.sValue = "'.$sLogin.'" 			
			AND		o.fDeleted = 0; ';
			
	//echo $query;
	
	$result = mysql_query(utf8_decode($query));
	
	$results = mysql_fetch_assoc($result);
	$id		 	= $results['id'];
	$sPassword 	= $results['sPassword'];
	$success 	= $results['success'];
	
	
	
	if ($success == 1) {
		$content 	= "Sua senha é: " . $sPassword;
		$subject	= "Senha socilitada";
		
		sendMail($sLogin, $content, $subject);		
		
		$rows['success'] = true;		
		
	} else {
		
		$rows['success'] = false;
	}

	echo json_encode(to_utf8($rows));
	
	//
	
	
	
?>