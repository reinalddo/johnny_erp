/*
 * AutoSuggestController based on Nicholas C.Zackas code
 *  @package AutoSuggestController
 *  @rev 1.01   fausto astudillo: funcionalidad completa de typeahead
 */
/**
*   Objeto Manejador de valores sugeridos
*   @param  otextBox    object  textbox para el que se aplica
*   @param  oprovider   object  Objeto Ajax encargado de generar los datos desde el servidor
*   @param  pWidth      integer Ancho del select box en px, si se omite es igual al ancho del textbox.
*   @param  pAuto       boll    Indica si es autoexpandible
*   @param  pStatic     integer Indica si es contenido estatico
*   @param  pUri        string  Direccion de la pagina a ejecutar
*   @return void
**/

function AutoSuggestController(oTextBox, oProvider, pWidth, pAuto, pStatic, pUri) {
    this.width_ = ((!pWidth) ? parseInt(oTextBox.style.width) : pWidth ) ;
    if(!pAuto) pAuto = false;
    if (!pStatic) pStatic = false;
    if (!pUri) pUri="";
    this.provider = oProvider;
    this.textbox = oTextBox;
    this.uri= pUri;
    this.expanded=false;        // estado de operacion, si es true= abierto
    this.layer = null;
    this.selectedIndex = -1; // fah OCt/28/05
    this.staticData = pStatic;    // indica si el contenido es dinamico o estatico.
    this.autoExpand = pAuto;           // indica si se despliega el contenido en cuanto recibe enfoque
    this.typedText = "";
    this.typedCount=0;          // Nuemro de caracteres tipeados (letras simbolos, numeros)
    this.data = [];
    this.columns = []; // Columnas adicionales de datos traidos del server
    this.contenedor= null;  // referencia del objeto contenedor de los elementos de salida
    this.output = [];  // output fields para las columnas
    this.patternSearch = true;  // busqueda por patrones
    this.taStyle = true;  // Estilo 'TypeAhead'
    this.nextField =false; // Siguiente campo a enfocar cuando salga
    this.notInList =false; // Aceptar enradas quen no esten en lista
    this.divFocus = false
    this.minLength = 9;          // Cantidad minima de letras para activarse.
    this.altKeyPressed = false;  // Indica si se ha presionado la tecla Alt
    this.ctrlKeyPressed = false; // Indica si se ha presionado la tecla ctrl
	this.ctrlDownKeyPressed = false;
    this.ctrlKHandler = [];      // Array con loas manejadores de Tecla ctrl
    this.required=false;         // inidcador de que el dato es obligatorio
    if (this.textbox)
        this.init();             // Ejecuta inicializacion solosi se recibe un objeto válido
}


var _p = AutoSuggestController.prototype;


_p.init = function () {
    var oThis = this;
    this.typedCount=0;
    this.textboxInfo = this.getElementInfo(this.textbox);
    this.textbox.onkeyup = function (e) { oThis.handleKeyUp(e); }
    this.textbox.onkeydown = function (e) { oThis.handleKeyDown(e); }
    this.textbox.onblur = function (e) {
//        oThis.textbox.style.border = "1px solid " + oThis.textbox.style.backgroundColor;
//        oThis.textbox.style.border = "";
        oThis.textbox.style.borderColor = "";
        if(oThis.typedCount == 0) return;
/*                                   -------------------- @TODO Analizar este cambiosiconviene o no
		if(oThis.typedCount == 0) return true;
*/        if (!oThis.divFocus) {
            oThis.hideSuggestions();
            oJ=document.getElementById("divDropDown");
            oJ=null;
            oThis.layer.outerHTML="";
            oThis.typedCount == 0;
//          oThis.textbox.style.backgroundColor = "transparent";
			gTeclaProcesada= false;
        }
        oThis.divFocus = false;
        oThis.displayOutput();
    }
    this.createDropDown();
    if (this.typedText.length < 1 && this.textbox.value.length > 0) this.typedText = this.textbox.value;
    if (!this.staticData || (this.staticData && !this.data.length)|| this.autoExpand) {   //
             if (this.uri && (this.typedCount > 0 || this.autoExpand))  this.provider.requestSuggestions(this, true);  //
       }
    if (this.autoExpand ) {   // Despliegue automatico  // bug 001
        this.autoSuggest(this.data, false);
    }
}
/**

**/
_p.displayOutput = function () {
    oThis = this;
    if (!oThis.expanded) return;
    for (j=1; j< oThis.output.length; j++){  // OJO::!!! LAs columnas inician en 1
        oParNode = oThis.textbox.parentNode;
        if (!(oThis.columns[j]==undefined)) {
//          oNode = fGetElement(oThis.textbox.parentNode, oThis.output[j]);
//          oNode = fGetElement(oThis.contenedor, oThis.output[j]);
            oNode = fGetElementRec(oThis.contenedor, oThis.output[j]);
            if (this.selectedIndex >=0)
                oNode.value = (oThis.columns[j][oThis.selectedIndex] != undefined) ? oThis.columns[j][oThis.selectedIndex]:"";
            else
                oNode.value ="";
        }
    }
    if (this.selectedIndex >=0 && this.data) this.textbox.value = this.data[this.selectedIndex];
    else if (!this.notInList) this.textbox.value ="";
}
_p.clearOutput = function () {
    oThis = this;
    for (j=1; j< oThis.output.length; j++){  // OJO::!!! LAs columnas inician en 1
            oNode = fGetElementRec(oThis.contenedor, oThis.output[j]);
            oNode.value ="";
    }
}
_p.selectRange = function (iStart, iLength) {
    if (this.textbox.createTextRange) {
        var oRange = this.textbox.createTextRange();
        oRange.moveStart("character", iStart);
        oRange.moveEnd("character", iLength - this.textbox.value.length);
        oRange.select();
    } else if (this.textbox.setSelectionRange)
        this.textbox.setSelectionRange(iStart, iLength);

    this.textbox.focus();
}


_p.typeAhead = function (sSuggestion) {
    if (this.textbox.createTextRange || this.textbox.setSelectionRange) {
        var iLength = this.textbox.value.length;
        this.textbox.value = sSuggestion;
        this.selectRange(iLength, sSuggestion.length);
    }
}

_p.autoSuggest = function (aSuggestions, bTypeAhead) {
    try {aSuggestions.length}
        catch(e) {
            this.hideSuggestions();
            return;
        };                                                      // **fah Oct/22/05
    if (aSuggestions.length > 0) {
        this.data = aSuggestions;                               // **fah Oct/22/05
//        if (bTypeAhead)
            
        if (this.searchSuggestion(this.textbox.value)>=0)   {
            if (this.taStyle)  this.typeAhead(aSuggestions[this.selectedIndex]);
            this.showSuggestions(aSuggestions);
        }
        else this.hideSuggestions();
    } else this.hideSuggestions();
    this.selectedIndex = this.getCurrentFocused()
}

_p.hideSuggestions = function () { this.layer.style.visibility = "hidden"; }

/**
    Returns the position of first ocurrence of a typed text in the suggestions array
    if there is no matching, returns -1
    @by    fah Oct/22/05
    @param text string      thest to search
**/
_p.searchSuggestion = function (text) {
    this.selectedIndex = -1;
    if (!this.data) return -1;
    if (this.patternSearch ) {
        text=String(text).toLowerCase().replace( "%", ".*", "g");  // sustituye % por .
        text=String(text).toLowerCase().replace( "*", ".*", "g");  // sustituye % por .
    }
    window.status=text;
    if (this.typedCount < 1 && this.autoExpand && String(this.textbox.value).length < 1 ){
      	this.selectedIndex = 0  ;
		return 0;
    }
    for (var i = 0; i < this.data.length; i++) {
        if (this.patternSearch) {       // busqueda por patron
            if( String(this.data[i]).toLowerCase().search(text) >=0 ) {
            	this.selectedIndex = i  ;
				return i;
            }
        }
        else {                          // busqueda textual
            if (String(this.data[i]).toLowerCase().indexOf(text.toLowerCase()) == 0) { // debe iniciar con 'texto'
                this.selectedIndex = i  ;
                return i;
            }
        }
    }
    return -1;
}

_p.highlightSuggestion = function (oSuggestionNode) {
    for (var i = 0; i < this.layer.childNodes.length; i++) {
        var oNode = this.layer.childNodes[i];
        if (oNode == oSuggestionNode) {
            oNode.className = "current";
        }
        else if (oNode.className = "current")
            oNode.className = "";
    }
    this.highlightText(oSuggestionNode.innerHTML);
}

_p.highlightText = function (oSuggestionText) { // fah Oct/22/'5
    this.selectedIndex = -1;
    for (var i = 0; i < this.layer.childNodes.length; i++) {
        var oNode = this.layer.childNodes[i];
      if (oNode.className = "current") oNode.className = "";
      slText=String(oNode.innerHTML);
//      window.status=slText;
      if (slText.indexOf(oSuggestionText) == 0 ) { // Si el nodo comienza con el texto buscado
            this.selectedIndex = i  //  **fah Oct/22/05
            oNode.className = "current";
            return;
        }
    }
}

_p.createDropDown = function () {
    if (document.getElementById("divDropDown"))
        document.body.removeChild(document.getElementById("divDropDown"));
    this.layer =  document.createElement("DIV");
    this.layer.id = "divDropDown";
    this.layer.className = "suggestions";
    this.layer.style.visibility = "hidden";
    this.layer.style.position = "absolute";
    this.layer.style.zIndex="0";
    this.layer.style.backgroundColor = this.textbox.style.backgroundColor;
    this.layer.style.align = "left";
    this.layer.style.text_align = "left";
    this.layer.style.left = (this.textboxInfo.left - 2) + "px";
    this.layer.style.top = (this.textboxInfo.top + this.textboxInfo.height - 1) + "px";
    window.status += "   /////   " + this.layer.style.left + " / " + this.layer.style.top;
    if (!parseInt(this.width_)  || this.width_ < 10 )    this.layer.style.width = this.textboxInfo.width + 20 - ((/msie/i.test(navigator.userAgent)) ? 2 : 0) + "px";
    else this.layer.style.width = this.width_;
    document.body.appendChild(this.layer);
    var oThis = this;
    this.layer.onfocus= function(){
		oThis.divFocus=true;    // fah
	}
    this.layer.onblur= function() {
        oThis.divFocus=false;    // fah
		oThis.textbox.focus();};
//	this.layer.onmouseover =
    this.layer.onmousedown = this.layer.onmouseup = function (e) {
        var oEvent = e || window.event;
        var oTarget = oEvent.target || oEvent.srcElement;
        if (oTarget.id == "divDetail") { //   solo se aplica si el click se hace  en los detalles
            switch (oEvent.type) {
                case  "mousedown" :
                    oThis.textbox.value = oTarget.firstChild.nodeValue;
                    oThis.typedText = oThis.textbox.value;
                    oThis.divFocus=false;    // fah
                    gTeclaProcesada = false;
                    oThis.highlightSuggestion(oTarget);
                    oThis.textbox.focus();  // fah
                    oThis.hideSuggestions();
                    oThis.displayOutput();
                    break;
                case "mouseover":
                    oThis.divFocus=true;    // fah
                    gTeclaProcesada = false;
//                    oThis.textbox.value = oTarget.firstChild.nodeValue;
//                    oThis.highlightSuggestion(oTarget);
//                    oThis.textbox.focus();  // fah
//                    oThis.displayOutput();
                    break;
                default:
                    oThis.divFocus=true;    // fah
                    break
            }
        } else {
                oThis.divFocus=true;    // fah
//                oThis.textbox.focus();
            }
        } //  oThis.divFocus=true;    // fah
    }
//}


_p.showSuggestions = function (aSuggestions) {
    var oDiv = null;
    this.layer.innerHTML = "";
    this.layer.style.height = "150px";
    for (var i = 0; i < aSuggestions.length; i++) {
        oDiv = document.createElement("DIV");
        oDiv.id="divDetail";
        oDiv.style.textAlign="left";
        oDiv.appendChild(document.createTextNode(aSuggestions[i]));
        oDiv.style.fontSize="10px";
        this.layer.appendChild(oDiv);
    }
    this.layer.style.left = (this.textboxInfo.left - 2) + "px";
    this.layer.style.top = (this.textboxInfo.top + this.textboxInfo.height - 1) + "px";
    this.layer.style.visibility = "visible";
    this.highlightText(this.textbox.value); // fah Oct/22/05
    this.expanded = true; //                    Estado de expandido
//    window.status="sel: " + this.selectedIndex;
}


_p.nextSuggestion = function () {
    var cSuggestionNodes = this.layer.childNodes;
    var currentNode = this.getCurrentFocused();
    this.selectedIndex = currentNode;  // fah Oct/22/05
    if (cSuggestionNodes.length > 0 && currentNode < cSuggestionNodes.length -1 ) {
        var oNode = cSuggestionNodes[currentNode + 1];
        this.highlightSuggestion(oNode);
//faa        this.textbox.value = oNode.firstChild.nodeValue;
//faa        this.typedText = this.textbox.value
//        this.selectedIndex -= 1;
    }
}


_p.previousSuggestion = function () {
    var cSuggestionNodes = this.layer.childNodes;
    var currentNode = this.getCurrentFocused();
    this.selectedIndex = currentNode;  // fah Oct/22/05
    if (cSuggestionNodes.length > 0 && currentNode > 0) {
        var oNode = cSuggestionNodes[currentNode - 1];
        this.highlightSuggestion(oNode);
//faa        this.textbox.value = oNode.firstChild.nodeValue;
//faa        this.typedText = this.textbox.value
//        this.selectedIndex -= 1;
    }
}


_p.getCurrentFocused = function () {
    var cSuggestionNodes = this.layer.childNodes;
    this.selectedIndex = -1;  // fah Oct/22/05
    if (cSuggestionNodes.length > 0) {
        for (var i = 0; i < cSuggestionNodes.length; i++) {
            if (cSuggestionNodes[i].className == "current") {
                this.selectedIndex = i; // **fah
                return i;
            }
        }
    }
    return -1;
}

_p.getElementInfo = function (el) {
    if (el.getBoundingClientRect) {
        var o = el.getBoundingClientRect();
        this.top = o.top;
        this.left = o.left;
        this.width = (o.right - o.left);
        this.height = (o.bottom - o.top);
    } else if (document.getBoxObjectFor) {
        var o = document.getBoxObjectFor(el);
        this.top = o.y;
        this.left = o.x;
        this.width = o.width;
        this.height = o.height;
    }
    window.status = o.top + " // " + o.left;
    return this;
}


_p.handleKeyUp = function (e) {
    if (this.typedCount == 0) return true;
    var oEvent = e || window.event;
    var code = oEvent.keyCode;
    blForced = false;
    if (this.typedCount == 0 && (code == 38 || code == 40)) return true;
//    if (code == 8 || code == 46) this.textbox.value = this.textbox.value.substr(0, this.textbox.value.length -1);
    if (code == 8 || code == 46) { // actualizar el texto tipeado cuando se borra una letra
        this.typedText = this.typedText.substr(0, this.typedText.length -1);
    }
   else  {
            this.typedText = this.textbox.value;
/*            if (this.textbox.value.length > this.typedText.length)
            this.typedText += this.textbox.value.substr(this.textbox.value.length -1, this.textbox.value.length );
*/
    }

    this.textbox.value = this.typedText;
    if (this.altKeyPressed ) return;
    if (this.ctrlKeyPressed){
        if(code == 40) blForced = true; // forzar la reconstruccion de la lista
        else return;
    }
//    if (this.ctrlKeyPressed  && (code != 40) ) return;
//alert("nn");
    if ((this.textbox.value.length >= this.minLength) || blForced || this.staticData) {
       if (!(code < 32 || (code >= 33 && code <= 46) ||
            (code >= 112 && code <= 123)) ||
            ((code ==40  && this.ctrlKeyPressed )|| code == 8 || code == 46)) // delete o backspace redibujar ???
          {
          if (!this.staticData || blForced ||  (this.staticData && !this.data)) {   // si es contenido semi-dinámico ó hay una peticion forzoza de recargar datos
             if (this.uri && (this.typedCount > 0 || blForced || this.autoExpand))  this.provider.requestSuggestions(this, true);  // si l contenido se obtiene del servidor
          }
          this.autoSuggest(this.data, false);
       }
    }
    if (code == 13  ) { // Al presionar ENTER Enfocar elcampo necesario
        if (this.expanded) fDetenerEvento(oEvent);
		this.focusNextField();
    }
	gTeclaProcesada= false;
    if (code != 9 ) return; // si nos es un TAB ó ENTER, evitar que se aplique 'HotKeys'
}
_p.focusNextField = function(){
		this.hideSuggestions();
        this.displayOutput();
        if (this.nextField) {
			var oNode = fGetElementRec(this.contenedor, this.nextField) ;
			oNode.focus();
        }
        else {              // Enfocar el primer campo visible, delos que han salido
            for (var i=0; i<this.output.lenght; i++){
				var oNode = fGetElementRec(this.contenedor, this.output[i]) ;
				if (oNode.style.visibility != "hidden") {
					oNode.focus();
					return;
				}
            }
        }
}
_p.handleKeyDown = function (e) {
//    if (!this.expanded) return true;
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
    gTeclaProcesada=true;
    if (this.ctrlKeyPressed) { slType="Ctrl"; window.status = "Ctrl " + oEvent.keyCode};
    if (this.altKeyPressed) { slType="Alt"; window.status = "Alt " + oEvent.keyCode};
    slCaract="";
    this.ctrlDownKeyPressed = false;
    switch (oEvent.keyCode) {
        case 17: // Ctrl Key
            break;
        case 18: // Alt Key
            break;
        case 36: // Home Key
            break;
        case 38: // UP Arrow
            if (this.ctrlKeyPressed || this.altKeyPressed) break;
//            if (this.data && this.data.length > 0 && this.selectedIndex >0 && this.typedCount > 0) this.previousSuggestion();
            if (this.data && this.data.length > 0 && this.selectedIndex >0 && this.expanded ) this.previousSuggestion();
            else gTeclaProcesada=false;
            break;
        
        case 40: // DOWN Arrow
            if (this.ctrlKeyPressed || this.altKeyPressed){
                this.ctrlDownKeyPressed = (this.ctrlKeyPressed) ? true : false;
				break;
            }
//			if (this.data && this.data.length > 0 && this.typedCount > 0) this.nextSuggestion();
            if (this.data && this.data.length > 0 && this.expanded) this.nextSuggestion();
            else { gTeclaProcesada=false;
                if (this.ctrlKeyPressed) window.status += " nnn" ;
				fDetenerEvento(oEvent);
            }
            break;

        case 13: // ENTER
            if (this.expanded) fDetenerEvento(oEvent);
            if(this.layer.style.visibility != "hidden") {
                gTeclaProcesada=true; // si esta deplegado eldiv auxiliar, enter selecciona,
                window.status="e1 ";
                return false;
            }
            else {                    // sino, operasobre elformulario
                window.status="e2";
                gTeclaProcesada=false;
            }
            return false;
            break;
        case 8: //                  Backspace, no continuar con el evento en niveles superiores
                gTeclaProcesada=true;
                this.typedCount +=1;
                return false;
       case 9: //                  Tab, no continuar con el evento en niveles superiores
                gTeclaProcesada=false;
                this.focusNextField();
                return false;
        case 88: // X
            gTeclaProcesada=false;
            this.typedCount +=1;
            if (this.ctrlKeyPressed) { // Ctrl-X
                this.data = [];
                this.columns = []; // Columnas adicionales de datos traidos del server
                this.textbox.value="";
                this.typedText="";
                 this.typedCount =0;
                 this.selectedIndex =-1;
                 this.clearOutput();
            }
        default:    // Letras, numeros, signos
            gTeclaProcesada=false;
            this.typedCount +=1;
            slCaract =String.fromCharCode(oEvent.keyCode).toUpperCase();
            break;
    }
//    document.TextDat1.value = " tecla " + oEvent.keyCode;

    re=/[A-Za-z0-9+-.,;:\/?¿¡#@|!$%&()Ç'=<>]/
    if (re.test(slCaract)) {
        if (this.altKeyPressed || this.ctrlKeyPressed) {
            if (eval("this.on" + slType + slCaract)) // si existe un manejador de evento ctrl o alt- tecla
                eval("this.on" + slType + slCaract + "(this)"); // ejecutar el manejador
        }

    }
}

/**
*	Habilita el proceso de generacion de un combo box en un objeto que recibe enfoque,
*   reutiliza siempre el mismo objeto, solo la primera vez crea los objetos
*	@param	oCtrl	objeto	Referencia del Objeto que recibe enfoque
*	@param	pSql	String	Sentencia SQl que genera datos para el combo
*	@param	pCond	String	Sentencia SQl adicional para el combo
*	@param	pSalida	String  Lista de los elementos que reciben la salida del combo
*							(Deben estar en el mismo contenedor que el elemento del combo)
*	@param	pConten	integer Nivel de ascendencia del nodo contenedor del los objetos de salida
*							(cuantos parentNode hay que aplicar para direccionarlos como childNodes)
*	@param	pWidth	integer Ancho de la lista
*	@return	void
**/
var gAutosuggestCont=0;
var oProv= new Object();
var oTextBox1 = new Object();
function fSelectBox(oCtrl, pSql, pCond, pSalida, pConten, pWidth, pExtra) {
	if (!pConten) pConten = 1; // Nivel ascendente delobjetocontenedor enla jerarquia DOM
	if (!pWidth) pWidth=false;
	if (!pExtra) pExtra = "";
   	blAuto = (pExtra.indexOf('auto')>=0 )? true: false;
	blStatic = (pExtra.indexOf('static')>=0) ? true: false;
    gTeclaProcesada=false;
    pSql = pSql.replace("{2}", pCond);  // Manejar unsegundoparametrocomo criterio de consulta
    oCtrl.style.borderColor = "blue";
	if (gAutosuggestCont==0) {
        oProv = new dataProvider(oCtrl.value)
        slUri='../Ge_Files/GeGeGe_sugerir.php?pLim=40' +'&pCon=' + pCond + '&pQry=' + '&pSql=' + pSql ;
	//slUri='../Ge_Files/GeGeGe_sugerir.php?pLim=20' +'&pCon=' + pCond + '&pQry=' + '&pSql=' + pSql ;
        oTextBox1 = new AutoSuggestController(oCtrl, oProv, pWidth, blAuto, blStatic, slUri);
        gAutosuggestCont +=1;
	    oTextBox1.autoExpand = (pExtra.indexOf('auto')>=0 )? true: false;
	    oTextBox1.staticData = (pExtra.indexOf('static')>=0) ? true: false;
    } else  {
        oTextBox1.autoExpand = (pExtra.indexOf('auto')>=0) ? true: false;
	    oTextBox1.staticData = (pExtra.indexOf('static')>=0) ? true: false;
        oTextBox1.textbox =  oCtrl;
        oTextBox1.typedText = oCtrl.value;
        oTextBox1.selectedIndex = -1;
        if (oCtrl.value.length > oTextBox1.minLength){
            oTextBox1.textbox.value = oCtrl.value.substring(0,oTextBox1.minLength);
        } else oTextBox1.textbox.value = oCtrl.value;
        if (oTextBox1.textbox.name  != oCtrl.name || oTextBox1.textbox.id  != oCtrl.id) { // NO es el mismo objeto visitado la ultima vez
           oTextBox1.data =[];
           oTextBox1.columns =[];
        }
        oTextBox1.uri=='../Ge_Files/GeGeGe_sugerir.php?pLim=40' +'&pCon=' + pCond + '&pQry=' + '&pSql=' + pSql ;
	//oTextBox1.uri=='../Ge_Files/GeGeGe_sugerir.php?pLim=20' +'&pCon=' + pCond + '&pQry=' + '&pSql=' + pSql ;
        oTextBox1.init();
    }
    AutoSuggestController.prototype.onCtrlB = function (oThis) {
        }
     AutoSuggestController.prototype.onCtrlZ = function (oThis) {
    }
	oTextBox1.contenedor = oTextBox1.textbox.parentNode // asumir el primer nivel de ascendencia
	for (i=1; i < pConten; i++) {			    // iterar hasta el nivel de ascendencia definido
		oTextBox1.contenedor = oTextBox1.contenedor.parentNode;
	}
	aSalida = pSalida.split(",");
	for (i=0; i < aSalida.length; i++) {
		oTextBox1.output[i+1]=aSalida[i];
		}
    oTextBox1.minLength=6;
}

