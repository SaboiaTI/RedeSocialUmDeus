<?php

	// --------------------------------------------------------------------------------
	// Login.php
	// @author Saboia Tecnologia da Informa��o <relacionamento@saboia.com.br>
	// @version 1.0
	// usu�rio da �ltima altera��o: william.campanari
	// data da �ltima altera��o: 09/04/2011 19:38
	// --------------------------------------------------------------------------------
	
$serverDB 	= "localhost";
$userDB		= "root";
$passDB		= "EstaEhsu4S3nh@";
$nameDB		= "hom-accl-umdeus";

	// conex�o ao BD:
	$conn = mysql_connect($serverDB, $userDB, $passDB) or die('Could not connect: ' . mysql_error());
	mysql_select_db($nameDB) or die('Could not select database');

	// conex�o ao BD: para ser utilizada com o comando mysqli_multi_query
	$conni = mysqli_connect($serverDB, $userDB, $passDB, $nameDB) or die('Could not connect: ' . mysql_error());


	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/AppStart.php");

?>