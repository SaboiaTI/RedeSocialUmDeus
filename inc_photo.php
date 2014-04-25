		<!-- conteudo photo -->
		
		<?php 

			// dados do ALBUM onde está REFERENCIADA da FOTO
			$assoc = getAssociations($socialObject['id'],array('REFERRED','PHOTOCAPA'),'ALBUM','OBJECT');
			$assoc = $assoc[0];

			$albumObject 					= getObject($assoc['id']);
			$albumObject['properties'] 		= getProperties($assoc['id']);
			
		?>
		
		<div id="content">

			<aside id="profile">
			
				<!-- foto do perfil -->
				<div class="photo-profile">
					<img src="<?= $profileObject['properties']['sAvatarPath'] ? $profileObject['properties']['sAvatarPath'] : "img/avatar-default.jpg"; ?>" alt="<?= $profileObject['sDisplayName']; ?>" border="0" width="150">
					
					<? if ($userObject['id'] == $profileObject['id']) { ?>
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
				
				<? if($userObject['id'] == $profileObject['id']) { ?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$profileObject['id'];?>&prob=<?=$profileObject['id'];?>&feed=info" target="_self">editar meu perfil</a>
				<? } ?>
				
				<? include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_friends.php"); ?>
				
			</aside>

			<section id="main-flow">
			
				<!-- resumo do usuário -->
				<div id="profile-summary">
					<h1><?= $profileObject['sDisplayName'];?></h1>
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
				
				
				
				
				<div class="comment-box" style="display:none;">
					<form id="comment-form" action="javascript:commentCreate();">
						<textarea required></textarea>
						<input type="submit" class="shared-button" value="comentar">
					</form>
				</div>
				
				
				
				
				<?php // photo ?>
				
				<button type="button" class="basic-button" onclick="javascript:window.location='index.php?id=<?=uniqid();?>&sob=<?=$profileObject['id'];?>&prob=<?=$profileObject['id'];?>&feed=photo-album'">álbuns de <?=$profileObject['sDisplayName'];?></button>
				
				<button type="button" class="basic-button" onclick="javascript:window.location='index.php?id=<?=uniqid();?>&sob=<?=$albumObject['id'];?>&prob=<?=$profileObject['id'];?>&feed=photo-album'">voltar para o álbum</button>
				
				<div>
				<? if ($userObject['id'] == $profileObject['id'] && 1==2) { ?>
				<br><button id="create-album" type="button" class="basic-button" data-user-id="<?=$socialObject['id'];?>">excluir foto</button>
				<? } ?>
				</div>
				
				<!-- begin: photo -->
				<article class="post root-post" data-key="<?=$socialObject['id'];?>" style="padding:0;">
					
					<?php
						
						$sDataPhoto = getProperties($socialObject['id'],'sDataPhoto');
						
						if ($sDataPhoto) {
							
							$sDataPhoto = $sDataPhoto['sDataPhoto'];
							$values 	= json_decode($sDataPhoto,true);
							$thumb 		= $values['thumb']	? $values['thumb'] 	: '';
							$link 		= $values['link']	? $values['link'] 	: '';
							
							$sContent = getDetails($socialObject['id'],'sContent');
							$sContent = $sContent['sContent'];
							
							echo '<img src="'.$link.'" style="width:515px">';
							
							
							if ($userObject['id'] == $profileObject['id']) {
							echo '<div sytle="display:block"><a href="#" id="photocapa" data-key=' . $socialObject['id'] . '>tornar capa do álbum</a></div>';
							}
							
							echo '<div class="desc" style="margin:5px 0 0 0;">';
							echo 	'<a href="index.php?id='.uniqid().'&sob='.$profileObject['id'].'&prob='.$profileObject['id'].'"><strong>'.$profileObject['sDisplayName'].'</strong></a> ';
							echo 	'<em style="font-size:10px;color:#999;">postou em '.$socialObject['tsCreation'].'</em><br>';
							echo 	$sContent;
							echo '</div>';

						}

					?>
					
					<?php
					// ----------------------------------------------------------------------
					// barra de interação (gostar, comentar, compartilhar)
					// ----------------------------------------------------------------------
					
					if (isset($userObject)) {
					
						echo '<div class="interaction-bar">';
						
						$like = count(likeStatus($userObject['id'],$socialObject['id'])) > 0 ? '<a href="#" class="dislike"><strong>você gostou</strong> (desfazer)</a>' : '<a href="#" class="like">gostei</a>' ;
						
						echo $like;
						
						echo ' | <a href="#" class="create-comment">comentar</a> | <a href="#" style="text-decoration:line-through;">compartilhar</a> | <a href="#" style="text-decoration:line-through;">denunciar</a>';
						
						$iTotal   = getCountAssociations($socialObject['id'],'OBJECT','','LIKER');
						if ($iTotal > 0) {
							$likeText = $iTotal > 1 ? $iTotal.' pessoas gostaram' : $iTotal.' pessoa gostou';
							echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
						}
						
						echo '</div>';
					}
					?>
					
					<?php
						$listComment = getAssociations($socialObject['id'],'REFERRED','COMMENT','OBJECT');
						for ($j=0;$j<count($listComment);$j++) {
					?>
					
						<article class="post comment" data-key="<?=$listComment[$j]['id'];?>">
							
							<div class="post-photo">
								<?
									$owner			  = getAssociations($listComment[$j]['id'], 'OBJECT', 'COMMENT', 'OWNER');
									$ownerDisplayName = $owner[0]['sDisplayName'];
									$ownerId		  = $owner[0]['id'];
									
									$owner			  = getObject($ownerId);
									$ownerCreation	  = $owner['tsCreation'];
									
									$sAvatarPath = getProperties($ownerId,'sAvatarPath');
									$sAvatarPath = $sAvatarPath['sAvatarPath'];
								?>
								<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>" target="_self">
								<img src="<?= $sAvatarPath ? $sAvatarPath : 'img/avatar-default.jpg'; ?>" alt="" border="0" width="30" height="30">
								</a>
							</div>
							
							<div class="post-info">
								<?
									$sContent = getDetails($listComment[$j]['id'],'sContent');
									$sContent = $sContent['sContent'];
								?>
								<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>"><strong><?=$ownerDisplayName; ?></strong></a>&nbsp;
								<em style="font-size:10px;color:#999;">comentou em <a href="index.php?id=<?=uniqid();?>&sob=<?=$listComment[$j]['id'];?>&prob=<?=$ownerId;?>" title="permalink" style="color:#999;"><?=$ownerCreation;?></a></em><br>
								<?=$sContent ? $sContent : ''; ?>
								<br>
								
								<?php
								// ----------------------------------------------------------------------
								// barra de interação (gostar, comentar, compartilhar)
								// ----------------------------------------------------------------------
								
								if (isset($userObject)) {
								
									echo '<div class="interaction-bar">';
									
									$like = count(likeStatus($userObject['id'],$listComment[$j]['id'])) > 0 ? '<a href="#" class="dislike"><strong>você gostou</strong> (desfazer)</a>' : '<a href="#" class="like">gostei</a>' ;
									
									echo $like;
									
									echo ' | <a href="#" class="create-comment">comentar</a> | <a href="#" style="text-decoration:line-through;">compartilhar</a> | <a href="#" style="text-decoration:line-through;">denunciar</a>';
									
									$iTotal   = getCountAssociations($listComment[$j]['id'],'OBJECT','','LIKER');
									if ($iTotal > 0) {
										$likeText = $iTotal > 1 ? $iTotal.' pessoas gostaram' : $iTotal.' pessoa gostou';
										echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
									}
									
									echo '</div>';
								}
								?>
								
							</div>
							
						</article>
					
					<? } ?>
					
					
					
					
				</article>
				<!-- end: photo -->
				
				
			
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
