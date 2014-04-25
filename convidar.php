<?php

	session_start();
	
	// bibliotecas compartilhadas:
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/libSocial.php");
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/AppConnect.php");
	
	// informações sobre usuário logado:
	if ( !isset($_SESSION["loggedUser"]) ) { $_SESSION["loggedUser"] = 0; }
	
	if ($_SESSION["loggedUser"] != 0) {
		$userObject = getObject($_SESSION["loggedUser"]);
		$userObject['properties'] = getProperties($_SESSION["loggedUser"]);
	}
	
	// include do arquivo header
	require_once($_SERVER['DOCUMENT_ROOT'] . "/inc_header.php");
	
	$properties = getProperties($userObject['id']);
	
?>

		<!-- conteudo search-results -->
		
		<div id="content">
		
			<aside id="profile">
			
				<!-- foto do perfil -->
				<div class="photo-profile">
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$userObject['id'];?>prob=<?=$userObject['id'];?>"><img src="<?php echo  (isset($userObject) && $userObject['properties']['sAvatarPath']) ? $userObject['properties']['sAvatarPath'] : "img/avatar-default.jpg"; ?>" alt="<?php echo  $userObject['sDisplayName']; ?>" border="0" width="150"></a>
					
					<?php if(isset($userObject)) {?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$userObject['id'];?>prob=<?=$userObject['id'];?>&feed=info">editar meu perfil</a>
					<?php } ?>
					
				</div>
				
			</aside>
			
			<section id="main-flow">
				
				<div id="profile-summary" style="min-height:50px">
					<h1>Convidar Amigos</h1>
				</div>
				
				<div id="failure-container" style="display:none;">
					<h2>Erro!</h2>
					<p>Não foi possivel alterar seu email! Este email já foi cadastrado.</p>
				</div>
				
				<div id="confirmation-container" style="display:none;">
					<h2>Confirmação</h2>
					<p>O convite será enviado com a seguinte mensagem:</p>
					<p><q><span id="message"></span></q></p>
					<p style="text-align:right;padding:0 20px 0 0;">
					<button type="button" class="basic-button" onclick="javascript:sendInvite('form#form-invite');">Confirmar</button>
					</p>
				</div>
				
				<?php if(isset($userObject) && $userObject['id'] == $socialObject['id']) { ?>
				
					<form id="form-invite" action="javascript:confirmInvite();" style="clear:both">
						
						<p><strong>Convide amigos que ainda não são membros da Rede Social</strong><br>Informe o(s) email(s) para os quais você deseja enviar o convite, seperados por vírgula.</p>
						
						<p>
						<label class="label" for="listFriends">para:</label><textarea id="listFriends" type="text" value="" rows="5" style="min-width:350px;max-width:350px;" required></textarea>
						</p>
						
						<p>
						<label class="label" for="sMessage">mensagem:</label><textarea id="sMessage" type="text" value="" rows="5" style="min-width:350px;max-width:350px;" required placeholder="escreva a mensagem que será enviada para seus amigos"></textarea>
						</p>
						
						<p style="text-align:right;padding:0 20px 0 0;"><input type="submit" class="basic-button" value="enviar convites"></p>
						
					</form>
					
				<?php } ?>
			
			</section>
			
			<aside id="banner">
				
				<!-- banner -->
				<div class="banner"onclick="javascript:location='doacao.html'" style="cursor:pointer">
					<img src="img/ajude-nos.jpg" alt="" >
					Lorem ipsum dolor sit amet, a non ut, etiam velit lacinia lacus sit. Rerum nec uma velit lorem vulputate. <a href="">Fa&ccedil;a uma doa&ccedil;&atilde;o aqui.</a>
				</div>
				
				<!-- banner -->
				<div class="banner"onclick="javascript:location='doacao.html'" style="cursor:pointer">
					<img src="img/caridade.jpg" alt="" >
					Lorem ipsum dolor sit amet, a non ut, etiam velit lacinia lacus sit. Rerum nec uma velit lorem vulputate. <a href="">Saiba mais aqui.</a>
				</div>
				
				<!-- busca de pessoas -->
				<!-- 
				<div id="search-people" class="search-box">
				
					<h1>Una-se &agrave;s pessoas</h1><a href="#"><img src="img/btn_minimizar.jpg" alt="" align="right" border="0" ></a><br>
					
					<p><strong>por localidade</strong></p>
					
					<p>
						pais: 
						<input type="text" class="input" name="pais" value="" placeholder="">
					</p>
					
					<p>
						estado: 
						<input type="text" class="input" name="estado" value="" placeholder="">
					</p>
					
					<p>
						cidade: 
						<input type="text" class="input" name="cidade" value="" placeholder="">
					</p>
					
					<p>
						bairro: 
						<input type="text" class="input" name="bairro" value="" placeholder="">
					</p>
					
					<p>
						<strong>por afinidade</strong>
						<input type="text" class="input" name="afinidade" value="" placeholder="">
						<p>exemplo: <em>idosos, crian&ccedil;as...</em></p>
					</p>
					
					<input type="submit" class="button" value="buscar" >
				
				</div>
				 -->
				<!-- busca de caridade -->
				<!-- 
				<div id="search-charity" class="search-box">
				
					<h1>Encontre a&ccedil;&otilde;es de caridade</h1><a href="#"><img src="img/btn_minimizar.jpg" alt="" align="right" border="0"></a><br>
					
					<p><strong>por localidade</strong></p>
					
					<p>
						pais: 
						<input type="text" class="input" name="pais" value="" placeholder="">
					</p>
					
					<p>
						estado: 
						<input type="text" class="input" name="estado" value="" placeholder="">
					</p>
					
					<p>
						cidade: 
						<input type="text" class="input" name="cidade" value="" placeholder="">
					</p>
					
					<p>
						bairro: 
						<input type="text" class="input" name="bairro" value="" placeholder="">
					</p>
					
					<p>
						<strong>por afinidade</strong>
						<input type="text" class="input" name="afinidade" value="" placeholder="">
						<p>exemplo: <em>idosos, crian&ccedil;as...</em></p>
					</p>
					
					<input type="submit" class="button" value="buscar" >
				
				</div>
				 -->
				
				<!-- banner -->
				<div class="banner" onclick="javascript:location='doacao.html'" style="cursor:pointer">
					<img src="img/acao_caridade.jpg" alt="" >
					Lorem ipsum dolor sit amet, a non ut, etiam velit lacinia lacus sit. Rerum nec uma velit lorem vulputate. <a href="">Saiba mais aqui.</a>
				</div>
			
			</aside>
			
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
