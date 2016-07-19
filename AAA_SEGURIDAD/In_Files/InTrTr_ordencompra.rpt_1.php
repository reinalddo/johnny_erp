<?php
/*    Reporte de tarjas, detalle General por Puerto, Vapor, Marca. Formato HTML
 *    @param   integer  pSem     Numero de semana a procesar
 *    @param   integer  pEmb     Numero de Embarque
 *    @param   string   PMarca   Marca
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
$pQry = fGetParam('pQryCom','');
$numero = fGetParam('regNumero','');

// JVL 140826
$stSQL = "SELECT par_descripcion FROM genparametros WHERE par_Clave = 'EGRUC' " ;
// echo $stSQL;
$subT = $db->execute($stSQL);  // fGetParam('pCond',"A�o: ".$anio." - Mes: ".$mes);
if($subT->EOF){
    fErrorPage('','PARAMETRO DE RUC NO ESTA ASIGNADO', true,false);
}else{
   $subT->MoveFirst();
   $sT = $subT->fetchRow();
   $Tpl->assign("subtitulo",'RUC:' . $sT['par_descripcion']);
}
//  Fin JVL 140826
// JVL 140826
$stSQL = "SELECT * FROM genparametros WHERE par_Clave = 'EGDIR' " ;
// echo $stSQL;
$subT = $db->execute($stSQL);  // fGetParam('pCond',"A�o: ".$anio." - Mes: ".$mes);
if($subT->EOF){
    fErrorPage('','PARAMETRO DE RUC NO ESTA ASIGNADO', true,false);
}else{
   $subT->MoveFirst();
   $sT = $subT->fetchRow();
   $Tpl->assign("direccion",$sT['par_Descripcion'] . $sT['par_Valor1'] . $sT['par_valor2'] . $sT['par_Valor3'] . $sT['par_Valor4']);
}
//  Fin JVL 140826
/*para consultar los detalles*/
$sSql = "select com_NumComp,com_FecTrans, com_Receptor, per_RUC ruc, com_Concepto, com_FecContab, 
        det_Secuencia, det_CodItem, act_descripcion descripcion, det_UniMedida, uni_Abreviatura, det_CanDespachada, 
        /*det_ValUnitario*/ det_CosUnitario ValUnitario,/*det_ValTotal*/ det_CosTotal ValTotal, com_Usuario,
        /*det_IndiceIva*/ act_IvaFlag iva
        ,com_FecVencim, if(com_FecVencim > com_FecContab,DATEDIFF(com_FecVencim,com_FecContab), 0) credito
        ,com_RefOperat, emb_SemInicio semana
        ,if(act_IvaFlag = 0,det_ValTotal,0) iva0, if(act_IvaFlag = 2,det_ValTotal,0) iva12
        from concomprobantes c 
                inner join invdetalle d on c.com_RegNumero=d.det_RegNumero
                inner join conactivos a on d.det_CodItem=act_CodAuxiliar
                inner join genunmedida m on d.det_UniMedida=uni_CodUnidad
                inner join conpersonas p on c.com_CodReceptor=per_CodAuxiliar
                left join liqembarques e on c.com_RefOperat=emb_RefOperativa
        where /*com_NumComp = 904001*/
        ".$pQry."
        order by com_FecDigita desc,det_Secuencia";

//echo $sSql;

$rs = $db->execute($sSql);
if($rs->EOF){
    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
   $ind = 0;   
   while ($ind < 20){
      $filas[$ind] = $ind + 1;
      $ind++;
   }
   //print_r($filas);
   //$aFilas =& SmartyArray($filas);
   $Tpl->assign("agFilas", $filas);
    
    while ($r = $rs->fetchRow()){
       $total += $r['ValTotal'];
       if (2 == $r['iva']){
         $iva += $r['ValTotal'];
       }
    }
    if ($iva > 0){
         $iva = ($iva * 0.12);
    }else $iva  = 0;
    
    $total += $iva;
    //echo $total."------".$iva;
    $letras = num2letras($total, false, 2, 2, " Dolares", " ctvs. ");//num2letras($total,false,1,2);
    $Tpl->assign("letras", $letras);
    $Tpl->assign("iva", $iva);
    $Tpl->assign("valorTot", $total);
    
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    
    $Tpl->assign("query", $pQry);
    
    if (!$Tpl->is_cached('InTrTr_ordencompra.tpl')) {
            }
    
            $Tpl->display('InTrTr_ordencompra.tpl');
}
?>
