<?php 
	// bibliotecas compartilhadas:
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/AppConnect.php");
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/libSocial.php");
	
	session_start();
	
	
	
	// ----------------------------------------------------------------------------------------------------
	// informa��es sobre usu�rio logado:
	if ( !isset($_SESSION["loggedUser"]) ) { $_SESSION["loggedUser"] = 0; }
	
	if ($_SESSION["loggedUser"] != 0) {
		$usob = $_SESSION["loggedUser"];
		$userObject = getObject($usob);
		$userObject['properties'] = getProperties($usob);
	}
	
	// ----------------------------------------------------------------------------------------------------
	// informa��es sobre o objeto acessado:
	$sob = isset($_GET['sob']) ? $_GET['sob'] : 0;
	$sob = intval($sob);

	if ($sob != 0) {
		$socialObject = getObject($sob);
		$socialObject['properties'] = getProperties($sob);
	}
	
	// ----------------------------------------------------------------------------------------------------
	// informa��es sobre o profile relacionado ao objeto acessado:
	$prob = isset($_GET['prob']) ? $_GET['prob'] : 0;
	$prob = intval($prob);

	if ($prob != 0) {
		$profileObject = getObject($prob);
		$profileObject['properties'] = getProperties($prob);
	}
	
	
	// include do arquivo header
	include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_header.php");
?>
		<!-- conteudo cadastro -->
		<div id="content">
			
		<div><h1 style="font-size:24px; color:#f58322; margin:0px 0px 25px 0px; padding:0px;">Criar conta</h1></div>
		
			<section id="main-flow" class="wide" style="width:680px; margin:0 15px 0 0;">
				
				<div id="success-container" style="display:none;">
					<h1>Parab�ns!</h1>
					<p>Seu cadastro foi realizado com sucesso!<br>Voc� agora j� pode se logar com seus dados.</p>
				</div>
				
				<div id="failure-container" style="display:none;">
					<h1>Desculpe</h1>
					<p>Infelizmente n�o conseguimos realizar seu cadastro.<br>Por favor, tente novamente mais tarde.</p>
				</div>
				
				<div id="form-container">
				
					<div style="margin-bottom:18px">
						<div id="cabprimeiraparte" class="form-step active"><p>informa��es do perfil</p></div>
						<? if (false)  { ?> 
							<div class="arrow-step"><img src="img/step-arrow.png" alt=""></div>
							<div class="form-step"><p>espa�os esirituais que frequenta</p></div>
						<? } ?>
						<div class="arrow-step"><img src="img/step-arrow.png" alt=""></div>
						<div id="cabsegundaparte" class="form-step"><p>aceita��o dos princ�pios da Rede Social UmDeus</p></div>
					</div>
					
					
					<!-- <form id="new-user" action="javascript:createAccount();" method="post" enctype="multipart/form-data"> -->
					<form id="new-user" action="javascript:createAccount();">
					
					<fieldset id="primeiraparte">
					
						<p>Preencha as informa&ccedil;&otilde;es de perfil. Essas informa&ccedil;&otilde;es o ajudar&atilde;o a localizar amigos.</p>
						
						<p>
							<label for="sFullName">nome:</label>
							<input type="text" class="input" name="nome" id="sFullName" required>
						</p>

						<p>
							<label for="sDisplayName">como gostaria de ser chamado:</label>
							<input type="text" class="input" name="sDisplayName" id="sDisplayName" required>
						</p>
						
						<p>
							<label for="sEmail">e-mail:</label>
							<input type="text" class="input" name="sEmail" id="sEmail" required pattern="^([0-9a-zA-Z]+([_.-]?[0-9a-zA-Z]+)*@[0-9a-zA-Z]+[0-9,a-z,A-Z,.,-]*(.){1}[a-zA-Z]{2,4})+$">
						</p>
						
						<p>
							<label for="sPassword">senha:</label>
							<input type="password" class="input" name="sPassword" id="sPassword" required>
						</p>
						
						<p>
							<label for="sPassword_repeat">confirmar senha:</label>
							<input type="password" class="input" name="sPassword_repeat" id="sPassword_repeat" required>
						</p>
						
						<p>
							<label for="dtBirthday">data de nascimento:</label>
							<input type="text" class="input" name="dtBirthday" id="dtBirthday" maxlength="10" pattern="^\d{2}\/\d{2}\/\d{4}$" required>
						</p>
						
						<p>
							<label for="idADMINCountry">pa�s:</label>
							<select name="idADMINCountry" id="idADMINCountry" required>
								<option selected disabled value="">selecione o pa�s</option>
								<option value="BR">Brasil</option>
							</select>
						</p>
						
						<p>
							<label for="idADMINState">estado onde vive:</label>
							<select name="idADMINState" id="idADMINState" required>
								<option selected disabled value="">selecione o estado</option>
								<option value="AC">Acre</option>
								<option value="AL">Alagoas</option>
								<option value="AP">Amap�</option>
								<option value="AM">Amazonas</option>
								<option value="BA">Bahia</option>
								<option value="CE">Cear�</option>
								<option value="DF">Distrito Federal</option>
								<option value="ES">Espirito Santo</option>
								<option value="GO">Goi�s</option>
								<option value="MA">Maranh�o</option>
								<option value="MS">Mato Grosso do Sul</option>
								<option value="MT">Mato Grosso</option>
								<option value="MG">Minas Gerais</option>
								<option value="PA">Par�</option>
								<option value="PB">Para�ba</option>
								<option value="PR">Paran�</option>
								<option value="PE">Pernambuco</option>
								<option value="PI">Piau�</option>
								<option value="RJ">Rio de Janeiro</option>
								<option value="RN">Rio Grande do Norte</option>
								<option value="RS">Rio Grande do Sul</option>
								<option value="RO">Rond�nia</option>
								<option value="RR">Roraima</option>
								<option value="SC">Santa Catarina</option>
								<option value="SP">S�o Paulo</option>
								<option value="SE">Sergipe</option>
								<option value="TO">Tocantins</option>
							</select>
						</p>
						
						<p>
							<label for="idADMINCity">cidade onde vive:</label>
							<input type="text" class="input" name="idADMINCity" id="idADMINCity" required>
						</p>
						
						<p>
							<label for="sPostalCode">cep:</label>
							<input type="text" class="input" name="sPostalCode" id="sPostalCode" placeholder="xxxxx-xxx" required maxlength="9" pattern="^\d{5}\-\d{3}$">
						</p>
						
						
						
						<!-- <p>
							<label for="sAvatarPath">imagem de apresenta&ccedil;&atilde;o:</label>
							<input type="file" class="input" name="sAvatarPath" id="sAvatarPath">
						</p> --
						
						<hr>
						
						<p>afinidades espirituais</p>
						<p>
							<div style="display:inline-block; margin-bottom:8px; margin-right:8px">
								<input type="checkbox" name="religiao"  style="margin-right:4px"> religi&atilde;o exemplo 
							</div>
								
							<div style="display:inline-block; margin-bottom:8px; margin-right:8px">
								<input type="checkbox" name="religiao"  style="margin-right:4px"> religi&atilde;o exemplo 
							</div>
								
							<div style="display:inline-block; margin-bottom:8px; margin-right:8px">
								<input type="checkbox" name="religiao"  style="margin-right:4px"> religi&atilde;o exemplo 
							</div>
								
							<div style="display:inline-block; margin-bottom:8px; margin-right:8px">
								<input type="checkbox" name="religiao"  style="margin-right:4px"> religi&atilde;o exemplo 
							</div>
							
							<br>
							
							<div style="display:inline-block; margin-bottom:8px; margin-right:8px">
								<input type="checkbox" name="religiao"  style="margin-right:4px"> religi&atilde;o exemplo 
							</div>
								
							<div style="display:inline-block; margin-bottom:8px; margin-right:8px">
								<input type="checkbox" name="religiao"  style="margin-right:4px"> religi&atilde;o exemplo 
							</div>
								
							<div style="display:inline-block; margin-bottom:8px; margin-right:8px">
								<input type="checkbox" name="religiao"  style="margin-right:4px"> religi&atilde;o exemplo 
							</div>
								
							<div style="display:inline-block; margin-bottom:8px; margin-right:8px">
								<input type="checkbox" name="religiao"  style="margin-right:4px"> religi&atilde;o exemplo 
							</div>
							
						</p>
						
						<!-- <p style="vertical-align:top">
						
							<img src="img/codigo.jpg" alt="">

							Verifica&ccedil;&atilde;o de seguran&ccedil;a.
							Digite a palavra ao lado.
							
						</p> -->
						
						<button type="button" class="basic-button" onclick="javascript:nextStep();" title="crie sua conta na rede social UmDeus">pr�ximo passo</button>
						
					</fieldset>
					
					<fieldset id="segundaparte" style="display:none">
						<textarea rows="10" style="width: 515px; height: 185px;" readonly="readonly" onfocus="this.rows=10">
Termos de Servi�o da rede social UM DEUS
1. Rela��o do usu�rio com a rede social UM DEUS
						</textarea><br/>
						<input type="checkbox" name="lieconcordo" onchange="javascript:
						if($(this).attr('checked')=='checked')
						{$('#criarconta').css({'display':'block'})}
						else{$('#criarconta').css({'display':'none'})};">Li e concordo com os termos acima.						
						<input id="criarconta" type="submit" class="basic-button" style="display:none" value="criar conta" >
					</fieldset>
					
					</form>
					
				</div>
				
			</section>
			
			<aside id="banner">
			
				<!-- banner -->
				<div class="banner">
					<a href="ajude.php" title="Fa�a uma doa��o aqui"><img src="img/ajude-nos.jpg" alt="Ajude a manter a Rede Social UmDeus"></a>
					Lorem ipsum dolor sit amet, a non ut, etiam velit lacinia lacus sit. Rerum nec uma velit lorem vulputate. <a href="ajude.php" title="Fa�a uma doa��o aqui">Fa&ccedil;a uma doa&ccedil;&atilde;o aqui.</a>
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
