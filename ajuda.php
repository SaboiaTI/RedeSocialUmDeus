<?php 
	// bibliotecas compartilhadas:
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/AppConnect.php");
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/libSocial.php");
	
	session_start();
	
	
	
	// ----------------------------------------------------------------------------------------------------
	// informações sobre usuário logado:
	if ( !isset($_SESSION["loggedUser"]) ) { $_SESSION["loggedUser"] = 0; }
	
	if ($_SESSION["loggedUser"] != 0) {
		$usob = $_SESSION["loggedUser"];
		$userObject = getObject($usob);
		$userObject['properties'] = getProperties($usob);
	}
	
	// ----------------------------------------------------------------------------------------------------
	// informações sobre o objeto acessado:
	$sob = isset($_GET['sob']) ? $_GET['sob'] : 0;
	$sob = intval($sob);

	if ($sob != 0) {
		$socialObject = getObject($sob);
		$socialObject['properties'] = getProperties($sob);
	}
	
	// ----------------------------------------------------------------------------------------------------
	// informações sobre o profile relacionado ao objeto acessado:
	$prob = isset($_GET['prob']) ? $_GET['prob'] : 0;
	$prob = intval($prob);

	if ($prob != 0) {
		$profileObject = getObject($prob);
		$profileObject['properties'] = getProperties($prob);
	}
	
	
	// include do arquivo header
	include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_header.php");
?>

		<!-- conteudo index -->
		<div id="content">
			
			<div><h1 style="font-size:24px; color:#f58322; margin:0px 0px 25px 0px; padding:0px;">Ajuda</h1></div>
			
			<div class="introduction" style="
										width:680px;
										display:inline-block;
										vertical-align:top;
										margin: 0 15px 0 0;
			">
				
				<img src="img/quem-somos.jpg">
				
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam nec mi id leo malesuada lobortis. Integer arcu massa, molestie in bibendum nec, tempus quis leo. Praesent purus nisl, placerat id lacinia vitae, iaculis ut tellus.</p>

				<p>Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vestibulum eget risus nec odio commodo consectetur. Nulla eget dui eget ante aliquam adipiscing. In rutrum magna sed tellus pellentesque blandit. Sed in metus sem. Nunc sagittis, ante eu dignissim bibendum, ligula nisi scelerisque quam, vel feugiat odio velit in enim.</p>
				
				<p>Duis id purus felis. Sed lorem enim, auctor vitae tristique vitae, bibendum nec enim. Aenean tristique convallis posuere. Sed convallis lectus mauris. Nullam feugiat pharetra metus vel gravida. Curabitur eget sem varius leo rhoncus ornare quis id nisi. Ut in mauris nisl, ac iaculis erat. Aenean tempor sollicitudin nulla eget semper.</p>

				<p>Nullam feugiat pharetra metus vel gravida. Curabitur eget sem varius leo rhoncus ornare quis id nisi. Ut in mauris nisl, ac iaculis erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vestibulum eget risus nec odio commodo consectetur. Nulla eget dui eget ante aliquam adipiscing. In rutrum magna sed tellus pellentesque blandit. Sed in metus sem. Nunc sagittis, ante eu dignissim bibendum, ligula nisi scelerisque quam, vel feugiat odio velit in enim. Praesent purus nisl, placerat id lacinia vitae, iaculis ut tellus.</p>
				
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam nec mi id leo malesuada lobortis. Integer arcu massa, molestie in bibendum nec, tempus quis leo. Praesent purus nisl, placerat id lacinia vitae, iaculis ut tellus.</p>
				
			</div>
			
			
			<!-- sidebar -->
			<aside id="banner">
			
				<!-- banner -->
				<div class="banner">
					<a href="ajude.php" title="Faça uma doação aqui"><img src="img/ajude-nos.jpg" alt="Ajude a manter a Rede Social UmDeus"></a>
					Lorem ipsum dolor sit amet, a non ut, etiam velit lacinia lacus sit. Rerum nec uma velit lorem vulputate. <a href="ajude.php" title="Faça uma doação aqui">Fa&ccedil;a uma doa&ccedil;&atilde;o aqui.</a>
				</div>
				
				<!-- banner -->
				<div class="banner">
					<a href="caridade.php" title="Pratique a caridade"><img src="img/caridade.gif" alt="Pratique a caridade"></a>
					Lorem ipsum dolor sit amet, a non ut, etiam velit lacinia lacus sit. Rerum nec uma velit lorem vulputate. <a href="caridade.php" title="Pratique a caridade">Saiba mais aqui.</a>
				</div>
				
				<!-- banner -->
				<div class="banner">
					<a href="page-create.php" title="Crie uma página para a sua ação de caridade"><img src="img/acao_caridade.jpg" alt="Crie uma página para a sua ação de caridade"></a>
					Lorem ipsum dolor sit amet, a non ut, etiam velit lacinia lacus sit. Rerum nec uma velit lorem vulputate. <a href="page-create.php" title="Crie uma página para a sua ação de caridade">Saiba mais aqui.</a>
				</div>
			
			</aside>
			<!-- end sidebar -->
			
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

