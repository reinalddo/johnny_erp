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


//$anio = fGetParam('s_Anio',date('Y'));
//$mes = fGetParam('s_Mes',date('m'));

//if ($anio == '') $anio = date('Y');
//if ($mes == '') $mes = date('m');

//$subtitulo = fGetParam('pCond',"A�o: ".$anio." - Mes: ".$mes);
$Tpl->assign("subtitulo",$subtitulo);


/*para consultar los detalles*/
$SqLDatosEmpresa="SELECT par_ID, par_Clave, par_Descripcion, par_Valor1 FROM genparametros WHERE par_Clave LIKE 'ELO%' OR par_Clave LIKE 'EG%'";
$DatosEmpresa = $db->execute($SqLDatosEmpresa);
while ($rtemp = $DatosEmpresa->fetchRow()){
   if ("EGNOM" == $rtemp['par_Clave']){
     $Empresa_NOMBRE = $rtemp['par_Descripcion'];
   }
   elseif ("EGRUC" == $rtemp['par_Clave']){
     $Empresa_RUC ="RUC:" . $rtemp['par_Descripcion'];
   }
   elseif ("ELOGO" == $rtemp['par_Clave']){
     $Empresa_Logo =$rtemp['par_Valor1'];
   }
   elseif ("EGDIR" == $rtemp['par_Clave']){
     $Empresa_DIR =$rtemp['par_Descripcion'];
   }
   elseif ("EGCIU" == $rtemp['par_Clave']){
     $Empresa_CIU =$rtemp['par_Descripcion'];
   }
   elseif ("EGPAIS" == $rtemp['par_Clave']){
     $Empresa_PAIS =$rtemp['par_Descripcion'];
   }
   elseif ("EGTELE" == $rtemp['par_Clave']){
     $Empresa_TELE = "TEL&Eacute;FONO: ". $rtemp['par_Descripcion'];
   }
}
$Tpl->assign("Empresa_NOMBRE", $Empresa_NOMBRE);
$Tpl->assign("Empresa_RUC", $Empresa_RUC);
$Tpl->assign("Empresa_Logo", $Empresa_Logo);
$Tpl->assign("Empresa_DIR", $Empresa_DIR);
$Tpl->assign("Empresa_TELE", $Empresa_TELE);
$Tpl->assign("Empresa_UBI", $Empresa_CIU . " - " . $Empresa_PAIS);
$sSql = "select com_NumComp,com_FecTrans, com_Receptor, p.per_RUC ruc, com_Concepto, com_FecContab, 
        det_Secuencia, det_CodItem, act_descripcion descripcion, det_UniMedida, uni_Abreviatura, det_CanDespachada, 
        det_cosunitario /*det_CosUnitario*/,det_CosTotal ValTotal/*det_CosTotal*/, com_Usuario,
        /*det_IndiceIva*/ act_IvaFlag iva
        ,com_FecVencim, if(com_FecVencim > com_FecContab,DATEDIFF(com_FecVencim,com_FecContab), 0) credito
        ,com_RefOperat, emb_SemInicio semana
        ,if(act_IvaFlag = 0,det_CosTotal,0) iva0, if(act_IvaFlag = 2,det_CosTotal,0) iva12
        ,f.per_Apellidos bodega_apellidos
        ,f.per_Nombres bodega_nombres
        ,DATEDIFF(com_FecVencim,com_FecTrans) Tiempo_Entrega
        ,act_NumSerie numParte
        ,det_destino DescTotal
        ,c.com_payment formaPago
        ,g.per_Nombres NombresSolicitante
        ,g.per_Apellidos ApellidosSolicitante
        ,h.per_Nombres NombresDptoSolicitante
        ,h.per_Apellidos ApellidosDptoSolicitante
        from concomprobantes c 
                inner join invdetalle d on c.com_RegNumero=d.det_RegNumero
                inner join conactivos a on d.det_CodItem=act_CodAuxiliar
                inner join genunmedida m on d.det_UniMedida=uni_CodUnidad
                inner join conpersonas p on c.com_CodReceptor=p.per_CodAuxiliar
                inner join conpersonas g on c.com_beneficiary=g.per_CodAuxiliar
                inner join conpersonas f on c.com_emisor=f.per_CodAuxiliar
                inner join conpersonas h on c.com_embarque=h.per_CodAuxiliar
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
       $desc += $r['DescTotal'];
       $tmp = $r['formaPago'];
       $solicitante = $r['NombresSolicitante'] . " " . $r['ApellidosSolicitante'];
       $dptosolicitante = $r['ApellidosDptoSolicitante'] . " " . $r['NombresDptoSolicitante'];
       $i++;
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
    $Tpl->assign("desc", $desc);
    $Tpl->assign("solicitante", $solicitante);
    $Tpl->assign("dptosolicitante", $dptosolicitante);
    
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    $Tpl->assign("query", $pQry);
    /*Forma de Pago*/
    $SqL="SELECT par_ID, par_Clave, par_Descripcion, par_Valor1 FROM genparametros WHERE par_Clave LIKE 'PTER%' AND par_Secuencia = " . $tmp . ";";
    $Datos= $db->execute($SqL);
    $Tpl->assign("formaPago", "No aplica");
    if(!empty($Datos)){
     $tmp=$Datos->fetchRow();
     $Tpl->assign("formaPago", $tmp['par_Descripcion']);   
    }
    if (!$Tpl->is_cached('InTrTr_ordencompraoc.tpl')) {
            }
    
            $Tpl->display('InTrTr_ordencompraoc.tpl');
}
?>