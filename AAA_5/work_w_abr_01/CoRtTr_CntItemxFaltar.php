<?php
include_once("General.inc.php");
define("RelativePath", "..");
include_once("adodb.inc.php");
include_once("GenUti.inc.php");
$gbTrans	= false;
$db = Null;
$cla=null;
$olEsq=null;
$db = &ADONewConnection('mysql');
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->autoRollback = true;
$db->debug = fGetParam("pAdoDbg",0);
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);

function fMax_OC(){
        global $db, $cla, $olEsq;
        $NumComp = fGetParam('NumComp', '0');
        $CodItem = fGetParam('CodItem', '0');
        $db->execute("DROP TABLE IF EXISTS tmp_cntoc");
        $db->execute("CREATE TEMPORARY TABLE tmp_cntoc
             SELECT a.NumComp,a.det_CodItem,IFNULL((a.cantidad-b.cantidad),a.cantidad) cantidad

            FROM (            

            SELECT com_NumComp AS NumComp,det_CodItem,ROUND(det_CanDespachada) AS cantidad

            FROM concomprobantes,invdetalle

            WHERE com_TipoComp='OC' AND com_RegNumero=det_RegNUmero AND com_NumComp=".$NumComp.") a

            LEFT JOIN

            ( SELECT co.enl_ID AS NumComp,det_CodItem,ROUND(det_CanDespachada) AS cantidad

            FROM 09_inventario.conenlace co,09_inventario.concomprobantes com, 09_inventario.invdetalle inv

            WHERE co.enl_Tipo='OC' AND co.enl_TipoComp=com_TipoComp AND co.enl_Numcomp=com.com_NumComp AND inv.det_RegNUmero=com.com_RegNumero 

            AND inv.det_Secuencia=co.enl_Secuencia AND co.enl_ID=".$NumComp.") b

            ON a.det_CodItem=b.det_CodItem");

        $alInfo=$db->GetRow("Select cantidad from tmp_cntoc where det_CodItem=".$CodItem);
        return array("info" => $alInfo );
}
$cla = NULL;
$alPar = NULL;
//$ilNumChq = NULL;
//$ilSecue= 1;
$opc = fGetParam('pOpc',1);
if (1 == $opc)
    $ogSal = fMax_OC();
//elseif (2 == $opc)
   //$ogSal = fValidar1();
/*elseif (3 == $opc)
    $ogSal = fValidaAutorizacion();
else
    $ogSal = fValidarRetencion();*/
print(json_encode($ogSal));

?>