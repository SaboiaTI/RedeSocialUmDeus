		<!-- conteudo profile -->
		<div id="content">
		
			<aside id="profile">
			
				<!-- foto do perfil -->
				<div class="photo-profile">
					<img src="<?= $socialObject['properties']['sAvatarPath'] ? $socialObject['properties']['sAvatarPath'] : "img/avatar-default.jpg"; ?>" alt="<?= $socialObject['sDisplayName']; ?>" border="0" width="150">
					
					<? if(isset($userObject) && $userObject['id'] == $socialObject['id']) { ?>
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
				
				<? if(isset($userObject) && $userObject['id'] == $socialObject['id']) { ?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$userObject['id'];?>&feed=info" target="_self">editar meu perfil</a>
				<? } ?>
				
				<? include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_friends.php"); ?>
				
			</aside>
			
			<section id="main-flow">
			
				<!-- resumo do usuário -->
				<div id="profile-summary">
					<h1><?= $socialObject['sDisplayName']; ?></h1>
					<?php 
						$idADMINCity = getProperties($socialObject['id'],'idADMINCity');
						$idADMINCity = $idADMINCity['idADMINCity'];
						
						$idADMINState = getProperties($socialObject['id'],'idADMINState');
						$idADMINState = $idADMINState['idADMINState'];
						
						echo $idADMINCity;
						
						if ($idADMINCity != '' && $idADMINState != '') {
							echo ', ';
						}
						
						echo $idADMINState;
						
					?><br>Afinidades espirituais: <?php
						
						//	<a style="text-decoration:line-through;">Religião 1</a>
						$affinities = getAssociations($socialObject['id'],'AFFINITYFOLLOWER','AFFINITY','OBJECT');
						$sep = '';
						foreach($affinities as $af) {
							echo $sep.'<a>'.$af['sDisplayName'].'</a>';
							$sep = ', ';
						}
					?>
				</div>
				
				<!-- opções de visualização -->
				<?
					$feed = isset($_GET['feed']) ? $_GET['feed'] : 'feed';
				?>
				<nav id="post-navigation">
					<ul>
						<li class="<?=$feed=='feed' 		? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id']?>&prob=<?=$profileObject['id']?>&feed=feed">feeds</a></li>
						<li class="<?=$feed=='info' 		? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id']?>&prob=<?=$profileObject['id']?>&feed=info">informações</a></li>
						<li class="<?=$feed=='photo-album' 	? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id']?>&prob=<?=$profileObject['id']?>&feed=photo-album">fotos</a></li>
						<li class="<?=$feed=='page' 		? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id']?>&prob=<?=$profileObject['id']?>&feed=page">páginas</a></li>
						<li class="<?=$feed=='group' 		? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id']?>&prob=<?=$profileObject['id']?>&feed=group">grupos</a></li>
					</ul>
				</nav>
				
				
				
				<? include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_photo-album.php"); ?>
				
				
				<? include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_timeline.php"); ?>
			
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
