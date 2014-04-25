<?php 
	// bibliotecas compartilhadas:
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/AppConnect.php");
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/libSocial.php");
	
	session_start();
	
	
	
	// ----------------------------------------------------------------------------------------------------
	// informações sobre usuário logado:
	if ( !isset($_SESSION["loggedUser"]) ) {
		$_SESSION["loggedUser"] = 0;
	}
	
	// ----------------------------------------------------------------------------------------------------
	// verifica se é um usuário com permissões de ADMINISTRAÇÃO:
	if ($_SESSION["loggedUser"] == 1 || $_SESSION["loggedUser"] == 41 || $_SESSION["loggedUser"] == 70 || $_SESSION["loggedUser"] == 788) {
		$usob = $_SESSION["loggedUser"];
		$userObject = getObject($usob);
		$userObject['properties'] = getProperties($usob);
	} else {
		$_SESSION["loggedUser"] = 0;
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
		<!-- conteudo admin page -->
		<div id="content">
			
		<div><h1 style="font-size:24px; color:#f58322; margin:0px 0px 25px 0px; padding:0px">Administração</h1></div>
		
			<section id="main-flow" class="wide" style="width:680px; margin:0 15px 0 0;">
				
				<div style="width:680px;display:inline-block;vertical-align:top;">
					
					<h2>Moderação</h2>
					<h3>Denúncias de posts</h3>
					<?php
						
						// todos os reports:
						$reports = getObjects(12);
						
						if (count($reports) > 0) {
							
						
						
							$reportsIdList = array();
							foreach($reports as $r) {
								$reportsIdList[] = $r['id'];
							}
							
							// POSTS, PHOTOS e COMMENTS que foram reportados:
							$reportedObjects = getAssociations($reportsIdList, 'OBJECT', 'REPORT', 'REFERRED', 'o.tsCreation DESC', 0, 0, '0');
							
							echo '<ul style="background-color:#F4F4F4;list-style:none;">';
						
							foreach($reportedObjects as $item) {
								
								$item['properties']	 = getProperties($item['id'],'');
								$item['details']	 = getDetails($item['id'],'');
								
								$owner 				 = getAssociations($item['id'], 'OBJECT', '', 'OWNER');
								$owner 				 = getObject($owner[0]['id']);
							//	$owner['properties'] = getProperties($owner['id'],'');
								
								$report				 = getAssociations($item['id'], 'REFERRED', 'REPORT', 'OBJECT');
								$report				 = getObject($report[0]['id']);
								$report['properties']= getProperties($report['id']);
								
								$reporter			 = getAssociations($item['id'], 'REFERRED', 'REPORT', 'OWNER');
								$reporter			 = getObject($reporter[0]['id']);
							
		echo '<li style="padding:5px;margin:0 0 3px 0;background-color:#FAFAFA;min-height:60px;">';
		
		echo 	'<div style="display:inline;float:right;">';
		
		echo 		'<button type="button" class="basic-button accept" onclick="javascript:deleteReport('.$report['id'].');" title="a denúncia sobre este conteúdo será removida">ignorar denúncia</button>&nbsp;';
		
		echo 		'<button type="button" class="basic-button remove" onclick="javascript:deleteObject('.$item['id'].');" title="o conteúdo será excluído da rede social e não será mais visível pelos usuários">excluir conteúdo</button>';
		
		echo 	'</div>';
		
		echo 	'<blockquote style="margin:2px 0;font-size:13px;">'.$item['details']['sContent'].'</blockquote>';
		
		echo 	'<a href="index.php?id='.uniqid().'&sob='.$item['id'].'&prob='.$owner['id'].'" target="_blank">Conteúdo</a> postado por <a href="index.php?id='.uniqid().'&sob='.$owner['id'].'&prob='.$owner['id'].'" target="_blank">'.$owner['sDisplayName'].'</a> ';
		echo 	'<em style="font-size:10px;color:#999;">em <a href="index.php?id='.uniqid().'&sob='.$item['id'].'&prob='.$owner['id'].'" target="_blank" style="color:#999;">'.showDate($item['tsCreation'],true).'</a></em>';
		echo 	'<br>tipo: '.$report['properties']['sAbuseType'].'';
		echo 	'<br>descrição: '.$report['properties']['sDescription'].'';
		
		echo 	'<br><em style="font-size:10px;color:#999;">denunciado por <a href="index.php?id='.uniqid().'&sob='.$reporter['id'].'&prob='.$reporter['id'].'" target="_blank" style="color:#999;">'.$reporter['sDisplayName'].'</a></em>';
		
		echo '</li>';
						
							}
						
							echo '</ul>';
						
						}
						
					?>
					
					
					
				</div>
				
			</section>
			
			<aside id="banner">
					<h2>Estatísticas da rede social</h2>
					
					<p>
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:200px;">Número de perfis cadastrados:</label><?php 
						$query = "SELECT COUNT(ID) AS iTotal FROM tbSOCIALObject WHERE iType = 1;";
						$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);
						while($row = mysql_fetch_assoc($result)) {
							$iTotal = $row['iTotal'];
						}
						echo $iTotal;
					?><br>
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:200px;">Número de perfis ativos:</label><?php 
						$query = "SELECT COUNT(ID) AS iTotal FROM tbSOCIALObject WHERE iType = 1 AND fDeleted <> 1; ";
						$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);
						while($row = mysql_fetch_assoc($result)) {
							$iTotal = $row['iTotal'];
						}
						echo $iTotal;
					?><br>
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:200px;">Número de páginas:</label><?php 
						$query = "SELECT COUNT(ID) AS iTotal FROM tbSOCIALObject WHERE iType = 2; ";
						$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);
						while($row = mysql_fetch_assoc($result)) {
							$iTotal = $row['iTotal'];
						}
						echo $iTotal;
					?><br>
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:200px;">Número de grupos:</label><?php 
						$query = "SELECT COUNT(ID) AS iTotal FROM tbSOCIALObject WHERE iType = 5; ";
						$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);
						while($row = mysql_fetch_assoc($result)) {
							$iTotal = $row['iTotal'];
						}
						echo $iTotal;
					?><br>
					</p>
					
					<p>
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:200px;">Número de posts:</label><?php 
						$query = "SELECT COUNT(ID) AS iTotal FROM tbSOCIALObject WHERE iType = 3; ";
						$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);
						while($row = mysql_fetch_assoc($result)) {
							$iTotal = $row['iTotal'];
						}
						echo $iTotal;
					?><br>
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:200px;">Número de fotos:</label><?php 
						$query = "SELECT COUNT(ID) AS iTotal FROM tbSOCIALObject WHERE iType = 9; ";
						$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);
						while($row = mysql_fetch_assoc($result)) {
							$iTotal = $row['iTotal'];
						}
						echo $iTotal;
					?><br>
					</p>
					
					<p>
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:200px;">Total de objetos:</label><?php 
						$query = "SELECT COUNT(ID) AS iTotal FROM tbSOCIALObject; ";
						$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);
						while($row = mysql_fetch_assoc($result)) {
							$iTotal = $row['iTotal'];
						}
						echo $iTotal;
					?><br>
					<label style="display:inline-block;color:#999;font-weight:bold;padding:7px 10px 0 0;width:200px;">Total de associações:</label><?php 
						$query = "SELECT (
									(SELECT COUNT(id) FROM tbSOCIALAssociation) + 
									(SELECT COUNT(id) FROM tbSOCIALObjectAssociation)
									) AS iTotal; ";
						$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);
						while($row = mysql_fetch_assoc($result)) {
							$iTotal = $row['iTotal'];
						}
						echo $iTotal;
					?><br>
					</p>
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
