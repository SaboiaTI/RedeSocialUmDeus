<?php 
	if (
		(isset($socialObject['id']) && $userObject['id'] == $socialObject['id']) || 
		(!isset($socialObject['id']) && !isset($profileObject['id']))
	) {
?>
	
	<!-- convites -->
	<div id="pending-friends">
		
		<?php
			
			$list 	= getAssociations($userObject['id'], 'INVITED_FRIEND','FRIENDSHIP','','',0,0,'0');
			$iTotal = getCountAssociations($userObject['id'], 'INVITED_FRIEND','FRIENDSHIP','');
			if (count($list) > 0) {
		?>
		<h2 style="font-size:12px;"><a href="search-results.php?id=<?=uniqid();?>&sob=<?=$userObject['id'];?>&prob=<?=$userObject['id'];?>&feed=pendingFriends" title="ver todos">Pedidos de amizade (<?=$iTotal;?>)</a></span></h2>
		<?php
			}
		?>
		
		<? for ($i=0;$i<count($list);$i++) {
		?>
		
			<div 
				class="invitation" 
				data-user-id="<?=$userObject['id'];?>" 
				data-object-id="<?=$list[$i]['id'];?>" 
			>
				<div style="height:55px">
					<div class="photo">
						<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$list[$i]['id'];?>" target="_self">
						<?
							$sAvatarPath = getProperties($list[$i]['id'],'sAvatarPath');
							$sAvatarPath = $sAvatarPath['sAvatarPath'];
						?>
						<img src="<?= $sAvatarPath ? $sAvatarPath : "img/avatar-default.jpg"; ?>" alt="" border="0" width="50">
						</a>
					</div>
					<div class="phrase">Esta pessoa quer ser sua amiga</div>
				</div>
				
				<div class="name-location">
					<a href="index.php?id=<?=uniqid();?>&sob=<?=$list[$i]['id'];?>&prob=<?=$list[$i]['id'];?>"><strong><?=$list[$i]['sDisplayName']; ?></strong></a><br>
					<?php 
						$idADMINCity = getProperties($list[$i]['id'],'idADMINCity');
						$idADMINCity = $idADMINCity['idADMINCity'];
						echo $idADMINCity;
					?>, <?php 
						$idADMINState = getProperties($list[$i]['id'],'idADMINState');
						$idADMINState = $idADMINState['idADMINState'];
						echo $idADMINState;
					?>
				</div>
				
				<div style="margin:0px auto">
					<button type="button" class="accept-relationship small">aceitar</button>
					<button type="button" class="remove-relationship small">rejeitar</button>
				</div>
			</div>
			
		<? } ?>
		
	</div>
	
<? } ?>


<!-- amizades -->
<div id="friends">
	
	<?php
		$iTotal = getCountAssociations((isset($profileObject['id']) ? $profileObject['id'] : $userObject['id']),'FRIEND','FRIENDSHIP','FRIEND');
	?>
	
	<h2 style="font-size:12px;"><a href="search-results.php?id=<?=uniqid();?>&sob=<?=isset($socialObject['id']) ? $socialObject['id'] : $userObject['id'];?>&prob=<?=isset($profileObject['id']) ?$profileObject['id'] : $userObject['id'];?>&feed=AllFriends" title="ver todos">Amigos (<?=$iTotal;?>)</a></span></h2>
	
	<?php
	$list = getAssociations((isset($profileObject['id']) ? $profileObject['id'] : $userObject['id']),'FRIEND','FRIENDSHIP','FRIEND','o.tsCreation DESC', 0, 5, '0');
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
					<img src="<?= $sAvatarPath ? $sAvatarPath : "img/avatar-default.jpg"; ?>" alt="" border="0" width="50">
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

<?php 
	if (
		(isset($socialObject['id']) && $userObject['id'] == $socialObject['id']) || 
		(!isset($socialObject['id']) && !isset($profileObject['id']))
	) {
?>

	<h2 style="font-size:11px;margin:0;"><a href="search-results.php?id=<?=uniqid();?>&sob=<?=isset($socialObject['id']) ? $socialObject['id'] : $userObject['id'];?>&prob=<?=isset($profileObject['id']) ?$profileObject['id'] : $userObject['id'];?>&feed=AllFriends" title="remover amigos">remover amigos</a></span></h2>

<? } ?>

</div>



<?php 
	if (
		(isset($socialObject['id']) && $userObject['id'] == $socialObject['id']) || 
		(!isset($socialObject['id']) && !isset($profileObject['id']))
	) {
?>
	
	<? if (false) { ?>
	<!-- sugestão de amizades -->
	<div id="friends">
	
		<h2 style="font-size:12px;">Pessoas que talvez <br>você conheça</h2>
		
		<?php 
		$friendList = getAssociations($userObject['id'],'FRIEND','FRIENDSHIP','FRIEND','','','','0');
		$friendIdList = array();
		foreach($friendList as $f) {
			$friendIdList[] = $f['id'];
		}
		$friendIdList = implode(',',$friendIdList);
		
		$query = 'SELECT 
			me.iType 				AS myRole,
			me.idSOCIALAssociation	AS Association,
			a.iType					AS AssociationType,
			o.id					AS id,
			o.sDisplayName			AS sDisplayName,
			o.tsCreation			AS tsCreation,
			them.iType				AS theirRole
		
		FROM tbSOCIALObjectAssociation me 

		INNER JOIN tbSOCIALAssociation a 
		ON me.idSOCIALAssociation = a.id 

		INNER JOIN tbSOCIALObjectAssociation them 
		ON a.id = them.idSOCIALAssociation 

		INNER JOIN tbSOCIALObject o 
		ON (
			them.idSOCIALObject = o.id 
			AND o.id <> me.idSOCIALObject 
			AND o.iType = 1
		)
		
		WHERE me.idSOCIALObject = '.$userObject['id'].' ';
	if (count($friendIdList) > 0) {
		$query .= 'AND   them.idSOCIALObject NOT IN('.$friendIdList.') ';
	}
		$query .= 'AND	  me.iType <> 1 
		AND	  a.iType <> 1 
		AND	  them.iType <> 1 
		GROUP BY o.id 
		ORDER BY RAND() 
		LIMIT 0,2; ';
		
		$result = mysql_query(utf8_decode($query)) or die('Query failed');
		
		$associations = array();
		while($row = mysql_fetch_assoc($result)) {
			$associations[] = $row;
		}
		$list = $associations;
		
		
	
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
						<img src="<?= $sAvatarPath ? $sAvatarPath : "img/avatar-default.jpg"; ?>" alt="" border="0" width="50">
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
	
	<? } ?>
	
<? } ?>