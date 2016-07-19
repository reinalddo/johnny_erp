// Author: Vincent Detemmerman
// Date: 2001-02-15 17:32:00
/**
*   Funciones de formateo y detetccion de valores numericos
*   @access     public
*   @author     fausto astudillo
*   @created    12/Abr/03
*   @rev        30/Ene/05       Añadir funciones de manejo numerico
**/
ES_EC= new locale(".", ",", "2"); //                         Definicion de numerico para Ecuadr
function locale(decimalPoint, thousandSep, fracDigits) {
  this.decimalPoint = new String(decimalPoint);
  this.thousandSep = new String(thousandSep);
  this.fracDigits = fracDigits;
  }
function roundFloat(num, fracDigits) {
  var factor = Math.pow(10, fracDigits);
  return(Math.round(num*factor)/factor);
  }     
function toLcString(num, lc) {
  var str = new String(num);
  var aParts = str.split(".");
  return(aParts.join(lc.decimalPoint));
  }
function formatNum(num, lc) {
  var sNum = new String(roundFloat(num, lc.fracDigits));
  if(lc.fracDigits>0) {
    if(sNum.indexOf(".")<0)
      sNum = sNum+".";
    punto= sNum.indexOf(".");
    decim = parseInt(lc.fracDigits);
    falta = 2 + punto + decim - sNum.length;
    if (falta) for (i=1; i< falta; i++) { sNum = sNum+"0"; }
    }
    var aParts = sNum.split(".");
    l=aParts[0].length;
    for (i=l-4; i>= 0;) {
        if (aParts[0].slice(0,i+1) != '-') {
            aParts[0] = aParts[0].slice(0,i+1) + lc.thousandSep + aParts[0].slice(i+1);
            window.status += " / i=" + i + ":  " + aParts[0]
        }
        i-=3;
	}
	sNum=aParts.join(".");
  return(toLcString(sNum, lc));
  }
function parseLcNum(str, lc) {
  var sNum = new String(str);
  var aParts = sNum.split(lc.thousandSep);
  sNum = aParts.join("");
  aParts = sNum.split(lc.decimalPoint);
  return(parseFloat(aParts.join(".")));
  }
  
function addZero(str, dig, izder) {
  var sNum = new String(str);
  while(sNum.length < dig) {
      if (izder == "i")  sNum = "0" + sNum;
      else sNum = sNum+"0";
	  }
   return (sNum);
   }
