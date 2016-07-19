<?php
/*
*   @rev	esl	31/oct/2012	Formulario de Impresion de Retenciones para Baloschi (Matricial)
*/
ob_start("ob_gzhandler");
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");
include("adoConn.inc.php");
require('Smarty.class.php');
include('tohtml.inc.php'); 
class Smarty_AAA extends Smarty {
   function Smarty_AAA()
   {
        $this->Smarty();
        $this->template_dir = './';
	//$this->template_dir = '../templates';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = fGetParam("pSmtDbg",false);
   }
}

include "../LibPhp/excelOut.php";
$Tpl = new Smarty_AAA();
$glFlag= fGetParam('pEmpq', false);
$Tpl->debugging =fGetparam("pAppDbg",false);
/*
 ********************************
*/

/*
 *	Define la instruccion sql a ejecutarse. Genera tambien los arreglos con las cabeceras de la tabla, con
 *	agrupamiento de cabecera
 **/
function &fDefineQry(&$db, $pQry=false)
{
    $db->Execute("SET lc_time_names = es_EC ");
    $ilNumProceso= fGetParam('pro_ID', 0);
    $alSql = Array();
    $alSql[] = "SELECT 	ID as ID,
			idProvFact COD,
			concat(pro.per_Apellidos, ' ', pro.per_Nombres) NOM,
			UPPER(date_format(fechaEmiRet1,'%d-%M-%y')) FEC,
			year(fechaRegistro) PER,
			ifNULL(pro.per_Ruc,'********') as RUC,
			pro.per_Direccion as Direcc,
			concat(ifNULL(pro.per_Direccion,' '), '  / Telf:', ifNULL(pro.per_Telefono1,' ')) as DIR,
			concat(establecimiento, '-', puntoEmision, '-', secuencial) as  FAC,
			LEFT(UPPER(tco.tab_Descripcion),24) as TIP,
			baseImpGrav as BIV,
			civ.tab_porcentaje as PIV,
			montoIva as MIV,
			montoIvaBienes as BIB,
			porRetBienes TIB,
			LEFT(prb.tab_Descripcion,3) TIB2,
			prb.tab_porcentaje as PIB,
			prb.tab_Descripcion AS DIB,
			valorRetBienes as MIB,
			montoIvaServicios as BIS,
			porRetServicios TIS,
			LEFT(prs.tab_Descripcion,3) TIS2,
			prs.tab_porcentaje as PIS,
			prs.tab_Descripcion AS DIS,
			valorRetServicios as MIS,
			BaseImpAir as BIR,
			cra.tab_txtData AS TIR,
			cra.tab_porcentaje as PIR,
			cra.tab_Descripcion AS DIR_, valRetAir as MIR,
			cmp.com_Concepto as CONCEP,
				    BaseImpAir2 as BIR2, cr2.tab_txtData AS TIR2, cr2.tab_porcentaje as PIR2, cr2.tab_Descripcion AS DIR2, valRetAir2 as MIR2,
				    BaseImpAir3 as BIR3, cr3.tab_txtData AS TIR3, cr3.tab_porcentaje as PIR3, cr3.tab_Descripcion AS DIR3, valRetAir3 as MIR3,
				    valRetAir + valRetAir2+ valRetAir3+ valorRetBienes + valorRetServicios as TOT
		    FROM fiscompras fco
				    LEFT JOIN fistablassri sus ON sus.tab_CodTabla = '3'  AND fco.codSustento +0  = sus.tab_Codigo +0
				    LEFT JOIN fistablassri tco ON tco.tab_CodTabla = '2'  AND fco.tipoComprobante +0 = tco.tab_Codigo
				    LEFT JOIN fistablassri civ ON civ.tab_CodTabla = '4'  AND fco.porcentajeIva = civ.tab_Codigo
				    LEFT JOIN fistablassri pic ON pic.tab_CodTabla = '6'  AND fco.porcentajeIce = pic.tab_Codigo
				    LEFT JOIN fistablassri prb ON prb.tab_CodTabla = '5a' AND fco.porRetBienes = prb.tab_Codigo
				    LEFT JOIN fistablassri prs ON prs.tab_CodTabla = '5'  AND fco.porRetServicios = prs.tab_Codigo
				    LEFT JOIN fistablassri cra ON cra.tab_CodTabla = '10' AND fco.codRetAir = cra.tab_Codigo
				    LEFT JOIN fistablassri cr2 ON cr2.tab_CodTabla = '10' AND fco.codRetAir2 = cr2.tab_Codigo
				    LEFT JOIN fistablassri cr3 ON cr3.tab_CodTabla = '10' AND fco.codRetAir3 = cr3.tab_Codigo
				    LEFT JOIN fistablassri ccm ON civ.tab_CodTabla = '2'  AND fco.docModificado = ccm.tab_Codigo
				    LEFT JOIN fistablassri tra ON tra.tab_CodTabla = 'A'  AND fco.tipoTransac = tra.tab_Codigo
				    LEFT JOIN conpersonas  pro ON pro.per_CodAuxiliar = fco.codProv
				    LEFT JOIN genparametros par ON par.par_clave= 'TIPID' AND par.par_secuencia = pro.per_tipoID
				    LEFT JOIN conpersonas  pv2 ON pv2.per_CodAuxiliar = fco.idProvFact
				    LEFT JOIN genparametros pm2 ON pm2.par_clave= 'TIPID' AND pm2.par_secuencia = pv2.per_tipoID
				    LEFT JOIN concomprobantes cmp ON com_NumRetenc	= ID
                 WHERE " . $pQry . " AND valRetAir + valorRetBienes + valorRetServicios  > 0";
    $rs= fSQL($db, $alSql);
    return $rs;
}

set_time_limit (0) ;
$db->debug=fGetParam("pAdoDbg", 0);
$slQry   = " ID = " . fGetParam('ID', false);
$rs = fDefineQry($db, $slQry );
$tplFile = 'CoRtTr_comprob_Matricial_Bal.tpl';
$Tpl->assign("gsNumCols", 6);
$Tpl->assign("gsEmpresa", $_SESSION["g_empr"]);
$gsSubt= fGetParam("pCond", "-");
$Tpl->assign("gsSubTitul", $gsSubt );
$aDet =& SmartyArray($rs);
if (count($aDet) <1){
   fErrorPage("NO EXISTE INFORMACION PARA LA CONDICION ESPECIFICADA");
}
$Tpl->assign("agData", $aDet);
//if (!$Tpl->is_cached($tplFile)) {
//}
$Tpl->display($tplFile);

?>
