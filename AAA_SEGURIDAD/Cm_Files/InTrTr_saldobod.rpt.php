<?php
/*
*   Detalle de Tarjas 
*   Detalle de Tarjas 
*   @date	11/03/09    
*   @author     fah
*   @
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
        //$this->template_dir = './';
	$this->template_dir = '../templates';
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
function &fDefineQry(&$db){
    global $giPerio;
    global $gdDesde;
    global $gdHasta;
    $ilBodega=fGetParam('com_Emisor', false);
    $slConI = " com_feccontab < '" . $gdDesde . "'"; //         Condicion Inicial
    $slConD = " com_feccontab >= '" . $gdDesde . "'";//         Condicion 'Durante'
    $slConF = " com_feccontab <= '" . $gdHasta . "'";//         Condicion Final2
    $slSql = "SELECT
		     com_emisor as 'BOD',
		     act_grupo AS 'GRU',
		     det_coditem AS 'ITE',
		     concat(act_descripcion, ' ', act_descripcion1) as 'DES' ,
		     uni_abreviatura as 'UNI',
		     ' ' AS 'C99',
		     sum(if ( com_feccontab < '2009-05-12' , det_cantequivale * pro_signo, 0)) as SAN,
		     sum(if ( com_feccontab < '2009-05-12' , det_cosTotal * pro_signo, 0)) as VAN,
		     sum(if ( com_feccontab >= '2009-05-12' AND pro_signo = 1, det_cantequivale , 0)) as CIN,
		     sum(if ( com_feccontab >= '2009-05-12' AND pro_signo = 1, det_cosTotal , 0)) as VIN,
		     sum(if ( com_feccontab >= '2009-05-12' AND pro_signo = -1 , det_cantequivale , 0)) as CEG,
		     sum(if ( com_feccontab >= '2009-05-12' AND pro_signo = -1 , det_cosTotal , 0)) as VEG,
		     sum(det_cantequivale * pro_signo) as SAC,
		     sum(det_cosTotal * pro_signo) as VAC,
		     sum(det_cosTotal * pro_signo) / sum(det_cantequivale * pro_signo) as PUN,
		     par_Descripcion as grupo,
		     per_Apellidos as bodeg
	       FROM
		     invprocesos
		  JOIN concomprobantes ON pro_codproceso = 1 AND com_tipocomp = cla_tipotransacc
		  JOIN invdetalle ON det_regnumero = com_regnumero
		  JOIN conactivos ON act_codauxiliar = det_coditem
		  JOIN genunmedida ON uni_codunidad= act_unimedida 
		  JOIN conpersonas ON per_CodAuxiliar = com_emisor
		  JOIN genparametros ON par_Clave='ACTGRU' and par_Secuencia = act_grupo 
	       WHERE com_feccontab >= '2004-12-31' AND " . $slConF . "  AND (det_cantequivale <> 0 OR det_cosTotal <> 0) ".
            ($ilBodega? 'AND com_Emisor = ' . $ilBodega : '') .
            " GROUP BY com_emisor,act_grupo,det_coditem,DES,uni_abreviatura,C99,par_Descripcion,per_Apellidos
            HAVING SAN <>0 OR CIN <>0 OR CEG <>0
            ORDER BY 1,2,3
        ";
    $rsLiq = $db->Execute($slSql);
    IF(!$rsLiq) fErrorPage('','NO SE GENERARON LOS DETALLES DE INVENTARIO', true,false);
    return $rsLiq;
}

function before_header(&$rpt, &$hdr){
    $ilTxtSize=10;  //
    $ilLeading=0;  //
    include_once ("RptHeader.inc.php");
  }
function before_group_BOD (&$rpt, &$group) {
    global $db;
    global $gdDesde, $gdHasta;
    $slGrupo = fDBValor($db, 'conpersonas', "concat(per_Apellidos, ' ' , per_Nombres) as tmp_bodega", "per_codauxiliar  = " . $group->currValue);
    $slText = $group->currValue . " - " . $slGrupo;
    $rpt->pdf->y -=10;
    $rpt->pdf->eztext($slText, 10, array('justification'=>'center', 'leading'=>12));//        Putting text before group data

    $slSql= " Select if(cla_clatransaccion=1, 'INGRESOS', 'EGRESOS') as tipo,
		          MIN(com_numcomp) as minimo, MAX(com_numcomp) as maximo
		          from concomprobantes join genclasetran on cla_tipocomp = com_tipocomp
		          WHERE com_emisor = " . $group->currValue . " AND
			             com_feccontab between '" . $gdDesde . "' and '" . $gdHasta. "' " .
	              " GROUP by 1";
    $rs = $db->Execute($slSql);
/****/
}
function before_group_GRU (&$rpt, &$group) {
    global $db;
    $slGrupo = fDBValor($db, 'genparametros', 'par_descripcion', "par_Clave  = 'actgru' and PAR_SECUENCIA  = " . $group->currValue);
    $slText = $group->currValue . " - " . $slGrupo;
    $rpt->pdf->y -=15;
    $rpt->putTextWrap($rpt->leftBorder, $rpt->pdf->y, 400, 10, $slText);//        Putting text before group data
    $rpt->pdf->y +=8;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$db =& fConexion();
$db->debug=fGetParam("pAdoDbg", 0);
set_time_limit (0) ;
$gdDesde=false;
$gdHasta= false;
$gdDesde = fGetParam('pFecI', false);
$gdHasta = fGetParam('pFecF', false);
$gsTipoR = fGetParam('pTipR', false);  //   Tipo de Reporte: F=Para Toma Fisica
$iCtoFlag  = fGetParam('pCosto', false);
$rs = fDefineQry($db);
$tplFile = 'InTrTr_saldobod.tpl';
$Tpl->assign("gsNumCols", 15);
$Tpl->assign("gsEmpresa", $_SESSION["g_empr"]);
$gsSubt= fGetParam("pCond");
$Tpl->assign("gsSubTitul", $gsSubt );
$Tpl->assign("gsNumCols", 15);
$aDet =& SmartyArray($rs);
if (count($aDet) <1){
   fErrorPage("NO EXISTE INFORMACION PARA LA CONDICION ESPECIFICADA");
}
$Tpl->assign("agData", $aDet);
$Tpl->display($tplFile);

?>
