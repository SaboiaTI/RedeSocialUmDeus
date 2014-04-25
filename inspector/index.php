<?php 

require_once ($_SERVER['DOCUMENT_ROOT'] . "/umdeus-redesocial/social/lib/AppConnect.php");

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Associations($par_myRole, $par_associationType, $par_theirRole) {
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	global $id;
	global $r_associationType;
	global $r_objectAssociationType;
	
	$par_myRole 			= !isset($par_myRole)			? '' : strtoupper($par_myRole);
	$par_associationType 	= !isset($par_associationType)	? '' : strtoupper($par_associationType);
	$par_theirRole 			= !isset($par_theirRole)		? '' : strtoupper($par_theirRole);

	// echo '$par_myRole = ' . $par_myRole.'<br>';

	$par_myRole 			= $par_myRole == '' 		 ? '' : $r_objectAssociationType[$par_myRole];
	$par_associationType 	= $par_associationType == '' ? '' : $r_associationType[$par_associationType];
	$par_theirRole 			= $par_theirRole == '' 		 ? '' : $r_objectAssociationType[$par_theirRole];
	
	// echo '$par_myRole = ' . $par_myRole.'<br>';

	
	$where = ''	;
	$where .= $par_myRole == ''			 ? '' : ('AND ' . 'me.iType = '.$par_myRole.' ');
	$where .= $par_associationType == '' ? '' : ('AND ' . 'a.iType = '.$par_associationType.' ');
	$where .= $par_theirRole == '' 		 ? '' : ('AND ' . 'them.iType = '.$par_theirRole.' ');
		
	$query = '
		SELECT 
			me.iType 				AS myRole,
			me.idSOCIALAssociation	AS Association,
			a.iType					AS AssociationType,
			o.id					AS id,
			o.sDisplayName			AS sDisplayName,
			them.iType				AS theirRole
		
		FROM tbSOCIALObjectAssociation me

		INNER JOIN tbSOCIALAssociation a
		ON me.idSOCIALAssociation = a.id 

		INNER JOIN tbSOCIALObjectAssociation them
		ON a.id = them.idSOCIALAssociation

		INNER JOIN tbSOCIALObject o 
		ON them.idSOCIALObject = o.id
		AND o.id <> me.idSOCIALObject ';

	$query .= 'WHERE me.idSOCIALObject = '.$id.' '.$where.' ';
	$query .= 'ORDER BY AssociationType, myRole, theirRole ';
	
	$query .= ';'; 


	$error = 'Query failed: '.mysql_error().'<br>'.$query;
	$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error().'<br>'.$query);

	$associations = array();
	while($row = mysql_fetch_assoc($result)) {
		$associations[] = $row;
	}

	return $associations;

}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Início do código principal
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



$id = isset($_GET['id']) ? $_GET['id'] : 0;
$id = intval($id);

// ObjectType

$query = 'SELECT 
			iType, 
			sKey, 
			sComment 
		FROM 
			tbSOCIAL_DOCObjectType; ';

$error = 'Query failed: '.mysql_error().'<br>'.$query;
$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error());

while($row = mysql_fetch_assoc($result)) {
 	$objectType[$row['iType']] = $row;
}



// AssociationType

$query = 'SELECT 
			iType,
			sKey,
			sComment
		FROM 
			tbSOCIAL_DOCAssociationType; ';

$error = 'Query failed: '.mysql_error().'<br>'.$query;
$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error());

while($row = mysql_fetch_assoc($result)) {
 	$associationType[$row['iType']] = $row;
	$r_associationType[$row['sKey']] = $row['iType'];
}



// ObjectAssociationType

$query = 'SELECT 
			iType,
			sKey,
			sComment
		FROM 
			tbSOCIAL_DOCObjectAssociationType; ';

$error = 'Query failed: '.mysql_error().'<br>'.$query;
$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error());

while($row = mysql_fetch_assoc($result)) {
 	$objectAssociationType[$row['iType']] = $row;
	$r_objectAssociationType[$row['sKey']] = $row['iType'];
}



// Object

$query = 'SELECT 
			ob.id,
			ob.sDisplayName,
			ob.sDirectLink,
			ob.iType,
			ob.tsCreation
		FROM 
			tbSOCIALObject ob
		WHERE 
			ob.id = '.$id.'; ';

$error = 'Query failed: '.mysql_error().'<br>'.$query;
$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error());

$objects = array();
while($row = mysql_fetch_assoc($result)) {
	$objects[] = $row;
}

if(count($objects) == 0) {
	die('Object does not exist');
}


// Information

$query = 'SELECT 
			pr.sKey,
			op.sValue
		FROM tbSOCIALProperty pr 
		LEFT OUTER JOIN tbSOCIALObjectProperty op 
		ON pr.id = op.idSOCIALProperty 
		AND op.idSOCIALObject = '.$id.'
		
		WHERE pr.iObjectType = '.$objects[0]['iType'].'; '; 

$error = 'Query failed: '.mysql_error().'<br>'.$query;
$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error());

$properties = array();
while($row = mysql_fetch_assoc($result)) {
 	$properties[] = $row;
}


// Details

$query = 'SELECT 
			dt.sKey,
			dt.sValue

		FROM tbSOCIALObjectDetail dt 

		WHERE dt.idSOCIALObject = '.$id.'; '; 

$error = 'Query failed: '.mysql_error().'<br>'.$query;
$result = mysql_query(utf8_decode($query)) or die('Query failed: '.mysql_error());

$details = array();
while($row = mysql_fetch_assoc($result)) {
 	$details[] = $row;
}

$associations = Associations('','','');

// Methods
$methods = array();

switch($objects[0]['iType']) {
	// PROFILE
	case 1 :
		array_push($methods, "create()","get()","delete()","proposeFriendship()","acceptFriendship()","refuseFriendship()","endFriendship()","report()","getFriends()","getPendingFriends()","getPages()","getGroups()","getPosts()","getComments()","getLikes()","getMessages()");
	break;
	
	// PAGE
	case 2 :
		array_push($methods, "create()","get()","delete()","like()","unlike()","report()","follow()","unfollow()","participate()","unparticipate()","support()","unsupport()","recomend()","getFollowers()","getParticipants()","getSupporters()","getPosts()","getLikes()");
	break;
	
	// POST
	case 3 :
		array_push($methods, "create()","get()","delete()","like()","unlike()","report()","getComments()","getLikes()");
	break;
	
	// COMMENT
	case 4 :
		array_push($methods, "create()","get()","delete()","like()","unlike()","report()","getLikes()");
	break;
	
	// GROUP
	case 5 :
		array_push($methods, "create()","get()","delete()","report()","follow()","unfollow()","getFollowers()");
	break;
	
	// MESSAGE
	case 6 :
		array_push($methods, "create()","get()","delete()","markAsRead()","markAsUnread()");
	break;
	
	// EVENT
	case 7 :
		array_push($methods, "create()","get()","delete()","follow()","unfollow()","report()","getFollowers()");
	break;
	
	// ALBUM
	case 8 :
		array_push($methods, "create()","get()","delete()","like()","unlike()","getPhotos()","addPhoto()","removePhoto()","setCoverPhoto()");
	break;
	
	// PHOTO
	case 9 :
		array_push($methods, "create()","get()","delete()","like()","unlike()");
	break;
	
}



?>

<!DOCTYPE html>
<html>
<head>
<title>Rede Social UmDeus</title>
<style type="text/css">
	html, body {
		background-color:#FFF;
		font-family:"Calibri", "Arial", "Helvetica", sans-serif;
		font-size:15px;
		color:#777;
		margin:0;
		padding:0;
	}
	html {
		margin:10px;
	}
	h1, h2 {
		border-bottom-style:solid;
		border-bottom-color:#EEE;
		border-bottom-width:1px;
		margin-top:25px;
		width:80%;
		color:#333;
	}
	h1 { font-size:20px; }
	h2 { font-size:16px; }
	ul { list-style-type:square; }
	
	table {
		border-collapse:collapse;
	}
	td, th {
		padding:5px 15px;
		border:1px solid #FFF;
		background-color:#F3F3F3;
	}
	th {
		text-align:left;
		border:none;
		background-color:#FFF;
	}
	header#header {
		position:relative;
		z-index:1;
		height:auto;
		padding:14px 20px;
		
		-webkit-box-shadow:0 1px 3px rgba(0,0,0,0.4);
		   -moz-box-shadow:0 1px 3px rgba(0,0,0,0.4);
				box-shadow:0 1px 3px rgba(0,0,0,0.4);
		
		-webkit-box-sizing:	border-box;
		   -moz-box-sizing: border-box;
			 -o-box-sizing: border-box;
				box-sizing: border-box;
		
		margin:-10px;
		overflow: hidden;
		
		background-color:#FFF;
		background-image:url('/umdeus-redesocial/social/shared/style/images/logo-header-saboia.png');
		background-repeat:no-repeat;
	}
	header#header > p {
		margin:0 0 5px 0;
		padding-left:160px;
	}
	header#header p > span.title {
		text-transform:uppercase;
		font-size:16px;
		line-height:30px;
	}
	header#header hr {
		margin:0 0 10px 0;
		border:0;
		border-bottom-style:solid;
		border-bottom-width:1px;
		border-bottom-color:#E5E5E5;
	}
</style>
</head>
<body>

	<header id="header">
		<p><span class="title">Social Network Inspector</span></p>
		<hr>
	</header>
	
	<h1>Object</h1>

	<ul>
		<li>iType: <?= $objectType[$objects[0]['iType']]['sKey']; ?></li>
		<li>id: <?= $objects[0]['id']; ?></li>
		<li>sDisplayName: <?= $objects[0]['sDisplayName']; ?></li>
		<li>sDirectLink: <?= $objects[0]['sDirectLink']; ?></li>
		<li>tsCreation: <?= $objects[0]['tsCreation']; ?></li>
	</ul>
	
	<h2>Information</h2>
	
	<ul>
	<?
	if (count($properties) > 0) {
		for ($i=0; $i<count($properties); $i++) {
			echo '<li>'.$properties[$i]['sKey'].': ' . (!is_null($properties[$i]['sValue']) ? $properties[$i]['sValue'] : 'null') . '</li>';
		}
	} else {
		echo '<li>none</li>';
	}
	?>
	</ul>
	
	<h2>Details</h2>
	
	<ul>
	<?
	if (count($details) > 0) {
		for ($i=0; $i<count($details); $i++) {
			echo '<li>'.$details[$i]['sKey'].': ' . (!is_null($details[$i]['sValue']) ? $details[$i]['sValue'] : 'null') . '</li>';
		}
	} else {
		echo '<li>none</li>';
	}
	?>
	</ul>
	
	<h2>Methods</h2>
	
	<ul>
	<?
	if (count($methods) > 0) {
		for ($i=0; $i<count($methods); $i++) {
			echo '<li>'.$methods[$i].'</li>';
		}
	} else {
		echo '<li>none</li>';
	}
	?>
	</ul>
	
	<h2>Friends</h2>
	
	<p>
	<table>
		<thead>
			<tr>
				<th>My Role</th>
				<th>Association</th>
				<th>Association Type</th>
				<th>Associated Object</th>
				<th>Associated Object Role</th>
			</tr>
		</thead>
		<tbody>
		<? $list = Associations('','FRIENDSHIP','FRIEND'); ?>
		<? for ($i=0;$i<count($list);$i++) { ?>
		
			<tr>
				<td><?=$objectAssociationType[$list[$i]['myRole']]['sKey']; ?></td>
				<td><?=$list[$i]['Association']; ?></td>
				<td><?=$associationType[$list[$i]['AssociationType']]['sKey']; ?></td>
				<td><a href="?id=<?=$list[$i]['id']; ?>"><?=$list[$i]['sDisplayName']; ?></a></td>
				<td><?=$objectAssociationType[$list[$i]['theirRole']]['sKey']; ?></td>
			</tr>
			
		<? } ?>
			
		</tbody>
	</table>
	</p>

	<h2>Invited Friends</h2>
	
	<p>
	<table>
		<thead>
			<tr>
				<th>My Role</th>
				<th>Association</th>
				<th>Association Type</th>
				<th>Associated Object</th>
				<th>Associated Object Role</th>
			</tr>
		</thead>
		<tbody>
		<? $list = Associations('','FRIENDSHIP','INVITED_FRIEND'); ?>
		<? for ($i=0;$i<count($list);$i++) { ?>
		
			<tr>
				<td><?=$objectAssociationType[$list[$i]['myRole']]['sKey']; ?></td>
				<td><?=$list[$i]['Association']; ?></td>
				<td><?=$associationType[$list[$i]['AssociationType']]['sKey']; ?></td>
				<td><a href="?id=<?=$list[$i]['id']; ?>"><?=$list[$i]['sDisplayName']; ?></a></td>
				<td><?=$objectAssociationType[$list[$i]['theirRole']]['sKey']; ?></td>
			</tr>
			
		<? } ?>
			
		</tbody>
	</table>
	</p>


	<h2>Pages</h2>
	
	<p>
	<table>
		<thead>
			<tr>
				<th>My Role</th>
				<th>Association</th>
				<th>Association Type</th>
				<th>Associated Object</th>
				<th>Associated Object Role</th>
			</tr>
		</thead>
		<tbody>
		<? $list = Associations('','PAGE','OBJECT'); ?>
		<? for ($i=0;$i<count($list);$i++) { ?>
		
			<tr>
				<td><?=$objectAssociationType[$list[$i]['myRole']]['sKey']; ?></td>
				<td><?=$list[$i]['Association']; ?></td>
				<td><?=$associationType[$list[$i]['AssociationType']]['sKey']; ?></td>
				<td><a href="?id=<?=$list[$i]['id']; ?>"><?=$list[$i]['sDisplayName']; ?></a></td>
				<td><?=$objectAssociationType[$list[$i]['theirRole']]['sKey']; ?></td>
			</tr>
			
		<? } ?>
			
		</tbody>
	</table>
	</p>

	<h2>Posts</h2>
	
	<p>
	<table>
		<thead>
			<tr>
				<th>My Role</th>
				<th>Association</th>
				<th>Association Type</th>
				<th>Associated Object</th>
				<th>Associated Object Role</th>
			</tr>
		</thead>
		<tbody>
		<? $list = Associations('','POST','OBJECT'); ?>
		<? for ($i=0;$i<count($list);$i++) { ?>
		
			<tr>
				<td><?=$objectAssociationType[$list[$i]['myRole']]['sKey']; ?></td>
				<td><?=$list[$i]['Association']; ?></td>
				<td><?=$associationType[$list[$i]['AssociationType']]['sKey']; ?></td>
				<td><a href="?id=<?=$list[$i]['id']; ?>"><?=$list[$i]['sDisplayName']; ?></a></td>
				<td><?=$objectAssociationType[$list[$i]['theirRole']]['sKey']; ?></td>
			</tr>
			
		<? } ?>
			
		</tbody>
	</table>
	</p>

	<h2>Comments I´ve made</h2>
	
	<p>
	<table>
		<thead>
			<tr>
				<th>My Role</th>
				<th>Association</th>
				<th>Association Type</th>
				<th>Associated Object</th>
				<th>Associated Object Role</th>
			</tr>
		</thead>
		<tbody>
		<? $list = Associations('','COMMENT','OBJECT'); ?>
		<? for ($i=0;$i<count($list);$i++) { ?>
		
			<tr>
				<td><?=$objectAssociationType[$list[$i]['myRole']]['sKey']; ?></td>
				<td><?=$list[$i]['Association']; ?></td>
				<td><?=$associationType[$list[$i]['AssociationType']]['sKey']; ?></td>
				<td><a href="?id=<?=$list[$i]['id']; ?>"><?=$list[$i]['sDisplayName']; ?></a></td>
				<td><?=$objectAssociationType[$list[$i]['theirRole']]['sKey']; ?></td>
			</tr>
			
		<? } ?>
			
		</tbody>
	</table>
	</p>

	<h2>Comments I was referred on</h2>
	
	<p>
	<table>
		<thead>
			<tr>
				<th>My Role</th>
				<th>Association</th>
				<th>Association Type</th>
				<th>Associated Object</th>
				<th>Associated Object Role</th>
			</tr>
		</thead>
		<tbody>
		<? $list = Associations('','COMMENT','REFERRED'); ?>
		<? for ($i=0;$i<count($list);$i++) { ?>
		
			<tr>
				<td><?=$objectAssociationType[$list[$i]['myRole']]['sKey']; ?></td>
				<td><?=$list[$i]['Association']; ?></td>
				<td><?=$associationType[$list[$i]['AssociationType']]['sKey']; ?></td>
				<td><a href="?id=<?=$list[$i]['id']; ?>"><?=$list[$i]['sDisplayName']; ?></a></td>
				<td><?=$objectAssociationType[$list[$i]['theirRole']]['sKey']; ?></td>
			</tr>
			
		<? } ?>
			
		</tbody>
	</table>
	</p>

	<h2>Messages</h2>
	
	<p>
	<table>
		<thead>
			<tr>
				<th>My Role</th>
				<th>Association</th>
				<th>Association Type</th>
				<th>Associated Object</th>
				<th>Associated Object Role</th>
			</tr>
		</thead>
		<tbody>
		<? $list = Associations('','MESSAGE',''); ?>
		<? for ($i=0;$i<count($list);$i++) { ?>
		
			<tr>
				<td><?=$objectAssociationType[$list[$i]['myRole']]['sKey']; ?></td>
				<td><?=$list[$i]['Association']; ?></td>
				<td><?=$associationType[$list[$i]['AssociationType']]['sKey']; ?></td>
				<td><a href="?id=<?=$list[$i]['id']; ?>"><?=$list[$i]['sDisplayName']; ?></a></td>
				<td><?=$objectAssociationType[$list[$i]['theirRole']]['sKey']; ?></td>
			</tr>
			
		<? } ?>
			
		</tbody>
	</table>
	</p>

	
	<h2>Groups</h2>
	
	<p>
	<table>
		<thead>
			<tr>
				<th>My Role</th>
				<th>Association</th>
				<th>Association Type</th>
				<th>Associated Object</th>
				<th>Associated Object Role</th>
			</tr>
		</thead>
		<tbody>
		<? $list = Associations('','GROUP','OBJECT'); ?>
		<? for ($i=0;$i<count($list);$i++) { ?>
		
			<tr>
				<td><?=$objectAssociationType[$list[$i]['myRole']]['sKey']; ?></td>
				<td><?=$list[$i]['Association']; ?></td>
				<td><?=$associationType[$list[$i]['AssociationType']]['sKey']; ?></td>
				<td><a href="?id=<?=$list[$i]['id']; ?>"><?=$list[$i]['sDisplayName']; ?></a></td>
				<td><?=$objectAssociationType[$list[$i]['theirRole']]['sKey']; ?></td>
			</tr>
			
		<? } ?>
			
		</tbody>
	</table>
	</p>

	
	<h2>Associations</h2>
	
	<table>
		<thead>
			<tr>
				<th>My Role</th>
				<th>Association</th>
				<th>Association Type</th>
				<th>Associated Object</th>
				<th>Associated Object Role</th>
			</tr>
		</thead>
		<tbody>
		
		<? for ($i=0;$i<count($associations);$i++) { ?>
		
			<tr>
				<td><?=$objectAssociationType[$associations[$i]['myRole']]['sKey']; ?></td>
				<td><?=$associations[$i]['Association']; ?></td>
				<td><?=$associationType[$associations[$i]['AssociationType']]['sKey']; ?></td>
				<td><a href="?id=<?=$associations[$i]['id']; ?>"><?=$associations[$i]['sDisplayName']; ?></a></td>
				<td><?=$objectAssociationType[$associations[$i]['theirRole']]['sKey']; ?></td>
			</tr>
			
		<? } ?>
			
		</tbody>
	</table>
	
	
</body>
</html>