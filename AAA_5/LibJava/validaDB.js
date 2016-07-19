/**
*   validaDB.js:        Funciones para habilitar un objeto validador que aplica consultas a una Base de datos
*/
window.mSelectflag = false;										// Indicador de Sventana de seleccion activa
window.OldValue	   = undefined;										// Valor anterior del HTMLelement actual
window.onfocus = function() { 	fActivaSeleccion()}
/**
*		Activa la pantalla de seleccion si esta abierta
*
*/
function fActivaSeleccion()
{
	if (window.mSelectflag) {
		if (!frames["frValida"].winsel.closed) {
            window.frames["frValida"].winsel.focus();
            return true;
        }
	}
}
/**
*		Almacena el valor anterior de un control HTML
*		@param		objeto:		Contenido del control al que se ingresa
*		@return		viod
*/
function fValorPrevio(oControl)
{
	window.OldValue = oControl.value
	window.status += oControl.value
}
/**
*		Verifica si el valor de un componente HTML ha cambiado
*		@param		objeto:		Contenido del control al que se ingresa
*		@return		boolean		Si al cambiar, caso contrario NO
*/
function fCambio(oControl){
	window.status = "old =" +  window.OldValue +"/" + oControl.value;
	if (window.OldValue == undefined || oControl.value != window.OldValue) return true;
	return false;
}
/**
*	Definicion de un Objeto Validador
*
*   @param  sHref		Str:	Pagina php que Realiza las acciones iniciales de validacion
*	@param	lCond		Str:	Condición de Bùsqueda
*	@param	lTabla		Str:	Tabla(s) sobre la que se valida
*	@param	sTipo		Int: 	Tipo de Validacion:  0= No debe existir, 1 = Debe existir solo uno,  2 = Puede existir mas de uno , 3 devolver un valor
*	@param	sMensj		String: Mensaje de Error
*	@param	lCampos	Str:	Campos que se seleccionan
*	@param	lForm		Str:  	Formulario receptor de los datos
*	@param	lDesti		Array:  Lista de campos de destino
*	@param	lPageR		Str:  	Página que lanza una selección individual si hay respuesta multiple
*	@param	lParam		Str:  	Parámetros que requiere la pagina de seleccion (keystring y Opcode)
*	@param	lOpts		Str:  	Opciones de apertura de la pagina de seleccion
*	@param	lFocus		Str:    Campo al que se hará focus
*/
function oValidador(lHref , lCond, lTabla, lTipo, lMensj, lCampos, lFormul, lDesti, lPageR, lParams ,  lOpts , lFocus)
{
    this.On      = false;                               //Bandera de actividad
	this.Separ   ="|";			                        // Caracter utilizado como separador de parametros del Url de seleccion
	this.Igual   = "@";			                        // Caracter utilizado para sustituir el signo "=" en url de seleccion
	this.Href    = (lHref)?lHref:"../Ge_Files/ValidaGeneral.php";
	this.Cond    = String(lCond).replace("=", this.Igual);
	this.Tabla   = lTabla;
	this.Tipo    = lTipo?lTipo:0;
	this.Mensj   = lMensj?lMensaj:"*** SELECCIONE UN ELEMENTO ***";
	this.Selec   = "";
    this.Campos  = lCampos;
	this.Formul  = lFormul;
	this.Desti   = lDesti?lDesti:"x";
	this.Params  = lParams?lParams:"pOpCode=E";
	this.PageR   = lPageR;
	ilAnch=700;
	ilLarg=400;

	sOpts = new String(lOpts);
	lOpts  = sOpts.lenght > 1 ? sOpts: ('toolbar=no,menubar=no,width=' + ilAnch + ',height=' + ilLarg + ',scrollbars=yes, alwaysRaised=1');
//	lOpts  = "'width=700'"
	this.Opts    = lOpts;
	this.Focus   = lFocus;
	this.Skeywd  = ""                                  //Parametro 's_keyword' a enviar a la consula'          (Opcional)
	this.CondEx   = ""                                 //Condicion Final para agregar al query de pagina de seleccion  (Opcionañ)
	this.SelUrl   = undefined;                         //Url para la pagina de Seleccion
	this.Aplicar = fAplicar;
   if (isNS4) {
     // Center on the main window.
     illeft = window.screenX + ((window.outerWidth - ilAnch) / 2)
     iltop = window.screenY + ((window.outerHeight - ilLarg) / 2)
     this.Opts   += ',' +  'screenX=' + illeft + ',screenY=' + iltop
  } else {
     // The best we can do is center in screen.
     illeft = (screen.width - ilAnch) / 2
     iltop = (screen.height - ilLarg) / 2
     this.Opts   += ',' +  'left=' + illeft + ',top=' + iltop
  }
    this.Opts = "'" + this.Opts + "'";
}
/**
*	Ejecuta validacion:	Dependiendo de ValFñag, Carga un frame "frValida"  con la pagina que consulta la BD ó
*   carga directamente la pagina de seleccion vacia.
*
*/
function fAplicar(pValFlag)
{
    if (pValFlag) {
	   this.PageR = this.PageR + this.Separ + this.Params.replace("=", this.Igual) +
				  this.Separ + "s_keyword" + this.Igual + this.Skeywd   +   this.Separ  +
                               "pCondEx="  + this.CondEx.replace("=", this.Igual);


        slHref = this.Href   +  "?pCond=" 	 + 	this.Cond   +   "&pTabla="  + 	this.Tabla 	+
    			 "&pSepar="  + this.Separ    + 	"&pIgual="  + 	this.Igual 	+	"&pOpts="  	+ 	this.Opts 	+	
    			 "&pTipo="   + this.Tipo     + 	"&pMensj="  + 	this.Mensj 	+	"&pCampos=" +
    			 this.Campos + "&pForm=" 	 + this.Formul 	+ 	"&pDesti="  + this.Desti	+
    			 "&pSelec="  + this.Selec.replace("=", this.Igual)  	    +
    			 "&pPageR="  + this.PageR 	 + "&pFocus="   + this.Focus  ;
//    	alert(slHref);
    window.frames["frValida"].location.replace(slHref);
    	}
//	window.open (slHref);
    else {
        window.status="V7";
        this.PageR = this.PageR     + "?"           + this.Params     +
                     "&pMensj="     + this.Mensj 	+ "&pCampos="     + this.Campos    + "&pForm=" 	 +
                     this.Formul 	+ "&pDesti="    + this.Desti      + "&pSelec="     + this.Selec   +
    			     "&pPageR="     + "&pFocus="    + this.Focus      + "s_keyword="   + this.Skeywd  +
                     "pCondEx="     + this.CondEx.replace("=", this.Igual);
	    window.open (this.PageR,'wSearch', this.Opts );
        }
}
