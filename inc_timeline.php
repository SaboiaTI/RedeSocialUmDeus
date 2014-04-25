<!-- begin wallposts -->
<?php 
	if ($feed == 'feed') {
?>
	
	<?php
	// ----------------------------------------------------------------------
	// caixa de compartilhamento
	if (
		(isset($userObject) 	&& !isset($socialObject) && !isset($profileObject)) || 
		(isset($socialObject) 	&& $socialObject['iType'] == 2 && $userObject['id'] == $profileObject['id']) || 
		(isset($socialObject) 	&& $socialObject['iType'] == 1)
		) {
	?>
	
	<div class="post-box">
		
		<div class="post-options">
			<p>Compartilhe seus impulsos de espiritualidade:<br>
			<a id="share-text" class="share-link active" href="#">Compartilhar impulso</a> | <a id="share-photo" class="share-link" href="#">Compartilhar foto</a></p>
		</div>
		
		<form id="share-text-form" action="javascript:postCreate(<?= isset($socialObject['id']) ? $socialObject['id'] : $userObject['id'] ;?>);">
			<textarea title="escreva seus impulsos de espiritualidade..." required onkeyup="identifyYoutubeVideo(this);"></textarea>
			<input type="submit" class="share-button" value="compartilhar">
		</form>
		
		<form id="share-photo-form" method="post" enctype="multipart/form-data" action="lib/socialAPI.php?action=PhotoCreate" target="iframe-post" style="display:none;">
			<input type="hidden" name="MAX_FILE_SIZE" value="3145728">
			<input type="hidden" name="idReferredObject" value="<?= isset($socialObject['id']) ? $socialObject['id'] : $userObject['id'] ;?>">
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
			<br><span style="font-size:11px;">tamanho máximo 3MB</span>
			<iframe name="iframe-post" width="100" height="50" style="display:none;"></iframe>
		</form>
			
		<div id="share-preview">
			<div id="share-preview-thumb"></div>
			<div id="share-preview-description"></div>
		</div>
		
	</div>
	
	<? } ?>
	
	
	<div class="comment-box" style="display:none;">
		<form id="comment-form" action="javascript:commentCreate();">
			<textarea required></textarea>
			<input type="submit" class="shared-button" value="comentar">
		</form>
	</div>
	
	<div class="report-box" style="display:none;">
		<p><strong>Denunciar abuso na rede social</strong></p>
		<form id="report-form" action="javascript:reportAbuse()">
			<input id="idObject" name="idObject" type="hidden" value="">
			<p><label><input required type="radio" name="abuse-type" value="nudez / conteúdo sexual">nudez / conteúdo sexual</label></p>
			<p><label><input required type="radio" name="abuse-type" value="spam / vírus">spam / vírus</label></p>
			<p><label><input required type="radio" name="abuse-type" value="identidade / informação pessoal">identidade / informação pessoal</label></p>
			<p><label><input required type="radio" name="abuse-type" value="atividade ilegal">atividade ilegal</label></p>
			<p><label><input required type="radio" name="abuse-type" value="ódio / violência">ódio / violência</label></p>
			<p><label><input required type="radio" name="abuse-type" value="segurança da conta">segurança da conta</label></p>
			<p><label><input required type="radio" name="abuse-type" value="preconceito">preconceito</label></p>
			<p><label><input required type="radio" name="abuse-type" value="outro">outro</label></p>
			
			<p><label class="label" for="sDescription">descrição do abuso</label><textarea id="sDescription" required style="width:280px;min-width:280px;max-width:280px;"></textarea></p>
			<p style="text-align:right;padding:0 12px 0 0;"><input type="submit" class="basic-button" value="denunciar">
			<input type="reset" class="basic-button" value="cancelar"></p>
		</form>
	</div>
	
	<!-- begin: posts -->
	<?php
		// --------------------------------------------------
		// montagem da lista de posts
		// a variável $idList guarda os ids dos profiles usados para a montagem da timeline
		// para ser usado na função de paginação
		
		$idList;
		
		// página inicial
		if(isset($from) && $from == "front-page") {
		
			$friendList = getAssociations($userObject['id'],'FRIEND','FRIENDSHIP','FRIEND','',0,0,'0');
			
			// adiciona o próprio profile à lista de posts:
			$friendList[] = array(
								"myRole" => 0, 
								"Association" => 0, 
								"AssociationType" => 0, 
								"id" => $userObject['id'], 
								"sDisplayName" => $userObject['sDisplayName'], 
								"theirRole" => 0
							);
			
			// monta uma lista com os ids de todos os amigos:
			$friendIdList = array();
			for ($i=0; $i < count($friendList); $i++) {
				$friendIdList[] = $friendList[$i]['id'];
			}
			
			// carrega todos os posts dos amigos:
			$list = getAssociations(
									$friendIdList, 
									'OWNER', 
									array('POST','PHOTO'), 
									'OBJECT', 
									'o.tsCreation DESC',
									0, 25, '0'
									);
			
			$idList = $friendIdList;
			
		// página do perfil
		} else {
			
			$list = getAssociations(
									$socialObject['id'], 
									'REFERRED', 
									array('POST','PHOTO'), 
									'OBJECT', 
									'o.tsCreation DESC',
									0, 25, '0'
									);
			
			$idList = array($socialObject['id']);
			
		}
	?>
	
	<?php
		
		if (count($list) == 0) {
			echo '<div><p>Você ainda não possui impulsos para exibir.<br>Compartilhe um impulso de espiritualidade com seus amigos!</p></div>';
		}
		
		foreach($list as $item) {
			$itemCreation	  = $item['tsCreation'];
			
			$owner			  = getAssociations($item['id'], 'OBJECT', 'POST', 'OWNER');
			if (!$owner) {
				$owner		  = getAssociations($item['id'], 'OBJECT', 'PHOTO', 'OWNER');
			}
			$ownerId 		  = $owner[0]['id'];
			$ownerDisplayName = $owner[0]['sDisplayName'];
			
			$owner			  = getObject($ownerId);
			$ownerCreation	  = $owner['tsCreation'];
			
			$sAvatarPath = getProperties($ownerId,'sAvatarPath');
			$sAvatarPath = $sAvatarPath['sAvatarPath'];
			
			$original	 = getAssociations($item['id'], 'OBJECT', array('POST','PHOTO'), 'ORIGINAL');
			if (count($original)>0) {
				$originalId 	= $original[0]['id'];
				$originalOwner 	= getAssociations($originalId, 'OBJECT', '', 'OWNER');
				if ($originalOwner) {
					$originalOwner	= getObject($originalOwner[0]['id']);
				} else {
					// o owner não existe (a conta foi bloqueada?)
					continue;
				}
			}
	?>
	
		<article class="post root-post" data-key="<?=$item['id']; ?>">
			
			<div class="post-photo">
				<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>" target="_self">
				<img src="<?= $sAvatarPath ? $sAvatarPath : 'img/avatar-default.jpg'; ?>" alt="" border="0" width="50">
				</a>
			</div>
			
			<div class="post-info">
				<?php 
					$sDataVideo = getProperties($item['id'],'sDataVideo');
					if ($sDataVideo) {
						$sDataVideo = $sDataVideo['sDataVideo'];
					}
					
					$sDataPhoto = getProperties($item['id'],'sDataPhoto');
					if ($sDataPhoto) {
						$sDataPhoto = $sDataPhoto['sDataPhoto'];
					}
					
					$sContent = getDetails($item['id'],'sContent');
					$sContent = $sContent['sContent'];
					$sContent = str_replace(chr(13), '<br>', $sContent);
					
					$sContent = identifyLink($sContent);
					
				?>
				
				<? if ($owner['fDeleted'] == 0) { ?>
				<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>"><strong><?=$ownerDisplayName;?></strong></a>&nbsp;
				<? } else { ?>
				<strong><?=$ownerDisplayName;?></strong>&nbsp;
				<? } ?>
				
				<? if (count($original)>0) { ?>
				
					<em style="font-size:10px;color:#999;">compartilhou o impulso de <a href="index.php?id=<?=uniqid();?>&sob=<?=$originalId;?>&prob=<?=$originalOwner['id'];?>"><?=$originalOwner['sDisplayName']?></a> em <a href="index.php?id=<?=uniqid();?>&sob=<?=$originalId;?>&prob=<?=$originalOwner['id'];?>" title="permalink" style="color:#999;"><?=$itemCreation;?></a></em><br>
				
				<? } else { ?>
				
					<em style="font-size:10px;color:#999;">postou em <a href="index.php?id=<?=uniqid();?>&sob=<?=$item['id'];?>&prob=<?=$ownerId;?>" title="permalink" style="color:#999;"><?=$itemCreation;?></a></em><br>
				
				<? } ?>
				
				<?php 
					if ($sDataVideo) {
						
						$values = json_decode($sDataVideo,true);
						$thumb 	= $values['thumb']	? $values['thumb'] 	: '';
						$link 	= $values['link']	? $values['link'] 	: '';
						$id		= $values['id']		? $values['id'] 	: '';
						
						if ($id != '') {
							
							echo '<div class="thumb" style="display:inline-block;width:120px;margin:5px 10px 0 0;padding:0;"><a href="#" title="assista ao vídeo" data-yvid="'.$id.'"><img src="'.$thumb.'"></a></div>';
							
							echo '<div class="desc" style="display:inline-block;margin:5px 0 0 0;vertical-align:top;width:300px;">'.$sContent.'</div>';
							
							echo '<div class="embed" style="display:none;margin:5px 0;"></div>';
							
						}
						
					} else if ($sDataPhoto) {
						
						$values = json_decode($sDataPhoto,true);
						$thumb 	= $values['thumb']	? $values['thumb'] 	: '';
						$link 	= $values['link']	? $values['link'] 	: '';
						
						if (count($original)>0) {
							
							echo '<div class="thumb-img" style="display:inline-block;width:120px;margin:5px 10px 0 0;padding:0;"><a href="index.php?id='.uniqid().'&sob='.$originalId.'&prob='.$originalOwner['id'].'" title="veja a imagem" target="_self"><img src="'.$thumb.'" style="width:120px;"></a></div>';
							
						} else {
							
							echo '<div class="thumb-img" style="display:inline-block;width:120px;margin:5px 10px 0 0;padding:0;"><a href="index.php?id='.uniqid().'&sob='.$item['id'].'&prob='.$ownerId.'" title="veja a imagem" target="_self"><img src="'.$thumb.'" style="width:120px;"></a></div>';
							
						}
						
						echo '<div class="desc" style="display:inline-block;margin:5px 0 0 0;vertical-align:top;width:300px;">'.$sContent.'</div>';
						
					} else {
						if ($sContent) { echo $sContent; }
					}
				?>
				
				<br>
				
				<?php
					
					// ----------------------------------------------------------------------
					// barra de interação (gostar, comentar, compartilhar)
					// ----------------------------------------------------------------------
					
					if (isset($userObject) && $owner['fDeleted'] == 0) {
					
						echo '<div class="interaction-bar">';
						
						$like = count(likeStatus($userObject['id'],$item['id'])) > 0 ? '<a href="#" class="dislike"><strong>você gostou</strong> (desfazer)</a>' : '<a href="#" class="like">gostei</a>' ;
						
						echo $like;
						
						echo ' | <a href="#" class="create-comment">comentar</a> | <a href="#" class="share-post">compartilhar</a>';
						// <a href="#" class="share-post">compartilhar</a> | <a href="#" class="report-abuse">denunciar</a>';
						
						$report = count(reportStatus($userObject['id'],$item['id'])) > 0 ? ' | <a><strong>você denunciou</strong></a>' : ' | <a href="#" class="report-abuse">denunciar</a>' ;
						
						echo $report;
						
						$iTotalLike   = getCountAssociations($item['id'],'OBJECT','','LIKER');
						if ($iTotalLike > 0) {
							$likeText = $iTotalLike > 1 ? $iTotalLike.' pessoas gostaram' : $iTotalLike.' pessoa gostou';
							echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
						}
						
						$iTotalReport   = getCountAssociations($item['id'],'REFERRED','REPORT','OWNER');
						if ($iTotalReport > 0) {
							$reportText = $iTotalReport > 1 ? $iTotalReport.' pessoas denunciaram' : $iTotalReport.' pessoa denunciou';
							echo '<br><span style="font-size:11px;color:#C00;">'.$reportText.'</span>';
						}
						
						echo '</div>';
					}
				?>
			</div>
			
		
		<?php
			$listComment = getAssociations($item['id'], 'REFERRED','COMMENT','OBJECT','',0,0,'0');
			for ($j=0;$j<count($listComment);$j++) {
		?>
		
			<article class="post comment" data-key="<?=$listComment[$j]['id'];?>">
				
				<div class="post-photo">
					<?
						$itemCreation	  = $listComment[$j]['tsCreation'];
						
						$owner			  = getAssociations($listComment[$j]['id'], 'OBJECT', 'COMMENT', 'OWNER');
						$ownerDisplayName = $owner[0]['sDisplayName'];
						$ownerId		  = $owner[0]['id'];
						
						$owner			  = getObject($ownerId);
						$ownerCreation	  = $owner['tsCreation'];
						
						$sAvatarPath = getProperties($ownerId,'sAvatarPath');
						$sAvatarPath = $sAvatarPath['sAvatarPath'];
					?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>" target="_self">
					<img src="<?= $sAvatarPath ? $sAvatarPath : 'img/avatar-default.jpg'; ?>" alt="" border="0" width="30">
					</a>
				</div>
				
				<div class="post-info">
					<?
						$sContent = getDetails($listComment[$j]['id'],'sContent');
						$sContent = $sContent['sContent'];
						$sContent = str_replace(chr(13), '<br>', $sContent);
					?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>"><strong><?=$ownerDisplayName;?></strong></a>&nbsp;
					<em style="font-size:10px;color:#999;">comentou em <a href="index.php?id=<?=uniqid();?>&sob=<?=$listComment[$j]['id'];?>&prob=<?=$ownerId;?>" title="permalink" style="color:#999;"><?=$itemCreation;?></a></em><br>
					<?=$sContent ? $sContent : ''; ?>
					<br>
					
					
					<?php
						
						// ----------------------------------------------------------------------
						// barra de interação (gostar, comentar, compartilhar) do comentário
						// ----------------------------------------------------------------------
						
						if (isset($userObject) && $owner['fDeleted'] == 0) {
						
							echo '<div class="interaction-bar">';
							
							$like = count(likeStatus($userObject['id'],$listComment[$j]['id'])) > 0 ? '<a href="#" class="dislike"><strong>você gostou</strong> (desfazer)</a>' : '<a href="#" class="like">gostei</a>' ;
							
							echo $like;
							
							echo ' | <a href="#" class="create-comment">comentar</a>';
							// <a href="#" class="report-abuse">denunciar</a>';
							
							$report = count(reportStatus($userObject['id'],$listComment[$j]['id'])) > 0 ? ' | <a><strong>você denunciou</strong></a>' :  ' | <a href="#" class="report-abuse">denunciar</a>' ;
							
							echo $report;
							
							/*
							$iTotal   = getCountAssociations($listComment[$j]['id'],'OBJECT','','LIKER');
							if ($iTotal > 0) {
								$likeText = $iTotal > 1 ? $iTotal.' pessoas gostaram' : $iTotal.' pessoa gostou';
								echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
							}
							*/
							$iTotalLike   = getCountAssociations($listComment[$j]['id'],'OBJECT','','LIKER');
							if ($iTotalLike > 0) {
								$likeText = $iTotalLike > 1 ? $iTotalLike.' pessoas gostaram' : $iTotalLike.' pessoa gostou';
								echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
							}
							
							$iTotalReport   = getCountAssociations($listComment[$j]['id'],'REFERRED','REPORT','OWNER');
							if ($iTotalReport > 0) {
								$reportText = $iTotalReport > 1 ? $iTotalReport.' pessoas denunciaram' : $iTotalReport.' pessoa denunciou';
								echo '<br><span style="font-size:11px;color:#C00;">'.$reportText.'</span>';
							}
							
							echo '</div>';
						}
					?>
					
				</div>
				
			</article>
		
		<? } ?>
		
		</article>
		
	<? } ?>
	
	<?php
		// --------------------------------------------------
		// paginação da timeline
	?>
	<a href="#" id="load-history" data-offset="25" data-idlist="<?=implode(',',$idList);?>" data-type="POST,PHOTO" title="carregar histórico mais antigo">
	<div style="
			padding:10px;
			text-align:center;
			margin:10px 0;
			background-color:#F4F4F4;
			border:1px solid #E2E2E2;
	">carregar histórico mais antigo</div></a>
	
	<!-- end: posts -->









<?php 
	} else if ($feed == 'oracoes') {
?>
	
	<? if (	isset($socialObject) && 
			$socialObject['iType'] == 5 && 
			$userObject['id'] == $profileObject['id']
		) { ?>
	
	<!-- caixa de compartilhamento -->
	<div class="post-box">
		
		<div class="post-options">
		<p>Envie uma oração:<br>
		<a id="share-text" class="share-link active" href="#">Compartilhar oração</a> | <a id="share-photo" class="share-link" href="#">Compartilhar imagem</a></p>
		</div>
		
		<form id="share-text-form" action="javascript:postCreate(<?=isset($socialObject['id']) ? $socialObject['id'] : '' ;?>);">
			<textarea title="escreva sua oração" required onkeyup="identifyYoutubeVideo(this);"></textarea>
			<input type="submit" class="share-button" value="compartilhar">
		</form>
		
		<form id="share-photo-form" method="post" enctype="multipart/form-data" action="lib/socialAPI.php?action=PhotoCreate" target="iframe-post" style="display:none;">
			<input type="hidden" name="MAX_FILE_SIZE" value="3145728">
			<input type="hidden" name="idReferredObject" value="<?=isset($socialObject['id']) ? $socialObject['id'] : '' ;?>">
			<textarea name="sContent" title="descreva sua imagem" required></textarea>
			<input type="submit" class="share-button" value="compartilhar" onclick="javascript:$('form#share-photo-form img#loading-image').show();">
			
			<label>Selecione o arquivo da imagem em seu computador</label>
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
			<br><span style="font-size:11px;">tamanho máximo 3MB</span>
			<iframe name="iframe-post" width="100" height="50" style="display:none;"></iframe>
		</form>
			
		<div id="share-preview">
			<div id="share-preview-thumb"></div>
			<div id="share-preview-description"></div>
		</div>
		
	</div>
	<? } ?>
	
	
	<div class="comment-box" style="display:none;">
		<form id="comment-form" action="javascript:commentCreate();">
			<textarea required></textarea>
			<input type="submit" class="shared-button" value="comentar">
		</form>
	</div>
	
	
	<!-- begin: orações -->
	<?php 
		$list = getAssociations(
								$socialObject['id'], 
								'REFERRED', 
								array('POST','PHOTO'), 
								'OBJECT', 
								'o.tsCreation DESC',
								0, 25, '0'
								);
		$idList = array($socialObject['id']);
		
		foreach($list as $item) {
	?>
		<article class="post root-post" data-key="<?=$item['id']; ?>">
			<?php

				$itemCreation	  = $item['tsCreation'];
				
				$owner			  = getAssociations($item['id'], 'OBJECT', 'POST', 'OWNER');
				if (!$owner) {
					$owner		  = getAssociations($item['id'], 'OBJECT', 'PHOTO', 'OWNER');
				}
				$ownerId 		  = $owner[0]['id'];
				$ownerDisplayName = $owner[0]['sDisplayName'];
				
				$owner			  = getObject($ownerId);
				$ownerCreation	  = $owner['tsCreation'];
				
				$sAvatarPath = getProperties($ownerId,'sAvatarPath');
				$sAvatarPath = $sAvatarPath['sAvatarPath'];
			?>
			
			<div class="post-info" style="width:500px;">
				<?php 
					$sDataVideo = getProperties($item['id'],'sDataVideo');
					if ($sDataVideo) {
						$sDataVideo = $sDataVideo['sDataVideo'];
					}
					
					$sDataPhoto = getProperties($item['id'],'sDataPhoto');
					if ($sDataPhoto) {
						$sDataPhoto = $sDataPhoto['sDataPhoto'];
					}
					
					$sContent = getDetails($item['id'],'sContent');
					$sContent = $sContent['sContent'];
					
					// $sContent = str_replace(chr(13), '<br>', $sContent);
					$sContent = identifyLink($sContent);
					
				?>
				<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>"><strong><?=$ownerDisplayName;?></strong></a>&nbsp;
				<em style="font-size:10px;color:#999;">compartilhou uma oração em <a href="index.php?id=<?=uniqid();?>&sob=<?=$item['id'];?>&prob=<?=$ownerId;?>" title="permalink" style="color:#999;"><?=$itemCreation;?></a></em><br>
				
				<?php 
					if ($sDataVideo) {
						
						$values = json_decode($sDataVideo,true);
						$thumb 	= $values['thumb']	? $values['thumb'] 	: '';
						$link 	= $values['link']	? $values['link'] 	: '';
						$id		= $values['id']		? $values['id'] 	: '';
						
						if ($id != '') {
							
							echo '<div class="thumb" style="display:block;width:500px;margin:5px 10px 0 0;padding:0;"><a href="#" title="assista ao vídeo" data-yvid="'.$id.'"><img src="'.$thumb.'"></a></div>';
							
							echo '<div class="desc" style="display:block;margin:5px 0 0 0;vertical-align:top;width:500px;">'.$sContent.'</div>';
							
							echo '<div class="embed" style="display:none;margin:5px 0;"></div>';
							
						}
						
					} else if ($sDataPhoto) {
						
						$values = json_decode($sDataPhoto,true);
						$thumb 	= $values['thumb']	? $values['thumb'] 	: '';
						$link 	= $values['link']	? $values['link'] 	: '';
						
						echo '<div class="thumb-img" style="display:block;width:500px;margin:10px 10px 10px 0;padding:0;"><img src="'.$link.'" style="width:500px;"></div>';
						
						echo '<div class="desc" style="display:block;margin:5px 0 0 0;vertical-align:top;width:500px;">'.$sContent.'</div>';
						
					} else {
						if ($sContent) { echo $sContent; }
					}
				?>
				
				<br>
				
				<?php
					
					// ----------------------------------------------------------------------
					// barra de interação (gostar, comentar, compartilhar)
					// ----------------------------------------------------------------------
					
					if (isset($userObject) && $owner['fDeleted'] == 0) {
					
						echo '<div class="interaction-bar">';
						
						$like = count(likeStatus($userObject['id'],$item['id'])) > 0 ? '<a href="#" class="dislike"><strong>você gostou</strong> (desfazer)</a>' : '<a href="#" class="like">gostei</a>' ;
						
						echo $like;
						
						echo ' | <a href="#" class="create-comment">comentar</a> | <a href="#" class="share-post">compartilhar</a>';
						// <a href="#" class="report-abuse">denunciar</a>';
						
						$report = count(reportStatus($userObject['id'],$item['id'])) > 0 ? ' | <a><strong>você denunciou</strong></a>' : ' | <a href="#" class="report-abuse">denunciar</a>' ;
						
						echo $report;
						/*
						$iTotal   = getCountAssociations($item['id'],'OBJECT','','LIKER');
						if ($iTotal > 0) {
							$likeText = $iTotal > 1 ? $iTotal.' pessoas gostaram' : $iTotal.' pessoa gostou';
							echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
						}
						*/
						$iTotalLike   = getCountAssociations($item['id'],'OBJECT','','LIKER');
						if ($iTotalLike > 0) {
							$likeText = $iTotalLike > 1 ? $iTotalLike.' pessoas gostaram' : $iTotalLike.' pessoa gostou';
							echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
						}
						
						$iTotalReport   = getCountAssociations($item['id'],'REFERRED','REPORT','OWNER');
						if ($iTotalReport > 0) {
							$reportText = $iTotalReport > 1 ? $iTotalReport.' pessoas denunciaram' : $iTotalReport.' pessoa denunciou';
							echo '<br><span style="font-size:11px;color:#C00;">'.$reportText.'</span>';
						}
						
						echo '</div>';
					}
				?>
				
				<? if (false) { ?>
				<a href="#" class="like">gostei</a> | <a href="#" class="create-comment">comentar</a> | <a href="#" class="share-post">compartilhar</a> | <a href="#" class="report-abuse">denunciar</a>
				<? } ?>
			</div>
			
		
		<?php
			$listComment = getAssociations($item['id'], 'REFERRED','COMMENT','OBJECT','',0,0,'0');
			for ($j=0;$j<count($listComment);$j++) {
		?>
			<article class="post comment" data-key="<?=$listComment[$j]['id'];?>" style="margin:10px 0 5px 0;">
				
				<div class="post-photo">
					<?php
						$itemCreation	  = $listComment[$j]['tsCreation'];
						
						$owner			  = getAssociations($listComment[$j]['id'], 'OBJECT', 'COMMENT', 'OWNER');
						$ownerDisplayName = $owner[0]['sDisplayName'];
						$ownerId		  = $owner[0]['id'];
						
						$owner			  = getObject($ownerId);
						$ownerCreation	  = $owner['tsCreation'];
						
						$sAvatarPath = getProperties($ownerId,'sAvatarPath');
						$sAvatarPath = $sAvatarPath['sAvatarPath'];
					?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>" target="_self">
					<img src="<?= $sAvatarPath ? $sAvatarPath : 'img/avatar-default.jpg'; ?>" alt="" border="0" width="30">
					</a>
				</div>
				
				<div class="post-info">
					<?php
						$sContent = getDetails($listComment[$j]['id'],'sContent');
						$sContent = $sContent['sContent'];
						$sContent = str_replace(chr(13), '<br>', $sContent);
					?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>"><strong><?=$ownerDisplayName;?></strong></a>&nbsp;
					<em style="font-size:10px;color:#999;">comentou em <a href="index.php?id=<?=uniqid();?>&sob=<?=$listComment[$j]['id'];?>&prob=<?=$ownerId;?>" title="permalink" style="color:#999;"><?=$itemCreation;?></a></em><br>
					<?=$sContent ? $sContent : ''; ?>
					<br>
					
					<?php
						
						// ----------------------------------------------------------------------
						// barra de interação (gostar, comentar, compartilhar) no comentário
						// ----------------------------------------------------------------------
						
						if (isset($userObject) && $owner['fDeleted'] == 0) {
						
							echo '<div class="interaction-bar">';
							
							$like = count(likeStatus($userObject['id'],$listComment[$j]['id'])) > 0 ? '<a href="#" class="dislike"><strong>você gostou</strong> (desfazer)</a>' : '<a href="#" class="like">gostei</a>' ;
							
							echo $like;
							
							echo ' | <a href="#" class="create-comment">comentar</a>';
							// <a href="#" class="report-abuse">denunciar</a>';
							
							$report = count(reportStatus($userObject['id'],$listComment[$j]['id'])) > 0 ? ' | <a><strong>você denunciou</strong></a>' : ' | <a href="#" class="report-abuse">denunciar</a>' ;
							
							echo $report;
							/*
							$iTotal   = getCountAssociations($listComment[$j]['id'],'OBJECT','','LIKER');
							if ($iTotal > 0) {
								$likeText = $iTotal > 1 ? $iTotal.' pessoas gostaram' : $iTotal.' pessoa gostou';
								echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
							}
							*/
							$iTotalLike   = getCountAssociations($listComment[$j]['id'],'OBJECT','','LIKER');
							if ($iTotalLike > 0) {
								$likeText = $iTotalLike > 1 ? $iTotalLike.' pessoas gostaram' : $iTotalLike.' pessoa gostou';
								echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
							}
							
							$iTotalReport   = getCountAssociations($listComment[$j]['id'],'REFERRED','REPORT','OWNER');
							if ($iTotalReport > 0) {
								$reportText = $iTotalReport > 1 ? $iTotalReport.' pessoas denunciaram' : $iTotalReport.' pessoa denunciou';
								echo '<br><span style="font-size:11px;color:#C00;">'.$reportText.'</span>';
							}
							
							echo '</div>';
						}
					?>
				
					<? if (false) { ?>
					<a href="#" class="like">gostei</a> | <a href="#" class="create-comment">comentar</a> | <a href="#" class="share-post">compartilhar</a> | <a href="#" class="report-abuse">denunciar</a>
					<? } ?>
					
				</div>
				
			</article>
		
		<? } ?>
		
		</article>
		
	<? } ?>

	<?php
		// --------------------------------------------------
		// paginação da timeline
	?>
	<a href="#" id="load-history" data-offset="25" data-idlist="<?=implode(',',$idList);?>" data-type="POST,PHOTO" title="carregar histórico mais antigo">
	<div style="
			padding:10px;
			text-align:center;
			margin:10px 0;
			background-color:#F4F4F4;
			border:1px solid #E2E2E2;
	">carregar histórico mais antigo</div></a>

<?php
	} else if ($feed == 'event') {
?>
	
	<h2>Eventos de <?=$socialObject['sDisplayName'];?></h2>
	
	<?php
		// ------------------------------------------------------------
		// criação e edição de eventos:
		
		if (
		(isset($userObject) 	&& !isset($socialObject) && !isset($profileObject)) || 
		(isset($socialObject) 	&& $socialObject['iType'] == 2 && $userObject['id'] == $profileObject['id']) || 
		(isset($socialObject) 	&& $socialObject['iType'] == 1)
		) {
	?>
	<div class="post-box" style="display:none;">
		
		<p><strong>Informações do novo evento</strong></p>
		
		<form id="create-event-form" action="javascript:eventCreate(<?= isset($socialObject['id']) ? $socialObject['id'] : $userObject['id'] ;?>);" style="margin:0;">
			
			<p><label class="label" for="sDisplayName">título</label><input type="text" id="sDisplayName" placeholder="título do evento" required></p>
			<p><label class="label" for="tsBegin">início</label><input type="text" id="tsBegin" placeholder="dd/mm/aaaa" pattern="^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/(19|20)\d{2}$" maxlength="10" required style="width:100px;">&nbsp;
					<select name="tsBeginTime" id="tsBeginTime" style="width:100px;">
						<option value="00:00:00">00:00</option>
						<option value="00:30:00">00:30</option>
						<option value="01:00:00">01:00</option>
						<option value="01:30:00">01:30</option>
						<option value="02:00:00">02:00</option>
						<option value="02:30:00">02:30</option>
						<option value="03:00:00">03:00</option>
						<option value="03:30:00">03:30</option>
						<option value="04:00:00">04:00</option>
						<option value="04:30:00">04:30</option>
						<option value="05:00:00">05:00</option>
						<option value="05:30:00">05:30</option>
						<option value="06:00:00">06:00</option>
						<option value="06:30:00">06:30</option>
						<option value="07:00:00">07:00</option>
						<option value="07:30:00">07:30</option>
						<option value="08:00:00">08:00</option>
						<option value="08:30:00">08:30</option>
						<option value="09:00:00">09:00</option>
						<option value="09:30:00">09:30</option>
						<option value="10:00:00">10:00</option>
						<option value="10:30:00">10:30</option>
						<option value="11:00:00">11:00</option>
						<option value="11:30:00">11:30</option>
						<option value="12:00:00">12:00</option>
						<option value="12:30:00">12:30</option>
						<option value="13:00:00">13:00</option>
						<option value="13:30:00">13:30</option>
						<option value="14:00:00">14:00</option>
						<option value="14:30:00">14:30</option>
						<option value="15:00:00">15:00</option>
						<option value="15:30:00">15:30</option>
						<option value="16:00:00">16:00</option>
						<option value="16:30:00">16:30</option>
						<option value="17:00:00">17:00</option>
						<option value="17:30:00">17:30</option>
						<option value="18:00:00">18:00</option>
						<option value="18:30:00">18:30</option>
						<option value="19:00:00">19:00</option>
						<option value="19:30:00">19:30</option>
						<option value="20:00:00">20:00</option>
						<option value="20:30:00">20:30</option>
						<option value="21:00:00">21:00</option>
						<option value="21:30:00">21:30</option>
						<option value="22:00:00">22:00</option>
						<option value="22:30:00">22:30</option>
						<option value="23:00:00">23:00</option>
						<option value="23:30:00">23:30</option>
					</select></p>
			<p><label class="label" for="tsEnd">fim</label><input type="text" id="tsEnd" placeholder="dd/mm/aaaa" pattern="^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/(19|20)\d{2}$" maxlength="10" required style="width:100px;">&nbsp;
					<select name="tsEndTime" id="tsEndTime" style="width:100px;">
						<option value="00:00:00">00:00</option>
						<option value="00:30:00">00:30</option>
						<option value="01:00:00">01:00</option>
						<option value="01:30:00">01:30</option>
						<option value="02:00:00">02:00</option>
						<option value="02:30:00">02:30</option>
						<option value="03:00:00">03:00</option>
						<option value="03:30:00">03:30</option>
						<option value="04:00:00">04:00</option>
						<option value="04:30:00">04:30</option>
						<option value="05:00:00">05:00</option>
						<option value="05:30:00">05:30</option>
						<option value="06:00:00">06:00</option>
						<option value="06:30:00">06:30</option>
						<option value="07:00:00">07:00</option>
						<option value="07:30:00">07:30</option>
						<option value="08:00:00">08:00</option>
						<option value="08:30:00">08:30</option>
						<option value="09:00:00">09:00</option>
						<option value="09:30:00">09:30</option>
						<option value="10:00:00">10:00</option>
						<option value="10:30:00">10:30</option>
						<option value="11:00:00">11:00</option>
						<option value="11:30:00">11:30</option>
						<option value="12:00:00">12:00</option>
						<option value="12:30:00">12:30</option>
						<option value="13:00:00">13:00</option>
						<option value="13:30:00">13:30</option>
						<option value="14:00:00">14:00</option>
						<option value="14:30:00">14:30</option>
						<option value="15:00:00">15:00</option>
						<option value="15:30:00">15:30</option>
						<option value="16:00:00">16:00</option>
						<option value="16:30:00">16:30</option>
						<option value="17:00:00">17:00</option>
						<option value="17:30:00">17:30</option>
						<option value="18:00:00">18:00</option>
						<option value="18:30:00">18:30</option>
						<option value="19:00:00">19:00</option>
						<option value="19:30:00">19:30</option>
						<option value="20:00:00">20:00</option>
						<option value="20:30:00">20:30</option>
						<option value="21:00:00">21:00</option>
						<option value="21:30:00">21:30</option>
						<option value="22:00:00">22:00</option>
						<option value="22:30:00">22:30</option>
						<option value="23:00:00">23:00</option>
						<option value="23:30:00">23:30</option>
					</select></p>
			<p><label class="label" for="sAddress">endereço</label><input type="text" id="sAddress" placeholder="rua" required></p>
			<p><label class="label" for="sComplement">complemento</label><input type="text" id="sComplement" placeholder="apartamento, casa, bloco"></p>
			<p><label class="label" for="idADMINCountry">país</label><select name="idADMINCountry" id="idADMINCountry" value="" required>
							<option disabled selected value="">selecione o país</option>
							<option value="BR">Brasil</option>
						</select></p>
			<p><label class="label" for="idADMINState">estado</label><select name="idADMINState" id="idADMINState" value="" required>
							<option disabled selected value="">selecione o estado</option>
							<option value="AC">Acre</option>
							<option value="AL">Alagoas</option>
							<option value="AP">Amapá</option>
							<option value="AM">Amazonas</option>
							<option value="BA">Bahia</option>
							<option value="CE">Ceará</option>
							<option value="DF">Distrito Federal</option>
							<option value="ES">Espirito Santo</option>
							<option value="GO">Goiás</option>
							<option value="MA">Maranhão</option>
							<option value="MS">Mato Grosso do Sul</option>
							<option value="MT">Mato Grosso</option>
							<option value="MG">Minas Gerais</option>
							<option value="PA">Pará</option>
							<option value="PB">Paraíba</option>
							<option value="PR">Paraná</option>
							<option value="PE">Pernambuco</option>
							<option value="PI">Piauí</option>
							<option value="RJ">Rio de Janeiro</option>
							<option value="RN">Rio Grande do Norte</option>
							<option value="RS">Rio Grande do Sul</option>
							<option value="RO">Rondônia</option>
							<option value="RR">Roraima</option>
							<option value="SC">Santa Catarina</option>
							<option value="SP">São Paulo</option>
							<option value="SE">Sergipe</option>
							<option value="TO">Tocantins</option>
						</select></p>
			<p><label class="label" for="idADMINCity">cidade</label><input type="text" id="idADMINCity" placeholder="cidade" required></p>
			<p><label class="label" for="sNeighborhood">bairro</label><input type="text" id="sNeighborhood" placeholder="bairro"></p>
			<p><label class="label" for="sPostalCode">CEP</label><input type="text" id="sPostalCode" placeholder="xxxxx-xxx" maxlength="9"></p>
			<p><label class="label" for="sSite">site</label><input type="text" id="sSite" placeholder=""></p>
			<p><label class="label" for="sDescription">descrição</label><textarea id="sDescription" required style="width:350px;max-width:350px;"></textarea></p>
			<p style="text-align:right;padding:0 12px 0 0;"><input type="submit" class="basic-button" value="criar evento">
			<input type="reset" class="basic-button" value="cancelar"></p>
		</form>
		
	</div>
	
	<div class="post-box" style="display:none;">
		
		<p><strong>Editar informações do evento</strong></p>
		
		<form id="edit-event-form" action="javascript:eventEdit();" style="margin:0;">
			
			<input type="hidden" id="idReferredObject" name="idReferredObject" value="">
			
			<p><label class="label" for="sDisplayName">título</label><input type="text" id="sDisplayName" placeholder="título do evento" required></p>
			<p><label class="label" for="tsBegin">início</label><input type="text" id="tsBegin" placeholder="dd/mm/aaaa" pattern="^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/(19|20)\d{2}$" maxlength="10" required style="width:100px;">&nbsp;
					<select name="tsBeginTime" id="tsBeginTime" style="width:100px;">
						<option value="00:00:00">00:00</option>
						<option value="00:30:00">00:30</option>
						<option value="01:00:00">01:00</option>
						<option value="01:30:00">01:30</option>
						<option value="02:00:00">02:00</option>
						<option value="02:30:00">02:30</option>
						<option value="03:00:00">03:00</option>
						<option value="03:30:00">03:30</option>
						<option value="04:00:00">04:00</option>
						<option value="04:30:00">04:30</option>
						<option value="05:00:00">05:00</option>
						<option value="05:30:00">05:30</option>
						<option value="06:00:00">06:00</option>
						<option value="06:30:00">06:30</option>
						<option value="07:00:00">07:00</option>
						<option value="07:30:00">07:30</option>
						<option value="08:00:00">08:00</option>
						<option value="08:30:00">08:30</option>
						<option value="09:00:00">09:00</option>
						<option value="09:30:00">09:30</option>
						<option value="10:00:00">10:00</option>
						<option value="10:30:00">10:30</option>
						<option value="11:00:00">11:00</option>
						<option value="11:30:00">11:30</option>
						<option value="12:00:00">12:00</option>
						<option value="12:30:00">12:30</option>
						<option value="13:00:00">13:00</option>
						<option value="13:30:00">13:30</option>
						<option value="14:00:00">14:00</option>
						<option value="14:30:00">14:30</option>
						<option value="15:00:00">15:00</option>
						<option value="15:30:00">15:30</option>
						<option value="16:00:00">16:00</option>
						<option value="16:30:00">16:30</option>
						<option value="17:00:00">17:00</option>
						<option value="17:30:00">17:30</option>
						<option value="18:00:00">18:00</option>
						<option value="18:30:00">18:30</option>
						<option value="19:00:00">19:00</option>
						<option value="19:30:00">19:30</option>
						<option value="20:00:00">20:00</option>
						<option value="20:30:00">20:30</option>
						<option value="21:00:00">21:00</option>
						<option value="21:30:00">21:30</option>
						<option value="22:00:00">22:00</option>
						<option value="22:30:00">22:30</option>
						<option value="23:00:00">23:00</option>
						<option value="23:30:00">23:30</option>
					</select></p>
			<p><label class="label" for="tsEnd">fim</label><input type="text" id="tsEnd" placeholder="dd/mm/aaaa" pattern="^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/(19|20)\d{2}$" maxlength="10" required style="width:100px;">&nbsp;
					<select name="tsEndTime" id="tsEndTime" style="width:100px;">
						<option value="00:00:00">00:00</option>
						<option value="00:30:00">00:30</option>
						<option value="01:00:00">01:00</option>
						<option value="01:30:00">01:30</option>
						<option value="02:00:00">02:00</option>
						<option value="02:30:00">02:30</option>
						<option value="03:00:00">03:00</option>
						<option value="03:30:00">03:30</option>
						<option value="04:00:00">04:00</option>
						<option value="04:30:00">04:30</option>
						<option value="05:00:00">05:00</option>
						<option value="05:30:00">05:30</option>
						<option value="06:00:00">06:00</option>
						<option value="06:30:00">06:30</option>
						<option value="07:00:00">07:00</option>
						<option value="07:30:00">07:30</option>
						<option value="08:00:00">08:00</option>
						<option value="08:30:00">08:30</option>
						<option value="09:00:00">09:00</option>
						<option value="09:30:00">09:30</option>
						<option value="10:00:00">10:00</option>
						<option value="10:30:00">10:30</option>
						<option value="11:00:00">11:00</option>
						<option value="11:30:00">11:30</option>
						<option value="12:00:00">12:00</option>
						<option value="12:30:00">12:30</option>
						<option value="13:00:00">13:00</option>
						<option value="13:30:00">13:30</option>
						<option value="14:00:00">14:00</option>
						<option value="14:30:00">14:30</option>
						<option value="15:00:00">15:00</option>
						<option value="15:30:00">15:30</option>
						<option value="16:00:00">16:00</option>
						<option value="16:30:00">16:30</option>
						<option value="17:00:00">17:00</option>
						<option value="17:30:00">17:30</option>
						<option value="18:00:00">18:00</option>
						<option value="18:30:00">18:30</option>
						<option value="19:00:00">19:00</option>
						<option value="19:30:00">19:30</option>
						<option value="20:00:00">20:00</option>
						<option value="20:30:00">20:30</option>
						<option value="21:00:00">21:00</option>
						<option value="21:30:00">21:30</option>
						<option value="22:00:00">22:00</option>
						<option value="22:30:00">22:30</option>
						<option value="23:00:00">23:00</option>
						<option value="23:30:00">23:30</option>
					</select></p>
			<p><label class="label" for="sAddress">endereço</label><input type="text" id="sAddress" placeholder="rua" required></p>
			<p><label class="label" for="sComplement">complemento</label><input type="text" id="sComplement" placeholder="apartamento, casa, bloco"></p>
			<p><label class="label" for="idADMINCountry">país</label><select name="idADMINCountry" id="idADMINCountry" value="" required>
							<option disabled value="">selecione o país</option>
							<option value="BR">Brasil</option>
						</select></p>
			<p><label class="label" for="idADMINState">estado</label><select name="idADMINState" id="idADMINState" value="" required>
							<option disabled value="">selecione o estado</option>
							<option value="AC">Acre</option>
							<option value="AL">Alagoas</option>
							<option value="AP">Amapá</option>
							<option value="AM">Amazonas</option>
							<option value="BA">Bahia</option>
							<option value="CE">Ceará</option>
							<option value="DF">Distrito Federal</option>
							<option value="ES">Espirito Santo</option>
							<option value="GO">Goiás</option>
							<option value="MA">Maranhão</option>
							<option value="MS">Mato Grosso do Sul</option>
							<option value="MT">Mato Grosso</option>
							<option value="MG">Minas Gerais</option>
							<option value="PA">Pará</option>
							<option value="PB">Paraíba</option>
							<option value="PR">Paraná</option>
							<option value="PE">Pernambuco</option>
							<option value="PI">Piauí</option>
							<option value="RJ">Rio de Janeiro</option>
							<option value="RN">Rio Grande do Norte</option>
							<option value="RS">Rio Grande do Sul</option>
							<option value="RO">Rondônia</option>
							<option value="RR">Roraima</option>
							<option value="SC">Santa Catarina</option>
							<option value="SP">São Paulo</option>
							<option value="SE">Sergipe</option>
							<option value="TO">Tocantins</option>
						</select></p>
			<p><label class="label" for="idADMINCity">cidade</label><input type="text" id="idADMINCity" placeholder="cidade" required></p>
			<p><label class="label" for="sNeighborhood">bairro</label><input type="text" id="sNeighborhood" placeholder="bairro"></p>
			<p><label class="label" for="sPostalCode">CEP</label><input type="text" id="sPostalCode" placeholder="xxxxx-xxx" maxlength="9"></p>
			<p><label class="label" for="sSite">site</label><input type="text" id="sSite" placeholder=""></p>
			<p><label class="label" for="sDescription">descrição</label><textarea id="sDescription" required style="width:350px;max-width:350px;"></textarea></p>
			<p style="text-align:right;padding:0 12px 0 0;"><input type="submit" class="basic-button" value="salvar evento">
			<input type="reset" class="basic-button" value="cancelar"></p>
		</form>
		
	</div>
	
	<div><button id="create-event" type="button" class="basic-button">criar novo evento</button></div>
	
	<? } ?>
	
	
	<!-- begin: events -->
	<?php 
		$list = getAssociations(
								$socialObject['id'], 
								'OWNER', 
								'EVENT', 
								'OBJECT', 
								'o.tsCreation DESC',
								0,0,'0'
								);
		
		$idList = array($socialObject['id']);
		
		foreach($list as $item) {
		
			$itemCreation 		 = showDate($item['tsCreation'],'timestamp');
			
			$owner 				 = getAssociations($item['id'], 'OBJECT', 'EVENT', 'OWNER');
			$owner 				 = getObject($owner[0]['id']);
			
			$owner['properties'] = getProperties($owner['id'],'');
			$item['properties']	 = getProperties($item['id'],'');
			
			$sContent = getDetails($item['id'],'sContent');
			$sContent = $sContent['sContent'];
			$sContent = str_replace(chr(13), '<br>', $sContent);
	?>
		<article class="post root-post" data-key="<?=$item['id'];?>">
			
			<div class="post-photo">
				<a href="index.php?id=<?=uniqid();?>&sob=<?=$item['id'];?>&prob=<?=$profileObject['id'];?>" target="_self">
				<img src="<?= $item['properties']['sAvatarPath'] ? $item['properties']['sAvatarPath'] : 'img/page-default.jpg'; ?>" alt="" border="0" width="50">
				</a>
			</div>
			
			<div class="post-info">
				
				<? if (
					(isset($userObject) 	&& !isset($socialObject) && !isset($profileObject)) || 
					(isset($socialObject) 	&& $socialObject['iType'] == 2 && $userObject['id'] == $profileObject['id']) || 
					(isset($socialObject) 	&& $socialObject['iType'] == 1)
					) { ?>
				<button type="button" class="basic-button edit-event" data-key="<?=$item['id'];?>" style="float:right;">editar evento</button>
				<? } ?>
				
				<h3><a href="index.php?id=<?=uniqid();?>&sob=<?=$item['id'];?>&prob=<?=$profileObject['id'];?>" target="_self"><?=$item['properties']['sDisplayName'];?></a></h3>
				
				<p><span class="label">horário:</span> <span class="field"><?=showDate($item['properties']['tsBegin'],'datetime');?></span> até <span class="field"><?=showDate($item['properties']['tsEnd'],'datetime');?></span><br>
				<span class="label">local:</span> <span class="field"><?=$item['properties']['sAddress'];?> <?=$item['properties']['sComplement'];?> <?=$item['properties']['sNeighborhood'];?><br>
				<?=$item['properties']['idADMINCountry'];?> <?=$item['properties']['idADMINState'];?>, <?=$item['properties']['idADMINCity'];?><br>
				<?=$item['properties']['sPostalCode'];?></span><br>
				<?=identifyLink($item['properties']['sSite']);?><br><br>
				<?=identifyLink($sContent);?></p>
				
				<p>criado por <a href="index.php?id=<?=uniqid();?>&sob=<?=$owner['id'];?>&prob=<?=$profileObject['id'];?>"><strong><?=$owner['sDisplayName'];?></strong></a> 
				<em style="font-size:10px;color:#999;"> em <a href="index.php?id=<?=uniqid();?>&sob=<?=$item['id'];?>&prob=<?=$profileObject['id'];?>" title="permalink" style="color:#999;"><?=$itemCreation;?></a></em></p>
			</div>
			
		</article>
		
	<? } ?>
	
	<?php
		// --------------------------------------------------
		// paginação da timeline
	?>
	<a href="#" id="load-history" data-offset="25" data-idlist="<?=implode(',',$idList);?>" data-type="EVENT" title="carregar histórico mais antigo">
	<div style="
			padding:10px;
			text-align:center;
			margin:10px 0;
			background-color:#F4F4F4;
			border:1px solid #E2E2E2;
	">carregar histórico mais antigo</div></a>
	<!-- end: events -->
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
<?php
	} else if ($feed == 'info') {
?>
	
	<?php
		$properties = getProperties($socialObject['id']);
		$disabled	= 'disabled';
		if(isset($userObject) && $userObject['id'] == $socialObject['id']) {
			$disabled = '';
		}
	?>
	
	<form id="form-profile" action="javascript:updateAccount(<?=$socialObject['id'];?>, 'form#form-profile');">
		
		<fieldset>
		
			<legend>Informações pessoais</legend>
			
			<p><label class="label" for="sFullName">Nome:</label><input id="sFullName" type="text" value="<?=$properties['sFullName'];?>" <?=$disabled?>></p>
			<p><label class="label" for="sDisplayName">Exibir como:</label><input id="sDisplayName" type="text" value="<?=$socialObject['sDisplayName'];?>" <?=$disabled?>></p>
			<p><label class="label" for="sEmail">E-mail:</label><input id="sEmail" type="text" value="<?=$properties['sEmail'];?>" <?=$disabled?>></p>
			
			<p><label class="label" for="dtBirthday">Data de Nascimento:</label><input id="dtBirthday" type="text" value="<?=$properties['dtBirthday'];?>" <?=$disabled?> maxlength="10"></p>
			
			<p><label class="label" for="idADMINCountry">País:</label><select name="idADMINCountry" id="idADMINCountry" value="<?=$properties['idADMINCountry'];?>" <?=$disabled?> required>
				<option disabled value="">selecione o país</option>
				<option <?=$properties['idADMINCountry']=='BR' ? 'selected' : '';?> value="BR">Brasil</option>
			</select>
			</p>
			
			<p><label class="label" for="idADMINState">Estado onde vive:</label><select name="idADMINState" id="idADMINState" value="<?=$properties['idADMINState'];?>" <?=$disabled?> required>
				<option disabled value="">selecione o estado</option>
				<option <?=$properties['idADMINState']=='AC' ? 'selected' : '';?> value="AC">Acre</option>
				<option <?=$properties['idADMINState']=='AL' ? 'selected' : '';?> value="AL">Alagoas</option>
				<option <?=$properties['idADMINState']=='AP' ? 'selected' : '';?> value="AP">Amapá</option>
				<option <?=$properties['idADMINState']=='AM' ? 'selected' : '';?> value="AM">Amazonas</option>
				<option <?=$properties['idADMINState']=='BA' ? 'selected' : '';?> value="BA">Bahia</option>
				<option <?=$properties['idADMINState']=='CE' ? 'selected' : '';?> value="CE">Ceará</option>
				<option <?=$properties['idADMINState']=='DF' ? 'selected' : '';?> value="DF">Distrito Federal</option>
				<option <?=$properties['idADMINState']=='ES' ? 'selected' : '';?> value="ES">Espirito Santo</option>
				<option <?=$properties['idADMINState']=='GO' ? 'selected' : '';?> value="GO">Goiás</option>
				<option <?=$properties['idADMINState']=='MA' ? 'selected' : '';?> value="MA">Maranhão</option>
				<option <?=$properties['idADMINState']=='MS' ? 'selected' : '';?> value="MS">Mato Grosso do Sul</option>
				<option <?=$properties['idADMINState']=='MT' ? 'selected' : '';?> value="MT">Mato Grosso</option>
				<option <?=$properties['idADMINState']=='MG' ? 'selected' : '';?> value="MG">Minas Gerais</option>
				<option <?=$properties['idADMINState']=='PA' ? 'selected' : '';?> value="PA">Pará</option>
				<option <?=$properties['idADMINState']=='PB' ? 'selected' : '';?> value="PB">Paraíba</option>
				<option <?=$properties['idADMINState']=='PR' ? 'selected' : '';?> value="PR">Paraná</option>
				<option <?=$properties['idADMINState']=='PE' ? 'selected' : '';?> value="PE">Pernambuco</option>
				<option <?=$properties['idADMINState']=='PI' ? 'selected' : '';?> value="PI">Piauí</option>
				<option <?=$properties['idADMINState']=='RJ' ? 'selected' : '';?> value="RJ">Rio de Janeiro</option>
				<option <?=$properties['idADMINState']=='RN' ? 'selected' : '';?> value="RN">Rio Grande do Norte</option>
				<option <?=$properties['idADMINState']=='RS' ? 'selected' : '';?> value="RS">Rio Grande do Sul</option>
				<option <?=$properties['idADMINState']=='RO' ? 'selected' : '';?> value="RO">Rondônia</option>
				<option <?=$properties['idADMINState']=='RR' ? 'selected' : '';?> value="RR">Roraima</option>
				<option <?=$properties['idADMINState']=='SC' ? 'selected' : '';?> value="SC">Santa Catarina</option>
				<option <?=$properties['idADMINState']=='SP' ? 'selected' : '';?> value="SP">São Paulo</option>
				<option <?=$properties['idADMINState']=='SE' ? 'selected' : '';?> value="SE">Sergipe</option>
				<option <?=$properties['idADMINState']=='TO' ? 'selected' : '';?> value="TO">Tocantins</option>
			</select></p>
			
			<p><label class="label" for="idADMINCity">Cidade onde vive:</label><input id="idADMINCity" type="text" value="<?=$properties['idADMINCity'];?>" <?=$disabled?>></p>
			<p><label class="label" for="sPostalCode">CEP:</label><input id="sPostalCode" type="text" value="<?=$properties['sPostalCode'];?>" <?=$disabled?> maxlength="9"></p>
		
		</fieldset>
		
		<fieldset style="display:none;">
		
			<legend>Espaços espirituais que frequenta</legend>
			
			<?php if(isset($userObject) && $userObject['id'] == $socialObject['id']) { ?>
			
			<p><label class="label" for="sState">Estado:</label><input id="sState" type="text"></p>
			<p><label class="label" for="sCity">Cidade:</label><input id="sCity" type="text"></p>
			<p><label class="label" for="sType">Tipo de Espaço:</label><input id="sType" type="text"></p>
			<p><label class="label" for="sNeighborhood">Bairro:</label><input id="sNeighborhood" type="text"></p>
			
			<?php } ?>
			
			<ul id="espacos" class="page-list">
				<li class="page-item">
					<div class="photo">
						<a href="index.php?id=<?=uniqid();?>&sob=19" target="_self"><img src="img/page-default.jpg" alt="" border="0" width="50"></a>
					</div>
					<div class="name-location">
						<a href="index.php?id=<?=uniqid();?>&sob=19"><strong>Espaço Espitirual 1</strong></a><br>
						<span style="color:#999">São Paulo, SP</span>
					</div>
				</li>
				
				<li class="page-item">
					<div class="photo">
						<a href="index.php?id=<?=uniqid();?>&sob=19" target="_self"><img src="img/page-default.jpg" alt="" border="0" width="50"></a>
					</div>
					<div class="name-location">
						<a href="index.php?id=<?=uniqid();?>&sob=19"><strong>Espaço Espiritual 2</strong></a><br>
						<span style="color:#999">São Paulo, SP</span>
					</div>
				</li>
			</ul>
			
		</fieldset>
		
		<fieldset>
			
			<legend>Afinidades espirituais</legend>
			
			<?php 
				$allAffinities 		= getObjects(10,'');
				$profileAffinities 	= getAssociations($socialObject['id'],'AFFINITYFOLLOWER','AFFINITY','OBJECT');
				
				if($userObject['id'] == $socialObject['id']) {
					
					foreach($allAffinities as $allAff) {
						
						$checked = '';
						foreach($profileAffinities as $proAff) {
							
							if ($proAff['id'] == $allAff['id']) {
								$checked = 'checked';
								break;
							}
						}
						
						echo '<p><label><input type="checkbox" name="afinidades-espirituais" value="'.$allAff['id'].'" '.$checked.'>'.$allAff['sDisplayName'].'</label></p>';
					}
				} else {
					
					foreach($profileAffinities as $proAff) {
						
						echo '<p>'.$proAff['sDisplayName'].'</p>';
						
					}
					
				}
			?>
		</fieldset>
		
		<?php if(isset($userObject) && $userObject['id'] == $socialObject['id']) { ?>
			<input type="submit" class="basic-button" value="salvar alterações">
		<?php } ?>
	
	</form>
	
<?php
	} else if ($feed == 'photo') {
?>
	
	<? if (isset($userObject)) { ?>
	<!-- caixa de compartilhamento -->
	<div class="post-box">
		
		<div class="post-options">
		<p>Compartilhe seus impulsos de espiritualidade:<br>
		<a id="share-text" class="share-link" href="#">Compartilhar impulso</a> | <a id="share-photo" class="share-link active" href="#">Compartilhar foto</a></p>
		</div>
		
		<form id="share-text-form" action="javascript:postCreate(<?=$socialObject['id'];?>);" style="display:none;">
			<textarea title="escreva seus impulsos de espiritualidade..." required onkeyup="identifyYoutubeVideo(this);"></textarea>
			<input type="submit" class="share-button" value="compartilhar">
		</form>
		
		<form id="share-photo-form" method="post" enctype="multipart/form-data" action="lib/socialAPI.php?action=PhotoCreate" target="iframe-post">
			<input type="hidden" name="MAX_FILE_SIZE" value="3145728">
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
			<br><span style="font-size:11px;">tamanho máximo 3MB</span>
			<iframe name="iframe-post" width="100" height="50" style="display:none;"></iframe>
		</form>
			
		<div id="share-preview">
			<div id="share-preview-thumb"></div>
			<div id="share-preview-description"></div>
		</div>
		
	</div>
	<? } ?>
	
	
	<div class="comment-box" style="display:none;">
		<form id="comment-form" action="javascript:commentCreate();">
			<textarea required></textarea>
			<input type="submit" class="shared-button" value="comentar">
		</form>
	</div>
	
	
	<!-- begin: photos -->
	<?
	
		if(isset($from) && $from == "front-page") {
		
			$friendList = getAssociations($userObject['id'],'FRIEND','FRIENDSHIP','FRIEND','',0,0,'0');
			
			// adiciona o próprio profile à lista de posts:
			$friendList[] = array(
								"myRole" => 0, 
								"Association" => 0, 
								"AssociationType" => 0, 
								"id" => $userObject['id'], 
								"sDisplayName" => $userObject['sDisplayName'], 
								"theirRole" => 0
							);
			
			// monta uma lista com os ids de todos os amigos:
			$friendIdList = array();
			for ($i=0; $i < count($friendList); $i++) {
				$friendIdList[] = $friendList[$i]['id'];
			}
			
			// carrega todos os posts de fotos dos amigos:
			$list = getAssociations(
									$friendIdList, 
									'OWNER', 
									'PHOTO', 
									'OBJECT', 
									'o.tsCreation DESC',
									0, 25, '0'
									);

			$idList = $friendIdList;
			
		} else {
	
			// recupera do banco de dados todas as fotos:
			$list = getAssociations(
									$socialObject['id'],
									'REFERRED',
									'PHOTO',
									'OBJECT', 
									'o.tsCreation DESC',
									0, 25, '0'
									);
		}
	?>
	
	<? foreach($list as $item) { ?>
	
		<article class="post root-post" data-key="<?=$item['id']; ?>">
			
			<div class="post-photo">
				<?php
				
					$itemCreation	  = $item['tsCreation'];
					$owner			  = getAssociations($item['id'], 'OBJECT', 'PHOTO', 'OWNER');
					
					if(isset($owner[0]['id']) && isset($owner[0]['sDisplayName'])) { 
					
						$ownerId 		  = $owner[0]['id'];
						$ownerDisplayName = $owner[0]['sDisplayName'];
						
						$owner			  = getObject($ownerId);
						$ownerCreation	  = $owner['tsCreation'];
						
						$sAvatarPath = getProperties($ownerId,'sAvatarPath');
						$sAvatarPath = $sAvatarPath['sAvatarPath'];
					
					}
				?>
				<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>" target="_self">
				<img src="<?= $sAvatarPath ? $sAvatarPath : 'img/avatar-default.jpg'; ?>" alt="" border="0" width="50">
				</a>
			</div>
			
			<div class="post-info">
				<?php 
					$sDataPhoto = getProperties($item['id'],'sDataPhoto');
					if ($sDataPhoto) {
						$sDataPhoto = $sDataPhoto['sDataPhoto'];
					}
					
					$sContent = getDetails($item['id'],'sContent');
					$sContent = $sContent['sContent'];
					$sContent = str_replace(chr(13), '<br>', $sContent);
					
					$original	 = getAssociations($item['id'], 'OBJECT', array('POST','PHOTO'), 'ORIGINAL');
					if (count($original)>0) {
						$originalId 	= $original[0]['id'];
						$originalOwner 	= getAssociations($originalId, 'OBJECT', '', 'OWNER');
						if ($originalOwner) {
							$originalOwner	= getObject($originalOwner[0]['id']);
						} else {
							// o owner não existe (a conta foi bloqueada?)
							continue;
						}
					}
				?>
				
				<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>"><strong><?=$ownerDisplayName; ?></strong></a>&nbsp;
				
				<? if (count($original)>0) { ?>
					
					<em style="font-size:10px;color:#999;">compartilhou o impulso de <a href="index.php?id=<?=uniqid();?>&sob=<?=$originalId;?>&prob=<?=$originalOwner['id'];?>"><?=$originalOwner['sDisplayName']?></a> em <a href="index.php?id=<?=uniqid();?>&sob=<?=$originalId;?>&prob=<?=$originalOwner['id'];?>" title="permalink" style="color:#999;"><?=$itemCreation;?></a></em><br>
				
				<? } else { ?>
				
					<em style="font-size:10px;color:#999;">postou em <a href="index.php?id=<?=uniqid();?>&sob=<?=$item['id'];?>&prob=<?=$ownerId;?>" title="permalink" style="color:#999;"><?=$itemCreation;?></a></em><br>
				
				<? } ?>
				
				<? if(false){ ?>
				<em style="font-size:10px;color:#999;">postou em <a href="index.php?id=<?=uniqid();?>&sob=<?=$item['id'];?>&prob=<?=$ownerId;?>" title="permalink" style="color:#999;"><?=$itemCreation;?></a></em><br>
				<? } ?>
				
				<?php 
					if ($sDataPhoto) {
						
						$values = json_decode($sDataPhoto,true);
						
						$thumb 	= $values['thumb']	? $values['thumb'] 	: '';
						$link 	= $values['link']	? $values['link'] 	: '';
						
						if (count($original)>0) {
							
							echo '<div class="thumb-img" style="display:inline-block;width:120px;margin:5px 10px 0 0;padding:0;"><a href="index.php?id='.uniqid().'&sob='.$originalId.'&prob='.$originalOwner['id'].'" title="veja a imagem" target="_self"><img src="'.$thumb.'" style="width:120px;"></a></div>';
							
						} else {
							
							echo '<div class="thumb-img" style="display:inline-block;width:120px;margin:5px 10px 0 0;padding:0;"><a href="index.php?id='.uniqid().'&sob='.$item['id'].'&prob='.$ownerId.'" title="veja a imagem" target="_self"><img src="'.$thumb.'" style="width:120px;"></a></div>';
							
						}
						
					//	echo '<div class="thumb-img" style="display:inline-block;width:120px;margin:5px 10px 0 0;padding:0;"><a href="index.php?id='.uniqid().'&sob='.$item['id'].'&prob='.$ownerId.'" title="veja a imagem" target="_self"><img src="'.$thumb.'" style="width:120px;"></a></div>';
						
						echo '<div class="desc" style="display:inline-block;margin:5px 0 0 0;vertical-align:top;width:300px;">'.$sContent.'</div>';
						
					} else {
						if ($sContent) { echo $sContent; }
					}
				?>
				
				
				<br>
				
				<?php
					
					// ----------------------------------------------------------------------
					// barra de interação (gostar, comentar, compartilhar)
					// ----------------------------------------------------------------------
					
					if (isset($userObject) && $owner['fDeleted'] == 0) {
					
						echo '<div class="interaction-bar">';
						
						$like = count(likeStatus($userObject['id'],$item['id'])) > 0 ? '<a href="#" class="dislike"><strong>você gostou</strong> (desfazer)</a>' : '<a href="#" class="like">gostei</a>' ;
						
						echo $like;
						
						echo ' | <a href="#" class="create-comment">comentar</a> | <a href="#" class="share-post">compartilhar</a>';
						// <a href="#" class="report-abuse">denunciar</a>';
						
						$report = count(reportStatus($userObject['id'],$item['id'])) > 0 ? ' | <a><strong>você denunciou</strong></a>' : ' | <a href="#" class="report-abuse">denunciar</a>' ;
						
						echo $report;
						/*
						$iTotal   = getCountAssociations($item['id'],'OBJECT','','LIKER');
						if ($iTotal > 0) {
							$likeText = $iTotal > 1 ? $iTotal.' pessoas gostaram' : $iTotal.' pessoa gostou';
							echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
						}
						*/
						$iTotalLike   = getCountAssociations($item['id'],'OBJECT','','LIKER');
						if ($iTotalLike > 0) {
							$likeText = $iTotalLike > 1 ? $iTotalLike.' pessoas gostaram' : $iTotalLike.' pessoa gostou';
							echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
						}
						
						$iTotalReport   = getCountAssociations($item['id'],'REFERRED','REPORT','OWNER');
						if ($iTotalReport > 0) {
							$reportText = $iTotalReport > 1 ? $iTotalReport.' pessoas denunciaram' : $iTotalReport.' pessoa denunciou';
							echo '<br><span style="font-size:11px;color:#C00;">'.$reportText.'</span>';
						}
						
						echo '</div>';
					}
				?>
				
				<? if (false) { ?>
				<a href="#" class="like">gostei</a> | <a href="#" class="create-comment">comentar</a> | <a href="#" class="share-post">compartilhar</a> | <a href="#" class="report-abuse">denunciar</a>
				<? } ?>
			</div>
			
		<!-- </article> -->
		
		<? $listComment = getAssociations($item['id'], 'REFERRED','COMMENT','OBJECT','',0,0,'0'); ?>
		<? for ($j=0;$j<count($listComment);$j++) { ?>
		
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
					<img src="<?= $sAvatarPath ? $sAvatarPath : 'img/avatar-default.jpg'; ?>" alt="" border="0" width="30">
					</a>
				</div>
				
				<div class="post-info">
					<?
						$sContent = getDetails($listComment[$j]['id'],'sContent');
						$sContent = $sContent['sContent'];
						$sContent = str_replace(chr(13), '<br>', $sContent);
					?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>"><strong><?=$ownerDisplayName; ?></strong></a>&nbsp;
					<em style="font-size:10px;color:#999;">comentou em <a href="index.php?id=<?=uniqid();?>&sob=<?=$listComment[$j]['id'];?>&prob=<?=$ownerId;?>" title="permalink" style="color:#999;"><?=$ownerCreation;?></a></em><br>
					<?=$sContent ? $sContent : ''; ?>
					<br>
					
					<?php
						
						// ----------------------------------------------------------------------
						// barra de interação (gostar, comentar, compartilhar) do comentário
						// ----------------------------------------------------------------------
						
						if (isset($userObject) && $owner['fDeleted'] == 0) {
						
							echo '<div class="interaction-bar">';
							
							$like = count(likeStatus($userObject['id'],$listComment[$j]['id'])) > 0 ? '<a href="#" class="dislike"><strong>você gostou</strong> (desfazer)</a>' : '<a href="#" class="like">gostei</a>' ;
							
							echo $like;
							
							echo ' | <a href="#" class="create-comment">comentar</a>';
							// <a href="#" class="report-abuse">denunciar</a>';
							
							$report = count(reportStatus($userObject['id'],$listComment[$j]['id'])) > 0 ? ' | <a><strong>você denunciou</strong></a>' : ' | <a href="#" class="report-abuse">denunciar</a>' ;
							
							echo $report;
							/*
							$iTotal   = getCountAssociations($listComment[$j]['id'],'OBJECT','','LIKER');
							if ($iTotal > 0) {
								$likeText = $iTotal > 1 ? $iTotal.' pessoas gostaram' : $iTotal.' pessoa gostou';
								echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
							}
							*/
							$iTotalLike   = getCountAssociations($listComment[$j]['id'],'OBJECT','','LIKER');
							if ($iTotalLike > 0) {
								$likeText = $iTotalLike > 1 ? $iTotalLike.' pessoas gostaram' : $iTotalLike.' pessoa gostou';
								echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
							}
							
							$iTotalReport   = getCountAssociations($listComment[$j]['id'],'REFERRED','REPORT','OWNER');
							if ($iTotalReport > 0) {
								$reportText = $iTotalReport > 1 ? $iTotalReport.' pessoas denunciaram' : $iTotalReport.' pessoa denunciou';
								echo '<br><span style="font-size:11px;color:#C00;">'.$reportText.'</span>';
							}
							
							echo '</div>';
						}
					?>
					
					<? if (false) { ?>
					<a href="#" class="like">gostei</a> | <a href="#" class="create-comment">comentar</a> | <a href="#" class="share-post">compartilhar</a> | <a href="#" class="report-abuse">denunciar</a>
					<? } ?>
				</div>
				
			</article>
		
		<? } ?>
		
		</article>
		
	<? } ?>
	
	<?php
		// --------------------------------------------------
		// paginação da timeline
	?>
	<a href="#" id="load-history" data-offset="25" data-idlist="<?=implode(',',$idList);?>" data-type="PHOTO" title="carregar histórico mais antigo">
	<div style="
			padding:10px;
			text-align:center;
			margin:10px 0;
			background-color:#F4F4F4;
			border:1px solid #E2E2E2;
	">carregar histórico mais antigo</div></a>
	
	<!-- end: photos -->
	


<?php
	} else if ($feed == 'video') {
?>
	
	<? if (isset($userObject)) { ?>
	<!-- caixa de compartilhamento -->
	<div class="post-box">
		
		<div class="post-options">
		<p>Compartilhe seus impulsos de espiritualidade:<br>
		<a id="share-text" class="share-link active" href="#">Compartilhar impulso</a> | <a id="share-photo" class="share-link" href="#">Compartilhar foto</a></p>
		</div>
		
		<form id="share-text-form" action="javascript:postCreate(<?=$socialObject['id'];?>);">
			<textarea title="escreva seus impulsos de espiritualidade..." required onkeyup="identifyYoutubeVideo(this);"></textarea>
			<input type="submit" class="share-button" value="compartilhar">
		</form>
		
		<form id="share-photo-form" method="post" enctype="multipart/form-data" action="lib/socialAPI.php?action=PhotoCreate" target="iframe-post" style="display:none;">
			<input type="hidden" name="MAX_FILE_SIZE" value="3145728">
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
			<br><span style="font-size:11px;">tamanho máximo 3MB</span>
			<iframe name="iframe-post" width="100" height="50" style="display:none;"></iframe>
		</form>
			
		<div id="share-preview">
			<div id="share-preview-thumb"></div>
			<div id="share-preview-description"></div>
		</div>
		
	</div>
	<? } ?>
	
	
	<div class="comment-box" style="display:none;">
		<form id="comment-form" action="javascript:commentCreate();">
			<textarea required></textarea>
			<input type="submit" class="shared-button" value="comentar">
		</form>
	</div>
	
	
	<!-- begin: videos -->
	<?
	
		if(isset($from) && $from == "front-page") {
		
			$friendList = getAssociations($userObject['id'],'FRIEND','FRIENDSHIP','FRIEND','',0,0,'0');
			
			// adiciona o próprio profile à lista de posts:
			$friendList[] = array(
								"myRole" => 0, 
								"Association" => 0, 
								"AssociationType" => 0, 
								"id" => $userObject['id'], 
								"sDisplayName" => $userObject['sDisplayName'], 
								"theirRole" => 0
							);
			
			// monta uma lista com os ids de todos os amigos:
			$friendIdList = array();
			for ($i=0; $i < count($friendList); $i++) {
				$friendIdList[] = $friendList[$i]['id'];
			}
			
			// carrega todos os posts dos amigos:
			$list = getAssociations(
									$friendIdList, 
									'OWNER', 
									'POST', 
									'OBJECT', 
									'o.tsCreation DESC',
									0, 50, '0'
									);
			
			$idList = $friendIdList;
			
		} else {
	
			// recupera do banco de dados todos os posts:
			$list = getAssociations(
									$socialObject['id'],
									'REFERRED',
									'POST',
									'OBJECT', 
									'o.tsCreation DESC',
									0, 50, '0'
									);
			
			$idList = $socialObject['id'];
		}
	?>
	
	<?php
		
		foreach($list as $item) {
			$itemCreation	  = $item['tsCreation'];
			$owner			  = getAssociations($item['id'], 'OBJECT', 'POST', 'OWNER','',0,0,'0');
			
			if (isset($owner[0]['id']) && isset($owner[0]['sDisplayName'])) {
			
				$ownerId 		  = $owner[0]['id'];
				$ownerDisplayName = $owner[0]['sDisplayName'];
				
				$owner			  = getObject($ownerId);
				$ownerCreation	  = $owner['tsCreation'];
				
				$sAvatarPath = getProperties($ownerId,'sAvatarPath');
				$sAvatarPath = $sAvatarPath['sAvatarPath'];
			}
			
			$sDataVideo = getProperties($item['id'],'sDataVideo');
			if ($sDataVideo['sDataVideo']) {
				$sDataVideo = $sDataVideo['sDataVideo'];
			} else {
				continue;
			}
			
			$sContent = getDetails($item['id'],'sContent');
			$sContent = $sContent['sContent'];
			$sContent = str_replace(chr(13), '<br>', $sContent);
			
			$original	 = getAssociations($item['id'], 'OBJECT', 'POST', 'ORIGINAL');
			if (count($original)>0) {
				$originalId 	= $original[0]['id'];
				$originalOwner 	= getAssociations($originalId, 'OBJECT', '', 'OWNER');
				if ($originalOwner) {
					$originalOwner	= getObject($originalOwner[0]['id']);
				} else {
					// o owner não existe (a conta foi bloqueada?)
					continue;
				}
			}
	?>
	
		<article class="post root-post" data-key="<?=$item['id']; ?>">
			
			<div class="post-photo">
				<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>" target="_self">
				<img src="<?= $sAvatarPath ? $sAvatarPath : 'img/avatar-default.jpg'; ?>" alt="" border="0" width="50">
				</a>
			</div>
			
			<div class="post-info">
				
				<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>"><strong><?=$ownerDisplayName; ?></strong></a>&nbsp;
				
				<? if (count($original)>0) { ?>
					
					<em style="font-size:10px;color:#999;">compartilhou o impulso de <a href="index.php?id=<?=uniqid();?>&sob=<?=$originalId;?>&prob=<?=$originalOwner['id'];?>"><?=$originalOwner['sDisplayName']?></a> em <a href="index.php?id=<?=uniqid();?>&sob=<?=$originalId;?>&prob=<?=$originalOwner['id'];?>" title="permalink" style="color:#999;"><?=$itemCreation;?></a></em><br>
				
				<? } else { ?>
				
					<em style="font-size:10px;color:#999;">postou em <a href="index.php?id=<?=uniqid();?>&sob=<?=$item['id'];?>&prob=<?=$ownerId;?>" title="permalink" style="color:#999;"><?=$itemCreation;?></a></em><br>
				
				<? } ?>
				
				<? if(false){ ?>
				<em style="font-size:10px;color:#999;">postou em <a href="index.php?id=<?=uniqid();?>&sob=<?=$item['id'];?>&prob=<?=$ownerId;?>" title="permalink" style="color:#999;"><?=$itemCreation;?></a></em><br>
				<? } ?>
				
				<?php 
					if ($sDataVideo) {
						
						$values = json_decode($sDataVideo,true);
						$thumb 	= $values['thumb']	? $values['thumb'] 	: '';
						$link 	= $values['link']	? $values['link'] 	: '';
						$id		= $values['id']		? $values['id'] 	: '';
						
						if ($id != '') {
							
							echo '<div class="thumb" style="display:inline-block;width:120px;margin:5px 10px 0 0;padding:0;"><a href="#" title="assista ao vídeo" data-yvid="'.$id.'"><img src="'.$thumb.'"></a></div>';
							
							echo '<div class="desc" style="display:inline-block;margin:5px 0 0 0;vertical-align:top;width:300px;">'.$sContent.'</div>';
							
							echo '<div class="embed" style="display:none;margin:5px 0;"></div>';
							
						}
						
					} else {
						if ($sContent) { echo $sContent; }
					}
				?>
				
				
				<br>
				
				<?php
					
					// ----------------------------------------------------------------------
					// barra de interação (gostar, comentar, compartilhar)
					// ----------------------------------------------------------------------
					
					if (isset($userObject) && $owner['fDeleted'] == 0) {
					
						echo '<div class="interaction-bar">';
						
						$like = count(likeStatus($userObject['id'],$item['id'])) > 0 ? '<a href="#" class="dislike"><strong>você gostou</strong> (desfazer)</a>' : '<a href="#" class="like">gostei</a>' ;
						
						echo $like;
						
						echo ' | <a href="#" class="create-comment">comentar</a> | <a href="#" class="share-post">compartilhar</a>';
						// <a href="#" class="report-abuse">denunciar</a>';
						
						$report = count(reportStatus($userObject['id'],$item['id'])) > 0 ? ' | <a><strong>você denunciou</strong></a>' : ' | <a href="#" class="report-abuse">denunciar</a>' ;
						
						echo $report;
						/*
						$iTotal   = getCountAssociations($item['id'],'OBJECT','','LIKER');
						if ($iTotal > 0) {
							$likeText = $iTotal > 1 ? $iTotal.' pessoas gostaram' : $iTotal.' pessoa gostou';
							echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
						}
						*/
						$iTotalLike   = getCountAssociations($item['id'],'OBJECT','','LIKER');
						if ($iTotalLike > 0) {
							$likeText = $iTotalLike > 1 ? $iTotalLike.' pessoas gostaram' : $iTotalLike.' pessoa gostou';
							echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
						}
						
						$iTotalReport   = getCountAssociations($item['id'],'REFERRED','REPORT','OWNER');
						if ($iTotalReport > 0) {
							$reportText = $iTotalReport > 1 ? $iTotalReport.' pessoas denunciaram' : $iTotalReport.' pessoa denunciou';
							echo '<br><span style="font-size:11px;color:#C00;">'.$reportText.'</span>';
						}
						
						echo '</div>';
					}
				?>
				
			</div>
			
		<!-- </article> -->
		
		<? $listComment = getAssociations($item['id'], 'REFERRED','COMMENT','OBJECT','',0,0,'0'); ?>
		<? for ($j=0;$j<count($listComment);$j++) { ?>
		
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
					<img src="<?= $sAvatarPath ? $sAvatarPath : 'img/avatar-default.jpg'; ?>" alt="" border="0" width="30">
					</a>
				</div>
				
				<div class="post-info">
					<?
						$sContent = getDetails($listComment[$j]['id'],'sContent');
						$sContent = $sContent['sContent'];
						$sContent = str_replace(chr(13), '<br>', $sContent);
					?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>"><strong><?=$ownerDisplayName; ?></strong></a>&nbsp;
					<em style="font-size:10px;color:#999;">comentou em <a href="index.php?id=<?=uniqid();?>&sob=<?=$listComment[$j]['id'];?>&prob=<?=$ownerId;?>" title="permalink" style="color:#999;"><?=$ownerCreation;?></a></em><br>
					<?=$sContent ? $sContent : ''; ?>
					<br>
					
					<?php
						
						// ----------------------------------------------------------------------
						// barra de interação (gostar, comentar, compartilhar) do comentário
						// ----------------------------------------------------------------------
						
						if (isset($userObject) && $owner['fDeleted'] == 0) {
						
							echo '<div class="interaction-bar">';
							
							$like = count(likeStatus($userObject['id'],$listComment[$j]['id'])) > 0 ? '<a href="#" class="dislike"><strong>você gostou</strong> (desfazer)</a>' : '<a href="#" class="like">gostei</a>' ;
							
							echo $like;
							
							echo ' | <a href="#" class="create-comment">comentar</a>';
							// <a href="#" class="report-abuse">denunciar</a>';
							
							$report = count(reportStatus($userObject['id'],$listComment[$j]['id'])) > 0 ? ' | <a><strong>você denunciou</strong></a>' : ' | <a href="#" class="report-abuse">denunciar</a>' ;
							
							echo $report;
							/*
							$iTotal   = getCountAssociations($listComment[$j]['id'],'OBJECT','','LIKER');
							if ($iTotal > 0) {
								$likeText = $iTotal > 1 ? $iTotal.' pessoas gostaram' : $iTotal.' pessoa gostou';
								echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
							}
							*/
							$iTotalLike   = getCountAssociations($listComment[$j]['id'],'OBJECT','','LIKER');
							if ($iTotalLike > 0) {
								$likeText = $iTotalLike > 1 ? $iTotalLike.' pessoas gostaram' : $iTotalLike.' pessoa gostou';
								echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
							}
							
							$iTotalReport   = getCountAssociations($listComment[$j]['id'],'REFERRED','REPORT','OWNER');
							if ($iTotalReport > 0) {
								$reportText = $iTotalReport > 1 ? $iTotalReport.' pessoas denunciaram' : $iTotalReport.' pessoa denunciou';
								echo '<br><span style="font-size:11px;color:#C00;">'.$reportText.'</span>';
							}
							
							echo '</div>';
						}
					?>
					
				</div>
				
			</article>
		
		<? } ?>
		
		</article>
		
	<? } ?>
	
	<?php
		// --------------------------------------------------
		// paginação da timeline
	?>
	<a href="#" id="load-history" data-offset="50" data-idlist="<?=implode(',',$idList);?>" data-type="POST" title="carregar histórico mais antigo">
	<div style="
			padding:10px;
			text-align:center;
			margin:10px 0;
			background-color:#F4F4F4;
			border:1px solid #E2E2E2;
	">carregar histórico mais antigo</div></a>
	
	<!-- end: videos -->



<?php
	} else if ($feed == 'page') {
		
		// página inicial
		if(isset($from) && $from == "front-page") {
		
?>		
		
		<? if (
			(isset($userObject) 	&& !isset($socialObject) && !isset($profileObject)) || 
			(isset($socialObject) 	&& $socialObject['iType'] == 2 && $userObject['id'] == $profileObject['id']) || 
			(isset($socialObject) 	&& $socialObject['iType'] == 1)
			) { ?>
		<!-- caixa de compartilhamento -->
		<div class="post-box">
			
			<div class="post-options">
			<p>Compartilhe seus impulsos de espiritualidade:<br>
			<a id="share-text" class="share-link active" href="#">Compartilhar impulso</a> | <a id="share-photo" class="share-link" href="#">Compartilhar foto</a></p>
			</div>
			
			<form id="share-text-form" action="javascript:postCreate(<?= isset($socialObject['id']) ? $socialObject['id'] : $userObject['id'] ;?>);">
				<textarea title="escreva seus impulsos de espiritualidade..." required onkeyup="identifyYoutubeVideo(this);"></textarea>
				<input type="submit" class="share-button" value="compartilhar">
			</form>
			
			<form id="share-photo-form" method="post" enctype="multipart/form-data" action="lib/socialAPI.php?action=PhotoCreate" target="iframe-post" style="display:none;">
				<input type="hidden" name="MAX_FILE_SIZE" value="3145728">
				<input type="hidden" name="idReferredObject" value="<?= isset($socialObject['id']) ? $socialObject['id'] : $userObject['id'] ;?>">
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
				<br><span style="font-size:11px;">tamanho máximo 3MB</span>
				<iframe name="iframe-post" width="100" height="50" style="display:none;"></iframe>
			</form>
				
			<div id="share-preview">
				<div id="share-preview-thumb"></div>
				<div id="share-preview-description"></div>
			</div>
			
		</div>
		<? } ?>
		
		
		<div class="comment-box" style="display:none;">
			<form id="comment-form" action="javascript:commentCreate();">
				<textarea required></textarea>
				<input type="submit" class="shared-button" value="comentar">
			</form>
		</div>
		
<?php 
			$pageList = getAssociations($userObject['id'],array('OWNER','FOLLOWER'),'PAGE','OBJECT','',0,0,'0');
			
			// monta uma lista com os ids de todas as páginas:
			$pageIdList = array();
			for ($i=0; $i < count($pageList); $i++) {
				$pageIdList[] = $pageList[$i]['id'];
			}
			
			// carrega todos os posts dos amigos:
			$list = getAssociations(
									$pageIdList, 
									array('OWNER','REFERRED'), 
									array('POST','PHOTO'), 
									'OBJECT', 
									'o.tsCreation DESC',
									0,0,'0'
									);
			
			$idList = $pageIdList;
			
			foreach($list as $item) {
?>
	
		<article class="post root-post" data-key="<?=$item['id'];?>">
			
			<div class="post-photo">
				<?php
				
					$itemCreation	  = $item['tsCreation'];
					$owner			  = getAssociations($item['id'], 'OBJECT', 'POST', 'OWNER');
					
					if(isset($owner[0]['id']) && isset($owner[0]['sDisplayName'])) { 
					
						$ownerId 		  = $owner[0]['id'];
						$ownerDisplayName = $owner[0]['sDisplayName'];
						
						$owner			  = getObject($ownerId);
						$ownerCreation	  = $owner['tsCreation'];
						
						$sAvatarPath = getProperties($ownerId,'sAvatarPath');
						$sAvatarPath = $sAvatarPath['sAvatarPath'];
						
						$original	 = getAssociations($item['id'], 'OBJECT', array('POST','PHOTO'), 'ORIGINAL');
						if (count($original)>0) {
							$originalId 	= $original[0]['id'];
							$originalOwner 	= getAssociations($originalId, 'OBJECT', '', 'OWNER');
							if ($originalOwner) {
								$originalOwner	= getObject($originalOwner[0]['id']);
							} else {
								// o owner não existe (a conta foi bloqueada?)
								continue;
							}
						}
						
					}
				?>
				<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>" target="_self">
				<img src="<?= $sAvatarPath ? $sAvatarPath : 'img/avatar-default.jpg'; ?>" alt="" border="0" width="50">
				</a>
			</div>
			
			<div class="post-info">
				<?php 
					$sDataPhoto = getProperties($item['id'],'sDataPhoto');
					if ($sDataPhoto) {
						$sDataPhoto = $sDataPhoto['sDataPhoto'];
					}
					
					$sContent = getDetails($item['id'],'sContent');
					$sContent = $sContent['sContent'];
					$sContent = str_replace(chr(13), '<br>', $sContent);
				?>
				<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>"><strong><?=$ownerDisplayName; ?></strong></a>&nbsp;
				
				<? if (count($original)>0) { ?>
				
					<em style="font-size:10px;color:#999;">compartilhou o impulso de <a href="index.php?id=<?=uniqid();?>&sob=<?=$originalId;?>&prob=<?=$originalOwner['id'];?>"><?=$originalOwner['sDisplayName']?></a> em <a href="index.php?id=<?=uniqid();?>&sob=<?=$originalId;?>&prob=<?=$originalOwner['id'];?>" title="permalink" style="color:#999;"><?=$itemCreation;?></a></em><br>
				
				<? } else { ?>
				
					<em style="font-size:10px;color:#999;">postou em <a href="index.php?id=<?=uniqid();?>&sob=<?=$item['id'];?>&prob=<?=$ownerId;?>" title="permalink" style="color:#999;"><?=$itemCreation;?></a></em><br>
				
				<? } ?>
				
				<? if(false){ ?>
				<em style="font-size:10px;color:#999;">postou em <a href="index.php?id=<?=uniqid();?>&sob=<?=$item['id'];?>&prob=<?=$ownerId;?>" title="permalink" style="color:#999;"><?=$itemCreation;?></a></em><br>
				<? } ?>
				
				<?php 
					if ($sDataPhoto) {
						
						$values = json_decode($sDataPhoto,true);
						
						$thumb 	= $values['thumb']	? $values['thumb'] 	: '';
						$link 	= $values['link']	? $values['link'] 	: '';
						
						if (count($original)>0) {
							
							echo '<div class="thumb-img" style="display:inline-block;width:120px;margin:5px 10px 0 0;padding:0;"><a href="index.php?id='.uniqid().'&sob='.$originalId.'&prob='.$originalOwner['id'].'" title="veja a imagem" target="_self"><img src="'.$thumb.'" style="width:120px;"></a></div>';
							
						} else {
							
							echo '<div class="thumb-img" style="display:inline-block;width:120px;margin:5px 10px 0 0;padding:0;"><a href="index.php?id='.uniqid().'&sob='.$item['id'].'&prob='.$ownerId.'" title="veja a imagem" target="_self"><img src="'.$thumb.'" style="width:120px;"></a></div>';
							
						}
						
					//	echo '<div class="thumb-img" style="display:inline-block;width:120px;margin:5px 10px 0 0;padding:0;"><a href="index.php?id='.uniqid().'&sob='.$item['id'].'&prob='.$ownerId.'" title="veja a imagem" target="_self"><img src="'.$thumb.'" style="width:120px;"></a></div>';
						
						echo '<div class="desc" style="display:inline-block;margin:5px 0 0 0;vertical-align:top;width:300px;">'.$sContent.'</div>';
						
					} else {
						if ($sContent) { echo $sContent; }
					}
				?>
				
				
				<br>
				
				<?php
					
					// ----------------------------------------------------------------------
					// barra de interação (gostar, comentar, compartilhar)
					// ----------------------------------------------------------------------
					
					if (isset($userObject) && $owner['fDeleted'] == 0) {
					
						echo '<div class="interaction-bar">';
						
						$like = count(likeStatus($userObject['id'],$item['id'])) > 0 ? '<a href="#" class="dislike"><strong>você gostou</strong> (desfazer)</a>' : '<a href="#" class="like">gostei</a>' ;
						
						echo $like;
						
						echo ' | <a href="#" class="create-comment">comentar</a> | <a href="#" class="share-post">compartilhar</a>';
						// <a href="#" class="report-abuse">denunciar</a>';
						
						$report = count(reportStatus($userObject['id'],$item['id'])) > 0 ? ' | <a><strong>você denunciou</strong></a>' : ' | <a href="#" class="report-abuse">denunciar</a>' ;
						
						echo $report;
						/*
						$iTotal   = getCountAssociations($item['id'],'OBJECT','','LIKER');
						if ($iTotal > 0) {
							$likeText = $iTotal > 1 ? $iTotal.' pessoas gostaram' : $iTotal.' pessoa gostou';
							echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
						}
						*/
						$iTotalLike   = getCountAssociations($item['id'],'OBJECT','','LIKER');
						if ($iTotalLike > 0) {
							$likeText = $iTotalLike > 1 ? $iTotalLike.' pessoas gostaram' : $iTotalLike.' pessoa gostou';
							echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
						}
						
						$iTotalReport   = getCountAssociations($item['id'],'REFERRED','REPORT','OWNER');
						if ($iTotalReport > 0) {
							$reportText = $iTotalReport > 1 ? $iTotalReport.' pessoas denunciaram' : $iTotalReport.' pessoa denunciou';
							echo '<br><span style="font-size:11px;color:#C00;">'.$reportText.'</span>';
						}
						
						echo '</div>';
					}
				?>
				
				<? if (false) { ?>
				<a href="#" class="like">gostei</a> | <a href="#" class="create-comment">comentar</a> | <a href="#" class="share-post">compartilhar</a> | <a href="#" class="report-abuse">denunciar</a>
				<? } ?>
			</div>
		
		<? $listComment = getAssociations($item['id'], 'REFERRED','COMMENT','OBJECT','',0,0,'0'); ?>
		<? for ($j=0;$j<count($listComment);$j++) { ?>
		
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
						
						$original	 = getAssociations($item['id'], 'OBJECT', array('POST','PHOTO'), 'ORIGINAL');
						if (count($original)>0) {
							$originalId 	= $original[0]['id'];
							$originalOwner 	= getAssociations($originalId, 'OBJECT', '', 'OWNER');
							if ($originalOwner) {
								$originalOwner	= getObject($originalOwner[0]['id']);
							} else {
								// o owner não existe (a conta foi bloqueada?)
								continue;
							}
						}
						
					?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>" target="_self">
					<img src="<?= $sAvatarPath ? $sAvatarPath : 'img/avatar-default.jpg'; ?>" alt="" border="0" width="30">
					</a>
				</div>
				
				<div class="post-info">
					<?
						$sContent = getDetails($listComment[$j]['id'],'sContent');
						$sContent = $sContent['sContent'];
						$sContent = str_replace(chr(13), '<br>', $sContent);
					?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$ownerId;?>&prob=<?=$ownerId;?>"><strong><?=$ownerDisplayName; ?></strong></a>&nbsp;
					
					<? if (count($original)>0) { ?>
					
						<em style="font-size:10px;color:#999;">compartilhou o impulso de <a href="index.php?id=<?=uniqid();?>&sob=<?=$originalId;?>&prob=<?=$originalOwner['id'];?>"><?=$originalOwner['sDisplayName']?></a> em <a href="index.php?id=<?=uniqid();?>&sob=<?=$originalId;?>&prob=<?=$originalOwner['id'];?>" title="permalink" style="color:#999;"><?=$itemCreation;?></a></em><br>
					
					<? } else { ?>
					
						<em style="font-size:10px;color:#999;">postou em <a href="index.php?id=<?=uniqid();?>&sob=<?=$item['id'];?>&prob=<?=$ownerId;?>" title="permalink" style="color:#999;"><?=$itemCreation;?></a></em><br>
					
					<? } ?>
					
					<? if(false){ ?>
					<em style="font-size:10px;color:#999;">comentou em <a href="index.php?id=<?=uniqid();?>&sob=<?=$item['id'];?>&prob=<?=$ownerId;?>" title="permalink" style="color:#999;"><?=$ownerCreation;?></a></em><br>
					<?=$sContent ? $sContent : ''; ?>
					<br>
					<? } ?>
					
					<?php
						
						// ----------------------------------------------------------------------
						// barra de interação (gostar, comentar, compartilhar)
						// ----------------------------------------------------------------------
						
						if (isset($userObject) && $owner['fDeleted'] == 0) {
						
							echo '<div class="interaction-bar">';
							
							$like = count(likeStatus($userObject['id'],$item['id'])) > 0 ? '<a href="#" class="dislike"><strong>você gostou</strong> (desfazer)</a>' : '<a href="#" class="like">gostei</a>' ;
							
							echo $like;
							
							echo ' | <a href="#" class="create-comment">comentar</a>';
							// <a href="#" class="report-abuse">denunciar</a>';
							
							$report = count(reportStatus($userObject['id'],$item['id'])) > 0 ? ' | <a><strong>você denunciou</strong></a>' : ' | <a href="#" class="report-abuse">denunciar</a>' ;
							
							echo $report;
							/*
							$iTotal   = getCountAssociations($item['id'],'OBJECT','','LIKER');
							if ($iTotal > 0) {
								$likeText = $iTotal > 1 ? $iTotal.' pessoas gostaram' : $iTotal.' pessoa gostou';
								echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
							}
							*/
							$iTotalLike   = getCountAssociations($item['id'],'OBJECT','','LIKER');
							if ($iTotalLike > 0) {
								$likeText = $iTotalLike > 1 ? $iTotalLike.' pessoas gostaram' : $iTotalLike.' pessoa gostou';
								echo '<br><span style="font-size:11px;">'.$likeText.'</span>';
							}
							
							$iTotalReport   = getCountAssociations($item['id'],'REFERRED','REPORT','OWNER');
							if ($iTotalReport > 0) {
								$reportText = $iTotalReport > 1 ? $iTotalReport.' pessoas denunciaram' : $iTotalReport.' pessoa denunciou';
								echo '<br><span style="font-size:11px;color:#C00;">'.$reportText.'</span>';
							}
							
							echo '</div>';
						}
					?>
					
					<? if (false) { ?>
					<a href="#" class="like">gostei</a> | <a href="#" class="create-comment">comentar</a> | <a href="#" class="share-post">compartilhar</a> | <a href="#" class="report-abuse">denunciar</a>
					<? } ?>
				</div>
				
			</article>
		
		<? } ?>
		
		</article>
	
	<? } ?>
	
	<?php
		// --------------------------------------------------
		// paginação da timeline
	?>
	<a href="#" id="load-history" data-offset="25" data-idlist="<?=implode(',',$idList);?>" data-type="POST,PHOTO" title="carregar histórico mais antigo">
	<div style="
			padding:10px;
			text-align:center;
			margin:10px 0;
			background-color:#F4F4F4;
			border:1px solid #E2E2E2;
	">carregar histórico mais antigo</div></a>
	
	<!-- end: posts -->
	
	
<?
	// página do perfil
	} else {
?>
	
	<!-- begin: my pages -->
	<h2>Minhas páginas</h2>
	<?
		$list = getAssociations($socialObject['id'],'OWNER','PAGE','OBJECT','',0,0,'0');
		if (count($list) <= 0) {
			print('<p>Você ainda não criou nenhuma página</p>');
		}
	?>
	
	<? for ($i=0;$i<count($list);$i++) { ?>
		
	<div class="page">
		<div>
			<div class="photo">
				<?
					$sAvatarPath = getProperties($list[$i]['id'],'sAvatarPath');
					$sAvatarPath = $sAvatarPath['sAvatarPath'];
				?>
				<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$socialObject['id'];?>" target="_self">
				<img src="<?= $sAvatarPath ? $sAvatarPath : 'img/page-default.jpg'; ?>" alt="" border="0" width="50">
				</a>
			</div>
			
			<div class="name-location">
				<?
					// $sContent = $list[$i]['sDisplayName'];
				?>
				<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$socialObject['id'];?>"><strong><?=$list[$i]['sDisplayName'];?></strong></a>
				<br>
				<span style="color:#999">
				<?php 
					$idADMINCity = getProperties($list[$i]['id'],'idADMINCity');
					$idADMINCity = $idADMINCity['idADMINCity'];
				
					$idADMINState = getProperties($list[$i]['id'],'idADMINState');
					$idADMINState = $idADMINState['idADMINState'];
					
					echo ($idADMINCity != '' ? $idADMINCity : '' );
					echo ($idADMINCity != '' && $idADMINState != '' ? ', ' : '');
					echo ($idADMINState != '' ? $idADMINState : '');
				?>
				</span>
			</div>
		</div>
	</div>
		
	<? } ?>
	<!-- end: my pages -->
	
	
	<!-- begin: pages i follow -->
	<h2>Páginas que sigo</h2>
	<?
		$list = getAssociations($socialObject['id'], 'FOLLOWER','PAGE','OBJECT','',0,0,'0');
		if (count($list) <= 0) {
			print('<p>Você ainda não está seguindo nenhuma página</p>');
		}
	?>
	
	<? for ($i=0;$i<count($list);$i++) { ?>
		
	<div class="page">
		<div>
			<div class="photo">
				<?
					$sAvatarPath = getProperties($list[$i]['id'],'sAvatarPath');
					$sAvatarPath = $sAvatarPath['sAvatarPath'];
					
					$assoc	 = getAssociations($list[$i]['id'],'OBJECT','PAGE','OWNER');
					$assoc	 = $assoc[0];
					$idOwner = getObject($assoc['id']);
					$idOwner = $idOwner['id'];
					
				?>
				<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$idOwner;?>" target="_self">
				<img src="<?= $sAvatarPath ? $sAvatarPath : 'img/page-default.jpg'; ?>" alt="" border="0" width="50">
				</a>
			</div>
			
			<div class="name-location">
				<?
					// $sContent = $list[$i]['sDisplayName'];
				?>
				<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$idOwner;?>"><strong><?=$list[$i]['sDisplayName'];?></strong></a>
				<br>
				<span style="color:#999">
				<?php 
					$idADMINCity = getProperties($list[$i]['id'],'idADMINCity');
					$idADMINCity = $idADMINCity['idADMINCity'];
				
					$idADMINState = getProperties($list[$i]['id'],'idADMINState');
					$idADMINState = $idADMINState['idADMINState'];
					
					echo ($idADMINCity != '' ? $idADMINCity : '' );
					echo ($idADMINCity != '' && $idADMINState != '' ? ', ' : '');
					echo ($idADMINState != '' ? $idADMINState : '');
				?>
				</span>
			</div>
		</div>
	</div>
		
	<? } ?>
	<!-- end: pages i follow -->
	
	<?php } ?>
	
<?php
	} else if ($feed == 'group') {
?>
	
	<!-- begin: my groups -->
	<h2>Meus grupos</h2>
	
	<?
		$list = getAssociations($socialObject['id'],'OWNER','GROUP','OBJECT','',0,0,'0');
		if (count($list) <= 0) {
			print('<p>Você ainda não criou nenhum grupo</p>');
		}
	?>
	
	<? for ($i=0;$i<count($list);$i++) { ?>
		
		<div class="page">
			<div>
				<div class="photo">
					<?php
						if (true) {
						$sAvatarPath = getProperties($list[$i]['id'],'sAvatarPath');
						$sAvatarPath = $sAvatarPath['sAvatarPath'];
						
						$assoc	 = getAssociations($list[$i]['id'],'OBJECT','GROUP','OWNER');
						$assoc	 = $assoc[0];
						$idOwner = getObject($assoc['id']);
						$idOwner = $idOwner['id'];
						}
					?>
					<? if (true) { ?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$idOwner;?>" target="_self">
					<img src="<?= $sAvatarPath ? $sAvatarPath : 'img/group-default.jpg'; ?>" alt="" border="0" width="50">
					</a>
					<? } ?>
				</div>
				
				<div class="name-location">
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$profileObject['id'];?>"><strong><?=$list[$i]['sDisplayName'];?></strong></a>
					<br>
					<span style="color:#999">
					<?php 
						if (false) {
						$idADMINCity = getProperties($list[$i]['id'],'idADMINCity');
						$idADMINCity = $idADMINCity['idADMINCity'];
					
						$idADMINState = getProperties($list[$i]['id'],'idADMINState');
						$idADMINState = $idADMINState['idADMINState'];
						
						echo ($idADMINCity  != '' ? $idADMINCity : '' );
						echo ($idADMINCity  != '' && $idADMINState != '' ? ', ' : '');
						echo ($idADMINState != '' ? $idADMINState : '');
						}
					?>
					</span>
				</div>
			</div>
		</div>
		
	<? } ?>
	<!-- end: my groups -->
	
	
	
	<!-- begin: groups i follow -->
	<h2>Grupos que participo</h2>
	
	<?
		$list = getAssociations($socialObject['id'],'MEMBER','GROUP','OBJECT','',0,0,'0');
		if (count($list) <= 0) {
			print('<p>Você ainda não participa de nenhum grupo</p>');
		}
	?>
	
	<? for ($i=0;$i<count($list);$i++) { ?>
		
		<div class="page">
			<div>
				<div class="photo">
					<?
						if (true) {
						$sAvatarPath = getProperties($list[$i]['id'],'sAvatarPath');
						$sAvatarPath = $sAvatarPath['sAvatarPath'];
						
						$assoc	 = getAssociations($list[$i]['id'],'OBJECT','GROUP','OWNER');
						$assoc	 = $assoc[0];
						$idOwner = getObject($assoc['id']);
						$idOwner = $idOwner['id'];
						}
					?>
					<? if (true) { ?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$idOwner;?>" target="_self">
					<img src="<?= $sAvatarPath ? $sAvatarPath : 'img/group-default.jpg'; ?>" alt="" border="0" width="50">
					</a>
					<? } ?>
				</div>
				
				<div class="name-location">
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$profileObject['id'];?>"><strong><?=$list[$i]['sDisplayName'];?></strong></a>
					<br>
					<span style="color:#999">
					<?php 
						if (false) {
						$idADMINCity = getProperties($list[$i]['id'],'idADMINCity');
						$idADMINCity = $idADMINCity['idADMINCity'];
					
						$idADMINState = getProperties($list[$i]['id'],'idADMINState');
						$idADMINState = $idADMINState['idADMINState'];
						
						echo ($idADMINCity  != '' ? $idADMINCity : '' );
						echo ($idADMINCity  != '' && $idADMINState != '' ? ', ' : '');
						echo ($idADMINState != '' ? $idADMINState : '');
						}
					?>
					</span>
				</div>
			</div>
		</div>
		
	<? } ?>
	<!-- end: groups i follow -->
	
<?
	}
?>
<!-- end wallposts -->