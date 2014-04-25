<?php
	
	// bibliotecas compartilhadas:
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/libSocial.php");
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/AppConnect.php");
	

	$idDaPhoto = $_REQUEST["idDaPhoto"];
	
	
	
	$idDaPhoto = !isset($idDaPhoto) ? '' : $idDaPhoto;	
	
	$rows = array();
	
	if ($idDaPhoto == '') 
	{
		$rows['success'] = false;	
		echo json_encode(to_utf8($rows));		
		return;
	}
	
	$query1 = '
	UPDATE  tbSOCIALObjectAssociation
	SET iType = 8 
	WHERE id IN 
	(
		SELECT ID 
		FROM (SELECT id, idSOCIALAssociation, iType FROM tbSOCIALObjectAssociation) AS a
		WHERE a.idSOCIALAssociation  IN 
		(
			SELECT b.idSOCIALAssociation 
			FROM (SELECT idSOCIALAssociation,idSOCIALObject, iType FROM tbSOCIALObjectAssociation ) AS b
			WHERE b.idSOCIALObject = ' . $idDaPhoto . '
			AND b.iType = 8
		)
		AND  a.iType = 19
	);';
	
	begin();
//	echo $query1;
	$resultObject = mysql_query(utf8_decode($query1));
		
	if ($resultObject == false) {
		rollback();
		$rows['success'] = false;
		echo json_encode(to_utf8($rows));		
		return;
		
	}
	
	$query = ' UPDATE tbSOCIALObjectAssociation
				SET iType = 19
				WHERE idSOCIALObject = ' . $idDaPhoto . ' 
				AND iType = 8 ;';
//	echo $query;
	$resultObject = mysql_query(utf8_decode($query));
		
	if ($resultObject == false) {
		rollback();
		$rows['success'] = false;
		echo json_encode(to_utf8($rows));		
		return;
		
	}

	commit();
	



	$rows['success'] = true;
	echo json_encode(to_utf8($rows));
	return;
	
	
	
?>