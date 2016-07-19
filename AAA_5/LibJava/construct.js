
/*
*   construct.js:   Funciones para generar la condicion "WHERE" basado en los datos ingresados en un formulario.
*                   Requiere el script validations.js
*   Autor:          Fausto Astudillo
*   Fecha:          Sep/15/04
*   USO: gaTipos y gatexto deben completarse con los datos correspondientes a cada campo, indexados por su nombre
*/
var gaError  = false;
var gaString = Array();	// 		Contiene los datos requeridos para el query
var gaTipos= new Array(); // 	Array de tipos de datos
var gaTexto= new Array(); //	Array de textos conversacionales de cada campo
var fields = ""; //				Lista general de controles (INPUT / SELECT)

fInicializaQuery();
var hidden_fields = null;
var ignore_fields = null;

/**
*	Inicializa contenido de variables de consulta
*
*/
function fInicializaQuery() {
	gaString['qry'] = '';
	gaString['txt'] = '';
	gaString['ext'] = '';
	gaString['err'] = 0;
}
/*
*   Estructura basica de un campo
*   @acces      public
*   @param      string  pType   Tipo de DAto: N=Numerico, C=Caracter, F=Fecha
*   @param      string  pText   Texto para presentar como cabecera del campo
*   @param      string  pField  Nombre de campo en la base de datos
*/
function oCampo(pType, pText, pField, pName ) {
	this.type=pType;
	this.text=pText;
	this.field = (pField) ? pField : false;
	this.name= (pName) ? pName : this.field;
}

/**
*   genera un query en funcion de datos de un formulario
*   @access  public
*   @param  string  $pValor     Contenido
*   @param  object  $pCampo		Onbjeto Campo
*   @param  string  $pVAltxt 	valor textual para referencia, en español plano
*   @param  string  pTipo    	Tipode cadena a generar: 1=Campos indeondientes, caso contrario una sola cadena
*   @return void
*/
function fConstruct(pValor, pCampo, pValtxt, pTipo)
{
    if (!pTipo) pTipo=false;
	var retVal = Array();
	if (!isBlank(pValor)) {
		pTexto = ((pCampo.text) ? pCampo.text : pCampo.field) ;
		if (!pCampo.fied) pCampo.field = pCampo.name;
		if (!pValtxt) pValtxt = pValor;
		slConcat = pTipo ? "&" : " AND "; //                        Concatenador de condiciones
        gaString['qry'] += gaString['qry'].length ? slConcat : '';
		gaString['txt'] += gaString['txt'].length ? ' Y ' : ' ';
		if (pCampo.Type == "C") slOperador = " like ";   //                                      @TODO Soportar varios operadores logicos: >, <, !=, between, etc
		else slOperador = "=";
		if (pTipo) slOperador = "=";
		if (pValor.indexOf('%')>0 ) slOperador = " like ";
        re = /^[<>!]/;
        if (re.test(pValor)) {  //                      Si contiene los simbolos de mayor/menor que o negacion
            slOperador = String(pValor).substr(0,1);    // Separa el operador logico
            if (slOperador == "!") slOperador = "<>";
            slOperador2 = String(pValor).substr(0,2);
            pValor=String(pValor).substr(1);            // Toma el resto del cotenido del campo
            if (slOperador2 == ">=") {
                slOperador = ">=";
                pValor=String(pValor).substr(2);
            }
            if (slOperador2 == "<=") {
                slOperador = "<=";
                pValor=String(pValor).substr(2);
            }
        }
        else {
            j = String(pValor).search(":");
            if (j>0) {
                pVal1=String(pValor).substr(0,j);
                pVal2=String(pValor).substr(j+1);
                slOperador = " BETWEEN " ;
                gaString['txt'] = pCampo.field + " ENTRE "   + fToSql(pVal1, pCampo.type) + " Y "   + fToSql(pVal2, pCampo.type);
                gaString['qry']+= pCampo.field + " BETWEEN " + fToSql(pVal1, pCampo.type) + " AND " + fToSql(pVal2, pCampo.type);
                gaString['ext'] += "&" + pCampo.field + '_MIN==' + fToSql(pVal1, pCampo.type);  // definir el parametro extra
                return true;
            }
        }
    	gaString['qry'] += pCampo.field + slOperador + fToSql(pValor, pCampo.type);
		if (gaError) gaString['txt'] += " ERROR EN " + pValtxt;
		gaString['txt'] += pTexto + ":" + fToSql(pValtxt, pCampo.type);
		if (slOperador == '=' ) gaString['ext'] += "&" + pCampo.field + '_MIN=<' + fToSql(pValor, pCampo.type);  // definir el parametro extra
		if (slOperador == '>' ) gaString['ext'] += "&" + pCampo.field + '_MIN=<=' + fToSql(pValor, pCampo.type);  // definir el parametro
		if (slOperador == '>=' ) {
            if (pCampo.type == 'N') gaString['ext'] += "&" + pCampo.field + '_MIN=<' + fToSql(pValor, pCampo.type);  // definir el parametro extra para campos numericos
            if (pCampo.type == 'F') gaString['ext'] += "&" + pCampo.field + '_MIN=<' + fToSql(pValor, pCampo.type);  // definir el parametro extra para campos de fecha
        }
		return true;
	}
}
function fToSql(pdata, ptype) {
    gaError= false;
    switch (ptype) {
			case 'N':
				return pdata;
				break;
			case "F":
			case 'C':
				return "'" + pdata + "'"
				break;
			default:
			    gaError= true;
				return "**" + pdata + "**";
	}
}
/*
*	Rastreo de los componentes del formulario, para determinar quienes cambiaron, generar el arreglo de modificados
*/
function preScan(theform,hidden_fields,ignore_fields){
	if(hidden_fields==null){hidden_fields="";}
	if(ignore_fields==null){ignore_fields="";}
	var hiddenFields=new Object();
	var ignoreFields=new Object();
	var i,field;
	var hidden_fields_array=hidden_fields.split(',');
	for(i=0;i<hidden_fields_array.length;i++){hiddenFields[Trim(hidden_fields_array[i])]=true;}
	var ignore_fields_array=ignore_fields.split(',');
	for(i=0;i<ignore_fields_array.length;i++){ignoreFields[Trim(ignore_fields_array[i])]=true;}
	gaModificados.length = 0;
    gaNumModif=0;
    mm=false;
    nn=false;
	if(document.getElementsByTagName('INPUT')) {
       var elements=document.getElementsByTagName('INPUT');
	   if (elements.length > 0) mm = scan(elements, hiddenFields, ignoreFields);
	}
    if ( document.getElementsByTagName('SELECT')) {
       elements=document.getElementsByTagName('SELECT');
	   if (elements.length > 0) nn = scan(elements, hiddenFields, ignoreFields);
    }
	elements=null;
 	if (mm || nn) return true;
	else return false;
}

/*
*   Análisis del contenido de los campos modificados y generacion de la sentencia WHERE para Mysql.
*   @param  string  pTipo   Tipo de query a obtener: 1= Condiciones independientes para c/campo, caso contrario una sola condicion anidad con "Y"
*/
function fGeneraQry(pTipo)
{
	fInicializaQuery();
	if (!preScan(document.forms[0], hidden_fields, ignore_fields)) return true;
	gaString['err'] = 0;
    if (getFromurl("jsDbg")) window.status += "GQ ";	
    if (gaModificados.length > 0) {
	    for (i=0; i<gaModificados.length ;i++) {
    if (getFromurl("jsDbg")) window.status += " " + i;
	        switch (gaModificados[i].tagName) {
				case 'SELECT':
				case 'select':
					slValue = gaModificados[i].value;
					ilindice= gaModificados[i].selectedIndex
					slText  = gaModificados[i].options[ilindice].text;
					break;
				case 'INPUT':
				case 'input':
					slValue = gaModificados[i].value;
					slText  = gaModificados[i].text;
					break;
				default:
					slValue = gaModificados[i].value;
					slText  = gaModificados[i].text;
	        }
	        slFieldName=gaModificados[i].name;
//		if (fExisteCampo(slFieldName)) {
		if (slFieldName.indexOf('txt_') <0 ) {
		   gaCampos[slFieldName].name = gaCampos[slFieldName].name ? gaCampos[slFieldName].name : slFieldName;
            	   fConstruct(slValue, gaCampos[slFieldName], slText, pTipo )
  		   }
	    }
	}
//	alert ("STR " + gaString['qry'] );
//	alert ("TEXT " + gaString['txt'] );
    if ((gaString['err'] * 1) == 0) return true;
 	return false;
}
function fExisteCampo(pNomb){
	for (jj=0; jj<gaCampos.length; jj++){
		if (gaCampos[jj].name == pNomb) return true;
		if (jj==1000) return false;
	    }
	return false
}
