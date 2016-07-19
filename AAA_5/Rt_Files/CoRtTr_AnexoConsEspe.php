<?php

/**
* Codigo para generar la consulta especifica de un anexo
* Recibe como parametros :
* 	- Id del Anexo
* @package      AAA
* @subpackage   Contabilidad
* @Author       Gina Franco
* @Date         22/04/09
* 				
*/
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

/**
*   Calcula el saldo de una cuenta contable y su auxiliar
*   @param      $pRNum      Integer     Nùmero de registro del comprobante
*   @param      $pConcep    String      Concepto de la transaccion
**/
function fProceso(){
    global $db, $cla, $olEsq;
    $ID = fGetParam('idAnexo', '0') ;
    /*$trSql = "SELECT fco.* , 
            tra.tab_Codigo AS tra_Codigo, concat(tra.tab_Codigo, '.- ',tra.tab_Descripcion) AS tra_Descripcion, tra.tab_TxtData AS tra_CodCompr, tra.tab_TxtData2 AS tra_Secuencial, 
            tid.tab_Codigo AS tid_Codigo, concat(tid.tab_Codigo, '.- ',tid.tab_Descripcion) AS tid_Descripcion, 
            tco.tab_Codigo AS tco_Codigo, concat(tco.tab_Codigo, '.- ',tco.tab_Descripcion) AS tco_Descripcion, tco.tab_TxtData AS tco_Secuencial, tco.tab_TxtData2 AS tco_Sustento, 
             civ.tab_Codigo AS civ_codigo, concat(civ.tab_Codigo, '.- ',civ.tab_Descripcion) AS civ_Descripcion, civ.tab_Porcentaje AS civ_Porcent, civ.tab_FecInicio AS civ_FecInic, civ.tab_FecFinal AS civ_FecFin, 
            sus.tab_Codigo AS sus_Codigo, concat(sus.tab_Codigo, '.- ',sus.tab_Descripcion) AS sus_Descripcion, sus.tab_TxtData AS sus_CodCompr, 
            pic.tab_Codigo AS pic_Codigo, concat(pic.tab_Codigo, '.- ',pic.tab_Descripcion) AS pic_Descripcion, pic.tab_Porcentaje AS pic_Porcent, 
            prb.tab_Codigo AS prb_Codigo, concat(prb.tab_Codigo, '.- ',prb.tab_Descripcion) AS prb_Descripcion, prb.tab_Porcentaje AS prb_Porcent, 
            prs.tab_Codigo AS prs_Codigo, concat(prs.tab_Codigo, '.- ',prs.tab_Descripcion) AS prs_Descripcion, prs.tab_Porcentaje AS prs_Porcent, 
            cra.tab_Codigo AS cra_Codigo, concat(cra.tab_Codigo, '.- ',cra.tab_Descripcion) AS cra_Descripcion, cra.tab_Porcentaje AS cra_Porcent, cra.tab_FecInicio AS cra_FecIni, 
            cra.tab_FecFinal AS cra_FecFin, cra.tab_IndProceso AS cra_Proceso, 
             cr2.tab_Codigo AS cr2_Codigo, concat(tra.tab_Codigo, '.- ',cr2.tab_Descripcion) AS cr2_Descripcion, cr2.tab_Porcentaje AS cr2_Porcent, 
            cr3.tab_Codigo AS cr3_Codigo, concat(tra.tab_Codigo, '.- ',cr3.tab_Descripcion) AS cr3_Descripcion, cr3.tab_Porcentaje AS cr3_Porcent, 
            ccm.tab_Codigo AS ccm_Codigo, concat(tra.tab_Codigo, '.- ',ccm.tab_Descripcion) AS ccm_Descripcion, 
            UCASE(ltrim(concat(ifnull(pro.per_Ruc,'-----------------'), ' ', ifnull(pro.per_Apellidos,'-'), ' ',  ifnull(pro.per_Nombres,''), ' [',pro.per_codAuxiliar, ']'))) as 	txt_ProvDescripcion, 
            ifnull(pro.per_Ruc,'') as txt_rucProv, 
            ifnull(par.par_Valor2,'') as txt_tpIdProv, 
            UCASE(ltrim(concat(ifnull(pv2.per_Ruc,'-----------------'), ' ', ifnull(pv2.per_Apellidos,'-'), ' ',  ifnull(pv2.per_Nombres,''), ' [',pv2.per_codAuxiliar, ']'))) as 	txt_ProvDescripcionFact, 
            ifnull(pv2.per_Ruc,'') as txt_rucProvFact, 
            ifnull(pm2.par_Valor2,'') as txt_tpIdProvFac, 
            sri.aut_FecEmision as txt_FecEmision,  
            sri.aut_FecVigencia as txt_FecVigencia, 
            sri.aut_NroInicial, 
            sri.aut_Nrofinal, 
            concat(tce.tab_Codigo, '.- ',tce.tab_Descripcion) AS tce_Descripcion, 
             com_TipoComp, com_NumComp, com_RegNumero
	     ,condetalle.det_CodCuenta,condetalle.det_IDAuxiliar
	     ,sri2.aut_FecEmision as txt_FecEmisionRet  
             ,sri2.aut_FecVigencia as txt_FecVigenciaRet
            FROM fiscompras fco 
            LEFT JOIN fistablassri sus ON sus.tab_CodTabla = \"3\"  AND fco.codSustento +0  = sus.tab_Codigo +0  
            LEFT JOIN fistablassri tco ON tco.tab_CodTabla = \"2\"  AND fco.tipoComprobante +0 = tco.tab_Codigo 
            LEFT JOIN fistablassri tce ON tce.tab_CodTabla = \"2\"  AND fco.facturaExportacion +0 = tce.tab_Codigo 
            LEFT JOIN fistablassri civ ON civ.tab_CodTabla = \"4\"  AND fco.porcentajeIva = civ.tab_Codigo 
            LEFT JOIN fistablassri pic ON pic.tab_CodTabla = \"6\"  AND fco.porcentajeIce = pic.tab_Codigo
            LEFT JOIN fistablassri prb ON prb.tab_CodTabla = \"5a\" AND fco.porRetBienes = prb.tab_Codigo 
            LEFT JOIN fistablassri prs ON prs.tab_CodTabla = \"5\"  AND fco.porRetServicios = prs.tab_Codigo 
             LEFT JOIN fistablassri cra ON cra.tab_CodTabla = \"10\" AND fco.codRetAir = cra.tab_Codigo 
             LEFT JOIN fistablassri cr2 ON cr2.tab_CodTabla = \"10\" AND fco.codRetAir2 = cr2.tab_Codigo 
              LEFT JOIN fistablassri cr3 ON cr3.tab_CodTabla = \"10\" AND fco.codRetAir3 = cr3.tab_Codigo 
            LEFT JOIN fistablassri ccm ON civ.tab_CodTabla = \"2\"  AND fco.docModificado = ccm.tab_Codigo 
            LEFT JOIN fistablassri tra ON tra.tab_CodTabla = \"A\"  AND fco.tipoTransac = tra.tab_Codigo 
            LEFT JOIN conpersonas  pro ON pro.per_CodAuxiliar = fco.codProv 
            LEFT JOIN genparametros par ON par.par_clave= \"TIPID\" AND par.par_secuencia = pro.per_tipoID 
            LEFT JOIN conpersonas  pv2 ON pv2.per_CodAuxiliar = fco.idProvFact 
            LEFT JOIN genparametros pm2 ON pm2.par_clave= \"TIPID\" AND pm2.par_secuencia = pv2.per_tipoID 
            LEFT JOIN fistablassri tid ON tid.tab_CodTabla = \"8\"  AND par.par_Valor2 = tid.tab_Codigo 
            LEFT JOIN genautsri sri ON aut_ID = autorizacion
	    LEFT JOIN genautsri sri2 ON sri2.aut_ID = autRetencion1
            LEFT JOIN  concomprobantes ON com_numretenc = fco.ID
	    inner join condetalle on concomprobantes.com_RegNumero=condetalle.det_RegNumero
	    inner join concuentas on concuentas.cue_Clase=5 and condetalle.det_CodCuenta=concuentas.Cue_CodCuenta 
            where fco.ID = " . $ID;*/
 $trSql = "SELECT fco.* , 
            tra.tab_Codigo AS tra_Codigo, CONCAT(tra.tab_Codigo, '.- ',tra.tab_Descripcion) AS tra_Descripcion, tra.tab_TxtData AS tra_CodCompr, tra.tab_TxtData2 AS tra_Secuencial, 
            tid.tab_Codigo AS tid_Codigo, CONCAT(tid.tab_Codigo, '.- ',tid.tab_Descripcion) AS tid_Descripcion, 
            tco.tab_Codigo AS tco_Codigo, CONCAT(tco.tab_Codigo, '.- ',tco.tab_Descripcion) AS tco_Descripcion, tco.tab_TxtData AS tco_Secuencial, tco.tab_TxtData2 AS tco_Sustento, 
             civ.tab_Codigo AS civ_codigo, CONCAT(civ.tab_Codigo, '.- ',civ.tab_Descripcion) AS civ_Descripcion, civ.tab_Porcentaje AS civ_Porcent, civ.tab_FecInicio AS civ_FecInic, civ.tab_FecFinal AS civ_FecFin, 
            sus.tab_Codigo AS sus_Codigo, CONCAT(sus.tab_Codigo, '.- ',sus.tab_Descripcion) AS sus_Descripcion, sus.tab_TxtData AS sus_CodCompr, 
            pic.tab_Codigo AS pic_Codigo, CONCAT(pic.tab_Codigo, '.- ',pic.tab_Descripcion) AS pic_Descripcion, pic.tab_Porcentaje AS pic_Porcent, 
            prb.tab_Codigo AS prb_Codigo, CONCAT(prb.tab_Codigo, '.- ',prb.tab_Descripcion) AS prb_Descripcion, prb.tab_Porcentaje AS prb_Porcent, 
            prs.tab_Codigo AS prs_Codigo, CONCAT(prs.tab_Codigo, '.- ',prs.tab_Descripcion) AS prs_Descripcion, prs.tab_Porcentaje AS prs_Porcent, 
            cra.tab_Codigo AS cra_Codigo, CONCAT(cra.tab_Codigo, '.- ',cra.tab_Descripcion) AS cra_Descripcion, cra.tab_Porcentaje AS cra_Porcent, cra.tab_FecInicio AS cra_FecIni, 
            cra.tab_FecFinal AS cra_FecFin, cra.tab_IndProceso AS cra_Proceso, 
             cr2.tab_Codigo AS cr2_Codigo, CONCAT(tra.tab_Codigo, '.- ',cr2.tab_Descripcion) AS cr2_Descripcion, cr2.tab_Porcentaje AS cr2_Porcent, 
            cr3.tab_Codigo AS cr3_Codigo, CONCAT(tra.tab_Codigo, '.- ',cr3.tab_Descripcion) AS cr3_Descripcion, cr3.tab_Porcentaje AS cr3_Porcent, 
            ccm.tab_Codigo AS ccm_Codigo, CONCAT(tra.tab_Codigo, '.- ',ccm.tab_Descripcion) AS ccm_Descripcion, 
            UCASE(LTRIM(CONCAT(IFNULL(pro.per_Ruc,'-----------------'), ' ', IFNULL(pro.per_Apellidos,'-'), ' ',  IFNULL(pro.per_Nombres,''), ' [',pro.per_codAuxiliar, ']'))) AS 	txt_ProvDescripcion, 
            IFNULL(pro.per_Ruc,'') AS txt_rucProv, 
            IFNULL(par.par_Valor2,'') AS txt_tpIdProv, 
            UCASE(LTRIM(CONCAT(IFNULL(pv2.per_Ruc,'-----------------'), ' ', IFNULL(pv2.per_Apellidos,'-'), ' ',  IFNULL(pv2.per_Nombres,''), ' [',pv2.per_codAuxiliar, ']'))) AS 	txt_ProvDescripcionFact, 
            IFNULL(pv2.per_Ruc,'') AS txt_rucProvFact, 
            IFNULL(pm2.par_Valor2,'') AS txt_tpIdProvFac, 
            sri.aut_FecEmision AS txt_FecEmision,  
            sri.aut_FecVigencia AS txt_FecVigencia, 
            sri.aut_NroInicial, 
            sri.aut_Nrofinal, 
            CONCAT(tce.tab_Codigo, '.- ',tce.tab_Descripcion) AS tce_Descripcion, 
             com_TipoComp, com_NumComp, com_RegNumero
	     ,condetalle.det_CodCuenta,condetalle.det_IDAuxiliar
	     ,sri2.aut_FecEmision AS txt_FecEmisionRet  
             ,sri2.aut_FecVigencia AS txt_FecVigenciaRet
FROM fiscompras fco 
LEFT JOIN fistablassri sus ON sus.tab_CodTabla = '3' AND fco.codSustento +0 = sus.tab_Codigo +0 
LEFT JOIN fistablassri tco ON tco.tab_CodTabla = '2' AND fco.tipoComprobante +0 = tco.tab_Codigo 
LEFT JOIN fistablassri tce ON tce.tab_CodTabla = '2' AND fco.facturaExportacion +0 = tce.tab_Codigo 
LEFT JOIN fistablassri civ ON civ.tab_CodTabla = '4' AND fco.porcentajeIva = civ.tab_Codigo 
LEFT JOIN fistablassri pic ON pic.tab_CodTabla = '6' AND fco.porcentajeIce = pic.tab_Codigo 
LEFT JOIN fistablassri prb ON prb.tab_CodTabla = '5a' AND fco.porRetBienes = prb.tab_Codigo 
LEFT JOIN fistablassri prs ON prs.tab_CodTabla = '5' AND fco.porRetServicios = prs.tab_Codigo 
LEFT JOIN fistablassri cra ON cra.tab_CodTabla = '10' AND fco.codRetAir = cra.tab_Codigo 
LEFT JOIN fistablassri cr2 ON cr2.tab_CodTabla = '10' AND fco.codRetAir2 = cr2.tab_Codigo 
LEFT JOIN fistablassri cr3 ON cr3.tab_CodTabla = '10' AND fco.codRetAir3 = cr3.tab_Codigo 
LEFT JOIN fistablassri ccm ON civ.tab_CodTabla = '2' AND fco.docModificado = ccm.tab_Codigo 
LEFT JOIN fistablassri tra ON tra.tab_CodTabla = 'A' AND fco.tipoTransac = tra.tab_Codigo 
LEFT JOIN conpersonas pro ON pro.per_CodAuxiliar = fco.codProv 
LEFT JOIN genparametros par ON par.par_clave= 'TIPID' AND par.par_secuencia = pro.per_tipoID 
LEFT JOIN conpersonas pv2 ON pv2.per_CodAuxiliar = fco.idProvFact 
LEFT JOIN genparametros pm2 ON pm2.par_clave= 'TIPID' AND pm2.par_secuencia = pv2.per_tipoID 
LEFT JOIN fistablassri tid ON tid.tab_CodTabla = '8' AND par.par_Valor2 = tid.tab_Codigo 
 LEFT JOIN genautsri sri ON sri.aut_ID = autorizacion  AND sri.aut_TipoDocum = tipocomprobante
 LEFT JOIN genautsri sri2 ON sri2.aut_ID = autRetencion1  AND sri2.aut_TipoDocum  = 7  AND sri2.aut_idAuxiliar= -99 
LEFT JOIN concomprobantes ON com_tipocomp =   tra.tab_txtData3 AND com_numretenc = fco.ID 
LEFT JOIN condetalle ON concomprobantes.com_RegNumero=condetalle.det_RegNumero   AND (det_codcuenta LIKE '5%' OR det_codcuenta LIKE '6%' )
WHERE  fco.ID=" . $ID;

    //echo ('viene: '.$trSql);
    $alInfo=$db->GetRow($trSql);//toda la informacion del anexo
	
    //$alInfo["ban_PxmoChq"]=$ilNumChq;

    return array("info" => $alInfo );
    
}
/********************************************************************************************************
 *					Inicio de Proceso
 *
 ********************************************************************************************************/
$cla = NULL;
$alPar = NULL;
//$ilNumChq = NULL;
//$ilSecue= 1;
$ogSal = fProceso();
print(json_encode($ogSal));
?>
