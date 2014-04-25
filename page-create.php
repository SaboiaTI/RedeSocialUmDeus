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
		<!-- conteudo page-create -->
		<div id="content">
			
		<div><h1 style="font-size:24px; color:#f58322; margin:0px 0px 25px 0px; padding:0px">Criar Página da Instituição</h1></div>
		
			<section id="main-flow" class="wide" style="width:680px; margin:0 15px 0 0;">
				
				<div id="success-container" style="display:none;">
					<h1>Parabéns!</h1>
					<p>Sua página foi criada com sucesso!</p>
				</div>
				
				<div id="failure-container" style="display:none;">
					<h1>Desculpe</h1>
					<p>Infelizmente não conseguimos criar sua página.<br>Por favor, tente novamente mais tarde.</p>
				</div>
				
				<div id="form-container" style="width:680px; display:inline-block; vertical-align:top">
				
					<form id="new-page" action="javascript:createPage();">
					
					<p>
						<label for="sFullName">nome da página:</label><input type="text" class="input" name="sFullName" id="sFullName" required>
					</p>

					<p>
						<label for="iPageType">tipo de página:</label><select class="input" name="iPageType" id="iPageType" required>
							<option value="1">Templo</option>
							<option value="2">Igreja</option>
							<option value="3">Outros</option>
						</select>
					</p>
					
					<p>
						<label for="sDescription">sobre:</label><textarea name="sDescription" id="sDescription" rows="3" required></textarea>
					</p>
					
					<p>
						<label for="sSite">site:</label><input type="text" class="input" name="sSite" id="sSite" required>
					</p>
					
					<h2>endereço</h2>
					
					<p>
						<label for="sPostalCode">cep:</label><input type="text" class="input" name="sPostalCode" id="sPostalCode" placeholder="xxxxx-xxx" required maxlength="9" pattern="^\d{5}\-\d{3}$">
					</p>
					
					<p>
						<label for="idADMINCountry">país:</label><select name="idADMINCountry" id="idADMINCountry" required>
							<option selected disabled value="">selecione o país</option>
							<option value="BR">Brasil</option>
						</select>
					</p>
					
					<p>
						<label for="idADMINState">estado:</label><select name="idADMINState" id="idADMINState" required>
							<option selected disabled value="">selecione o estado</option>
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
						</select>
					</p>
					
					<p>
						<label for="idADMINCity">cidade:</label><input type="text" class="input" name="idADMINCity" id="idADMINCity" required>
					</p>
					
					<p>
						<label for="sAddress">endereço:</label><input type="text" class="input" name="sAddress" id="sAddress" required>
					</p>
					
					<p>
						<label for="sComplement">complemento:</label><input type="text" class="input" name="sComplement" id="sComplement">
					</p>
					
					<p>
						<label for="sNeighborhood">bairro:</label><input type="text" class="input" name="sNeighborhood" id="sNeighborhood">
					</p>
					
					<!-- <p>
						<label for="sAvatarPath">imagem de apresenta&ccedil;&atilde;o:</label>
						<input type="file" class="input" name="sAvatarPath" id="sAvatarPath">
					</p> -->
					
					<input type="submit" class="basic-button" value="criar página">
					
					</form>
					
				</div>
				
			</section>
			
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
