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
	} else {
		header('Location: /index.php');
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
		
		<div><h1 style="font-size:24px;color:#f58322;margin:0px 0px 25px 0px;padding:0px;">Criar Grupo de Oração e Meditação</h1></div>
		
			<section id="main-flow" class="wide" style="width:680px; margin:0 15px 0 0;">
				
				<div id="success-container" style="display:none;">
					<h1>Parabéns!</h1>
					<p>Seu grupo de oração e meditação foi criado com sucesso!</p>
				</div>
				
				<div id="failure-container" style="display:none;">
					<h1>Desculpe</h1>
					<p>Infelizmente não conseguimos criar seu grupo de oração e meditação.<br>Por favor, tente novamente mais tarde.</p>
				</div>
				
				<div id="form-container" style="width:680px;display:inline-block;vertical-align:top">
					
					<div style="margin-bottom:18px">
						<div id="cabprimeiraparte" class="form-step active"><p>periodicidade <br>e local</p></div>
						<div style="display:table-cell;vertical-align:middle;"><img src="img/step-arrow.png" alt=""></div>
						<div id="cabsegundaparte" class="form-step"><p>convidar membros</p></div>
					</div>
					
					<form id="new-group" action="javascript:createGroup();">
						
						<fieldset id="primeiraparte">
						
							<p><label for="sDisplayName">nome do grupo:</label><input type="text" class="input" id="sDisplayName" required></p>
							
							<p><label for="iGroupType">objetivo:</label><select class="input" id="iGroupType" required="required">
								<option selected disabled value="">selecione</option>
								<option value="1">Oração</option>
								<option value="2">Meditação</option>
								<option value="3">Vigília</option>
							</select></p>
							
							<p><label for="sGroupTheme">tema:</label><input type="text" class="input" id="sGroupTheme" required></p>
							
							<p><br></p>
							
							<p><label for="dtBegin">período:</label><input type="text" id="dtBegin" placeholder="dd/mm/aaaa" pattern="^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/(19|20)\d{2}$" maxlength="10" required="" style="width:100px;"> até <input type="text" id="dtEnd" placeholder="dd/mm/aaaa" pattern="^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/(19|20)\d{2}$" maxlength="10" required="" style="width:100px;"></p>
							
							<p><label for="iPeriodicity">periodicidade:</label><select id="iPeriodicity" required 
							onchange="javascript:
								if($(this).find('option:selected').val()=='1') {
									$('#iPeriodicity2').closest('p').hide();
									$('#iPeriodicity3').closest('p').hide();
									$('#iPeriodicity4').closest('p').hide();
									$('#iPeriodicity2').removeAttr('required');
									$('#iPeriodicity3').removeAttr('required');
									$('#iPeriodicity4').removeAttr('required');
								} else if ($(this).find('option:selected').val()=='2') {
									$('#iPeriodicity2').closest('p').slideDown('fast');
									$('#iPeriodicity3').closest('p').hide();
									$('#iPeriodicity4').closest('p').hide();
									$('#iPeriodicity2').attr('required','true');
									$('#iPeriodicity3').removeAttr('required');
									$('#iPeriodicity4').removeAttr('required');
								} else if ($(this).find('option:selected').val()=='3') {
									$('#iPeriodicity2').closest('p').hide();
									$('#iPeriodicity3').closest('p').slideDown('fast');
									$('#iPeriodicity4').closest('p').hide();
									$('#iPeriodicity2').removeAttr('required');
									$('#iPeriodicity3').attr('required','true');
									$('#iPeriodicity4').removeAttr('required');
								} else if ($(this).find('option:selected').val()=='4') {
									$('#iPeriodicity2').closest('p').hide();
									$('#iPeriodicity3').closest('p').hide();
									$('#iPeriodicity4').closest('p').slideDown('fast');
									$('#iPeriodicity2').removeAttr('required');
									$('#iPeriodicity3').removeAttr('required');
									$('#iPeriodicity4').attr('required','true');
								};
							">
								<option selected disabled value="">selecione</option>
								<option value="1">Diária</option>
								<option value="2">Semanal</option>
								<option value="3">Mensal</option>
								<option value="4">Anual</option>
							</select></p>
							
							<p style="display:none;"><label for="iPeriodicity2">dia da semana:</label><select id="iPeriodicity2">
								<option selected disabled value="">selecione</option>
								<option value="1">Domingo</option>
								<option value="2">Segunda-feira</option>
								<option value="3">Terça-feira</option>
								<option value="4">Quarta-feira</option>
								<option value="5">Quinta-feira</option>
								<option value="6">Sexta-feira</option>
								<option value="7">Sábado</option>
							</select></p>
							
							<p style="display:none;"><label for="iPeriodicity3">dia do mês:</label><select id="iPeriodicity3">
								<option selected disabled value="">selecione</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
								<option value="16">16</option>
								<option value="17">17</option>
								<option value="18">18</option>
								<option value="19">19</option>
								<option value="20">20</option>
								<option value="21">21</option>
								<option value="22">22</option>
								<option value="23">23</option>
								<option value="24">24</option>
								<option value="25">25</option>
								<option value="26">26</option>
								<option value="27">27</option>
								<option value="28">28</option>
								<option value="29">29</option>
								<option value="30">30</option>
								<option value="31">31</option>
							</select></p>
							
							<p style="display:none;"><label for="iPeriodicity4">mês:</label><select id="iPeriodicity4">
								<option selected disabled value="">selecione</option>
								<option value="1">Janeiro</option>
								<option value="2">Fevereiro</option>
								<option value="3">Março</option>
								<option value="4">Abril</option>
								<option value="5">Maio</option>
								<option value="6">Junho</option>
								<option value="7">Julho</option>
								<option value="8">Agosto</option>
								<option value="9">Setembro</option>
								<option value="10">Outubro</option>
								<option value="11">Novembro</option>
								<option value="12">Dezembro</option>
							</select></p>
							
							<p><label for="tsOracao">horário local da oração:</label><select id="tsOracao" style="width:100px;">
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
							
							<p><br></p>
							
							<p><label for="idADMINCountry">país:</label><select name="idADMINCountry" id="idADMINCountry" required>
								<option selected disabled value="">selecione o país</option>
								<option value="BR">Brasil</option>
							</select></p>
							
							<p><label for="idADMINState">estado:</label><select name="idADMINState" id="idADMINState" required>
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
							</select></p>
							
							<p><label for="idADMINCity">cidade:</label><input type="text" class="input" name="idADMINCity" id="idADMINCity" required></p>
							
							<p><label for="sDescription">instruções:</label><textarea name="sDescription" id="sDescription" rows="5" style="width:340px;max-width:340px;"></textarea></p>
							
							<p><label for="sSite">site:</label><input type="text" class="input" name="sSite" id="sSite"></p>
							
							<br>
							<button type="button" class="basic-button" onclick="javascript:
								var validRequiredFields=0;
								$('#primeiraparte').find('input:required').each(function(index){
									if ($.trim($(this).val())=='') {
										validRequiredFields++;
									}
								});
								if (validRequiredFields==0) {
									$('#primeiraparte').slideUp('fast');
									$('#segundaparte').slideDown('fast');
									$('#cabprimeiraparte').removeClass('active');
									$('#cabsegundaparte').addClass('active');
								} else {
									$(this).closest('form').addClass('validated');
									$(this).after('&nbsp;preencha os campos obrigatórios antes de prosseguir');
								}" 
							title="próximo passo">próximo passo</button>
							
						</fieldset>
						
						<fieldset id="segundaparte" style="display:none;">
							
							<p>Convide seus amigos a participar do grupo de oração e meditação</p>
							<div>
							<?php
								
								$listFriend = getAssociations($userObject['id'],'FRIEND','FRIENDSHIP','FRIEND');
								
								for ($i=0;$i<count($listFriend);$i++) {
									
									$properties  = getProperties($listFriend[$i]['id'],'');
									$sAvatarPath = $properties['sAvatarPath'];
									$sCity 		 = $properties['idADMINCity'];
									$sState 	 = $properties['idADMINState'];
								
							?>
								<div class="user">
									<div>
										<div style="display:inline-block;vertical-align:middle;margin-right:5px;">
											<input type="checkbox" value="<?=$listFriend[$i]['id'];?>">
										</div>
										<div class="photo" style="vertical-align:middle;">
											<a href="index.php?id=<?=uniqid();?>&sob=<?=$listFriend[$i]['id'];?>&prob=<?=$listFriend[$i]['id'];?>" target="_blank">
											<img src="<?=$sAvatarPath ? $sAvatarPath : "img/avatar-default.jpg";?>" alt="<?=$listFriend[$i]['sDisplayName'];?>" border="0" width="50" height="50">
											</a>
										</div>
										<div class="name-location">
											<a href="index.php?id=<?=uniqid();?>&sob=<?=$listFriend[$i]['id'];?>&prob=<?=$listFriend[$i]['id'];?>" target="_blank"><strong><?=$listFriend[$i]['sDisplayName'];?></strong></a><br>
											<span style="color:#999"><?php
												echo ($sCity != '' ? $sCity : '' );
												echo ($sCity != '' && $sState != '' ? ', ' : '');
												echo ($sState != '' ? $sState : '');
											?></span>
										</div>
									</div>
								</div>
							<?php } ?>
							</div>
							<br>
							<input type="submit" class="basic-button" value="incluir amigos e criar página">
							
						</fieldset>
						
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
