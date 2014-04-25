<?php
	
	session_start();
	
	// bibliotecas compartilhadas:
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/libSocial.php");
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/AppConnect.php");
	
	
	
	// include do arquivo header
	require_once($_SERVER['DOCUMENT_ROOT'] . "/inc_header.php");
	
	// verifica se é uma página de objeto:
	if (isset($socialObject)) {
		
		require_once($_SERVER['DOCUMENT_ROOT'] . "/object.php");
		
	} else if (!isset($socialObject) && isset($userObject)) {
		
		require_once($_SERVER['DOCUMENT_ROOT'] . "/object.php");
		
	} else {
	
		require_once($_SERVER['DOCUMENT_ROOT'] . "/inc_home.php");
	
		echo '</div><!-- end div#container -->';
		
		require_once($_SERVER['DOCUMENT_ROOT'] . "/inc_footer.php");
	
	}
?>