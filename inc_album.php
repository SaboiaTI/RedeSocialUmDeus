		<!-- conteudo album -->
		
		<?php 
			/*
			// dados do PROFILE DONO do ALBUM
			$assoc = getAssociations($socialObject['id'],'OBJECT','ALBUM','OWNER');
			$assoc = $assoc[0];
			
			$profileObject 					= getObject($assoc['id']);
			$profileObject['properties'] 	= getProperties($assoc['id']);
			*/
		?>
		
		<div id="content">
		
			<aside id="profile">
			
				<!-- foto do perfil -->
				<div class="photo-profile">
					<img src="<?= $profileObject['properties']['sAvatarPath'] ? $profileObject['properties']['sAvatarPath'] : "img/avatar-default.jpg"; ?>" alt="<?= $profileObject['sDisplayName']; ?>" border="0" width="150">
					
					<? if($userObject['id'] == $profileObject['id']) { ?>
					<div id="edit-avatar">alterar imagem
					
						<form id="avatar-form" method="post" enctype="multipart/form-data" action="lib/socialAPI.php?action=uploadFile" target="iframe" style="margin:0;padding:0;position:absolute;top:0;left:0;">
							<input type="hidden" name="MAX_FILE_SIZE" value="5242880">
							<span id="uploader"><input type="file" name="picture" onchange="javascript:document.getElementById('avatar-form').submit();return uploadFile();" style="width:148px;border:1px solid #333;cursor:default;opacity:0;filter:alpha(opacity=0);"></span>
							<img id="loading-image" src="img/loading.gif" style="vertical-align:top;">
							<iframe name="iframe" width="400" height="100" style="display:none;"></iframe>
						</form>
					
					</div>
					<? } ?>
				</div>
				
				<? if ($userObject['id'] == $profileObject['id']) { ?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$userObject['id'];?>&prob=<?=$userObject['id'];?>&feed=info" target="_self">editar meu perfil</a>
				<? } ?>
				
				<? include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_friends.php"); ?>
				
			</aside>
			
			<section id="main-flow">
			
				<!-- resumo do usuário -->
				<div id="profile-summary">
					<h1><?= $profileObject['sDisplayName']; ?></h1>
					<?php 
						$idADMINCity = getProperties($profileObject['id'],'idADMINCity');
						$idADMINCity = $idADMINCity['idADMINCity'];
						echo $idADMINCity;
					?>, <?php 
						$idADMINState = getProperties($profileObject['id'],'idADMINState');
						$idADMINState = $idADMINState['idADMINState'];
						echo $idADMINState;
					?><br>Afinidades espirituais: <a style="text-decoration:line-through;">Religião 1</a>
				</div>
				
				<!-- opções de visualização -->
				<nav id="post-navigation">
					<ul>
						<li class=""><a href="index.php?id=<?=uniqid();?>&sob=<?=$profileObject['id']?>&prob=<?=$profileObject['id']?>&feed=feed">feeds</a></li>
						<li class=""><a href="index.php?id=<?=uniqid();?>&sob=<?=$profileObject['id']?>&prob=<?=$profileObject['id']?>&feed=info">informações</a></li>
						<li class="active"><a href="index.php?id=<?=uniqid();?>&sob=<?=$profileObject['id']?>&prob=<?=$profileObject['id']?>&feed=photo-album">fotos</a></li>
						<li class=""><a href="index.php?id=<?=uniqid();?>&sob=<?=$profileObject['id']?>&prob=<?=$profileObject['id']?>&feed=page">páginas</a></li>
						<li class=""><a href="index.php?id=<?=uniqid();?>&sob=<?=$profileObject['id']?>&prob=<?=$profileObject['id']?>&feed=group">grupos</a></li>
					</ul>
				</nav>
				
				<?php // álbuns ?>
				
				<button type="button" class="basic-button" onclick="javascript:window.location='index.php?id=<?=uniqid();?>&sob=<?=$profileObject['id'];?>&prob=<?=$profileObject['id'];?>&feed=photo-album'">álbuns de <?=$profileObject['sDisplayName'];?></button>
				
				<h2><?=$socialObject['sDisplayName'];?></h2>
				
				<? if (isset($userObject)) { ?>
					<!-- caixa de compartilhamento -->
					<div class="post-box" style="display:none;">

						<form id="share-photo-form" method="post" enctype="multipart/form-data" action="lib/socialAPI.php?action=PhotoCreate" target="iframe-post">
							<input type="hidden" name="MAX_FILE_SIZE" value="5242880">
							<input type="hidden" name="idReferredObject" value="<?=$socialObject['id'];?>">
							<textarea name="sContent" title="escreva algo sobre sua imagem" required></textarea>
							<input type="submit" class="share-button" value="compartilhar" onclick="javascript:$('form#share-photo-form img#loading-image').show();">
							
							<label>Selecione o arquivo da imagem em seu computador:</label>
							<input type="file" name="picture" onchange="" style="
										border-top:1px solid #d1d1d1;
										border-left:1px solid #d1d1d1;
										border-bottom:1px solid #e1e1e1;
										border-right:1px solid #e1e1e1;
										width:360px;
										-webkit-box-sizing:border-box;
										   -moz-box-sizing:border-box;
											 -o-box-sizing:border-box;
												box-sizing:border-box;
										margin:0;
										padding:0;
							">
							<img id="loading-image" src="img/loading.gif" style="vertical-align:top;display:none;">
							<iframe name="iframe-post" width="100" height="50" style="display:none;"></iframe>
						</form>
						
					</div>
				<? } ?>
				
				
				
				
				<div>
				<? if (isset($userObject['id']) && $userObject['id'] == $profileObject['id']) { ?>
				<button id="create-photo" type="button" class="basic-button" data-user-id="<?=$userObject['id'];?>">adicionar foto</button>
				<? } ?>
				</div>
				
				<!-- begin: photos -->
				<article>
					
					<?php
						
						$listPhotos = getAssociations($socialObject['id'],'OBJECT','ALBUM',array('REFERRED','PHOTOCAPA'));
						
						foreach($listPhotos as $photo) {
							
							$sDataPhoto = getProperties($photo['id'],'sDataPhoto');
							
							if ($sDataPhoto) {
								
								$sDataPhoto = $sDataPhoto['sDataPhoto'];
								$values 	= json_decode($sDataPhoto,true);
								$thumb 		= $values['thumb']	? $values['thumb'] 	: '';
								$link 		= $values['link']	? $values['link'] 	: '';
					?>
							<div class="thumb-img" style="
								display:inline-block;
								margin:5px 10px 0 0;
								padding:10px;
								width:120px;
								
								background-color:#F4F4F4;
								border:1px solid #EEE;
								
							"><a href="index.php?id=<?=uniqid();?>&sob=<?=$photo['id'];?>&prob=<?=$profileObject['id'];?>" title="veja a imagem ampliada" target="_self" style="margin:0;padding:0;"><img src="<?=$thumb;?>" style="width:120px;margin:0;padding:0;"></a></div>

					<?
							}
						}
					?>
					
				</article>
				<!-- end: photos -->
				
				
			
			</section>
			
			<? include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_sidebar.php"); ?>
			
		</div>
		
	</div>
	
	<div class="footer">
		<footer>
			<nav>
				<ul>
					<li><a href="termos-de-uso.php">termos de uso</a></li>
					<li><a href="privacidade.php">privacidade</a></li>
					<li><a href="ajuda.php">ajuda</a></li>
				</ul>
			</nav>
		</footer>
	</div>
	
	
<!-- <script src="js/libs/jquery-1.6.2.min.js"></script> -->
<!-- <script src="js/script.js"></script> -->

<!-- 
<script>
	var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']]; // Change UA-XXXXX-X to be your site's ID
	(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
	g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
	s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
-->

<!--[if lt IE 7 ]>
	<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>
	<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
<![endif]-->

</body>
</html>
