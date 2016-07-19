<?php
/*    Reporte de cierre de caja. Formato HTML
 *    @param   integer  pFecIni     Fecha de Inicio para consulta
 *    @param   integer  pFecFin     Fecha Final de rango
 *
 */
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

//if (fGetparam("pExcel",false)){
//   header("Content-Type:  application/vnd.ms-excel");
//   header("Expires: 0");
//   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//}
include("../LibPhp/excelOut.php");

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$glFlag= fGetParam('pEmpq', false);
$pQry = fGetParam('pQryCom','');


$opc = fGetParam('pOpc',1);
//$fecFin = fGetParam('pFecFin',Date);


$fecIni = fGetParam('fIni',Date);
$fecFin = fGetParam('fFin',Date);
$subtitulo =  "Desde: ".$fecIni." - Hasta: ".$fecFin;

$Tpl->assign("subtitulo",$subtitulo);

$Tpl->assign("opc",$opc);


//consulta de empresas
$slSql = "select emp_descripcion empresa,emp_basedatos base from seguridad.segempresas
            where emp_estado=1 and emp_consolidacion like '%C%'
            /*emp_grupo='09' and emp_idempresa like '9%'
            and emp_descripcion not like 'EMPRESA%' and emp_descripcion not like '%INVENTARIO%'
            and emp_descripcion not like 'LATIN%'*/
            order by emp_descripcion";
$rs3 = $db->execute($slSql);
$rs3->MoveFirst();
while ($r3 = $rs3->fetchRow()){

    /*$tSql = "select par_Secuencia cod, par_Descripcion txt from genparametros
                                            where par_clave='ESDOC'
                                        order by par_Secuencia";
    $rs2 = $db->execute($tSql);
    $rs2->MoveFirst();
    $col = 0;
    $select = "";
    while ($r2 = $rs2->fetchRow()){
        //echo "<br/>".$r3['base']." - ".$r2['txt'];
        $select  .= ",(select sum(det_valcredito) total from ".$r3['base'].".concheques_cab ca
                    join ".$r3['base'].".concheques_det de on ca.idbatch=de.idbatch and tipo=1 
                    join ".$r3['base'].".condetalle det2 on de.com_regnum = det2.det_regnumero and de.det_secuencia=det2.det_secuencia
	where ca.operacion = ".$r2['cod']." and det_ValCredito<>0 and det_idauxiliar=per_CodAuxiliar) ".$r2['txt']."";
            
        $cuenta[$col++] = $r2['txt'];
    }*/
    $select = "";
    $select  .= ",(select /*sum(det_valdebito)-*/sum(det_ValCredito) total from ".$r3['base'].".concheques_cab ca
                    join ".$r3['base'].".concheques_det de on ca.idbatch=de.idbatch and tipo=1 
                    join ".$r3['base'].".condetalle det2 on de.com_regnum = det2.det_regnumero and de.det_secuencia=det2.det_secuencia
	where ca.operacion = 0 and det_ValCredito<>0 and det_idauxiliar=per_CodAuxiliar
            and de.com_regnum in (select com_regnum from ".$r3['base'].".concheques_cab ca 
                join ".$r3['base'].".concheques_det de on ca.idbatch=de.idbatch and tipo=1 
                where operacion=1 and fecRegistro between '".$fecIni."' and  '".$fecFin."')) NA";
        
    $select  .= ",(select /*sum(det_valdebito)-*/sum(det_ValCredito) total from ".$r3['base'].".concheques_cab ca
                    join ".$r3['base'].".concheques_det de on ca.idbatch=de.idbatch and tipo=1 
                    join ".$r3['base'].".condetalle det2 on de.com_regnum = det2.det_regnumero and de.det_secuencia=det2.det_secuencia
	where ca.operacion = 1 and det_ValCredito<>0 and det_idauxiliar=per_CodAuxiliar
            and de.com_regnum in (select com_regnum from ".$r3['base'].".concheques_cab ca 
                join ".$r3['base'].".concheques_det de on ca.idbatch=de.idbatch and tipo=1 
                where operacion=1 and fecRegistro between '".$fecIni."' and  '".$fecFin."')) Emitido";
                
    $select  .= ",(select /*sum(det_valdebito)-*/sum(det_ValCredito) total from ".$r3['base'].".concheques_cab ca
                    join ".$r3['base'].".concheques_det de on ca.idbatch=de.idbatch and tipo=1 
                    join ".$r3['base'].".condetalle det2 on de.com_regnum = det2.det_regnumero and de.det_secuencia=det2.det_secuencia
	where ca.operacion = 2 and det_ValCredito<>0 and det_idauxiliar=per_CodAuxiliar
            and de.com_regnum in (select com_regnum from ".$r3['base'].".concheques_cab ca 
                join ".$r3['base'].".concheques_det de on ca.idbatch=de.idbatch and tipo=1 
                where operacion=1 and fecRegistro between '".$fecIni."' and  '".$fecFin."')) Confirmado";
                
    $select  .= ",(select /*sum(det_valdebito)-*/sum(det_ValCredito) total from ".$r3['base'].".concheques_cab ca
                    join ".$r3['base'].".concheques_det de on ca.idbatch=de.idbatch and tipo=1 
                    join ".$r3['base'].".condetalle det2 on de.com_regnum = det2.det_regnumero and de.det_secuencia=det2.det_secuencia
	where ca.operacion = 4 and det_ValCredito<>0 and det_idauxiliar=per_CodAuxiliar
            and de.com_regnum in (select com_regnum from ".$r3['base'].".concheques_cab ca 
                join ".$r3['base'].".concheques_det de on ca.idbatch=de.idbatch and tipo=1 
                where operacion=1 and fecRegistro between '".$fecIni."' and  '".$fecFin."')) Reconfirmado";
                
    $select  .= ",(select /*sum(det_valdebito)-*/sum(det_ValCredito) total from ".$r3['base'].".concheques_cab ca
                    join ".$r3['base'].".concheques_det de on ca.idbatch=de.idbatch and tipo=1 
                    join ".$r3['base'].".condetalle det2 on de.com_regnum = det2.det_regnumero and de.det_secuencia=det2.det_secuencia
	where ca.operacion = 3 and det_ValCredito<>0 and det_idauxiliar=per_CodAuxiliar
            and de.com_regnum in (select com_regnum from ".$r3['base'].".concheques_cab ca 
                join ".$r3['base'].".concheques_det de on ca.idbatch=de.idbatch and tipo=1 
                where operacion=1 and fecRegistro between '".$fecIni."' and  '".$fecFin."')) Pagado"; 
            
    
    
    $sSql .= " select '".$r3['empresa']."' empresa,per_Apellidos banco".$select."
        from ".$r3['base'].".conpersonas  
        JOIN ".$r3['base'].".concategorias ON  cat_codauxiliar = per_codauxiliar  and cat_Categoria in (10)
        where per_codauxiliar in
            (select det_idauxiliar total from ".$r3['base'].".concheques_cab ca 
                    join ".$r3['base'].".concheques_det de on ca.idbatch=de.idbatch and tipo=1 
                    join ".$r3['base'].".condetalle det2 on de.com_regnum = det2.det_regnumero and de.det_secuencia=det2.det_secuencia 
                    where ca.operacion = 1 and det_ValCredito<>0 and fecRegistro between '".$fecIni."' and  '".$fecFin."')
                    
         union ";
        
    
}
$sSql = substr($sSql, 0, (strlen ($sSql)-6));
//echo "<br/><br/>".$sSql;

$sSql .= " order by banco,empresa "; 
        

//echo "<br/><br/>".$sSql;
$rs = $db->execute($sSql);
//$rs->MoveFirst();

    $cuenta[$col++] = "NA";
    $cuenta[$col++] = "Emitido";
    $cuenta[$col++] = "Confirmado";
    $cuenta[$col++] = "Reconfirmado";
    $cuenta[$col++] = "Saldo por Entregar";
    $cuenta[$col++] = "Confirmado";
    $cuenta[$col++] = "Pagado";
    $cuenta[$col++] = "Flotante";
$col = ($col-1)*2;
//echo $col;
$Tpl->assign("agCab", $cuenta);
$Tpl->assign("agTot", $col+2);

//echo "<br/>".count($agSaldos);
//echo "<br/>".$agSaldos[102]['dep'];

//$Tpl->assign("agSaldos", $agSaldos);
//$acumula = 0;
//$Tpl->assign("acumula",$acumula);


//echo basename($_SERVER[ "PHP_SELF"],".php");
$filename = basename($_SERVER[ "PHP_SELF"],".php");
$Tpl->assign("agArchivo", $filename);

//$rs = $db->execute($sSql . $slFrom);
if($rs->EOF){//0 == count($agSaldos)){
    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
//    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
//    
    if (!$Tpl->is_cached('CoTrTr_chequeEstadoEmp.tpl')) {
            }
//    
            $Tpl->display('CoTrTr_chequeEstadoEmp.tpl');
}
?>