<?php
/*    Reporte de comprobante contable (PC) Dinaser
     @rev   esl      17/Agosto/2011    Solo dejar nombre de la cuenta y no de los padres - Dinaser
 */

ob_start("ob_gzhandler");
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
include_once("adoConn.inc.php");
$db->debug=fGetparam("pAdoDbg",false);
require('Smarty.class.php');
class Smarty_AAA extends Smarty {
   function Smarty_AAA()
   {
        $this->Smarty();
        $this->template_dir = '../templates';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = false;
   }
}

include("../LibPhp/excelOut.php");

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$glFlag= fGetParam('pEmpq', false);
// parametro para el query general
$pQry = fGetParam('pQryCom','');

$sSql = " SELECT
                   com_RegNumero AS 'REG',
                   cla_Descripcion as 'TXT',
                    det_secuencia AS 'SEC',
                    com_TipoComp AS 'TIP',
                    com_NumComp AS 'COM',
                    com_FecTrans AS 'FTR',
                    com_FecContab AS 'FCO',
                    com_FecVencim AS 'FVE',
                    com_Emisor AS 'CEM',
                    com_CodReceptor AS 'CRE',
                    com_Receptor AS 'REC',
                    concat(b.per_Apellidos, ' ', b.per_Nombres, ' [', b.per_ruc,']') as 'NRE',
                    concat(com_Concepto, ' - ' ,b.per_Apellidos, ' ', b.per_Nombres)  AS 'CON',
                    concat(det_CodCuenta, '   ') AS 'CCU',
                    '----' AS 'ABC',
                    IF(det_idauxiliar =0, ' ', concat(det_idauxiliar, '     ')) AS 'CAU',
                    concat_ws('/ ',ifnull(left(a.cue_descripcion,20),''), ifnull(left(p.cue_descripcion,20),''), ifnull(left(c.cue_Descripcion,25),'******* No existe???')) AS 'CUE',
                    CONCAT_WS('/',IFNULL(LEFT(c.cue_Descripcion,25),'******* No existe???')) AS 'CUE2', /* PARA DINASER */
                    concat(IF(det_idauxiliar <> 0 ,
                                concat(IFNULL( concat(act_descripcion, ' ', ifnull(act_descripcion1,' ')),
                                   concat(d.per_Apellidos, ' ', ifnull(d.per_Nombres, ' ')) ),
                            ' :  ' ), ''), det_Glosa )   AS 'DES',
                    det_NumCheque as 'CHE',
                    det_ValDebito  AS 'VDB',
                    det_ValCredito AS 'VCR',
                    par_descripcion as 'RUC'
                 FROM concomprobantes JOIN condetalle on (det_RegNumero = com_RegNumero)
                    LEFT JOIN conpersonas b ON (b.per_CodAuxiliar = com_CodReceptor)  /*nombre del receptor en la cabecera*/
                    LEFT JOIN conpersonas d ON (d.per_CodAuxiliar = det_IdAuxiliar) /*descripcion del auxiliar en el detalle del comprobante*/
                    LEFT JOIN conactivos ON (act_CodAuxiliar = det_IdAuxiliar)
                    LEFT JOIN concuentas c ON (c.cue_codcuenta = det_codcuenta)
                    LEFT JOIN concuentas p ON (p.cue_id = c.cue_padre)
                    LEFT JOIN concuentas a ON (a.cue_id = p.cue_padre)
                    left join genparametros on par_clave = 'EGRUC'
                    JOIN genclasetran ON cla_tipoComp = com_TipoComp 
                    ";


$sSql .= ($pQry ? " WHERE " . $pQry  : " " ) . " ";
$sSql .= " ORDER BY 1, 2"; 
$rs = $db->execute($sSql . $slFrom);

if($rs->EOF){
    fErrorPage('','NO SE GENERO INFORMACION PARA MOSTRAR', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    $slPiePag = $_SESSION["g_user"] . ", " . date("%d %M %y");
    $Tpl->assign("slPiePag", $slPiePag);
    if (!$Tpl->is_cached('CoTrTr_comprob_html.tpl')) {
            }
            $Tpl->display('CoTrTr_comprob_html.tpl');
}
?>

