
//Begin CCS script
//Include Common JSFunctions @1-73ADA5ED
/*
*   Creacion de Objeto para RPC
*/
olAct = new clsRpc();
function fCambioDato(pObj, pRow, pCol)
{

    slSql = "REPLACE INTO fistablassri(tab_CodTabla, tab_CodSecuencial, tab_Codigo, tab_txtData) "+
			 " VALUES ('99', " + pRow + ", " + pCol + ", '" + pObj.value + "') ";
//window.status = "Actualizando " + slSql;
//alert(slSql);
	olAct.procesa(false, slSql, '99', pRow, pCol);
}
/**
*   Evento onLoad, a ejecutarse luego del proceso remoto
*/
_rpc.onLoad = function () {
    var data = this.getResponse();
//    alert (data.mensaje)
}

