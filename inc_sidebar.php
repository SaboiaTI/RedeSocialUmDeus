			<!-- sidebar -->
			<aside id="banner">
			
			<? 
				if(isset($userObject) && !isset($socialObject)) {
					$socialObject = $userObject;
				}
			?>
			
			<?php 
				// notificações: convites, mensagens
				if (isset($userObject) && $userObject['id'] == $socialObject['id']) {
			?>
				
				<!-- notificações: convites, mensagens -->
				
				
				
					<?php 
					
					$iTotal = getCountAssociations($userObject['id'],'INVITED_FRIEND','FRIENDSHIP','');
					$list 	= getAssociations($userObject['id'],'INVITED_FRIEND','FRIENDSHIP','');
					if (count($list) > 0) { ?>
					
						<div id="invite-message">
						
							<div id="invite">
								<div class="icon"><img src="img/new-friendship.jpg" alt=""></div>
								<div class="phrase">
									Pedidos de amizade pendentes:<br>
									<a href="search-results.php?id=<?=uniqid();?>&sob=<?=$userObject['id'];?>&prob=<?=$userObject['id'];?>&feed=pendingFriends" title="ver pedidos de amizade"><span style="color:#f5984a">Voc&ecirc; tem <?=$iTotal;?> pedido(s) de amizade pendente(s)</span></a>
								</div>
							</div>
							
						</div>
					
					<?php
					} ?>
				
					<?php 
				
					$query = 'SELECT count(fAck) as totalMessage FROM tbSOCIALObjectAssociation			
								WHERE iType = 12 AND idSOCIALObject = '.$userObject['id'].' AND fAck = 0';
					
					$result = mysql_query($query);
					$row 	= mysql_fetch_assoc($result);
					
					if($row['totalMessage'] > 0) { ?>
					
						<div id="invite-message">
				
							<div id="message">
								<div class="icon">
									<img src="img/new-message.jpg" alt="">
								</div>
								<div class="phrase">
									Mensagens não lidas:<br>
									<a href="message.php?id=<?=uniqid();?>&sob=<?=$userObject['id'];?>&prob=<?=$userObject['id'];?>"><span style="color:#f5984a">Voc&ecirc; tem novas mensagens</span></a>
								</div>
							</div>
							
						</div>
				
					<?php
					} ?>
					
				
			<?php 
				// acessando um PROFILE
				} else if ($socialObject['iType'] == 1 && $socialObject['id'] != $userObject['id']) { 
			?>
				
				<? $fsStatus = friendshipStatus($userObject['id'], $socialObject['id']); ?>
				
				<? // if ( $fsStatus['myRole']=="FRIEND" && $fsStatus['theirRole']=="FRIEND" ) { ?>
				<? if ( count($fsStatus) > 0 && $fsStatus['myRole'] == 1 && $fsStatus['theirRole'] == 1 ) { ?>
				
				<button 
					id="remove-friendship" 
					type="button" 
					class="remove-relationship" 
					data-user-id="<?=$userObject['id']; ?>" 
					data-object-id="<?=$socialObject['id']; ?>"
					>
					desfazer amizade
				</button>
				
				<? // } else if ( $fsStatus['myRole']=="FRIEND" && $fsStatus['theirRole']=="INVITED_FRIEND" ) { ?>
				<? } else if ( count($fsStatus) > 0 && $fsStatus['myRole'] == 1 && $fsStatus['theirRole'] == 2 ) { ?>
				
				<button 
					id="remove-friendship" 
					type="button" 
					class="remove-relationship" 
					data-user-id="<?=$userObject['id']; ?>" 
					data-object-id="<?=$socialObject['id']; ?>"
					>
					cancelar convite de amizade
				</button>
				
				<? // } else if ( $fsStatus['myRole']=="INVITED_FRIEND" && $fsStatus['theirRole']=="FRIEND" ) { ?>
				<? } else if ( count($fsStatus) > 0 && $fsStatus['myRole'] == 2 && $fsStatus['theirRole'] == 1 ) { ?>
				
				<button 
					id="accept-friendship" 
					type="button" 
					class="accept-relationship" 
					data-user-id="<?=$userObject['id']; ?>" 
					data-object-id="<?=$socialObject['id']; ?>"
					>
					aceitar pedido de amizade
				</button>
				
				<? } else { ?>
				
				<button 
					id="add-friendship" 
					type="button" 
					class="add-relationship" 
					data-user-id="<?=$userObject['id']; ?>" 
					data-object-id="<?=$socialObject['id']; ?>"
					>
					adicionar como amigo
				</button>
				
				<? } ?>
				
				
			<?php
				// acessando uma PAGE da qual não sou o OWNER
				} else if ($socialObject['iType'] == 2 && $profileObject['id'] != $userObject['id']) {
			?>
				
				<? $fsStatus = followStatus($userObject['id'], $socialObject['id']); ?>
				
				<? // if ( count($fsStatus)>0 && $fsStatus['myRole']=="FOLLOWER" && $fsStatus['theirRole']=="PAGE" ) { ?>
				<? if ( count($fsStatus)>0 && $fsStatus['myRole']==7 && $fsStatus['theirRole']==3 ) { ?>
				
				<button 
					id="unfollow-page" 
					type="button" 
					class="remove-relationship" 
					data-user-id="<?=$userObject['id']; ?>" 
					data-object-id="<?=$socialObject['id']; ?>"
					>
					parar de seguir esta página
				</button>
				
				<? } else { ?>
				
				<button 
					id="follow-page" 
					type="button" 
					class="add-relationship" 
					data-user-id="<?=$userObject['id']; ?>" 
					data-object-id="<?=$socialObject['id']; ?>"
					>
					quero ser um seguidor
				</button>
				
				<? } ?>
			
			
			
			<?php
				// acessando um GROUP do qual não sou o OWNER
				} else if ($socialObject['iType'] == 5 && $profileObject['id'] != $userObject['id']) {
			?>
				
				<? $fsStatus = memberStatus($userObject['id'], $socialObject['id']); ?>
				
				<? // if ( count($fsStatus)>0 && $fsStatus['myRole']=="MEMBER" && $fsStatus['theirRole']=="OBJECT" ) { ?>
				<? if ( count($fsStatus)>0 && $fsStatus['myRole']==14 && $fsStatus['theirRole']==3 ) { ?>
				
				<button 
					id="unfollow-group" 
					type="button" 
					class="remove-relationship" 
					data-user-id="<?=$userObject['id']; ?>" 
					data-object-id="<?=$socialObject['id']; ?>"
					>
					sair deste grupo
				</button>
				
				<? } else { ?>
				
				<button 
					id="follow-group" 
					type="button" 
					class="add-relationship" 
					data-user-id="<?=$userObject['id']; ?>" 
					data-object-id="<?=$socialObject['id']; ?>"
					>
					quero participar deste grupo
				</button>
				
				<? } ?>
			
			
			
			
			
			<? } ?>
				
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
				
				<!-- busca de pessoas -->
				<div id="search-people" class="search-box">
				
					<h1>Una-se às pessoas</h1>
					
					<a class="minimize" href="#"><img src="img/btn_minimizar.jpg" alt="" align="right" border="0"></a>
					<a class="maximize" href="#"><img src="img/btn_maximizar.jpg" alt="" align="right" border="0" style="display:none"></a>
				
					<form id="form-people" action="search-results.php">
						<p><strong>por localidade</strong></p>
						<input type="hidden" name="feed" value="profile">
						<p>
							país:
							<select name="pais" id="pais">
								<option selected="" disabled="" value="">selecione o país</option>
								<option value="BR">Brasil</option>
							</select>
						</p>
						
						<p>
							estado:
							<select name="estado" id="estado">
								<option selected="" disabled="" value="">selecione o estado</option>
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
							cidade: 
							<input type="text" class="input" name="cidade" value="" placeholder="">
						</p>
						
						<p>
							bairro: 
							<input type="text" class="input" name="bairro" value="" placeholder="">
						</p>
						
						<? if (false) { ?>
						<p>
							<strong>por palavra-chave</strong>
							<input type="text" class="input" name="afinidade" value="" placeholder="">
							<p>exemplo: <em>idosos, crian&ccedil;as...</em></p>
						</p>
						<? } ?>
						
						<input type="submit" class="button" value="buscar">
					</form>
					
				</div>
				
				<!-- busca de caridade -->
				<div id="search-charity" class="search-box">
				
					<h1>Encontre ações de caridade</h1>
					<a class="minimize" href="#"><img src="img/btn_minimizar.jpg" alt="" align="right" border="0"></a>
					<a class="maximize" href="#"><img src="img/btn_maximizar.jpg" alt="" align="right" border="0" style="display:none"></a>
					<br>
						
					<form id="form-charity" action="search-results.php">		
						<p><strong>por localidade</strong></p>
						<input type="hidden" name="feed" value="page">
						<p>
							país:
							<select name="pais" id="pais">
								<option selected="" disabled="" value="">selecione o país</option>
								<option value="BR">Brasil</option>
							</select>
						</p>
						
						<p>
							estado:
							<select name="estado" id="estado">
								<option selected="" disabled="" value="">selecione o estado</option>
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
							cidade: 
							<input type="text" class="input" name="cidade" value="" placeholder="">
						</p>
						
						<p>
							bairro: 
							<input type="text" class="input" name="bairro" value="" placeholder="">
						</p>
						
						<p>
							<strong>por palavra-chave</strong>
							<input type="text" class="input" name="afinidade" value="" placeholder="">
							<p>exemplo: <em>idosos, crian&ccedil;as...</em></p>
						</p>
						
						<input type="submit" class="button" value="buscar">
					</form>
					
				</div>
				
				<!-- banner -->
				<div class="banner">
					<a href="page-create.php" title="Crie uma página para a sua ação de caridade"><img src="img/acao_caridade.jpg" alt="Crie uma página para a sua ação de caridade"></a>
					Lorem ipsum dolor sit amet, a non ut, etiam velit lacinia lacus sit. Rerum nec uma velit lorem vulputate. <a href="page-create.php" title="Crie uma página para a sua ação de caridade">Saiba mais aqui.</a>
				</div>
			
			</aside>
			<!-- end sidebar -->