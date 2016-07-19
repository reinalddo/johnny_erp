
/**
*   Validacion de fechas digitadas, aplicando el formato dd/mm/yy o dd/mmm/yy.
*   Actualiza el contebido del texto con la fecha en formato dd/mmm/yy
*   @param      pCtrl       Object 	Control de texto que ontiene los datos
*   @return     true / false
*   CHANGELOG:
*   @revision   fah001: 07/09/04    Asegurar que la validacion numerica del mes, se aplique multiplicando por 1
*/

function fValidarFecObj(pCtrl)
{
	return fValidarFecha(pCtrl.form.name, pCtrl.name)
}

/**
*   Validacion de fechas digitadas, aplicando el formato dd/mm/yy o dd/mmm/yy.
*   Actualiza el contebido del texto con la fecha en formato dd/mmm/yy
*   @param      formName:   Nombre del formulario en el que se encuentra el texto a validar
*   @param      textName:   Nombre del control texto que contiene el dato a validar
*   @return     void
*   CHANGELOG:
*   @revision   fah001: 07/09/04    Asegurar que la validacion numerica del mes, se aplique multiplicando por 1
*/

function fValidarFecha(formName, textName)
{
var errMsg="", lenErr=false, dateErr=false;
 var testObj=eval('document.' + formName + '.' + textName + '.value');

 if (testObj.indexOf('/') > 0 ) {
    var testStr=testObj.split('/');
    }
 else {
    var testStr = Array();
    testStr[0]=testObj.substring(0,2);
    testStr[1]=testObj.substring(2,4);
    testStr[2]=testObj.substring(4,6);
}
 if(testStr.length>3 || testStr.length<3 )
 {
  lenErr=true;
  dateErr=true;
  errMsg+="Hay un Error en el formato de Fecha Digitada. (" + textName + ")";
 }
 
 var monthsArr = new Array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago" ,"Sep", "Oct", "Nov", "Dic");
 var monthsNum = new Array("1", "2", "3", "4", "5", "6", "7", "8" ,"9", "10", "11", "12");
 var daysArr = new Array;
 for (var i=0; i<12; i++)
 {
  if(i!=1)				// Si no es el 2do mes
  {
   if((i/2)==(Math.round(i/2)))			// Meses pares
   {
    if(i<=6)							// Antes de Junio
    {     daysArr[i]="31";    }
    else
    {     daysArr[i]="30";    }
   }
   else						// Mese Impares
   {
    if(i<=6)
    {     daysArr[i]="30";    }
    else
    {     daysArr[i]="31";    }
   }
  }
  else					// El segundo mes
  {
   if((testStr[2]/4)==(Math.round(testStr[2]/4)))	// año bisiesto
   {    daysArr[i]="29";   }
   else
   {    daysArr[i]="28";   }
  }
 }
 var monthErr=false, yearErr=false;
 if((testStr[2]>99 || testStr[2]<1) &&!lenErr)
 {
  yearErr=true;
  dateErr=true;
  errMsg+="\nEl año \"" + testStr[2] + "\" no es correcto.";
 }
 if (!lenErr) {
    var numMonth= parseInt(testStr[1]*1); //                                fah001
    if (numMonth != NaN && numMonth > 0 && numMonth < 13 )  { var setMonth= parseInt(testStr[1] -1);}
    else {for(var i=0; i<12; i++)
            { if(testStr[1].toLowerCase() == monthsArr[i].toLowerCase())
                  { var setMonth=i;
                    break;
                  }
            }
     }
    }
 if(!lenErr && (setMonth==undefined))
 {
  monthErr=true;
  errMsg+="\nEl mes \"" + testStr[1] + "\" es incorrecto.";
  dateErr=true;
 }
 if(!monthErr && !yearErr && !lenErr)
 {
  if(testStr[0]>daysArr[setMonth])
  {
   errMsg+=monthsArr[setMonth] + '/' + testStr[2] + ' No tiene ' + testStr[0] + ' días.';
   dateErr=true;
  }
 }
 if(!dateErr)
 {
    eval('document.' + formName + '.' + textName + '.value = \'' + testStr[0] + '/' + monthsArr[setMonth] + '/' + testStr[2] + '\'')
    return true;
 }
 else
 {
  eval('document.' + formName + '.' + textName + '.focus()');
  eval('document.' + formName + '.' + textName + '.select()');
  alert(errMsg + '\n____________________________\n\nEjemplo de fecha válida :\n23/02/04   o   23/Feb/04');
  return false;
 }
}
/**
* 	Conversion de Formatos de Fechas
*	@access		public
*	@param		pFech		String	texto representando un fecha
*	@param		pFtoI		String	Formato de Ingreso	
*	@param		pFtoS		String	Formato de Salida
*	@return		String 		Cadena que representa la Fecha en el formato de salida
*   @require    'datepicker.js' de CCS
**/
function fConvFecha(pFech, pFtoI, pFtoS){
	if (!pFtoS) pFtoS = "yyyy-mm-dd";	  // Default
	if (!pFtoI) pFtoI = "dd/MMM/yy";	 // Default
	return formatDate(parseDate(pFech, parseDateFormat(pFtoI)), parseDateFormat(pFtoS));
}

