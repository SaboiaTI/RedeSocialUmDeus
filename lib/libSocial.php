<?php 

require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/AppConnect.php");
require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/sendMail.php");

// $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
// $id = intval($id);



// -------------------------------------------------------------------------------------------------
// Funções:		begin(), commit(), rollback()
// Objetivo:	Usadas para transações em bancos de dados InnoDB
// Retorno:		
// -------------------------------------------------------------------------------------------------

function begin() 	{ mysql_query("BEGIN"); }
function commit() 	{ mysql_query("COMMIT"); }
function rollback() { mysql_query("ROLLBACK"); }



function identifyLink($sText) {
	
	if (isset($sText) && $sText != "") {
		
		$strText = $sText;
		$pattern = '/((http:\/\/)|(www.))([^\s]*)/';
		
		$replace = array ('"$1 ".strtoupper("$2")', "$3/$2/$1 $4");
		
		$arrMatch = null;
		$strReturn = '';
		
		$strReturn = preg_replace( $pattern, '<a href="'."$0".'" title="" target="_blank">'."$0".'</a>', $sText);
		
		return $strReturn;
		
	} else {
	
		return $sText;
	}
}


function showDate($theDate, $formatTimeStamp='') {
	
	$sqlDatePattern		= '/\b[0-9]{4}\-[0-9]{2}\-[0-9]{2}\b/g';
	$monthDatePattern	= '/\b[0-9]{4}\-[0-9]{2}\b/g';
	$timestampPattern	= '/\b[0-9]{4}\-[0-9]{2}\-[0-9]{2}\s[0-9]{2}\:[0-9]{2}\:[0-9]{2}\b/';
	
	if (isset($theDate)) {
		
	//	if ($timestampPattern.test($theDate) == true) {
		if (preg_match($timestampPattern, $theDate)) {
			
			// verifica se é um timestamp no formato "YYYY-MM-DD HH:MM:SS"
			// $theDate = explode('', $theDate);
			$newDate = $theDate[8].$theDate[9]."/".$theDate[5].$theDate[6]."/".$theDate[0].$theDate[1].$theDate[2].$theDate[3];
			
			if (isset($formatTimeStamp) && $formatTimeStamp == 'datetime') {
				$newDate .= " às ".$theDate[11].$theDate[12].":".$theDate[14].$theDate[15]; // ":".$theDate[17].$theDate[18];
				
			} else if (isset($formatTimeStamp) && $formatTimeStamp == 'timestamp') {
				$newDate .= " ".$theDate[11].$theDate[12].":".$theDate[14].$theDate[15].":".$theDate[17].$theDate[18];
			}
			
			$theDate = $newDate;
			
		}
		/*
		else if ($sqlDatePattern.test($theDate) == true) {
			
			// verifica se é uma data no formato "YYYY-MM-DD":
			$theDate = $theDate.split("-");
			$theDate = $theDate[2]."/".$theDate[1]."/".$theDate[0];
		}
		
		else if ($monthDatePattern.test($theDate) == true) {
			
			// verifica se é uma data no formato "YYYY-MM":
			$theDate = $theDate.split("-");
			$theDate = $theDate[1]."/".$theDate[0];
			
		}
		*/
		return $theDate;
		
	} else {
		return "";
	}
}

// -------------------------------------------------------------------------------------------------
// Função:		to_utf8($input:String ou Array)
// Objetivo:	Usadas para trasformar strings ou arrays em formato UTF-8, para padronizar  
// 				os textos do banco de dados com os textos enviados via jQuery/AJAX/JSON
// Retorno:		String ou Array convertida para UTF-8
// -------------------------------------------------------------------------------------------------

function to_utf8($input) {
	
	$output;
	
	if (is_array($input)) {
		
		foreach ($input as $key => $value) {
			$output[to_utf8($key)] = to_utf8($value);
		}
		
	} elseif(is_string($input)) {
		
		return utf8_encode($input);
		
	} else {
		
		return $input;
		
	}
	
	if (isset($output)) { return $output; }
}








function getObjectTypeList() {
	
	global $objectType;
	
	$query = 'SELECT 
			iType, 
			sKey, 
			sComment 
		FROM 
			tbSOCIAL_DOCObjectType; ';
	
	$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error());

	while($row = mysql_fetch_assoc($result)) {
		$objectType[$row['iType']] = $row;
	}
}

function getAssociationTypeList() {
	
	global $associationType;
	global $r_associationType;
	
	$query = 'SELECT 
				iType,
				sKey,
				sComment
			FROM 
				tbSOCIAL_DOCAssociationType; ';
	
	$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error());

	while($row = mysql_fetch_assoc($result)) {
		$associationType[$row['iType']] = $row;
		$r_associationType[$row['sKey']] = $row['iType'];
	}
}

function getObjectAssociationTypeList() {
	
	global $objectAssociationType;
	global $r_objectAssociationType;
	
	$query = 'SELECT 
				iType,
				sKey,
				sComment
			FROM 
				tbSOCIAL_DOCObjectAssociationType; ';

	$error = 'Query failed: '.mysql_error().'<br>'.$query;
	$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error());

	while($row = mysql_fetch_assoc($result)) {
		$objectAssociationType[$row['iType']] = $row;
		$r_objectAssociationType[$row['sKey']] = $row['iType'];
	}
	
}





function getObject($id) {
	
	$id = !isset($id) ? '' : $id;
	$object;
	
	$query = 'SELECT 
				ob.id,
				ob.sDisplayName,
				ob.sDirectLink,
				ob.iType,
				IFNULL(ob.fDeleted,0) 	AS fDeleted,
				ob.tsCreation
			FROM 
				tbSOCIALObject ob
			WHERE ob.id = '.$id.'
			/* FODASE AND   ob.fDeleted <> 1 */; ';
	
	$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error());
	
	while($row = mysql_fetch_assoc($result)) {
		$object = $row;
	}
	
	return $object;
}

function getProperties($id, $property='') {
	
	$id 		= !isset($id) 		? '' : $id;
	$property 	= !isset($property) ? '' : $property;
	
	
	$where  = '';
	$where .= $property == '' ? '' : ('AND ' . 'pr.sKey = "'.$property.'" ');
	
	
	$query = 'SELECT 
			pr.sKey,
			op.sValue
		
		FROM tbSOCIALProperty pr 
		
		LEFT OUTER JOIN tbSOCIALObjectProperty op 
		ON pr.id = op.idSOCIALProperty 
		AND op.idSOCIALObject = '.$id.'
		
		LEFT OUTER JOIN tbSOCIALObject ob
		ON ob.id = '.$id.' ';
	
	$query .= 'WHERE pr.iObjectType = ob.iType '.$where.' ';
	$query .= 'ORDER BY pr.sKey, pr.id ';
	
	$query .= ';'; 
	
	$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);

	$properties = array();
	while($row = mysql_fetch_assoc($result)) {
		$properties[$row['sKey']] = $row['sValue'];
		// $properties[$row['sKey']] = filter_var($row['sValue'],FILTER_SANITIZE_SPECIAL_CHARS);
	}
	
	return $properties;
}

function getDetails($id, $detail='') {
	
	$id 		= !isset($id) 		? '' : $id;
	$detail 	= !isset($detail) ? '' : $detail;
	
	
	$where  = '';
	$where .= $detail == '' ? '' : ('AND ' . 'dt.sKey = "'.$detail.'" ');
	
	
	$query = 'SELECT 
			dt.sKey,
			dt.sValue
		
		FROM tbSOCIALObjectDetail dt ';
	
	$query .= 'WHERE dt.idSOCIALObject = '.$id.' '.$where.' ';
	$query .= 'ORDER BY dt.sKey, dt.id ';
	
	$query .= ';'; 
	
	$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);

	$details = array();
	while($row = mysql_fetch_assoc($result)) {
		// $details[$row['sKey']] = $row['sValue'];
		// $details[$row['sKey']] = filter_var($row['sValue'],FILTER_SANITIZE_SPECIAL_CHARS);
		
		$sValue = str_replace(chr(13), '<br>', $row['sValue']);
		$sValue = filter_var($sValue,FILTER_SANITIZE_SPECIAL_CHARS);
		$sValue = str_replace('&#60;br&#62;', '<br>', $sValue);
		$details[$row['sKey']] = $sValue;
		
	}
	
	return $details;
}

function getAssociations($id, $par_myRole, $par_associationType, $par_theirRole, $par_order='' ,$par_offset=0, $par_rowcount=0,$fDeleted='') {

	global $r_associationType;
	global $r_objectAssociationType;
	
	$id 					= !isset($id)					? '' : $id;						// objeto de referência
	$par_myRole 			= !isset($par_myRole)			? '' : $par_myRole;				// papel do objeto de referência
	$par_associationType 	= !isset($par_associationType)	? '' : $par_associationType;	// tipo de associação
	$par_theirRole 			= !isset($par_theirRole)		? '' : $par_theirRole;			// papel do objeto buscado

	$idIsArray 	= is_array($id) ? true : false;
	$id 		= is_array($id) ? implode(',', $id) : $id;
	
	if (is_array($par_myRole)) {
		
		$str_myRole = '';
		$str_concat = '';
		
		foreach ($par_myRole as $value) {
			
			$str_myRole .= $str_concat.$r_objectAssociationType[$value];
			$str_concat  = ',';
		}
		$par_myRole = $str_myRole;
		
	} else {
		$par_myRole = $par_myRole == '' ? '' : $r_objectAssociationType[$par_myRole];
	}
	
	if (is_array($par_associationType)) {
		
		$str_associationType = '';
		$str_concat = '';
		
		foreach ($par_associationType as $value) {
			
			$str_associationType .= $str_concat.$r_associationType[$value];
			$str_concat  = ',';
		}
		$par_associationType = $str_associationType;
		
	} else {
		$par_associationType = $par_associationType == ''  ? '' : $r_associationType[$par_associationType];
	}
	
	if (is_array($par_theirRole)) {
		
		$str_theirRole = '';
		$str_concat = '';
		
		foreach ($par_theirRole as $value) {
			
			$str_theirRole .= $str_concat.$r_objectAssociationType[$value];
			$str_concat  = ',';
		}
		$par_theirRole = $str_theirRole;
		
	} else {
		$par_theirRole = $par_theirRole == '' ? '' : $r_objectAssociationType[$par_theirRole];
	}
	
	$where = ''	;
	$where .= $par_myRole == ''			 	? '' : ('AND ' . 'me.iType IN ('.$par_myRole.') ');
	$where .= $par_associationType == '' 	? '' : ('AND ' . 'a.iType IN ('.$par_associationType.') ');
	$where .= $par_theirRole == '' 		 	? '' : ('AND ' . 'them.iType IN ('.$par_theirRole.') ');
	$where .= $fDeleted == '' 		 		? '' : ('AND ' . 'o.fDeleted IN ('.$fDeleted.') ');
	
	$query = '
		SELECT 
			me.iType 				AS myRole,
			me.idSOCIALAssociation	AS Association,
			a.iType					AS AssociationType,
			o.id					AS id,
			CASE WHEN (o.fDeleted = 1 AND o.iType = 1) THEN "Conta Desabilitada" ELSE o.sDisplayName END AS sDisplayName,
			o.tsCreation			AS tsCreation,
			IFNULL(o.fDeleted,0) 	AS fDeleted,
			them.iType				AS theirRole
			
		
		FROM tbSOCIALObjectAssociation me

		INNER JOIN tbSOCIALAssociation a
		ON me.idSOCIALAssociation = a.id 

		INNER JOIN tbSOCIALObjectAssociation them
		ON a.id = them.idSOCIALAssociation

		INNER JOIN tbSOCIALObject o 
		ON them.idSOCIALObject = o.id
		AND o.id <> me.idSOCIALObject ';

	$query .= 'WHERE me.idSOCIALObject IN ('.$id.') '.$where.' ';
	
	if ($par_order != '')
	{
		$query .= 'ORDER BY ' .$par_order. ', AssociationType, myRole, theirRole ';
	}	
	else
	{
		$query .= 'ORDER BY AssociationType, myRole, theirRole ';
	}
	
	if ($idIsArray == true) {
	
		if  (intval($par_rowcount) > 0) {
			$query .= ' LIMIT ' .$par_offset. ',' .$par_rowcount ;	
		} else {
			$query .= ' LIMIT 0, 20' ;
		}
		
	} else {
		if  (intval($par_rowcount) > 0) {
			$query .= ' LIMIT ' .$par_offset. ',' .$par_rowcount ;	
		}
	}
	$query .= '; '; 
	
	
	
	$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);

	$associations = array();
	while($row = mysql_fetch_assoc($result)) {
		$associations[] = $row;
	}

	return $associations;
	
}





function getCountAssociations($id, $par_myRole, $par_associationType, $par_theirRole) {

	global $r_associationType;
	global $r_objectAssociationType;
	
	$id 					= !isset($id)					? '' : $id;						// objeto de referência
	$par_myRole 			= !isset($par_myRole)			? '' : $par_myRole;				// papel do objeto de referência
	$par_associationType 	= !isset($par_associationType)	? '' : $par_associationType;	// tipo de associação
	$par_theirRole 			= !isset($par_theirRole)		? '' : $par_theirRole;			// papel do objeto buscado

	$idIsArray 	= is_array($id) ? true : false;
	$id 		= is_array($id) ? implode(',', $id) : $id;
	
	if (is_array($par_myRole)) {
		
		$str_myRole = '';
		$str_concat = '';
		
		foreach ($par_myRole as $value) {
			
			$str_myRole .= $str_concat.$r_objectAssociationType[$value];
			$str_concat  = ',';
		}
		$par_myRole = $str_myRole;
		
	} else {
		$par_myRole = $par_myRole == '' ? '' : $r_objectAssociationType[$par_myRole];
	}
	
	if (is_array($par_associationType)) {
		
		$str_associationType = '';
		$str_concat = '';
		
		foreach ($par_associationType as $value) {
			
			$str_associationType .= $str_concat.$r_associationType[$value];
			$str_concat  = ',';
		}
		$par_associationType = $str_associationType;
		
	} else {
		$par_associationType = $par_associationType == ''  ? '' : $r_associationType[$par_associationType];
	}
	
	if (is_array($par_theirRole)) {
		
		$str_theirRole = '';
		$str_concat = '';
		
		foreach ($par_theirRole as $value) {
			
			$str_theirRole .= $str_concat.$r_objectAssociationType[$value];
			$str_concat  = ',';
		}
		$par_theirRole = $str_theirRole;
		
	} else {
		$par_theirRole = $par_theirRole == '' ? '' : $r_objectAssociationType[$par_theirRole];
	}
	
	$where = ''	;
	$where .= $par_myRole == ''			 ? '' : ('AND ' . 'me.iType IN ('.$par_myRole.') ');
	$where .= $par_associationType == '' ? '' : ('AND ' . 'a.iType IN ('.$par_associationType.') ');
	$where .= $par_theirRole == '' 		 ? '' : ('AND ' . 'them.iType IN ('.$par_theirRole.') ');
	
	//	--	me.iType 				AS myRole,
	//	--	me.idSOCIALAssociation	AS Association,
	//	--	a.iType					AS AssociationType,
	//	--	o.id					AS id,
	//	--	o.sDisplayName			AS sDisplayName,
	//	--	o.tsCreation			AS tsCreation,
	//	--	them.iType				AS theirRole
	
	$query = '
		SELECT 
			COUNT(me.id)			AS iTotal
		
		FROM tbSOCIALObjectAssociation me

		INNER JOIN tbSOCIALAssociation a
		ON me.idSOCIALAssociation = a.id 

		INNER JOIN tbSOCIALObjectAssociation them
		ON a.id = them.idSOCIALAssociation

		INNER JOIN tbSOCIALObject o 
		ON them.idSOCIALObject = o.id
		AND o.id <> me.idSOCIALObject ';

	$query .= 'WHERE me.idSOCIALObject IN ('.$id.') AND o.fDeleted <> 1 '.$where.'; ';
	
	$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);

	$iTotal;
	while($row = mysql_fetch_assoc($result)) {
		$iTotal = $row['iTotal'];
	}
	return $iTotal;
	
}





function getObjects($objectType, $search='') {

	$objectType = !isset($objectType) ? '' : $objectType;
	$search		= !isset($search)	  ? '' : $search;
	
	$query = '
		SELECT 
			o.id,
			o.sDisplayName,
			o.sDirectLink,
			o.iType,
			IFNULL(o.fDeleted,0) AS fDeleted,
			o.tsCreation
		
		FROM tbSOCIALObject AS o ';

	if($search != '') {
		
		$search['pais']		  != '' ? $query .= " INNER JOIN tbSOCIALObjectProperty AS op1 ON o.id = op1.idSOCIALObject " : $query .= '';
		$search['estado']	  != '' ? $query .= " INNER JOIN tbSOCIALObjectProperty AS op2 ON o.id = op2.idSOCIALObject " : $query .= '';
		$search['cidade']	  != '' ? $query .= " INNER JOIN tbSOCIALObjectProperty AS op3 ON o.id = op3.idSOCIALObject " : $query .= '';
		$search['bairro']	  != '' ? $query .= " INNER JOIN tbSOCIALObjectProperty AS op4 ON o.id = op4.idSOCIALObject " : $query .= '';
		$search['afinidade']  != '' ? $query .= " INNER JOIN tbSOCIALObjectProperty AS op5 ON o.id = op5.idSOCIALObject " : $query .= '';
		$search['objetivo']	  != '' ? $query .= " INNER JOIN tbSOCIALObjectProperty AS op6 ON o.id = op6.idSOCIALObject " : $query .= '';
		$search['tema']		  != '' ? $query .= " INNER JOIN tbSOCIALObjectProperty AS op7 ON o.id = op7.idSOCIALObject " : $query .= '';
		
		/*
		$query .= ' INNER JOIN tbSOCIALObjectProperty AS op1 
					ON o.id = op1.idSOCIALObject 
					
					INNER JOIN tbSOCIALObjectProperty AS op2 
					ON o.id = op2.idSOCIALObject 
					
					INNER JOIN tbSOCIALObjectProperty AS op3 
					ON o.id = op3.idSOCIALObject 
					
					INNER JOIN tbSOCIALObjectProperty AS op4 
					ON o.id = op4.idSOCIALObject 
					
					INNER JOIN tbSOCIALObjectProperty AS op5 
					ON o.id = op5.idSOCIALObject 
					
					INNER JOIN tbSOCIALObjectProperty AS op6 
					ON o.id = op6.idSOCIALObject 
					
					INNER JOIN tbSOCIALObjectProperty AS op7 
					ON o.id = op7.idSOCIALObject 
					
					LEFT OUTER JOIN tbSOCIALObjectDetail AS od 
					ON o.id = od.idSOCIALObject ';
		//*/
	}
	
	$query .= 'WHERE o.iType = '.$objectType.' AND o.fDeleted <> 1 ';
	
	$values = '';
	
	
	/*
	if ($search != '') {
		
		$search['pais']		 != '' ? $query .= "AND op.idSOCIALProperty IN (5,12,32,46) AND op.sValue like '%".$search['pais']."%' " 	: $query .= '';
		$search['estado']	 != '' ? $query .= "AND op.idSOCIALProperty IN (6,13,33,47) AND op.sValue like '%".$search['estado']."%' " 	: $query .= '';
		$search['cidade']	 != '' ? $query .= "AND op.idSOCIALProperty IN (7,14,34,48) AND op.sValue like '%".$search['cidade']."%' " 	: $query .= '';
		$search['bairro']	 != '' ? $query .= "AND op.idSOCIALProperty IN (22,36) AND op.sValue like '%".$search['bairro']."%' " 		: $query .= '';
		$search['afinidade'] != '' ? $query .= "AND op.idSOCIALProperty IN (10) AND op.sValue like '%".$search['afinidade']."%' " 		: $query .= '';
		$search['objetivo']	 != '' ? $query .= "AND op.idSOCIALProperty IN (39) AND op.sValue = ".$search['objetivo']." " 				: $query .= '';
		$search['tema']		 != '' ? $query .= "AND op.idSOCIALProperty IN (40) AND op.sValue like '%".$search['tema']."%' " 			: $query .= '';
	}
	//*/
	
	if ($search != '') {
		
		$query .= "AND ( 1=1 ";
		
		$search['pais']		 != '' ? $query .= "AND (op1.idSOCIALProperty IN (5,12,32,46) AND op1.sValue like '%".$search['pais']."%') "	  : $query .= '';
		$search['estado']	 != '' ? $query .= "AND (op2.idSOCIALProperty IN (6,13,33,47) AND op2.sValue like '%".$search['estado']."%') "	  : $query .= '';
		$search['cidade']	 != '' ? $query .= "AND (op3.idSOCIALProperty IN (7,14,34,48) AND op3.sValue like '%".$search['cidade']."%') "	  : $query .= '';
		$search['bairro']	 != '' ? $query .= "AND (op4.idSOCIALProperty IN (22,36) 	  AND op4.sValue like '%".$search['bairro']."%') "	  : $query .= '';
		$search['afinidade'] != '' ? $query .= "AND (op5.idSOCIALProperty IN (10) 		  AND op5.sValue like '%".$search['afinidade']."%') " : $query .= '';
		$search['objetivo']	 != '' ? $query .= "AND (op6.idSOCIALProperty IN (39) 		  AND op6.sValue = ".$search['objetivo'].") "		  : $query .= '';
		$search['tema']		 != '' ? $query .= "AND (op7.idSOCIALProperty IN (40) 		  AND op7.sValue like '%".$search['tema']."%') "	  : $query .= '';
		
		$query .= ") ";
	}
	
	$query .= 'GROUP BY o.id ';
	$query .= 'ORDER BY o.sDisplayName, o.id; '; 
	
//	echo '<br>'.$query.'<br>';
	
	$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);

	$objects = array();
	while($row = mysql_fetch_assoc($result)) {
		$objects[] = $row;
	}

	return $objects;	
}



function friendshipStatus($idMyProfile, $idFriend) {
	
	$query = '	SELECT 
					oame.iType AS myRole,
					oafr.iType AS theirRole

				FROM tbSOCIALAssociation assoc

				INNER JOIN tbSOCIALObjectAssociation oame
				ON (assoc.id = oame.idSOCIALAssociation 
				AND oame.idSOCIALObject = '.$idMyProfile.')

				INNER JOIN tbSOCIALObjectAssociation oafr
				ON (assoc.id = oafr.idSOCIALAssociation 
				AND oafr.idSOCIALObject = '.$idFriend.')

				WHERE assoc.iType = 1; ';

	$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);

	$status = array();
	while($row = mysql_fetch_assoc($result)) {
		$status = $row;
	}

	return $status;

}

function followStatus($idMyProfile, $idPage) {
	
	$query = '	SELECT 
					oame.iType AS myRole,
					oapg.iType AS theirRole

				FROM tbSOCIALAssociation assoc

				INNER JOIN tbSOCIALObjectAssociation oame
				ON (assoc.id = oame.idSOCIALAssociation 
				AND oame.idSOCIALObject = '.$idMyProfile.')

				INNER JOIN tbSOCIALObjectAssociation oapg
				ON (assoc.id = oapg.idSOCIALAssociation 
				AND oapg.idSOCIALObject = '.$idPage.')

				WHERE assoc.iType = 2; ';

	$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);

	$status = array();
	while($row = mysql_fetch_assoc($result)) {
		$status = $row;
	}

	return $status;

}

function memberStatus($idMyProfile, $idGroup) {
	
	$query = '	SELECT 
					oame.iType AS myRole,
					oapg.iType AS theirRole

				FROM tbSOCIALAssociation assoc

				INNER JOIN tbSOCIALObjectAssociation oame
				ON (assoc.id = oame.idSOCIALAssociation 
				AND oame.idSOCIALObject = '.$idMyProfile.')

				INNER JOIN tbSOCIALObjectAssociation oapg
				ON (assoc.id = oapg.idSOCIALAssociation 
				AND oapg.idSOCIALObject = '.$idGroup.')

				WHERE assoc.iType = 5; ';

	$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);

	$status = array();
	while($row = mysql_fetch_assoc($result)) {
		$status = $row;
	}

	return $status;

}

function likeStatus($idMyProfile, $idObject) {
	
	$query = '	SELECT 
					oame.iType AS myRole,
					oaobj.iType AS theirRole

				FROM tbSOCIALAssociation assoc

				INNER JOIN tbSOCIALObjectAssociation oame
				ON (assoc.id = oame.idSOCIALAssociation 
				AND oame.idSOCIALObject = '.$idMyProfile.' 
				AND oame.iType = 5)

				INNER JOIN tbSOCIALObjectAssociation oaobj
				ON (assoc.id = oaobj.idSOCIALAssociation 
				AND oaobj.idSOCIALObject = '.$idObject.' 
				AND oaobj.iType = 3); ';

	$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);

	$status = array();
	while($row = mysql_fetch_assoc($result)) {
		$status = $row;
	}

	return $status;
}

function reportStatus($idMyProfile, $idObject) {
	
	$query = '	SELECT 
					oame.iType AS myRole,
					oaobj.iType AS theirRole

				FROM tbSOCIALAssociation assoc

				INNER JOIN tbSOCIALObjectAssociation oame
				ON (
					assoc.id = oame.idSOCIALAssociation 
					AND oame.idSOCIALObject = '.$idMyProfile.' 
					AND oame.iType = 4 
					AND assoc.iType = 12
					)

				INNER JOIN tbSOCIALObjectAssociation oaobj
				ON (
					assoc.id = oaobj.idSOCIALAssociation 
					AND oaobj.idSOCIALObject = '.$idObject.' 
					AND oaobj.iType = 8 
					AND assoc.iType = 12
					); ';

	$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);

	$status = array();
	while($row = mysql_fetch_assoc($result)) {
		$status = $row;
	}

	return $status;
}



// ---

$objectType;
$associationType;
$r_associationType;
$objectAssociationType;
$r_objectAssociationType;


getObjectTypeList();
getAssociationTypeList();
getObjectAssociationTypeList();

// ---





function ObjectSetProperty($idSOCIALObject, $idSOCIALProperty, $sValue) {
	
	global $conni;
	
	$idProfile 			= $_SESSION["loggedUser"];
	$idSOCIALObject 	= !isset($idSOCIALObject) 	? '' : $idSOCIALObject;
	$idSOCIALProperty 	= !isset($idSOCIALProperty) ? '' : $idSOCIALProperty;
	$sValue 			= !isset($sValue) 			? '' : $sValue;
	
	$idSOCIALObject 	= filter_var($idSOCIALObject, FILTER_SANITIZE_MAGIC_QUOTES);
	$idSOCIALProperty 	= filter_var($idSOCIALProperty, FILTER_SANITIZE_MAGIC_QUOTES);
	$sValue 			= filter_var($sValue, FILTER_SANITIZE_MAGIC_QUOTES);
	
	// busca a propriedade existente relacionada ao objeto
	$query = '	SET @idSOCIALObjectProperty = (
					SELECT 
						prop.id 
					
					FROM tbSOCIALObjectProperty prop 

					WHERE 	prop.idSOCIALObject 	= '.$idSOCIALObject.' 
					AND		prop.idSOCIALProperty 	= '.$idSOCIALProperty.' 
					
					LIMIT 0, 1
				); ';
	
	$query .= ' UPDATE tbSOCIALObjectProperty 
				SET    sValue = "'.$sValue.'" 
				WHERE  id = @idSOCIALObjectProperty; ';
	
	
	// echo $query;
	
	begin();
	
	if (mysqli_multi_query($conni, utf8_decode($query))) {
		commit();
		return true;
	} else {
		rollback();
		return false;
	}
	
}

function ObjectCreateProperty($idSOCIALObject, $idSOCIALProperty, $sValue) {
	
	global $conni;
	
	$idProfile 			= $_SESSION["loggedUser"];
	$idSOCIALObject 	= !isset($idSOCIALObject) 	? '' : $idSOCIALObject;
	$idSOCIALProperty 	= !isset($idSOCIALProperty) ? '' : $idSOCIALProperty;
	$sValue 			= !isset($sValue) 			? '' : $sValue;
	
	
	// busca a propriedade existente relacionada ao objeto
	/*
	$query = '	SET @idSOCIALObjectProperty = (
					SELECT 
						prop.id 
					
					FROM tbSOCIALObjectProperty prop 

					WHERE 	prop.idSOCIALObject 	= '.$idSOCIALObject.' 
					AND		prop.idSOCIALProperty 	= '.$idSOCIALProperty.' 
					
					LIMIT 0, 1
				); ';
	*/
	
	$query = ' INSERT INTO tbSOCIALObjectProperty (
					idSOCIALObject,
					idSOCIALProperty,
					sValue
				)
				VALUES 
				(
					'.$idSOCIALObject.',
					'.$idSOCIALProperty.',
					"'.$sValue.'"
				); ';
	
	
	// echo $query;
	
	begin();
	
	if (mysqli_multi_query($conni, utf8_decode($query))) {
		commit();
		return true;
	} else {
		rollback();
		return false;
	}
	
}







// TO-DO: criar como um método "create()" public em um objeto "Post"

function PageCreate($sFullName, $sDisplayName, $iPageType, $sDescription, $sSite, $idADMINCountry, $idADMINState, $idADMINCity, $sPostalCode, $sAddress, $sComplement, $sNeighborhood) {
	
	global $conni;
	
	$idProfile 		= $_SESSION["loggedUser"];
	
	$sFullName 		= !isset($sFullName)	 	? 'NULL' : $sFullName;
	$sDisplayName 	= !isset($sDisplayName)	 	? 'NULL' : $sDisplayName;
	$iPageType 		= !isset($iPageType)	 	? 'NULL' : $iPageType;
	$sDescription 	= !isset($sDescription)	 	? 'NULL' : $sDescription;
	$sSite 			= !isset($sSite)		 	? 'NULL' : $sSite;
	
	$idADMINCountry	= !isset($idADMINCountry) 	? 'NULL' : $idADMINCountry;
	$idADMINState 	= !isset($idADMINState) 	? 'NULL' : $idADMINState;
	$idADMINCity	= !isset($idADMINCity) 		? 'NULL' : $idADMINCity;
	
	$sPostalCode	= !isset($sPostalCode) 		? 'NULL' : $sPostalCode;
	$sAddress		= !isset($sAddress) 		? 'NULL' : $sAddress;
	$sComplement	= !isset($sComplement) 		? 'NULL' : $sComplement;
	$sNeighborhood	= !isset($sNeighborhood) 	? 'NULL' : $sNeighborhood;
	
	$query = '	SET @tsTimeStamp = CURRENT_TIMESTAMP; 

				INSERT INTO tbSOCIALObject 
					(
						id,
						sDisplayName,
						sDirectLink,
						iType,
						tsCreation
					)
				VALUES 
					(
						NULL, 
						"'.$sDisplayName.'", 
						"'.$sDisplayName.'", 
						2, 
						@tsTimeStamp
					);

				SET @pageId = LAST_INSERT_ID();
				
				INSERT INTO tbSOCIALObjectProperty 
					(
						id, 
						idSOCIALObject, 
						idSOCIALProperty, 
						sValue
					)
				VALUES 
					(
						NULL, 
						@pageId, 
						9, 
						"'.$sFullName.'"
					),
					(
						NULL, 
						@pageId, 
						18, 
						"'.$iPageType.'"
					),
					(
						NULL, 
						@pageId, 
						10, 
						"'.$sDescription.'"
					),
					(
						NULL, 
						@pageId, 
						19, 
						"'.$sSite.'"
					),
					(
						NULL, 
						@pageId, 
						12, 
						"'.$idADMINCountry.'"
					),
					(
						NULL, 
						@pageId, 
						13, 
						"'.$idADMINState.'"
					),
					(
						NULL, 
						@pageId, 
						14, 
						"'.$idADMINCity.'"
					),
					(
						NULL, 
						@pageId, 
						15, 
						"'.$sPostalCode.'"
					),
					(
						NULL, 
						@pageId, 
						20, 
						"'.$sAddress.'"
					),
					(
						NULL, 
						@pageId, 
						21, 
						"'.$sComplement.'"
					),
					(
						NULL, 
						@pageId, 
						22, 
						"'.$sNeighborhood.'"
					); ';
	
	// cria a associação que ligará o objeto page ao objeto profile do usuário criador
	$query .= '	INSERT INTO 
					tbSOCIALAssociation 
					(
						id,
						iType
					)
				VALUES (
						NULL,
						2
					);
					
				SET @idAssociation = LAST_INSERT_ID(); ';
	
	// cria as ligações de cada objeto à associação
	$query .= '	INSERT INTO	
					tbSOCIALObjectAssociation
					(
						id,
						iType,
						idSOCIALObject,
						idSOCIALAssociation,
						tsCreation
					)
				VALUES (
						NULL,
						3,
						@pageId,
						@idAssociation,
						@tsTimeStamp
					), 
					(
						NULL,
						4,
						'.$idProfile.',
						@idAssociation,
						@tsTimeStamp
					); ';
	
//	echo $query;
	
	begin();
	
	if (mysqli_multi_query($conni, utf8_decode($query))) {
		commit();
		return true;
	} else {
		rollback();
		return false;
	}
	
}




function GroupCreate(
						$sDisplayName,
						$iGroupType,
						$sGroupTheme,
						$dtBegin,
						$dtEnd,
						$iPeriodicity,
						$iPeriodicityDetail,
						$tsOracao,
						$idADMINCountry,
						$idADMINState,
						$idADMINCity,
						$sDescription,
						$sSite,
						$invitedFriends
					) {
	
	$idProfile 			= $_SESSION["loggedUser"];
	
	$sDisplayName 		= !isset($sDisplayName)	 		? 'NULL' : $sDisplayName;
	$sDirectLink		= '';
	$iGroupType 		= !isset($iGroupType)	 		? 'NULL' : $iGroupType;
	$sGroupTheme 		= !isset($sGroupTheme)	 		? 'NULL' : $sGroupTheme;
	$dtBegin 			= !isset($dtBegin)	 			? 'NULL' : $dtBegin;
	$dtEnd 				= !isset($dtEnd)	 			? 'NULL' : $dtEnd;
	$iPeriodicity 		= !isset($iPeriodicity)	 		? 'NULL' : $iPeriodicity;
	$iPeriodicityDetail = !isset($iPeriodicityDetail)	? 'NULL' : $iPeriodicityDetail;
	$tsOracao 			= !isset($tsOracao)	 			? 'NULL' : $tsOracao;
	$idADMINCountry		= !isset($idADMINCountry) 		? 'NULL' : $idADMINCountry;
	$idADMINState 		= !isset($idADMINState) 		? 'NULL' : $idADMINState;
	$idADMINCity		= !isset($idADMINCity) 			? 'NULL' : $idADMINCity;
	$sDescription 		= !isset($sDescription)	 		? 'NULL' : $sDescription;
	$sSite 				= !isset($sSite)		 		? 'NULL' : $sSite;
	$invitedFriends 	= !isset($invitedFriends) 		? 'NULL' : $invitedFriends;
	$sAvatarPath		= NULL;
	
	$sDisplayName 			= filter_var($sDisplayName, FILTER_SANITIZE_MAGIC_QUOTES);
	$sDirectLink 			= filter_var($sDirectLink, FILTER_SANITIZE_MAGIC_QUOTES);
	$iGroupType 			= filter_var($iGroupType, FILTER_SANITIZE_MAGIC_QUOTES);
	$sGroupTheme 			= filter_var($sGroupTheme, FILTER_SANITIZE_MAGIC_QUOTES);
	$dtBegin 				= filter_var($dtBegin, FILTER_SANITIZE_MAGIC_QUOTES);
	$dtEnd 					= filter_var($dtEnd, FILTER_SANITIZE_MAGIC_QUOTES);
	$iPeriodicity 			= filter_var($iPeriodicity, FILTER_SANITIZE_MAGIC_QUOTES);
	$iPeriodicityDetail 	= filter_var($iPeriodicityDetail, FILTER_SANITIZE_MAGIC_QUOTES);
	$tsOracao 				= filter_var($tsOracao, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINCountry 		= filter_var($idADMINCountry, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINState 			= filter_var($idADMINState, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINCity 			= filter_var($idADMINCity, FILTER_SANITIZE_MAGIC_QUOTES);
	$sDescription 			= filter_var($sDescription, FILTER_SANITIZE_MAGIC_QUOTES);
	$sSite 					= filter_var($sSite, FILTER_SANITIZE_MAGIC_QUOTES);
	$invitedFriends 		= filter_var($invitedFriends, FILTER_SANITIZE_MAGIC_QUOTES);
	$sAvatarPath 			= filter_var($sAvatarPath, FILTER_SANITIZE_MAGIC_QUOTES);
	
	$invitedFriends = explode(',',$invitedFriends);
	
	begin();
	
	// timestamp para ser usado nas demais queries:
	$query = 'SELECT CURRENT_TIMESTAMP AS tsTimeStamp; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	while($row = mysql_fetch_assoc($result)) {
		$tsTimeStamp = $row['tsTimeStamp'];
	}
	
	
	// cria o objeto GROUP
	// iType = 5 (GROUP)
	$query = '	INSERT INTO 
				tbSOCIALObject 
				(
					id,
					sDisplayName,
					sDirectLink,
					iType,
					tsCreation
				)
			VALUES (
					NULL,
					"'.$sDisplayName.'",
					"'.$sDirectLink.'",
					5, 
					"'.$tsTimeStamp.'"
				); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	// id do objeto, para ser usado nas demais queries:
	$query = 'SELECT LAST_INSERT_ID() AS idGroup; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	while($row = mysql_fetch_assoc($result)) {
		$idGroup = $row['idGroup'];
	}
	
	
	
	// cria a descrição do objeto na tabela tbSOCIALObjectDetail
	$query = '	INSERT INTO 
				tbSOCIALObjectDetail 
				(
					id, 
					idSOCIALObject, 
					sKey, 
					sValue
				)
			VALUES (
					NULL,
					'.$idGroup.',
					"sContent",
					"'.$sDescription.'"
				); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	
	
	// cria a associação que ligará o objeto GROUP ao objeto profile do usuário criador
	// iType = 5 (GROUP)
	$query = '	INSERT INTO 
					tbSOCIALAssociation 
					(
						id,
						iType
					)
				VALUES (
						NULL,
						5
					); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	// id da associação, para ser usado nas demais queries:
	$query = 'SELECT LAST_INSERT_ID() AS idAssociation; ';
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	while($row = mysql_fetch_assoc($result)) {
		$idAssociation = $row['idAssociation'];
	}
	
	
	
	// cria as ligações de cada objeto à associação
	// iType = 3  (OBJECT)
	// iType = 4  (OWNER)
	// iType = 14 (MEMBER)
	$query = '	INSERT INTO	
					tbSOCIALObjectAssociation
					(
						id,
						iType,
						idSOCIALObject,
						idSOCIALAssociation,
						tsCreation
					)
				VALUES (
						NULL,
						3,
						'.$idGroup.',
						'.$idAssociation.',
						"'.$tsTimeStamp.'"
					), 
					(
						NULL,
						4,
						'.$idProfile.',
						'.$idAssociation.',
						"'.$tsTimeStamp.'"
					)';
	
	foreach ($invitedFriends as $friend) {
		
		$query .= ',(
						NULL,
						14,
						'.$friend.',
						'.$idAssociation.',
						"'.$tsTimeStamp.'"
					)';
	}
	
	$query .= '; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	// cria as propriedades do GROUP
	$query = '	INSERT INTO	
					tbSOCIALObjectProperty (idSOCIALObject, idSOCIALProperty, sValue)
									VALUES ('.$idGroup.', 39, "'.$iGroupType.'"); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	$query = '	INSERT INTO	
					tbSOCIALObjectProperty (idSOCIALObject, idSOCIALProperty, sValue)
									VALUES ('.$idGroup.', 40, "'.$sGroupTheme.'"); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	$query = '	INSERT INTO	
					tbSOCIALObjectProperty (idSOCIALObject, idSOCIALProperty, sValue)
									VALUES ('.$idGroup.', 41, "'.$dtBegin.'"); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	$query = '	INSERT INTO	
					tbSOCIALObjectProperty (idSOCIALObject, idSOCIALProperty, sValue)
									VALUES ('.$idGroup.', 42, "'.$dtEnd.'"); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	$query = '	INSERT INTO	
					tbSOCIALObjectProperty (idSOCIALObject, idSOCIALProperty, sValue)
									VALUES ('.$idGroup.', 43, "'.$iPeriodicity.'"); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	$query = '	INSERT INTO	
					tbSOCIALObjectProperty (idSOCIALObject, idSOCIALProperty, sValue)
									VALUES ('.$idGroup.', 44, "'.$iPeriodicityDetail.'"); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	$query = '	INSERT INTO	
					tbSOCIALObjectProperty (idSOCIALObject, idSOCIALProperty, sValue)
									VALUES ('.$idGroup.', 45, "'.$tsOracao.'"); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	$query = '	INSERT INTO	
					tbSOCIALObjectProperty (idSOCIALObject, idSOCIALProperty, sValue)
									VALUES ('.$idGroup.', 46, "'.$idADMINCountry.'"); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	$query = '	INSERT INTO	
					tbSOCIALObjectProperty (idSOCIALObject, idSOCIALProperty, sValue)
									VALUES ('.$idGroup.', 47, "'.$idADMINState.'"); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	$query = '	INSERT INTO	
					tbSOCIALObjectProperty (idSOCIALObject, idSOCIALProperty, sValue)
									VALUES ('.$idGroup.', 48, "'.$idADMINCity.'"); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	$query = '	INSERT INTO	
					tbSOCIALObjectProperty (idSOCIALObject, idSOCIALProperty, sValue)
									VALUES ('.$idGroup.', 49, "'.$sSite.'"); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	/*
	$query = '	INSERT INTO	
					tbSOCIALObjectProperty (idSOCIALObject, idSOCIALProperty, sValue)
									VALUES ('.$idGroup.', 50, "'.$sAvatarPath.'"); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	*/
	
	
	
	
	// se tudo deu certo até aqui:
	commit();
	return true;
	
}





function GroupUpdate(
						$idGroup,
						$sDisplayName,
						$iGroupType,
						$sGroupTheme,
						$dtBegin,
						$dtEnd,
						$iPeriodicity,
						$iPeriodicityDetail,
						$tsOracao,
						$idADMINCountry,
						$idADMINState,
						$idADMINCity,
						$sDescription,
						$sSite
					) {
	
	$idProfile 			= $_SESSION["loggedUser"];
	
	$idGroup 			= !isset($idGroup)	 			? 'NULL' : $idGroup;
	$sDisplayName 		= !isset($sDisplayName)	 		? 'NULL' : $sDisplayName;
	$sDirectLink		= '';
	$iGroupType 		= !isset($iGroupType)	 		? 'NULL' : $iGroupType;
	$sGroupTheme 		= !isset($sGroupTheme)	 		? 'NULL' : $sGroupTheme;
	$dtBegin 			= !isset($dtBegin)	 			? 'NULL' : $dtBegin;
	$dtEnd 				= !isset($dtEnd)	 			? 'NULL' : $dtEnd;
	$iPeriodicity 		= !isset($iPeriodicity)	 		? 'NULL' : $iPeriodicity;
	$iPeriodicityDetail = !isset($iPeriodicityDetail)	? 'NULL' : $iPeriodicityDetail;
	$tsOracao 			= !isset($tsOracao)	 			? 'NULL' : $tsOracao;
	$idADMINCountry		= !isset($idADMINCountry) 		? 'NULL' : $idADMINCountry;
	$idADMINState 		= !isset($idADMINState) 		? 'NULL' : $idADMINState;
	$idADMINCity		= !isset($idADMINCity) 			? 'NULL' : $idADMINCity;
	$sDescription 		= !isset($sDescription)	 		? 'NULL' : $sDescription;
	$sSite 				= !isset($sSite)		 		? 'NULL' : $sSite;
	$sAvatarPath		= NULL;
//	$invitedFriends 	= !isset($invitedFriends) 		? 'NULL' : $invitedFriends;
	
	$idGroup 				= filter_var($idGroup, FILTER_SANITIZE_MAGIC_QUOTES);
	$sDisplayName 			= filter_var($sDisplayName, FILTER_SANITIZE_MAGIC_QUOTES);
	$sDirectLink 			= filter_var($sDirectLink, FILTER_SANITIZE_MAGIC_QUOTES);
	$iGroupType 			= filter_var($iGroupType, FILTER_SANITIZE_MAGIC_QUOTES);
	$sGroupTheme 			= filter_var($sGroupTheme, FILTER_SANITIZE_MAGIC_QUOTES);
	$dtBegin 				= filter_var($dtBegin, FILTER_SANITIZE_MAGIC_QUOTES);
	$dtEnd 					= filter_var($dtEnd, FILTER_SANITIZE_MAGIC_QUOTES);
	$iPeriodicity 			= filter_var($iPeriodicity, FILTER_SANITIZE_MAGIC_QUOTES);
	$iPeriodicityDetail 	= filter_var($iPeriodicityDetail, FILTER_SANITIZE_MAGIC_QUOTES);
	$tsOracao 				= filter_var($tsOracao, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINCountry 		= filter_var($idADMINCountry, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINState 			= filter_var($idADMINState, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINCity 			= filter_var($idADMINCity, FILTER_SANITIZE_MAGIC_QUOTES);
	$sDescription 			= filter_var($sDescription, FILTER_SANITIZE_MAGIC_QUOTES);
	$sSite 					= filter_var($sSite, FILTER_SANITIZE_MAGIC_QUOTES);
//	$invitedFriends 		= filter_var($invitedFriends, FILTER_SANITIZE_MAGIC_QUOTES);
	$sAvatarPath 			= filter_var($sAvatarPath, FILTER_SANITIZE_MAGIC_QUOTES);
	
	// $invitedFriends = explode(',',$invitedFriends);
	
	
	
	
	
	
	begin();
		
		$queryObject = '
					UPDATE tbSOCIALObject 
					SET 
						sDisplayName = "'.$sDisplayName.'" 
					WHERE id = '.$idGroup.'
					; ';
		
		$resultObject = mysql_query(utf8_decode($queryObject));
		
		if ($resultObject == false) {
			rollback();
			return false;
		}
		
		// propriedades padrão do objeto:
		// cria todas com valor NULL para evitar erros no SQL
		$queryDefaultProperties = '
			INSERT INTO tbSOCIALObjectProperty 
			(idSOCIALObject,idSOCIALProperty,sValue) 
			SELECT '.$idGroup.' AS idSOCIALObject, 
			p.id AS idSOCIALProperty, 
			NULL AS sValue 
			
			FROM tbSOCIALProperty p 
			
			LEFT OUTER JOIN 
			(
			SELECT idSOCIALProperty 
			FROM tbSOCIALObjectProperty 
			WHERE idSocialObject = '.$idGroup.' 
			)t
			ON t.idSOCIALProperty = p.id 
			
			WHERE t.idSOCIALProperty IS NULL 
			AND p.iObjectType = 5; ';
		
		$resultDefaultProperties = mysql_query(utf8_decode($queryDefaultProperties));
		
		if ($resultDefaultProperties == false) {
			rollback();
			return false;
		}
		
		
		// descrição do objeto na tabela tbSOCIALObjectDetail
		$queryDetail = '
				UPDATE tbSOCIALObjectDetail 
				SET	sValue = "'.$sDescription.'"
				
				WHERE idSOCIALObject = '.$idGroup.' 
				AND   sKey = "sContent" 
				LIMIT 1; ';
		
		$resultDetail = mysql_query(utf8_decode($queryDetail));
		
		if ($resultDetail == false) {
			rollback();
			return false;
		}
		
		
		
		// propriedades do objeto:
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$iGroupType.'" 
			
			WHERE 	idSOCIALObject = '.$idGroup.' 
			AND 	idSOCIALProperty = 39 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		// propriedades do objeto:
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$sGroupTheme.'" 
			
			WHERE 	idSOCIALObject = '.$idGroup.' 
			AND 	idSOCIALProperty = 40 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		// propriedades do objeto:
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$dtBegin.'" 
			
			WHERE 	idSOCIALObject = '.$idGroup.' 
			AND 	idSOCIALProperty = 41 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		// propriedades do objeto:
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$dtEnd.'" 
			
			WHERE 	idSOCIALObject = '.$idGroup.' 
			AND 	idSOCIALProperty = 42 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		// propriedades do objeto:
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$iPeriodicity.'" 
			
			WHERE 	idSOCIALObject = '.$idGroup.' 
			AND 	idSOCIALProperty = 43 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		// propriedades do objeto:
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$iPeriodicityDetail.'" 
			
			WHERE 	idSOCIALObject = '.$idGroup.' 
			AND 	idSOCIALProperty = 44 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		// propriedades do objeto:
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$tsOracao.'" 
			
			WHERE 	idSOCIALObject = '.$idGroup.' 
			AND 	idSOCIALProperty = 45 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		// propriedades do objeto:
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$idADMINCountry.'" 
			
			WHERE 	idSOCIALObject = '.$idGroup.' 
			AND 	idSOCIALProperty = 46 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		// propriedades do objeto:
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$idADMINState.'" 
			
			WHERE 	idSOCIALObject = '.$idGroup.' 
			AND 	idSOCIALProperty = 47 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		// propriedades do objeto:
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$idADMINCity.'" 
			
			WHERE 	idSOCIALObject = '.$idGroup.' 
			AND 	idSOCIALProperty = 48 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		// propriedades do objeto:
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$sSite.'" 
			
			WHERE 	idSOCIALObject = '.$idGroup.' 
			AND 	idSOCIALProperty = 49 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		/*
		// propriedades do objeto:
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$sAvatarPath.'" 
			
			WHERE 	idSOCIALObject = '.$idGroup.' 
			AND 	idSOCIALProperty = 50 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		*/
		
		// propriedades do objeto:
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$iGroupType.'" 
			
			WHERE 	idSOCIALObject = '.$idGroup.' 
			AND 	idSOCIALProperty = 39 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		
	
	
	
	
	
	
	
	
	// se tudo deu certo até aqui:
	commit();
	return true;
	
}



function sendInvite($idUser, $listFriends, $sMessage) {

	$idUser 		= isset($idUser) 		? $idUser 		: NULL;
	$listFriends 	= isset($listFriends) 	? $listFriends 	: NULL;
	$sMessage 		= isset($sMessage) 		? $sMessage 	: NULL;
	$nameUser		= getObject($idUser);
	$nameUser		= $nameUser['sDisplayName'];
	
//	$nameUser		= $socialObject['sDisplayName'];
	
	$friend = array();
	$friend = explode(',',$listFriends);
	
	$countFriend = count($friend);
	
	$subject = 'Convite para Rede Social Um Deus';
	
	for($n = 0; $n < $countFriend; $n++) {
	
		$to = '';
		$to = $friend[$n];
		
		$content  = "";
		$content .= "<p>Você recebeu uma convite de ".$nameUser." para fazer parte da <a href='http://umdeus.org/' target='_blank'>Rede Social Um Deus</a>:</p>\n";
		$content .= "<p><q style='font-style:italic;font-size:14px;color:#666666;'>".$sMessage."</q></p>\n";
		$content .= "<p>Para se cadastrar na Rede Social UmDeus, clique <a href='http://umdeus.org/profile-create.php' target='_blank'>aqui</a> ou acesse http://umdeus.org/profile-create.php</p>\n";
		
		
		/*
		$content	= "";
		$content	.= "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
		$content	.= "<html>\n";
		$content	.= "<head>\n";
		$content	.= 	"<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>\n";
		$content	.= 	"<meta property='og:title' content='Convite - Rede Social UmDeus'>\n";
		$content	.= 	"<title>Convite - Rede Social UmDeus</title>\n";
		$content	.= "</head>\n";
		$content	.= "<body leftmargin='0' marginwidth='0' topmargin='0' marginheight='0' offset='0' style='font-family:Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;background-color:#FAFAFA;width:100% !important;'>\n";
		$content	.= 	"<center>\n";
		$content	.= 		"<table border='0' cellpadding='0' cellspacing='0' height='100%' width='100%' style='margin:0;padding:0;height:100% !important;width:100% !important;'>\n";
		$content	.= 		"<tr><td align='center' valign='top'><br />\n";
		$content	.= 		"<table border='0' cellpadding='0' cellspacing='0' width='600' id='templateContainer'>\n";
		$content	.= 			"<tr><td style='height:18px;background-color:#6E2B60;padding:10px;font-family:Arial,Helvetica,sans-serif;'>\n";
		$content	.= 				"<h1 style='color:#FFF;font-size:16px;font-family:Arial,Helvetica,sans-serif;margin:0px;padding:0px'><a href='http://lab.saboia.info/' target='_blank' style='color:#FFF;text-decoration:none'>Rede Social - Um Deus</a></h1>\n";
		$content	.= 			"</td></tr>\n";
		$content	.= 			"<tr><td style='background-color:#EEEEEE;padding:30px 10px;font-family:Arial,Helvetica,sans-serif;font-size:12px;'>\n";
		$content	.= 				"<div id='logo'><a href='http://lab.saboia.info/'><img src='http://lab.saboia.info/img/logo.png' alt='Rede Social Um Deus'></a></div>\n";
		$content	.= 			"</td></tr>\n";
		$content	.= 			"<tr><td style='padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:12px;'>\n";
		$content	.= 				"<p>Você recebeu uma convite de ".$nameUser." para fazer parte da <a href='http://umdeus.org/' target='_blank'>Rede Social Um Deus</a>:</p>\n";
		$content	.= 				"<p><q style='font-style:italic;font-size:14px;color:#666666;'>".$sMessage."</q></p>\n";
		$content	.= 				"<p>Para se cadastrar na Rede Social UmDeus, clique <a href='http://umdeus.org/profile-create.php' target='_blank'>aqui</a> ou acesse http://umdeus.org/profile-create.php</p>\n";
		$content	.= 			"</td></tr>\n";
		$content	.= 		"</table>\n";
		$content	.= 		"</td></tr>\n";
		$content	.= 		"</table>\n";
		$content	.= 	"</center>\n";
		$content	.= "</body>\n";
		$content	.= "</html>\n";
		*/
		
		
		
		$send = sendMail2($to, $content, $subject);
		
		if($send == false) {
			return false;
		}
		
	}
	
	return true;

}




function ProfileCreate($sFullName, $sEmail, $sDisplayName, $sPassword, $dtBirthday, $idADMINCountry, $idADMINState, $idADMINCity, $sPostalCode, $sAvatarPath) {
	
	$sFullName 			= filter_var($sFullName, FILTER_SANITIZE_MAGIC_QUOTES);
	$sEmail				= filter_var($sEmail, FILTER_SANITIZE_MAGIC_QUOTES);
	$sDisplayName		= filter_var($sDisplayName, FILTER_SANITIZE_MAGIC_QUOTES);
	$sPassword			= filter_var($sPassword, FILTER_SANITIZE_MAGIC_QUOTES);
	$dtBirthday			= filter_var($dtBirthday, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINCountry		= filter_var($idADMINCountry, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINState		= filter_var($idADMINState, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINCity		= filter_var($idADMINCity, FILTER_SANITIZE_MAGIC_QUOTES);
	$sPostalCode		= filter_var($sPostalCode, FILTER_SANITIZE_MAGIC_QUOTES);
	$sAvatarPath		= filter_var($sAvatarPath, FILTER_SANITIZE_MAGIC_QUOTES);
	
	
	
	$profile = new SocialProfile();
	
	$profile->sDisplayName 	= !isset($sDisplayName) ? NULL : $sDisplayName;
	$profile->sDirectLink 	= NULL;
	
	$profile->objectProperties['sFullName'] 		= !isset($sFullName) 		? NULL : $sFullName;
	$profile->objectProperties['sEmail'] 			= !isset($sEmail) 			? NULL : $sEmail;
	$profile->objectProperties['sPassword'] 		= !isset($sPassword) 		? NULL : $sPassword;
	$profile->objectProperties['dtBirthday'] 		= !isset($dtBirthday) 		? NULL : $dtBirthday;
	$profile->objectProperties['idADMINCountry'] 	= !isset($idADMINCountry) 	? NULL : $idADMINCountry;
	$profile->objectProperties['idADMINState'] 		= !isset($idADMINState) 	? NULL : $idADMINState;
	$profile->objectProperties['idADMINCity'] 		= !isset($idADMINCity) 		? NULL : $idADMINCity;
	$profile->objectProperties['sPostalCode'] 		= !isset($sPostalCode) 		? NULL : $sPostalCode;
	$profile->objectProperties['sAvatarPath'] 		= NULL;
	
	
	return $profile->create();
	
	
	/*
	global $conni;
	
	$sFullName 		= !isset($sFullName)	 ? 'NULL' : $sFullName;
	$sDisplayName 	= !isset($sDisplayName)	 ? 'NULL' : $sDisplayName;
	$sEmail 		= !isset($sEmail)		 ? 'NULL' : $sEmail;
	$sPassword 		= !isset($sPassword)	 ? 'NULL' : $sPassword;
	$dtBirthday 	= !isset($dtBirthday)	 ? 'NULL' : $dtBirthday;
	$idADMINCountry = !isset($idADMINCountry)? 'NULL' : $idADMINCountry;
	$idADMINState 	= !isset($idADMINState)	 ? 'NULL' : $idADMINState;
	$idADMINCity 	= !isset($idADMINCity)	 ? 'NULL' : $idADMINCity;
	$sPostalCode 	= !isset($sPostalCode)	 ? 'NULL' : $sPostalCode;
	$sAvatarPath 	= 'NULL';
	
	
	$query = '	SET @tsTimeStamp = CURRENT_TIMESTAMP; 

				INSERT INTO tbSOCIALObject 
					(
						id,
						sDisplayName,
						sDirectLink,
						iType,
						tsCreation
					)
				VALUES 
					(
						NULL, 
						"'.$sDisplayName.'", 
						"nome-de-exibicao", 
						1, 
						@tsTimeStamp
					);

				SET @profileId = LAST_INSERT_ID();
				
				INSERT INTO tbSOCIALObjectProperty 
					(
						id, 
						idSOCIALObject, 
						idSOCIALProperty, 
						sValue
					)
				VALUES 
					(
						NULL, 
						@profileId, 
						1, 
						"'.$sFullName.'"
					),
					(
						NULL, 
						@profileId, 
						3, 
						"'.$sEmail.'"
					),
					(
						NULL, 
						@profileId, 
						16, 
						"'.$sPassword.'"
					),
					(
						NULL, 
						@profileId, 
						4, 
						"'.$dtBirthday.'"
					),
					(
						NULL, 
						@profileId, 
						5, 
						"'.$idADMINCountry.'"
					),
					(
						NULL, 
						@profileId, 
						6, 
						"'.$idADMINState.'"
					),
					(
						NULL, 
						@profileId, 
						7, 
						"'.$idADMINCity.'"
					),
					(
						NULL, 
						@profileId, 
						8, 
						"'.$sPostalCode.'"
					),
					(
						NULL, 
						@profileId, 
						2, 
						NULL
					); ';
	
	// TO-DO: faltam demais propriedades
	
	begin();
	
	if (mysqli_multi_query($conni, utf8_decode($query))) {
		commit();
		return true;
	} else {
		rollback();
		return false;
	}
	// */
}


function ProfileUpdate($idProfile, $sFullName, $sEmail, $sDisplayName, $dtBirthday, $idADMINCountry, $idADMINState, $idADMINCity, $sPostalCode, $spiritualAffinities) {
	
	$idProfile				= filter_var($idProfile, FILTER_SANITIZE_MAGIC_QUOTES);
	$sFullName 				= filter_var($sFullName, FILTER_SANITIZE_MAGIC_QUOTES);
	$sEmail					= filter_var($sEmail, FILTER_SANITIZE_MAGIC_QUOTES);
	$sDisplayName			= filter_var($sDisplayName, FILTER_SANITIZE_MAGIC_QUOTES);
	$dtBirthday				= filter_var($dtBirthday, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINCountry			= filter_var($idADMINCountry, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINState			= filter_var($idADMINState, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINCity			= filter_var($idADMINCity, FILTER_SANITIZE_MAGIC_QUOTES);
	$sPostalCode			= filter_var($sPostalCode, FILTER_SANITIZE_MAGIC_QUOTES);
	$spiritualAffinities	= filter_var($spiritualAffinities, FILTER_SANITIZE_MAGIC_QUOTES);
	
	
	
	// carrega as propriedades atuais do perfil:
	$profile = new SocialProfile();
	$profile->id = !isset($idProfile) ? NULL : $idProfile;
	$profile->load();
	
	// novas propriedades, para serem atualizadas no perfil:
	$profile->sDisplayName 	= !isset($sDisplayName) ? NULL : $sDisplayName;
	
	$profile->objectProperties['sFullName'] 		= !isset($sFullName) 		? NULL : $sFullName;
	$profile->objectProperties['sEmail'] 			= !isset($sEmail) 			? NULL : $sEmail;
	$profile->objectProperties['dtBirthday'] 		= !isset($dtBirthday) 		? NULL : $dtBirthday;
	$profile->objectProperties['idADMINCountry'] 	= !isset($idADMINCountry) 	? NULL : $idADMINCountry;
	$profile->objectProperties['idADMINState'] 		= !isset($idADMINState) 	? NULL : $idADMINState;
	$profile->objectProperties['idADMINCity'] 		= !isset($idADMINCity) 		? NULL : $idADMINCity;
	$profile->objectProperties['sPostalCode'] 		= !isset($sPostalCode) 		? NULL : $sPostalCode;
	
	// afinidades espirituais:
	$profile->spiritualAffinities = explode(',',$spiritualAffinities);
	
	return $profile->update();
	
}

function updateConfig($idUser, $sFullName, $sDisplayName, $dtBirthday, $idADMINCountry, $idADMINState, $idADMINCity, $sPostalCode, $sEmailAtual, $sEmailNovo, $sPasswordAtual, $sPasswordNovo, $spiritualAffinities) {
	
	$idUser 				= filter_var($idUser, FILTER_SANITIZE_MAGIC_QUOTES);
	$sFullName				= filter_var($sFullName, FILTER_SANITIZE_MAGIC_QUOTES);
	$sDisplayName			= filter_var($sDisplayName, FILTER_SANITIZE_MAGIC_QUOTES);
	$dtBirthday				= filter_var($dtBirthday, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINCountry			= filter_var($idADMINCountry, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINState			= filter_var($idADMINState, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINCity			= filter_var($idADMINCity, FILTER_SANITIZE_MAGIC_QUOTES);
	$sPostalCode			= filter_var($sPostalCode, FILTER_SANITIZE_MAGIC_QUOTES);
	$sEmailAtual			= filter_var($sEmailAtual, FILTER_SANITIZE_MAGIC_QUOTES);
	$sEmailNovo				= filter_var($sEmailNovo, FILTER_SANITIZE_MAGIC_QUOTES);
	$sPasswordAtual			= filter_var($sPasswordAtual, FILTER_SANITIZE_MAGIC_QUOTES);
	$sPasswordNovo			= filter_var($sPasswordNovo, FILTER_SANITIZE_MAGIC_QUOTES);
	//$spiritualAffinities	= filter_var($spiritualAffinities, FILTER_SANITIZE_MAGIC_QUOTES);
	
	
	// carrega as propriedades atuais do perfil:
	$profile		= new SocialProfile();
	$profile->id 	= !isset($idUser) ? NULL : $idUser;
	$profile->load();
	
	// novas propriedades, para serem atualizadas no perfil:
	$profile->sDisplayName 	= !isset($sDisplayName) ? NULL : $sDisplayName;
	
	$profile->objectProperties['sFullName'] 		= !isset($sFullName) 		? NULL : $sFullName;
	$profile->objectProperties['dtBirthday'] 		= !isset($dtBirthday) 		? NULL : $dtBirthday;
	$profile->objectProperties['idADMINCountry'] 	= !isset($idADMINCountry) 	? NULL : $idADMINCountry;
	$profile->objectProperties['idADMINState'] 		= !isset($idADMINState) 	? NULL : $idADMINState;
	$profile->objectProperties['idADMINCity'] 		= !isset($idADMINCity) 		? NULL : $idADMINCity;
	$profile->objectProperties['sPostalCode'] 		= !isset($sPostalCode) 		? NULL : $sPostalCode;
	
	// afinidades espirituais:
	
	
	//$profile->spiritualAffinities = explode(',',$spiritualAffinities);
	$profile->spiritualAffinities = array();
	
	// verifica se o $sEmailAtual é igual ao email cadastrado do usuário
	$query  = 'SELECT sValue 
			   FROM tbSOCIALObjectProperty
			   WHERE idSOCIALObject = '.$idUser.' AND idSOCIALProperty = 3';
			  
	$result =  mysql_query(utf8_decode($query));
	
	while($row = mysql_fetch_assoc($result)) {
		$checkEmail = $row['sValue'];
	}
	
	// caso sejam iguais
	if($checkEmail == $sEmailAtual) {
	
		// verifica se o $sEmailNovo já foi cadastrado
		$query  = 'SELECT id 
				   FROM tbSOCIALObjectProperty
				   WHERE sValue = "'.$sEmailNovo.'" AND idSOCIALProperty = 3';
			  
		$result =  mysql_query(utf8_decode($query));
		
		while($row = mysql_fetch_assoc($result)) {
			$checkExistEmail = $row['id'];
		}
	
		if(!isset($checkExistEmail)) {
		
			$query  = 'UPDATE 
							tbSOCIALObjectProperty 
						SET 
							sValue = "'.$sEmailNovo.'"
						WHERE 	idSOCIALProperty = 3 
						AND		idSOCIALObject = '.$idUser.';';
			  
			$result =  mysql_query(utf8_decode($query));
			
			if ($result == false) {
				rollback();
				return false;
			}
		
		} else {
			return "erro email";
		}
	}
	
	// verifica se a $sPasswordlAtual é igual a senha do usuário
	$query  = 'SELECT sValue 
			   FROM tbSOCIALObjectProperty
			   WHERE idSOCIALObject = '.$idUser.' AND idSOCIALProperty = 16';
			  
	$result =  mysql_query(utf8_decode($query));
	
	while($row = mysql_fetch_assoc($result)) {
		$checkPassword = $row['sValue'];
	}
	
	if($checkPassword == $sPasswordAtual) {
		
		$query  = 'UPDATE 
						tbSOCIALObjectProperty 
					SET 
						sValue = "'.$sPasswordNovo.'"
					WHERE 	idSOCIALProperty = 16
					AND		idSOCIALObject = '.$idUser.';';
		  
		$result =  mysql_query(utf8_decode($query));
		
		if ($result == false) {
			rollback();
			return false;
		}
		
	}
	
	return $profile->update();
}


function deleteAccount($idUser, $sMotivo) {

	$idUser	 = !isset($idUser)  ? NULL : $idUser;
	$sMotivo = !isset($sMotivo) ? NULL : $sMotivo;
		
	$query  = 'UPDATE 
					tbSOCIALObject 
				SET 
					fDeleted = 1
				WHERE id  = '.$idUser.';';
	  
	$result =  mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	$query  = 'INSERT INTO 
				tbSOCIALObjectDetail
					(
						id,	
						idSOCIALObject,
						sKey,
						sValue
					)
				VALUES (
						NULL,
						'.$idUser.',
						"sMotivo",
						"'.$sMotivo.'"
					);';
	  
	$result =  mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	return true;

}

function PageUpdate($idPage, $sDisplayName, $sDescription, $sAddress, $sComplement, $sNeighborhood, $idADMINCountry, $idADMINState, $idADMINCity, $sPostalCode) {
	
	$idPage 			= filter_var($idPage, FILTER_SANITIZE_MAGIC_QUOTES);
	$sDisplayName		= filter_var($sDisplayName, FILTER_SANITIZE_MAGIC_QUOTES);
	$sDescription		= filter_var($sDescription, FILTER_SANITIZE_MAGIC_QUOTES);
	$sAddress			= filter_var($sAddress, FILTER_SANITIZE_MAGIC_QUOTES);
	$sComplement		= filter_var($sComplement, FILTER_SANITIZE_MAGIC_QUOTES);
	$sNeighborhood		= filter_var($sNeighborhood, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINCountry		= filter_var($idADMINCountry, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINState		= filter_var($idADMINState, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINCity		= filter_var($idADMINCity, FILTER_SANITIZE_MAGIC_QUOTES);
	$sPostalCode		= filter_var($sPostalCode, FILTER_SANITIZE_MAGIC_QUOTES);
	
	
	// carrega as propriedades atuais do perfil:
	$page = new SocialPage();
	$page->id = !isset($idPage) ? NULL : $idPage;
	$page->load();
	
	// novas propriedades, para serem atualizadas no perfil:
	$page->sDisplayName = !isset($sDisplayName) ? NULL : $sDisplayName;
	
	$page->objectProperties['sFullName'] 		= !isset($sDisplayName) 	? NULL : $sDisplayName;
	
	$page->objectProperties['sDescription'] 	= !isset($sDescription) 	? NULL : $sDescription;
	$page->objectProperties['sAddress'] 		= !isset($sAddress) 		? NULL : $sAddress;
	$page->objectProperties['sComplement'] 		= !isset($sComplement) 		? NULL : $sComplement;
	$page->objectProperties['sNeighborhood'] 	= !isset($sNeighborhood) 	? NULL : $sNeighborhood;
	
	$page->objectProperties['idADMINCountry'] 	= !isset($idADMINCountry) 	? NULL : $idADMINCountry;
	$page->objectProperties['idADMINState'] 	= !isset($idADMINState) 	? NULL : $idADMINState;
	$page->objectProperties['idADMINCity'] 		= !isset($idADMINCity) 		? NULL : $idADMINCity;
	$page->objectProperties['sPostalCode'] 		= !isset($sPostalCode) 		? NULL : $sPostalCode;
	
	return $page->update();
	
}

function ProfileProposeFriendship($idProposerProfile, $idGuestProfile) {
	
	global $conni;
	
	$idProposerProfile 	= !isset($idProposerProfile) ? '' : $idProposerProfile;
	$idGuestProfile 	= !isset($idGuestProfile) 	 ? '' : $idGuestProfile;
	
	$query = '	SET @idProposerProfile	= '.$idProposerProfile.';
				SET @idGuestProfile 	= '.$idGuestProfile.';
				SET @tsTimeStamp		= CURRENT_TIMESTAMP; ';
	
	// cria a associação que ligará os dois objetos
	// iType=1 (FRIENDSHIP)
	$query .= '	INSERT INTO 
					tbSOCIALAssociation 
					(
						id,
						iType
					)
				VALUES (
						NULL,
						1 
					);

				SET @idFriendshipAssociation = LAST_INSERT_ID(); ';
	
	// cria as ligações de cada objeto à associação
	// iType=1 (FRIEND)
	// iType=2 (INVITED_FRIEND)
	$query .= '	INSERT INTO	
					tbSOCIALObjectAssociation
					(
						id,
						iType,
						idSOCIALObject,
						idSOCIALAssociation,
						tsCreation
					)
				VALUES (
						NULL,
						1,
						@idProposerProfile,
						@idFriendshipAssociation,
						@tsTimeStamp
					), 
					(
						NULL,
						2, 
						@idGuestProfile,
						@idFriendshipAssociation,
						@tsTimeStamp
					); ';
	
	begin();
	
	if (mysqli_multi_query($conni, utf8_decode($query))) {
		commit();
		return true;
	} else {
		rollback();
		return false;
	}
}

function ProfileAcceptFriendship($idFriend, $idMyProfile) {

	global $conni;
	
	$idFriend 		= !isset($idFriend) 	? '' : $idFriend;
	$idMyProfile 	= !isset($idMyProfile) 	? '' : $idMyProfile;
	
	$query = 'SET @tsTimeStamp = CURRENT_TIMESTAMP; ';

	// busca a associação de amizade pendente, criada para relacionar os dois perfis
	// assoc.iType 	= 1 (FRIENDSHIP)
	// oa.iType 	= 2 (INVITED_FRIEND) // oa.iType = 1 OR 2 (FRIEND OR INVITED_FRIEND)
	$query .= '	SET @idFriendshipAssociation = (
					SELECT 
						assoc.id
						
					FROM tbSOCIALAssociation assoc

					INNER JOIN tbSOCIALObjectAssociation oa 
					ON oa.idSOCIALAssociation = assoc.id 

					INNER JOIN tbSOCIALObjectAssociation oa2 
					ON (oa2.idSOCIALObject = '.$idFriend.' 
						AND oa2.idSOCIALAssociation = assoc.id 
						AND oa2.idSOCIALObject <> oa.idSOCIALObject)

					WHERE 	oa.idSOCIALObject 	= '.$idMyProfile.' 
					AND		assoc.iType 		= 1 
					AND 	oa.iType 			IN (1,2) 

					LIMIT 0, 1
				); ';

	// atualiza a ligação entre a associação e o profile
	// iType = 1(FRIEND)
	// iType = 2(INVITED_FRIEND)
	
	$query .= '	UPDATE 
					tbSOCIALObjectAssociation 
				SET 
					iType = 1,
					tsLastUpdate = @tsTimeStamp
				WHERE 	idSOCIALAssociation = @idFriendshipAssociation 
				AND 	iType = 2 
				AND		idSOCIALObject = '.$idMyProfile.'; ';
	
	// echo $query;
	
	begin();
	
	if (mysqli_multi_query($conni, utf8_decode($query))) {
		commit();
		return true;
	} else {
		rollback();
		return false;
	}
}

function ProfileEndFriendship($idProfile, $idFriendProfile) {
	
	global $conni;
	
	$idProfile 			= !isset($idProfile) ? '' : $idProfile;
	$idFriendProfile 	= !isset($idFriendProfile) 	 ? '' : $idFriendProfile;
	
	$query = 'SET @tsTimeStamp = CURRENT_TIMESTAMP; ';

	// busca a associação de amizade confirmada, criada para relacionar os dois perfis
	// assoc.iType 	= 1 (FRIENDSHIP)
	// oa.iType 	= 1 (FRIEND)
	$query .= '	SET @idFriendshipAssociation = (
					SELECT 
						assoc.id
						
					FROM tbSOCIALAssociation assoc

					INNER JOIN tbSOCIALObjectAssociation oa 
					ON oa.idSOCIALAssociation = assoc.id 

					INNER JOIN tbSOCIALObjectAssociation oa2 
					ON (oa2.idSOCIALObject = '.$idFriendProfile.' 
						AND oa2.idSOCIALAssociation = assoc.id 
						AND oa2.idSOCIALObject <> oa.idSOCIALObject)

					WHERE 	oa.idSOCIALObject 	= '.$idProfile.' 
					AND		assoc.iType 		= 1 
					AND 	oa.iType 			IN (1,2) 
					LIMIT 0, 1
				); ';


	// exclui a ligação de ambos profiles com a associação
	// iType = 1 (FRIEND) / 2 (INVITED_FRIEND)
	$query .= '	DELETE FROM 
					tbSOCIALObjectAssociation 

				WHERE 	idSOCIALAssociation = @idFriendshipAssociation 
				AND 	iType = 1; ';
			//	AND 	iType IN (1,2); ';

	// exclui a associação
	// iType = 1 (FRIENDSHIP)
	$query .= '	DELETE FROM 
					tbSOCIALAssociation 

				WHERE 	id = @idFriendshipAssociation 
				AND 	iType = 1; ';
	
	// echo $query;
	
	begin();
	
	if (mysqli_multi_query($conni, utf8_decode($query))) {
		commit();
		return true;
	} else {
		rollback();
		return false;
	}
	
}



function PostCreate($idReferredObject, $sContent, $sDataVideo) {
	
	global $conni;
	
	$idProfile 			= $_SESSION["loggedUser"];
	$idReferredObject 	= !isset($idReferredObject) ? '' : $idReferredObject;
	$sContent 			= !isset($sContent) 		? '' : $sContent;
	$sDataVideo 		= !isset($sDataVideo) 		? '' : $sDataVideo;
	
	$idReferredObject 	= filter_var($idReferredObject, FILTER_SANITIZE_MAGIC_QUOTES);
	$sContent 			= filter_var($sContent, FILTER_SANITIZE_MAGIC_QUOTES);
	$sDataVideo 		= filter_var($sDataVideo, FILTER_SANITIZE_MAGIC_QUOTES);
	
	$sDisplayName 		= 'Post';
	$sDirectLink 		= strtolower(strip_tags($sDisplayName));
	
	$query = 'SET @tsTimeStamp = CURRENT_TIMESTAMP; ';

	// cria o objeto Post
	// iType = 3 (POST)
	$query .= '	INSERT INTO 
					tbSOCIALObject 
					(
						id,
						sDisplayName,
						sDirectLink,
						iType,
						tsCreation
					)
				VALUES (
						NULL,
						"'.$sDisplayName.'",
						"'.$sDirectLink.'",
						3, 
						@tsTimeStamp
					);

				SET @idPost = LAST_INSERT_ID(); ';

	// cria a associação que ligará o objeto post ao objeto profile do usuário criador
	// iType = 3 (POST)
	$query .= '	INSERT INTO 
					tbSOCIALAssociation 
					(
						id,
						iType
					)
				VALUES (
						NULL,
						3
					);

				SET @idAssociation = LAST_INSERT_ID(); ';

	// cria as ligações de cada objeto à associação
	// iType = 3 (OBJECT)
	// iType = 4 (OWNER)
	// iType = 8 (REFERRED)
	$query .= '	INSERT INTO	
					tbSOCIALObjectAssociation
					(
						id,
						iType,
						idSOCIALObject,
						idSOCIALAssociation,
						tsCreation
					)
				VALUES (
						NULL,
						3,
						@idPost,
						@idAssociation,
						@tsTimeStamp
					), 
					(
						NULL,
						4,
						'.$idProfile.',
						@idAssociation,
						@tsTimeStamp
					), 
					(
						NULL,
						8,
						'.$idReferredObject.',
						@idAssociation,
						@tsTimeStamp
					); ';
	
	// detalhes (conteúdo) do post:
	$query .= 'INSERT INTO	
					tbSOCIALObjectDetail
					(
						id,
						idSOCIALObject,
						sKey,
						sValue
					)
				VALUES (
						NULL,
						@idPost,
						"sContent",
						"'.$sContent.'"
					); ';
	
	// propriedade (vídeo opcional) do post:
	if ($sDataVideo != '') {
		
		$query .= 'INSERT INTO tbSOCIALObjectProperty 
					(
						id, 
						idSOCIALObject, 
						idSOCIALProperty, 
						sValue
					)
				VALUES 
					(
						NULL, 
						@idPost, 
						23, 
						"'.$sDataVideo.'"
					); ';
		
	}
	
	// echo $query;
	
	begin();
	
	if (mysqli_multi_query($conni, utf8_decode($query))) {
		commit();
		return true;
	} else {
		rollback();
		return false;
	}
	
}


function PostShare($idOriginalPost) {
	
	global $conni;
	
	$idProfile 			= $_SESSION["loggedUser"];
	$idReferredObject 	= $_SESSION["loggedUser"];
	$idOriginalPost 	= !isset($idOriginalPost) 	? '' : $idOriginalPost;
	
	
	$sDisplayName 		= 'Post';
	$sDirectLink 		= strtolower(strip_tags($sDisplayName));
	
	$query = 'SET @tsTimeStamp = CURRENT_TIMESTAMP; ';
	
	// recupera o conteúdo do post original
	$query .= '	SET @sContent = (
					SELECT od.sValue 
					FROM tbSOCIALObject o 
					INNER JOIN tbSOCIALObjectDetail od 
					ON (od.idSOCIALObject = o.id 
					AND od.sKey = "sContent") 
					WHERE o.id = '.$idOriginalPost.'
				); ';
	
	
	
	// recupera o iType do post original
	$query .= '	SET @iTypePost = (
					SELECT o.iType 
					FROM tbSOCIALObject o 
					WHERE o.id = '.$idOriginalPost.'
				); ';
	
	// cria o objeto Post ou Photo
	// iType = 3,9 (POST,PHOTO) - @iTypePost definido pelo select anterior
	$query .= '	INSERT INTO 
					tbSOCIALObject 
					(
						id,
						sDisplayName,
						sDirectLink,
						iType,
						tsCreation
					)
				VALUES (
						NULL,
						"'.$sDisplayName.'",
						"'.$sDirectLink.'",
						@iTypePost, 
						@tsTimeStamp
					);

				SET @idPost = LAST_INSERT_ID(); ';

	// cria a associação que ligará o objeto post ao objeto profile do usuário criador
	// define qual será o iType da associação, baseado no @iTypePost
	$query .= '	SET @iTypeAssoc = (
					SELECT IF(@iTypePost=3,3,7)
				); ';
	
	$query .= '	INSERT INTO 
					tbSOCIALAssociation 
					(
						id,
						iType
					)
				VALUES (
						NULL,
						@iTypeAssoc
					);

				SET @idAssociation = LAST_INSERT_ID(); ';

	// cria as ligações de cada objeto à associação
	// iType = 3  (OBJECT)
	// iType = 4  (OWNER)
	// iType = 8  (REFERRED)
	// iType = 17 (ORIGINAL)
	$query .= '	INSERT INTO	
					tbSOCIALObjectAssociation
					(
						id,
						iType,
						idSOCIALObject,
						idSOCIALAssociation,
						tsCreation
					)
				VALUES (
						NULL,
						3,
						@idPost,
						@idAssociation,
						@tsTimeStamp
					), 
					(
						NULL,
						4,
						'.$idProfile.',
						@idAssociation,
						@tsTimeStamp
					), 
					(
						NULL,
						8,
						'.$idReferredObject.',
						@idAssociation,
						@tsTimeStamp
					), 
					(
						NULL,
						17,
						'.$idOriginalPost.',
						@idAssociation,
						@tsTimeStamp
					); ';
	
	// detalhes (conteúdo) do post:
	$query .= 'INSERT INTO	
					tbSOCIALObjectDetail
					(
						id,
						idSOCIALObject,
						sKey,
						sValue
					)
				VALUES (
						NULL,
						@idPost,
						"sContent",
						@sContent
					); ';
	
	
	
	// seleciona a propriedade sDataPhoto (photo opcional) do post:
	$query .= ' INSERT INTO tbSOCIALObjectProperty 
					(
						idSOCIALObject, 
						idSOCIALProperty, 
						sValue
					)
				SELECT
					@idPost AS idSOCIALObject,
					25 AS idSOCIALProperty,
					op.sValue 
				FROM tbSOCIALObjectProperty op

				WHERE op.idSOCIALProperty = 25 
				AND op.idSOCIALObject = '.$idOriginalPost.'
				AND IFNULL(op.sValue,"") <> ""; ';
	
	
	// seleciona a propriedade sDataVideo (vídeo opcional) do post:
	$query .= ' INSERT INTO tbSOCIALObjectProperty 
					(
						idSOCIALObject, 
						idSOCIALProperty, 
						sValue
					)
				SELECT
					@idPost AS idSOCIALObject,
					23 AS idSOCIALProperty,
					op.sValue 
				FROM tbSOCIALObjectProperty op

				WHERE op.idSOCIALProperty = 23 
				AND op.idSOCIALObject = '.$idOriginalPost.'
				AND IFNULL(op.sValue,"") <> ""; ';
				
	
	begin();
	
	if (mysqli_multi_query($conni, utf8_decode($query))) {
		commit();
		return true;
	} else {
		rollback();
		return false;
	}
	
}


function ReportAbuse($idObject, $sAbuseType, $sDescription) {
	
	$idProfile 		= $_SESSION["loggedUser"];
	$idObject  		= !isset($idObject) 	? '' : $idObject;
	$sAbuseType  	= !isset($sAbuseType) 	? '' : $sAbuseType;
	$sDescription  	= !isset($sDescription) ? '' : $sDescription;
	
	$idObject 		= filter_var($idObject, FILTER_SANITIZE_MAGIC_QUOTES);
	$sAbuseType 	= filter_var($sAbuseType, FILTER_SANITIZE_MAGIC_QUOTES);
	$sDescription 	= filter_var($sDescription, FILTER_SANITIZE_MAGIC_QUOTES);
	
	
	begin();
	
	// --------------------------------------------------
	// time stamp usado nas demais queries
	$query = 'SELECT CURRENT_TIMESTAMP AS tsTimeStamp;';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	while($row = mysql_fetch_assoc($result)) {
		$tsTimeStamp = $row['tsTimeStamp'];
	}
	
	
	// --------------------------------------------------
	// cria o objeto do tipo REPORT
	$query = 'INSERT INTO 
				tbSOCIALObject 
				(
					sDisplayName, 
					sDirectLink, 
					iType, 
					tsCreation
				) 
				VALUES 
				(
					"Report",
					"report",
					12,
					"'.$tsTimeStamp.'"
				); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	// --------------------------------------------------
	// recupera o id do objeto REPORT (idReport)
	$query = 'SELECT LAST_INSERT_ID() AS idReport; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	while($row = mysql_fetch_assoc($result)) {
		$idReport = $row['idReport'];
	}
	
	
	
	// --------------------------------------------------
	// cria a associação do tipo REPORT
	$query = 'INSERT INTO 
				tbSOCIALAssociation 
				(
					id,
					iType
				) 
				VALUES 
				(
					NULL,
					12
				); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	// --------------------------------------------------
	// recupera o id da associação do tipo REPORT (idReportAssociation)
	$query = 'SELECT LAST_INSERT_ID() AS idReportAssociation; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	while($row = mysql_fetch_assoc($result)) {
		$idReportAssociation = $row['idReportAssociation'];
	}
	
	
	
	// --------------------------------------------------
	// cria as ligações entre os objetos PROFILE e POST/PHOTO/COMMENT 
	// ao objeto REPORT, usando a associação
	// iType = 3 (OBJECT)
	// iType = 4 (OWNER)
	// iType = 8 (REFERRED)
	$query = 'INSERT INTO 
				tbSOCIALObjectAssociation 
				(
					iType, 
					idSOCIALObject, 
					idSOCIALAssociation, 
					tsCreation, 
					tsLastUpdate
				) 
				VALUES 
				(
					3, 
					'.$idReport.', 
					'.$idReportAssociation.', 
					"'.$tsTimeStamp.'",
					"'.$tsTimeStamp.'"
				), 
				(
					4, 
					'.$idProfile.', 
					'.$idReportAssociation.', 
					"'.$tsTimeStamp.'",
					"'.$tsTimeStamp.'"
				), 
				(
					8, 
					'.$idObject.', 
					'.$idReportAssociation.', 
					"'.$tsTimeStamp.'",
					"'.$tsTimeStamp.'"
				) ; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	// --------------------------------------------------
	// propriedades da denúncia:
	$query = 'INSERT INTO 
					tbSOCIALObjectProperty
					(
						id,
						idSOCIALObject,
						idSOCIALProperty,
						sValue
					)
				VALUES 
					(
						NULL,
						'.$idReport.',
						51,
						"'.$sAbuseType.'"
					),
					(
						NULL,
						'.$idReport.',
						52,
						"'.$sDescription.'"
					); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	// se tudo deu certo até aqui:
	commit();
	return true;
	
}

function deleteReport($idReport) {
	
	$idProfile 	= $_SESSION["loggedUser"];
	$idReport  	= !isset($idReport) ? '' : $idReport;
	$idReport 	= filter_var($idReport, FILTER_SANITIZE_MAGIC_QUOTES);
	
	
	begin();
	
	
	// --------------------------------------------------
	// busca os ids de todos os objetos e associações que precisam ser excluídas
	$query = 'SELECT 
				o.id AS reportId, 
				oaRep.id AS referredAssociationId,
				a.id AS reportAssociationId,
				oaObj.id AS ownerAssociationId

				FROM tbSOCIALObject o 

				INNER JOIN tbSOCIALObjectAssociation oaRep
				ON (oaRep.idSOCIALObject = o.id AND oaRep.iType = 3) 

				INNER JOIN tbSOCIALAssociation a 
				ON (a.id = oaRep.idSOCIALAssociation AND a.iType = 12) 

				INNER JOIN tbSOCIALObjectAssociation oaObj 
				ON (oaObj.idSOCIALAssociation = a.id AND oaObj.iType = 8) 

				WHERE o.id = '.$idReport.' 
				AND o.iType = 12; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	while($row = mysql_fetch_assoc($result)) {
		$reportId 				= $row['reportId'];
		$referredAssociationId 	= $row['referredAssociationId'];
		$reportAssociationId 	= $row['reportAssociationId'];
		$ownerAssociationId 	= $row['ownerAssociationId'];
	}



	// --------------------------------------------------
	// deleta os objetos e associações
	$query = 'DELETE FROM tbSOCIALObject 
				WHERE id = '.$reportId.' 
				AND iType = 12 
				LIMIT 1; ';

	$result = mysql_query(utf8_decode($query));

	if ($result == false) {
		rollback();
		return false;
	}


	$query = 'DELETE FROM tbSOCIALObjectAssociation 
				WHERE id = '.$referredAssociationId.' 
				AND iType = 3 
				LIMIT 1; ';

	$result = mysql_query(utf8_decode($query));

	if ($result == false) {
		rollback();
		return false;
	}


	$query = 'DELETE FROM tbSOCIALAssociation 
				WHERE id = '.$reportAssociationId.' 
				AND iType = 12 
				LIMIT 1; ';

	$result = mysql_query(utf8_decode($query));

	if ($result == false) {
		rollback();
		return false;
	}


	$query = 'DELETE FROM tbSOCIALObjectAssociation 
				WHERE id = '.$ownerAssociationId.' 
				AND iType = 8 
				LIMIT 1; ';
	
	$result = mysql_query(utf8_decode($query));

	if ($result == false) {
		rollback();
		return false;
	}



	// se tudo deu certo até aqui:
	commit();
	return true;

}



function deleteObject($idObject) {

	$idObject = !isset($idObject) ? NULL : $idObject;
	
	
	begin();
	
	$query  = 'UPDATE tbSOCIALObject 
				SET fDeleted = 1 
				WHERE id = '.$idObject.'; ';

	$result =  mysql_query(utf8_decode($query));

	if ($result == false) {
		rollback();
		return false;
	}


	// se tudo deu certo até aqui:
	commit();
	return true;

}






function AlbumCreate($idProfile, $sDisplayName) {
	
//	$idProfile 			= $_SESSION["loggedUser"];
	$idProfile 			= !isset($idProfile) 		? '' : $idProfile;
	$sDisplayName 		= !isset($sDisplayName) 	? '' : $sDisplayName;
	$sDirectLink 		= strtolower(strip_tags($sDisplayName));
	
	$idProfile 		= filter_var($idProfile, FILTER_SANITIZE_MAGIC_QUOTES);
	$sDisplayName 	= filter_var($sDisplayName, FILTER_SANITIZE_MAGIC_QUOTES);
	
	
	begin();
	
	// timestamp para ser usado nas demais queries:
	$query = 'SELECT CURRENT_TIMESTAMP AS tsTimeStamp; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	while($row = mysql_fetch_assoc($result)) {
		$tsTimeStamp = $row['tsTimeStamp'];
	}
	
	
	// cria o objeto Album
	// iType = 8 (ALBUM)
	$query = '	INSERT INTO 
				tbSOCIALObject 
				(
					id,
					sDisplayName,
					sDirectLink,
					iType,
					tsCreation
				)
			VALUES (
					NULL,
					"'.$sDisplayName.'",
					"'.$sDirectLink.'",
					8, 
					"'.$tsTimeStamp.'"
				); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	// id do objeto, para ser usado nas demais queries:
	$query = 'SELECT LAST_INSERT_ID() AS idAlbum; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	while($row = mysql_fetch_assoc($result)) {
		$idAlbum = $row['idAlbum'];
	}
	
	
	
	// cria a associação que ligará o objeto Album ao objeto profile do usuário criador
	// iType = 8 (ALBUM)
	$query = '	INSERT INTO 
					tbSOCIALAssociation 
					(
						id,
						iType
					)
				VALUES (
						NULL,
						8
					); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	// id da associação, para ser usado nas demais queries:
	$query = 'SELECT LAST_INSERT_ID() AS idAssociation; ';
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	while($row = mysql_fetch_assoc($result)) {
		$idAssociation = $row['idAssociation'];
	}
	
	
	
	// cria as ligações de cada objeto à associação
	// iType = 3 (OBJECT)
	// iType = 4 (OWNER)
	$query = '	INSERT INTO	
					tbSOCIALObjectAssociation
					(
						id,
						iType,
						idSOCIALObject,
						idSOCIALAssociation,
						tsCreation
					)
				VALUES (
						NULL,
						3,
						'.$idAlbum.',
						'.$idAssociation.',
						"'.$tsTimeStamp.'"
					), 
					(
						NULL,
						4,
						'.$idProfile.',
						'.$idAssociation.',
						"'.$tsTimeStamp.'"
					); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	
	// se tudo deu certo até aqui:
	commit();
	return true;
//	return $idPost;
	
}

function PhotoCreate($idReferredObject, $sContent) {
	
	$idProfile 			= $_SESSION["loggedUser"];
	$idReferredObject 	= !isset($idReferredObject) ? '' : $idReferredObject;
	$sContent 			= !isset($sContent) 		? '' : $sContent;
	
	$sDisplayName 		= 'Photo';
	$sDirectLink 		= strtolower(strip_tags($sDisplayName));
	
	
	$idReferredObject = filter_var($idReferredObject, FILTER_SANITIZE_MAGIC_QUOTES);
	$sContent 		  = filter_var($sContent, FILTER_SANITIZE_MAGIC_QUOTES);
	
	
	begin();
	
	// timestamp para ser usado nas demais queries:
	$query = 'SELECT CURRENT_TIMESTAMP AS tsTimeStamp; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	while($row = mysql_fetch_assoc($result)) {
		$tsTimeStamp = $row['tsTimeStamp'];
	}
	
	
	// cria o objeto Photo
	// iType = 9 (PHOTO)
	$query = '	INSERT INTO 
				tbSOCIALObject 
				(
					id,
					sDisplayName,
					sDirectLink,
					iType,
					tsCreation
				)
			VALUES (
					NULL,
					"'.$sDisplayName.'",
					"'.$sDirectLink.'",
					9, 
					"'.$tsTimeStamp.'"
				); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	// id do objeto, para ser usado nas demais queries:
	$query = 'SELECT LAST_INSERT_ID() AS idPost; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	while($row = mysql_fetch_assoc($result)) {
		$idPost = $row['idPost'];
	}
	
	
	
	// cria a associação que ligará o objeto Photo ao objeto profile do usuário criador
	// iType = 7 (PHOTO)
	$query = '	INSERT INTO 
					tbSOCIALAssociation 
					(
						id,
						iType
					)
				VALUES (
						NULL,
						7
					); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	// id da associação, para ser usado nas demais queries:
	$query = 'SELECT LAST_INSERT_ID() AS idAssociation; ';
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	while($row = mysql_fetch_assoc($result)) {
		$idAssociation = $row['idAssociation'];
	}
	
	
	
	// cria as ligações de cada objeto à associação
	// iType = 3 (OBJECT)
	// iType = 4 (OWNER)
	// iType = 8 (REFERRED)
	$query = '	INSERT INTO	
					tbSOCIALObjectAssociation
					(
						id,
						iType,
						idSOCIALObject,
						idSOCIALAssociation,
						tsCreation
					)
				VALUES (
						NULL,
						3,
						'.$idPost.',
						'.$idAssociation.',
						"'.$tsTimeStamp.'"
					), 
					(
						NULL,
						4,
						'.$idProfile.',
						'.$idAssociation.',
						"'.$tsTimeStamp.'"
					), 
					(
						NULL,
						8,
						'.$idReferredObject.',
						'.$idAssociation.',
						"'.$tsTimeStamp.'"
					); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	// detalhes (conteúdo de texto) do post:
	$query = 'INSERT INTO 
					tbSOCIALObjectDetail
					(
						id,
						idSOCIALObject,
						sKey,
						sValue
					)
				VALUES (
						NULL,
						'.$idPost.',
						"sContent",
						"'.$sContent.'"
					); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	// Neste ponto, a photo foi criada no banco de dados;
	
	
	// ----------------------------------------------------------------------
	// agrupa o objeto PHOTO recém-postado em um álbum padrão (se postado na timeline -> idReferredObject não é um ÁLBUM)
	// ou agrupa o objeto PHOTO em um álbum especificado (se postado em álbum -> idReferredObject é um ALBUM)
	
	// verifica se idReferredObject é um ALBUM
	
	$query = 'SELECT iType FROM tbSOCIALObject WHERE id = '.$idReferredObject.'; ';
	$result = mysql_query(utf8_decode($query));
	while($row = mysql_fetch_assoc($result)) {
		$iType = $row['iType'];
	}
	
	if ($iType != 8) {
		// não é um ÁLBUM!
		// vamos postar no álbum de Fotos do Mural (ou criar um álbum de Fotos do Mural caso não exista)
	
		// verifica se já existe um Album para este usuário
		$query = 'SELECT 
					a.id AS idAssociation 

					FROM tbSOCIALAssociation a 

					INNER JOIN tbSOCIALObjectAssociation oa 
					ON oa.idSOCIALAssociation = a.id 

					INNER JOIN tbSOCIALObject o 
					ON o.id = oa.idSOCIALObject 

					WHERE a.iType = 8 
					AND o.id = '.$idProfile.' 

					LIMIT 0,1; ';
		
		$result = mysql_query(utf8_decode($query));
		
		while($row = mysql_fetch_assoc($result)) {
			$idAlbumAssociation = $row['idAssociation'];
		}
	
	} else {
		
		// é um ÁLBUM!
		// vamos postar no álbum recebido como parâmetro em idReferredObject
		
		// recupera o id da ASSOCIATION do ÁLBUM recebido
		$query = 'SELECT 
					a.id AS idAssociation

					FROM tbSOCIALAssociation a

					INNER JOIN tbSOCIALObjectAssociation oa
					ON oa.idSOCIALAssociation = a.id

					INNER JOIN tbSOCIALObject o 
					ON o.id = oa.idSOCIALObject 

					WHERE a.iType = 8 
					AND o.id = '.$idReferredObject.'

					LIMIT 0,1; ';
		
		$result = mysql_query(utf8_decode($query));
		
		while($row = mysql_fetch_assoc($result)) {
			$idAlbumAssociation = $row['idAssociation'];
		}
		
	}
	
	
	
	// ----------------------------------------------------------------------
	// aqui sabemos se estamos postando em um ÁLBUM ou no mural de um PROFILE
	
	
	
	// cria o objeto Album (apenas se ainda não houver um álbum)
	// iType = 8 (ALBUM)
	if(!isset($idAlbumAssociation)) {
		
		$query = '	INSERT INTO 
					tbSOCIALObject 
					(
						id,
						sDisplayName,
						sDirectLink,
						iType,
						tsCreation
					)
				VALUES (
						NULL,
						"Fotos do Mural",
						"fotos-do-mural",
						8, 
						"'.$tsTimeStamp.'"
					); ';
		
		$result = mysql_query(utf8_decode($query));
		
		if ($result == false) {
			rollback();
			return false;
		}
		
		// id do objeto, para ser usado nas demais queries:
		$query = 'SELECT LAST_INSERT_ID() AS idAlbum; ';
		
		$result = mysql_query(utf8_decode($query));
		
		if ($result == false) {
			rollback();
			return false;
		}
		while($row = mysql_fetch_assoc($result)) {
			$idAlbum = $row['idAlbum'];
		}
		
		
		
		// cria a associação que ligará o objeto Album ao objeto profile do usuário criador
		// iType = 8 (ALBUM)
		$query = '	INSERT INTO 
						tbSOCIALAssociation 
						(
							id,
							iType
						)
					VALUES (
							NULL,
							8
						); ';
		
		$result = mysql_query(utf8_decode($query));
		
		if ($result == false) {
			rollback();
			return false;
		}
		
		
		// id da associação, para ser usado nas demais queries:
		$query = 'SELECT LAST_INSERT_ID() AS idAssociation; ';
		$result = mysql_query(utf8_decode($query));
		
		if ($result == false) {
			rollback();
			return false;
		}
		while($row = mysql_fetch_assoc($result)) {
			$idAssociation = $row['idAssociation'];
		}
		
		
		
		// cria as ligações de cada objeto à associação
		// iType = 3 (OBJECT)	// O ALBUM
		// iType = 4 (OWNER)	// O USUÁRIO
		// iType = 8 (REFERRED) // A PHOTO
		$query = '	INSERT INTO	
						tbSOCIALObjectAssociation
						(
							id,
							iType,
							idSOCIALObject,
							idSOCIALAssociation,
							tsCreation
						)
					VALUES (
							NULL,
							3,
							'.$idAlbum.',
							'.$idAssociation.',
							"'.$tsTimeStamp.'"
						), 
						(
							NULL,
							4,
							'.$idProfile.',
							'.$idAssociation.',
							"'.$tsTimeStamp.'"
						), 
						(
							NULL,
							8,
							'.$idPost.',
							'.$idAssociation.',
							"'.$tsTimeStamp.'"
						); ';
		
		$result = mysql_query(utf8_decode($query));
		
		if ($result == false) {
			rollback();
			return false;
		}
		
		
	// o Album já existe, apenas vamos usá-lo:
	} else {
		
		// cria APENAS as ligações da photo à associação
		// iType = 8 (REFERRED) // A PHOTO
		$query = '	INSERT INTO	
						tbSOCIALObjectAssociation
						(
							id,
							iType,
							idSOCIALObject,
							idSOCIALAssociation,
							tsCreation
						)
					VALUES (
							NULL,
							8,
							'.$idPost.',
							'.$idAlbumAssociation.',
							"'.$tsTimeStamp.'"
						); ';
		
		$result = mysql_query(utf8_decode($query));
		
		if ($result == false) {
			rollback();
			return false;
		}
		
	}
	
	// Neste ponto, o álbum foi criado no banco de dados, 
	// e a foto foi adicionada ao álbum;
	
	
	
	
	
	
	// se tudo deu certo até aqui:
	commit();
	return $idPost;
	
}








function EventCreate($idReferredObject, $sDisplayName, $tsBegin, $tsEnd, $sAddress, $sComplement, $idADMINCountry, $idADMINState, $idADMINCity, $sNeighborhood, $sPostalCode, $sSite,$sDescription) {
	
	$idProfile 			= $_SESSION["loggedUser"];
	
	$idReferredObject 	= !isset($idReferredObject) ? '' : $idReferredObject;
	$sDisplayName		= !isset($sDisplayName) 	? NULL : $sDisplayName;
	$tsBegin			= !isset($tsBegin) 			? NULL : $tsBegin;
	$tsEnd				= !isset($tsEnd) 			? NULL : $tsEnd;
	$sAddress			= !isset($sAddress) 		? NULL : $sAddress;
	$sComplement		= !isset($sComplement) 		? NULL : $sComplement;
	$idADMINCountry		= !isset($idADMINCountry) 	? NULL : $idADMINCountry;
	$idADMINState		= !isset($idADMINState) 	? NULL : $idADMINState;
	$idADMINCity		= !isset($idADMINCity) 		? NULL : $idADMINCity;
	$sNeighborhood		= !isset($sNeighborhood) 	? NULL : $sNeighborhood;
	$sPostalCode		= !isset($sPostalCode) 		? NULL : $sPostalCode;
	$sSite				= !isset($sSite) 			? NULL : $sSite;
	$sDescription		= !isset($sDescription) 	? NULL : $sDescription;
	
	$sDirectLink 		= strtolower(strip_tags($sDisplayName));
	
	
	$idReferredObject 	= filter_var($idReferredObject, FILTER_SANITIZE_MAGIC_QUOTES);
	$sDisplayName 		= filter_var($sDisplayName, FILTER_SANITIZE_MAGIC_QUOTES);
	$tsBegin 			= filter_var($tsBegin, FILTER_SANITIZE_MAGIC_QUOTES);
	$tsEnd 				= filter_var($tsEnd, FILTER_SANITIZE_MAGIC_QUOTES);
	$sAddress 			= filter_var($sAddress, FILTER_SANITIZE_MAGIC_QUOTES);
	$sComplement 		= filter_var($sComplement, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINCountry 	= filter_var($idADMINCountry, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINState 		= filter_var($idADMINState, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINCity 		= filter_var($idADMINCity, FILTER_SANITIZE_MAGIC_QUOTES);
	$sNeighborhood 		= filter_var($sNeighborhood, FILTER_SANITIZE_MAGIC_QUOTES);
	$sPostalCode 		= filter_var($sPostalCode, FILTER_SANITIZE_MAGIC_QUOTES);
	$sSite 				= filter_var($sSite, FILTER_SANITIZE_MAGIC_QUOTES);
	$sDescription 		= filter_var($sDescription, FILTER_SANITIZE_MAGIC_QUOTES);
	
	
	
	
	begin();
	
	// timestamp para ser usado nas demais queries:
	$query = 'SELECT CURRENT_TIMESTAMP AS tsTimeStamp; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	while($row = mysql_fetch_assoc($result)) {
		$tsTimeStamp = $row['tsTimeStamp'];
	}
	
	
	// cria o objeto Event
	// iType = 7 (EVENT)
	$query = '	INSERT INTO 
				tbSOCIALObject 
				(
					id,
					sDisplayName,
					sDirectLink,
					iType,
					tsCreation
				)
			VALUES (
					NULL,
					"'.$sDisplayName.'",
					"'.$sDirectLink.'",
					7, 
					"'.$tsTimeStamp.'"
				); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	// id do objeto, para ser usado nas demais queries:
	$query = 'SELECT LAST_INSERT_ID() AS idEvent; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	while($row = mysql_fetch_assoc($result)) {
		$idEvent = $row['idEvent'];
	}
	
	
	
	// cria a associação que ligará o objeto EVENT ao objeto profile da PAGE OWNER do evento
	// iType = 10 (EVENT)
	$query = '	INSERT INTO 
					tbSOCIALAssociation 
					(
						id,
						iType
					)
				VALUES (
						NULL,
						10
					); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	// id da associação, para ser usado nas demais queries:
	$query = 'SELECT LAST_INSERT_ID() AS idAssociation; ';
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	while($row = mysql_fetch_assoc($result)) {
		$idAssociation = $row['idAssociation'];
	}
	
	
	
	// cria as ligações de cada objeto à associação
	// iType = 3 (OBJECT)
	// iType = 4 (OWNER)
	$query = '	INSERT INTO	
					tbSOCIALObjectAssociation
					(
						id,
						iType,
						idSOCIALObject,
						idSOCIALAssociation,
						tsCreation
					)
				VALUES (
						NULL,
						3,
						'.$idEvent.',
						'.$idAssociation.',
						"'.$tsTimeStamp.'"
					), 
					(
						NULL,
						4,
						'.$idReferredObject.',
						'.$idAssociation.',
						"'.$tsTimeStamp.'"
					); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	// detalhes (conteúdo de texto) do post:
	$query = 'INSERT INTO 
					tbSOCIALObjectDetail
					(
						id,
						idSOCIALObject,
						sKey,
						sValue
					)
				VALUES (
						NULL,
						'.$idEvent.',
						"sContent",
						"'.$sDescription.'"
					); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	$query = 'INSERT INTO tbSOCIALObjectProperty 
					(
						id, 
						idSOCIALObject, 
						idSOCIALProperty, 
						sValue
					)
				VALUES 
					(
						NULL, 
						'.$idEvent.', 
						26, 
						"'.$sDisplayName.'"
					), 
					(
						NULL, 
						'.$idEvent.', 
						27, 
						"'.$sDescription.'"
					), 
				--	(
				--		NULL, 
				--		'.$idEvent.', 
				--		28, 
				--		"'.$sAvatarPath.'"
				--	), 
					(
						NULL, 
						'.$idEvent.', 
						29, 
						"'.$sSite.'"
					), 
					(
						NULL, 
						'.$idEvent.', 
						30, 
						"'.$sAddress.'"
					), 
					(
						NULL, 
						'.$idEvent.', 
						31, 
						"'.$sComplement.'"
					), 
					(
						NULL, 
						'.$idEvent.', 
						32, 
						"'.$idADMINCountry.'"
					), 
					(
						NULL, 
						'.$idEvent.', 
						33, 
						"'.$idADMINState.'"
					), 
					(
						NULL, 
						'.$idEvent.', 
						34, 
						"'.$idADMINCity.'"
					), 
					(
						NULL, 
						'.$idEvent.', 
						35, 
						"'.$sPostalCode.'"
					), 
					(
						NULL, 
						'.$idEvent.', 
						36, 
						"'.$sNeighborhood.'"
					), 
					(
						NULL, 
						'.$idEvent.', 
						37, 
						"'.$tsBegin.'"
					), 
					(
						NULL, 
						'.$idEvent.', 
						38, 
						"'.$tsEnd.'"
					); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	// se tudo deu certo até aqui:
	commit();
	return true;
	
}



function EventEdit($idEvent, $sDisplayName, $tsBegin, $tsEnd, $sAddress, $sComplement, $idADMINCountry, $idADMINState, $idADMINCity, $sNeighborhood, $sPostalCode, $sSite,$sDescription) {
	
	$idProfile 			= $_SESSION["loggedUser"];
	$idEvent 			= !isset($idEvent) 			? ''   : $idEvent;
	$sDisplayName		= !isset($sDisplayName) 	? NULL : $sDisplayName;
	$tsBegin			= !isset($tsBegin) 			? NULL : $tsBegin;
	$tsEnd				= !isset($tsEnd) 			? NULL : $tsEnd;
	$sAddress			= !isset($sAddress) 		? NULL : $sAddress;
	$sComplement		= !isset($sComplement) 		? NULL : $sComplement;
	$idADMINCountry		= !isset($idADMINCountry) 	? NULL : $idADMINCountry;
	$idADMINState		= !isset($idADMINState) 	? NULL : $idADMINState;
	$idADMINCity		= !isset($idADMINCity) 		? NULL : $idADMINCity;
	$sNeighborhood		= !isset($sNeighborhood) 	? NULL : $sNeighborhood;
	$sPostalCode		= !isset($sPostalCode) 		? NULL : $sPostalCode;
	$sSite				= !isset($sSite) 			? NULL : $sSite;
	$sDescription		= !isset($sDescription) 	? NULL : $sDescription;
	
	$sDirectLink 		= strtolower(strip_tags($sDisplayName));
	
	
	$idEvent 			= filter_var($idEvent, FILTER_SANITIZE_MAGIC_QUOTES);
	$sDisplayName 		= filter_var($sDisplayName, FILTER_SANITIZE_MAGIC_QUOTES);
	$tsBegin 			= filter_var($tsBegin, FILTER_SANITIZE_MAGIC_QUOTES);
	$tsEnd 				= filter_var($tsEnd, FILTER_SANITIZE_MAGIC_QUOTES);
	$sAddress 			= filter_var($sAddress, FILTER_SANITIZE_MAGIC_QUOTES);
	$sComplement 		= filter_var($sComplement, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINCountry 	= filter_var($idADMINCountry, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINState 		= filter_var($idADMINState, FILTER_SANITIZE_MAGIC_QUOTES);
	$idADMINCity 		= filter_var($idADMINCity, FILTER_SANITIZE_MAGIC_QUOTES);
	$sNeighborhood 		= filter_var($sNeighborhood, FILTER_SANITIZE_MAGIC_QUOTES);
	$sPostalCode 		= filter_var($sPostalCode, FILTER_SANITIZE_MAGIC_QUOTES);
	$sSite 				= filter_var($sSite, FILTER_SANITIZE_MAGIC_QUOTES);
	$sDescription 		= filter_var($sDescription, FILTER_SANITIZE_MAGIC_QUOTES);
	
	
	
	begin();
	
	// timestamp para ser usado nas demais queries:
	$query = 'SELECT CURRENT_TIMESTAMP AS tsTimeStamp; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	while($row = mysql_fetch_assoc($result)) {
		$tsTimeStamp = $row['tsTimeStamp'];
	}
	
	
	
	
	// detalhes do evento:
	$query = '
				UPDATE tbSOCIALObjectDetail
				SET 
					sValue = "'.$sDescription.'" 
					
				WHERE idSOCIALObject = '.$idEvent.' 
				AND   sKey = "sContent" 
				LIMIT 1; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	// propriedades do evento:
	$query = '
			UPDATE tbSOCIALObjectProperty 
				SET sValue = "'.$sDisplayName.'" 
			WHERE idSOCIALObject = '.$idEvent.' 
			AND   idSOCIALProperty = 26 
			LIMIT 1; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	
	$query = '
			UPDATE tbSOCIALObjectProperty 
				SET sValue = "'.$sDescription.'" 
			WHERE idSOCIALObject = '.$idEvent.' 
			AND   idSOCIALProperty = 27 
			LIMIT 1; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	
	$query = '
			UPDATE tbSOCIALObjectProperty 
				SET sValue = "'.$sSite.'" 
			WHERE idSOCIALObject = '.$idEvent.' 
			AND   idSOCIALProperty = 29 
			LIMIT 1; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	$query = '
			UPDATE tbSOCIALObjectProperty 
				SET sValue = "'.$sAddress.'" 
			WHERE idSOCIALObject = '.$idEvent.' 
			AND   idSOCIALProperty = 30 
			LIMIT 1; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	$query = '
			UPDATE tbSOCIALObjectProperty 
				SET sValue = "'.$sComplement.'" 
			WHERE idSOCIALObject = '.$idEvent.' 
			AND   idSOCIALProperty = 31 
			LIMIT 1; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	$query = '
			UPDATE tbSOCIALObjectProperty 
				SET sValue = "'.$idADMINCountry.'" 
			WHERE idSOCIALObject = '.$idEvent.' 
			AND   idSOCIALProperty = 32 
			LIMIT 1; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	$query = '
			UPDATE tbSOCIALObjectProperty 
				SET sValue = "'.$idADMINState.'" 
			WHERE idSOCIALObject = '.$idEvent.' 
			AND   idSOCIALProperty = 33 
			LIMIT 1; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	$query = '
			UPDATE tbSOCIALObjectProperty 
				SET sValue = "'.$idADMINCity.'" 
			WHERE idSOCIALObject = '.$idEvent.' 
			AND   idSOCIALProperty = 34 
			LIMIT 1; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	$query = '
			UPDATE tbSOCIALObjectProperty 
				SET sValue = "'.$sPostalCode.'" 
			WHERE idSOCIALObject = '.$idEvent.' 
			AND   idSOCIALProperty = 35 
			LIMIT 1; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	$query = '
			UPDATE tbSOCIALObjectProperty 
				SET sValue = "'.$sNeighborhood.'" 
			WHERE idSOCIALObject = '.$idEvent.' 
			AND   idSOCIALProperty = 36 
			LIMIT 1; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	$query = '
			UPDATE tbSOCIALObjectProperty 
				SET sValue = "'.$tsBegin.'" 
			WHERE idSOCIALObject = '.$idEvent.' 
			AND   idSOCIALProperty = 37 
			LIMIT 1; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	$query = '
			UPDATE tbSOCIALObjectProperty 
				SET sValue = "'.$tsEnd.'" 
			WHERE idSOCIALObject = '.$idEvent.' 
			AND   idSOCIALProperty = 38 
			LIMIT 1; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	
	
	// se tudo deu certo até aqui:
	commit();
	return true;
	
}







function messageCreate($idSender, $idReceiver,  $idConversation, $sContent) {
	
	$idSender			= !isset($idSender) 		? '' : $idSender;
	$idReceiver			= !isset($idReceiver) 		? '' : $idReceiver;
	$sContent 			= !isset($sContent) 		? '' : $sContent;
	
	$idSender 			= filter_var($idSender, FILTER_SANITIZE_MAGIC_QUOTES);
	$idReceiver 		= filter_var($idReceiver, FILTER_SANITIZE_MAGIC_QUOTES);
	$sContent 			= filter_var($sContent, FILTER_SANITIZE_MAGIC_QUOTES);
	
	
	
	$idsReceiver 		= array();
	$idsReceiver 		= explode(",",$idReceiver);
	
	$nReceiver 			= count($idsReceiver);
	
	$sDisplayName 		= 'Message';
	$sDirectLink 		= strtolower(strip_tags($sDisplayName));
	
	
	//-------------------------------------------------
	// verifica se existe uma conversation inciada entre o sender e o receiver
	// (para CONVERSATION entre apenas 2 PROFILES - 1 SENDER e 1 RECEIVER)
	
	if ($nReceiver <= 1) {
		
		// query nova
		$query = 'SELECT associations.idAssociation 
					
					FROM (
						
						SELECT 
							assoc.idAssociation,
							assoc.countParticipants,
							oSender.id AS idSender,';
							
		for ($n=0; $n<$nReceiver; $n++) {			
							
			$query .= ' oReceiver'.$n.'.id AS idReceiver'.$n.', ';
		}
		
		$query = substr_replace($query, '', -2, 2);
		
		$query .= 	' FROM (
							SELECT 
								a.id AS idAssociation, 
								COUNT(oa.id) AS countParticipants 
								
							FROM tbSOCIALAssociation AS a 
							
							INNER JOIN tbSOCIALObjectAssociation AS oa 
							ON (oa.idSOCIALAssociation = a.id 
							AND oa.iType = 15) 
							
							INNER JOIN tbSOCIALObject AS oSender 
							ON oSender.id = oa.idSOCIALObject 
							
							WHERE a.iType = 9 

							GROUP BY a.id 
						) AS assoc
						
						INNER JOIN tbSOCIALObjectAssociation AS oa 
						ON (
							oa.idSOCIALAssociation = assoc.idAssociation 
							AND oa.iType = 15
						) 
						
						INNER JOIN tbSOCIALObject AS oSender 
						ON oSender.id = oa.idSOCIALObject ';
						
		for ($n=0; $n<$nReceiver; $n++) {
						
			$query .= '	INNER JOIN tbSOCIALObjectAssociation AS oa'.$n.' 
						ON (
							oa'.$n.'.idSOCIALAssociation = assoc.idAssociation 
							AND oa'.$n.'.iType = 15
						) 
						
						INNER JOIN tbSOCIALObject AS oReceiver'.$n.' 
						ON oReceiver'.$n.'.id = oa'.$n.'.idSOCIALObject ';
		}
		
		$query .= 	'	WHERE assoc.countParticipants = '.($nReceiver+1).' 
						AND oSender.id = '.$idSender.' ';
		
		for ($m=0; $m<$nReceiver; $m++) {
			
			$query .= '	AND oReceiver'.$m.'.id = '.$idsReceiver[$m].' ';
		}

		$query .= 	') AS associations; ';
		
		$result = mysql_query(utf8_decode($query));
		
		if ($result == false) {
			rollback();
			echo 'erro em 3295';
			return false;
		}
		
		while($row = mysql_fetch_assoc($result)) {
			$idAssocConversation = $row['idAssociation'];
		}
	
	
	} 
	
	// (para CONVERSATION entre mais de 2 PROFILES - 1 SENDER e n RECEIVERS)
	else if ($nReceiver > 1 && $idConversation != null) {
		$query = '  SELECT idSOCIALAssociation
			
					FROM tbSOCIALObjectAssociation
					
					WHERE idSOCIALObject = '.$idConversation.';';
					
		$result = mysql_query(utf8_decode($query));
		
		while($row = mysql_fetch_assoc($result)) {
			$idAssocConversation = $row['idSOCIALAssociation'];
		}
		
	}
	
	//-------------------------------------------------
	// time stamp
	$query = 'SELECT CURRENT_TIMESTAMP AS tsTimeStamp;';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		echo 'erro em 3385';
		return false;
	}
	
	while($row = mysql_fetch_assoc($result)) {
		$tsTimeStamp = $row['tsTimeStamp'];
	}
	
	
	// se em nenhuma das tentativas acima foi retornada uma ASSOCIATION do tipo CONVERSATION 
	// entre os participantes da mensagem, então uma nova CONVERSATION é criada e utilizada
	if(!isset($idAssocConversation)) {
	
		//-------------------------------------------------
		// cria o objeto Conversation
		// iType = 11 (CONVERSATION)
		$query = '	INSERT INTO 
					tbSOCIALObject 
					(
						id,
						sDisplayName,
						sDirectLink,
						iType,
						tsCreation
					)
				VALUES (
						NULL,
						"Conversation",
						"conversation",
						11, 
						"'.$tsTimeStamp.'"
					); ';
		
		$result = mysql_query(utf8_decode($query));
		
		if ($result == false) {
			rollback();
			echo 'erro em 3419';
			return false;
		}
	
	
		//-------------------------------------------------
		// seleciona o id do objeto Conversation criado acima
		$query = 'SELECT LAST_INSERT_ID() AS idConversation; ';
		
		$result = mysql_query(utf8_decode($query));
		
		if ($result == false) {
			rollback();
			echo 'erro em 3432';
			return false;
		}
		
		while($row = mysql_fetch_assoc($result)) {
			$idConversation = $row['idConversation'];
		}
	}
	
	//-------------------------------------------------
	// cria o objeto Message
	// iType = 11 (MESSAGE)
	$query = '	INSERT INTO 
				tbSOCIALObject 
				(
					id,
					sDisplayName,
					sDirectLink,
					iType,
					tsCreation
				)
			VALUES (
					NULL,
					"'.$sDisplayName.'",
					"'.$sDirectLink.'",
					6, 
					"'.$tsTimeStamp.'"
				); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		echo 'erro em 3465';
		return false;
	}
	
	
	//-------------------------------------------------
	// seleciona o id do objeto Message criado acima
	$query = 'SELECT LAST_INSERT_ID() AS idMessage; ';
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		echo 'erro em 3477';
		return false;
	}
	while($row = mysql_fetch_assoc($result)) {
		$idMessage = $row['idMessage'];
	}
	
	if(!isset($idAssocConversation)) {
	
		//-------------------------------------------------
		// cria a associação conversation
		// iType = 9 (Conversation)
		$query = '	INSERT INTO 
						tbSOCIALAssociation 
						(
							id,
							iType
						)
					VALUES (
							NULL,
							9
						); ';
		
		$result = mysql_query(utf8_decode($query));
		
		if ($result == false) {
			rollback();
			echo 'erro em 3504';
			return false;
		}
		
		$query = 'SELECT LAST_INSERT_ID() AS idAssocConversation; ';
		$result = mysql_query(utf8_decode($query));
		
		if ($result == false) {
			rollback();
			echo 'erro em 3513';
			return false;
		}
		while($row = mysql_fetch_assoc($result)) {
			$idAssocConversation = $row['idAssocConversation'];
		}
		
		$query = 'INSERT INTO	
					tbSOCIALObjectAssociation (
						id,
						iType,
						idSOCIALObject,
						idSOCIALAssociation,
						tsCreation
					) VALUES (
						NULL,
						3,
						'.$idConversation.',
						'.$idAssocConversation.',
						"'.$tsTimeStamp.'"
					), (
						NULL,
						15,
						'.$idSender.',
						'.$idAssocConversation.',
						"'.$tsTimeStamp.'"
					), ';
		
		for($n=0; $n < $nReceiver; $n++) {
					
			$query .=	'(
							NULL,
							15,
							'.$idsReceiver[$n].',
							'.$idAssocConversation.',
							"'.$tsTimeStamp.'"';
			if($n == ($nReceiver - 1)) {
				$query .=	'); ';
			} else {
				$query .=	'), ';
			}
		}
		
		$result = mysql_query(utf8_decode($query));
		
		if ($result == false) {
			rollback();
			echo 'erro em 3560';
			return false;
		}
		
	}
	
	//-------------------------------------------------
	// cria a associação message
	// iType = 6 (MESSAGE)
	$query = '	INSERT INTO 
					tbSOCIALAssociation 
					(
						id,
						iType
					)
				VALUES (
						NULL,
						6
					); ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		echo 'erro em 3584';
		return false;
	}
	
	$query = 'SELECT LAST_INSERT_ID() AS idAssocMessage; ';
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		echo 'erro em 3593';
		return false;
	}
	while($row = mysql_fetch_assoc($result)) {
		$idAssocMessage = $row['idAssocMessage'];
	}
	
	$query = 'INSERT INTO	
					tbSOCIALObjectDetail
					(
						id,
						idSOCIALObject,
						sKey,
						sValue
					)
				VALUES (
						NULL,
						'.$idMessage.',
						"sMessage",
						"'.$sContent.'"
					);';
					
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		echo 'erro em 3619';
		return false;
	}
	
	// cria as ligações de cada objeto à associação
	// iType = 3 (OBJECT)
	// iType = 4 (OWNER)
	$query = '	INSERT INTO	
					tbSOCIALObjectAssociation (
						id,
						iType,
						idSOCIALObject,
						idSOCIALAssociation,
						tsCreation
					) VALUES (
						NULL,
						11,
						'.$idSender.',
						'.$idAssocMessage.',
						"'.$tsTimeStamp.'"
					), ';
	
	for($n=0; $n < $nReceiver; $n++) {
					
		$query .=	'(
						NULL,
						12,
						'.$idsReceiver[$n].',
						'.$idAssocMessage.',
						"'.$tsTimeStamp.'"
					), ';
					
	}
					
	$query .=		'(
						NULL,
						3,
						'.$idMessage.',
						'.$idAssocMessage.',
						"'.$tsTimeStamp.'"
					),
					(
						NULL,
						16,
						'.$idMessage.',
						'.$idAssocConversation.',
						"'.$tsTimeStamp.'"
					);';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		echo 'erro em 3672';
		return false;
	}
	
	
	
	// se tudo deu certo até aqui:
	commit();
	return true;
}



function CommentCreate($idReferredObject, $sContent) {
	
	global $conni;
	
	$idProfile 			= $_SESSION["loggedUser"];
	$idReferredObject 	= !isset($idReferredObject) ? '' : $idReferredObject;
	$sContent 			= !isset($sContent) 		? '' : $sContent;
	
	$idReferredObject 	= filter_var($idReferredObject, FILTER_SANITIZE_MAGIC_QUOTES);
	$sContent 			= filter_var($sContent, FILTER_SANITIZE_MAGIC_QUOTES);
	
	
	
	$sDisplayName 		= 'Comment';
	$sDirectLink 		= strtolower(strip_tags($sDisplayName));
	
	$query = 'SET @tsTimeStamp = CURRENT_TIMESTAMP; ';

	// cria o objeto Comment
	// iType = 4 (COMMENT)
	$query .= '	INSERT INTO 
					tbSOCIALObject 
					(
						id,
						sDisplayName,
						sDirectLink,
						iType,
						tsCreation
					)
				VALUES (
						NULL,
						"'.$sDisplayName.'",
						"'.$sDirectLink.'",
						4, 
						@tsTimeStamp
					);

				SET @idPost = LAST_INSERT_ID(); ';

	// cria a associação que ligará o objeto Comment ao objeto profile do usuário criador
	// iType = 4 (COMMENT)
	$query .= '	INSERT INTO 
					tbSOCIALAssociation 
					(
						id,
						iType
					)
				VALUES (
						NULL,
						4
					);

				SET @idAssociation = LAST_INSERT_ID(); ';

	// cria as ligações de cada objeto à associação
	// iType = 3 (OBJECT)
	// iType = 4 (OWNER)
	// iType = 8 (REFERRED)
	$query .= '	INSERT INTO	
					tbSOCIALObjectAssociation
					(
						id,
						iType,
						idSOCIALObject,
						idSOCIALAssociation,
						tsCreation
					)
				VALUES (
						NULL,
						3,
						@idPost,
						@idAssociation,
						@tsTimeStamp
					), 
					(
						NULL,
						4,
						'.$idProfile.',
						@idAssociation,
						@tsTimeStamp
					), 
					(
						NULL,
						8,
						'.$idReferredObject.',
						@idAssociation,
						@tsTimeStamp
					); ';
	
	// detalhes (conteúdo) do post:
	$query .= 'INSERT INTO	
					tbSOCIALObjectDetail
					(
						id,
						idSOCIALObject,
						sKey,
						sValue
					)
				VALUES (
						NULL,
						@idPost,
						"sContent",
						"'.$sContent.'"
					); ';
	
	begin();
	
	if (mysqli_multi_query($conni, utf8_decode($query))) {
		commit();
		return true;
	} else {
		rollback();
		return false;
	}
	
}



function likeObject($idUser, $idReferredObject) {
	
	$idUser 			= $_SESSION["loggedUser"];
	$idReferredObject 	= !isset($idReferredObject) ? '' : $idReferredObject;
	
	// --------------------------------------------------
	// time stamp usado nas demais queries
	$query = 'SELECT CURRENT_TIMESTAMP AS tsTimeStamp;';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	while($row = mysql_fetch_assoc($result)) {
		$tsTimeStamp = $row['tsTimeStamp'];
	}
	
	
	// --------------------------------------------------
	// identifica a associação do conteúdo a ser gostado
	$query = 'SELECT 
				a.id AS idAssociation

				FROM tbSOCIALAssociation a

				INNER JOIN tbSOCIALObjectAssociation oa
				ON oa.idSOCIALAssociation = a.id

				INNER JOIN tbSOCIALObject o 
				ON o.id = oa.idSOCIALObject 

				WHERE o.id = '.$idReferredObject.' 
			--	AND a.iType = 8 

				LIMIT 0,1; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	while($row = mysql_fetch_assoc($result)) {
		$idAssociation = $row['idAssociation'];
	}
	
	
	if (isset($idAssociation)) {
		
		$query = '	INSERT INTO	
						tbSOCIALObjectAssociation
						(
							id,
							iType,
							idSOCIALObject,
							idSOCIALAssociation,
							tsCreation
						)
					VALUES (
							NULL,
							5,
							'.$idUser.',
							'.$idAssociation.',
							"'.$tsTimeStamp.'"
						); ';
		
		$result = mysql_query(utf8_decode($query));
		
		if ($result == false) {
			rollback();
			return false;
		}
		
	}
	
	// se tudo deu certo até aqui:
	commit();
	return true;
	
}

function dislikeObject($idUser, $idReferredObject) {
	
	$idUser 			= $_SESSION["loggedUser"];
	$idReferredObject 	= !isset($idReferredObject) ? '' : $idReferredObject;
	
	// --------------------------------------------------
	// identifica a associação do conteúdo a ser desgostado
	$query = 'SELECT 
				a.id AS idAssociation

				FROM tbSOCIALAssociation a

				INNER JOIN tbSOCIALObjectAssociation oa
				ON oa.idSOCIALAssociation = a.id

				INNER JOIN tbSOCIALObject o 
				ON o.id = oa.idSOCIALObject 

				WHERE o.id = '.$idReferredObject.' 

				LIMIT 0,1; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	while($row = mysql_fetch_assoc($result)) {
		$idAssociation = $row['idAssociation'];
	}
	
	if (!isset($idAssociation)) {
		rollback();
		return false;
	}
	
	
	
	// --------------------------------------------------
	// identifica a associação de LIKE entre o objeto e o PROFILE do usuário
	$query = 'SELECT 
				oa.id AS idLikeAssociation

				FROM tbSOCIALObjectAssociation oa

				WHERE oa.iType = 5
				AND   oa.idSOCIALObject = '.$idUser.'
				AND   oa.idSOCIALAssociation = '.$idAssociation.'

				LIMIT 0,1; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	while($row = mysql_fetch_assoc($result)) {
		$idLikeAssociation = $row['idLikeAssociation'];
	}
	
	if (!isset($idLikeAssociation)) {
		rollback();
		return false;
	}
	
	
	
	// --------------------------------------------------
	// remove a associação
	$query = '	DELETE FROM tbSOCIALObjectAssociation 
				WHERE id = '.$idLikeAssociation.' 
				LIMIT 1; ';
	
	$result = mysql_query(utf8_decode($query));
	
	if ($result == false) {
		rollback();
		return false;
	}
	
	// se tudo deu certo até aqui:
	commit();
	return true;
	
}




function PageFollow($idProfile, $idPage) {
	
	global $conni;
	
	$idProfile 	= !isset($idProfile) ? '' : $idProfile;
	$idPage 	= !isset($idPage) 	 ? '' : $idPage;
	
	$query = '	SET @idProfile	 = '.$idProfile.';
				SET @idPage 	 = '.$idPage.';
				SET @tsTimeStamp = CURRENT_TIMESTAMP; ';
	
	// usa a associação existente relacionada ao objeto page
	// iType=2 (PAGE)
	$query .= '	SET @idAssociation = (
					SELECT 
						assoc.id 
						
					FROM tbSOCIALAssociation assoc

					INNER JOIN tbSOCIALObjectAssociation oa 
					ON oa.idSOCIALAssociation = assoc.id 

					WHERE 	oa.idSOCIALObject = @idPage 
					AND		assoc.iType = 2

					LIMIT 0, 1
				); ';
	// cria as ligações de cada objeto à associação
	// iType=7 (FOLLOWER)
	$query .= '	INSERT INTO	
					tbSOCIALObjectAssociation
					(
						id,
						iType,
						idSOCIALObject,
						idSOCIALAssociation,
						tsCreation
					)
				VALUES (
						NULL,
						7,
						@idProfile,
						@idAssociation,
						@tsTimeStamp
					); ';
	
	begin();
	
	if (mysqli_multi_query($conni, utf8_decode($query))) {
		commit();
		return true;
	} else {
		rollback();
		return false;
	}
}

function PageUnfollow($idProfile, $idPage) {
	
	global $conni;
	
	$idProfile 	= !isset($idProfile) ? '' : $idProfile;
	$idPage 	= !isset($idPage) 	 ? '' : $idPage;
	
	$query = '	SET @idProfile	 = '.$idProfile.';
				SET @idPage 	 = '.$idPage.';
				SET @tsTimeStamp = CURRENT_TIMESTAMP; ';
	
	// usa a associação existente relacionada ao objeto page
	// iType=2 (PAGE)
	$query .= '	SET @idAssociation = (
					SELECT 
						assoc.id 
						
					FROM tbSOCIALAssociation assoc

					INNER JOIN tbSOCIALObjectAssociation oa 
					ON oa.idSOCIALAssociation = assoc.id 

					WHERE 	oa.idSOCIALObject = @idPage 
					AND		assoc.iType = 2

					LIMIT 0, 1
				); ';
	
	// exclui as ligações de cada objeto à associação
	// iType=7 (FOLLOWER)
	
	$query .= '	DELETE FROM 
					tbSOCIALObjectAssociation 

				WHERE 	idSOCIALAssociation = @idAssociation 
				AND 	idSOCIALObject = @idProfile 
				AND 	iType = 7; ';
	
	begin();
	
	if (mysqli_multi_query($conni, utf8_decode($query))) {
		commit();
		return true;
	} else {
		rollback();
		return false;
	}
}




function GroupFollow($idProfile, $idGroup) {
	
	global $conni;
	
	$idProfile 	= !isset($idProfile) ? '' : $idProfile;
	$idGroup 	= !isset($idGroup) 	 ? '' : $idGroup;
	
	$query = '	SET @idProfile	 = '.$idProfile.';
				SET @idGroup 	 = '.$idGroup.';
				SET @tsTimeStamp = CURRENT_TIMESTAMP; ';
	
	// usa a associação existente relacionada ao objeto GROUP
	// iType=5 (GROUP)
	$query .= '	SET @idAssociation = (
					SELECT 
						assoc.id 
						
					FROM tbSOCIALAssociation assoc

					INNER JOIN tbSOCIALObjectAssociation oa 
					ON oa.idSOCIALAssociation = assoc.id 

					WHERE 	oa.idSOCIALObject = @idGroup 
					AND		assoc.iType = 5

					LIMIT 0, 1
				); ';
	// cria as ligações de cada objeto à associação
	// iType=14 (MEMBER)
	$query .= '	INSERT INTO	
					tbSOCIALObjectAssociation
					(
						id,
						iType,
						idSOCIALObject,
						idSOCIALAssociation,
						tsCreation
					)
				VALUES (
						NULL,
						14,
						@idProfile,
						@idAssociation,
						@tsTimeStamp
					); ';
	
	begin();
	
	if (mysqli_multi_query($conni, utf8_decode($query))) {
		commit();
		return true;
	} else {
		rollback();
		return false;
	}
}

function GroupUnfollow($idProfile, $idGroup) {
	
	global $conni;
	
	$idProfile 	= !isset($idProfile) ? '' : $idProfile;
	$idGroup 	= !isset($idGroup) 	 ? '' : $idGroup;
	
	$query = '	SET @idProfile	 = '.$idProfile.';
				SET @idGroup 	 = '.$idGroup.';
				SET @tsTimeStamp = CURRENT_TIMESTAMP; ';
	
	// usa a associação existente relacionada ao objeto GROUP
	// iType=5 (GROUP)
	$query .= '	SET @idAssociation = (
					SELECT 
						assoc.id 
						
					FROM tbSOCIALAssociation assoc

					INNER JOIN tbSOCIALObjectAssociation oa 
					ON oa.idSOCIALAssociation = assoc.id 

					WHERE 	oa.idSOCIALObject = @idGroup 
					AND		assoc.iType = 5

					LIMIT 0, 1
				); ';
	
	// exclui as ligações de cada objeto à associação
	// iType=14 (MEMBER)
	
	$query .= '	DELETE FROM 
					tbSOCIALObjectAssociation 

				WHERE 	idSOCIALAssociation = @idAssociation 
				AND 	idSOCIALObject = @idProfile 
				AND 	iType = 14; ';
	
	begin();
	
	if (mysqli_multi_query($conni, utf8_decode($query))) {
		commit();
		return true;
	} else {
		rollback();
		return false;
	}
}





function userLogin($sLogin, $sPassword) {
	
	$sLogin 	= !isset($sLogin)	 ? '' : $sLogin;
	$sPassword 	= !isset($sPassword) ? '' : $sPassword;
	$login;
	
	$query = 'SELECT 
				o.id,
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
			AND 	opp.sValue = "'.$sPassword.'"
			AND		o.fDeleted = 0; ';
			
	//		CONVERT('".$sPassword."', BINARY(255))
	
	$result = mysql_query(utf8_decode($query));
	
	$results = mysql_fetch_assoc($result);
	$id		 = $results['id'];
	$success = $results['success'];
	
	if ($success == 1) {
		$_SESSION["loggedUser"] = $id;
		return true;
		
	} else {
		$_SESSION["loggedUser"] = 0;
		return false;
	}
	
}

function userLogout() {
	$_SESSION["loggedUser"] = 0;
	return true;
}



?>
