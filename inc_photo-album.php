<!-- begin: photo-album -->
<? if ($feed == 'photo-album') { ?>
	
	<h2>Álbuns de <?=$socialObject['sDisplayName'];?></h2>
	
	<? if ($userObject['id'] == $profileObject['id']) { ?>
		<!-- caixa de compartilhamento -->
		<div class="post-box" style="display:none;">

		<form id="create-album-form" action="javascript:albumCreate(<?=$profileObject['id'];?>);">
			<input type="text" class="input" id="sDisplayName" placeholder="nome do álbum" required style="width:350px;height:22px;margin-top:0;">
			<input type="submit" class="basic-button" value="criar álbum">
		</form>
			
		</div>
	<? } ?>
	
	
	<div>
	<? if ($userObject['id'] == $profileObject['id']) { ?>
	<button id="create-album" type="button" class="basic-button" data-user-id="<?=$profileObject['id'];?>">criar novo álbum</button>
	<? } ?>
	</div>
	
	
	
	<? if (false) { ?>
	<div class="comment-box" style="display:none;">
		<form id="comment-form" action="javascript:commentCreate();">
			<textarea required></textarea>
			<input type="submit" class="shared-button" value="comentar">
		</form>
	</div>
	<? } ?>
	
	
	
	<!-- begin: albuns -->
	<article>
	<?
		// recupera do banco de dados todos os álbuns deste usuário:
		$list = getAssociations($profileObject['id'],'OWNER','ALBUM','OBJECT');
	?>
	
	<? 
	foreach($list as $item) {
	?>
		<div class="photo-album" data-key="<?=$item['id']; ?>" style="
			display:inline-block;
			width:140px;
			margin:0 20px 15px 0;
			padding:0;
		">
	<? 
			$listPhotos = getAssociations($item['id'],'OBJECT','ALBUM','PHOTOCAPA');
			$iTotal 	= getCountAssociations($item['id'],'OBJECT','ALBUM',array('REFERRED','PHOTOCAPA'));
			$thumb 		= '/img/photo-default.jpg';
			
			foreach($listPhotos as $photo) {
				
				$sDataPhoto = getProperties($photo['id'],'sDataPhoto');
				
				if ($sDataPhoto) {
					
					$sDataPhoto = $sDataPhoto['sDataPhoto'];
					$values 	= json_decode($sDataPhoto,true);
					$thumb 		= $values['thumb']	? $values['thumb'] 	: '';
				//	$link 		= $values['link']	? $values['link'] 	: '';
				}
				
				// mostra apenas a primeira foto!
				break;
			}
	?>
			<div class="thumb-img" style="
				display:inline-block;
				margin:5px 10px 0 0;
				padding:10px;
				width:120px;
				background-color:#F4F4F4;
				border:1px solid #EEE;
			"><a href="index.php?id=<?=uniqid();?>&sob=<?=$item['id'];?>&prob=<?=$profileObject['id'];?>" title="veja o álbum <?=$item['sDisplayName'];?>" target="_self" style="margin:0;padding:0;"><img src="<?=$thumb;?>" style="width:120px;margin:0;padding:0;"></a><p><a href="index.php?id=<?=uniqid();?>&sob=<?=$item['id'];?>&prob=<?=$profileObject['id'];?>" target="_self"><?=$item['sDisplayName'];?> (<?=$iTotal;?>)</a></p></div>
		
		</div>
		
	<? } ?>
	</article>
	<!-- end: albuns -->
	
<? } ?>
<!-- end: photo-album -->