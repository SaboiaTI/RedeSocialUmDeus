/* ------------------------------------------------------------------------------------------------------
 * script.js
 * funções utilizadas na rede social UmDeus
 * author	Saboia Tecnologia da Informação <relacionamento@saboia.com.br>
 * link		http://www.saboia.com.br
 * version 	1.0
 * --------------------------------------------------------------------------------------------------- */

 
 

function userLogin() {
	
	var sLogin = $.trim($("div#box-login input#email").val());
	var sPassword = $.trim($("div#box-login input#password").val());
	
	var form = "form#userLogin";
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}
	
	/*
	var strTest  = 'lib/socialAPI.php';
		strTest += '?action=userLogin';
		strTest += '&sLogin='+sLogin;
		strTest += '&sPassword='+sPassword;
	window.location = strTest;
	//*/
	
	//*
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action"	:"userLogin",
							"sLogin"	:sLogin,
							"sPassword"	:sPassword
						},
			success: 	function(data) {
							
							if (data.success && data.success == true) {
								
								$("div#box-login > p#message").hide();
								$("div#box-login > p#message").text("");
								
								if (window.location == "http://lab.saboia.info/profile-create.php") {
									window.location = "/index.php";
								} else {
									window.location.reload();
								}
								
							} else {
								
								$("div#box-login > p#message").show();
								$("div#box-login > p#message").text("dados de login inválidos");
							}
						},
			error:		function(data) {
							$("div#box-login > p#message").show();
							$("div#box-login > p#message").text("dados de login inválidos");
						}
	});
	//*/
}



function requestPassword(sLogin) {
	
	if (sLogin == "") {	
		$("div#box-login > p#message").show();
		$("div#box-login > p#message").text("digite o email");
		return
	}
	
	//var form = "form#userLogin";
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}
	
	/*
	var strTest  = 'requestPassword.php';
		strTest += '?sLogin='+sLogin;
	window.location = strTest;
	//*/
	
	//*
	$.ajax({
			type: 		"POST",
			url: 		"requestPassword.php",
			dataType: 	"json",
			data: 		{
							"sLogin"	:sLogin
						},
			success: 	function(data) {
							
							if (data.success && data.success == true) {
								
								$("div#box-login > p#message").show();
								$("div#box-login > p#message").text("Email enviado com sucesso para " + sLogin);

							} else {

								$("div#box-login > p#message").show();
								$("div#box-login > p#message").text("Email não encontrado");

							}
						},
			error:		function(data) {
							$("div#box-login > p#message").show();
							$("div#box-login > p#message").text("Email não encontrado");
						}
	});
	//*/
}



function userLogout() {
	
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action"	:"userLogout"
						},
			success: 	function(data) {
							
							if (data.success && data.success == true) {
								// window.location.reload();
								window.location = "/";
							}
						}
	});
	
}


function photoCapa(idDaPhoto) {

	/*
	var strTest  = 'photoCapa.php';
		strTest += '?idDaPhoto='+idDaPhoto;
	window.location = strTest;
	//*/
	
	//*
	$.ajax({
			type: 		"POST",
			url: 		"photoCapa.php",
			dataType: 	"json",
			data: 		{
							"idDaPhoto"	:idDaPhoto
						},
			success: 	function(data) {
							
				if (data.success && data.success == true) {
					 window.location.reload();
					//window.location = "/";
				}
			}
	});
	//*/
}

function confirmInvite(form) {
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}

	$("div#confirmation-container").stop(true,true).delay(250).slideDown(250);
	$("div#confirmation-container span#message").text( $("textarea#sMessage").val() );
}

function sendInvite(form) {

//	var form = "form#userLogin";
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}
	
	var listFriends = $.trim($(form).find("textarea#listFriends").val());
	var sMessage 	= $.trim($(form).find("textarea#sMessage").val());
	
	var strTest  = "lib/socialAPI.php?action=sendInvite";
	strTest += "&listFriends="+listFriends;
	strTest += "&sMessage="+sMessage;
	
	//alert(strTest);
	
	window.location = strTest;
	
	/*$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action"		:"sendInvite",
							"listFriends"	:listFriends,
							"sMessage"		:sMessage
						},
			success: 	function(data) {
							
							if (data.success && data.success == true) {
								window.location.reload();
							}
						}
	});*/

}

function nextStep() {
	
	var form = "form#new-user";
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}

	$('#primeiraparte').slideUp('fast');
	$('#segundaparte').slideDown('fast');
	$('#cabprimeiraparte').removeClass('active');
	$('#cabsegundaparte').addClass('active');						
}

function createAccount() {
	
	// -------------------------------------------------------------------------------
	// verificação de senha
	
	if ( $.trim($("form#new-user input#sPassword").val()) != $.trim($("form#new-user input#sPassword_repeat").val()) ) {
		return false;
	}
	
	/*
	var validRequiredFields = 0;
	
//	$("form#new-user").find("input:required").each(function(index){
	
	$("form#new-page").find("input").filter(function(index){
		return $(this).attr("required",true);
	}).each(function(index){
		if ( $.trim($(this).val()) == "" ) {
			validRequiredFields++;
		}
	});
	
//	$("form#new-user").find("textarea:required").each(function(index){

	$("form#new-user").find("textarea").filter(function(index){
		return $(this).attr("required",true);
	}).each(function(index){
		if ( $.trim($(this).val()) == "" ) {
			validRequiredFields++;
		}
	});
	
	if ( validRequiredFields > 0 ) {
		return false;
	}
	*/
	
	var sFullName 		= $.trim($("form#new-user input#sFullName").val());
	var sDisplayName 	= $.trim($("form#new-user input#sDisplayName").val());
	var sEmail 			= $.trim($("form#new-user input#sEmail").val());
	var sPassword 		= $.trim($("form#new-user input#sPassword").val());
	var dtBirthday 		= $.trim($("form#new-user input#dtBirthday").val());
	
	var idADMINCountry 	= $.trim($("form#new-user select#idADMINCountry option:selected").val());
	var idADMINState 	= $.trim($("form#new-user select#idADMINState option:selected").val());
	var idADMINCity 	= $.trim($("form#new-user input#idADMINCity").val());
	var sPostalCode 	= $.trim($("form#new-user input#sPostalCode").val());
	
	var sAvatarPath 	= $.trim($("form#new-user input#sAvatarPath").val());
	
	
	/*
	var strTest  = "lib/socialAPI.php?action=createAccount";
		strTest += "&sFullName="+sFullName;
		strTest += "&sDisplayName="+sDisplayName;
		strTest += "&sEmail="+sEmail;
		strTest += "&sPassword="+sPassword;
		strTest += "&dtBirthday="+dtBirthday;
		strTest += "&idADMINState="+idADMINState;
		strTest += "&idADMINCity="+idADMINCity;
		strTest += "&sPostalCode="+sPostalCode;
		strTest += "&sAvatarPath="+sAvatarPath;
	window.location = strTest;
	//*/
	
	//*
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action":			"createAccount",
							"sFullName":		sFullName,
							"sEmail":			sEmail,
							"sDisplayName":		sDisplayName,
							"sPassword":		sPassword,
							"dtBirthday":		dtBirthday,
							"idADMINCountry":	idADMINCountry,
							"idADMINState":		idADMINState,
							"idADMINCity":		idADMINCity,
							"sPostalCode":		sPostalCode,
							"sAvatarPath":		sAvatarPath
						},
			success: 	function(data) {
							
							if (data.success && data.success == true) {
								
								$("form#new-user").get(0).reset();
								$("div#form-container").stop(true,true).slideUp(500);
								$("div#success-container").stop(true,true).delay(250).slideDown(250);
								
							} else {
								
								$("form#new-user").get(0).reset();
								$("div#form-container").stop(true,true).slideUp(500);
								$("div#failure-container").stop(true,true).delay(250).slideDown(250);
							}
						}
	});
	//*/
	
}

function createPage() {
	
	var form = "form#new-page";
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}
	
	var sFullName 		= $.trim($("form#new-page input#sFullName").val());
	var sDisplayName 	= $.trim($("form#new-page input#sFullName").val());
	var iPageType 		= $.trim($("form#new-page select#iPageType option:selected").val());
	var sDescription 	= $.trim($("form#new-page textarea#sDescription").val());
	var sSite 			= $.trim($("form#new-page input#sSite").val());
	
	var idADMINCountry 	= $.trim($("form#new-page select#idADMINCountry option:selected").val());
	var idADMINState 	= $.trim($("form#new-page select#idADMINState option:selected").val());
	var idADMINCity 	= $.trim($("form#new-page input#idADMINCity").val());
	var sPostalCode 	= $.trim($("form#new-page input#sPostalCode").val());
	
	var sAddress 		= $.trim($("form#new-page input#sAddress").val());
	var sComplement		= $.trim($("form#new-page input#sComplement").val());
	var sNeighborhood	= $.trim($("form#new-page input#sNeighborhood").val());
	
	
	// -------------------------------------------------------------------------------
	// verificação dos campos marcados como 'required'
	// (usado também como validação em browsers não compatíveis com validadores HTML5)
	
	var validRequiredFields = 0;
	
	$("form#new-page").find("input").filter(function(index){
		return $(this).attr("required",true);
	}).each(function(index){
		if ( $.trim($(this).val()) == "" ) {
			validRequiredFields++;
		}
	});
	
	$("form#new-page").find("textarea").filter(function(index){
		return $(this).attr("required",true);
	}).each(function(index){
		if ( $.trim($(this).val()) == "" ) {
			validRequiredFields++;
		}
	});
	
	if ( validRequiredFields > 0 ) {
		return false;
	}
	
	
	/*
	var strTest  = "lib/socialAPI.php?action=createPage";
		strTest += "&sFullName="+sFullName;
		strTest += "&sDisplayName="+sDisplayName;
		strTest += "&iPageType="+iPageType;
		strTest += "&sDescription="+sDescription;
		strTest += "&sSite="+sSite;
		
		strTest += "&idADMINCountry="+idADMINCountry;
		strTest += "&idADMINState="+idADMINState;
		strTest += "&idADMINCity="+idADMINCity;
		strTest += "&sPostalCode="+sPostalCode;
		strTest += "&sAddress="+sAddress;
		strTest += "&sComplement="+sComplement;
		strTest += "&sNeighborhood="+sNeighborhood;
	window.location = strTest;
	//*/
	
	//*
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action":			"createPage",
							"sFullName":		sFullName,
							"sDisplayName":		sDisplayName,
							"iPageType":		iPageType,
							"sDescription":		sDescription,
							"sSite":			sSite,
							"idADMINCountry": 	idADMINCountry,
							"idADMINState": 	idADMINState,
							"idADMINCity": 		idADMINCity,
							"sPostalCode": 		sPostalCode,
							"sAddress": 		sAddress,
							"sComplement": 		sComplement,
							"sNeighborhood": 	sNeighborhood
						},
			success: 	function(data) {
							
							if (data.success && data.success == true) {
								// window.location = "object.php";
								$("form#new-page").get(0).reset();
								$("div#form-container").stop(true,true).slideUp(500);
								$("div#success-container").stop(true,true).delay(250).slideDown(250);
								
							} else if (data.success && data.success == false) {
								$("form#new-page").get(0).reset();
								$("div#form-container").stop(true,true).slideUp(500);
								$("div#failure-container").stop(true,true).delay(250).slideDown(250);
							}
						}
	});
	//*/
	
}

function createGroup() {
	
	var form = "form#new-group";
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}
	
	var sDisplayName		= $.trim($("form#new-group input#sDisplayName").val());
	var iGroupType			= $.trim($("form#new-group select#iGroupType option:selected").val());
	var sGroupTheme			= $.trim($("form#new-group input#sGroupTheme").val());
	var dtBegin				= $.trim($("form#new-group input#dtBegin").val());
	var dtEnd				= $.trim($("form#new-group input#dtEnd").val());
	var iPeriodicity		= $.trim($("form#new-group select#iPeriodicity option:selected").val());
	var iPeriodicityDetail	= $.trim($("form#new-group select#iPeriodicity"+iPeriodicity+" option:selected").val());
	var tsOracao			= $.trim($("form#new-group select#tsOracao option:selected").val());
	var idADMINCountry		= $.trim($("form#new-group select#idADMINCountry option:selected").val());
	var idADMINState		= $.trim($("form#new-group select#idADMINState option:selected").val());
	var idADMINCity			= $.trim($("form#new-group input#idADMINCity").val());
	var sDescription		= $.trim($("form#new-group textarea#sDescription").val());
	var sSite				= $.trim($("form#new-group input#sSite").val());
	
	var arFriends = $("form#new-group fieldset#segundaparte div.user").filter(function(index) {
						return $(this).find("input[type='checkbox']").attr("checked") == "checked";
					});
	arFriends = $.makeArray(arFriends);
	
	var invitedFriends = new Array();
	for (f in arFriends) {
		invitedFriends.push(
			$(arFriends[f]).find("input[type='checkbox']").val()
		);
	}
	
	invitedFriends = invitedFriends.toString();
	
	
	
	// -------------------------------------------------------------------------------
	// verificação dos campos marcados como 'required'
	// (usado também como validação em browsers não compatíveis com validadores HTML5)
	
	var validRequiredFields = 0;
	
	$("form#new-group").find("input").filter(function(index){
		return $(this).attr("required",true);
	}).each(function(index){
		if ( $.trim($(this).val()) == "" ) {
			validRequiredFields++;
		}
	});
	
	$("form#new-group").find("textarea").filter(function(index){
		return $(this).attr("required",true);
	}).each(function(index){
		if ( $.trim($(this).val()) == "" ) {
			validRequiredFields++;
		}
	});
	
	if ( validRequiredFields > 0 ) {
		return false;
	}
	
	/*
	var strTest  = "lib/socialAPI.php";
		strTest += "?action="+				"createGroup";
		strTest += "&sDisplayName="+		sDisplayName;
		strTest += "&iGroupType="+			iGroupType;
		strTest += "&sGroupTheme="+			sGroupTheme;
		strTest += "&dtBegin="+				dtBegin;
		strTest += "&dtEnd="+				dtEnd;
		strTest += "&iPeriodicity="+		iPeriodicity;
		strTest += "&iPeriodicityDetail="+	iPeriodicityDetail;
		strTest += "&tsOracao="+ 			tsOracao;
		strTest += "&idADMINCountry="+ 		idADMINCountry;
		strTest += "&idADMINState="+ 		idADMINState;
		strTest += "&idADMINCity="+ 		idADMINCity;
		strTest += "&sDescription="+ 		sDescription;
		strTest += "&sSite="+ 				sSite;
		strTest += "&invitedFriends="+		invitedFriends;
	
	window.location = strTest;
	//*/
	
	
	// -------------------------------------------------------------------------------
	//*
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action":				"createGroup",
							"sDisplayName":			sDisplayName,	
							"iGroupType":			iGroupType,
							"sGroupTheme":			sGroupTheme,
							"dtBegin":				dtBegin,
							"dtEnd":				dtEnd,
							"iPeriodicity":			iPeriodicity,
							"iPeriodicityDetail":	iPeriodicityDetail,
							"tsOracao": 			tsOracao,
							"idADMINCountry": 		idADMINCountry,
							"idADMINState": 		idADMINState,
							"idADMINCity": 			idADMINCity,
							"sDescription": 		sDescription,
							"sSite": 				sSite,
							"invitedFriends":		invitedFriends
						},
			success: 	function(data) {
							
							if (data.success && (data.success == true || data.success == "true" || data.success == 1)) {
								
								$("form#new-group").get(0).reset();
								$("div#form-container").stop(true,true).slideUp(500);
								$("div#success-container").stop(true,true).delay(250).slideDown(250);
								
							} else {
								
								$("form#new-group").get(0).reset();
								$("div#form-container").stop(true,true).slideUp(500);
								$("div#failure-container").stop(true,true).delay(250).slideDown(250);
							}
						}
	});
	//*/
}



function ProfileProposeFriendship(idProposerProfile, idGuestProfile) {
		
	var idProposerProfile	= !idProposerProfile ? "" : idProposerProfile;
	var idGuestProfile		= !idGuestProfile 	 ? "" : idGuestProfile;
	
	if (idProposerProfile == "" || idGuestProfile == "") {
		return false;
	}
	
	$.ajax({
		type: 		"POST",
		url: 		"lib/socialAPI.php",
		dataType: 	"json",
		data: 		{
						"action"			:"ProfileProposeFriendship",
						"idProposerProfile"	:idProposerProfile,
						"idGuestProfile"	:idGuestProfile
					},
		success: 	function(data) {
						if (data.success && data.success == true) {
							$("button#add-friendship").stop(true,true).fadeOut(500);
							window.location.reload();
						}
					}
	});
}

function ProfileAcceptFriendship(idFriend, idMyProfile) {
		
		var idFriend	= !idFriend 	? "" : idFriend;
		var idMyProfile = !idMyProfile 	? "" : idMyProfile;
		
		if (idFriend == "" || idMyProfile == "") {
			return false;
		}
		
		/*
		var strTest  = "lib/socialAPI.php?action=ProfileAcceptFriendship";
			strTest += "&idFriend="+idFriend;
			strTest += "&idMyProfile="+idMyProfile;
		window.location = strTest;
		//*/
		
		//*
		$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action" 		:"ProfileAcceptFriendship",
							"idFriend" 		:idFriend,
							"idMyProfile" 	:idMyProfile
						},
			success: 	function(data) {
							if (data.success && data.success == true) {
								$("button#accept-friendship").stop(true,true).fadeOut(500);
								window.location.reload();
							}
						}
		});
		//*/
}

function ProfileEndFriendship(idProfile, idFriendProfile) {
		
		var idFriendProfile	= !idFriendProfile	? "" : idFriendProfile;
		var idProfile		= !idProfile 		? "" : idProfile;
		
		if (idFriendProfile == "" || idProfile == "") {
			return false;
		}
		
		/*
		var strTest  = "lib/socialAPI.php";
			strTest += "?action=ProfileEndFriendship";
			strTest += "&idFriendProfile="+idFriendProfile;
			strTest += "&idProfile="+idProfile;
		window.location = strTest;
		//*/
		
		//*
		$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action"			:"ProfileEndFriendship",
							"idFriendProfile"	:idFriendProfile,
							"idProfile" 		:idProfile
						},
			success: 	function(data) {
							if (data.success && data.success == true) {
								// $("button#remove-friendship").stop(true,true).fadeOut(500);
								window.location.reload();
							}
						}
		});
		//*/
}




function identifyYoutubeVideo(target) {
	
	// regExp:	/^(http:\/\/)?(www.)?(youtube.com\/watch\?v=)([a-zA-z0-9]*)$/
	// link:	http://www.youtube.com/watch?v=M4ZL__PeZ54
	// embed:	<iframe width="399" height="203" src="http://www.youtube.com/embed/M4ZL__PeZ54?rel=0" frameborder="0" allowfullscreen></iframe>
	// thumb:	http://i1.ytimg.com/vi/M4ZL__PeZ54/default.jpg
	
	if (target.value != "") {
		
		var strText = target.value;
		var pattern = /(http:\/\/)?(www.)?(youtube.com\/watch\?v=)([a-zA-z0-9]*)/;
		var patternText = /(http:\/\/)?(www.)?(youtube.com\/watch\?v=)([a-zA-z0-9]*)/g;
		
		var arrMatch = null;
		var strJson = '';
		
		if (pattern.test(strText) == true) {
			
			var idYV;
			
			while (arrMatch = pattern.exec(strText)) {
				idYV = arrMatch[arrMatch.length-1];
				break;
			}
			
			var linkYV 	= 'http://www.youtube.com/watch?v='+idYV;
			var embedYV = '<iframe width="399" height="203" src="http://www.youtube.com/embed/'+idYV+'?rel=0" frameborder="0" allowfullscreen></iframe>';
			var thumbYV = 'http://i1.ytimg.com/vi/'+idYV+'/default.jpg';
			
			var thumbText = '<a href="'+linkYV+'" class="yv" title="assista ao vídeo" target="_blank"><img src="'+thumbYV+'"></a>';
			
			var descText = strText.replace(
				patternText,
				function($0,$1,$2,$3,$4){
					
					var linkYV 	= 'http://www.youtube.com/watch?v='+$4;
					var embedYV = '<iframe width="399" height="203" src="http://www.youtube.com/embed/'+$4+'?rel=0" frameborder="0" allowfullscreen></iframe>';
					var thumbYV = 'http://i1.ytimg.com/vi/'+$4+'/default.jpg';
					
					return('<a href="' + linkYV + '" title="assista ao vídeo" target="_blank">' + linkYV + '</a>');
				}
			);
			
			strJson = '{"thumb":"'+thumbYV+'","link":"'+linkYV+'","id":"'+idYV+'"}';
			
			document.getElementById("share-preview").style.display = "block";
			document.getElementById("share-preview-thumb").innerHTML = thumbText;
			document.getElementById("share-preview-description").innerHTML = descText;
			target.setAttribute("data-video",strJson);
			
		} else {
			strJson = '';
			
			document.getElementById("share-preview").style.display = "none";
			document.getElementById("share-preview-thumb").innerHTML = '';
			document.getElementById("share-preview-description").innerHTML = '';
			target.removeAttribute("data-video");
		}
		
		
		
		/*
		var arrMatch = null;
		
		while (arrMatch = pattern.exec(strText)) {
			
			var result 	= arrMatch;
			var idYV 	= result[result.length-1];
			
			var linkYV 	= 'http://www.youtube.com/watch?v='+idYV;
			var embedYV = '<iframe width="399" height="203" src="http://www.youtube.com/embed/'+idYV+'?rel=0" frameborder="0" allowfullscreen></iframe>';
			var thumbYV = 'http://i1.ytimg.com/vi/'+idYV+'/default.jpg';
			
			// alert(thumbYV);
		}
		*/
		
		/*
		if (pattern.test(strText) == true) {
		
			var newText = strText.replace(
				pattern,
				function($0,$1,$2,$3,$4){
					
					var linkYV 	= 'http://www.youtube.com/watch?v='+$4;
					var embedYV = '<iframe width="399" height="203" src="http://www.youtube.com/embed/'+$4+'?rel=0" frameborder="0" allowfullscreen></iframe>';
					var thumbYV = 'http://i1.ytimg.com/vi/'+$4+'/default.jpg';
					
					return('<a href="'+linkYV+'" class="yv" title="assista ao vídeo" target="_blank"><img src="'+thumbYV+'"></a>');
				}
			);
			
			document.getElementById('share-text-preview').innerHTML = newText;
			
		} else {
			document.getElementById('share-text-preview').innerHTML = '';
		}
		*/
		
	}
}

function identifyLink(sText) {
	
	// regExp:	/^(http:\/\/)?(www.)?(youtube.com\/watch\?v=)([a-zA-z0-9]*)$/
	// link:	http://www.youtube.com/watch?v=M4ZL__PeZ54
	// embed:	<iframe width="399" height="203" src="http://www.youtube.com/embed/M4ZL__PeZ54?rel=0" frameborder="0" allowfullscreen></iframe>
	// thumb:	http://i1.ytimg.com/vi/M4ZL__PeZ54/default.jpg
	
	if (sText != "") {
		
		var strText = sText;
		var pattern = /(http:\/\/)?(www.)?(.*)/;
		var patternText = /(http:\/\/)?(www.)?(.*)/g;
		
		var arrMatch = null;
		var strReturn = '';
		
		if (pattern.test(strText) == true) {
			
			var descText = strText.replace(
				patternText,
				function($0,$1,$2,$3){
					return('<a href="' + $0 + '" title="" target="_blank">' + $0 + '</a>');
				}
			);
			
			strReturn = descText;
			
		} else {
		
			strReturn = strText;
			
		}
		
		return strReturn;
		
	}
}



function postCreate(idReferredObject) {
	
	var idReferredObject = $.trim(idReferredObject);
	
	var form = "form#share-text-form";
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}
	
	var sContent		 = $.trim($("form#share-text-form").find("textarea").val()) != "" ? $.trim($("form#share-text-form").find("textarea").val()) : $.trim($("form#share-form").find("textarea").val());
	var sDataVideo		 = $.trim($("form#share-text-form").find("textarea").attr("data-video")) != "" ? $.trim($("form#share-text-form").find("textarea").attr("data-video")) : $.trim($("form#share-form").find("textarea").attr("data-video"));
	
	if (sContent == "") { return; }
	
	/*
	var strTest  = "lib/socialAPI.php?action=PostCreate";
		strTest += "&idReferredObject="+idReferredObject;
		strTest += "&sContent="+sContent;
		strTest += "&sDataVideo="+sDataVideo;
	window.location = strTest;
	return;
	//*/
	
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action" 			:"PostCreate",
							"idReferredObject" 	:idReferredObject,
							"sContent" 			:sContent,
							"sDataVideo"		:sDataVideo
						},
			success: 	function(data) {
							if (data.success && data.success == true) {
								// $("button#remove-friendship").stop(true,true).fadeOut(500);
								window.location.reload();
							}
						}
		});
}

function photoPostCreate() {

	var form = "share-photo-form";
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}
	
	$('form#share-photo-form img#loading-image').show();
	
	$("form#share-photo-form").find("input.shared-button").bind("click");
}

function postShare(idOriginalPost) {
	
	var idOriginalPost = $.trim(idOriginalPost);
	
	/*
	var strTest  = "lib/socialAPI.php?action=PostShare";
		strTest += "&idOriginalPost="+idOriginalPost;
	window.location = strTest;
	return;
	//*/
	
	//*
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action" 			:"PostShare",
							"idOriginalPost" 	:idOriginalPost
						},
			success: 	function(data) {
							if (data.success && data.success == true) {
								window.location.reload();
							}
						}
		});
	//*/
}

function reportAbuse() {
	
	var form = "form#report-form";
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}
	
	var idObject	 = $.trim($("form#report-form").find("#idObject").val());
	var sAbuseType	 = $.trim($("form#report-form").find("input[type='radio']:checked").val());
	var sDescription = $.trim($("form#report-form").find("#sDescription").val());
	
	
	if (idObject == "") 	{ return false; }
	if (sAbuseType == "") 	{ return false; }
	if (sDescription == "") { return false; }
	
	
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action" 		:"ReportAbuse",
							"idObject" 		:idObject,
							"sAbuseType" 	:sAbuseType,
							"sDescription" 	:sDescription
						},
			success: 	function(data) {
							if (data.success && data.success == true) {
								window.location.reload();
							}
						}
		});
	
}

function unreportAbuse(idObject) {
	
	if (idObject == "") { return false; }
	
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action" 	:"UnreportAbuse",
							"idObject" 	:idObject
						},
			success: 	function(data) {
							if (data.success && data.success == true) {
								window.location.reload();
							}
						}
		});
}



function albumCreate(idProfile) {

	var form = "form#create-album-form";
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}
	
	var idProfile 		= $.trim(idProfile);
	var sDisplayName 	= $.trim($("form#create-album-form").find("input#sDisplayName").val());
	
	if (sDisplayName == "") { return; }
	
	/*
	var strTest  = "lib/socialAPI.php?action=AlbumCreate";
		strTest += "&idProfile="+idProfile;
		strTest += "&sDisplayName="+sDisplayName;
	window.location = strTest;
	return;
	//*/
	
	//*
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action" 			:"AlbumCreate",
							"idProfile" 		:idProfile,
							"sDisplayName" 		:sDisplayName
						},
			success: 	function(data) {
							
							if (data.success && data.success == true) {
								window.location.reload();
								// alert(data.lastId);
							}
						}
		});
	//*/
}

function photoCreate(idReferredObject) {

	var form = "form#share-photo-form";
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}
	
	var idReferredObject = $.trim(idReferredObject);
	var sContent		 = $.trim($("form#share-photo-form").find("textarea").val()) != "" ? $.trim($("form#share-photo-form").find("textarea").val()) : $.trim($("form#share-form").find("textarea").val());
	var sPicture		 = $("form#share-photo-form").find("input[type='file']").val();
	
	if (sContent == "") { return; }
	
	/*
	var strTest  = "lib/socialAPI.php?action=PhotoCreate";
		strTest += "&idReferredObject="+idReferredObject;
		strTest += "&sContent="+sContent;
		strTest += "&sPicture="+sPicture;
	window.location = strTest;
	return;
	//*/
	
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action" 			:"PhotoCreate",
							"idReferredObject" 	:idReferredObject,
							"sContent" 			:sContent,
							"sPicture"			:sPicture
						},
			success: 	function(data) {
							
							/*
							var strTest = "";
							for (i in data) { strTest += i + ":" + data[i] + "\n"; }
							alert(strTest);
							*/
							
							if (data.success && data.success == true) {
								window.location.reload();
								// alert(data.lastId);
							}
						}
		});
}

function eventCreate(idReferredObject) {
	
	var form = "form#create-event-form";
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}
	
	var idReferredObject 	= $.trim(idReferredObject);
	
	var sDisplayName		= $.trim($("form#create-event-form").find("#sDisplayName").val());
	var tsBegin				= sqlDate($.trim($("form#create-event-form").find("#tsBegin").val())) + " " + $.trim($("form#create-event-form").find("#tsBeginTime option:selected").val());
	var tsEnd				= sqlDate($.trim($("form#create-event-form").find("#tsEnd").val())) + " " + $.trim($("form#create-event-form").find("#tsEndTime option:selected").val());
	var sAddress			= $.trim($("form#create-event-form").find("#sAddress").val());
	var sComplement			= $.trim($("form#create-event-form").find("#sComplement").val());
	var idADMINCountry		= $.trim($("form#create-event-form").find("#idADMINCountry").val());
	var idADMINState		= $.trim($("form#create-event-form").find("#idADMINState").val());
	var idADMINCity			= $.trim($("form#create-event-form").find("#idADMINCity").val());
	var sNeighborhood		= $.trim($("form#create-event-form").find("#sNeighborhood").val());
	var sPostalCode			= $.trim($("form#create-event-form").find("#sPostalCode").val());
	var sSite				= $.trim($("form#create-event-form").find("#sSite").val());
	var sDescription		= $.trim($("form#create-event-form").find("#sDescription").val());
	
	/*
	var strTest  = "lib/socialAPI.php?action=EventCreate";
		strTest += "&idReferredObject="	+idReferredObject;
		strTest += "&sDisplayName=" 	+sDisplayName;
		strTest += "&tsBegin=" 			+tsBegin;
		strTest += "&tsEnd=" 			+tsEnd;
		strTest += "&sAddress=" 		+sAddress;
		strTest += "&sComplement=" 		+sComplement;
		strTest += "&idADMINCountry=" 	+idADMINCountry;
		strTest += "&idADMINState=" 	+idADMINState;
		strTest += "&idADMINCity=" 		+idADMINCity;
		strTest += "&sNeighborhood=" 	+sNeighborhood;
		strTest += "&sPostalCode=" 		+sPostalCode;
		strTest += "&sSite=" 			+sSite;
		strTest += "&sDescription=" 	+sDescription;
		
		
	window.location = strTest;
	return;
	//*/
	
	//*
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action" 			:"EventCreate",
							"idReferredObject" 	:idReferredObject,
							"sDisplayName" 		:sDisplayName,
							"tsBegin" 			:tsBegin,
							"tsEnd" 			:tsEnd,
							"sAddress" 			:sAddress,
							"sComplement" 		:sComplement,
							"idADMINCountry" 	:idADMINCountry,
							"idADMINState" 		:idADMINState,
							"idADMINCity" 		:idADMINCity,
							"sNeighborhood" 	:sNeighborhood,
							"sPostalCode" 		:sPostalCode,
							"sSite" 			:sSite,
							"sDescription" 		:sDescription
						},
			success: 	function(data) {
							if (data.success && data.success == true) {
								window.location.reload();
							}
						}
		});
	// */
}


function eventEdit() {
	
	var form = "form#edit-event-form";
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}
	
	var idEvent				= $.trim($("form#edit-event-form").find("#idReferredObject").val());
	
	var sDisplayName		= $.trim($("form#edit-event-form").find("#sDisplayName").val());
	var tsBegin				= sqlDate($.trim($("form#edit-event-form").find("#tsBegin").val())) + " " + $.trim($("form#edit-event-form").find("#tsBeginTime option:selected").val());
	var tsEnd				= sqlDate($.trim($("form#edit-event-form").find("#tsEnd").val())) + " " + $.trim($("form#edit-event-form").find("#tsEndTime option:selected").val());
	var sAddress			= $.trim($("form#edit-event-form").find("#sAddress").val());
	var sComplement			= $.trim($("form#edit-event-form").find("#sComplement").val());
	var idADMINCountry		= $.trim($("form#edit-event-form").find("#idADMINCountry").val());
	var idADMINState		= $.trim($("form#edit-event-form").find("#idADMINState").val());
	var idADMINCity			= $.trim($("form#edit-event-form").find("#idADMINCity").val());
	var sNeighborhood		= $.trim($("form#edit-event-form").find("#sNeighborhood").val());
	var sPostalCode			= $.trim($("form#edit-event-form").find("#sPostalCode").val());
	var sSite				= $.trim($("form#edit-event-form").find("#sSite").val());
	var sDescription		= $.trim($("form#edit-event-form").find("#sDescription").val());
	
	/*
	var strTest  = "lib/socialAPI.php?action=EventEdit";
		strTest += "&idReferredObject="	+idReferredObject;
		strTest += "&sDisplayName=" 	+sDisplayName;
		strTest += "&tsBegin=" 			+tsBegin;
		strTest += "&tsEnd=" 			+tsEnd;
		strTest += "&sAddress=" 		+sAddress;
		strTest += "&sComplement=" 		+sComplement;
		strTest += "&idADMINCountry=" 	+idADMINCountry;
		strTest += "&idADMINState=" 	+idADMINState;
		strTest += "&idADMINCity=" 		+idADMINCity;
		strTest += "&sNeighborhood=" 	+sNeighborhood;
		strTest += "&sPostalCode=" 		+sPostalCode;
		strTest += "&sSite=" 			+sSite;
		strTest += "&sDescription=" 	+sDescription;
		
		
	window.location = strTest;
	return;
	//*/
	
	//*
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action" 			:"EventEdit",
							"idEvent" 			:idEvent,
							"sDisplayName" 		:sDisplayName,
							"tsBegin" 			:tsBegin,
							"tsEnd" 			:tsEnd,
							"sAddress" 			:sAddress,
							"sComplement" 		:sComplement,
							"idADMINCountry" 	:idADMINCountry,
							"idADMINState" 		:idADMINState,
							"idADMINCity" 		:idADMINCity,
							"sNeighborhood" 	:sNeighborhood,
							"sPostalCode" 		:sPostalCode,
							"sSite" 			:sSite,
							"sDescription" 		:sDescription
						},
			success: 	function(data) {
							if (data.success && data.success == true) {
								window.location.reload();
							}
						}
		});
	// */
}



function commentCreate() {
	
	var form = "form#comment-form";
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}
	
	var idReferredObject = $.trim($("form#comment-form").closest("article.post").attr("data-key"));
	var sContent		 = $.trim($("form#comment-form").find("textarea").val());
	
	if (idReferredObject == "") { return false; }
	if (sContent == "") 		{ return false; }
	
	/*
	var strTest  = "lib/socialAPI.php?action=CommentCreate";
		strTest += "&idReferredObject="+idReferredObject;
		strTest += "&sContent="+sContent;
	window.location = strTest;
	//*/
	
	//*
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action" 			:"CommentCreate",
							"idReferredObject" 	:idReferredObject,
							"sContent" 			:sContent
						},
			success: 	function(data) {
							if (data.success && data.success == true) {
								window.location.reload();
							}
						}
		});
	//*/
}


function likeObject(idReferredObject) {
	
	var idReferredObject = $.trim(idReferredObject);
	
	if (idReferredObject == "") { return false; }
	
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action" 			:"likeObject",
							"idReferredObject" 	:idReferredObject
						},
			success: 	function(data) {
							if (data.success && data.success == true) {
								window.location.reload();
							}
						}
		});
}

function dislikeObject(idReferredObject) {
	
	var idReferredObject = $.trim(idReferredObject);
	
	if (idReferredObject == "") { return false; }
	
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action" 			:"dislikeObject",
							"idReferredObject" 	:idReferredObject
						},
			success: 	function(data) {
							if (data.success && data.success == true) {
								window.location.reload();
							}
						}
		});
}

/*function addSelect(object) {
	
	if ($(object).find("option:selected").val() != 0) {
		
		if ($(object).hasClass("inuse") == false) {
			
			// adiciona um novo select:
			if($("select.receiver").parent().length < (object.children().length - 1)) {
				var clonado = $("div.row").last().clone();
				$("div.row").last().after(clonado);
			}
			
			// marca o select como sendo usado:
			$(object).addClass("inuse");
			
			
			$(object).parent().find("button.basic-button").css({"display":"inline-block"});
			
		}
	}
		// lista todos os valores dos selects usados:
		var optionSelect = new Array();
		for (i=0; i < $("form#message-form select.receiver").length; i++) {
			optionSelect.push($($("form#message-form select.receiver")[i]).find("option:selected").val());
		}
		
		
		for (i=0; i < $("form#message-form select.receiver").length; i++) {
			
			var select  = $($("form#message-form select.receiver")[i]);
			var options = $(select).find("option");
			
			// limpa o attr disabled de todos os options:
			for (j=0; j<options.length; j++) {
				$(options[j]).removeAttr("disabled");
			}
			
			// coloca o attr disabled nos options ja usados:
			for (j=0; j<options.length; j++) {
				
				for (k=0; k<optionSelect.length; k++) {
					
					if ( $(options[j]).val() == optionSelect[k]) {
						if ( $(options[j]).attr("selected") != true) {
							$(options[j]).attr("disabled",true);
							break;
						}
					}

				}
			}
			
		}
	// }
		if($(object).attr("id") == "idReciver" $("select#idReceiverExtra").length == 0) {
		
			$(object).after('<label class="label">para : </label> <select id="idReceiverExtra"></select>');
			
			var content = $(object).children();
			var clonado = content.clone();
	
			$("select#idReceiverExtra").last().append(clonado);
			$('select option[value="'+optionSelect+'"]').last().attr({"disabled":"disabled"});
			
		} else if($(object).attr("id") == "idReceiverExtra" && $(object).length > 0) {
		
			$(object).after('<label class="label">para : </label> <select id="idReceiverExtra"></select>');
			
			var content = $(object).children();
			var clonado = content.clone();
	
			$("select#idReceiverExtra").last().append(clonado);
			$('select option[value="'+optionSelect+'"]').last().attr({"disabled":"disabled"});
			$('select option[value="'+optionSelect+'"]').before().attr({"disabled":"disabled"});
		}
	
}

function removeSelect(object) {

	$(object).closest("div.row").remove();
	
	var select = $(object).closest("div.row").closest("form").find("select").last();
	
	select.css({"background-color":"red"});
	
	select.change();
		
	//if (select.hasClass("inuse") == true) {
		
	//	addSelect($("div.row").last().find(""));
	//}
	

}*/



function messageCreate() {
	
	var idReceiver;
	var idConversation;
	var sContent;
	
	var form = "form#message-form";
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}
	
	if ($.trim($("form#message-form").find("input#idReceiver").val()) != '') {
		idReceiver = $.trim($("form#message-form").find("input#idReceiver").val());
	} else {
		idReceiver = $("form#message-form select.receiver").val();
	}
		
	idReceiver 		= idReceiver.toString();
	idConversation 	= $.trim($("form#message-form").find("input#idConversation").val())
	sContent 		= $.trim($("form#message-form").find("textarea").val());
	
	/*
	var strTest  = "lib/socialAPI.php?action=messageCreate";
		strTest += "&idReceiver="+idReceiver;
		strTest += "&idConversation="+idConversation;
		strTest += "&sContent="+sContent;
		
	alert(strTest);
	window.location = strTest;
	// return;
	// */
	
	// /*
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action" 			:"messageCreate",
							"idReceiver"		:idReceiver,
							"idConversation"	:idConversation,
							"sContent" 			:sContent
						},
			success: 	function(data) {
							if (data.success && data.success == true) {
								window.location.reload();
							}
						}
		});
	// */
} 






function loadHistory(target, idList, iOffset, iLimit, iType) {
	
	$(target).fadeOut("fast");
	
	$.ajax({
		type: 		"POST",
		url: 		"lib/socialAPI.php",
		dataType: 	"json",
		data: 		{
						"action"	:"loadHistory",
						"idList"	:idList.toString(),
						"iOffset"	:iOffset,
						"iLimit"	:iLimit,
						"iType"		:iType
					},
		success: 	function(data) {
						
						if (data.success && data.success == true) {
							
							if (data.result) {
								
								var item;
								var strPosts = '';
								
								for (itm in data.result) {
									
									item = data.result[itm];
									
				strPosts += '<article class="post root-post" data-key="'+item.id+'">';
				
				// post principal:
				
				strPosts += 	'<div class="post-photo">';
				strPosts += 		'<a href="index.php?id='+uniqid()+'&sob='+item.owner.id+'&prob='+item.owner.id+'" target="_self">';
				strPosts += 		'<img src="'+(item.owner.properties.sAvatarPath != null ? item.owner.properties.sAvatarPath : "img/avatar-default.jpg")+'" alt="" border="0" width="50">';
				strPosts += 		'</a>';
				strPosts += 	'</div>';
				
				strPosts += 	'<div class="post-info">';
				strPosts += 		'<a href="index.php?id='+uniqid()+'&sob='+item.owner.id+'&prob='+item.owner.id+'"><strong>'+item.owner.sDisplayName+'</strong></a>&nbsp;';
				strPosts += 		'<em style="font-size:10px;color:#999;">postou em <a href="index.php?id='+uniqid()+'&sob='+item.id+'&prob='+item.owner.id+'" title="permalink" style="color:#999;">'+item.item.tsCreation+'</a></em><br>';
				
				
				
				
				// post é um vídeo:
				if (item.properties.sDataVideo != null) {
					
					try {
					
						var values = $.parseJSON(item.properties.sDataVideo);
						
						if (values['id'] != null) {
							
							strPosts += '<div class="thumb" style="display:inline-block;width:120px;margin:5px 10px 0 0;padding:0;"><a href="#" title="assista ao vídeo" data-yvid="'+values['id']+'"><img src="'+values['thumb']+'"></a></div>';
						}
						
					} catch(error){ }
					
					strPosts += '<div class="desc" style="display:inline-block;margin:5px 0 0 0;vertical-align:top;width:300px;">'+identifyLink(item.details.sContent)+'</div>';
					strPosts += '<div class="embed" style="display:none;margin:5px 0;"></div>';
				
				// post é uma imagem:
				} else if (item.properties.sDataPhoto != null) {
					
					try {
						
						var values = $.parseJSON(item.properties.sDataPhoto);
						
						if (values['thumb'] != null) {
						
							strPosts += '<div class="thumb-img" style="display:inline-block;width:120px;margin:5px 10px 0 0;padding:0;"><a href="index.php?id='+uniqid()+'&sob='+item.id+'&prob='+item.owner.id+'" title="veja a imagem" target="_self"><img src="'+values['thumb']+'" style="width:120px;"></a></div>';
						}
						
					} catch(error){ }
					
					strPosts += '<div class="desc" style="display:inline-block;margin:5px 0 0 0;vertical-align:top;width:300px;">'+identifyLink(item.details.sContent)+'</div>';
					
				// post de texto simples:
				} else {
					strPosts += identifyLink(item.details.sContent);
				}
				
				strPosts += 		'<br>';
				
				
				// barra de interação (gostar, comentar, compartilhar):
				strPosts += '<div class="interaction-bar">';
				
				var like = item.youLike == true ? '<a href="#" class="dislike"><strong>você gostou</strong> (desfazer)</a>' : '<a href="#" class="like">gostei</a>' ;
				
				strPosts += like;
				
				strPosts += ' | <a href="#" class="create-comment">comentar</a> | <a href="#" class="share-post">compartilhar</a>';
				
				var report = item.youReport == true ? ' | <a><strong>você denunciou</strong></a>' : ' | <a href="#" class="report-abuse">denunciar</a>' ;
				
				strPosts += report;
				
				
				
				if (item.likes > 0) {
					var likeText = item.likes > 1 ? item.likes+' pessoas gostaram' : item.likes+' pessoa gostou';
					strPosts += '<br><span style="font-size:11px;">'+likeText+'</span>';
				}
				
				if (item.reports > 0) {
					var reportText = item.reports > 1 ? item.reports+' pessoas denunciaram' : item.reports+' pessoa denunciou';
					strPosts += '<br><span style="font-size:11px;color:#C00;">'+reportText+'</span>';
				}
				
				strPosts += '</div>';
				
				strPosts += 	'</div>';
				
				
				
				// comentários:
				for (comm in item.comments) {
					
					comment = item.comments[comm];
					
				strPosts += '<article class="post comment" data-key="'+comment.id+'">';
				
				strPosts += 	'<div class="post-photo">';
				strPosts += 		'<a href="index.php?id='+uniqid()+'&sob='+comment.owner.id+'&prob='+comment.owner.id+'" target="_self">';
				strPosts += 		'<img src="'+(comment.owner.properties.sAvatarPath != null ? comment.owner.properties.sAvatarPath : "img/avatar-default.jpg")+'" alt="" border="0" width="30">';
				strPosts += 		'</a>';
				strPosts += 	'</div>';
				
				strPosts += 	'<div class="post-info">';
				strPosts += 		'<a href="index.php?id='+uniqid()+'&sob='+comment.owner.id+'&prob='+comment.owner.id+'"><strong>'+comment.owner.sDisplayName+'</strong></a>&nbsp;';
				strPosts += 		'<em style="font-size:10px;color:#999;">comentou em <a href="index.php?id='+uniqid()+'&sob='+comment.id+'&prob='+comment.owner.id+'" title="permalink" style="color:#999;">'+comment.item.tsCreation+'</a></em><br>';
				strPosts += 		identifyLink(comment.details.sContent);
				strPosts += 		'<br>';
				
				
				// barra de interação (gostar, comentar, compartilhar):
				strPosts += '<div class="interaction-bar">';
				
				var like = comment.youLike == true ? '<a href="#" class="dislike"><strong>você gostou</strong> (desfazer)</a>' : '<a href="#" class="like">gostei</a>' ;
				
				strPosts += like;
				
				strPosts += ' | <a href="#" class="create-comment">comentar</a>';
				
				var report = comment.youReport == true ? ' | <a><strong>você denunciou</strong></a>' :  ' | <a href="#" class="report-abuse">denunciar</a>' ;
				
				strPosts += report;
				
				if (comment.likes > 0) {
					var likeText = comment.likes > 1 ? comment.likes+' pessoas gostaram' : comment.likes+' pessoa gostou';
					strPosts += '<br><span style="font-size:11px;">'+likeText+'</span>';
				}
				
				if (comment.reports > 0) {
					var reportText = comment.reports > 1 ? comment.reports+' pessoas denunciaram' : comment.reports+' pessoa denunciou';
					strPosts += '<br><span style="font-size:11px;color:#C00;">'+reportText+'</span>';
				}
				
				strPosts += '</div>';
				
				
				
				strPosts += 	'</div>';
				
				strPosts += '</article>';
				
				}
				
				
				strPosts += '</article>';
								
								}
								
								$(target).before(strPosts);
								$(target).fadeIn("fast");
								
							}
							
						} else if (data.success && data.success == false) {
							
						}
					}
	});
	
}


function confirmationDelete(option) {

	if(option == 'show') {

		$("div#confirmation-container").stop(true,true).delay(250).slideDown(250);
		
	} else if(option == 'hide') {
	
		$("div#confirmation-container").stop(true,true).delay(250).slideUp(250);
	}

}

function deleteAccount(form) {
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}

	var sMotivo = $(form).find("input[name='motivo']:checked").val();
	
	switch(sMotivo) {
		case "1":
			sMotivo = 'Não sei como usar a Rede Social';
		break;
		
		case "2":
			sMotivo = 'Isto é temporário. Eu voltarei';
		break;
		
		case "3":
			sMotivo = 'Eu não gostei do conteúdo e informações enviadas.';
		break;
		
		case "4":
			sMotivo = 'Recebo muitos e-mails, convites e solicitações.';
		break;
		
		case "5":
			sMotivo = $(form).find("textarea#mais").val();
		break;
	}
	
	$.ajax({
		type: 		"POST",
		url: 		"lib/socialAPI.php",
		dataType: 	"json",
		data: 		{
						"action"	: "deleteAccount",
						"sMotivo"	: sMotivo
					},
		success: 	function(data) {
						if (data.success && data.success == true) {
							event.preventDefault();
							event.stopPropagation();
							userLogout();
						}
					}
	});

}



function PageFollow(idProfile, idPage) {
	
	var idProfile	= !idProfile ? "" : idProfile;
	var idPage		= !idPage 	 ? "" : idPage;
	
	if (idProfile == "" || idPage == "") {
		return false;
	}
	
	$.ajax({
		type: 		"POST",
		url: 		"lib/socialAPI.php",
		dataType: 	"json",
		data: 		{
						"action"	:"PageFollow",
						"idProfile"	:idProfile,
						"idPage"	:idPage
					},
		success: 	function(data) {
						if (data.success && data.success == true) {
							$("button#follow-page").stop(true,true).fadeOut(500);
							window.location.reload();
						}
					}
	});

}

function PageUnfollow(idProfile, idPage) {
	
	var idProfile	= !idProfile ? "" : idProfile;
	var idPage		= !idPage 	 ? "" : idPage;
	
	if (idProfile == "" || idPage == "") {
		return false;
	}
	
	$.ajax({
		type: 		"POST",
		url: 		"lib/socialAPI.php",
		dataType: 	"json",
		data: 		{
						"action"	:"PageUnfollow",
						"idProfile"	:idProfile,
						"idPage"	:idPage
					},
		success: 	function(data) {
						if (data.success && data.success == true) {
							$("button#unfollow-page").stop(true,true).fadeOut(500);
							window.location.reload();
						}
					}
	});

}




function GroupFollow(idProfile, idGroup) {
	
	var idProfile	= !idProfile ? "" : idProfile;
	var idGroup		= !idGroup 	 ? "" : idGroup;
	
	if (idProfile == "" || idGroup == "") {
		return false;
	}
	
	$.ajax({
		type: 		"POST",
		url: 		"lib/socialAPI.php",
		dataType: 	"json",
		data: 		{
						"action"	:"GroupFollow",
						"idProfile"	:idProfile,
						"idGroup"	:idGroup
					},
		success: 	function(data) {
						if (data.success && data.success == true) {
							$("button#follow-page").stop(true,true).fadeOut(500);
							window.location.reload();
						}
					}
	});

}

function GroupUnfollow(idProfile, idGroup) {
	
	var idProfile	= !idProfile ? "" : idProfile;
	var idGroup		= !idGroup 	 ? "" : idGroup;
	
	if (idProfile == "" || idGroup == "") {
		return false;
	}
	
	$.ajax({
		type: 		"POST",
		url: 		"lib/socialAPI.php",
		dataType: 	"json",
		data: 		{
						"action"	:"GroupUnfollow",
						"idProfile"	:idProfile,
						"idGroup"	:idGroup
					},
		success: 	function(data) {
						if (data.success && data.success == true) {
							$("button#unfollow-page").stop(true,true).fadeOut(500);
							window.location.reload();
						}
					}
	});

}




function deleteReport(idReport) {
	
	$.ajax({
		type: 		"POST",
		url: 		"lib/socialAPI.php",
		dataType: 	"json",
		data: 		{
						"action" 	:"deleteReport",
						"idReport" 	:idReport
					},
		success: 	function(data) {
						if (data.success && data.success == true) {
							window.location.reload();
						}
					}
	});
	
}

function deleteObject(idObject) {
	
	// alert("tentando excluir o objeto "+idObject);
	/*
	var strTest  = 'lib/socialAPI.php';
		strTest += '?action=deleteObject';
		strTest += '&idObject=idObject';
	
	window.location = strTest;
	*/
	
	$.ajax({
		type: 		"POST",
		url: 		"lib/socialAPI.php",
		dataType: 	"json",
		data: 		{
						"action" 	:"deleteObject",
						"idObject" 	:idObject
					},
		success: 	function(data) {
						if (data.success && data.success == true) {
							window.location.reload();
						}
					}
	});
}




function updateAccount(idProfile, targetForm) {
	
	var form = targetForm;
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}
	
	var idProfile	= !idProfile  ? "" : idProfile;
	var targetForm	= !targetForm ? "" : targetForm;
	
	var sFullName		= $.trim($(targetForm).find("input#sFullName").val());
	var sDisplayName	= $.trim($(targetForm).find("input#sDisplayName").val());
	var sEmail			= $.trim($(targetForm).find("input#sEmail").val());
	var dtBirthday		= $.trim($(targetForm).find("input#dtBirthday").val());
	var idADMINCountry	= $.trim($(targetForm).find("select#idADMINCountry").find("option:selected").val());
	var idADMINState	= $.trim($(targetForm).find("select#idADMINState").find("option:selected").val());
	var idADMINCity		= $.trim($(targetForm).find("input#idADMINCity").val());
	var sPostalCode		= $.trim($(targetForm).find("input#sPostalCode").val());
	
	var arSpiritualAffinities = new Array();
	var afinidades = $(targetForm).find("input:checkbox[name='afinidades-espirituais']:checked");
	
	for ( i=0; i<afinidades.length; i++ ) {
		arSpiritualAffinities.push( $(afinidades[i]).val() );
	}
	
	
	/*
	var strTest  = "lib/socialAPI.php?action=updateAccount";
		strTest += "&idProfile="		+idProfile;
		strTest += "&sFullName="		+sFullName;
		strTest += "&sDisplayName="		+sDisplayName;
		strTest += "&sEmail="			+sEmail;
		strTest += "&dtBirthday="		+dtBirthday;
		strTest += "&idADMINCountry="	+idADMINCountry;
		strTest += "&idADMINState="		+idADMINState;
		strTest += "&idADMINCity="		+idADMINCity;
		strTest += "&sPostalCode="		+sPostalCode;
		strTest += "&spiritualAffinities="+arSpiritualAffinities.toString();
		
	window.location = strTest;
	//*/
	
	//*
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action"		:"updateAccount",
							"idProfile"		:idProfile,
							"sFullName"		:sFullName,
							"sDisplayName"	:sDisplayName,
							"sEmail"		:sEmail,
							"dtBirthday"	:dtBirthday,
							"idADMINCountry":idADMINCountry,
							"idADMINState"	:idADMINState,
							"idADMINCity"	:idADMINCity,
							"sPostalCode"	:sPostalCode,
							"spiritualAffinities":arSpiritualAffinities.toString()
							
						},
			success: 	function(data) {
							if (data.success && data.success == true) {
								window.location.reload();
							}
						}
		});
	//*/
	
	
}

function updateConfig(idProfile, targetForm) {

	var form = targetForm;
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}

	var idProfile	= !idProfile  ? "" : idProfile;
	var targetForm	= !targetForm ? "" : targetForm;

	var sFullName		= $.trim($(targetForm).find("input#sFullName").val());
	var sDisplayName	= $.trim($(targetForm).find("input#sDisplayName").val());
	var dtBirthday		= $.trim($(targetForm).find("input#dtBirthday").val());
	
	var idADMINCountry	= $.trim($(targetForm).find("select#idADMINCountry").find("option:selected").val());
	var idADMINState	= $.trim($(targetForm).find("select#idADMINState").find("option:selected").val());
	
	var idADMINCity		= $.trim($(targetForm).find("input#idADMINCity").val());
	var sPostalCode		= $.trim($(targetForm).find("input#sPostalCode").val());
	
	var sEmailAtual		= $.trim($(targetForm).find("input#sEmailAtual").val())		== "" ? "" : $.trim($(targetForm).find("input#sEmailAtual").val());
	var sEmailNovo		= $.trim($(targetForm).find("input#sEmailNovo").val())		== "" ? "" : $.trim($(targetForm).find("input#sEmailNovo").val());
	
	var sPasswordAtual	= $.trim($(targetForm).find("input#sPasswordAtual").val()) 	== "" ? "" : $.trim($(targetForm).find("input#sPasswordAtual").val());
	var sPasswordNovo	= $.trim($(targetForm).find("input#sPasswordNovo").val()) 	== "" ? "" : $.trim($(targetForm).find("input#sPasswordNovo").val());
	
	var arSpiritualAffinities = new Array();
	
	for (i=0;i<$(targetForm).find("input:checkbox[name='afinidades-espirituais']:checked").length;i++) {
		arSpiritualAffinities.push( $($(targetForm).find("input:checkbox[name='afinidades-espirituais']:checked")[i]).val() );
	}
	
	/*	
	var strTest  = "lib/socialAPI.php?action=updateConfig";
		strTest += "&sFullName="			+sFullName;
		strTest += "&sDisplayName="			+sDisplayName;
		strTest += "&dtBirthday="			+dtBirthday;
		strTest += "&idADMINCountry="		+idADMINCountry;
		strTest += "&idADMINState="			+idADMINState;
		strTest += "&idADMINCity="			+idADMINCity;
		strTest += "&sPostalCode="			+sPostalCode;
		strTest += "&sEmailAtual="			+sEmailAtual;
		strTest += "&sEmailNovo="			+sEmailNovo;
		strTest += "&sPasswordAtual="		+sPasswordAtual;
		strTest += "&sPasswordNovo="		+sPasswordNovo;
		strTest += "&spiritualAffinities="	+arSpiritualAffinities.toString();
	
	alert(strTest);
	
	window.location = strTest;
	//*/
	
	//*
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action"			 :"updateConfig",
							"sFullName"			 :sFullName,
							"sDisplayName"		 :sDisplayName,
							"dtBirthday"		 :dtBirthday,
							"idADMINCountry"	 :idADMINCountry,
							"idADMINState"		 :idADMINState,
							"idADMINCity"		 :idADMINCity,
							"sPostalCode"		 :sPostalCode,
							"sEmailAtual"		 :sEmailAtual,
							"sEmailNovo"		 :sEmailNovo,
							"sPasswordAtual"	 :sPasswordAtual,
							"sPasswordNovo"		 :sPasswordNovo,
							"spiritualAffinities":arSpiritualAffinities.toString()
							
						},
			success: 	function(data) {
							if (data.success && data.success == true) {
								window.location.reload();
							} else if(data.success && data.success == "erro email") {
								$("form#form-config").get(0).reset();
								$("div#failure-container").stop(true,true).delay(250).slideDown(250);
							}
						}
		});
	//*/
	
}


function updatePage(idPage, targetForm) {

	var form = targetForm;
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}
	
	var idPage		= !idPage 	  ? "" : idPage;
	var targetForm	= !targetForm ? "" : targetForm;
	
	var sDisplayName	= $.trim($(targetForm).find("input#sDisplayName").val());
	var sDescription	= $.trim($(targetForm).find("input#sDescription").val());
	
	var sAddress		= $.trim($(targetForm).find("input#sAddress").val());
	var sComplement		= $.trim($(targetForm).find("input#sComplement").val());
	var sNeighborhood	= $.trim($(targetForm).find("input#sNeighborhood").val());
	var idADMINCountry	= $.trim($(targetForm).find("select#idADMINCountry").find("option:selected").val());
	var idADMINState	= $.trim($(targetForm).find("select#idADMINState").find("option:selected").val());
	var idADMINCity		= $.trim($(targetForm).find("input#idADMINCity").val());
	var sPostalCode		= $.trim($(targetForm).find("input#sPostalCode").val());
	
	/*
	var strTest  = "lib/socialAPI.php";
		strTest += "?action="			+"updatePage";
		strTest += "&idPage="			+idPage;
		strTest += "&sDisplayName="		+sDisplayName;
		strTest += "&sDescription="		+sDescription;
		strTest += "&sAddress="			+sAddress;
		strTest += "&sComplement="		+sComplement;
		strTest += "&sNeighborhood="	+sNeighborhood;
		strTest += "&idADMINCountry="	+idADMINCountry;
		strTest += "&idADMINState="		+idADMINState;
		strTest += "&idADMINCity="		+idADMINCity;
		strTest += "&sPostalCode="		+sPostalCode;
	
	window.location = strTest;
	//*/
	
	//*
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action"		:"updatePage",
							"idPage"		:idPage,
							"sDisplayName"	:sDisplayName,
							"sDescription"	:sDescription,
							"sAddress"		:sAddress,
							"sComplement"	:sComplement,
							"sNeighborhood"	:sNeighborhood,
							"idADMINCountry":idADMINCountry,
							"idADMINState"	:idADMINState,
							"idADMINCity"	:idADMINCity,
							"sPostalCode"	:sPostalCode
							
						},
			success: 	function(data) {
							if (data.success && data.success == true) {
								window.location.reload();
							}
						}
		});
	//*/
	
	
}

function updateGroup(idGroup, targetForm) {
	
	var form = targetForm;
	
	if(validateForm(form) == false) {
		alert("Os campos marcados devem ser preenchidos!");
		return;
	}
	
	var idGroup		= !idGroup 	  ? "" : idGroup;
	var targetForm	= !targetForm ? "" : targetForm;
	
	var sDisplayName		= $.trim($(targetForm).find("input#sDisplayName").val());
	var iGroupType			= $.trim($(targetForm).find("select#iGroupType option:selected").val());
	var sGroupTheme			= $.trim($(targetForm).find("input#sGroupTheme").val());
	var dtBegin				= $.trim($(targetForm).find("input#dtBegin").val());
	var dtEnd				= $.trim($(targetForm).find("input#dtEnd").val());
	var iPeriodicity		= $.trim($(targetForm).find("select#iPeriodicity option:selected").val());
	var iPeriodicityDetail	= $.trim($(targetForm).find("select#iPeriodicity"+iPeriodicity+" option:selected").val());
	var tsOracao			= $.trim($(targetForm).find("select#tsOracao option:selected").val());
	var idADMINCountry		= $.trim($(targetForm).find("select#idADMINCountry option:selected").val());
	var idADMINState		= $.trim($(targetForm).find("select#idADMINState option:selected").val());
	var idADMINCity			= $.trim($(targetForm).find("input#idADMINCity").val());
	var sDescription		= $.trim($(targetForm).find("textarea#sDescription").val());
	var sSite				= $.trim($(targetForm).find("input#sSite").val());
	
	/*
	var strTest  = "lib/socialAPI.php";
		strTest += "?action="				+"updateGroup";
		strTest += "&idGroup="				+idGroup;
		strTest += "&sDisplayName="			+sDisplayName;
		strTest += "&iGroupType="			+iGroupType;
		strTest += "&sGroupTheme="			+sGroupTheme;
		strTest += "&dtBegin="				+dtBegin;
		strTest += "&dtEnd="				+dtEnd;
		strTest += "&iPeriodicity="			+iPeriodicity;
		strTest += "&iPeriodicityDetail="	+iPeriodicityDetail;
		strTest += "&tsOracao="				+tsOracao;
		strTest += "&idADMINCountry="		+idADMINCountry;
		strTest += "&idADMINState="			+idADMINState;
		strTest += "&idADMINCity="			+idADMINCity;
		strTest += "&sDescription="			+sDescription;
		strTest += "&sSite="				+sSite;
	
	window.location = strTest;
	//*/
	
	//*
	$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action"				:"updateGroup",
							"idGroup"				:idGroup,
							"sDisplayName"			:sDisplayName,
							"iGroupType"			:iGroupType,
							"sGroupTheme"			:sGroupTheme,
							"dtBegin"				:dtBegin,
							"dtEnd"					:dtEnd,
							"iPeriodicity"			:iPeriodicity,
							"iPeriodicityDetail"	:iPeriodicityDetail,
							"tsOracao"				:tsOracao,
							"idADMINCountry"		:idADMINCountry,
							"idADMINState"  		:idADMINState,
							"idADMINCity"  			:idADMINCity,
							"sDescription"  		:sDescription,
							"sSite"  				:sSite
						},
			success: 	function(data) {
							if (data.success && data.success == true) {
								window.location.reload();
							}
						}
		});
	//*/
	
	
}




// BEGIN: FUNÇÕES PARA UPLOAD DE ARQUIVO - USADO APENAS PARA AVATAR

// var AjaxUpload = function() {

var http		 = createRequestObject();
var timeInterval = "";

function createRequestObject() {
    var obj;
    var browser = navigator.appName;
    if(browser == "Microsoft Internet Explorer"){
    	return new ActiveXObject("Microsoft.XMLHTTP");
    }
    else{
    	return new XMLHttpRequest();
    }   
}

function uploadFile() {
	timeInterval = setInterval("traceUpload()", 1500);
}

function traceUpload() {
   http.onreadystatechange = handleResponse;
   http.open("GET", "lib/socialAPI.php?action=uploadFile&checkFile=1");
   http.send(null); 
}

function handleResponse() {
	
	if (http.readyState == 4) {
		
		var response    = http.responseText;
		var objResponse = jQuery.parseJSON(response);
		var pathToFile;
		
	//	alert("objResponse:" + objResponse.success);
	//	clearInterval(timeInterval);
		
		//*
		if (objResponse) {
		
			if (objResponse.success) {
			
				if (objResponse.success == true || objResponse.success == "true") {
					
					if (objResponse.status == "complete") {
						
						clearInterval(timeInterval);
						pathToFile = objResponse.file;
						
					//	alert("upload completed");
					//	alert("pathToFile = "+pathToFile);
						
						// salva o endereço da imagem como avatar no banco de dados:
						$.ajax({
							type: 		"POST",
							url: 		"lib/socialAPI.php",
							dataType: 	"json",
							data: 		{
											"action" 			:"ObjectSetProperty",
											"idSOCIALObject" 	:"user",
											"idSOCIALProperty" 	:"2",
											"sValue" 			:pathToFile
										},
							success: 	function(data) {
											if (data.success && data.success == true) {
												// window.location.reload();
												$("div.photo-profile > img").attr("src",pathToFile);
											}
										}
						});
						
					}
					
				} else if (objResponse.success == false || objResponse.success == "false") {
					
					if (objResponse.status == "loading") {
						
					}
					
				}
			}
		}
		//*/
		
    } else {
		// document.getElementById(uploaderId).innerHTML = "<img src='img/loading.gif' alt='loading...'>";
		// alert("uploading...");
	}
	
}



// }

// END: FUNÇÕES PARA UPLOAD DE ARQUIVO





function minimize(form, time) {
	if (time==undefined) { time = "fast"; }
	$(form).css({"padding-bottom":"15px"});
	$(form).find("form").slideUp(time);
	$(form).find("a.minimize").children().css({"display":"none"});
	$(form).find("a.maximize").children().css({"display":"inline-block"});
}

function maximize(form, time) {
	if (time==undefined) { time = "fast"; }
	$(form).css({"padding-bottom":"10px"});
	$(form).find("form").slideDown(time);
	$(form).find("a.maximize").children().css({"display":"none"});
	$(form).find("a.minimize").children().css({"display":"inline-block"});
}



// -------------------------------------------------------------------------------
// verificação dos campos marcados como 'required'
// (usado também como validação em browsers não compatíveis com validadores HTML5)
function validateForm(form) {

	var validRequiredFields = 0;
	
	$(form).find("input").filter("[required]").each(function(index){
		if ( $.trim($(this).val()) == "" ) {
			$(this).addClass("invalid");
			validRequiredFields++;
			
		}
	});

	// validação de textarea	
	$(form).find("textarea").filter("[required]").each(function(index){
		if ( $.trim($(this).val()) == "" ) {
			$(this).addClass("invalid");
			validRequiredFields++;
		}
	});
	
	// validação de select
	$(form).find("select").filter("[required]").each(function(index){
		if ( $.trim($(this).val()) == "" ) {
			$(this).addClass("invalid");
			validRequiredFields++;
		}
	});
	
	if(validRequiredFields > 0) {
		return false;
	} else {
		return true;
	}

}



$(document).ready(function() {
	
	// ----------------------------------------------------------------------
	// profile actions:
	$("button#add-friendship").bind("click", function(event) {
		
		var idProposerProfile = $(this).attr("data-user-id");
		var idGuestProfile	  = $(this).attr("data-object-id");
		
		// alert("ProfileProposeFriendship("+idProposerProfile+", "+idGuestProfile+")");
		ProfileProposeFriendship(idProposerProfile, idGuestProfile);
	});
	
	$("button#accept-friendship").bind("click", function(event) {
		
		var idGuestProfile	  = $(this).attr("data-user-id");
		var idProposerProfile = $(this).attr("data-object-id");
		
		// alert("ProfileAcceptFriendship("+idProposerProfile+", "+idGuestProfile+")");
		ProfileAcceptFriendship(idProposerProfile, idGuestProfile);
	});
	
	$("button#remove-friendship").bind("click", function(event) {
		
		var idProfile		 = $(this).attr("data-user-id");
		var idFriendProfile	 = $(this).attr("data-object-id");
		
		ProfileEndFriendship(idProfile, idFriendProfile);
	});
	
	$("div#pending-friends").find("button.accept-relationship").each(function(){
		$(this).bind("click", function(event) {
			
			var idFriend 	= $(this).closest("div.invitation").attr("data-object-id");
			var idMyProfile = $(this).closest("div.invitation").attr("data-user-id");
			
			// alert("ProfileAcceptFriendship("+idFriend+", "+idMyProfile+")");
			ProfileAcceptFriendship(idFriend, idMyProfile);
		});
	});
	
	$("div#pending-friends").find("button.remove-relationship").each(function(){
		$(this).bind("click", function(event) {
			
			var idProfile		 = $(this).closest("div.invitation").attr("data-user-id");
			var idFriendProfile	 = $(this).closest("div.invitation").attr("data-object-id");
			
			ProfileEndFriendship(idProfile, idFriendProfile);
		});
	});
	
	
	
	// ----------------------------------------------------------------------
	// page actions:
	$("button#follow-page").bind("click", function(event) {
		
		var idProfile = $(this).attr("data-user-id");
		var idPage	  = $(this).attr("data-object-id");
		
		PageFollow(idProfile, idPage);
	});
	
	$("button#unfollow-page").bind("click", function(event) {
		
		var idProfile = $(this).attr("data-user-id");
		var idPage	  = $(this).attr("data-object-id");
		
		PageUnfollow(idProfile, idPage);
	});
	
	
	
	// ----------------------------------------------------------------------
	// group actions:
	$("button#follow-group").bind("click", function(event) {
		
		var idProfile = $(this).attr("data-user-id");
		var idGroup	  = $(this).attr("data-object-id");
		
		GroupFollow(idProfile, idGroup);
	});
	
	$("button#unfollow-group").bind("click", function(event) {
		
		var idProfile = $(this).attr("data-user-id");
		var idGroup	  = $(this).attr("data-object-id");
		
		GroupUnfollow(idProfile, idGroup);
	});
	
	
	
	// ----------------------------------------------------------------------
	$("a#logout").bind("click", function(event) {
		event.preventDefault();
		event.stopPropagation();
		userLogout();
	});
	
	
	
	// ----------------------------------------------------------------------
	// posts:
	$("div.post-box a#share-text").bind("click", function(event){
		event.preventDefault();
		event.stopPropagation();
		
		$("form#share-text-form").show();
		$("form#share-photo-form").hide();
		
		$("div.post-box a#share-text").addClass("active");
		$("div.post-box a#share-photo").removeClass("active");
		
	});
	
	$("div.post-box a#share-photo").bind("click", function(event){
		event.preventDefault();
		event.stopPropagation();
		
		$("form#share-photo-form").show();
		$("form#share-text-form").hide();
		
		$("div.post-box a#share-photo").addClass("active");
		$("div.post-box a#share-text").removeClass("active");
	});
	
	
	
	
	// ----------------------------------------------------------------------
	// carregamento de posts na timeline:
	$("a#load-history").live("click", function(event){
		
		event.stopPropagation();
		event.preventDefault();
		
		var target = $(this);
		
		loadHistory(target, target.attr("data-idlist"), target.attr("data-offset"), 25, target.attr("data-type"));
		target.attr("data-offset", parseInt(target.attr("data-offset"))+25);
		
	});
	
	
	
	// ----------------------------------------------------------------------
	// embed vídeo youtube em posts:
	$("div.post-info > div.thumb > a").live("click", function(event){
		
		event.preventDefault();
		event.stopPropagation();
		
		// alert($(this).attr("data-yvid"));
		var yvid = $(this).attr("data-yvid");
		var embedStr = '<iframe width="399" height="203" src="http://www.youtube.com/embed/'+yvid+'?rel=0" frameborder="0" allowfullscreen>';
		
		$(this).closest("div.post-info").find("div.embed").empty().append(embedStr);
		$(this).closest("div.post-info").find("div.embed").css({"display":""});
		$(this).closest("div.thumb").slideUp(250);
	});
	
	
	
	// ----------------------------------------------------------------------
	// gostar (curtir)
	$("a.like").live("click", function(event){
		
		event.preventDefault();
		event.stopPropagation();
		
		likeObject($(this).closest("article.post").attr("data-key"));
	});
	
	$("a.dislike").live("click", function(event){
		
		event.preventDefault();
		event.stopPropagation();
		
		dislikeObject($(this).closest("article.post").attr("data-key"));
	});
	
	
	
	// ----------------------------------------------------------------------
	// comentários, compartilhamentos, denunciar
	$("a.create-comment").live("click", function(event){
		event.preventDefault();
		event.stopPropagation();
		
		var box = $("div.comment-box").detach();
		
		$(this).closest("article.root-post").append(box);
		box.css({"display":"","opacity":"0"});
		box.animate({"opacity":"1"}, 250);
	});
	
	$("a.share-post").live("click", function(event){
		event.preventDefault();
		event.stopPropagation();
		
		postShare($(this).closest("article.root-post").attr("data-key"));
		
	});
	
	$("a.report-abuse").live("click", function(event){
		event.preventDefault();
		event.stopPropagation();
		
		// reportAbuse($(this).closest("article.post").attr("data-key"));
		$("div.report-box").find("input#idObject").val($(this).closest("article.post").attr("data-key"));
		
		var box = $("div.report-box").detach();
		
		$(this).closest("article.root-post").append(box);
		box.css({"display":"","opacity":"0"});
		box.animate({"opacity":"1"}, 250);
		
	});
	
	$("div.report-box").bind("reset", function(event){
		$(this).slideUp(150);
	});
	
	/*
	$("a.unreport-abuse").live("click", function(event){
		event.preventDefault();
		event.stopPropagation();
		
		unreportAbuse($(this).closest("article.post").attr("data-key"));
		
	});
	*/
	
	
	
	// ----------------------------------------------------------------------
	// botão minimizar formularios search-people e search-charity
	minimize("div#search-people", 0);
	minimize("div#search-charity", 0);
	
	$("div#search-people").find("a.minimize").bind("click", function(event) {
		event.preventDefault();
		minimize("div#search-people");
	});
	
	$("div#search-charity").find("a.minimize").bind("click", function(event) {
		event.preventDefault();
		minimize("div#search-charity");
	});
	
	// botão maximizar formularios search-people e search-charity
	$("div#search-people").find("a.maximize").bind("click", function(event) {
		event.preventDefault();
		maximize("div#search-people");
	});
	
	$("div#search-charity").find("a.maximize").live("click", function(event) {
		event.preventDefault();
		maximize("div#search-charity");
	});
	
	
	
	// ----------------------------------------------------------------------
	// botão maximizar formularios criar nova mensagem
	$("div#new-message").bind("click", function(event) {
		$("div.post-box").slideDown('fast');
		$("div#new-message").css({"display":"none"});
	});
	
	$("input.cancelar").bind("click", function(event) {
		$("div.post-box").slideUp('fast');
		$("div#new-message").css({"display":"block"});
	});
	
	
	
	// ----------------------------------------------------------------------
	// campos de formulário com tratamento de máscara
	$("input#dtBirthday").bind("keydown", function(event) {
		formatField($(this), event, "date");
	});
	
	$("input#tsBegin").bind("keydown", function(event) {
		formatField($(this), event, "date");
	});
	
	$("input#tsEnd").bind("keydown", function(event) {
		formatField($(this), event, "date");
	});
	
	$("input#dtBegin").bind("keydown", function(event) {
		formatField($(this), event, "date");
	});
	
	$("input#dtEnd").bind("keydown", function(event) {
		formatField($(this), event, "date");
	});
	
	$("input#sPostalCode").bind("keydown", function(event) {
		formatField($(this), event, "cep");
	});
	
	
	
	// ----------------------------------------------------------------------
	// edição de imagem de avatar
	$("div#edit-avatar").fadeOut(0);
	$("div#edit-avatar").closest("div.photo-profile").bind("mouseenter", function(event){
		$("div#edit-avatar").stop(true,true).fadeIn(250);
	});
	
	$("div#edit-avatar").closest("div.photo-profile").bind("mouseleave", function(event){
		$("div#edit-avatar").stop(true,true).fadeOut(250);
	});
	
	
	
	// ----------------------------------------------------------------------
	// photo and album:
	$("button#create-photo").bind("click", function(event){
		
		event.preventDefault();
		$(this).slideUp("fast");
		$("form#share-photo-form").closest("div.post-box").slideDown("fast");
		
	});
	
	$("button#create-album").bind("click", function(event){
		
		event.preventDefault();
		$(this).slideUp("fast");
		$("form#create-album-form").closest("div.post-box").slideDown("fast");
		
	});
	
	// verifica se os campos do email são iguais
	$("form#form-config").find("input#sEmailConfirma").bind("keyup", function() {
	
		if($("form#form-config").find("input#sEmailNovo").val() != $("form#form-config").find("input#sEmailConfirma").val()) {
			$("input#sEmailConfirma").css({"border-top":"1px solid #840000", "border-left":"1px solid #840000", "border-bottom":"1px solid #E23434", "border-right":"1px solid #E23434", "background-color":"#ffdcdc"});
			$("input#sEmailConfirma").attr({"required":true});
		} else if($("form#form-config").find("input#sPasswordNovo").val() == $("form#form-config").find("input#sPasswordConfirma").val()) {
			$("input#sEmailConfirma").css({"border-top":"1px solid #D1D1D1", "border-left":"1px solid #D1D1D1", "border-bottom":"1px solid #E1E1E1", "border-right":"1px solid #E1E1E1", "background-color":"#F6F6F6"});
			$("input#sEmailConfirma").attr({"required":false});
		}
	});
	
	// verifica se os campos do password são iguais
	$("form#form-config").find("input#sPasswordConfirma").bind("keyup", function() {
	
		if($("form#form-config").find("input#sPasswordNovo").val() != $("form#form-config").find("input#sPasswordConfirma").val()) {
			$("input#sPasswordConfirma").css({"border-top":"1px solid #840000", "border-left":"1px solid #840000", "border-bottom":"1px solid #E23434", "border-right":"1px solid #E23434", "background-color":"#ffdcdc"});
			$("input#sPasswordConfirma").attr({"required":true});
		} else if($("form#form-config").find("input#sPasswordNovo").val() == $("form#form-config").find("input#sPasswordConfirma").val()) {
			$("input#sPasswordConfirma").css({"border-top":"1px solid #D1D1D1", "border-left":"1px solid #D1D1D1", "border-bottom":"1px solid #E1E1E1", "border-right":"1px solid #E1E1E1", "background-color":"#F6F6F6"});
			$("input#sPasswordConfirma").attr({"required":false});
		}
	});
	
	
	
	// ----------------------------------------------------------------------
	// eventos:
	$("button#create-event").bind("click", function(event) {
		event.preventDefault();
		$(this).slideUp("fast");
		$("form#create-event-form").closest("div.post-box").slideDown("fast");
	});
	
	$("button.edit-event").bind("click", function(event) {
		event.preventDefault();
		$("button.edit-event").slideUp(75);
		
		var idEvent = $(this).attr("data-key");
		
		// preenche o formulário com os dados atuais:
		$.ajax({
			type: 		"POST",
			url: 		"lib/socialAPI.php",
			dataType: 	"json",
			data: 		{
							"action" 	:"EventLoad",
							"idEvent" 	:idEvent
						},
			success: 	function(data) {
							
							$("form#edit-event-form").find("#sDisplayName")	 .val(data.sDisplayName);
							
							$("form#edit-event-form").find("#tsBegin")		 .val(showDate(data.properties.tsBegin));
							$("form#edit-event-form").find("#tsBeginTime")	 .val(showTime(data.properties.tsBegin));
							
							$("form#edit-event-form").find("#tsEnd")		 .val(showDate(data.properties.tsEnd));
							$("form#edit-event-form").find("#tsEndTime")	 .val(showTime(data.properties.tsEnd));
							
							$("form#edit-event-form").find("#sAddress")		 .val(data.properties.sAddress);
							$("form#edit-event-form").find("#sComplement")	 .val(data.properties.sComplement);
							$("form#edit-event-form").find("#idADMINCountry").val(data.properties.idADMINCountry);
							$("form#edit-event-form").find("#idADMINState")	 .val(data.properties.idADMINState);
							$("form#edit-event-form").find("#idADMINCity")	 .val(data.properties.idADMINCity);
							$("form#edit-event-form").find("#sNeighborhood") .val(data.properties.sNeighborhood);
							$("form#edit-event-form").find("#sPostalCode")	 .val(data.properties.sPostalCode);
							$("form#edit-event-form").find("#sSite")		 .val(data.properties.sSite);
							$("form#edit-event-form").find("#sDescription")	 .val(data.details.sContent);
							
							$("form#edit-event-form").find("#idReferredObject").val(idEvent);
							$("form#edit-event-form").closest("div.post-box").slideDown("fast");
							
						}
		});
		
	});
	
	$("form#create-event-form").find("input[type='reset']").bind("click", function(event) {
		$("form#create-event-form").closest("div.post-box").slideUp("fast");
		$("button#create-event").slideDown("fast");
	});
	
	$("form#edit-event-form").find("input[type='reset']").bind("click", function(event) {
		$("form#edit-event-form").closest("div.post-box").slideUp("fast");
		$("button.edit-event").slideDown("fast");
	});
	
	//reenviando a senha
	$("a#reenviarsenha").bind("click", function(event) {		
		event.preventDefault();
		requestPassword($("input#email").val());
	});	
	
	//definir a capa do album
	$("a#photocapa").bind("click", function(event) {		
		event.preventDefault();
		photoCapa($(this).attr("data-key"));
	});	
	
	
});

