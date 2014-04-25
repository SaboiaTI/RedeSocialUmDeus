<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="iso-8859-1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?
	if (isset($socialObject)) {
		echo $socialObject['sDisplayName'].' - Rede Social UmDeus';
	} else {
		echo 'Rede Social UmDeus';
	}
	
	?></title>
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	
	<link rel="stylesheet" href="css/style.css">
	
	<!--[if lte IE 7]> <link rel="stylesheet" href="css/correction-ie7.css"> <![endif]-->
	
	<script src="js/libs/modernizr-2.0.min.js"></script>
	<script src="js/libs/respond.min.js"></script>
	<script src="js/libs/jquery-1.7.min.js"></script>
	<script src="js/script.js"></script>
	<script src="js/basicLib.js"></script>
	
</head>
<body>

	<!-- main container -->
	<div id="container">
	
		<!-- page header -->
		<!-- <div class="header"> -->
		<header>
		
			<!-- institutional navigation -->
			<nav id="about">
				<ul>
					<li><a href="quem-somos.php">quem somos</a></li>
					<li><a href="grupos-de-oracao.php">grupos de oração e meditação</a></li>
				</ul>
			</nav>
			
			<!-- language navigation -->
			<nav id="language">
				<ul>
					<li>ambiente de desenvolvimento</li>
					<!-- <li>idioma: <a href="#">português</a></li> -->
				</ul>
			</nav>
			<?
			$query = "SELECT COUNT(ID) AS iTotal FROM tbSOCIALObject WHERE iType = 1 AND fDeleted <> 1 ";
			$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);
			while($row = mysql_fetch_assoc($result)) {
					$iTotal = $row['iTotal'];
				}
			
			?>
			<!-- logo, slogan, frase  -->
			<div id="logo-slogan">
				<div id="logo"><a href="index.php?id=<?=uniqid();?>"><img src="img/logo.png" alt="Rede Social Um Deus"></a></div>
				<div id="slogan">
					<img src="img/slogan.png" alt="o amor maior é que nos une"><br>
					<p>Já somos <strong style="color:#E92E91;"><?=$iTotal; ?></strong> pessoas cadastradas</p>
				</div>
			</div>
			
			<? if (isset($userObject)) { ?>
			
			<div id="user-greetings">Olá <a href="index.php?id=<?=uniqid();?>&sob=<?=$userObject['id'];?>&prob=<?=$userObject['id'];?>"><?=$userObject['sDisplayName'] ?></a> | <a href="#" id="logout">sair</a></div>
			
			<? } else { ?>
			
			<div id="user-greetings">&nbsp;</div>
			
			<div id="box-login">
				<form id="userLogin" action="javascript:userLogin();">
					<p><label for="email">e-mail:</label><input type="text" class="input" id="email" placeholder="email@exemplo.com" required></p>
					<p><label for="password">senha:</label><input type="password" class="input" id="password" required></p>
				<? if (false) { ?>
					<p><input type="checkbox" class="checkbox" id="conectado" disabled>mantenha-me conectado</p>
				<? } ?>
					<p><input type="submit" value="entrar"><a href="profile-create.php" target="_self" title="crie sua conta na rede social UmDeus">criar conta</a> | <a id="reenviarsenha" href="#" target="_self" title="esqueceu sua senha?">reenviar senha</a></p>
				</form>
				<p id="message" style="display:none;"></p>
			</div>
			
			<? } ?>
			
		</header>
		
		<nav id="profile">
			<ul>
				<li><a href="index.php?id=<?=uniqid();?>">página incial</a></li>
				<? if (isset($userObject)) { ?>
				<li><a href="index.php?id=<?=uniqid();?>&sob=<?=$userObject['id']?>&prob=<?=$userObject['id']?>">meu perfil</a></li>
				<li><a href="search-results.php?id=<?=uniqid();?>&sob=<?=$userObject['id']?>&prob=<?=$userObject['id']?>&feed=AllFriends">amigos</a></li>
				<li><a href="message.php?id=<?=uniqid();?>&sob=<?=$userObject['id']?>&prob=<?=$userObject['id']?>">mensagens</a></li>
				<li><a href="config.php?id=<?=uniqid();?>&sob=<?=$userObject['id']?>&prob=<?=$userObject['id']?>">configurações</a></li>
				<? } ?>
			</ul>
		</nav>
		
		<!-- </div> -->