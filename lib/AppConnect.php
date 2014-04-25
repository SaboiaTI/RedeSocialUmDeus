<?php

	// --------------------------------------------------------------------------------
	// Login.php
	// @author Saboia Tecnologia da Informaзгo <relacionamento@saboia.com.br>
	// @version 1.0
	// usuбrio da ъltima alteraзгo: william.campanari
	// data da ъltima alteraзгo: 09/04/2011 19:38
	// --------------------------------------------------------------------------------
	
$serverDB 	= "localhost";
$userDB		= "root";
$passDB		= "EstaEhsu4S3nh@";
$nameDB		= "hom-accl-umdeus";

	// conexгo ao BD:
	$conn = mysql_connect($serverDB, $userDB, $passDB) or die('Could not connect: ' . mysql_error());
	mysql_select_db($nameDB) or die('Could not select database');

	// conexгo ao BD: para ser utilizada com o comando mysqli_multi_query
	$conni = mysqli_connect($serverDB, $userDB, $passDB, $nameDB) or die('Could not connect: ' . mysql_error());


	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/AppStart.php");

?>