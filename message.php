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
	
	if(isset($socialObject['id']) && $socialObject['id'] != $userObject['id']) {
		
		$idConversation   	= $socialObject['id'];
		$listConversa		= getAssociations($idConversation, 'OBJECT', 'CONVERSATION', 'CONVERSA', 'o.tsCreation ASC');	
		$listParticipant    = getAssociations($idConversation, 'OBJECT', 'CONVERSATION', 'PARTICIPANT');
		
	} else {
		
		$idConversation		= null;
		$listFriend 		= getAssociations($userObject['id'], 'FRIEND', 'FRIENDSHIP', 'FRIEND');
		$listConversation 	= getAssociations($userObject['id'], 'PARTICIPANT', 'CONVERSATION', 'OBJECT', 'o.tsCreation DESC');
	}
	

?>

		<!-- conteudo message -->
		
		<div id="content">
		
			<aside id="profile">
			
				<!-- foto do perfil -->
				<div class="photo-profile">
				
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$userObject['id'];?>prob=<?=$userObject['id'];?>"><img src="<?= ($userObject['properties']['sAvatarPath']) ? $userObject['properties']['sAvatarPath'] : "img/avatar-default.jpg"; ?>" alt="<?= $userObject['sDisplayName']; ?>" border="0" width="150"></a>
					
					<?php if(isset($userObject)) {?>
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$userObject['id'];?>prob=<?=$userObject['id'];?>&feed=info">editar meu perfil</a>
					<?php } ?>
					
				</div>
				
			</aside>
			
			<?php 
			// --------------------------------------------------------------------------------
			// end : mensagens
			// exibe a lista de conversations do usuário (socialObject[id] == NULL)
			if ($idConversation == null) {
			?>
			
				<section id="main-flow">
					
					<div id="profile-summary" style="min-height:50px">
					
						<h1>Mensagens</h1>
						
						<div id="new-message">criar nova mensagem</div>
						
					</div>
					
					<!-- formulário nova mensagem -->
					<div class="post-box" style="display:none">
					
						<h3>Nova mensagem</h3>
						
						<form id="message-form" action="javascript:messageCreate();">
						
							<input id="idConversation" type="hidden" value="">
							
							<div class="row">
								<label class="label">para :</label>
								
								<select id="idReceiver" class="receiver" required style="margin-top:0px" multiple>
									<option value="" disabled>Selecione um amigo</option>
									<?php 
									for ($i=0;$i<count($listFriend);$i++) { 
										echo '<option value="'.$listFriend[$i]['id'].'">'.$listFriend[$i]['sDisplayName'].'</option>';
									} ?>
								</select>
								
								<button type="button" class="basic-button" style="display:none; padding:7px; margin-bottom:5px" >x</button>
							</div>
							
							<div>
								<label class="label">mensagem :</label>
								<textarea required style="width:400px"></textarea>
							</div>
							
							<div align="right">
								<input type="button" class="share-button cancelar" value="cancelar">
								<input type="submit" class="share-button" value="enviar">
							</div>
							
						</form>
					
					</div>
				
					<?php 
					// lista de OBJECTS iType CONVERSATION
					for ($i=0; $i < count($listConversation); $i++) {
					
						$listParticipant = getAssociations($listConversation[$i]['id'],'OBJECT','CONVERSATION','PARTICIPANT', 'o.tsCreation DESC');
						
						$listMessage = getAssociations($listConversation[$i]['id'],'OBJECT','CONVERSATION','CONVERSA', 'o.tsCreation DESC');
						
						$query = 	'SELECT soam.id, soam.fAck FROM tbSOCIALObjectAssociation as soa

										INNER JOIN tbSOCIALObjectAssociation as soam
										ON soam.idSOCIALAssociation = soa.idSOCIALAssociation
										
									WHERE soa.iType = 3 AND soa.idSOCIALObject = '.$listMessage[0]['id'].' AND soam.iType = 12 AND soam.idSOCIALObject = '.$userObject['id'];
									
						$result = mysql_query($query);
						$row 	= mysql_fetch_assoc($result);
						
						// print_r($listMessage);
						// print_r($row);
						
						$bgColor = '';
						
						if(isset($row['fAck']) && $row['fAck'] == 0) {
						
							$bgColor = "style='background-color:#ececec'";
						}
						
						$idParticipant 		= array();
						$nameParticipant	= array();
						
						for($n = 0; $n < count($listParticipant); $n++) {
						
							if ($listParticipant[$n]['id'] != $userObject['id']) {
								
								// $idParticipant 	 .= $listParticipant[$n]['id'].',';
								// $nameParticipant .= $listParticipant[$n]['sDisplayName'].', ';
								
								$idParticipant[]   = $listParticipant[$n]['id'];
								$nameParticipant[] = $listParticipant[$n]['sDisplayName'];
							}
						}
						
						
						$idParticipant   = implode(',',$idParticipant);
						$nameParticipant = implode(', ',$nameParticipant);
						// $nameParticipant = substr_replace($nameParticipant,"",-2);
						
					?>
						
						<div class="conversation" id="<?php echo $listConversation[$i]['id'] ?>" <?php echo $bgColor ?> >
							
							<div>
							
								<?php
								for($n=0; $n < count($listParticipant); $n++) {
							
									if($listParticipant[$n]['id'] != $userObject['id']) {
										$sAvatarPath = getProperties($listParticipant[$n]['id'],'sAvatarPath');
										$sAvatarPath = $sAvatarPath['sAvatarPath'];
										
										if($sAvatarPath != '') {
										
											echo '<div class="photo"><img src="'.$sAvatarPath.'" alt="" border="0" width="50" height="50" style="margin-right:5px"></div>';
										} else {
											echo '<div class="photo"><img src="img/avatar-default.jpg" alt="" border="0" width="50" height="50" style="margin-right:5px"></div>';
										}
									
									}
									
								} ?>	
								
								<!-- <div style="display:inline-block; vertical-align:top; padding-left:5px"> -->
								<div class="name-location">
									<strong><?php echo $nameParticipant ?></strong>
								</div>
								
								<div class="button">
									<button type="button" class="basic-button remove" onclick="javascript:window.location='message.php?id=<?php echo $_GET['id'];?>&sob=<?php echo $listConversation[$i]['id'] ?>&prob=<?php echo $idParticipant; ?>';">ver conversa</button>
								</div>
								
							</div>
						
						</div>		
						
					<?php
					} ?>
				
				</section>
			
			<?php 
			// --------------------------------------------------------------------------------
			// exibe a lista de mensagens dentro da conversation acessada (socialObject[id])
			
			} else {
			?>
			
				<section id="main-flow">
					
					<div id="profile-summary" style="height:60px">
						
					<?php
						
						$listMessage = getAssociations($socialObject['id'],'OBJECT','CONVERSATION','CONVERSA', 'o.tsCreation DESC');
						
						$query = 	'SELECT soam.id, soam.fAck FROM tbSOCIALObjectAssociation as soa

										INNER JOIN tbSOCIALObjectAssociation as soam
										ON soam.idSOCIALAssociation = soa.idSOCIALAssociation
										
									WHERE soa.iType = 3 AND soa.idSOCIALObject = '.$listMessage[0]['id'].' AND soam.iType = 12 AND soam.idSOCIALObject = '.$userObject['id'];
									
						$result = mysql_query($query);
						$row 	= mysql_fetch_assoc($result);
						
						if(isset($row['fAck']) && $row['fAck'] == 0) {
						
							$query = 	'UPDATE 
											tbSOCIALObjectAssociation
										SET 
											fAck = 1
										WHERE  id = '.$row['id'];
							
							$result = mysql_query($query);
							
						}
						
						$nameParticipant	= array();
						
						for ($i = 0; $i < count($listParticipant); $i++) {
						
							if($listParticipant[$i]['id'] != $userObject['id']) {
							
								$nameParticipant[] = '<a href="index.php?id='.$_GET['id'].'&sob='.$listParticipant[$i]['id'].'&prob='.$listParticipant[$i]['id'].'">'.$listParticipant[$i]['sDisplayName'].'</a> ';
							}
							
						} 
						
						$nameParticipant = implode(', ',$nameParticipant);
						echo '<h1>'.$nameParticipant.'</h1><br>';
						
					?>
						
						<button type="button" class="basic-button" onclick="window.location='message.php?id=<?php echo $_GET['id']?>&sob=<?php echo $_SESSION['loggedUser'] ?>&prob=<?php echo $_SESSION['loggedUser'] ?>'" style="float:right;">voltar para mensagens</button>
						
						<? if (false) { ?>
						<a href="message.php?id<?php echo $_GET['id']?>&sob=<?php echo $_SESSION['loggedUser'] ?>&prob=<?php echo $_SESSION['loggedUser'] ?>" target="_self">
						<div class="share-button" style="width:112px;height:18px;background-color:#F58322;color:#FFF;padding:8px;margin-left:15px;cursor:pointer;float:right;vertical-align:top;border:none;"> voltar para todas as mensagens</div></a>
						<? } ?>
						
					</div>
					
					<?php 
					for ($i=0; $i < count($listConversa); $i++) {
						
						$sMessage = getDetails($listConversa[$i]['id']);
						
						$listSender = getAssociations($listConversa[$i]['id'], 'OBJECT', 'MESSAGE', 'SENDER');?>
						
						<div class="user">
							<div>
							
							<?php 
								for ($n=0; $n < count($listSender); $n++) {
							?>
								
								<div style="display:block; height:15px">
								
									<a href="index.php?id=<?php echo $listSender[$n]['id']; ?>">
									<div style="float:left; width:70%"><strong><?php echo $listSender[$n]['sDisplayName']; ?></strong></div>
									</a>
									<div style="float:right; text-align:right; color:#999; width:30%"><?php echo showDate($listConversa[$i]['tsCreation'], 'timestamp') ?></div>
									
								</div>
							<?php
								}
							?>
								
								<div style="clear:right; margin-top:10px"><?php echo $sMessage['sMessage']; ?></div>
							</div>
						</div>
					
					<?php } ?>
					
					<div class="post-box">
					
						<form id="message-form" action="javascript:messageCreate();">
							<input id="idReceiver" type="hidden" value="<?php echo $_GET['prob']; ?>">
							<input id="idConversation" type="hidden" value="<?php echo $_GET['sob']; ?>">	
							<div>
								<label class="label">mensagem :</label>
								<textarea required style="width:400px"></textarea>
							</div>
							<div align="right">
								<input type="submit" class="share-button" value="enviar">
							</div>
						</form>
						
					</div>
					
				</section>
			
			<?php 
			// --------------------------------------------------------------------------------
			// end : mensagens
			}
			?>
			
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
