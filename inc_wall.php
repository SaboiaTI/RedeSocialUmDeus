		<!-- conteudo wall -->
		<div id="content">
		
			<aside id="profile">
			
				<!-- opções de visualização -->
				<? $feed = isset($_GET['feed']) ? $_GET['feed'] : 'feed'; ?>
				<nav id="feed-navigation">
					<ul>
					
						<li class="<?=$feed=='feed'	 ? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&feed=feed">todos os feeds</a></li>
						<li class="<?=$feed=='photo' ? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&feed=photo">fotos</a></li>
						<li class="<?=$feed=='page'	 ? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&feed=page">páginas</a></li>
						<li class="<?=$feed=='video' ? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&feed=video">vídeos</a></li>
					<?
					if (false) {
					?>	
						<li class="<?=$feed=='link'  ? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&feed=link">links</a></li>
						<li class="<?=$feed=='text'  ? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&feed=text">textos</a></li>
					<?
					}										
					?>
					</ul>
				</nav>
				
				<? include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_friends.php") ?>
				
			</aside>
			
			<section id="main-flow">
			
				<!-- resumo do usuário -->
				<div id="profile-summary">
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$userObject['id']?>&prob=<?=$userObject['id']?>"><img src="<?= $userObject['properties']['sAvatarPath'] ? $userObject['properties']['sAvatarPath'] : "img/avatar-default.jpg"; ?>" alt="<?= $userObject['sDisplayName']; ?>" border="0" width="50" height="50" style="float:left;margin:0 10px 0 0;"></a>
					<h1><a href="index.php?id=<?=uniqid();?>&sob=<?=$userObject['id']?>&prob=<?=$userObject['id']?>"><?= $userObject['sDisplayName']; ?></a></h1>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$userObject['id']?>&prob=<?=$userObject['id']?>&feed=info">editar meu perfil</a>
				</div>
				
			<? if ($feed == 'feed') {
			
				$from = "front-page";
				include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_timeline.php");
				
			} else if ($feed == 'photo') {
				
				$from = "front-page";
				include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_timeline.php");
				
			} else if ($feed == 'page') {
				
				$from = "front-page";
				include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_timeline.php");
				
			} else if ($feed == 'video') {
				
				$from = "front-page";
				include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_timeline.php");
				
			} else {
				
				?><p>em breve</p><?
				
			} ?>
			
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
