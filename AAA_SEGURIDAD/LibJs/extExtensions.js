/*
 * 	Extensiones a objetos Ext
 *  @author     Fausto Astudillo
 *  @date       12/Ago/07
*	@rev		fah 21/03/09	Mejorar opciones de formateo de datos numericos
 */
/*
 * Definiciones generales
 **/
 Ext.ns("app.gen");
    app.gen.dateFmt  ='d-M-y';
    app.gen.dateFmts = 'd-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y |d/M/Y|';
    app.gen.dateTimeFmt  ='d-M-y H:i';
    app.gen.dateTimeFmts = 'dmy|d-m-y H:i|d-m-Y H:i|d-M-y H:i|d-M-Y H:i|d/m/y H:i|d/m/Y H:i|d/M/y H:i|d/M/Y H:i|d-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y|d/M/Y';

/*
 * 	Añadir Metodo getSelectedIndex al combo box
 *  @return     el indice correspondiente al registro seleccionado en el Combo.
 */
 
Ext.override(Ext.form.ComboBox, { // returns a ComboBox's selectedIndex
  getSelectedIndex : function() {
    var s = this.store;
    return s.indexOf(s.query(this.valueField, this.getValue()).get(0));
  }
});
/*
 * 	Añadir Metodos a un formulario
 */
Ext.override(Ext.form.FormPanel, { // returns an array of modified Fiels
/*
 *  Retorna un arreglo con los campos modificados del Formulario
 *  @return     array de objetos/campos
 */
  getModifiedFields : function() {
    return this.items.items.findAll(function(olField){return olField.isDirty()});
  	},
/*
 *  Retorna un arreglo con los valores modificados del Formulario
 *  @return     array referenciado por campo, valor
 */
  getModifiedValues : function() {
    var alModVal = new Array();
    var alModFld = this.getModifiedFields();
    this.items.items.findAll(
       function (olField){
	    if (olField.isDirty()){
		    alModVal[olField.getId()] = olField.getRawValue();
		    return true;
	    }
       }
	)
	return alModVal;
  }
});
/*	@TODO   Revisar porque no funciona
 *	Redefinicion de Formatos y basicos para permitir la definicion de formatos en configuraciones "lazy"
 *
  */
/*Ext.override(Ext.util.Format, {
	dateFormat: 'd/M/y',
	moneyFormat: '$0,000.00',
	date: function(v, format){
		if(!v){
			return '';
		}
		if(!Ext.isDate(v)){
			v = new Date(Date.parse(v));
		}
		return v.dateFormat((typeof format == 'string') ? format : Ext.util.Format.dateFormat);
	},
	money: function(v){
		return Ext.util.Format.number(v, Ext.util.Format.moneyFormat);
	},
	stripTags: function(v){
		return !v ? v : String(v).replace(Ext.util.Format.stripTagsRE, '');
	},
	stripScripts: function(v){
		return !v ? v : String(v).replace(Ext.util.Format.stripScriptsRe, '');
	}
});*/
/*
*   Renderizador de fechas , garantiza la conversion de formato al cargar el grid y
*   al modificar el campo
*   @par    pVal    String      Valor textual
*   @par    pCell   Object      Ref al objeto Celda
*   @par    prec    Object      Ref al Registro de datos actual
*   @par    pRidx   Integer     Indice de fila
*   @par    pCidx   Integer     Indice de clumna
*   @par    pGrid   Object      Ref al Objeto Grid Actual
**/
function fRenderDate(pVal,pCell,pRec, pRidx,pCidx,pGrid) {
    try {
        if (pVal.length == undefined) var dlFec=pVal;
        else var dlFec=Date.parseDate(pVal, "Y-m-d");
        pRec.data[this.name] =  dlFec.format('Y-m-d');
        return dlFec.format('d/M/y');
    } catch (e){ return pVal};
}

/*
*   Renderizar un valor Numerico, 2 decimales, sin simbolo demoneda, no ceros
*
*  @param      v	float   Valor a formatear
*  @return     texto con datos formateados
*/
function fRendQuantity__(v){
	d=2;
    z=false;
    if (v + 0 == 0 && !z) 		return "";
    v = (Math.round((v-0)*100))/100;
    v = (v == Math.floor(v)) ? v + ".00" : ((v*10 == Math.floor(v*10)) ? v + "0" : v);
    v = String(v);
    var ps = v.split('.');
    var whole = ps[0];
    var sub = ps[1] ? '.'+ ps[1] : '.00';
	var falta = d - sub.length + 1;
	if (falta) for (var i=1; i< falta; i++) { sub +="0"};
    var r = /(\d+)(\d{3})/;
    while (r.test(whole)) {
        whole = whole.replace(r, '$1' + ',' + '$2');
    }
    return whole + sub;
}
/*
*   Renderizar un valor Numerico, n decimales, sin simbolo demoneda, no ceros
*
*  @param      v	float   Valor a formatear
*  @param      d	int     Numero de Decimales a presentar
*  @return     texto con datos formateados
*/
function fRendQuantity(v, d){
	if (!d) d=2;
    z=false;
    if (v + 0 == 0 && !z) 		return "";
    v = (Math.round((v-0)*100))/100;
    v = (v == Math.floor(v)) ? v + ".00" : ((v*10 == Math.floor(v*10)) ? v + "0" : v);
    v = String(v);
    var ps = v.split('.');
    var whole = ps[0];
    var sub = ps[1] ? '.'+ ps[1] : '.00';
	var falta = d - sub.length + 1;
	if (falta) for (var i=1; i< falta; i++) { sub +="0"};
    var r = /(\d+)(\d{3})/;
    while (r.test(whole)) {
        whole = whole.replace(r, '$1' + ',' + '$2');
    }
    return whole + sub;
}
/*
*   Renderizar un valor Numerico, n decimales, sin simbolo demoneda, no ceros
*
*  @param      v	float   Valor a formatear
*  @param      d	int     Numero de Decimales a presentar
*  @param      s	string  Separador de decimales
*  @param      z	string  texto a devolver cuando el valor = 0
*  @return     texto con datos formateados
*/
function fRendNumber(v, d, s, z){		// #fah21/03/09
	if (!d) d=0;
	if (!d) s="";
	if (!z) z="";
	if (v==0) return z;
    if (v + 0 == 0 && !z) 		return "";
    v = (Math.round((v-0)*100))/100;
    v = (v == Math.floor(v)) ? v + ".00" : ((v*10 == Math.floor(v*10)) ? v + "0" : v);
    v = String(v);
    var ps = v.split('.');
    var whole = ps[0];
    var sub = ps[1] ? '.'+ ps[1] : '.00';
	var falta = d - sub.length + 1;
	if (falta) for (var i=1; i< falta; i++) { sub +="0"};
    var r = /(\d+)(\d{3})/;
    while (r.test(whole) && s != "") {
        whole = whole.replace(r, '$1' + s + '$2');
    }
	if (d>0)    return whole + sub;
	else return whole;
}
/*
*   Renderizar un valor Numerico, 4 decimales, sin simbolo demoneda, no ceros
*
*  @param      v	float   Valor a formatear
*  @return     texto con datos formateados
*/
function fRendQtty4(v){
  return fRendQuantity(v, 4);
}
/*
*   Renderizar un valor Numerico, sin decimales, sin simbolo demoneda, no ceros
*
*  @param      v	float   Valor a formatear
*  @return     texto con datos formateados
*/
function fRendInteger(v){
	d=0;
    z=false;
    v1=v;
    if (v + 0 == 0 && !z) 		return "";
    v = (v == Math.floor(v)) ? v + ".00" : ((v*10 == Math.floor(v*10)) ? v + "0" : v);
    v = String(v);
    var ps = v.split('.');
    var whole = ps[0];
    var sub = ps[1] ? '.'+ ps[1] : '.00';
    var r = /(\d+)(\d{3})/;
    while (r.test(whole)) {
        whole = whole.replace(r, '$1' + ',' + '$2');
    }
}
/*
 *	Definicion de nuevs formatos 
 **/
Ext.apply(Ext.util.Format, {
   /*
   *	Formato formato tipo moneda, con colores segun el signo
   *	@param	float  val	Valor a renderizar
   **/	 
  floatPosNeg: function(val){
	if(val == 0) return "";
	if(val > 0){
		return '<span style="color:green;">' + fRendNumber(val,2,",") + '</span>';
	}else if(val < 0){
		return '<span style="color:red;">' + fRendNumber(val,2,",") + '</span>';
	}
	return val;
  }
  /*
   *	Punto Flotante con 2 decimales
   *	@param	float  val	Valor a renderizar
   **/		
  ,float2Dec: function(val){
	if(val == 0) return "";
	if(val > 0){
		return '<span style="color:green;">' + fRendNumber(val,2,"") + '</span>';
	}else if(val < 0){
		return '<span style="color:red;">' + fRendNumber(val,2,"") + '</span>';
	}
	return val;
	}
  /*
   *	Entero Simple
   *	@param	float  val	Valor a renderizar
   **/		
  ,intSimple: function(val){
	return parseInt(val);
	}
  /*
   *	Entero con separador
   *	@param	float  val	Valor a renderizar
   **/		
  ,intComa: function(val){
	return fRendNumber(val,0,",")
  }
})

/*
*   Accones para asegurar que el contenido del campooculto de los grids se actualice y se envie con el form
*
*/
gaHidden = new Array();
function fSelCombo(pCmb, pRec, pIdx){
	gaHidden[pCmb.hiddenName] = pCmb.getValue();
 }
