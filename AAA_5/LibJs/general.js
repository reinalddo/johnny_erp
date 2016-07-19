/**
*   Funciones de Apoyo en JavaScript. de uso general
*   @access     public
*   @author     fausto astudillo
*   @created    12/Abr/03
**/
/**
    *       Function para determinar el numero de fila de un componente repetitivo en ccs
    *       basado en el sufijo del nombre
    *       @access   public
    *       @param    sl        Sufijo del nombre del objeto
    *       @return   integer   Número de fila que corresponde al objeto analizado
    */
function  fTraefila(sl) {
	var slnomb = String(sl);
	if (slnomb.length > 0 ){
		k=slnomb.substr(slnomb.lastIndexOf("_"))
	}
	var ilFila= parseInt((k.substr(1)));
    return ilFila;
}
    /**
    *      Convierte un texto numerico formateado, en numero segun el tipo especificado
    *      @access   public
    *      @param    pTipo    Tipo de Conversion:  I= Entero,     F= Flotante
    *      @return   el valor numerico correspondiente o (NaN)
    *
    */
function fNum(pTipo, pDato)
{
    if (pDato.lenght < 1) p.dato = "0";
    switch (pTipo) {
           case "I":
                return isNaN(parseInt(String(pDato).replace(",", "")))?0:parseInt(String(pDato).replace(",", ""));
           case "F":
                return isNaN(parseFloat(String(pDato).replace(",", "")))?0:parseFloat(String(pDato).replace(",", ""));
    }
}
    /**
    *      Activa seleccion de un campo en cuanto ingresa a el, para facilitar digitacion / modificacion
    *      @access   public
    *      @param    pForm      Nombre del Formulario
    *      @param    pObjeto    Nombre del componente HTML a seleccionar
    *      @return   void
    *
    */
function fSelecc(pForm, pObjeto)
{
    eval("document." + pForm + "." + pObjeto + ".select()");
}
    /**
    *      Abrir una ventana segun parametros enviados
    *      @access   public
    *      @param    pUrl       String: Url de pagina a crear
    *      @param    pWin       String: Nombre de ventana, si viene vacio, se asigna un nombre aleatorio
    *      @param    pOpc       String: Opciones de apertura
    *      @param    pAnch      Integer: Ancho, en pixeles
    *      @param    pLarg      Integer: Largo, en pixels
    *      @return   void
    */
function fAbrirWin(pUrl, pWin, pOpc, pAnch, pLarg)
{
    sWin    = new String(pWin);
    sWin    = pWin ? sWin : 'Win' + Math.round(Math.random() * 10);                   // Si no viene el param, nombre aleatorio
    sOpcDef = 'toolbar=no,menubar=no,scrollbars=yes,resize=yes,alwaysRaised=1,dependant=1'                            // Opciones Default
    //pOpc    = pOpc ? sOpcDef + ',' + pOpc : sOpcDef  //
    pOpc    = sOpcDef  //
    pAnch   = pAnch ? pAnch : 1200;
    pLarg   = pLarg ? pLarg : 300;
    pOpc   +=  ', ' + 'width='  + pAnch + ', height=' + pLarg;
    pOpc   += ',' + fCentrarWin(pAnch, pLarg);
//  alert(sWin + " = "  + pUrl + "    " + pOpc + "  /   " + String(pWin).lenght);
    Win     = window.open(pUrl, sWin, pOpc);
//    eval(pWin+".focus();");
    Win.focus();
}
    /**
    *      Abrir una ventana segun parametros enviados, COMO UN OBJETO.
    *      @access   public
    *      @param    pUrl       String: Url de pagina a crear
    *      @param    oWin       Objeto: Referencia del Objeto a devolver;
    *      @param    pWin       String: Nombre de ventana, si viene vacio, se asigna un nombre aleatorio
    *      @param    pOpc       String: Opciones de apertura
    *      @param    pAnch      Integer: Ancho, en pixeles
    *      @param    pLarg      Integer: Largo, en pixels
    *      @return   void
    */
function fAbrirObjWin(pUrl, oWin, pWin, pOpc, pAnch, pLarg)
{
    sWin    = new String(pWin);
    sWin    = sWin.lenght > 1 ? sWin : 'Win' + Math.round(Math.random() * 10);                             // Si no viene el param, nombre aleatorio
    sOpcDef = 'status=yes,titlebar=no,toolbar=no,resize=yes,menubar=no,scrollbars=yes, alwaysRaised=1,dependant'                            // Opciones Default
    pOpc    = pOpc.lenght > 1 ? sOpcDef + ',' + pOpc : sOpcDef  //
    pAnch   =  (pAnch > 0 ? pAnch : 600 ) ;
    pLarg   =  (pLarg > 0 ? pLarg : 300);
    pOpc   +=  ', ' + 'width='  + pAnch + ', height=' + pLarg;
    pOpc   += ',' + fCentrarWin(pAnch, pLarg);
//  alert(pWin + " = "  + pUrl + "    " + pOpc + "  /   " + String(pWin).lenght);
    oWin    = window.open(pUrl, sWin, pOpc);
//    eval(pWin+".focus();");
    oWin.focus();
}


    /**
    *      Define un string para ubicar una ventana en el centro de la pantalla, dependiendo del browser
    *      @access   public
    *      @param    pAnch      Integer: Ancho, en pixeles
    *      @param    pLarg      Integer: Largo, en pixels
    *      @return   string
    *
    */
function fCentrarWin(pAnch, pLarg) {
        if (isNS4) {
         // Center on the main window.
         illeft = window.screenX + ((window.outerWidth - pAnch) / 2)
         iltop  = window.screenY + ((window.outerHeight - pLarg) / 2)
         return    'screenX=' + illeft + ',screenY=' + iltop
      } else {
         // The best we can do is center in screen.
         illeft = (screen.width - pAnch) / 2
         iltop = (screen.height - pLarg) / 2
         return     "left=" + illeft + ",top=" + iltop
      }
}

/**
*   Devuelve el numero de filas, asociado como el array resultante de una tabla HTML
*   @access  public
*   @param  object  Objeto a examinar su posicion
*   @return  int    Nùmero de filas qu componen la tabla
*/
function fNumFilas(obj) {
    switch (obj.tagName) {
        case  'TR':
            ilFilas = obj.parentNode.rows.length;              // si elevento se lanza desde la fila
            break;
        case  'TR':
            ilFilas = obj.parentNode.parentNode.rows.length;   // si el evento se lanza desde la celda
            break;
        case  'INPUT':
        case  'SELECT':
        default:
            ilFilas = obj.parentNode.parentNode.parentNode.rows.length;
            break;
    }
    return ilFilas;
}
/**
*   Devuelve el numero de filadel Onjeto actual
*   @descr   rowofset   debe contener el numero de filas extra (exceptuando la primera) que contiene la cabecera de latabla
*                       ejm: si tiene una fila de cabecera = 0, dos fila = 1, tres filas = 2, etc.
*   @access  public
*   @param  object  Objeto a examinar su posicion
*   @param  int     rowoffset Desplazamiento respecto de la primera fila que nos interesa (para descartar headers)
*   @return int     Indice que posiciona el objeto dentro del arreglo (tabla)
*/
function fEstaFila(obj, rowOffset) {
    if (!rowOffset) rowOffset = 0;
    switch (obj.tagName) {
        case  'TR':
            ilFila = obj.rowIndex-rowOffset;              // si elevento se lanza desde la fila
            break;
        case  'TR':
            ilFila = obj.parentNode.rowIndex-rowOffset;   // si el evento se lanza desde la celda
            break;
        case  'INPUT':
        case  'SELECT':
        default:
            ilFila = obj.parentNode.parentNode.rowIndex-rowOffset;
            break;
    }
    return ilFila;
}
/**
*   Hace movimiento vertical en la tabla de datos
*   @access  public
*   @param  object  e evento
*   @return void
*/
function fKeyUp(e)
{
// alert(e);
	if (!e) var e = window.event; // e gives access to the event in all browsers
	var tg = (e.target) ? e.target : e.srcElement
	var kc = (e.wich) ? e.wich : e.keyCode
	ilFilas = fNumFilas(tg);
	ilFila = fEstaFila(tg);
	slcampo = tg.name.substr(0, tg.name.lastIndexOf("_"))
	if (!(kc == 38) && !(kc == 40)) return;
	if (kc == 38)  ilDesplaza = ilFila -1;
	if (kc == 40)  ilDesplaza = ilFila + 1;
	if (ilDesplaza <= 1) ilDesplaza = 1;
	if (ilDesplaza >= ilFilas) ilDesplaza = ilFilas-1;
	slDestino=slcampo + "_" + ilDesplaza;
// window.status = "tecla presionada :" + kc + " / " + tg.name + "  // " + slcampo + "  // " + slcampo +"_"+ ilDesplaza ;
	if (kc == 38 || kc == 40 ) {
/*	
        tg.form.elements[slDestino].focus();
//        if (tg.form.elements.elements[slDestino].tagName.toUpperCase() != "SELECT") tg.form..elements[slDestino].select();
*/
       document.forms.liqtarjadetal.elements[slcampo + "_" + ilDesplaza].focus()
	   document.forms.liqtarjadetal.elements[slcampo + "_" + ilDesplaza].select()
	}
}
/**
*   Devuelve un valor enviado en la Url de pa pagina en ejecucion
*   @access  public
*   @param  string    name   Nombre de la variable a obtener
*   @param  string    def    Valor por defecto
*   @return valor de la variable ó falso si no existe
*/
function getFromurl(Name, Def) {
   if (arguments.length < 2) Def=false;
   var search = Name + "="
   var surl   = new String(document.URL);

   if (surl.length > 0) { 	// if there are any Url
      offset = surl.indexOf(search)
      if (offset != -1) { 			// if var exists
         offset += search.length
         end = surl.indexOf("&", offset) // set index of beginning of value
         if (end == -1) // set index of end of cookie value
            end = surl.length;
         return unescape(surl.substring(offset, end))
         }
   }
   return Def;
}
/**
*   Hace movimiento vertical en la tabla de datos, en campo de cantDespachada
*   @access  public
*   @param  object  e evento
*   @param  object  f Default field to focus when a line is empty
*   @return void
*/
function fKeyUp_Secuencia(e, f)
{
// alert(e);
//alert (e.keyCode)
	if (!e) var e = window.event; // e gives access to the event in all browsers
	if (!e) var e = window.Event; // e gives access to the event in all browsers	
	var tg = (e.target) ? e.target : e.srcElement
	var kc = (e.wich) ? e.wich : e.keyCode
	slcampo = tg.name.substr(0, tg.name.lastIndexOf("_"));
    if(!f) f=slcampo                   // if default field not defined, focus to same field in next line ( if empty)
// alert(tg.form.name);
    if (kc != 38 && kc != 40) return;
//	with (document.forms.InTrTr_detalle) {
    with (tg.form) {
		ilFilas = fNumFilas(tg);
		ilPrimFila = fEstaFila(elements[slcampo + "_1"]) ;	
//		ilPrimFila = fEstaFila(elements["det_Secuencia_1"]) ;
//		ilUltiFila = fEstaFila(elements["det_Secuencia_5"]) ;
		ilFilas = 30;
		ilFila = fEstaFila(tg) - ilPrimFila +1 ;
//		slcampo = "det_CodItem" ;
		if (kc == 38)  ilDesplaza = ilFila -1;
		if (kc == 40)  ilDesplaza = ilFila + 1;
		if (ilDesplaza <= 1) {
			ilDesplaza = 1;
		}
		if (ilDesplaza >  ilFilas) {
			ilDesplaza = ilFilas;
			return;
		}
        if (!elements[slcampo + "_" + ilDesplaza])
            return;
        if (!elements[f + "_" + ilDesplaza].value) slcampo = f ; // en fila vacia, va al primer campo editable
//	 window.status = "tecla presionada :" + kc + " / " + tg.name + "  // " + slcampo + "  // " + slcampo +"_"+ ilDesplaza + "  ===" + elements[f + "_" + ilDesplaza].value;
		if (kc == 38 || kc == 40) {
//		    window.status = slcampo + "_" + ilDesplaza;
            try {
    		    if (elements[slcampo + "_" + ilDesplaza]) {
        			elements[slcampo + "_" + ilDesplaza].focus()
    	       		if (elements[slcampo + "_" + ilDesplaza].tagName.toUpperCase() != "SELECT") elements[slcampo + "_" + ilDesplaza].select();
    			}
			} catch (e) {
			}
		}
	}
}
/**
*   Cambia el color de fondo de un afila
*   @ref    Navega por todos los elemendos descendentes y aplica el cambio
*   @access  public
*   @param  object  pobj    Objeto al que se aplica
*   @param  syring  pcolor  Color que se aplicara
*   @return void
*/
function fCambiarFondo(pobj, pcolor) {
    pobj.style.backgroundColor=pcolor
    if (pobj.hasChildNodes) {
	    ilHijos = pobj.childNodes.length;
	    for (i=0; i<ilHijos-1; i++) {
	        lobjHijos= pobj.childNodes;
	        	lobjHijo= pobj.childNodes[i]; // Ojo: FF no usa "children"
		        if (lobjHijo.nodeType == 1) {
			        lobjHijo.style.backgroundColor=pcolor;
			        ilNietos=lobjHijo.childNodes.length ;
			        for (j=0; j<(ilNietos); j++) {
			            lobjNieto = lobjHijo.childNodes[j];
			            if (lobjNieto.style) lobjNieto.style.backgroundColor=pcolor;
			        }
		        }
	    }
    }

}
/**
*   Enfoca un elemento "INput" una tabla, fila y columna especificada
*   @access  public
*   @param  object  pElement Elemento en el que se encuantra la tabla (form, frame, etc)
*   @param  string  pTabla   ID de la tabla
*   @param  integer pNFila   Numero de fila
*   @param  integer pNCol    Numero de Columna
*   @return void
*/
function fEnfocaFila(pElem, pTabla, pNFila, pNCol)  {
    /*
    if (window.document.forms.length == 0) {
        window.status = "Nota: No se enfoca el elemento " + pElem;
        return true;
    }
*/
//    if (String(pForm).length > 0 )
    if (!pNCol) pNCol=0;
    olTabla = document.getElementById(pTabla);
    olFila=olTabla.getElementsByTagName("TBODY")[0].childNodes[pNFila];
    olCol=olFila.getElementsByTagName("TD")[pNCol];
    obj = fExtrae(olCol, "INPUT", pElem);
//    alert(olTabla + " / " + olFila.rowIndex + obj.name );
    obj.focus();
}
/**
*   Enfoca un elemento dentro de un formulario
*   @access  public
*   @param  object  pElem    Nombre del elemento a enfocar
*   @param  string  pForm    Formulario en el que se encuentra el obj.
*   @param  integer pNFila   Numero de fila
*   @param  integer pNCol    Numero de Columna
*   @return void
*/
function fEnfoca(pElem, pForm)  {
    /*
    if (window.document.forms.length == 0) {
        window.status = "Nota: No se enfoca el elemento " + pElem;
        return true;
    }
*/
//    if (String(pForm).length > 0 )
      if (pForm===undefined) {
        ilNum=window.document.forms.length -1;
        window.status=ilNum;
        window.document.forms[ilNum][pElem].focus();
//        window.status+=" / " + pForm
        }
    else
        window.document.forms[pForm][pElem].focus();
}
/**
*   Extrae un objeto especifico de un objeto contenedor
*   @access  public
*   @param  object  pObj     Objeto contenedor
*   @param  string  pTipo    'tagName' del objeto contenido
*   @param  integer pNombre  Nombre del Objeto a extraer
*   @return objeto contenido, o falso si no existe
*/
function fExtrae(pObj, pTipo, pNombre) {
    aElem = pObj.getElementsByTagName(pTipo);
    k=aElem.length;
    for (i=0; i<k; i++) {
        if (aElem[i].name == pNombre) {
            return aElem[i];
        }
    }
    return false;
}
/**
*   Devuelve un valor numerico redondeao
*   @param  valor   float       Valor a redondear
*   @param  precision   int     Número de posiciones decimales
*   @access public
*   @return flotante redondeado a 'precision' posiciones decimales
**/
function fredondear(valor, precision)
{
        valor = "" + valor //convierte valor a string
        precision = parseInt(precision);
        var total = "" + Math.round(valor * Math.pow(10, precision));
        var puntoDec = total.length - precision;
        if (precision > total.length) return valor;
        if(puntoDec != 0)  {
                resultado = total.substring(0, puntoDec);
                resultado += ".";
                resultado += total.substring(puntoDec, total.length);
        }
        else {
                resultado = 0;
                resultado += ".";
                resultado += total.substring(puntoDec, total.length);
        }
        return resultado;
}
/*
*	Llama una pantalla para solicitar parametros de ejecucion de un Script
*	@param	pUrl	String		Direccion del archivo a ejecutar
*	@param	pTipo	Integer		Tipo de Query a generar: 1=condiciones individuales para cada campo,
*							    caso contrario un solo string conteniendo todas las condiciones
*/
function fPidePerio(pUrl, pTitul, pTipo, pTxtI, pTxtF, pNom1, pNom2, pExtra, pApli, pEsta){
	if (pUrl.length < 3 ) {
		alert("NO SE HA DEFINIDO EL PROCESO A EJECUTAR");
		return false;
		}
	pTitul= (pTitul)? '&pTitul=' +pTitul : 'INGRESE LOS PARAMETROS';
    pTxtI= (pTxtI)? '&pTxtI=' +pTxtI : '';	
	pTxtF= (pTxtF)? '&pTxtF=' +pTxtF : '';
    pNom1= (pNom1)? '&pNom1=' +pNom1 : '';	
    pNom2= (pNom2)? '&pNom2=' +pNom2 : '';	
	pApli = (pApli) ? '&pApli=' +pApli : '';
	sEsta = (pEsta)? String('&pMinE='+ pEsta): '&pMinE=1'; // Asumir un estado minimo
	aEsta = sEsta.split(':');
	if (aEsta.length > 1) sEsta='&pMinE=' + $aEsta[0] +'&pMaxE=' + $aEsta[1] ; // definir Estado Minimo y Maximo a Utilizar en Seleccion
    pExtra= (pExtra)? '&pRepParam=' + pExtra : '';
    pWin=String(pUrl).replace(".","");	
    pWin=String(pUrl).replace(".","");
    pWin=String(pUrl).replace(".","");	
    pWin=pWin.replace("/","");
    pWin=pWin.replace(".","");    pWin=pWin.replace(".","");    pWin=pWin.replace(".","");
    pWin=pWin.replace("&","");
    pWin=pWin.replace("=","");
    pWin=pWin.replace("/","");
    pWin=pWin.substr(12,99);
	slUrl = '../Ge_Files/GeGeGe_selfech.php?';
	if(!(String(pUrl).indexOf('?'))) pUrl += '?';
    slParametros= "&pTitul=" + pTitul +  pTxtI + pTxtF + pNom1 + pNom2 + "&pLarg=600&pAnch=900&pTipo=" +pTipo + sEsta + '&pUrl=' + pUrl+  pExtra;
	fAbrirWin(slUrl +  slParametros, pWin, ' ', 480, 190)
	return false;
}
/**
    Trae la referencia a un elemnto HTML contenenido en un nodo cualquiera
    
    @param  oNodeBase   Nodo contenedor
    @param  sId         Id / Nombre del objeto a obtener
    @param  sNom  bool  Bandera para indicar que la busqueda es por nombre, no por ID
    @return Node Object Nodo buscado, que tenga ID pedida en el nodo contendor referenciado, si no existe, retorna false
**/
function fGetElement(oNode, sId, sNom){
    if (oNode.hasChildNodes) {
       for (idx=0; idx < oNode.childNodes.length; idx++) {             // >
            var oNodeHijo = oNode.childNodes[idx]
//            if (oNodeHijo.hasChildNodes ) {
            if (oNodeHijo.childNodes.length > 0 ) {
                for (idxh=0; idxh < oNodeHijo.childNodes.length; idxh++) {             // >
                    if (!sNom) { if (oNodeHijo.childNodes[idxh].id == sId) return oNodeHijo.childNodes[idxh]; }
                    else { if (oNodeHijo.childNodes[idxh].name == sId) return oNodeHijo.childNodes[idxh]; }
                }
            } else {
                if (!sNom)  if (oNodeHijo.id == sId) return oNodeHijo;
                else        if (oNodeHijo.name == sId) return oNodeHijo;
            }
        }
        return false;
    }
    else  return false;
}
/**
    Trae la referencia a un elemnto HTML contenenido en un nodo cualquiera aplicando un proceso recursivo

    @param  oNodeBase   Nodo contenedor
    @param  sId         Id / Nombre del objeto a obtener
    @param  sNom  bool  Bandera para indicar que la busqueda es por nombre, no por ID
    @return Node Object Nodo buscado, que tenga ID pedida en el nodo contendor referenciado, si no existe, retorna false
**/
function fGetElementRec(oNode, sId, sNom){
    if (!sNom)  if (oNode.id == sId) return oNode;
    else        if (oNode.name == sId) return oNode;
    if (oNode.childNodes.length > 0) {
       for (var idx=0; idx < oNode.childNodes.length ; idx++) {             // >
            var oNodeHijo = fGetElementRec(oNode.childNodes[idx], sId, sNom)
            if (oNodeHijo != false ) return oNodeHijo;
    	}
    	return false;
    }
    else    return false;
}
/**
    Devuelve la fila proxima a la actual, hacia  atras o adelante
    
    @param  oOrigen     Nodo Origen (Fila actual)
    @param  iSalto      numero de Elementos a 'Saltar', positivo haci aa delante, negativo atras
**/
function fAvanzaRow(oNode, iSalto){
    if (iSalto < 0) iFin = iSalto* (-1);
    else iFin = iSalto;
    iTipo = oNode.nodeType;
    iAv = 0;
    oHerm = oNode;
    do {
        do {
            if (iSalto > 0) {
                if (oHerm.nextSibling) oHerm = oHerm.nextSibling
                else { oHerm = false;
                     break;}
                }
            else {if (oHerm.previousSibling) oHerm = oHerm.previousSibling
                 else {oHerm = false; break;}
                 }
            if (!oHerm) break;
        } while (oHerm.nodeType != iTipo)
        iAv +=1
    } while (iAv < iSalto);
    if (oHerm.nodeType != iTipo) return false;
    return oHerm;
}
/**
    Unica el foco en el elemento ctrlId contenido en el nodo iSalto posiciones hacia atras o adelante
    
    @param  oRow        object      Fila actual
    @param  iSalto      integer     Posiciones que debe saltar hacia atras (<0) o adelante (>0)
    \param  ctrlId      string      ID del objeto a enfocar dentro del contenedor objetivo
**/
function fEnfocaPxmo(oRow, iSalto, ctrlId) {
    oNRow = fAvanzaRow(oRow, iSalto);
    if (oNRow) {
        oCtrl2 = fGetElement(oNRow, ctrlId);
        if(oCtrl2) oCtrl2.focus();
    }
}

gTeclaProcesada=false;
/**
    Maneja las respuestas ante eventos de teclado con telas de cursor. util para navegar entre filas de un grid
**/
function fManejarTecla(e, row) {
    if (gTeclaProcesada) return; // No procesar si la tecla ya paso por el manejador del elemento
    var oEvent = e || window.event;
    if(oEvent.modifiers) {   // moz browsers
        this.ctrlKeyPressed = (oEvent.modifiers & oEvent.CONTROL_MASK) != 0;
        this.altKeyPressed = (oEvent.modifiers & oEvent.ALT_MASK) != 0;
    } else {                // ie
        this.ctrlKeyPressed = oEvent.ctrlKey;
        this.altKeyPressed = oEvent.altKey;
    }
    window.status= "";
    slType ="";
    oCtrl = oEvent.target || oEvent.srcElement;
    if (this.ctrlKeyPressed) { slType="Ctrl"; window.status = "Ctrl " + oEvent.keyCode};
    if (this.altKeyPressed) { slType="Alt"; window.status = "Alt " + oEvent.keyCode};
    switch (oEvent.keyCode) {
        case 38: // UP Arrow
            if (this.ctrlKeyPressed || this.altKeyPressed) break;
            fEnfocaPxmo(row, -1, oCtrl.id);
            break;
        case 40: // DOWN Arrow
            if (this.ctrlKeyPressed || this.altKeyPressed) break;
            fEnfocaPxmo(row, 1, oCtrl.id);
            break;
        default:
            break;
    }
}
/**
*   Convertir un numero formateado con separador decimal, en un numerico
*/
function fParseDec(pNum){
        var num1   = 0.00;
        exp=/,/g;
        num1 = parseFloat(String(pNum).replace(exp,""));
        if (!isNaN(num1)) {
                return num1;
        }
        else return 0;

}
/**
	Evitar  bubbling de un evento
**/
function fDetenerEvento(e){
	e.cancelBubble = true;
	if (e.stopPropagation) e.stopPropagation();
	if ( e.preventDefault != undefined ) e.preventDefault();
    else e.returnValue = false;
}

function fRoundNumber(number,decimals) {
	var newString;// The new rounded number
	decimals = Number(decimals);
	if (decimals < 1) {
		newString = (Math.round(number)).toString();
	} else {
		var numString = number.toString();
		if (numString.lastIndexOf(".") == -1) {// If there is no decimal point
			numString += ".";// give it one at the end
		}
		var cutoff = numString.lastIndexOf(".") + decimals;// The point at which to truncate the number
		var d1 = Number(numString.substring(cutoff,cutoff+1));// The value of the last decimal place that we'll end up with
		var d2 = Number(numString.substring(cutoff+1,cutoff+2));// The next decimal, after the last one we want
		if (d2 >= 5) {// Do we need to round up at all? If not, the string will just be truncated
			if (d1 == 9 && cutoff > 0) {// If the last digit is 9, find a new cutoff point
				while (cutoff > 0 && (d1 == 9 || isNaN(d1))) {
					if (d1 != ".") {
						cutoff -= 1;
						d1 = Number(numString.substring(cutoff,cutoff+1));
					} else {
						cutoff -= 1;
					}
				}
			}
			d1 += 1;
		} 
		newString = numString.substring(0,cutoff) + d1.toString();
	}
	if (newString.lastIndexOf(".") == -1) {// Do this again, to the new string
		newString += ".";
	}
	var decs = (newString.substring(newString.lastIndexOf(".")+1)).length;
	for(var i=0;i<decimals-decs;i++) newString += "0";
	//var newNumber = Number(newString);// make it a number if you like
	return  newString; // Output the result to the form field (change for your purposes)
}

