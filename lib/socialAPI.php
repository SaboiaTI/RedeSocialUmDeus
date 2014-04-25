<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/AppConnect.php");
require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/libSocial.php");

$action = (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) ? $_REQUEST['action'] : NULL;
$rows 	= array();

session_start();


switch ($action)
{
	case 'userLogin' :
		
		$sLogin 	= (isset($_REQUEST['sLogin'])	 && !empty($_REQUEST['sLogin'])) 	? $_REQUEST['sLogin']	 : NULL;
		$sPassword 	= (isset($_REQUEST['sPassword']) && !empty($_REQUEST['sPassword'])) ? $_REQUEST['sPassword'] : NULL;
		
		$success = false;
		$success = userLogin($sLogin, $sPassword);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'userLogout' :
		
		$success = userLogout();
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	
	
	case 'createAccount' :
		
		$success = false;
		
		$sFullName 		= (isset($_REQUEST['sFullName'])	 && !empty($_REQUEST['sFullName'])) 	? $_REQUEST['sFullName'] 		: NULL;
		$sEmail 		= (isset($_REQUEST['sEmail'])		 && !empty($_REQUEST['sEmail'])) 		? $_REQUEST['sEmail'] 			: NULL;
		$sDisplayName 	= (isset($_REQUEST['sDisplayName'])	 && !empty($_REQUEST['sDisplayName'])) 	? $_REQUEST['sDisplayName'] 	: NULL;
		$sPassword 		= (isset($_REQUEST['sPassword'])	 && !empty($_REQUEST['sPassword'])) 	? $_REQUEST['sPassword'] 		: NULL;
		$dtBirthday 	= (isset($_REQUEST['dtBirthday'])	 && !empty($_REQUEST['dtBirthday'])) 	? $_REQUEST['dtBirthday'] 		: NULL;
		
		$idADMINCountry = (isset($_REQUEST['idADMINCountry'])&& !empty($_REQUEST['idADMINCountry']))? $_REQUEST['idADMINCountry'] 	: NULL;
		$idADMINState 	= (isset($_REQUEST['idADMINState'])	 && !empty($_REQUEST['idADMINState'])) 	? $_REQUEST['idADMINState'] 	: NULL;
		$idADMINCity 	= (isset($_REQUEST['idADMINCity'])	 && !empty($_REQUEST['idADMINCity'])) 	? $_REQUEST['idADMINCity'] 		: NULL;
		$sPostalCode 	= (isset($_REQUEST['sPostalCode'])	 && !empty($_REQUEST['sPostalCode'])) 	? $_REQUEST['sPostalCode'] 		: NULL;
		$sAvatarPath 	= (isset($_REQUEST['sAvatarPath'])	 && !empty($_REQUEST['sAvatarPath'])) 	? $_REQUEST['sAvatarPath'] 		: NULL;
		
		$success = ProfileCreate($sFullName, $sEmail, $sDisplayName, $sPassword, $dtBirthday, $idADMINCountry, $idADMINState, $idADMINCity, $sPostalCode, $sAvatarPath);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'updateAccount' :
		
		$success = false;
		
		$idProfile 		= (isset($_REQUEST['idProfile'])	 && !empty($_REQUEST['idProfile'])) 	? $_REQUEST['idProfile'] 		: NULL;
		$sFullName 		= (isset($_REQUEST['sFullName'])	 && !empty($_REQUEST['sFullName'])) 	? $_REQUEST['sFullName'] 		: NULL;
		$sDisplayName 	= (isset($_REQUEST['sDisplayName'])	 && !empty($_REQUEST['sDisplayName'])) 	? $_REQUEST['sDisplayName'] 	: NULL;
		$sEmail 		= (isset($_REQUEST['sEmail'])		 && !empty($_REQUEST['sEmail'])) 		? $_REQUEST['sEmail'] 			: NULL;
		$dtBirthday 	= (isset($_REQUEST['dtBirthday'])	 && !empty($_REQUEST['dtBirthday'])) 	? $_REQUEST['dtBirthday'] 		: NULL;
		
		$idADMINCountry = (isset($_REQUEST['idADMINCountry'])&& !empty($_REQUEST['idADMINCountry']))? $_REQUEST['idADMINCountry'] 	: NULL;
		$idADMINState 	= (isset($_REQUEST['idADMINState'])	 && !empty($_REQUEST['idADMINState'])) 	? $_REQUEST['idADMINState'] 	: NULL;
		$idADMINCity 	= (isset($_REQUEST['idADMINCity'])	 && !empty($_REQUEST['idADMINCity'])) 	? $_REQUEST['idADMINCity'] 		: NULL;
		$sPostalCode 	= (isset($_REQUEST['sPostalCode'])	 && !empty($_REQUEST['sPostalCode'])) 	? $_REQUEST['sPostalCode'] 		: NULL;
		
		$spiritualAffinities = (isset($_REQUEST['spiritualAffinities']) && !empty($_REQUEST['spiritualAffinities'])) ? $_REQUEST['spiritualAffinities'] : NULL;
		
		$success = ProfileUpdate($idProfile, $sFullName, $sEmail, $sDisplayName, $dtBirthday, $idADMINCountry, $idADMINState, $idADMINCity, $sPostalCode, $spiritualAffinities);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'createPage' :
		
		$sFullName 		= (isset($_REQUEST['sFullName']) 		&& !empty($_REQUEST['sFullName'])) 		? $_REQUEST['sFullName'] 		: NULL;
		$sDisplayName 	= (isset($_REQUEST['sDisplayName']) 	&& !empty($_REQUEST['sDisplayName'])) 	? $_REQUEST['sDisplayName'] 	: NULL;
		$iPageType 		= (isset($_REQUEST['iPageType']) 		&& !empty($_REQUEST['iPageType'])) 		? $_REQUEST['iPageType'] 		: NULL;
		$sDescription 	= (isset($_REQUEST['sDescription']) 	&& !empty($_REQUEST['sDescription'])) 	? $_REQUEST['sDescription'] 	: NULL;
		$sSite 			= (isset($_REQUEST['sSite']) 			&& !empty($_REQUEST['sSite'])) 			? $_REQUEST['sSite'] 			: NULL;
		
		$idADMINCountry = (isset($_REQUEST['idADMINCountry']) 	&& !empty($_REQUEST['idADMINCountry'])) ? $_REQUEST['idADMINCountry'] 	: NULL;
		$idADMINState 	= (isset($_REQUEST['idADMINState']) 	&& !empty($_REQUEST['idADMINState'])) 	? $_REQUEST['idADMINState'] 	: NULL;
		$idADMINCity 	= (isset($_REQUEST['idADMINCity']) 		&& !empty($_REQUEST['idADMINCity'])) 	? $_REQUEST['idADMINCity'] 		: NULL;
		
		$sPostalCode 	= (isset($_REQUEST['sPostalCode']) 		&& !empty($_REQUEST['sPostalCode'])) 	? $_REQUEST['sPostalCode'] 		: NULL;
		$sAddress 		= (isset($_REQUEST['sAddress']) 		&& !empty($_REQUEST['sAddress'])) 		? $_REQUEST['sAddress'] 		: NULL;
		$sComplement 	= (isset($_REQUEST['sComplement']) 		&& !empty($_REQUEST['sComplement'])) 	? $_REQUEST['sComplement'] 		: NULL;
		$sNeighborhood 	= (isset($_REQUEST['sNeighborhood']) 	&& !empty($_REQUEST['sNeighborhood'])) 	? $_REQUEST['sNeighborhood']	: NULL;
		
		$success = false;
		$success = PageCreate($sFullName, $sDisplayName, $iPageType, $sDescription, $sSite, $idADMINCountry, $idADMINState, $idADMINCity, $sPostalCode, $sAddress, $sComplement, $sNeighborhood);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'updatePage' :
		
		$success = false;
		
		$idPage 		= (isset($_REQUEST['idPage'])		 && !empty($_REQUEST['idPage'])) 		? $_REQUEST['idPage'] 			: NULL;
		$sDisplayName 	= (isset($_REQUEST['sDisplayName'])	 && !empty($_REQUEST['sDisplayName'])) 	? $_REQUEST['sDisplayName'] 	: NULL;
		$sDescription 	= (isset($_REQUEST['sDescription'])	 && !empty($_REQUEST['sDescription'])) 	? $_REQUEST['sDescription'] 	: NULL;
		$sAddress 		= (isset($_REQUEST['sAddress'])		 && !empty($_REQUEST['sAddress'])) 		? $_REQUEST['sAddress'] 		: NULL;
		$sComplement 	= (isset($_REQUEST['sComplement'])	 && !empty($_REQUEST['sComplement'])) 	? $_REQUEST['sComplement'] 		: NULL;
		$sNeighborhood 	= (isset($_REQUEST['sNeighborhood']) && !empty($_REQUEST['sNeighborhood'])) ? $_REQUEST['sNeighborhood'] 	: NULL;
		
		$idADMINCountry = (isset($_REQUEST['idADMINCountry'])&& !empty($_REQUEST['idADMINCountry']))? $_REQUEST['idADMINCountry'] 	: NULL;
		$idADMINState 	= (isset($_REQUEST['idADMINState'])	 && !empty($_REQUEST['idADMINState'])) 	? $_REQUEST['idADMINState'] 	: NULL;
		$idADMINCity 	= (isset($_REQUEST['idADMINCity'])	 && !empty($_REQUEST['idADMINCity'])) 	? $_REQUEST['idADMINCity'] 		: NULL;
		$sPostalCode 	= (isset($_REQUEST['sPostalCode'])	 && !empty($_REQUEST['sPostalCode'])) 	? $_REQUEST['sPostalCode'] 		: NULL;
		
		
		$success = PageUpdate($idPage, $sDisplayName, $sDescription, $sAddress, $sComplement, $sNeighborhood, $idADMINCountry, $idADMINState, $idADMINCity, $sPostalCode);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	
	
	
	
	case 'createGroup' :
		
		$sDisplayName 		= (isset($_REQUEST['sDisplayName']) 	  && !empty($_REQUEST['sDisplayName'])) 	  ? $_REQUEST['sDisplayName'] 	    : NULL;
		$iGroupType			= (isset($_REQUEST['iGroupType']) 		  && !empty($_REQUEST['iGroupType'])) 		  ? $_REQUEST['iGroupType']		    : NULL;
		$sGroupTheme		= (isset($_REQUEST['sGroupTheme']) 		  && !empty($_REQUEST['sGroupTheme'])) 		  ? $_REQUEST['sGroupTheme']	    : NULL;
		$dtBegin			= (isset($_REQUEST['dtBegin']) 			  && !empty($_REQUEST['dtBegin'])) 			  ? $_REQUEST['dtBegin'] 		    : NULL;
		$dtEnd				= (isset($_REQUEST['dtEnd']) 			  && !empty($_REQUEST['dtEnd'])) 			  ? $_REQUEST['dtEnd'] 		    : NULL;
		$iPeriodicity		= (isset($_REQUEST['iPeriodicity']) 	  && !empty($_REQUEST['iPeriodicity'])) 	  ? $_REQUEST['iPeriodicity']	    : NULL;
		$iPeriodicityDetail	= (isset($_REQUEST['iPeriodicityDetail']) && !empty($_REQUEST['iPeriodicityDetail'])) ? $_REQUEST['iPeriodicityDetail'] : NULL;
		$tsOracao			= (isset($_REQUEST['tsOracao']) 		  && !empty($_REQUEST['tsOracao'])) 		  ? $_REQUEST['tsOracao'] 			: NULL;
		$idADMINCountry 	= (isset($_REQUEST['idADMINCountry']) 	  && !empty($_REQUEST['idADMINCountry'])) 	  ? $_REQUEST['idADMINCountry']     : NULL;
		$idADMINState 		= (isset($_REQUEST['idADMINState']) 	  && !empty($_REQUEST['idADMINState'])) 	  ? $_REQUEST['idADMINState'] 	    : NULL;
		$idADMINCity 		= (isset($_REQUEST['idADMINCity']) 		  && !empty($_REQUEST['idADMINCity'])) 		  ? $_REQUEST['idADMINCity'] 	    : NULL;
		$sDescription 		= (isset($_REQUEST['sDescription']) 	  && !empty($_REQUEST['sDescription'])) 	  ? $_REQUEST['sDescription'] 	    : NULL;
		$sSite 				= (isset($_REQUEST['sSite']) 			  && !empty($_REQUEST['sSite'])) 			  ? $_REQUEST['sSite'] 			    : NULL;
		$invitedFriends 	= (isset($_REQUEST['invitedFriends']) 	  && !empty($_REQUEST['invitedFriends'])) 	  ? $_REQUEST['invitedFriends']	    : NULL;
		
		$success = false;
		
		$success = GroupCreate(
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
							);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
	
	break;
	
	case 'updateGroup' :
		
		$idGroup 			= (isset($_REQUEST['idGroup']) 			  && !empty($_REQUEST['idGroup'])) 			  ? $_REQUEST['idGroup'] 		    : NULL;
		$sDisplayName 		= (isset($_REQUEST['sDisplayName']) 	  && !empty($_REQUEST['sDisplayName'])) 	  ? $_REQUEST['sDisplayName'] 	    : NULL;
		$iGroupType			= (isset($_REQUEST['iGroupType']) 		  && !empty($_REQUEST['iGroupType'])) 		  ? $_REQUEST['iGroupType']		    : NULL;
		$sGroupTheme		= (isset($_REQUEST['sGroupTheme']) 		  && !empty($_REQUEST['sGroupTheme'])) 		  ? $_REQUEST['sGroupTheme']	    : NULL;
		$dtBegin			= (isset($_REQUEST['dtBegin']) 			  && !empty($_REQUEST['dtBegin'])) 			  ? $_REQUEST['dtBegin'] 		    : NULL;
		$dtEnd				= (isset($_REQUEST['dtEnd']) 			  && !empty($_REQUEST['dtEnd'])) 			  ? $_REQUEST['dtEnd'] 		    	: NULL;
		$iPeriodicity		= (isset($_REQUEST['iPeriodicity']) 	  && !empty($_REQUEST['iPeriodicity'])) 	  ? $_REQUEST['iPeriodicity']	    : NULL;
		$iPeriodicityDetail	= (isset($_REQUEST['iPeriodicityDetail']) && !empty($_REQUEST['iPeriodicityDetail'])) ? $_REQUEST['iPeriodicityDetail'] : NULL;
		$tsOracao			= (isset($_REQUEST['tsOracao']) 		  && !empty($_REQUEST['tsOracao'])) 		  ? $_REQUEST['tsOracao'] 			: NULL;
		$idADMINCountry 	= (isset($_REQUEST['idADMINCountry']) 	  && !empty($_REQUEST['idADMINCountry'])) 	  ? $_REQUEST['idADMINCountry']     : NULL;
		$idADMINState 		= (isset($_REQUEST['idADMINState']) 	  && !empty($_REQUEST['idADMINState'])) 	  ? $_REQUEST['idADMINState'] 	    : NULL;
		$idADMINCity 		= (isset($_REQUEST['idADMINCity']) 		  && !empty($_REQUEST['idADMINCity'])) 		  ? $_REQUEST['idADMINCity'] 	    : NULL;
		$sDescription 		= (isset($_REQUEST['sDescription']) 	  && !empty($_REQUEST['sDescription'])) 	  ? $_REQUEST['sDescription'] 	    : NULL;
		$sSite 				= (isset($_REQUEST['sSite']) 			  && !empty($_REQUEST['sSite'])) 			  ? $_REQUEST['sSite'] 			    : NULL;
	//	$invitedFriends 	= (isset($_REQUEST['invitedFriends']) 	  && !empty($_REQUEST['invitedFriends'])) 	  ? $_REQUEST['invitedFriends']	    : NULL;
		
		$success = false;
		
		$success = GroupUpdate(
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
							);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
	
	break;
	
	
	case 'deleteAccount' :
		
		$success = false;
		
		$idUser 				= (isset($_SESSION["loggedUser"]) && !empty($_SESSION["loggedUser"]))	? $_SESSION["loggedUser"] 	: NULL;
		$sMotivo 				= (isset($_REQUEST['sMotivo'])	  && !empty($_REQUEST['sMotivo']))		? $_REQUEST['sMotivo'] 		: NULL;
		
		$success = deleteAccount($idUser, $sMotivo);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	
	case 'updateConfig' :
		
		$success = false;
		
		$idUser 				= (isset($_SESSION["loggedUser"])	 		&& !empty($_SESSION["loggedUser"])) 		? $_SESSION["loggedUser"] 			: NULL;
		$sFullName 				= (isset($_REQUEST['sFullName'])	 		&& !empty($_REQUEST['sFullName'])) 			? $_REQUEST['sFullName'] 			: NULL;
		$sDisplayName 			= (isset($_REQUEST['sDisplayName'])	 		&& !empty($_REQUEST['sDisplayName'])) 		? $_REQUEST['sDisplayName'] 		: NULL;
		$dtBirthday 			= (isset($_REQUEST['dtBirthday'])	 		&& !empty($_REQUEST['dtBirthday'])) 		? $_REQUEST['dtBirthday'] 			: NULL;
		$idADMINCountry			= (isset($_REQUEST['idADMINCountry'])		&& !empty($_REQUEST['idADMINCountry']))		? $_REQUEST['idADMINCountry'] 		: NULL;
		$idADMINState 			= (isset($_REQUEST['idADMINState'])	 		&& !empty($_REQUEST['idADMINState'])) 		? $_REQUEST['idADMINState'] 		: NULL;
		$idADMINCity 			= (isset($_REQUEST['idADMINCity'])	 		&& !empty($_REQUEST['idADMINCity'])) 		? $_REQUEST['idADMINCity'] 			: NULL;
		$sPostalCode 			= (isset($_REQUEST['sPostalCode'])	 		&& !empty($_REQUEST['sPostalCode'])) 		? $_REQUEST['sPostalCode'] 			: NULL;
		$sEmailAtual			= (isset($_REQUEST['sEmailAtual'])	 		&& !empty($_REQUEST['sEmailAtual'])) 		? $_REQUEST['sEmailAtual'] 			: NULL;
		$sEmailNovo				= (isset($_REQUEST['sEmailNovo'])	 		&& !empty($_REQUEST['sEmailNovo'])) 		? $_REQUEST['sEmailNovo'] 			: NULL;
		$sPasswordAtual			= (isset($_REQUEST['sPasswordAtual'])	 	&& !empty($_REQUEST['sPasswordAtual'])) 	? $_REQUEST['sPasswordAtual'] 		: NULL;
		$sPasswordNovo			= (isset($_REQUEST['sPasswordNovo'])	 	&& !empty($_REQUEST['sPasswordNovo'])) 		? $_REQUEST['sPasswordNovo'] 		: NULL;
		
		$spiritualAffinities 	= '';
		
		// $spiritualAffinities 	= (isset($_REQUEST['spiritualAffinities']) && !empty($_REQUEST['spiritualAffinities'])) ? $_REQUEST['spiritualAffinities'] 	: NULL;
		
		$success = updateConfig($idUser, $sFullName, $sDisplayName, $dtBirthday, $idADMINCountry, $idADMINState, $idADMINCity, $sPostalCode, $sEmailAtual, $sEmailNovo, $sPasswordAtual, $sPasswordNovo, $spiritualAffinities );
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	
	
	case 'loadHistory' :
		
		$idUser  = (isset($_SESSION["loggedUser"]) 	&& !empty($_SESSION["loggedUser"])) ? $_SESSION["loggedUser"] 	: NULL;
		$idList  = (isset($_REQUEST['idList']) 		&& !empty($_REQUEST['idList']))  	? $_REQUEST['idList']  		: NULL;
		$iOffset = (isset($_REQUEST['iOffset']) 	&& !empty($_REQUEST['iOffset'])) 	? $_REQUEST['iOffset'] 		: NULL;
		$iLimit  = (isset($_REQUEST['iLimit']) 		&& !empty($_REQUEST['iLimit']))  	? $_REQUEST['iLimit']  		: NULL;
		$iType   = (isset($_REQUEST['iType']) 		&& !empty($_REQUEST['iType']))  	? $_REQUEST['iType']  		: NULL;
		
		
		// ítens da timeline:
		$postList = array();
		$list = getAssociations(
								explode(',',$idList), 
								'REFERRED', 
							//	array('POST','PHOTO'), 
								explode(',',$iType), 
								'OBJECT', 
								'o.tsCreation DESC',
								$iOffset, 
								$iLimit
								);
		
		foreach ($list as $item) {
			
			$owner 					= getAssociations($item['id'], 'OBJECT', 'POST', 'OWNER');
			if (!$owner) { $owner 	= getAssociations($item['id'], 'OBJECT', 'PHOTO', 'OWNER'); }
			$owner 					= getObject($owner[0]['id']);
			$owner['properties'] 	= getProperties($owner['id']);
			
		//	$item_properties['sDataVideo'] 	= getProperties($item['id'],'sDataVideo');
		//	$item_properties['sDataPhoto'] 	= getProperties($item['id'],'sDataPhoto');
			
			$item_properties 		= getProperties($item['id']);
			
			$item_details 			= getDetails($item['id']);
			
			$item_you_like 			= count(likeStatus($idUser ,$item['id'])) > 0 ? true : false;
			$item_likes 			= getCountAssociations($item['id'],'OBJECT','','LIKER');
			
			$item_you_report		= count(reportStatus($idUser ,$item['id'])) > 0 ? true : false;
			$item_reports 			= getCountAssociations($item['id'],'REFERRED','REPORT','OWNER');
			
			// count(likeStatus($idUser ,$item['id']));
			
			
			
			// comentários de cada ítem da timeline:
			$comm = array();
			$listComment = getAssociations(
											$item['id'],
											'REFERRED',
											'COMMENT',
											'OBJECT'
											);
			
			for ($j=0;$j<count($listComment);$j++) {
				
				$comm_owner 				= getAssociations($listComment[$j]['id'], 'OBJECT', 'COMMENT', 'OWNER');
				$comm_owner 				= getObject($comm_owner[0]['id']);
				$comm_owner['properties'] 	= getProperties($comm_owner['id']);
				
				$comm_properties 			= getProperties($listComment[$j]['id']);
				$comm_details 				= getDetails($listComment[$j]['id']);
				
				$comm_you_like 				= count(likeStatus($idUser ,$listComment[$j]['id'])) > 0 ? true : false;
				$comm_likes 				= getCountAssociations($listComment[$j]['id'],'OBJECT','','LIKER');
				
				$comm_you_report			= count(reportStatus($idUser ,$listComment[$j]['id'])) > 0 ? true : false;
				$comm_reports 				= getCountAssociations($listComment[$j]['id'],'REFERRED','REPORT','OWNER');
				
				
				$comm[] = array(
								"id"			=> $listComment[$j]['id'],
								"item"			=> getObject($listComment[$j]['id']),
								"owner" 		=> $comm_owner,
								"properties" 	=> $comm_properties,
								"details" 		=> $comm_details,
								"youLike"		=> $comm_you_like,
								"likes"			=> $comm_likes,
								"youReport"		=> $comm_you_report,
								"reports"		=> $comm_reports
								);
			}
			
			$postList[] = array(
							"id" 			=> $item['id'],
							"item"			=> getObject($item['id']),
							"owner" 		=> $owner,
							"properties" 	=> $item_properties,
							"details" 		=> $item_details,
							"youLike"		=> $item_you_like,
							"likes"			=> $item_likes,
							"youReport"		=> $item_you_report,
							"reports"		=> $item_reports,
							"comments"		=> $comm
							);
		
		}
		
		$rows = array();
		$rows['success'] = true;
		$rows['result']  = $postList;
		
		echo json_encode(to_utf8($rows));
		
	break;
	
	
	
	
	
	case 'ProfileProposeFriendship' :
		
		$idProposerProfile 	= (isset($_REQUEST['idProposerProfile']) 	&& !empty($_REQUEST['idProposerProfile'])) ? $_REQUEST['idProposerProfile'] : NULL;
		$idGuestProfile 	= (isset($_REQUEST['idGuestProfile']) 		&& !empty($_REQUEST['idGuestProfile']))	? $_REQUEST['idGuestProfile'] 		: NULL;
		
		$success = false;
		$success = ProfileProposeFriendship($idProposerProfile, $idGuestProfile);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'ProfileAcceptFriendship' :
		
		$idFriend 		= (isset($_REQUEST['idFriend']) 	&& !empty($_REQUEST['idFriend'])) 	? $_REQUEST['idFriend'] 	: NULL;
		$idMyProfile 	= (isset($_REQUEST['idMyProfile']) 	&& !empty($_REQUEST['idMyProfile']))? $_REQUEST['idMyProfile'] 	: NULL;
		
		$success = false;
		$success = ProfileAcceptFriendship($idFriend, $idMyProfile);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'ProfileEndFriendship' :
		
		$idProfile		 = (isset($_REQUEST['idProfile']) 		&& !empty($_REQUEST['idProfile']))		 ? $_REQUEST['idProfile'] 		: NULL;
		$idFriendProfile = (isset($_REQUEST['idFriendProfile']) && !empty($_REQUEST['idFriendProfile'])) ? $_REQUEST['idFriendProfile'] : NULL;
		
		$success = false;
		$success = ProfileEndFriendship($idProfile, $idFriendProfile);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'PostCreate' :
		
		$idReferredObject	= (isset($_REQUEST['idReferredObject']) && !empty($_REQUEST['idReferredObject'])) 	? $_REQUEST['idReferredObject'] : NULL;
		$sContent 			= (isset($_REQUEST['sContent']) 		&& !empty($_REQUEST['sContent']))			? $_REQUEST['sContent'] 		: NULL;
		$sDataVideo 		= (isset($_REQUEST['sDataVideo']) 		&& !empty($_REQUEST['sDataVideo']))			? $_REQUEST['sDataVideo'] 		: NULL;
		
		$success = false;
		$success = PostCreate($idReferredObject, $sContent, $sDataVideo);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'PostShare' :
		
		$idOriginalPost		= (isset($_REQUEST['idOriginalPost']) 	&& !empty($_REQUEST['idOriginalPost'])) 	? $_REQUEST['idOriginalPost'] 	: NULL;
		
		$success = false;
		$success = PostShare($idOriginalPost);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'ReportAbuse' :
		
		$idObject 		= (isset($_REQUEST['idObject']) 	&& !empty($_REQUEST['idObject'])) 	  ? $_REQUEST['idObject'] 	  : NULL;
		$sAbuseType		= (isset($_REQUEST['sAbuseType']) 	&& !empty($_REQUEST['sAbuseType']))   ? $_REQUEST['sAbuseType']   : NULL;
		$sDescription	= (isset($_REQUEST['sDescription']) && !empty($_REQUEST['sDescription'])) ? $_REQUEST['sDescription'] : NULL;
		
		$success = false;
		$success = ReportAbuse($idObject, $sAbuseType, $sDescription);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'deleteReport' :
		
		$idReport = (isset($_REQUEST['idReport']) && !empty($_REQUEST['idReport'])) ? $_REQUEST['idReport'] : NULL;
		
		$success = false;
		$success = deleteReport($idReport);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'deleteObject' :
		
		$idObject = (isset($_REQUEST['idObject']) && !empty($_REQUEST['idObject'])) ? $_REQUEST['idObject'] : NULL;
		
		$success = false;
		$success = deleteObject($idObject);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	
	
	case 'CommentCreate' :
		
		$idReferredObject	= (isset($_REQUEST['idReferredObject']) && !empty($_REQUEST['idReferredObject'])) 	? $_REQUEST['idReferredObject'] : NULL;
		$sContent 			= (isset($_REQUEST['sContent']) 		&& !empty($_REQUEST['sContent']))			? $_REQUEST['sContent'] 		: NULL;
		
		$success = false;
		$success = CommentCreate($idReferredObject, $sContent);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'messageCreate' :
		
		$idSender			= (isset($_SESSION["loggedUser"])  		&& !empty($_SESSION["loggedUser"])) 	? $_SESSION["loggedUser"] 		: NULL;
		
		$idReceiver			= (isset($_REQUEST['idReceiver'])		&& !empty($_REQUEST['idReceiver']))  	? $_REQUEST['idReceiver'] 		: NULL;
		$idConversation		= (isset($_REQUEST['idConversation'])  	&& !empty($_REQUEST['idConversation'])) ? $_REQUEST['idConversation'] 	: NULL;
		$sContent 			= (isset($_REQUEST['sContent'])    		&& !empty($_REQUEST['sContent']))		? $_REQUEST['sContent']			: NULL;
		
		$success = false;
		$success = messageCreate($idSender, $idReceiver, $idConversation, $sContent);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'EventCreate' :
		
		$idReferredObject	= (isset($_REQUEST['idReferredObject']) && !empty($_REQUEST['idReferredObject'])) ? $_REQUEST['idReferredObject'] : NULL;
		
		$sDisplayName		= (isset($_REQUEST['sDisplayName']) 	&& !empty($_REQUEST['sDisplayName'])) 	? $_REQUEST['sDisplayName'] 	: NULL;
		$tsBegin			= (isset($_REQUEST['tsBegin']) 			&& !empty($_REQUEST['tsBegin'])) 		? $_REQUEST['tsBegin'] 			: NULL;
		$tsEnd				= (isset($_REQUEST['tsEnd']) 			&& !empty($_REQUEST['tsEnd'])) 			? $_REQUEST['tsEnd'] 			: NULL;
		$sAddress			= (isset($_REQUEST['sAddress']) 		&& !empty($_REQUEST['sAddress'])) 		? $_REQUEST['sAddress'] 		: NULL;
		$sComplement		= (isset($_REQUEST['sComplement']) 		&& !empty($_REQUEST['sComplement'])) 	? $_REQUEST['sComplement'] 		: NULL;
		$idADMINCountry		= (isset($_REQUEST['idADMINCountry']) 	&& !empty($_REQUEST['idADMINCountry'])) ? $_REQUEST['idADMINCountry'] 	: NULL;
		$idADMINState		= (isset($_REQUEST['idADMINState']) 	&& !empty($_REQUEST['idADMINState'])) 	? $_REQUEST['idADMINState']		: NULL;
		$idADMINCity		= (isset($_REQUEST['idADMINCity']) 		&& !empty($_REQUEST['idADMINCity'])) 	? $_REQUEST['idADMINCity'] 		: NULL;
		$sNeighborhood		= (isset($_REQUEST['sNeighborhood']) 	&& !empty($_REQUEST['sNeighborhood'])) 	? $_REQUEST['sNeighborhood']	: NULL;
		$sPostalCode		= (isset($_REQUEST['sPostalCode']) 		&& !empty($_REQUEST['sPostalCode'])) 	? $_REQUEST['sPostalCode'] 		: NULL;
		$sSite				= (isset($_REQUEST['sSite']) 			&& !empty($_REQUEST['sSite'])) 			? $_REQUEST['sSite'] 			: NULL;
		$sDescription		= (isset($_REQUEST['sDescription']) 	&& !empty($_REQUEST['sDescription'])) 	? $_REQUEST['sDescription'] 	: NULL;
		
		
		$success = false;
		
		$success = EventCreate(
								$idReferredObject,
								$sDisplayName,
								$tsBegin,
								$tsEnd,
								$sAddress,
								$sComplement,
								$idADMINCountry,
								$idADMINState,
								$idADMINCity,
								$sNeighborhood,
								$sPostalCode,
								$sSite,
								$sDescription
							);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'EventEdit' :
		
		$idEvent		= (isset($_REQUEST['idEvent']) 			&& !empty($_REQUEST['idEvent'])) 		? $_REQUEST['idEvent'] 			: NULL;
		$sDisplayName	= (isset($_REQUEST['sDisplayName']) 	&& !empty($_REQUEST['sDisplayName'])) 	? $_REQUEST['sDisplayName'] 	: NULL;
		$tsBegin		= (isset($_REQUEST['tsBegin']) 			&& !empty($_REQUEST['tsBegin'])) 		? $_REQUEST['tsBegin'] 			: NULL;
		$tsEnd			= (isset($_REQUEST['tsEnd']) 			&& !empty($_REQUEST['tsEnd'])) 			? $_REQUEST['tsEnd'] 			: NULL;
		$sAddress		= (isset($_REQUEST['sAddress']) 		&& !empty($_REQUEST['sAddress'])) 		? $_REQUEST['sAddress'] 		: NULL;
		$sComplement	= (isset($_REQUEST['sComplement']) 		&& !empty($_REQUEST['sComplement'])) 	? $_REQUEST['sComplement'] 		: NULL;
		$idADMINCountry	= (isset($_REQUEST['idADMINCountry']) 	&& !empty($_REQUEST['idADMINCountry'])) ? $_REQUEST['idADMINCountry'] 	: NULL;
		$idADMINState	= (isset($_REQUEST['idADMINState']) 	&& !empty($_REQUEST['idADMINState'])) 	? $_REQUEST['idADMINState']		: NULL;
		$idADMINCity	= (isset($_REQUEST['idADMINCity']) 		&& !empty($_REQUEST['idADMINCity'])) 	? $_REQUEST['idADMINCity'] 		: NULL;
		$sNeighborhood	= (isset($_REQUEST['sNeighborhood']) 	&& !empty($_REQUEST['sNeighborhood'])) 	? $_REQUEST['sNeighborhood']	: NULL;
		$sPostalCode	= (isset($_REQUEST['sPostalCode']) 		&& !empty($_REQUEST['sPostalCode'])) 	? $_REQUEST['sPostalCode'] 		: NULL;
		$sSite			= (isset($_REQUEST['sSite']) 			&& !empty($_REQUEST['sSite'])) 			? $_REQUEST['sSite'] 			: NULL;
		$sDescription	= (isset($_REQUEST['sDescription']) 	&& !empty($_REQUEST['sDescription'])) 	? $_REQUEST['sDescription'] 	: NULL;
		
		
		$success = false;
		
		$success = EventEdit(
								$idEvent,
								$sDisplayName,
								$tsBegin,
								$tsEnd,
								$sAddress,
								$sComplement,
								$idADMINCountry,
								$idADMINState,
								$idADMINCity,
								$sNeighborhood,
								$sPostalCode,
								$sSite,
								$sDescription
							);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	
	
	case 'likeObject' :
		
		$idUser 			= (isset($_SESSION["loggedUser"]) 		&& !empty($_SESSION["loggedUser"])) 		? $_SESSION["loggedUser"] 		: NULL;
		$idReferredObject 	= (isset($_REQUEST['idReferredObject']) && !empty($_REQUEST['idReferredObject'])) 	? $_REQUEST['idReferredObject'] : NULL;
		
		$success = false;
		$success = likeObject($idUser, $idReferredObject);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'dislikeObject' :
		
		$idUser 			= (isset($_SESSION["loggedUser"]) 		&& !empty($_SESSION["loggedUser"])) 		? $_SESSION["loggedUser"] 		: NULL;
		$idReferredObject 	= (isset($_REQUEST['idReferredObject']) && !empty($_REQUEST['idReferredObject'])) 	? $_REQUEST['idReferredObject'] : NULL;
		
		$success = false;
		$success = dislikeObject($idUser, $idReferredObject);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	
	
	case 'PageFollow' :
		
		$idProfile 	= (isset($_REQUEST['idProfile']) && !empty($_REQUEST['idProfile'])) ? $_REQUEST['idProfile'] : NULL;
		$idPage 	= (isset($_REQUEST['idPage'])	 && !empty($_REQUEST['idPage']))	? $_REQUEST['idPage']	 : NULL;
		
		$success = false;
		$success = PageFollow($idProfile, $idPage);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'PageUnfollow' :
		
		$idProfile 	= (isset($_REQUEST['idProfile']) && !empty($_REQUEST['idProfile'])) ? $_REQUEST['idProfile'] : NULL;
		$idPage 	= (isset($_REQUEST['idPage'])	 && !empty($_REQUEST['idPage']))	? $_REQUEST['idPage']	 : NULL;
		
		$success = false;
		$success = PageUnfollow($idProfile, $idPage);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	
	
	
	case 'GroupFollow' :
		
		$idProfile 	= (isset($_REQUEST['idProfile']) && !empty($_REQUEST['idProfile'])) ? $_REQUEST['idProfile'] : NULL;
		$idGroup 	= (isset($_REQUEST['idGroup'])	 && !empty($_REQUEST['idGroup']))	? $_REQUEST['idGroup']	 : NULL;
		
		$success = false;
		$success = GroupFollow($idProfile, $idGroup);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'GroupUnfollow' :
		
		$idProfile 	= (isset($_REQUEST['idProfile']) && !empty($_REQUEST['idProfile'])) ? $_REQUEST['idProfile'] : NULL;
		$idGroup 	= (isset($_REQUEST['idGroup'])	 && !empty($_REQUEST['idGroup']))	? $_REQUEST['idGroup']	 : NULL;
		
		$success = false;
		$success = GroupUnfollow($idProfile, $idGroup);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	case 'ObjectSetProperty' :
		
		$idSOCIALObject 	= (isset($_REQUEST['idSOCIALObject'])	&& !empty($_REQUEST['idSOCIALObject']))		? $_REQUEST['idSOCIALObject']	: NULL;
		$idSOCIALProperty 	= (isset($_REQUEST['idSOCIALProperty'])	&& !empty($_REQUEST['idSOCIALProperty']))	? $_REQUEST['idSOCIALProperty']	: NULL;
		$sValue 			= (isset($_REQUEST['sValue'])			&& !empty($_REQUEST['sValue']))				? $_REQUEST['sValue']			: NULL;
		
		if ($idSOCIALObject == "user") {
			$idSOCIALObject = $_SESSION["loggedUser"];
		}
		
		$success = false;
		$success = ObjectSetProperty($idSOCIALObject, $idSOCIALProperty, $sValue);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	
	case 'uploadFile__old' :
		
		$dirName = "/images";
		
		// o parâmetro 'checkFile' é usado para identificar se a chamada a esta função é para fazer o uploda do arquivo
		// ou se apenas está verificando o status do processo de upload;
		
		// upload:
		if (!isset($_GET['checkFile']) && isset($_FILES['picture'])) {
			
			
			// verifica se o arquivo é uma imagem:
			if (!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $_FILES['picture']['type'])) {
				echo '{"success":"false","status":"complete"}';
				return;
			}
			
			
			// tenta criar o diretório de upload, caso não exista:
			@mkdir($_SERVER['DOCUMENT_ROOT'].$dirName,0777,true);
			
			
			// gera um nome único para salvar a nova imagem:
			preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $_FILES['picture']['name'], $extension);
			$fileName = md5(uniqid(time())).".".$extension[1];
			
			$_SESSION['fileName'] 		= $dirName.'/'.$fileName;
			$_SESSION['fileUploaded'] 	= false;
			
			
			// begin - redimensionamento da imagem
			
			if ($extension[1] == "jpg" || $extension[1] == "jpeg" ) {
				
				$srcImage = imagecreatefromjpeg($_FILES['picture']['tmp_name']);
				
			} else if ($extension[1] == "png") {
				
				$srcImage = imagecreatefrompng($_FILES['picture']['tmp_name']);
				
			} else {
				$srcImage = imagecreatefromgif($_FILES['picture']['tmp_name']);
			}
			
			$dimensions  = getimagesize($_FILES['picture']['tmp_name']);
			$imageWidth  = $dimensions[0];
			$imageHeigth = $dimensions[1];
			
			$newWidth = 150;
			$newHeight = ($imageHeigth / $imageWidth) * $newWidth;
			$tmpImage = imagecreatetruecolor($newWidth, $newHeight);
			
			
			imagecopyresampled($tmpImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $imageWidth, $imageHeigth);
			imagejpeg($tmpImage, $_SERVER['DOCUMENT_ROOT'].$dirName.'/'.$fileName, 100);
			
			imagedestroy($srcImage);
			imagedestroy($tmpImage);
			
			$_SESSION['fileUploaded'] = true;
			
			// end - redimensionamento da imagem
			
		}
		
		// checando status:
		if (isset($_GET['checkFile']) && $_GET['checkFile'] == 1) {
			
			if ( file_exists($_SERVER['DOCUMENT_ROOT'].$_SESSION['fileName']) && $_SESSION['fileUploaded'] == true) {
				
				echo '{"success":"true","status":"complete","file":"'.$_SESSION['fileName'].'"}';
				
				$_SESSION['fileName'] 		= NULL;
				$_SESSION['fileUploaded'] 	= NULL;
				
			} else {
				echo '{"success":"false","status":"loading"}';
				// header('Location: '.$_SERVER['DOCUMENT_ROOT'].'/');
			}
		}
		
	break;
	
	
	
	
	case 'sendInvite' :
		
		$success = false;
		
		$idUser 		= (isset($_SESSION["loggedUser"]) 	&& !empty($_SESSION["loggedUser"]))		? $_SESSION["loggedUser"] 	: NULL;
		$listFriends 	= (isset($_REQUEST['listFriends'])	&& !empty($_REQUEST['listFriends'])) 	? $_REQUEST['listFriends']	: NULL;
		$sMessage 		= (isset($_REQUEST['sMessage'])	 	&& !empty($_REQUEST['sMessage'])) 		? $_REQUEST['sMessage'] 	: NULL;
		
		$success = sendInvite($idUser, $listFriends, $sMessage);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	
	
	
	
	
	case 'AlbumCreate' :
		
		$idProfile		= (isset($_REQUEST['idProfile']) 	&& !empty($_REQUEST['idProfile'])) 		? $_REQUEST['idProfile'] 	: NULL;
		$sDisplayName 	= (isset($_REQUEST['sDisplayName']) && !empty($_REQUEST['sDisplayName']))	? $_REQUEST['sDisplayName'] : NULL;
		
		$success = false;
		$success = AlbumCreate($idProfile, $sDisplayName);
		
		$rows = array();
		$rows['success'] = $success;
		echo json_encode(to_utf8($rows));
		
	break;
	
	
	
	case 'PhotoCreate' :
		
		$idReferredObject	= (isset($_REQUEST['idReferredObject']) && !empty($_REQUEST['idReferredObject'])) 	? $_REQUEST['idReferredObject'] : NULL;
		$sContent 			= (isset($_REQUEST['sContent']) 		&& !empty($_REQUEST['sContent']))			? $_REQUEST['sContent'] 		: NULL;
		$sContent			= utf8_encode($sContent);
		
		// id do objeto Photo criado:
		$lastId = PhotoCreate($idReferredObject, $sContent);
		
		
		if ($lastId != false) {
			
			$dirName = "/images";
			
			// upload:
			if (isset($_FILES['picture'])) {
				
				
				// verifica se o arquivo é uma imagem:
				if (!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/i", $_FILES['picture']['type'])) {
					
					$rows = array();
					$rows['success'] = false;
					$rows['status']  = "complete";
					$rows['message']  = "o arquivo selecionado não é uma imagem válida";
					echo json_encode(to_utf8($rows));
					
					return;
				}
				
				
				
				// tenta criar o diretório de upload, caso não exista:
				@mkdir($_SERVER['DOCUMENT_ROOT'].$dirName,0777,true);
				
				
				// gera um nome único para salvar a nova imagem (usando o id do objeto Photo criado):
				preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $_FILES['picture']['name'], $extension);
				$fileName  = $lastId.".".$extension[1]; // md5(uniqid(time())).".".$extension[1];
				$thumbName = $lastId."-thumb.".$extension[1];
				
			//	$_SESSION['fileName'] 		= $dirName.'/'.$fileName;
			//	$_SESSION['fileUploaded'] 	= false;
				
				// --------------------------------------------------
				// begin - redimensionamento da imagem
				
				if ($extension[1] == "jpg" || $extension[1] == "jpeg" ) {
					
					$srcImage = imagecreatefromjpeg($_FILES['picture']['tmp_name']);
					
				} else if ($extension[1] == "png") {
					
					$srcImage = imagecreatefrompng($_FILES['picture']['tmp_name']);
					
				} else {
					$srcImage = imagecreatefromgif($_FILES['picture']['tmp_name']);
				}
				
				$dimensions  = getimagesize($_FILES['picture']['tmp_name']);
				$imageWidth  = $dimensions[0];
				$imageHeigth = $dimensions[1];
				
				$thumbWidth 	= 120;
				$thumbHeight 	= ($imageHeigth / $imageWidth) * $thumbWidth;
				$tmpThumbImage 	= imagecreatetruecolor($thumbWidth, $thumbHeight);
				
				$fullWidth 		= $imageWidth > 1024 ? 1024 : $imageWidth;
				$fullHeight 	= ($imageHeigth / $imageWidth) * $fullWidth;
				$tmpFullImage 	= imagecreatetruecolor($fullWidth, $fullHeight);
				
				
				imagecopyresampled($tmpFullImage, $srcImage, 0, 0, 0, 0, $fullWidth, $fullHeight, $imageWidth, $imageHeigth);
				imagejpeg($tmpFullImage, $_SERVER['DOCUMENT_ROOT'].$dirName.'/'.$fileName, 100);
				
				imagecopyresampled($tmpThumbImage, $srcImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $imageWidth, $imageHeigth);
				imagejpeg($tmpThumbImage, $_SERVER['DOCUMENT_ROOT'].$dirName.'/'.$thumbName, 100);
				
				
				imagedestroy($srcImage);
				imagedestroy($tmpFullImage);
				imagedestroy($tmpThumbImage);
				
			//	$_SESSION['fileUploaded'] = true;
				
				// --------------------------------------------------
				// end - redimensionamento da imagem
				
				// atualiza o caminho da imagem no objeto Photo:
				
			//	$imgPath = filter_var(($dirName.'/'.$fileName), FILTER_SANITIZE_MAGIC_QUOTES);
				$imgPath = '{"thumb":"'.($dirName.'/'.$thumbName).'","link":"'.($dirName.'/'.$fileName).'"}';
				$imgPath = filter_var($imgPath, FILTER_SANITIZE_MAGIC_QUOTES);
				
				if (ObjectCreateProperty($lastId, 25, $imgPath)) {
					
					// upload e criação do objeto PHOTO concluído com sucesso!
					// echo "<script>parent.location.reload();</script>";
					// header('Location: /');
					// echo "<script>alert('parent.name = '+parent.name);</script>";
					
					echo "<script>var loc=parent.location;parent.location=loc;</script>";
				}
			}
			
		} else {
			
			// houve um erro ao criar o objeto PHOTO:
			$rows = array();
			$rows['success'] = false;
			$rows['status']  = "complete";
			$rows['message']  = "não foi possível criar o post com a imagem";
			echo json_encode(to_utf8($rows));
			
			return;
		}
		
	break;
	
	
	case 'uploadPicture' :
		
		$dirName = "/images";
		
		// o parâmetro 'checkFile' é usado para identificar se a chamada a esta função é para fazer o uploda do arquivo
		// ou se apenas está verificando o status do processo de upload;
		
		// upload:
		if (!isset($_GET['checkFile']) && isset($_FILES['picture'])) {
			
			
			// verifica se o arquivo é uma imagem:
			if (!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $_FILES['picture']['type'])) {
				
				$rows = array();
				$rows['success'] = false;
				$rows['status']  = "complete";
				echo json_encode(to_utf8($rows));
				// echo '{"success":"false","status":"complete"}';
				return;
			}
			
			
			// tenta criar o diretório de upload, caso não exista:
			@mkdir($_SERVER['DOCUMENT_ROOT'].$dirName,0777,true);
			
			
			// gera um nome único para salvar a nova imagem:
			preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $_FILES['picture']['name'], $extension);
			$fileName = md5(uniqid(time())).".".$extension[1];
			
			$_SESSION['fileName'] 		= $dirName.'/'.$fileName;
			$_SESSION['fileUploaded'] 	= false;
			
			
			// begin - redimensionamento da imagem
			
			if ($extension[1] == "jpg" || $extension[1] == "jpeg" ) {
				
				$srcImage = imagecreatefromjpeg($_FILES['picture']['tmp_name']);
				
			} else if ($extension[1] == "png") {
				
				$srcImage = imagecreatefrompng($_FILES['picture']['tmp_name']);
				
			} else {
				$srcImage = imagecreatefromgif($_FILES['picture']['tmp_name']);
			}
			
			$dimensions  = getimagesize($_FILES['picture']['tmp_name']);
			$imageWidth  = $dimensions[0];
			$imageHeigth = $dimensions[1];
			
			$newWidth 	= 800;
			$newHeight 	= ($imageHeigth / $imageWidth) * $newWidth;
			$tmpImage 	= imagecreatetruecolor($newWidth, $newHeight);
			
			
			imagecopyresampled($tmpImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $imageWidth, $imageHeigth);
			imagejpeg($tmpImage, $_SERVER['DOCUMENT_ROOT'].$dirName.'/'.$fileName, 100);
			
			imagedestroy($srcImage);
			imagedestroy($tmpImage);
			
			$_SESSION['fileUploaded'] = true;
			
			// end - redimensionamento da imagem
			
		}
		
		// checando status:
		if (isset($_GET['checkFile']) && $_GET['checkFile'] == 1) {
			
			if ( file_exists($_SERVER['DOCUMENT_ROOT'].$_SESSION['fileName']) && $_SESSION['fileUploaded'] == true) {
				
				// echo '{"success":"true","status":"complete","file":"'.$_SESSION['fileName'].'"}';
				
				$rows = array();
				$rows['success'] = true;
				$rows['status']  = "complete";
				$rows['file']    = $_SESSION['fileName'];
				echo json_encode(to_utf8($rows));
				
				$_SESSION['fileName'] 		= NULL;
				$_SESSION['fileUploaded'] 	= NULL;
				
			} else {
				
				// echo '{"success":"false","status":"loading"}';
				
				$rows = array();
				$rows['success'] = false;
				$rows['status']  = "loading";
				echo json_encode(to_utf8($rows));
			}
		}
		
	break;
	
	
	
	case 'EventLoad' :
		
		$idEvent = (isset($_REQUEST['idEvent']) && !empty($_REQUEST['idEvent'])) ? $_REQUEST['idEvent'] : NULL;
		
		$event = getObject($idEvent);
		
		$event['properties'] = getProperties($event['id'],'');
		$event['details']	 = getDetails($event['id'],'');
		
		echo json_encode(to_utf8($event));
		
	break;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}




/* 	TO-DO: 
	este objeto será o grande responsável por todas as interações com objetos da rede social
	objetos da rede social são Posts, Profiles, Pages, Groups, etc.
*/

class SocialObject {
	
	public $id;
	public $sDisplayName;
	public $sFullName;
	public $sDirectLink;
	public $iType;
	public $tsCreation;
	
	public $objectProperties;
	public $objectDetails;

	function __construct() {
		
	}
	
	/*
	public function set_iType($new_iType) {
		$this->iType = $new_objectType;
	}

	public function get_iType() {
		return $this->iType;
	}
	*/
	
	
	
	protected function getAssociations($par_myRole, $par_associationType, $par_theirRole) {
		
		if (is_null($this->id)) {
			return false;
		}
		
		global $r_associationType;
		global $r_objectAssociationType;
		
		$par_myRole 			= !isset($par_myRole)			? '' : strtoupper($par_myRole);
		$par_associationType 	= !isset($par_associationType)	? '' : strtoupper($par_associationType);
		$par_theirRole 			= !isset($par_theirRole)		? '' : strtoupper($par_theirRole);

		// echo '$par_myRole = ' . $par_myRole.'<br>';

		$par_myRole 			= $par_myRole == '' 		 ? '' : $r_objectAssociationType[$par_myRole];
		$par_associationType 	= $par_associationType == '' ? '' : $r_associationType[$par_associationType];
		$par_theirRole 			= $par_theirRole == '' 		 ? '' : $r_objectAssociationType[$par_theirRole];
		
		// echo '$par_myRole = ' . $par_myRole.'<br>';

		
		$where = ''	;
		$where .= $par_myRole == ''			 ? '' : ('AND ' . 'me.iType = '.$par_myRole.' ');
		$where .= $par_associationType == '' ? '' : ('AND ' . 'a.iType = '.$par_associationType.' ');
		$where .= $par_theirRole == '' 		 ? '' : ('AND ' . 'them.iType = '.$par_theirRole.' ');
			
		$query = '
			SELECT 
				me.iType 				AS myRole,
				me.idSOCIALAssociation	AS Association,
				a.iType					AS AssociationType,
				o.id					AS id,
				o.sDisplayName			AS sDisplayName,
				them.iType				AS theirRole
			
			FROM tbSOCIALObjectAssociation me

			INNER JOIN tbSOCIALAssociation a
			ON me.idSOCIALAssociation = a.id 

			INNER JOIN tbSOCIALObjectAssociation them
			ON a.id = them.idSOCIALAssociation

			INNER JOIN tbSOCIALObject o 
			ON them.idSOCIALObject = o.id
			AND o.id <> me.idSOCIALObject ';

		$query .= 'WHERE me.idSOCIALObject = '.$this->id.' '.$where.' ';
		$query .= 'ORDER BY AssociationType, myRole, theirRole ';
		
		$query .= '; '; 
		
		$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);

		$associations = array();
		while($row = mysql_fetch_assoc($result)) {
			$associations[] = $row;
		}

		return $associations;
		
	}
	
	
	
}



class SocialProfile extends SocialObject {
	
	function __construct() {
		
		$this->id 			= NULL;
		$this->sDisplayName = NULL;
		$this->sDirectLink 	= NULL;
		$this->iType 		= 1;
		$this->tsCreation 	= NULL;
		
		$this->objectProperties = array();
		$this->objectProperties['sFullName'] 		= NULL;
		$this->objectProperties['sAvatarPath'] 		= NULL;
		$this->objectProperties['sEmail'] 			= NULL;
		$this->objectProperties['sPassword'] 		= NULL;
		$this->objectProperties['dtBirthday'] 		= NULL;
		$this->objectProperties['idADMINCountry'] 	= NULL;
		$this->objectProperties['idADMINState'] 	= NULL;
		$this->objectProperties['idADMINCity'] 		= NULL;
		$this->objectProperties['sPostalCode'] 		= NULL;
		
		$this->objectDetails = array();
	}
	
	
	public function create() {
		
		begin();
		
		//verificando se o email já foi cadastrado
		$queryObject = '
		SELECT ID
		FROM tbSOCIALObjectProperty 
		WHERE idSOCIALProperty = 3 AND sValue = "'.$this->objectProperties['sEmail'].'"';
		$resultObject = mysql_query(utf8_decode($queryObject));		
		
		$row = mysql_fetch_assoc($resultObject);	
		if ( !(is_null($row['ID'])) ) {			
			rollback();
			//	echo json_encode(to_utf8($rows));
			return false;
		}
		
		$queryObject = '
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
							"'.$this->sDisplayName.'", 
							"'.$this->sDirectLink.'", 
							'.$this->iType.', 
							NOW()
						); ';
		
		$resultObject = mysql_query(utf8_decode($queryObject));
		
		if ($resultObject == false) {
			rollback();
		//	echo json_encode(to_utf8($rows));
			return false;
		}
		
		
		$queryLastId  ='SELECT last_insert_id() AS lastId; ';
		
		$resultLastId = mysql_query(utf8_decode($queryLastId));
		
		$row = mysql_fetch_assoc($resultLastId);	
		if ( !(is_null($row['lastId'])) ) {
			$this->id = $row['lastId'];
		}
		
		
		
		
		$queryProperties = '
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
							'.$this->id.', 
							1, 
							"'.$this->objectProperties['sFullName'].'"
						),
						(
							NULL, 
							'.$this->id.', 
							3, 
							"'.$this->objectProperties['sEmail'].'"
						),
						(
							NULL, 
							'.$this->id.', 
							16, 
							"'.$this->objectProperties['sPassword'].'"
						),
						(
							NULL, 
							'.$this->id.', 
							4, 
							"'.$this->objectProperties['dtBirthday'].'"
						),
						(
							NULL, 
							'.$this->id.', 
							5, 
							"'.$this->objectProperties['idADMINCountry'].'"
						),
						(
							NULL, 
							'.$this->id.', 
							6, 
							"'.$this->objectProperties['idADMINState'].'"
						),
						(
							NULL, 
							'.$this->id.', 
							7, 
							"'.$this->objectProperties['idADMINCity'].'"
						),
						(
							NULL, 
							'.$this->id.', 
							8, 
							"'.$this->objectProperties['sPostalCode'].'"
						),
						(
							NULL, 
							'.$this->id.', 
							2, 
							NULL
						); ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
		//	echo json_encode(to_utf8($rows));
			return false;
		}
		
		commit();
		return true;
		
	}
	
	
	public function update() {
		
		begin();
		
		$queryObject = '
					UPDATE tbSOCIALObject 
					SET 
						sDisplayName = "'.$this->sDisplayName.'" 
					WHERE id = '.$this->id.'
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
			SELECT '.$this->id.' AS idSOCIALObject, 
			p.id AS idSOCIALProperty, 
			NULL AS sValue 
			
			FROM tbSOCIALProperty p 
			
			LEFT OUTER JOIN 
			(
			SELECT idSOCIALProperty 
			FROM tbSOCIALObjectProperty 
			WHERE idSocialObject = '.$this->id.' 
			)t
			ON t.idSOCIALProperty = p.id 
			
			WHERE t.idSOCIALProperty IS NULL 
			AND p.iObjectType = 1; ';
		
		$resultDefaultProperties = mysql_query(utf8_decode($queryDefaultProperties));
		
		if ($resultDefaultProperties == false) {
			rollback();
			return false;
		}
		
		
		
		
		// propriedades do objeto:
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['sFullName'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 1 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['sEmail'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 3 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['dtBirthday'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 4 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['idADMINCountry'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 5 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['idADMINState'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 6 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['idADMINCity'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 7 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['sPostalCode'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 8 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		
		
		// -----------------------------------------------------------------------------------------------------------------------------
		// afinidades espirituais:
		
		// desfaz todas as associações do PROFILE à associação AFFINITY
		// iType=18 (AFFINITYFOLLOWER)
		$queryAffinities = '
			DELETE FROM 
				tbSOCIALObjectAssociation 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	iType = 18; ';
			
		$resultAffinities = mysql_query(utf8_decode($queryAffinities));
		
		if ($resultAffinities == false) {
			rollback();
			return false;
		}
		
	//	echo 'spiritualAffinities:<br>';
	//	print_r($this->spiritualAffinities);
	//	echo '<br>';
		
		if(isset($this->spiritualAffinities) && count($this->spiritualAffinities)>0 && $this->spiritualAffinities[0] != '') {
		
			// agora, para cada affinity, cria as ligações com o PROFILE:
			foreach($this->spiritualAffinities as $affinity) {
				
				// busca a associação AFFINITY
				// assoc.iType 	= 11 (AFFINITY)
				$queryAssociation = '
						SELECT 
							assoc.id AS idAffinitiesAssociation
							
						FROM tbSOCIALAssociation assoc
						
						INNER JOIN tbSOCIALObjectAssociation oa 
						ON oa.idSOCIALAssociation = assoc.id 
						
						WHERE 	oa.idSOCIALObject 	= '.$affinity.' 
						AND		assoc.iType 		= 11 
						AND 	oa.iType 			= 3 
	
						LIMIT 0, 1; ';
				
				$resultAssociation = mysql_query(utf8_decode($queryAssociation));
				
				if ($resultAssociation == false) {
					rollback();
					return false;
				}
				
				while($row = mysql_fetch_assoc($resultAssociation)) {
					$idAffinitiesAssociation = $row['idAffinitiesAssociation'];
				}
				
				
				/*
				// desfaz todas as associações do PROFILE à associação AFFINITY
				// iType=18 (AFFINITYFOLLOWER)
				$queryAffinities = '
					DELETE FROM 
						tbSOCIALObjectAssociation 
	
					WHERE 	idSOCIALAssociation = '.$idAffinitiesAssociation.' 
					AND 	idSOCIALObject = '.$this->id.' 
					AND 	iType = 18
					
					LIMIT 1; ';
					
				$resultAffinities = mysql_query(utf8_decode($queryAffinities));
				
				if ($resultAffinities == false) {
					rollback();
					return false;
				}
				*/
				
				
				// então, recria a ligação do PROFILE à associação AFFINITY
				// iType=18 (AFFINITYFOLLOWER)
				$queryAffinities = '
					INSERT INTO 
						tbSOCIALObjectAssociation
							(
								iType,
								idSOCIALObject,
								idSOCIALAssociation,
								tsCreation
							)
						VALUES (
								18,
								'.$this->id.',
								'.$idAffinitiesAssociation.',
								NOW()
							); ';
				
				$resultAffinities = mysql_query(utf8_decode($queryAffinities));
				
				if ($resultAffinities == false) {
					rollback();
					return false;
				}
			
			}
			
			}
		
		commit();
		return true;
		
	}
	
	
	public function load() {
		
		if (is_null($this->id)) {
			return false;
		}
		
		// --------------------------------------------------
		// dados básicos do objeto
		
		$query = 'SELECT 
					ob.id,
					ob.sDisplayName,
					ob.sDirectLink,
					ob.iType,
					ob.tsCreation
				FROM 
					tbSOCIALObject ob
				WHERE ob.id = '.$this->id.'
				AND   ob.iType = '.$this->iType.'; ';
		
		$result = mysql_query(utf8_decode($query));
		
		if ($result == false) {
			return false;
		}
		
		while($row = mysql_fetch_assoc($result)) {
			
			$this->sDisplayName = $row['sDisplayName'];
			$this->sDirectLink 	= $row['sDirectLink'];
			$this->iType 		= $row['iType'];
			$this->tsCreation 	= $row['tsCreation'];
		}
		
		
		// --------------------------------------------------
		// propriedades do objeto
		
		$queryProperties = 'SELECT 
			pr.sKey,
			op.sValue
			
			FROM tbSOCIALProperty pr 
			
			LEFT OUTER JOIN tbSOCIALObjectProperty op 
			ON pr.id = op.idSOCIALProperty 
			AND op.idSOCIALObject = '.$this->id.' 
			
			LEFT OUTER JOIN tbSOCIALObject ob
			ON ob.id = '.$this->id.' 
			
			WHERE pr.iObjectType = ob.iType 
			AND   pr.sKey <> "sPassword" 
			
			ORDER BY pr.sKey, pr.id ;';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			return false;
		}
		
		while($row = mysql_fetch_assoc($resultProperties)) {
			$this->objectProperties[$row['sKey']] = $row['sValue'];
		}
		
		
		// --------------------------------------------------
		// detalhes do objeto

		$queryDetails = 'SELECT 
			dt.sKey,
			dt.sValue
		
		FROM tbSOCIALObjectDetail dt 
		
		WHERE dt.idSOCIALObject = '.$this->id.'
		
		ORDER BY dt.sKey, dt.id; ';
		
		$resultDetails = mysql_query(utf8_decode($queryDetails));
		
		if ($resultDetails == false) {
			return false;
		}
		
		while($row = mysql_fetch_assoc($resultDetails)) {
			$this->objectDetails[$row['sKey']] = $row['sValue'];
		}
		
		
		
		// após o carregamento de todas as propriedades, o objeto está pronto:
		return true;
	}
	
	/*
	public function getFriends() {
		
		$this->getAssociations('FRIEND', 'FRIENDSHIP', 'FRIEND');
	}
	
	
	public function getPendingFriendshipRequests() {
		
		$this->getAssociations('INVITED_FRIEND','FRIENDSHIP','');
	}
	*/
	
}



class SocialPage extends SocialObject {
	
	function __construct() {
		
		$this->id 			= NULL;
		$this->sDisplayName = NULL;
		$this->sDirectLink 	= NULL;
		$this->iType 		= 2;
		$this->tsCreation 	= NULL;
		
		$this->objectProperties = array();
		$this->objectProperties['sFullName'] 		= NULL;
		$this->objectProperties['sDescription'] 	= NULL;
		$this->objectProperties['iPageType'] 		= NULL;
		$this->objectProperties['sSite'] 			= NULL;
		$this->objectProperties['sAddress'] 		= NULL;
		$this->objectProperties['sComplement'] 		= NULL;
		$this->objectProperties['sNeighborhood']	= NULL;
		$this->objectProperties['idADMINCountry'] 	= NULL;
		$this->objectProperties['idADMINState'] 	= NULL;
		$this->objectProperties['idADMINCity'] 		= NULL;
		$this->objectProperties['sPostalCode'] 		= NULL;
		
		$this->objectDetails = array();
	}
	
	public function create() {
		// TO-DO
	}
	
	public function update() {
		
		begin();
		
		$queryObject = '
					UPDATE tbSOCIALObject 
					SET 
						sDisplayName = "'.$this->sDisplayName.'" 
					WHERE id = '.$this->id.'
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
			SELECT '.$this->id.' AS idSOCIALObject, 
			p.id AS idSOCIALProperty, 
			NULL AS sValue 
			
			FROM tbSOCIALProperty p 
			
			LEFT OUTER JOIN 
			(
			SELECT idSOCIALProperty 
			FROM tbSOCIALObjectProperty 
			WHERE idSocialObject = '.$this->id.' 
			)t
			ON t.idSOCIALProperty = p.id 
			
			WHERE t.idSOCIALProperty IS NULL 
			AND p.iObjectType = 2; ';
		
		$resultDefaultProperties = mysql_query(utf8_decode($queryDefaultProperties));
		
		if ($resultDefaultProperties == false) {
			rollback();
			return false;
		}
		
		
		
		
		// propriedades do objeto:
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['sFullName'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 9 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['sDescription'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 10 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['sAvatarPath'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 11 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['idADMINCountry'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 12 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['idADMINState'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 13 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['idADMINCity'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 14 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['sPostalCode'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 15 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['iPageType'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 18 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['sSite'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 19 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['sAddress'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 20 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['sComplement'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 21 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		
		$queryProperties = '
			UPDATE tbSOCIALObjectProperty 
			SET 	sValue = "'.$this->objectProperties['sNeighborhood'].'" 
			
			WHERE 	idSOCIALObject = '.$this->id.' 
			AND 	idSOCIALProperty = 22 
			LIMIT 1; ';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			rollback();
			return false;
		}
		
		commit();
		return true;
		
	}
	
	public function load() {
		
		if (is_null($this->id)) {
			return false;
		}
		
		// --------------------------------------------------
		// dados básicos do objeto
		
		$query = 'SELECT 
					ob.id,
					ob.sDisplayName,
					ob.sDirectLink,
					ob.iType,
					ob.tsCreation
				FROM 
					tbSOCIALObject ob
				WHERE ob.id = '.$this->id.'
				AND   ob.iType = '.$this->iType.'; ';
		
		$result = mysql_query(utf8_decode($query));
		
		if ($result == false) {
			return false;
		}
		
		while($row = mysql_fetch_assoc($result)) {
			
			$this->sDisplayName = $row['sDisplayName'];
			$this->sDirectLink 	= $row['sDirectLink'];
			$this->iType 		= $row['iType'];
			$this->tsCreation 	= $row['tsCreation'];
		}
		
		
		// --------------------------------------------------
		// propriedades do objeto
		
		$queryProperties = 'SELECT 
			pr.sKey,
			op.sValue
			
			FROM tbSOCIALProperty pr 
			
			LEFT OUTER JOIN tbSOCIALObjectProperty op 
			ON pr.id = op.idSOCIALProperty 
			AND op.idSOCIALObject = '.$this->id.' 
			
			LEFT OUTER JOIN tbSOCIALObject ob
			ON ob.id = '.$this->id.' 
			
			WHERE pr.iObjectType = ob.iType 
			AND   pr.sKey <> "sPassword" 
			
			ORDER BY pr.sKey, pr.id ;';
		
		$resultProperties = mysql_query(utf8_decode($queryProperties));
		
		if ($resultProperties == false) {
			return false;
		}
		
		while($row = mysql_fetch_assoc($resultProperties)) {
			$this->objectProperties[$row['sKey']] = $row['sValue'];
		}
		
		
		// --------------------------------------------------
		// detalhes do objeto

		$queryDetails = 'SELECT 
			dt.sKey,
			dt.sValue
		
		FROM tbSOCIALObjectDetail dt 
		
		WHERE dt.idSOCIALObject = '.$this->id.'
		
		ORDER BY dt.sKey, dt.id; ';
		
		$resultDetails = mysql_query(utf8_decode($queryDetails));
		
		if ($resultDetails == false) {
			return false;
		}
		
		while($row = mysql_fetch_assoc($resultDetails)) {
			$this->objectDetails[$row['sKey']] = $row['sValue'];
		}
		
		// após o carregamento de todas as propriedades, o objeto está pronto:
		return true;
	}
	
}


?>