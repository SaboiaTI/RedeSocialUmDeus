		<!-- conteudo page -->
		<div id="content">
		
			<aside id="profile">
			
				<!-- foto da pagina -->
				<div class="photo-profile">
					<img src="<?= $socialObject['properties']['sAvatarPath'] ? $socialObject['properties']['sAvatarPath'] : "img/page-default.jpg"; ?>" alt="<?= $socialObject['sDisplayName']; ?>" border="0" width="150" height="150">
					
					
					
					<? if($userObject['id'] == $profileObject['id']) { ?>
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
				
				<? if($userObject['id'] == $profileObject['id']) { ?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id'];?>&prob=<?=$profileObject['id'];?>&feed=info" target="_self">editar página da entidade</a>
				<? } ?>
				
				
				
				<!-- seguidores -->
				<div id="friends">
					
					<?php
						$iTotal = getCountAssociations($socialObject['id'],'OBJECT','PAGE','FOLLOWER');
					?>
					
					<h2>Administradores</h2>
					
					<?php 
						$list = getAssociations($socialObject['id'],'OBJECT','PAGE','OWNER','',0,5);
						for ($i=0;$i<count($list);$i++) {
					?>
						<div class="user">
							<div>
								<div class="photo">
									<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$list[$i]['id'];?>" target="_self">
									<?
										$sAvatarPath = getProperties($list[$i]['id'],'sAvatarPath');
										$sAvatarPath = $sAvatarPath['sAvatarPath'];
									?>
									<img src="<?= $sAvatarPath ? $sAvatarPath : "img/avatar-default.jpg"; ?>" alt="" border="0" width="50" height="50">
									</a>
								</div>
								<div class="name-location">
									<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$list[$i]['id'];?>"><strong><?=$list[$i]['sDisplayName']; ?></strong></a><br>
									<span style="color:#999">
									<?php 
										$idADMINCity = getProperties($list[$i]['id'],'idADMINCity');
										$idADMINCity = $idADMINCity['idADMINCity'];
										
										$idADMINState = getProperties($list[$i]['id'],'idADMINState');
										$idADMINState = $idADMINState['idADMINState'];
										
										echo ($idADMINCity != '' ? $idADMINCity : '' );
										
										if ($idADMINCity != '' && $idADMINState != '') {
											echo ', ';
										}
										
										echo ($idADMINState != '' ? $idADMINState : '');
									?>
									</span>
								</div>
							</div>
						</div>
						
					<? } ?>
					
					<h2>Seguidores (<?=$iTotal;?>)<br><span style="font-size:12px"><a href="index.php?id=<?=uniqid();?>
					&sob=<?=$socialObject['id'];?>
					&prob=<?=$profileObject['id'];?>
					&feed=follower">ver todos</a></span></h2>
					
					<?php 
						$list = getAssociations($socialObject['id'],'OBJECT','PAGE','FOLLOWER','',0,5);
						for ($i=0;$i<count($list);$i++) {
					?>
						<div class="user">
							<div>
								<div class="photo">
									<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$list[$i]['id'];?>" target="_self">
									<?
										$sAvatarPath = getProperties($list[$i]['id'],'sAvatarPath');
										$sAvatarPath = $sAvatarPath['sAvatarPath'];
									?>
									<img src="<?= $sAvatarPath ? $sAvatarPath : "img/avatar-default.jpg"; ?>" alt="" border="0" width="50" height="50">
									</a>
								</div>
								<div class="name-location">
									<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$list[$i]['id'];?>"><strong><?=$list[$i]['sDisplayName']; ?></strong></a><br>
									<span style="color:#999">
									<?php 
										$idADMINCity = getProperties($list[$i]['id'],'idADMINCity');
										$idADMINCity = $idADMINCity['idADMINCity'];
										
										$idADMINState = getProperties($list[$i]['id'],'idADMINState');
										$idADMINState = $idADMINState['idADMINState'];
										
										echo ($idADMINCity != '' ? $idADMINCity : '' );
										
										if ($idADMINCity != '' && $idADMINState != '') {
											echo ', ';
										}
										
										echo ($idADMINState != '' ? $idADMINState : '');
									?>
									</span>
								</div>
							</div>
						</div>
						
					<? } ?>
					
				</div>
				
			</aside>
			
			<section id="main-flow">
			
				<!-- resumo da página -->
				<div id="profile-summary">
					
					<h1><?= $socialObject['sDisplayName']; ?></h1>
					
					<?php 
						$details 		= getDetails($socialObject['id']);
						$properties 	= getProperties($socialObject['id']);
						
						$idADMINCity 	= isset($properties['idADMINCity'])  ? $properties['idADMINCity'] 	: '';
						$idADMINState 	= isset($properties['idADMINState']) ? $properties['idADMINState'] 	: '';
						$sDescription 	= isset($properties['sDescription']) ? $properties['sDescription'] 	: '';
					?>
					
					<?php 
						echo '<p>';
						
						echo $sDescription.'<br>';
						
						echo $idADMINCity;
						if ($idADMINCity != '' && $idADMINState != '') {
							echo ', ';
						}
						echo $idADMINState;
						
						echo '</p>';
					?>
				</div>
				
				
				
				<!-- opções de visualização -->
				<? $feed = isset($_GET['feed']) ? $_GET['feed'] : 'feed'; ?>
				<nav id="post-navigation">
					<ul>
						<li class="<?=$feed=='feed'			? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id']?>&prob=<?=$profileObject['id']?>&feed=feed">mural</a></li>
						<li class="<?=$feed=='info'			? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id']?>&prob=<?=$profileObject['id']?>&feed=info">informações</a></li>
						<li class="<?=$feed=='event'		? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id']?>&prob=<?=$profileObject['id']?>&feed=event">eventos</a></li>
						<? if (false) { ?>
						<li class="<?=$feed=='participant'	? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id']?>&prob=<?=$profileObject['id']?>&feed=participant">participantes</a></li>
						<? } ?>
						<li class="<?=$feed=='follower'		? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id']?>&prob=<?=$profileObject['id']?>&feed=follower">seguidores</a></li>
					</ul>
				</nav>
				
			<? if ($feed == 'feed') { ?>
				
				<? include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_timeline.php"); ?>
			
			<? } else if ($feed == 'event') { ?>
				
				<? include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_timeline.php"); ?>
			
			<? } else if ($feed == 'info') { ?>
				
				<?php
					$disabled	= 'disabled';
					if($userObject['id'] == $profileObject['id']) {
						$disabled = '';
					}
				?>
				
				<form id="form-page" action="javascript:updatePage(<?=$socialObject['id'];?>, 'form#form-page');">
					
					<fieldset>
					
						<legend>Informações</legend>
						
						<p>
						<label class="label" for="iPageType">Tipo:</label>
						<select class="input" name="iPageType" id="iPageType" value="<?=$properties['iPageType'];?>" disabled required>
							<option <?=$properties['iPageType']=='1' ? 'selected' : '';?> value="1">Templo</option>
							<option <?=$properties['iPageType']=='2' ? 'selected' : '';?> value="2">Igreja</option>
							<option <?=$properties['iPageType']=='3' ? 'selected' : '';?> value="3">Outros</option>
						</select>
						</p>
						
						<p><label class="label" for="sDisplayName">Nome:</label><input id="sDisplayName" type="text" value="<?=$socialObject['sDisplayName'];?>" <?=$disabled?>></p>
						<p><label class="label" for="sDescription">Sobre:</label><input id="sDescription" type="text" value="<?=$properties['sDescription'];?>" <?=$disabled?>></p>
						
						<br>
						
						<p><label class="label" for="sAddress">Endereço:</label><input id="sAddress" type="text" value="<?=$properties['sAddress'];?>" <?=$disabled?>></p>
						<p><label class="label" for="sComplement">Complemento:</label><input id="sComplement" type="text" value="<?=$properties['sComplement'];?>" <?=$disabled?>></p>
						<p><label class="label" for="sNeighborhood">Bairro:</label><input id="sNeighborhood" type="text" value="<?=$properties['sNeighborhood'];?>" <?=$disabled?>></p>
						
						<p>
						<label class="label" for="idADMINCountry">País:</label>
						<select name="idADMINCountry" id="idADMINCountry" value="<?=$properties['idADMINCountry'];?>" <?=$disabled?> required>
							<option disabled value="">selecione o país</option>
							<option <?=$properties['idADMINCountry']=='BR' ? 'selected' : '';?> value="BR">Brasil</option>
						</select>
						</p>
						
						<p>
						<label class="label" for="idADMINState">Estado:</label>
						<select name="idADMINState" id="idADMINState" value="<?=$properties['idADMINState'];?>" <?=$disabled?> required>
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
						</select>
						</p>
						
						
						<p><label class="label" for="idADMINCity">Cidade:</label><input id="idADMINCity" type="text" value="<?=$properties['idADMINCity'];?>" <?=$disabled?>></p>
						<p><label class="label" for="sPostalCode">CEP:</label><input id="sPostalCode" type="text" value="<?=$properties['sPostalCode'];?>" <?=$disabled?> maxlength="9"></p>
					
					</fieldset>
					
					<?php if($userObject['id'] == $profileObject['id']) { ?>
						<input type="submit" class="basic-button" value="salvar alterações">
					<?php } ?>
				
				</form>
				
			<? } else if ($feed == 'follower') { ?>
				
				<?php 
					$list = getAssociations($socialObject['id'],'OBJECT','PAGE','FOLLOWER');
					for ($i=0;$i<count($list);$i++) {
				?>
				
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
								<span style="color:#999"><?=$sCity;?>, <?=$sState;?></span>
							</div>
							
							<? if ($userObject['id'] == $profileObject['id']) { ?>
							<div style="display:inline-block;vertical-align:top;float:right;">
								<button type="button" class="basic-button remove" onclick="javascript:PageUnfollow(<?=$list[$i]['id'];?>, <?=$socialObject['id'];?>);">remover seguidor</button>
							</div>
							<? } ?>
							
						</div>
					</div>
					
				<? } ?>
				
			<? } ?>
			
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
