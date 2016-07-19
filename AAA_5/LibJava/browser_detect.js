// Deteccion del browser utilizado
//

var isDOM=document.getElementById?1:0;
var isIE=document.all?1:0;
var isNS4=navigator.appName=='Netscape'&&!isDOM?1:0;
var isIE4=isIE&&!isDOM?1:0;
var isDyn=isDOM||isIE||isNS4;
// We need to explicitly detect Konqueror
// because Konqueror 3 sets IE = 1 ... AAAAAAAAAARGHHH!!!
var isKQ    = (navigator.userAgent.indexOf("Konqueror") > -1) ? 1 : 0;
// We need to detect Konqueror 2.2 as it does not handle the window.onresize event
var isKQ22  = (navigator.userAgent.indexOf("Konqueror 2.2") > -1 || navigator.userAgent.indexOf("Konqueror/2.2") > -1) ? 1 : 0;
isOP    = (navigator.userAgent.indexOf("Opera") > -1) ? 1 : 0;
isOP5   = (navigator.userAgent.indexOf("Opera 5") > -1 || navigator.userAgent.indexOf("Opera/5") > -1) ? 1 : 0;
isOP6   = (navigator.userAgent.indexOf("Opera 6") > -1 || navigator.userAgent.indexOf("Opera/6") > -1) ? 1 : 0;
isOP56  = isOP || isOP6;
isIE5   = isIE && isDOM;
isIE4   = (document.all) ? 1 : 0;
isIE4   = isIE4 && isIE && !isDOM;

