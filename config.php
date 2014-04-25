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
					<h1>Configurações</h1>
				</div>
				
				<nav id="post-navigation">
					<ul>
						<li id="config" class="active" style="width:175px;"><a href="#" onclick="javascript:
								event.preventDefault();
								$('form#form-config').slideDown('fast');
								$('form#form-unsubscribe').slideUp('fast');
								$('li#config').addClass('active');
								$('li#unsubscribe').removeClass('active');
								
						">configurações do usuário</a></li>
						<li id="unsubscribe" class="" style="width:175px;"><a href="#" onclick="javascript:
								event.preventDefault();
								$('form#form-config').slideUp('fast');
								$('form#form-unsubscribe').slideDown('fast');
								$('li#config').removeClass('active');
								$('li#unsubscribe').addClass('active');
						">desativar minha conta</a></li>
					</ul>
				</nav>
				
				<div id="failure-container" style="display:none;">
					<h2>Erro!</h2>
					<p>Não foi possivel alterar seu email! Este email já foi cadastrado.</p>
				</div>
				
				<div id="confirmation-container" style="display:none;">
					<h2>Atenção!</h2>
					<p>
						Você tem certeza que deseja excluir a sua conta?
						<button type="button" class="basic-button" onclick="javascript:confirmationDelete('hide');" style="padding:8px; float:right; margin-left:5px">não</button>
						<button type="button" class="basic-button" onclick="javascript:deleteAccount('form#form-unsubscribe');" style="padding:8px; float:right">sim</button>
					</p>
				</div>
				
				<?php if(isset($userObject) && $userObject['id'] == $socialObject['id']) { ?>
				
					<form id="form-config" action="javascript:updateConfig(<?php echo $socialObject['id'];?>, 'form#form-config');">
			
						<fieldset>
						
							<legend>Informações pessoais</legend>
							
							<p><label class="label" for="sFullName">Nome:</label><input id="sFullName" type="text" value="<?php echo $properties['sFullName'];?>" ></p>
							<p><label class="label" for="sDisplayName">Exibir como:</label><input id="sDisplayName" type="text" value="<? echo $socialObject['sDisplayName'];?>"></p>
							<p><label class="label" for="dtBirthday">Data de Nascimento:</label><input id="dtBirthday" type="text" value="<?php echo $properties['dtBirthday'];?>"  maxlength="10"></p>
							
							<p><label class="label" for="idADMINCountry">País:</label><select name="idADMINCountry" id="idADMINCountry" value="<?=$properties['idADMINCountry'];?>" required>
								<option disabled value="">selecione o país</option>
								<option <?=$properties['idADMINCountry']=='BR' ? 'selected' : '';?> value="BR">Brasil</option>
							</select>
							</p>
							<p><label class="label" for="idADMINState">Estado onde vive:</label><select name="idADMINState" id="idADMINState" value="<?=$properties['idADMINState'];?>" required>
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
							<!-- <p><label class="label" for="idADMINCountry">País:</label><input id="idADMINCountry" type="text" value="<?// echo $properties['idADMINCountry'];?>"></p>
							<p><label class="label" for="idADMINState">Estado:</label><input id="idADMINState" type="text" value="<?php// echo $properties['idADMINState'];?>" ></p> -->
							
							<p><label class="label" for="idADMINCity">Cidade onde vive:</label><input id="idADMINCity" type="text" value="<?php echo $properties['idADMINCity'];?>" ></p>
							<p><label class="label" for="sPostalCode">CEP:</label><input id="sPostalCode" type="text" value="<?php echo $properties['sPostalCode'];?>"  maxlength="9"></p>
						
						</fieldset>
						
						<? if (false) { ?>
						<fieldset>
						
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
										<a href="index.php?id=<?php echo uniqid();?>&sob=19" target="_self"><img src="img/page-default.jpg" alt="" border="0" width="50" height="50"></a>
									</div>
									<div class="name-location">
										<a href="index.php?id=<?php echo uniqid();?>&sob=19"><strong>Espaço Espitirual 1</strong></a><br>
										<span style="color:#999">São Paulo, SP</span>
									</div>
								</li>
								
								<li class="page-item">
									<div class="photo">
										<a href="index.php?id=<?php echo uniqid();?>&sob=19" target="_self"><img src="img/page-default.jpg" alt="" border="0" width="50" height="50"></a>
									</div>
									<div class="name-location">
										<a href="index.php?id=<?php echo uniqid();?>&sob=19"><strong>Espaço Espiritual 2</strong></a><br>
										<span style="color:#999">São Paulo, SP</span>
									</div>
								</li>
							</ul>
							
						</fieldset>
						<? } ?>
						
						<fieldset>
						
							<legend>Conta</legend>
							
							<p><strong>Alterar Email:</strong></p>
							<br>
							
							<p>
								<label class="label" for="sEmailAtual">E-mail atual: </label>
								<input id="sEmailAtual" type="text" >
							</p>
							<p>
								<label class="label" for="sEmailNovo">Novo e-mail: </label>
								<input id="sEmailNovo" type="text">
							</p>
							<p>
								<label class="label" for="sEmailConfirma">Confirmar novo email: </label>
								<input id="sEmailConfirma" type="text" >
							</p>
							
							<br>
							<p><strong>Alterar Senha:</strong></p>
							<br>
							
							<p>
								<label class="label" for="sPasswordAtual">Senha atual: </label>
								<input id="sPasswordAtual" type="password" value="" style="margin:0px" >
							</p>
							<p>
								<label class="label" for="sPasswordNovo">Nova senha: </label>
								<input id="sPasswordNovo" type="password" value="" style="margin:0px" >
							</p>
							<p>
								<label class="label" for="sPasswordConfirma">Confirma nova senha: </label>
								<input id="sPasswordConfirma" type="password" value="" style="margin:0px" >
							</p>
						
						</fieldset>
						
						<? if (false) { ?>
						<fieldset>
							
							<legend>Afinidades espirituais</legend>
							
							<?php 
								$religions = getObjects(10,'');
								for ($i=0;$i<count($religions);$i++) {
									echo '<p><label><input type="checkbox" name="afinidades-espirituais" value="'.$religions[$i]['id'].'">'.$religions[$i]['sDisplayName'].'</label></p>';
								}
							?>
						</fieldset>
						<? } ?>
						
						<input type="submit" class="basic-button" value="salvar alterações">
						
					</form>
					
					<form id="form-unsubscribe" action="javascript:confirmationDelete('show');" style="display:none;">
					
						<fieldset>
						
							<legend>Desativar Minha Conta</legend>
							
							<p>
							Tem certeza de que deseja desativar sua conta?<br>
							É possivel que amigos deixem de manter contato com você.<br>
							<br>
							<strong>Motivo da desativação da conta:</strong>
							</p>
							
							<p><input id="motivo1" type="radio" name="motivo" value="1"><label for="motivo1" style="margin: 3px 3px 0px 5px" />Não sei como usar a Rede Social.</label></p>
							<p><input id="motivo2" type="radio" name="motivo" value="2"><label for="motivo2" style="margin: 3px 3px 0px 5px" />Isto é temporário. Eu voltarei.</label></p>
							<p><input id="motivo3" type="radio" name="motivo" value="3"><label for="motivo3" style="margin: 3px 3px 0px 5px" />Eu não gostei do conteúdo e informações enviadas.</label></p>
							<p><input id="motivo4" type="radio" name="motivo" value="4"><label for="motivo4" style="margin: 3px 3px 0px 5px" />Recebo muitos e-mails, convites e solicitações.</label></p>
							<p><input id="motivo5" type="radio" name="motivo" value="5"><label for="motivo5" style="margin: 3px 3px 0px 5px" />Outro</label></p>
							
							<br>
							<p>Fale mais sobre o cancelamento da conta:</p>
							
							<textarea rows="7" id="mais" style="width:510px; max-width:510px"></textarea>
						
						</fieldset>
						
						<input type="submit" class="basic-button" value="desativar minha conta">
		
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
					<img src="img/caridade.gif" alt="" >
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
