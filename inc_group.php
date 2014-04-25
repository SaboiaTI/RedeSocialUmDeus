		<!-- conteudo group -->
		<div id="content">
		
			<aside id="profile">
			
				<!-- foto do group -->
				<div class="photo-profile">
					
					<img src="<?= isset($socialObject['properties']['sAvatarPath']) ? $socialObject['properties']['sAvatarPath'] : "img/group-default.jpg"; ?>" alt="<?= $socialObject['sDisplayName']; ?>" border="0" width="150" height="150">
					
				<? if (false) { ?>
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
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id'];?>&prob=<?=$profileObject['id'];?>&feed=info" target="_self">editar grupo</a>
				<? } ?>
				
				
				
				<!-- membros -->
				<div id="friends">
					
					<?php
						$iTotal = getCountAssociations($socialObject['id'],'OBJECT','GROUP','MEMBER');
					?>
					
					<h2>Administradores</h2>
					
					<?php 
						$list = getAssociations($socialObject['id'],'OBJECT','GROUP','OWNER','',0,5);
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
					
					
					
					<h2>Membros (<?=$iTotal;?>)<br><span style="font-size:12px"><a href="index.php?id=<?=uniqid();?>
					&sob=<?=$socialObject['id'];?>
					&prob=<?=$profileObject['id'];?>
					&feed=member">ver todos</a></span></h2>
					
					<?php 
						$list = getAssociations($socialObject['id'],'OBJECT','GROUP','MEMBER','',0,5);
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
			
				<?php 
					// ------------------------------------------------------------------------------------------
					// resumo de informações do grupo
					// ------------------------------------------------------------------------------------------
				?>
				<div id="profile-summary">
					
					<h1><?=$socialObject['sDisplayName'];?></h1>
					
					<?php 
						$details 		= getDetails($socialObject['id']);
						$properties 	= getProperties($socialObject['id']);
						
						$idADMINCity 	= isset($properties['idADMINCity'])  ? $properties['idADMINCity'] 	: '';
						$idADMINState 	= isset($properties['idADMINState']) ? $properties['idADMINState'] 	: '';
						$sDescription 	= isset($details['sContent']) 		 ? $details['sContent'] 		: '';
					?>
					
					<?php 
						echo '<p>';
						
						switch($properties['iGroupType']) 
						{
							case '1' : echo '<strong>Objetivo</strong>: Oração<br>';	break;
							case '2' : echo '<strong>Objetivo</strong>: Meditação<br>';	break;
							case '3' : echo '<strong>Objetivo</strong>: Vigília<br>';	break;
						}
						echo '<strong>Tema</strong>: '.$properties['sGroupTheme'].'<br>';
						
						echo $idADMINCity;
						if ($idADMINCity != '' && $idADMINState != '') {
							echo ', ';
						}
						echo $idADMINState;
						
						echo '</p>';
					?>
					
				</div>
				
				
				
				<?php 
					// ------------------------------------------------------------------------------------------
					// opções de visualização
					// ------------------------------------------------------------------------------------------
					$feed = isset($_GET['feed']) ? $_GET['feed'] : 'oracoes';
				?>
				<nav id="post-navigation">
					<ul>
						<li class="<?=$feed=='oracoes'	? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id']?>&prob=<?=$profileObject['id']?>&feed=oracoes">orações</a></li>
						<li class="<?=$feed=='info'		? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id']?>&prob=<?=$profileObject['id']?>&feed=info">informações</a></li>
						<li class="<?=$feed=='member'	? 'active' : ''; ?>"><a href="index.php?id=<?=uniqid();?>&sob=<?=$socialObject['id']?>&prob=<?=$profileObject['id']?>&feed=member">membros</a></li>
					</ul>
				</nav>
				
			<?php 
				// ------------------------------------------------------------------------------------------
				// feed de orações do grupo
				// ------------------------------------------------------------------------------------------
				if ($feed == 'oracoes') { 
			?>
				
				<? include_once($_SERVER['DOCUMENT_ROOT'] . "/inc_timeline.php"); ?>
			
			<?php 
				// ------------------------------------------------------------------------------------------
				// informações sobre o grupo
				// ------------------------------------------------------------------------------------------
				} else if ($feed == 'info') { 
			?>
				
				<?php
					/*
					$details 	= getDetails($socialObject['id']);
					$properties = getProperties($socialObject['id']);
					*/
					$disabled	= 'disabled';
					if($userObject['id'] == $profileObject['id']) {
						$disabled = '';
					}
				?>
				
				<?php
					// ------------------------------------------------------------------------------------------
					// administrador acessando a página (exibe o formulário para edição)
					// ------------------------------------------------------------------------------------------
					if ($userObject['id'] == $profileObject['id']) {
				?>
				
				<form id="form-group" action="javascript:updateGroup(<?=$socialObject['id'];?>, 'form#form-group');">
					
					<fieldset>
					
						<legend>Informações</legend>
						
						<p><label class="label" for="sDisplayName">Nome:</label><input id="sDisplayName" type="text" value="<?=$socialObject['sDisplayName'];?>" <?=$disabled?>></p>
						
						<p>
						<label class="label" for="iGroupType">Objetivo:</label><select class="input" id="iGroupType" value="<?=$properties['iGroupType'];?>" <?=$disabled?> required>
							<option <?=$properties['iGroupType']=='1' ? 'selected' : '';?> value="1">Oração</option>
							<option <?=$properties['iGroupType']=='2' ? 'selected' : '';?> value="2">Meditação</option>
							<option <?=$properties['iGroupType']=='3' ? 'selected' : '';?> value="3">Vigília</option>
						</select>
						</p>
						
						<p><label class="label" for="sGroupTheme">Tema:</label><input id="sGroupTheme" type="text" value="<?=$properties['sGroupTheme'];?>" <?=$disabled?>></p>
						
						<br>
						
						<p>
						<label class="label" for="dtBegin">Período:</label><input id="dtBegin" type="text" placeholder="dd/mm/aaaa" pattern="^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/(19|20)\d{2}$" maxlength="10" required value="<?=$properties['dtBegin'];?>" <?=$disabled?> style="width:100px;"> até <input type="text" id="dtEnd" placeholder="dd/mm/aaaa" pattern="^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/(19|20)\d{2}$" maxlength="10" required value="<?=$properties['dtEnd'];?>" <?=$disabled?> style="width:100px;">
						</p>
						
						<p>
						<label class="label" for="iPeriodicity">Periodicidade:</label><select class="input" id="iPeriodicity" value="<?=$properties['iPeriodicity'];?>" <?=$disabled?> required onchange="javascript:
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
							<option <?=$properties['iPeriodicity']=='1' ? 'selected' : '';?> value="1">Diária</option>
							<option <?=$properties['iPeriodicity']=='2' ? 'selected' : '';?> value="2">Semanal</option>
							<option <?=$properties['iPeriodicity']=='3' ? 'selected' : '';?> value="3">Mensal</option>
							<option <?=$properties['iPeriodicity']=='4' ? 'selected' : '';?> value="4">Anual</option>
						</select>
						</p>
						
						<?php 
							$display2 = $properties['iPeriodicity']=='2' ? '' : 'style="display:none;"';
							$display3 = $properties['iPeriodicity']=='3' ? '' : 'style="display:none;"';
							$display4 = $properties['iPeriodicity']=='4' ? '' : 'style="display:none;"';
						?>
						
						<p <?=$display2;?>>
						<label class="label" for="iPeriodicity2">dia da semana:</label><select id="iPeriodicity2"<?=$disabled?> value="<?=$properties['iPeriodicityDetail'];?>">
							<option value="" disabled>selecione</option>
							<option <?=$properties['iPeriodicityDetail']=='1' && $properties['iPeriodicity']=='2' ? 'selected' : '';?> value="1">Domingo</option>
							<option <?=$properties['iPeriodicityDetail']=='2' && $properties['iPeriodicity']=='2' ? 'selected' : '';?> value="2">Segunda-feira</option>
							<option <?=$properties['iPeriodicityDetail']=='3' && $properties['iPeriodicity']=='2' ? 'selected' : '';?> value="3">Terça-feira</option>
							<option <?=$properties['iPeriodicityDetail']=='4' && $properties['iPeriodicity']=='2' ? 'selected' : '';?> value="4">Quarta-feira</option>
							<option <?=$properties['iPeriodicityDetail']=='5' && $properties['iPeriodicity']=='2' ? 'selected' : '';?> value="5">Quinta-feira</option>
							<option <?=$properties['iPeriodicityDetail']=='6' && $properties['iPeriodicity']=='2' ? 'selected' : '';?> value="6">Sexta-feira</option>
							<option <?=$properties['iPeriodicityDetail']=='7' && $properties['iPeriodicity']=='2' ? 'selected' : '';?> value="7">Sábado</option>
						</select>
						</p>
						
						<p <?=$display3;?>>
						<label class="label" for="iPeriodicity3">dia do mês:</label><select id="iPeriodicity3" <?=$disabled?> value="<?=$properties['iPeriodicityDetail'];?>">
							<option value="" disabled>selecione</option>
							<option <?=$properties['iPeriodicityDetail']=='1'  && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="1">1</option>
							<option <?=$properties['iPeriodicityDetail']=='2'  && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="2">2</option>
							<option <?=$properties['iPeriodicityDetail']=='3'  && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="3">3</option>
							<option <?=$properties['iPeriodicityDetail']=='4'  && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="4">4</option>
							<option <?=$properties['iPeriodicityDetail']=='5'  && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="5">5</option>
							<option <?=$properties['iPeriodicityDetail']=='6'  && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="6">6</option>
							<option <?=$properties['iPeriodicityDetail']=='7'  && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="7">7</option>
							<option <?=$properties['iPeriodicityDetail']=='8'  && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="8">8</option>
							<option <?=$properties['iPeriodicityDetail']=='9'  && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="9">9</option>
							<option <?=$properties['iPeriodicityDetail']=='10' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="10">10</option>
							<option <?=$properties['iPeriodicityDetail']=='11' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="11">11</option>
							<option <?=$properties['iPeriodicityDetail']=='12' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="12">12</option>
							<option <?=$properties['iPeriodicityDetail']=='13' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="13">13</option>
							<option <?=$properties['iPeriodicityDetail']=='14' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="14">14</option>
							<option <?=$properties['iPeriodicityDetail']=='15' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="15">15</option>
							<option <?=$properties['iPeriodicityDetail']=='16' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="16">16</option>
							<option <?=$properties['iPeriodicityDetail']=='17' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="17">17</option>
							<option <?=$properties['iPeriodicityDetail']=='18' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="18">18</option>
							<option <?=$properties['iPeriodicityDetail']=='19' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="19">19</option>
							<option <?=$properties['iPeriodicityDetail']=='20' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="20">20</option>
							<option <?=$properties['iPeriodicityDetail']=='21' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="21">21</option>
							<option <?=$properties['iPeriodicityDetail']=='22' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="22">22</option>
							<option <?=$properties['iPeriodicityDetail']=='23' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="23">23</option>
							<option <?=$properties['iPeriodicityDetail']=='24' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="24">24</option>
							<option <?=$properties['iPeriodicityDetail']=='25' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="25">25</option>
							<option <?=$properties['iPeriodicityDetail']=='26' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="26">26</option>
							<option <?=$properties['iPeriodicityDetail']=='27' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="27">27</option>
							<option <?=$properties['iPeriodicityDetail']=='28' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="28">28</option>
							<option <?=$properties['iPeriodicityDetail']=='29' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="29">29</option>
							<option <?=$properties['iPeriodicityDetail']=='30' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="30">30</option>
							<option <?=$properties['iPeriodicityDetail']=='31' && $properties['iPeriodicity']=='3' ? 'selected' : '';?> value="31">31</option>
						</select>
						</p>
						
						<p <?=$display4;?>>
						<label class="label" for="iPeriodicity4">mês:</label><select id="iPeriodicity4" <?=$disabled?> value="<?=$properties['iPeriodicityDetail'];?>">
							<option value="" disabled>selecione</option>
							<option <?=$properties['iPeriodicityDetail']=='1'  && $properties['iPeriodicity']=='4' ? 'selected' : '';?> value="1">Janeiro</option>
							<option <?=$properties['iPeriodicityDetail']=='2'  && $properties['iPeriodicity']=='4' ? 'selected' : '';?> value="2">Fevereiro</option>
							<option <?=$properties['iPeriodicityDetail']=='3'  && $properties['iPeriodicity']=='4' ? 'selected' : '';?> value="3">Março</option>
							<option <?=$properties['iPeriodicityDetail']=='4'  && $properties['iPeriodicity']=='4' ? 'selected' : '';?> value="4">Abril</option>
							<option <?=$properties['iPeriodicityDetail']=='5'  && $properties['iPeriodicity']=='4' ? 'selected' : '';?> value="5">Maio</option>
							<option <?=$properties['iPeriodicityDetail']=='6'  && $properties['iPeriodicity']=='4' ? 'selected' : '';?> value="6">Junho</option>
							<option <?=$properties['iPeriodicityDetail']=='7'  && $properties['iPeriodicity']=='4' ? 'selected' : '';?> value="7">Julho</option>
							<option <?=$properties['iPeriodicityDetail']=='8'  && $properties['iPeriodicity']=='4' ? 'selected' : '';?> value="8">Agosto</option>
							<option <?=$properties['iPeriodicityDetail']=='9'  && $properties['iPeriodicity']=='4' ? 'selected' : '';?> value="9">Setembro</option>
							<option <?=$properties['iPeriodicityDetail']=='10' && $properties['iPeriodicity']=='4' ? 'selected' : '';?> value="10">Outubro</option>
							<option <?=$properties['iPeriodicityDetail']=='11' && $properties['iPeriodicity']=='4' ? 'selected' : '';?> value="11">Novembro</option>
							<option <?=$properties['iPeriodicityDetail']=='12' && $properties['iPeriodicity']=='4' ? 'selected' : '';?> value="12">Dezembro</option>
						</select>
						</p>
						
						<p>
						<label class="label" for="tsOracao">horário local da oração:</label><select id="tsOracao" <?=$disabled?> value="<?=$properties['tsOracao'];?>" style="width:100px;">
							<option <?=$properties['tsOracao']=='00:00:00' ? 'selected' : '';?> value="00:00:00">00:00</option>
							<option <?=$properties['tsOracao']=='00:30:00' ? 'selected' : '';?> value="00:30:00">00:30</option>
							<option <?=$properties['tsOracao']=='01:00:00' ? 'selected' : '';?> value="01:00:00">01:00</option>
							<option <?=$properties['tsOracao']=='01:30:00' ? 'selected' : '';?> value="01:30:00">01:30</option>
							<option <?=$properties['tsOracao']=='02:00:00' ? 'selected' : '';?> value="02:00:00">02:00</option>
							<option <?=$properties['tsOracao']=='02:30:00' ? 'selected' : '';?> value="02:30:00">02:30</option>
							<option <?=$properties['tsOracao']=='03:00:00' ? 'selected' : '';?> value="03:00:00">03:00</option>
							<option <?=$properties['tsOracao']=='03:30:00' ? 'selected' : '';?> value="03:30:00">03:30</option>
							<option <?=$properties['tsOracao']=='04:00:00' ? 'selected' : '';?> value="04:00:00">04:00</option>
							<option <?=$properties['tsOracao']=='04:30:00' ? 'selected' : '';?> value="04:30:00">04:30</option>
							<option <?=$properties['tsOracao']=='05:00:00' ? 'selected' : '';?> value="05:00:00">05:00</option>
							<option <?=$properties['tsOracao']=='05:30:00' ? 'selected' : '';?> value="05:30:00">05:30</option>
							<option <?=$properties['tsOracao']=='06:00:00' ? 'selected' : '';?> value="06:00:00">06:00</option>
							<option <?=$properties['tsOracao']=='06:30:00' ? 'selected' : '';?> value="06:30:00">06:30</option>
							<option <?=$properties['tsOracao']=='07:00:00' ? 'selected' : '';?> value="07:00:00">07:00</option>
							<option <?=$properties['tsOracao']=='07:30:00' ? 'selected' : '';?> value="07:30:00">07:30</option>
							<option <?=$properties['tsOracao']=='08:00:00' ? 'selected' : '';?> value="08:00:00">08:00</option>
							<option <?=$properties['tsOracao']=='08:30:00' ? 'selected' : '';?> value="08:30:00">08:30</option>
							<option <?=$properties['tsOracao']=='09:00:00' ? 'selected' : '';?> value="09:00:00">09:00</option>
							<option <?=$properties['tsOracao']=='09:30:00' ? 'selected' : '';?> value="09:30:00">09:30</option>
							<option <?=$properties['tsOracao']=='10:00:00' ? 'selected' : '';?> value="10:00:00">10:00</option>
							<option <?=$properties['tsOracao']=='10:30:00' ? 'selected' : '';?> value="10:30:00">10:30</option>
							<option <?=$properties['tsOracao']=='11:00:00' ? 'selected' : '';?> value="11:00:00">11:00</option>
							<option <?=$properties['tsOracao']=='11:30:00' ? 'selected' : '';?> value="11:30:00">11:30</option>
							<option <?=$properties['tsOracao']=='12:00:00' ? 'selected' : '';?> value="12:00:00">12:00</option>
							<option <?=$properties['tsOracao']=='12:30:00' ? 'selected' : '';?> value="12:30:00">12:30</option>
							<option <?=$properties['tsOracao']=='13:00:00' ? 'selected' : '';?> value="13:00:00">13:00</option>
							<option <?=$properties['tsOracao']=='13:30:00' ? 'selected' : '';?> value="13:30:00">13:30</option>
							<option <?=$properties['tsOracao']=='14:00:00' ? 'selected' : '';?> value="14:00:00">14:00</option>
							<option <?=$properties['tsOracao']=='14:30:00' ? 'selected' : '';?> value="14:30:00">14:30</option>
							<option <?=$properties['tsOracao']=='15:00:00' ? 'selected' : '';?> value="15:00:00">15:00</option>
							<option <?=$properties['tsOracao']=='15:30:00' ? 'selected' : '';?> value="15:30:00">15:30</option>
							<option <?=$properties['tsOracao']=='16:00:00' ? 'selected' : '';?> value="16:00:00">16:00</option>
							<option <?=$properties['tsOracao']=='16:30:00' ? 'selected' : '';?> value="16:30:00">16:30</option>
							<option <?=$properties['tsOracao']=='17:00:00' ? 'selected' : '';?> value="17:00:00">17:00</option>
							<option <?=$properties['tsOracao']=='17:30:00' ? 'selected' : '';?> value="17:30:00">17:30</option>
							<option <?=$properties['tsOracao']=='18:00:00' ? 'selected' : '';?> value="18:00:00">18:00</option>
							<option <?=$properties['tsOracao']=='18:30:00' ? 'selected' : '';?> value="18:30:00">18:30</option>
							<option <?=$properties['tsOracao']=='19:00:00' ? 'selected' : '';?> value="19:00:00">19:00</option>
							<option <?=$properties['tsOracao']=='19:30:00' ? 'selected' : '';?> value="19:30:00">19:30</option>
							<option <?=$properties['tsOracao']=='20:00:00' ? 'selected' : '';?> value="20:00:00">20:00</option>
							<option <?=$properties['tsOracao']=='20:30:00' ? 'selected' : '';?> value="20:30:00">20:30</option>
							<option <?=$properties['tsOracao']=='21:00:00' ? 'selected' : '';?> value="21:00:00">21:00</option>
							<option <?=$properties['tsOracao']=='21:30:00' ? 'selected' : '';?> value="21:30:00">21:30</option>
							<option <?=$properties['tsOracao']=='22:00:00' ? 'selected' : '';?> value="22:00:00">22:00</option>
							<option <?=$properties['tsOracao']=='22:30:00' ? 'selected' : '';?> value="22:30:00">22:30</option>
							<option <?=$properties['tsOracao']=='23:00:00' ? 'selected' : '';?> value="23:00:00">23:00</option>
							<option <?=$properties['tsOracao']=='23:30:00' ? 'selected' : '';?> value="23:30:00">23:30</option>
						</select>
						</p>
						
						<br>
						
						<p>
						<label class="label" for="idADMINCountry">País:</label><select name="idADMINCountry" id="idADMINCountry" value="<?=$properties['idADMINCountry'];?>" <?=$disabled?> required>
							<option selected disabled value="">selecione o país</option>
							<option <?=$properties['idADMINCountry']=='BR' ? 'selected' : '';?> value="BR">Brasil</option>
						</select>
						</p>
						
						<p>
						<label class="label" for="idADMINState">Estado:</label><select name="idADMINState" id="idADMINState" value="<?=$properties['idADMINState'];?>" <?=$disabled?> required>
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
						
						<p>
						<label class="label" for="idADMINCity">Cidade:</label><input id="idADMINCity" type="text" value="<?=$properties['idADMINCity'];?>" <?=$disabled?>>
						</p>
						
						<br>
						
						<p>
						<label class="label" for="sDescription">Instruções:</label><textarea name="sDescription" id="sDescription" rows="5" style="width:350px;max-width:350px;"><?=isset($details['sContent']) ? $details['sContent'] : '';?></textarea>
						</p>
						
						<p>
						<label class="label" for="sSite">Site:</label><input id="sSite" type="text" value="<?=$properties['sSite'];?>" <?=$disabled?>>
						</p>
						
					</fieldset>
					
					<?php if($userObject['id'] == $profileObject['id']) { ?>
						<input type="submit" class="basic-button" value="salvar alterações">
					<?php } ?>
				
				</form>
				
				<?php
					// ------------------------------------------------------------------------------------------
					// usuário não-administrador acessando a página (exibe as informações apenas como texto)
					// ------------------------------------------------------------------------------------------
					} else {
				?>
					
					<h2>Informações</h2>
					
					<p>
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:125px;text-align:right;">Nome:</label><?=$socialObject['sDisplayName'];?><br>
					
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:125px;text-align:right;">Objetivo:</label><?php
							switch($properties['iGroupType']) 
							{
								case '1' : echo 'Oração'; 	 break;
								case '2' : echo 'Meditação'; break;
								case '3' : echo 'Vigília'; 	 break;
							}
						?><br>
					
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:125px;text-align:right;">Tema:</label><?=$properties['sGroupTheme'];?></p>
					
					<br>
					
					<p>
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:125px;text-align:right;">Período:</label><?=$properties['dtBegin'];?> até <?=$properties['dtEnd'];?><br>
					
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:125px;text-align:right;">Periodicidade:</label><?php
							switch($properties['iPeriodicity']) 
							{
								case '1' : echo 'Diária'; 	break;
								case '2' : echo 'Semanal'; 	break;
								case '3' : echo 'Mensal'; 	break;
								case '4' : echo 'Anual'; 	break;
							}
						?><br>
					
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:125px;text-align:right;">Frequência:</label><?php
							switch($properties['iPeriodicity'])
							{
								case '2' :
							
									switch($properties['iPeriodicityDetail']) 
									{
										case '1' : echo 'Domingo';		 break;
										case '2' : echo 'Segunda-feira'; break;
										case '3' : echo 'Terça-feira';	 break;
										case '4' : echo 'Quarta-feira';	 break;
										case '5' : echo 'Quinta-feira';	 break;
										case '6' : echo 'Sexta-feira';	 break;
										case '7' : echo 'Sábado';		 break;
									}
								
								break;
								
								case '3' :
							
									echo $properties['iPeriodicityDetail'];
									
								break;
								
								case '4' :
							
									switch($properties['iPeriodicityDetail']) 
									{
										case '1'  : echo 'Janeiro';	  break;
										case '2'  : echo 'Fevereiro'; break;
										case '3'  : echo 'Março';	  break;
										case '4'  : echo 'Abril';	  break;
										case '5'  : echo 'Maio';	  break;
										case '6'  : echo 'Junho';	  break;
										case '7'  : echo 'Julho';	  break;
										case '8'  : echo 'Agosto';	  break;
										case '9'  : echo 'Setembro';  break;
										case '10' : echo 'Outubro';   break;
										case '11' : echo 'Novembro';  break;
										case '12' : echo 'Dezembro';  break;
									}
								
								break;
							
							}
					?><br>
					
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:125px;text-align:right;">horário local da oração:</label>
						<?php
							echo $properties['tsOracao'];
						?></p>
					
					<br>
					
					<p>
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:125px;text-align:right;">País:</label><?php
						switch($properties['idADMINCountry']) 
						{
							case 'BR' : echo 'Brasil'; 	break;
						}
					?><br>
					
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:125px;text-align:right;">Estado:</label>
					<?php
						switch($properties['idADMINState']) 
						{
							case 'AC' : echo 'Acre'; 				break;
							case 'AL' : echo 'Alagoas'; 			break;
							case 'AP' : echo 'Amapá'; 				break;
							case 'AM' : echo 'Amazonas'; 			break;
							case 'BA' : echo 'Bahia'; 				break;
							case 'CE' : echo 'Ceará'; 				break;
							case 'DF' : echo 'Distrito Federal';	break;
							case 'ES' : echo 'Espirito Santo';		break;
							case 'GO' : echo 'Goiás'; 				break;
							case 'MA' : echo 'Maranhão'; 			break;
							case 'MS' : echo 'Mato Grosso do Sul'; 	break;
							case 'MT' : echo 'Mato Grosso'; 		break;
							case 'MG' : echo 'Minas Gerais'; 		break;
							case 'PA' : echo 'Pará'; 				break;
							case 'PB' : echo 'Paraíba'; 			break;
							case 'PR' : echo 'Paraná'; 				break;
							case 'PE' : echo 'Pernambuco'; 			break;
							case 'PI' : echo 'Piauí'; 				break;
							case 'RJ' : echo 'Rio de Janeiro'; 		break;
							case 'RN' : echo 'Rio Grande do Norte'; break;
							case 'RS' : echo 'Rio Grande do Sul'; 	break;
							case 'RO' : echo 'Rondônia'; 			break;
							case 'RR' : echo 'Roraima'; 			break;
							case 'SC' : echo 'Santa Catarina'; 		break;
							case 'SP' : echo 'São Paulo'; 			break;
							case 'SE' : echo 'Sergipe'; 			break;
							case 'TO' : echo 'Tocantins'; 			break;
						}
					?><br>
					
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:125px;text-align:right;">Cidade:</label><?=$properties['idADMINCity'];?></p>
					
					<br>
					
					<p>
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:125px;text-align:right;">Instruções:</label><?=isset($details['sContent']) ? $details['sContent'] : '';?></p>
					
					<p>
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:125px;text-align:right;">Site:</label><?=identifyLink($properties['sSite']);?></p>
				
				<?php } ?>
				
			<?php
				// ------------------------------------------------------------------------------------------
				// membros do grupo
				// ------------------------------------------------------------------------------------------
				} else if ($feed == 'member') {
			?>
				
				<?php 
					$list = getAssociations($socialObject['id'],'OBJECT','GROUP','MEMBER');
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
								<button type="button" class="basic-button remove" onclick="javascript:GroupUnfollow(<?=$list[$i]['id'];?>, <?=$socialObject['id'];?>);">remover membro</button>
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
