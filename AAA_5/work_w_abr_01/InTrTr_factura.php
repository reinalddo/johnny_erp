<?php
/*    Factura Formato HTML
 *
 */
//$FileName = "CoTrTr_productoresdet.rpt.php";
include_once("../LibPhp/GenCifras.php");
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
        $this->template_dir = 'template';
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
$pQry = fGetParam('pQryCom','');
$numero = fGetParam('regNumero','');


//$anio = fGetParam('s_Anio',date('Y'));
//$mes = fGetParam('s_Mes',date('m'));

//if ($anio == '') $anio = date('Y');
//if ($mes == '') $mes = date('m');

//$subtitulo = fGetParam('pCond',"Año: ".$anio." - Mes: ".$mes);
$Tpl->assign("subtitulo",$subtitulo);


/*para consultar los detalles*/
$sSql = "SELECT com_tipocomp AS TIPO, com_numcomp AS COMPR, det_secuencia  AS SECUE, com_concepto AS CONCEP
            , com_feccontab AS FECHA, com_refoperat AS REFOP, com_emisor AS CODEM, 
            concat(b.per_Apellidos, ' ', b.per_nombres) as BODEG, com_codreceptor AS CODRE, 
            concat(p.per_Apellidos, ' ', p.per_nombres) as RECEP, det_coditem AS CODIT, 
            left(concat(act_descripcion, ' ', act_descripcion1),25) as ITEM, det_candespachada AS CANTI, 
            det_cantequivale AS CANTE, uni_abreviatura AS UNIDA,  det_costotal AS COSTO, det_valtotal AS VALOR
            ,det_ValUnitario vunit
            ,p.per_direccion direccion, p.per_ciudad ciudad, p.per_telefono1 telefono,
            p.per_ruc ruc
            FROM genclasetran 
            JOIN concomprobantes ON cla_aplicacion = 'IN' AND com_tipoComp = cla_tipoComp              
            LEFT JOIN conpersonas b ON b.per_codauxiliar = com_emisor              
            LEFT JOIN conpersonas p ON p.per_codauxiliar = com_codreceptor              
            LEFT JOIN invdetalle ON det_regnumero = com_regnumero              
            LEFT JOIN conactivos ON act_codauxiliar = det_coditem              
            LEFT JOIN genunmedida ON uni_CodUnidad = act_unimedida 
            WHERE ".$pQry."/*com_TipoComp='FA' AND com_NumComp=251*/
            ORDER  BY com_emisor, com_numcomp, com_tipocomp";

//echo $sSql;

$rs = $db->execute($sSql);
if($rs->EOF){
    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
    $ind = 0;   
    while ($ind < 22){
       $filas[$ind] = $ind + 1;
       $ind++;
    }
    $Tpl->assign("agFilas", $filas);
    
    while ($r = $rs->fetchRow()){
       $total += $r['VALOR'];
       
    }
    //echo $total."------";
    $letras = num2letras($total, false, 2, 2, " Dolares", " ctvs. ");//num2letras($total,false,1,2);
    $Tpl->assign("letras", $letras);
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    
    if (!$Tpl->is_cached('InTrTr_factura.tpl')) {
            }
    
            $Tpl->display('InTrTr_factura.tpl');
}
?>