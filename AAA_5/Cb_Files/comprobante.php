<?php
// the line below is only needed if the include path is not set on php.ini
//ini_set("include path","C:\AppServ\www\phpreports");
@session_start("dbname");

//comentar en producción
    $_SESSION["dbname"]="09_ameneg";
//comentar en producción

include_once ("GenUti.inc.php");
include_once "PHPReportMaker.php";
ini_set("error_reporting",fGetParam("pError", 0));

$oRpt = new PHPReportMaker();
$oRpt->setDatabase($_SESSION["dbname"]);
$oRpt->setUser("root");
$oRpt->setPassword("xx");
//$oRpt->setSQL("select CITY,NAME,TYPE,ITEM,VALUE from sales order by CITY,NAME,TYPE,ITEM");
$strSql ="SELECT  com_RegNumero AS 'REG',
                    det_secuencia AS 'SEC',
                    com_TipoComp AS 'TIP',
                    com_NumComp AS 'COM',
                    com_FecTrans AS 'FTR',
                    com_FecContab AS 'FCO',
                    com_Emisor AS 'CEM',
                    com_CodReceptor AS 'CRE',
                    com_Receptor AS 'REC',
                    com_Concepto AS 'CON',
                    concat(det_CodCuenta, '   ') AS 'CCU',
                    '----' AS 'ABC',
                    IF(det_idauxiliar =0, ' ', concat(det_idauxiliar, '     ')) AS 'CAU',
                    cue_Descripcion AS 'CUE',
                    concat(IF(det_idauxiliar <> 0 ,
                                concat(IFNULL( concat(act_descripcion, ' ', ifnull(act_descripcion1,' ')),
                                   concat(per_Apellidos, ' ', ifnull(per_Nombres, ' ')) ),
                            ' :  ' ), ''), det_Glosa )   AS 'DES',
                    det_NumCheque as 'CHE',
                    det_ValDebito  AS 'VDB',
                    det_ValCredito AS 'VCR'
                 FROM concomprobantes JOIN condetalle on (det_RegNumero = com_RegNumero)
                    LEFT JOIN conpersonas ON (per_CodAuxiliar = det_IdAuxiliar)
                    LEFT JOIN conactivos ON (act_CodAuxiliar = det_IdAuxiliar)
                    LEFT JOIN concuentas ON (cue_codcuenta = det_codcuenta) ";
if (strlen($_REQUEST[$pQry])>1){
   $strSql=$strSql." WHERE ". $pQry  . " ORDER BY 1, 2";
}
//echo $strSql;
$oRpt->setSQL($strSql);
$oRpt->setXML("comprobante.xml");
$oRpt->run();
?>
