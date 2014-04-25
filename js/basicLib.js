/* ------------------------------------------------------------------------------------------------------
 * basicLib.js
 * 
 * fun��es e comportamentos comuns ao framework bloop
 * @author 			Saboia Tecnologia da Informa��o <relacionamento@saboia.com.br>
 * @link			http://www.saboia.com.br
 * @version 		1.0
 * @dependencies	jQuery
 * ------------------------------------------------------------------------------------------------------
*/






// -------------------------------------------------------------------------------------------------
// m�todo:			gup()
// prop�sito:		use este m�todo para ler informa��es da URL, mimetizando o recebimento de par�metros via GET
// par�metros:		paramName:String	indica o par�metro a ser procurado na URL
// retorna:			String				valor do par�metro procurado
// afeta:			
// depend�ncias:	
// eventos:			
// exemplo:			gup("id");
// coment�rios:		
// -------------------------------------------------------------------------------------------------

this.gup = function(paramName) {

	paramName = paramName.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]"+paramName+"=([^&#]*)";
	var regex = new RegExp( regexS );
	var results = regex.exec( window.location.href );
	if( results == null )
		return "";
	else
		return results[1];
}





// -------------------------------------------------------------------------------------------------
// m�todo:			xmlToJson()
// prop�sito:		use este m�todo para transformar uma estrutura de XML em um objeto no formato JSON
// par�metros:		xml:XMLDocument		estrutura de dados XML a ser convertida para JSON
// retorna:			Object				estrutura de dados em formato JSON
// afeta:			
// depend�ncias:	
// eventos:			
// exemplo:			xmlToJson(xml);
// coment�rios:		baseado em: http://davidwalsh.name/convert-xml-json
// -------------------------------------------------------------------------------------------------

this.xmlToJson = function(xml) {
	
	var obj = {};

	if (xml.nodeType == 1) { // element
		// do attributes
		if (xml.attributes.length > 0) {
		obj["@attributes"] = {};
			for (var j = 0; j < xml.attributes.length; j++) {
				var attribute = xml.attributes.item(j);
				obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
			}
		}
	} else if (xml.nodeType == 3) { // text
		obj = xml.nodeValue;
	}

	// do children
	if (xml.hasChildNodes()) {
		for(var i = 0; i < xml.childNodes.length; i++) {
			var item = xml.childNodes.item(i);
			var nodeName = item.nodeName;
			if (typeof(obj[nodeName]) == "undefined") {
				obj[nodeName] = xmlToJson(item);
			} else {
				if (typeof(obj[nodeName].length) == "undefined") {
					var old = obj[nodeName];
					obj[nodeName] = [];
					obj[nodeName].push(old);
				}
				obj[nodeName].push(xmlToJson(item));
			}
		}
	}
	
	return obj;
	
	
	/*
	// op��o enviada por um segundo desenvolvedor
	// ( http://dl.dropbox.com/u/513327/xmlToJSON.html )
	var attr,
		child,
		attrs = xml.attributes,
		children = xml.childNodes,
		key = xml.nodeType,
		obj = {},
		i = -1;
		
	if (key == 1 && attrs.length) {
		obj[key = '@attributes'] = {};
		while (attr = attrs.item(++i)) {
			obj[key][attr.nodeName] = attr.nodeValue;
		}
		i = -1;
	} else if (key == 3) {
		obj = xml.nodeValue;
	}
	
	while (child = children.item(++i)) {
		key = child.nodeName;
		if (obj.hasOwnProperty(key)) {
			if (obj.toString.call(obj[key]) != '[object Array]') {
				obj[key] = [obj[key]];
			}
			obj[key].push(xmlToJson(child));
		}
		else {
			obj[key] = xmlToJson(child);
		}
	}
	return obj;
	*/
	
};





// -------------------------------------------------------------------------------------------------
// m�todo:			JSON.stringify()
// prop�sito:		use este m�todo para transformar um objeto no formato JSON em uma String
// par�metros:		obj:Object		estrutura de Objeto JSON a ser convertida para String
// retorna:			String			contendo a estrutura de dados em formato String
// afeta:			
// depend�ncias:	
// eventos:			
// exemplo:			JSON.stringify(data.recordset);
// coment�rios:		este m�todo � �til para ser usado em conjunto com o 'BloopSuperField'; ao carregar dados do servidor, 
//					o programador pode usar esta fun��o para transformar o objeto JSON resultante em uma String, e us�-la 
//					como par�metro 'data-x-values' do m�todo 'BloopSuperField.createSuperSelect()'
// -------------------------------------------------------------------------------------------------
/*
JSON.stringify2 = function (obj) {
	
    var t = typeof(obj);
    
	if (t != "object" || obj === null) {
        // simple data type
        if (t == "string") obj = '"'+obj+'"';
        return String(obj);
    }
    else {
        // recurse array or object
        var n, v, json = [], arr = (obj && obj.constructor == Array);
        for (n in obj) {
            v = obj[n]; t = typeof(v);
            if (t == "string") v = '"'+v+'"';
            else if (t == "object" && v !== null) v = JSON.stringify(v);
            json.push((arr ? "" : '"' + n + '":') + String(v));
        }
        return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
    }
	
}
*/




// -------------------------------------------------------------------------------------------------
// m�todo:			showDate()
// prop�sito:		use este m�todo para formatar a data para exibi��o na tela
// par�metros:		theDate:String			data no formato "YYYY-DD-MM"
//					showTimeStamp:Boolean	indica se ser� retornada a data com o timestamp
// retorna:			String					data no formato "DD/MM/YYYY"
// afeta:			
// depend�ncias:	
// eventos:			
// exemplo:			showDate("2011-20-06", true);
// coment�rios:		� necess�rio ainda criar m�todo para tratamento de TIMESTAMP, 
//					ou mesmo extrair neste m�todo o DATE de um TIMESTAMP passado como par�metro
// -------------------------------------------------------------------------------------------------

this.showDate = function(theDate, showTimeStamp) {
	
	var sqlDatePattern	 = /\b[0-9]{4}\-[0-9]{2}\-[0-9]{2}\b/g;
	var monthDatePattern = /\b[0-9]{4}\-[0-9]{2}\b/g;
	var timestampPattern = /\b[0-9]{4}\-[0-9]{2}\-[0-9]{2}\s[0-9]{2}\:[0-9]{2}\:[0-9]{2}\b/g;
	
	if (theDate) {
		
		if (timestampPattern.test(theDate) == true) {
			
			// verifica se � um timestamp no formato "YYYY-MM-DD HH:MM:SS"
			theDate = theDate.split("");
			newDate = theDate[8]+theDate[9] + "/" + theDate[5]+theDate[6] + "/" + theDate[0]+theDate[1]+theDate[2]+theDate[3];
			
			if (showTimeStamp && showTimeStamp == true) {
				
				newDate += " " + theDate[11]+theDate[12] +":"+ theDate[14]+theDate[15] +":"+ theDate[17]+theDate[18];
				
			}
			
			theDate = newDate;
			
		}
		
		else if (sqlDatePattern.test(theDate) == true) {
			
			// verifica se � uma data no formato "YYYY-MM-DD":
			theDate = theDate.split("-");
			theDate = theDate[2] + "/" + theDate[1] + "/" + theDate[0];
		}
		
		else if (monthDatePattern.test(theDate) == true) {
			
			// verifica se � uma data no formato "YYYY-MM":
			theDate = theDate.split("-");
			theDate = theDate[1] + "/" + theDate[0];
			
		}
		
		return theDate;
		
	} else {
		return "";
	}
}

this.showTime = function(theTimeStamp) {
	
	var timestampPattern = /\b[0-9]{4}\-[0-9]{2}\-[0-9]{2}\s[0-9]{2}\:[0-9]{2}\:[0-9]{2}\b/g;
	
	if (theTimeStamp) {
		
		if (timestampPattern.test(theTimeStamp) == true) {
			
			// verifica se � um timestamp no formato "YYYY-MM-DD HH:MM:SS"
			theTimeStamp = theTimeStamp.split("");
			
			// newTime = theTimeStamp[8]+theTimeStamp[9] + "/" + theTimeStamp[5]+theTimeStamp[6] + "/" + theTimeStamp[0]+theTimeStamp[1]+theTimeStamp[2]+theTimeStamp[3] + " " + theTimeStamp[11]+theTimeStamp[12] +":"+ theTimeStamp[14]+theTimeStamp[15] +":"+ theTimeStamp[17]+theTimeStamp[18];
			
			newTime = theTimeStamp[11]+theTimeStamp[12] +":"+ theTimeStamp[14]+theTimeStamp[15] +":"+ theTimeStamp[17]+theTimeStamp[18];
			
			theTimeStamp = newTime;
			
		}
		
		return newTime;
		
	} else {
		return "";
	}
}




// -------------------------------------------------------------------------------------------------
// m�todo:			sqlDate()
// prop�sito:		use este m�todo para formatar a data para o banco de dados
// par�metros:		theDate:String		data no formato "DD/MM/YYYY"
// retorna:			String				data no formato "YYYY-DD-MM"
// afeta:			
// depend�ncias:	
// eventos:			
// exemplo:			sqlDate("20/06/2011");
// coment�rios:		
// -------------------------------------------------------------------------------------------------

this.sqlDate = function(theDate) {
	
	var prbrDatePattern	 = /\b[0-9]{2}\/[0-9]{2}\/[0-9]{4}\b/g;
	var monthDatePattern = /\b[0-9]{2}\/[0-9]{4}\b/g;
	
	if (theDate) {
		
		if (prbrDatePattern.test(theDate) == true) {
			
			// verifica se � uma data no formato "DD/MM/YYYY"
			
			theDate = theDate.split("/");
			theDate = theDate[2] + "-" + theDate[1] + "-" + theDate[0];
		
		} else if (monthDatePattern.test(theDate) == true) {
			
			// verifica se � uma data no formato "MM/YYYY"
			
			theDate = theDate.split("/");
			theDate = theDate[1] + "-" + theDate[0] + "-01";
			
		}
		
		return theDate;
		
	} else {
		return "";
	}
}





// -------------------------------------------------------------------------------------------------
// Fun��o:		formatField(field, event, format)
// Objetivo:	m�scara de input do campo 
// Exemplo:		formatField(htmlElement, event, "date");
// Retorno:		
// -------------------------------------------------------------------------------------------------

this.formatField = function(field, event, format) {
	
	var key = event.keyCode;
	
	// n�o s�o tratadas pela fun��o:
	// teclas delete, tab, shift, backspace
	// teclas left arrow, up arrow, right arrow, down arrow
	// teclas ctrl, alt, shift
	if (
	key!=8  && key!=9  && key!=16 && key!=46 &&
	key!=37 && key!=38 && key!=39 && key!=40 &&
	key!=16 && key!=17 && key!=18
	) {
		
		switch (format) {
			
			case "date" :
				// impede input de valores n�o num�ricos no campo:
				// valores de key entre 48 - 57 (keyboard) ou 96 - 105 (numpad keyboard)
				if (!( (key >= 48 && key <= 57) || (key >= 96 && key <= 105) )) {
					// field.val( field.val().substring(0,field.val().length-1) );
					event.preventDefault();
				}
				
				// formata input:
				if ($(field).val().length == 2 || $(field).val().length == 5) {
					$(field).val( field.val() + "/" );
				}
			break;
			
			case "month" :
				// impede input de valores n�o num�ricos no campo:
				// valores de key entre 48 - 57 (keyboard) ou 96 - 105 (numpad keyboard)
				if (!( (key >= 48 && key <= 57) || (key >= 96 && key <= 105) )) {
					// field.val( field.val().substring(0,field.val().length-1) );
					event.preventDefault();
				}
				
				// formata input:
				if ($(field).val().length == 2) {
					$(field).val( field.val() + "/" );
				}
			break;
			
			
			/*
			case "money" :
				// impede input de valores n�o num�ricos no campo:
				// valores de key entre 48 - 57 (keyboard) ou 96 - 105 (numpad keyboard)
				if (!( (key >= 48 && key <= 57) || (key >= 96 && key <= 105) )) {
					event.preventDefault();
				}
				
				// formata input:
				var moneyValue 		= "";
				var stringValue 	= "";
				var tempValue 		= "";
				var formattedValue  = "";
				
				moneyValue  = field.val();
				stringValue = moneyValue.replace(".","");
				stringValue = stringValue.replace(",","");
				
				// stringValue agora cont�m apenas os n�meros, sem formata��o
				arValue = stringValue.split("");
				
				for (i=(arValue.length - 1); i>=0; i--) {
					
					formattedValue = arValue[i] + formattedValue;
					
					if (formattedValue.length == 2) {
						formattedValue = "," + formattedValue;
					}
				}
				
				field.val(formattedValue);
				
			break;
			*/
			
			
			case "cpf" :
				// impede input de valores n�o num�ricos no campo:
				// valores de key entre 48 - 57 (keyboard) ou 96 - 105 (numpad keyboard)
				if (!( (key >= 48 && key <= 57) || (key >= 96 && key <= 105) )) {
					// field.val( field.val().substring(0,field.val().length-1) );
					event.preventDefault();
				}
				
				// formata input:
				if ($(field).val().length == 3 || $(field).val().length == 7) {
					$(field).val( field.val() + "." );
					
				} else if ($(field).val().length == 11) {
					$(field).val( field.val() + "-" );
				}
				
			break;
			
			case "cnpj" :
				// impede input de valores n�o num�ricos no campo:
				// valores de key entre 48 - 57 (keyboard) ou 96 - 105 (numpad keyboard)
				if (!( (key >= 48 && key <= 57) || (key >= 96 && key <= 105) )) {
					// field.val( field.val().substring(0,field.val().length-1) );
					event.preventDefault();
				}
				
				// formata input:
				if ($(field).val().length == 2 || $(field).val().length == 6) {
					$(field).val( field.val() + "." );
					
				} else if ($(field).val().length == 10) {
					$(field).val( field.val() + "/" );
					
				} else if ($(field).val().length == 15) {
					$(field).val( field.val() + "-" );
				}
				
			break;
			
			case "phone" :
				// impede input de valores n�o num�ricos no campo:
				// valores de key entre 48 - 57 (keyboard) ou 96 - 105 (numpad keyboard)
				if ( !( (key >= 48 && key <= 57) || (key >= 96 && key <= 105) ) ) {
					// field.val( field.val().substring(0,field.val().length-1) );
					event.preventDefault();
				}
				
				// formata input:
				if ($(field).val().length == 1) {
					$(field).val( "+" + field.val() );
					
				} else if ($(field).val().length == 3) {
					$(field).val( field.val() + " (" );
					
				} else if ($(field).val().length == 7) {
					$(field).val( field.val() + ") " );
					
				} else if ($(field).val().length == 13) {
					$(field).val( field.val() + "-" );
				}
				
			break;
			
			case "cep" :
				// impede input de valores n�o num�ricos no campo:
				// valores de key entre 48 - 57 (keyboard) ou 96 - 105 (numpad keyboard)
				if (!( (key >= 48 && key <= 57) || (key >= 96 && key <= 105) )) {
					// field.val( field.val().substring(0,field.val().length-1) );
					event.preventDefault();
				}
				
				// formata input:
				if ($(field).val().length == 5) {
					$(field).val( field.val() + "-" );
				}
				
			break;
			
		}
		
	}
	
}

this.formatMoney = function(field, event) {
	
	var key = event.keyCode;
	
	// n�o s�o tratadas pela fun��o:
	// teclas delete, tab, shift, backspace
	// teclas left arrow, up arrow, right arrow, down arrow
	// teclas ctrl, alt, shift
	if (
	key!=8  && key!=9  && key!=16 && key!=46 &&
	key!=37 && key!=38 && key!=39 && key!=40 &&
	key!=16 && key!=17 && key!=18
	) {
		
		if (event.type == "keydown") {
			
			// impede input de valores n�o num�ricos no campo:
			// valores de key entre 48 - 57 (keyboard) ou 96 - 105 (numpad keyboard)
			if (!( (key >= 48 && key <= 57) || (key >= 96 && key <= 105) )) {
				event.preventDefault();
			}
		
		} else if (event.type == "keyup") {
			
			// formata input:
			var moneyValue 		= "";
			var stringValue 	= "";
			var formattedValue  = "";
			var count			= 0;
			
			moneyValue  = field.val();
			stringValue = moneyValue.replace(/\./g,"");
			stringValue = stringValue.replace(/\,/g,"");
			
			stringValue = parseInt(stringValue,10);
			stringValue = stringValue.toString();
			
			// stringValue agora cont�m apenas os n�meros, sem formata��o
			arValue = stringValue.split("");
			
			for (i=(arValue.length-1); i>=0; i--) {
				
				formattedValue = arValue[i] + formattedValue;
				
				if (formattedValue.length == 2) {
					formattedValue = "," + formattedValue;
				}
				
				if (formattedValue.length > 3) {
					count++;
					if (count == 3) {
						count = 0;
						formattedValue = "." + formattedValue;
					}
				}
			}
			
			// corrige v�rgulas e pontos no come�o do valor:
			if (formattedValue.indexOf(".") == 0) { formattedValue = formattedValue.slice(1); }
			if (formattedValue.indexOf(",") == 0) { formattedValue = "0" + formattedValue; }
			
			field.val(formattedValue);
			
		}
	}
}



// -------------------------------------------------------------------------------------------------
// Fun��o:		moneyToFloat(value)
// Objetivo:	transforma uma String no formato '12.350,25' (money) para '12350.25' (float)
// Exemplo:		moneyToFloat("12.350,25");
// Retorno:		Float
// -------------------------------------------------------------------------------------------------

this.moneyToFloat = function(value) {
	
	if (value === "") {
		value = 0;
		
	} else {
		value = value.replace(".","");
		value = value.replace(",",".");
		value = parseFloat(value);
	}
	
	return value;
}



// -------------------------------------------------------------------------------------------------
// Fun��o:		floatToMoney(value)
// Objetivo:	transforma uma String no formato '12350.25' (float) para '12.350,25' (money)
// Exemplo:		floatToMoney(12350.25);
// Retorno:		Float
// -------------------------------------------------------------------------------------------------

this.floatToMoney = function(value) {
	
	var integer = null;
	var decimal = null;
	var c = null;
	var j = null;
	
	var aux = new Array();
	
	value = value.toString();
	
	// caso haja pontos na string, separa as partes em inteiro e decimal:
	c = value.indexOf(".",0);
	
	if (c > 0) {
		integer = value.substring(0, c);
		decimal = value.substring(c+1, value.length);
	} else {
		integer = value;
	}
	
	
	// pega a parte inteiro de 3 em 3 partes
	for (j = integer.length, c = 0; j > 0; j-=3, c++) {
		aux[c]=integer.substring(j-3, j);
	}
	
	// percorre a string acrescentando os pontos
	integer = "";
	for (c = aux.length-1; c >= 0; c--) {
		integer += aux[c] + ".";
	}
	// retirando o ultimo ponto e finalizando a parte inteiro
	
	integer = integer.substring(0, integer.length-1);
	
	decimal = parseInt(decimal);
	
	if(isNaN(decimal)) {
		decimal = "00";
	} else {
		decimal = decimal.toString();
		if (decimal.length === 1) {
			decimal = decimal + "0";
		}
	}
	
	value = integer + "," + decimal;
	
	return value;
}





// -------------------------------------------------------------------------------------------------
// m�todo:			generateValidPassword()
// prop�sito:		use este m�todo para gerar uma senha v�lida para o sistema
// par�metros:		
// retorna:			String		senha gerada aleatoriamente dentro dos grupos de caracteres v�lidos
// afeta:			
// depend�ncias:	
// eventos:			
// exemplo:			generateValidPassword();
// coment�rios:		
// -------------------------------------------------------------------------------------------------

this.generateValidPassword = function() {
	
	var strNovaSenha;
	
	var arConsoantes;
	var arVogais;
	var arNumeros;
	var arConsoantesMaiusculas;
	var arVogaisMaiusculas;
	var arSimbolos;
	
	var i;
	var intNumber;
	
	arConsoantes			= ("b,c,d,f,g,h,j,k,l,m,n,p,q,r,s,t,v,w,x,y,z,b,c,d,f,g,h,j,k,l,m,n,p,q,r,s,t,v,w,x,y,z").split(",");
	arVogais				= ("a,e,i,o,u").split(",");
	arNumeros				= ("0,1,2,3,4,5,6,7,8,9").split(",");
	arConsoantesMaiusculas	= ("B,C,D,F,G,H,J,K,L,M,N,P,Q,R,S,T,V,W,X,Y,Z,B,C,D,F,G,H,J,K,L,M,N,P,Q,R,S,T,V,W,X,Y,Z").split(",");
	arVogaisMaiusculas		= ("A,E,I,O,U").split(",");
	arSimbolos				= ("!,@,#,$,%,&,*,?,-").split(",");
	
	strNovaSenha = "";
	
	while (strNovaSenha.length < 10) {
		
		i=0;
		
		while (strNovaSenha.length < 2) {
			
			intNumber = Math.floor(Math.random() * arConsoantes.length);
			strNovaSenha += arConsoantes[intNumber];
			
			i++;
			if (i == 10) break;
		}
		
		while (strNovaSenha.length < 4) {
		
			intNumber = Math.floor(Math.random() * arVogais.length);
			strNovaSenha += arVogais[intNumber];
			
			i++;
			if (i == 10) break;
		}
		
		while (strNovaSenha.length < 6) {
		
			intNumber = Math.floor(Math.random() * arNumeros.length);
			strNovaSenha += arNumeros[intNumber];
			
			i++;
			if (i == 10) break;
		}
		
		while (strNovaSenha.length < 8) {
		
			intNumber = Math.floor(Math.random() * arConsoantesMaiusculas.length);
			strNovaSenha += arConsoantesMaiusculas[intNumber];
			
			i++;
			if (i == 10) break;
		}
		
		while (strNovaSenha.length < 9) {
		
			intNumber = Math.floor(Math.random() * arSimbolos.length);
			strNovaSenha += arSimbolos[intNumber];
			
			i++;
			if (i == 10) break;
		}
		
		while (strNovaSenha.length < 11) {
		
			intNumber = Math.floor(Math.random() * arVogaisMaiusculas.length);
			strNovaSenha += arVogaisMaiusculas[intNumber];
			
			i++;
			if (i == 10) break;
		}
		
	}
	
	return strNovaSenha;
	
}



// -------------------------------------------------------------------------------------------------
// m�todo:			validatePassword()
// prop�sito:		use este m�todo para checar se uma senha � v�lida para o sistema
// par�metros:		strPassword:String	senha a ser checada
// retorna:			Boolean				
// afeta:			
// depend�ncias:	
// eventos:			
// exemplo:			validatePassword("rosebud");
// coment�rios:		
// -------------------------------------------------------------------------------------------------

this.validatePassword = function(strPassword) {
	
	// matrizes com conjuntos v�lidos
	var	strCaracteres;

	// vari�veis utilizadas para fazer a contagem m�nima da senha
	// quanto maior a contagem mais segura a senha.
	var intPontoConsoante;
	var intPontoVogal;
	var intPontoNumero;
	var intPontoConsoantesMaiuscula;
	var intPontoVogalMaiuscula;
	var intPontoSimbolo;

	var i;
	var j;
	var intError;
	var intTotalPontos;

	var intPosicao;
	var strLetraSenha;

	var intTamanhoDaSenha = 6;			// define a quantidade m�nima de caracteres da senha
	var intQuantidadeDePontos = 3;		// define a quantidade m�nima de requesitos obrigat�rios

	intPontoConsoante = 0;
	intPontoVogal = 0;
	intPontoNumero = 0;
	intPontoConsoantesMaiuscula = 0;
	intPontoVogalMaiuscula = 0;
	intPontoSimbolo = 0;
	intError = 0;

	// montando a lista com os caracteres v�lidos
	strCaracteres = "aeiouAEIOUbcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ1234567890!@#$%&*?-";

	// a senha deve ter um n�mero m�nimo de caracteres:
	if (strPassword.length < intTamanhoDaSenha) {
		
		intError = 1;
		
	} else {
		
		for (i=0;i<=strPassword.length;i++) {
			
			// pegando a letra da senha
			strLetraSenha = strPassword.substr(i,1);
			
			// recuperado a posi��o da letra no conjunto de caracteres
			intPosicao = strCaracteres.indexOf(strLetraSenha,0);
			
			
			// o usu�rio est� tentando utilizar um caracter que est� fora do conjunto
			if (intPosicao == -1) {
				intError = 2;
				break;
			}
			
			
			// vogais min�sculas
			if (intPosicao < 5) {
				intPontoVogal = 1;
			// vogais mai�sculas
			} else if (intPosicao < 10) {
				intPontoVogalMaiuscula = 1;
			// consoantes min�sculas
			} else if (intPosicao < 31) {
				intPontoConsoante = 1;
			// consoantes mai�sculas
			} else if (intPosicao < 52) {
				intPontoConsoantesMaiuscula = 1;
			// n�meros
			} else if (intPosicao < 62) {
				intPontoNumero = 1;
			// s�mbolos
			} else {
				intPontoSimbolo = 1;
			}
			
		}
		
	}

	// se n�o tem erro, conta os pontos...
	if (intError == 0) {
	
		intTotalPontos = intPontoVogal + intPontoVogalMaiuscula + intPontoConsoante + intPontoConsoantesMaiuscula + intPontoNumero + intPontoSimbolo;

		if (intTotalPontos < intQuantidadeDePontos) {
			intError = 3;
		}
		
	}
	
	return intError == 0;

}



function uniqid() {
	var newDate = new Date;
	return newDate.getTime();
}

function identifyLink(sText) {
	
	if (sText && sText != "") {
		
		var pattern = /((http:\/\/)|(www.))([^\s]*)/;
		var strReturn = '';
		
		strReturn = sText.replace(pattern, '<a href="'+"$&"+'" title="" target="_blank">'+"$&"+'</a>');
		
		return strReturn;
		
	} else {
	
		return sText;
	}
}
