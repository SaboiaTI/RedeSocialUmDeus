<?php
	
	session_start();
	
	// bibliotecas compartilhadas:
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/libSocial.php");
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/AppConnect.php");
	
	
	// include do arquivo header
	require_once($_SERVER['DOCUMENT_ROOT'] . "/inc_header.php");
	
	$feed = isset($_GET['feed']) ? $_GET['feed'] : 'profile';
	
	if ($feed=='profile' || $feed=='page' || $feed=='group') {
	
		$search = array();
		
		$search['pais']			= isset($_GET['pais']) 		? $_GET['pais'] 	 : '';
		$search['estado']		= isset($_GET['estado']) 	? $_GET['estado'] 	 : '';
		$search['cidade']		= isset($_GET['cidade']) 	? $_GET['cidade'] 	 : '';
		$search['bairro']		= isset($_GET['bairro']) 	? $_GET['bairro'] 	 : '';
		$search['afinidade']	= isset($_GET['afinidade']) ? $_GET['afinidade'] : '';
		
		$search['objetivo']		= isset($_GET['objetivo']) 	? $_GET['objetivo']	 : '';
		$search['tema']			= isset($_GET['tema']) 		? $_GET['tema']		 : '';
	}
	
	
	
	if (!isset($socialObject))  { $socialObject  = $userObject; }
	if (!isset($profileObject)) { $profileObject = $userObject; }
	
?>

		<!-- conteudo search-results -->
		
		<div id="content">
		
			<aside id="profile">
			
				<!-- foto do perfil -->
				<div class="photo-profile">
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id'];?>&prob=<?=$profileObject['id'];?>"><img src="<?= ($profileObject['properties']['sAvatarPath']) ? $profileObject['properties']['sAvatarPath'] : "img/avatar-default.jpg"; ?>" alt="<?= $profileObject['sDisplayName']; ?>" border="0" width="150"></a>
					
					<? if($userObject['id'] == $profileObject['id']) { ?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id'];?>&prob=<?=$profileObject['id'];?>&feed=info">editar meu perfil</a>
					<? } ?>
					
				</div>
				
			</aside>
			
			<section id="main-flow">
			
				<div id="profile-summary">
				<?php if ($feed == 'AllFriends') {
					if($userObject['id'] == $profileObject['id']) {
				?>
					<h1>Todos meus amigos</h1> 
					<div style="display:block; padding-top:10px; padding-right:10px; height:40px">
						<button type="button" class="basic-button remove" onclick="javascript:window.location='convidar.php?<?php echo 'id='.$_GET['id'].'&sob='.$_GET['sob'].'&prob='.$_GET['prob']; ?>';" style="float:right" >convidar amigos</button>
					</div>
				<?php
					} else {
				?>
					<h1>Todos os amigos de <?=$profileObject['sDisplayName'];?></h1>
				<?php
					}
				} else if ($feed == 'pendingFriends') {
					if($userObject['id'] == $profileObject['id']) {
				?>
					<h1>Convites de amizade pendentes</h1>
				<?php
					}
				} else {
				?>
					<h1>Resultado da busca</h1>
				<?
				}
				?>
				
				<? if ($feed == 'profile') { ?>
					<p>Listando os usuários cadastrados na rede social UmDeus</p>
				<? } else if ($feed == 'page') { ?>
					<p>Listando as páginas cadastradas na rede social UmDeus</p>
				<? } else if ($feed == 'group') { ?>
					<p>Listando os grupos de oração cadastrados na rede social UmDeus</p>
				<? } ?>
				</div>
				
				
				
				<!-- resultados da busca -->
				<?php 
				//echo $feed;
					if ($feed == 'profile') {
						$list = getObjects(1, $search);
					} else if ($feed == 'page') {
						$list = getObjects(2, $search);
					} else if ($feed == 'AllFriends') {
						$list = getAssociations($profileObject['id'],'FRIEND','FRIENDSHIP','FRIEND');
					} else if ($feed == 'pendingFriends') {
						$list = getAssociations($profileObject['id'],'INVITED_FRIEND','FRIENDSHIP','');
					} else if ($feed == 'group') {
						$list = getObjects(5, $search);
					} else {
						$list = getObjects(1);
					}
				?>
				
				<? for ($i=0;$i<count($list);$i++) { ?>
				
					<div class="user">
						<div>
							<div class="photo">
								<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$list[$i]['id'];?>" target="_self">
								<?
									$properties  = getProperties($list[$i]['id'],'');
									$sAvatarPath = $properties['sAvatarPath'];
									$sCity 		 = $properties['idADMINCity'];
									$sState 	 = $properties['idADMINState'];
								?>
								<img src="<?= $sAvatarPath ? $sAvatarPath : "img/avatar-default.jpg"; ?>" alt="" border="0" width="50" height="50">
								</a>
							</div>
							<div class="name-location">
								<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$list[$i]['id'];?>"><strong><?=$list[$i]['sDisplayName']; ?></strong></a><br>
								<span style="color:#999">
								
								<?php
								echo ($sCity  != '' ? $sCity : '' );
								echo ($sCity  != '' && $sState != '' ? ', ' : '');
								echo ($sState != '' ? $sState : '');
								?>
								
								
								</span>
							</div>
							
							<? if ($feed == 'AllFriends' && $userObject['id'] == $profileObject['id']) { ?>
								<div class="button">
									<button type="button" class="basic-button remove" onclick="javascript:ProfileEndFriendship(<?=$profileObject['id'];?>, <?=$list[$i]['id'];?>);">desfazer amizade</button>
								</div>
							<? } else if ($feed == 'pendingFriends') { ?>
								<div class="button">
									<button type="button" class="accept-relationship small" onclick="javascript:ProfileEndFriendship(<?=$profileObject['id'];?>, <?=$list[$i]['id'];?>);">aceitar</button>
									<button type="button" class="remove-relationship small" onclick="javascript:ProfileEndFriendship(<?=$profileObject['id'];?>, <?=$list[$i]['id'];?>);">rejeitar</button>
								</div>
							<? } ?>
							
						</div>
					</div>
					
				<? } ?>
				
			</section>
			
			<? require_once($_SERVER['DOCUMENT_ROOT'] . "/inc_sidebar.php"); ?>
			
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
