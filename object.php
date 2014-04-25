<?php 

if (isset($socialObject) && $socialObject['iType']) {
	
	switch ($socialObject['iType']) {
		
		
		// PROFILE (tando do usuario logado como de outro usuбrio)
		case 1 :
			include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_profile.php");
		break;
		
		// PБGINA
		case 2 :
			include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_page.php");
		break;
		
		// POST
		case 3 :
			include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_post.php");
		break;
		
		// GROUP
		case 5 :
			include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_group.php");
		break;
		
		// EVENT
		case 7 :
			include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_event.php");
		break;
		
		// БLBUM
		case 8 :
			include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_album.php");
		break;
		
		// PHOTO
		case 9 :
			include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_photo.php");
		break;
		
		default :
			echo '<br>Desculpe, nгo foi possнvel acessar a informaзгo solicitada.';
		break;
	}
	
} else {
	
	//acesso ao timeline
	include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_wall.php");
	
}

?>