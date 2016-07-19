<?php
/*
*   InTrTr_karprodsemsum_deufis.rpt.php: Resumen de Deudores de carton / fisico
*   @author     Fausto Astudillo
*   @param      integer		pQryTar  Condición de búsqueda
*   @output     contenido pdf del reporte.
*/
//error_reporting(E_ALL);

//include("../LibPhp/ezPdfReport.php");


/**
*   Definicion de Query que selecciona los datos a presentar. Todos los movimientos previos a la fecha de corte,
*   y no se encuentren liquidados ó la fecha de liquidacion sea posterior sl corte
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
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

include ("../../AAA_SEGURIDAD/LibPhp/excelOut.php");



$Tpl = new Smarty_AAA();
$glFlag= fGetParam('pEmpq', false);
$Tpl->debugging =fGetparam("pAppDbg",false);

function &fDefineQry(&$db, $pQry=false){
	global $gfFech;
	global $gfFech1;
	global $gsCodi;
    $slSql = "SELECT 
                concat(com_tipocomp, '-', com_numcomp) AS COMPR,
                com_feccontab AS FECHA,
                com_refoperat AS SEM,
                concat(p.per_Apellidos, ' ', p.per_nombres) as RECEP,
                det_coditem AS COD,
                left(concat(act_descripcion, ' ', act_descripcion1),25) as ITEM,
                uni_abreviatura AS UNI,
		(det_candespachada * cla_claTransaccion * (-1)) AS CANTID,
                (det_costotal * cla_claTransaccion * (-1))  AS COSTO
            FROM genclasetran JOIN concomprobantes ON cla_aplicacion = 'IN' AND com_tipoComp = cla_tipoComp
             LEFT JOIN conpersonas b ON b.per_codauxiliar = com_emisor
             LEFT JOIN conpersonas p ON p.per_codauxiliar = com_codreceptor
             LEFT JOIN invdetalle ON det_regnumero = com_regnumero
             LEFT JOIN conactivos ON act_codauxiliar = det_coditem
             LEFT JOIN conperiodos ON per_aplicacion = 'LI' and per_numperiodo = com_numproceso
              JOIN genunmedida ON uni_CodUnidad = act_unimedida
			 WHERE com_tipocomp IN ('EP', 'DV')  AND 
			 	  ((com_feccontab  BETWEEN '$gfFech' AND '$gfFech1' and com_numproceso <= 0  ) OR
		     	   (com_feccontab  BETWEEN '$gfFech' AND '$gfFech1' AND ( per_fecfinal BETWEEN '$gfFech' AND '$gfFech1' )
			))" ;
//	   (com_feccontab  <= '$gfFech' AND ('$gfFech' between per_fecinicial and per_fecfinal  OR  per_fecINICIAL > '$gfFech' )			
//    if ($pQry) $slSql .= " AND com_refoperat <= 34 and (com_numproceso > 34 OR com_numproceso < 1"  . $pQry ;
    $slSql .= (strlen($pQry) > 2)?  " AND " . $pQry : "" ;
    $slSql .=  (strlen($gsCodi) > 2)? " AND com_codreceptor = " . $gsCodi : "" ;
    $slSql .= " GROUP BY RECEP,ITEM" ;
    $slSql .= " ORDER BY RECEP,ITEM" ;
    $rsLiq = $db->Execute($slSql);
    IF (!$rsLiq) fErrorPage('','NO SE GENERARON LOS DETALLES DE INVENTARIO', true,false);
    return $rsLiq;
}
/** Process the Report Header
*   You can access any property / method from ezPdfReport Object using var $rpt and group data from variable $group received as parameters
*   To put any text, line, rectangle, etc into your report, use the object $rpt->pdf and its "ez functions" (see ezPdf manual),
*   be care of functions that don´t move the insertion point to void text overlapping
*   Note: This function REDEFINES the top margin.
*   @access public
*   @param  object      $rpt        Reference to current report object
*   @param  object      $hdr        Reference to current header report object
*   @return void
**/
    
/*    
    
function before_header(&$rpt, &$hdr){
    $ilTxtSize=10;  //
    $ilLeading=0;  //
    include_once ("RptHeader.inc.php");
  }
/** CAbecera de gruop COMPR
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
  

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
include ("../../AAA_SEGURIDAD/LibPhp/pie.php");

 
$slQry   = fGetParam('pQryCom', false);
$gfFech  = fGetParam('pFecI', false);
$gfFech1  = fGetParam('pFecF', false);
$gsCodi  = fGetParam('com_CodReceptor', false);

//echo "CODIGO: " . $gsCodi;

//*****************
$db =& fConexion();
set_time_limit (0) ;

$rs =& fDefineQry($db, $slQry );

//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Helvetica';


$glProTxt='';
$glVapTxt='';
$glMarTxt='';

$tplFile = 'InTrTr_karprodsemsum_deufis1.tpl';

$gstitle= "DETALLE DE INVENTARIO";
$Tpl->assign("title1",$gstitle);

$gsSubt= "CORTE DE " . $gfFech . " HASTA " . $gfFech1;
$Tpl->assign("subTitle",$gsSubt);

//$gsSubt= " " . fGetParam("pCond", "-");
//$Tpl->assign("gsSubTitul", $gsSubt );
//$Tpl->assign("gsNumCols", 7);
$Tpl->assign("gsNumCols", 7);
$aDet =& SmartyArray($rs);
$Tpl->assign("agData", $aDet);

$Tpl->display($tplFile);

?>



