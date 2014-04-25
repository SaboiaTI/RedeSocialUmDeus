<?php 
	
	//session_start();
	
	// --------------------------------------------------------------------------------
	// AppStart.php
	// @author Saboia Tecnologia da Informação <relacionamento@saboia.com.br>
	// @version 1.0
	// usuário da última alteração: rafael.henrique
	// data da última alteração: 09/12/2011 11:18
	// --------------------------------------------------------------------------------

	
	// ----------------------------------------------------------------------------------------------------
	// informações sobre usuário logado:
	if ( !isset($_SESSION["loggedUser"]) ) { $_SESSION["loggedUser"] = 0; }
	
	if ($_SESSION["loggedUser"] != 0) {
		
		$usob = $_SESSION["loggedUser"];
		$userObject = getObject($usob);
		$userObject['properties'] = getProperties($usob);
	}
	
	if (isset($usob)) {
		
		// ----------------------------------------------------------------------------------------------------
		// informações sobre o objeto acessado:
		$sob = isset($_GET['sob']) ? $_GET['sob'] : 0;
		$sob = intval($sob);
		
		if ($sob != 0) {
			
			$socialObject = getObject($sob);
			$socialObject['properties'] = getProperties($sob);
			
		}
		
		
		
		// ----------------------------------------------------------------------------------------------------
		// informações sobre o profile relacionado ao objeto acessado:
		$prob = isset($_GET['prob']) ? $_GET['prob'] : 0;
		$prob = intval($prob);

		if ($prob != 0) {
			
			$profileObject = getObject($prob);
			$profileObject['properties'] = getProperties($prob);
			
		}
		
		/*
		echo '<div style="position:fixed;bottom:25px;left:10px;width:100px;padding:2px;background-color:rgba(10,10,10,0.65);color:#FFF;font-size:11px;z-index:100000;">';
		echo '<strong>debug:</strong><br>';
		if (isset($userObject))		{ echo 'userObject: '		.$userObject['id'].'<br>'; } 	else { echo 'userObject:--<br>'; }
		if (isset($socialObject))	{ echo 'socialObject: '		.$socialObject['id'].'<br>'; }	else { echo 'socialObject:--<br>'; }
		if (isset($profileObject))	{ echo 'profileObject: '	.$profileObject['id'].'<br>'; }	else { echo 'profileObject:--<br>'; }
		echo '</div>';
		// */
	
	}
	
?>