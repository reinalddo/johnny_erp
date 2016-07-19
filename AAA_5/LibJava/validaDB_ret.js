/**
*       validaDB_ret.js:  Funciones para retornar valores desde la pagina de seleccion a la principal
*/
alDest= new Array();
if(getFromurl("pOpCode"," ") == "E") {
    if (window.opener && window.opener.parent.oValida.Desti) window.opener.parent.oValida.Desti.indexOf(",")>0?alDest=window.opener.parent.oValida.Desti.split(","):alDest[0]=window.opener.parent.oValida.Desti;
    slForm= window.opener.parent.oValida.Formul?window.opener.parent.oValida.Formul+".":"";
    }
window.enMantto=false;
window.onclose=fCerrar;
function fCerrar()
{
//    if (confirm("DESEA CERRAR LA VENTANA SIN SELECCIONAR NADA?")) {
        if (!window.opener) window.close();
        slPrefijo = "window.opener.parent.document." + slForm ;
        if (getFromurl("pOpCode") == "E" && window.opener.parent.mSelectflag ) {
            window.opener.parent.mSelectflag = false;
            window.opener.parent.top.mSelectflag = false;
            slCmd  = slPrefijo + alDest[0] + ".focus(); \n"
            slCmd += "if (" + slPrefijo + alDest[0] + "tagName == 'INPUT') " + slPrefijo + alDest[0] + ".select(); \n"
            eval(slCmd)
            window.close()
        }
//    }
}
/**
*       Retorna informacion a la pagina principal cuando el usuario clickea en una fila
*       @param      obj     Object: Objeto sobre el que se clickea
*       @param      frm     String: Formulario en el que se encuentra
*/
function fRetornarDatos(obj, frm)
{
//    alert("dato:" + 	document.getElementsByTagName("INPUT")["3"].name + " = " +
//						document.getElementsByTagName("INPUT")["3"].value +
//						" " + fgetElement("caj_Descripcion_1")  + "  otro:   " + document.forms["liqcajas_list"].caj_Componente1[1].value);
//    alert(document.getElementById('caj_CodCaja')[1].name + "  =  " + document.getElementById('caj_CodCaja')[1].value)
    
    if (window.enMantto) {
        window.enMantto = false
        return;                        // no continuar porque se aplico el mantenimiento
        }
    if(getFromurl("pOpCode") == "E" ) {
        if (window.opener.closed) window.close();
        if (obj.tagName == 'TR')  {
            ilFilas = obj.parentNode.rows.length;              // si elevento se lanza desde la fila
            ilFila  = obj.rowIndex-2;
            }
        if (obj.tagName == 'TD' ) {
            ilFilas = obj.parentNode.parentNode.rows.length;   // si el evento se lanza desde la celda
            ilFila  = obj.parentNode.rowIndex-2;
            }
        if (obj.tagName == 'INPUT')  {
            ilFilas = obj.parentNode.parentNode.parentNode.rows.length;   // si el evento se lanza desde un textbox
            ilFila  = obj.parentNode.parentNode.rowIndex-2;
            }
        slCmd=" with (window.opener.parent.document) {\n";
        alOrig= new Array();
        window.opener.parent.oValida.Campos.indexOf(",")>0?alOrig=window.opener.parent.oValida.Campos.split(","):alOrig[0]=window.opener.parent.oValida.Campos;
        for (i=0;i< alDest.length;i++) {
        	if (frm.length) {                                                               // si hay un formulario
                slCmd += slForm + alDest[i] + ".value= " +			
    				 "document.forms['" + frm + "']."
    			}
    		else {
    		    slCmd += slForm + alDest[i] + ".value= " +			
    				 "document.";
    			}	


            if ((ilFilas -3) > 1) {                                                       //Si la consulta tiene mas de una respuesta
            	slCmd +=  alOrig[i] + "[" + ilFila + "].value; \n"					//Aplica contexto de array para identificar los elementos HTML
    			}
            else                                                                            			//Si la consulta arroja un resultado unico
                slCmd += alOrig[i]+ ".value; \n";                                             			//Aplica identificacion por nombre de loc componentes HTML
        }
        slCmd += "\n}";
//     alert("Comando Generado:" + slCmd + "         ");
        eval(slCmd);
        window.opener.parent.mSelectflag = false;
        window.opener.parent.top.mSelectflag = false;
        window.opener.parent.focus();
        slPrefijo = "window.opener.parent.document." + slForm ;
        if (window.opener.parent.oValida.Focus!='') {
            slCmd  = slPrefijo + alDest[0] + ".focus(); \n"
            slCmd += "if (" + slPrefijo + alDest[0] + ".tagName == 'INPUT') " + slPrefijo + alDest[0] + ".select(); \n"
//            slCmd  = "window.opener.parent.document." + slForm + window.opener.parent.oValida.Focus + ".focus(); \n";
//            slCmd += "window.opener.parent.document." + slForm + window.opener.parent.oValida.Focus + ".select(); \n";
            eval(slCmd);
        }
        window.close();
    }
}
/**
*       Resalta el texto del control enfocado
*       @param      obj     Object: Objeto sobre que se enfoca
*       @return      void
*/
function fTxtResalta(obj, lcolor)
{
    obj.style.color=lcolor
    obj.style.fontVARIANT='italic';
    obj.style.fontSize='14';
    obj.style.cursor='hand';
}
/**
*       Vuelve e texto del control enfocado a su color normal
*       @param      obj     Object: Objeto sobre que se enfoca
*       @return      void
*/
function fTxtNormal(obj)
{
    obj.style.color=''
    obj.style.fontWeight='';
    obj.style.fontSize='11';
    obj.style.cursor='default';
}
/**
*       Abre pagina nueva de mantenimiento,
*       @param      obj        Object: Objeto sobre el que se clickea
*       @param      pag        String: Url de la pagina que hace el mantenimiento
*       @param      clave      String: Parametros necesarios para ubicar el registro a mantener
*/
function fMantenimiento(obj, pag, clave)
{
    window.enMantto=true;
    var lsOpts= "toolbar=no,menubar=no,width=900,height=500,scrollbars=yes, alwaysRaised=1";
    pag += clave?"?" + clave + "="+obj.value:"";
    slWinName = 'wMante' + (Math.random * 100)
    window.open(pag, slWinName, lsOpts);
}
