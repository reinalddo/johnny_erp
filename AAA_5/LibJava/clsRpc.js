/**
*   Clase que define el objeto Ajax que ejecuta procesos remotos
*
*/
function clsRpc() {
	pAjax.call(this);
	pAjax.setDebugMode(false);
}
/**
*   Inicializacion del Objeto
*/
var _rpc = clsRpc.prototype = new pAjax;
/**
*   Definicion del proceso a ejecutar,
*   @param:	pUri    text    Direccion del proceso remoto a ejecutar
*   @param:	pUri    pSql    Instruccion SQL
*   @param:	pPar    string  texto con loa nombres de parametros, separados por comas
*   @param:	pVal    string  texto con loa valores de parametros, separados por comas
*/
_rpc.procesa = function (pUri, pSql, pPar, pVal) {
	pAjaxDbg=getFromurl('pAjaxDbg', 0);
    if(pAjaxDbg >0) slDbg="pAjaxDbg=" + pAjaxDbg;
    else slDbg="";
	if (!pUri) pUri = "../Ge_Files/GeGeGe_rpcfuncion.php?pSql=" + pSql + "&" + slDbg;
    var oRequest = this.prepare("fDBejecutar", pAjaxRequest.POST);
	oRequest.setURI(pUri +pSql);
    oRequest.setParam("param", pSql);
/*	oRequest.setParam(pPar[0], pVal[o]);
	*/
	oRequest.execute(pAjaxRequest.ASYNC);
}
