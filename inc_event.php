		<!-- conteudo event -->
		
		<?php 
			
			// dados da PAGE que criou o evento
			$assoc = getAssociations($socialObject['id'],'OBJECT','EVENT','OWNER');
			$assoc = $assoc[0];
			
			$pageObject 					= getObject($assoc['id']);
			$pageObject['properties'] 		= getProperties($assoc['id']);
			
		?>
		
		<div id="content">

			<aside id="profile">
			
				<!-- foto da pagina -->
				<div class="photo-profile">
					<img src="<?= $pageObject['properties']['sAvatarPath'] ? $pageObject['properties']['sAvatarPath'] : "img/page-default.jpg"; ?>" alt="<?= $socialObject['sDisplayName']; ?>" border="0" width="150" height="150">
					
					
					
					<? if($userObject['id'] == $profileObject['id']) { ?>
					<div id="edit-avatar">alterar imagem
					
						<form id="avatar-form" method="post" enctype="multipart/form-data" action="lib/socialAPI.php?action=uploadFile" target="iframe" style="margin:0;padding:0;position:absolute;top:0;left:0;">
							<input type="hidden" name="MAX_FILE_SIZE" value="2097152">
							<span id="uploader"><input type="file" name="picture" onchange="javascript:document.getElementById('avatar-form').submit();return uploadFile();" style="width:148px;border:1px solid #333;cursor:default;opacity:0;filter:alpha(opacity=0);"></span>
							<img id="loading-image" src="img/loading.gif" style="vertical-align:top;">
							<iframe name="iframe" width="400" height="100" style="display:none;"></iframe>
						</form>
					
					</div>
					<? } ?>
					
				</div>
				
				<? if($userObject['id'] == $profileObject['id']) { ?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$pageObject['id'];?>&prob=<?=$profileObject['id'];?>&feed=info" target="_self">editar página da entidade</a>
				<? } ?>
				
				
				<!-- seguidores -->
				<div id="friends">
					
					<?php
						$iTotal = getCountAssociations($pageObject['id'],'OBJECT','PAGE','FOLLOWER');
					?>
					
					<h2>Administradores</h2>
					
					<?php 
						$list = getAssociations($pageObject['id'],'OBJECT','PAGE','OWNER','',0,5);
						for ($i=0;$i<count($list);$i++) {
					?>
						<div class="user">
							<div>
								<div class="photo">
									<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$list[$i]['id'];?>" target="_self">
									<?
										$sAvatarPath = getProperties($list[$i]['id'],'sAvatarPath');
										$sAvatarPath = $sAvatarPath['sAvatarPath'];
									?>
									<img src="<?= $sAvatarPath ? $sAvatarPath : "img/avatar-default.jpg"; ?>" alt="" border="0" width="50" height="50">
									</a>
								</div>
								<div class="name-location">
									<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$list[$i]['id'];?>"><strong><?=$list[$i]['sDisplayName']; ?></strong></a><br>
									<span style="color:#999">
									<?php 
										$idADMINCity = getProperties($list[$i]['id'],'idADMINCity');
										$idADMINCity = $idADMINCity['idADMINCity'];
										
										$idADMINState = getProperties($list[$i]['id'],'idADMINState');
										$idADMINState = $idADMINState['idADMINState'];
										
										echo ($idADMINCity != '' ? $idADMINCity : '' );
										
										if ($idADMINCity != '' && $idADMINState != '') {
											echo ', ';
										}
										
										echo ($idADMINState != '' ? $idADMINState : '');
									?>
									</span>
								</div>
							</div>
						</div>
						
					<? } ?>
					
					<h2>Seguidores (<?=$iTotal;?>)<br><span style="font-size:12px"><a href="search-results.php?id=<?=uniqid();?>
					&sob=<?=$pageObject['id'];?>
					&prob=<?=$profileObject['id'];?>
					&feed=AllFollowers">ver todos</a></span></h2>
					
					<?php 
						$list = getAssociations($pageObject['id'],'OBJECT','PAGE','FOLLOWER','',0,5);
						for ($i=0;$i<count($list);$i++) {
					?>
						<div class="user">
							<div>
								<div class="photo">
									<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$list[$i]['id'];?>" target="_self">
									<?
										$sAvatarPath = getProperties($list[$i]['id'],'sAvatarPath');
										$sAvatarPath = $sAvatarPath['sAvatarPath'];
									?>
									<img src="<?= $sAvatarPath ? $sAvatarPath : "img/avatar-default.jpg"; ?>" alt="" border="0" width="50" height="50">
									</a>
								</div>
								<div class="name-location">
									<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$list[$i]['id'];?>"><strong><?=$list[$i]['sDisplayName']; ?></strong></a><br>
									<span style="color:#999">
									<?php 
										$idADMINCity = getProperties($list[$i]['id'],'idADMINCity');
										$idADMINCity = $idADMINCity['idADMINCity'];
										
										$idADMINState = getProperties($list[$i]['id'],'idADMINState');
										$idADMINState = $idADMINState['idADMINState'];
										
										echo ($idADMINCity != '' ? $idADMINCity : '' );
										
										if ($idADMINCity != '' && $idADMINState != '') {
											echo ', ';
										}
										
										echo ($idADMINState != '' ? $idADMINState : '');
									?>
									</span>
								</div>
							</div>
						</div>
						
					<? } ?>
					
				</div>
				
			</aside>

			<section id="main-flow">
			
				<!-- resumo da página -->
				<div id="profile-summary">
					<h1><?= $pageObject['sDisplayName']; ?></h1>
					<?php 
						$idADMINCity = getProperties($pageObject['id'],'idADMINCity');
						$idADMINCity = $idADMINCity['idADMINCity'];
						
						$idADMINState = getProperties($pageObject['id'],'idADMINState');
						$idADMINState = $idADMINState['idADMINState'];
						
						echo $idADMINCity;
						
						if ($idADMINCity != '' && $idADMINState != '') {
							echo ', ';
						}
						
						echo $idADMINState;
						
						$sDescription = getProperties($pageObject['id'], 'sDescription');
						$sDescription = $sDescription['sDescription'];
						echo '<p>'.$sDescription.'</p>';
					?>
				</div>
				
				<!-- opções de visualização -->
				<nav id="post-navigation">
					<ul>
						<li class=""><a href="index.php?id=<?=uniqid();?>&sob=<?=$pageObject['id']?>&prob=<?=$profileObject['id']?>&feed=feed">mural</a></li>
						<li class=""><a href="index.php?id=<?=uniqid();?>&sob=<?=$pageObject['id']?>&prob=<?=$profileObject['id']?>&feed=info">informações</a></li>
						<li class="active"><a href="index.php?id=<?=uniqid();?>&sob=<?=$pageObject['id']?>&prob=<?=$profileObject['id']?>&feed=event">eventos</a></li>
						<li class=""><a href="index.php?id=<?=uniqid();?>&sob=<?=$pageObject['id']?>&prob=<?=$profileObject['id']?>&feed=follower">seguidores</a></li>
						<? if (false) { ?>
						<li class="<?=$feed=='participant'	? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&sob=<?=$pageObject['id']?>&prob=<?=$profileObject['id']?>&feed=participant">participantes</a></li>
						<? } ?>
					</ul>
				</nav>
				
				
				<div class="comment-box" style="display:none;">
					<form id="comment-form" action="javascript:commentCreate();">
						<textarea required></textarea>
						<input type="submit" class="shared-button" value="comentar">
					</form>
				</div>
				
				
				<!-- begin: event -->
				<?php
					
					$itemCreation = showDate($profileObject['tsCreation'],'timestamp');
					
					$sContent = getDetails($socialObject['id'],'sContent');
					$sContent = $sContent['sContent'];
					
				?>
				<article class="post root-post" data-key="<?=$socialObject['id'];?>">
					
					<div class="post-photo">
						<a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id'];?>&prob=<?=$profileObject['id'];?>" target="_self">
						<img src="<?= $socialObject['properties']['sAvatarPath'] ? $socialObject['properties']['sAvatarPath'] : 'img/page-default.jpg'; ?>" alt="" border="0" width="50" height="50">
						</a>
					</div>
					
					<div class="post-info">
						
						<h3><?=$socialObject['properties']['sDisplayName'];?></h3>
						
						<p><span class="label">horário:</span> <span class="field"><?=showDate($socialObject['properties']['tsBegin'],'datetime');?></span> até <span class="field"><?=showDate($socialObject['properties']['tsEnd'],'datetime');?></span><br>
						<span class="label">local:</span> <span class="field"><?=$socialObject['properties']['sAddress'];?> <?=$socialObject['properties']['sComplement'];?> <?=$socialObject['properties']['sNeighborhood'];?><br>
						<?=$socialObject['properties']['idADMINCountry'];?> <?=$socialObject['properties']['idADMINState'];?>, <?=$socialObject['properties']['idADMINCity'];?><br>
						<?=$socialObject['properties']['sPostalCode'];?></span><br>
						<?=identifyLink($socialObject['properties']['sSite']);?><br><br>
						<?=identifyLink($sContent);?></p>
						
						<p>criado por <a href="index.php?id=<?=uniqid();?>&sob=<?=$pageObject['id'];?>&prob=<?=$profileObject['id'];?>"><strong><?=$pageObject['sDisplayName'];?></strong></a> 
						<em style="font-size:10px;color:#999;"> em <?=$itemCreation;?></em></p>
					</div>
					
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
				<!-- end: event -->
				
				
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
