<?php
error_reporting(E_ALL);
include("../LibPhp/ezPdfReport.php");
include("../LibPhp/GenCifras.php");
include("../LibPhp/ConTasas.php");
include("adodb.inc.php");

function fDefineQry(&$db,$pQry=false){ 
	$inventario="09_inventario";
	$indicador= fGetParam('ind', 'OC');
	if($indicador=='OC')
		$slSql="SELECT '". $_SESSION['g_dbase'] ."' as Empresa,com_TipoComp AS TipoComp,com_NumComp AS NumComp, det_CodItem AS Cod_Item,act_Descripcion AS Item,ROUND(det_CantEquivale) AS Cantidad,com_Receptor AS Proveedor
				FROM concomprobantes
				JOIN invdetalle ON com_RegNumero=det_RegNUmero
				JOIN conactivos ON act_CodAuxiliar =det_CodItem
				WHERE com_TipoComp='OC' AND com_RegNumero NOT IN (SELECT enl_RegNumero AS com_RegNumero FROM ".$inventario.".invenlace where enl_CodEmpresa= '". $_SESSION['g_dbase'] ."')
				ORDER BY 2,3";
	else
		$slSql="SELECT '". $_SESSION['g_dbase'] ."' as Empresa,com_TipoComp AS TipoComp,com_NumComp AS NumComp, det_CodItem AS Cod_Item,ROUND(det_CantEquivale) AS Cantidad,act_Descripcion AS Item,com_Receptor AS Proveedor
				FROM ".$inventario.".concomprobantes
				JOIN ".$inventario.".invdetalle ON com_RegNumero=det_RegNUmero
				JOIN ".$inventario.".conactivos ON act_CodAuxiliar =det_CodItem
				WHERE com_TipoComp='IB' AND com_RegNumero NOT IN (SELECT enl_RegNumero2 AS com_RegNumero FROM ".$inventario.".invenlace)
				ORDER BY 2,3";
		
	$rsLiq = $db->Execute($slSql);
    IF(!$rsLiq) fErrorPage('','NO SE GENERARON LOS DETALLES DEL REPORTE', true,false);
    return $rsLiq;
}

function before_header(&$rpt, &$hdr){
    $ilTxtSize=10; 
    $ilLeading=0;  
    include_once ("RptHeader.inc.php");

}

function before_group_Empresa (&$rpt, &$group) {
    global $db;
    global $ilPag;
    global $gfValor;
    global $firstComp;
    if (!$firstComp)  $rpt->pdf->NewPage();
    $firstComp=false;
    $rpt->pdf->y= $rpt->pdf->ez['pageHeight'] - 20;
    $rpt->pdf->leftMargin = $rpt->leftBorder;
    $rpt->pdf->y -=10;
    $rpt->rptOpt['onlyHeader']=true;
    $rpt->rptOpt['showHeadings']=2;
    $rpt->rptOpt['showLines']=3;
    $alBlankLine=array();
    $alBlankline[]['COM']=' ';
    $rpt->pdf->ezTable($alBlankLine,$rpt->colHead,'', $rpt->rptOpt);
    $rpt->rptOpt['onlyHeader']=false;
    $rpt->rptOpt['showHeadings']=0;
    $rpt->rptOpt['showLines']=0;
    $rpt->pdf->y +=8;
}

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$slQry   = fGetParam('pQryCom', false);
$db =& fConexion();
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry );
$slFontName = 'Helvetica';
$rep = new ezPdfReport($rs, array(21.5,27.8), "landscape", $slFontName, 8);
$rep->title="";
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(1,1.5,0.3,0.5);
$rep->colHead = array(
                        'TipoComp' => "TIPO COMP.",
						'NumComp' => "NUM. COMP.",
                        'Cod_Item'  => "COD. TIEM",
                        'Item' 	    => "DESCRIPCION",
                        'Cantidad' => "CANTIDAD",
                        'Proveedor'  => "PROVEEDOR"
                        );
$rep->rptOpt = array('fontSize'=>8, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);

$rep->printColHead=false;
$rep->printFooter=false;
$rep->setDefaultColPro('format', "9:2:,:."); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type

$rep->columns['Cod_Item']->type='C';
$rep->columns['NumComp']->type='C';
$rep->columns['Cantidad']->type='C';

$rep->setDefaultColOpt('justification', 'center');
$rep->setDefaultColOpt('width', 55);
$rep->colOpt['NumComp'] ['width']=60;
$rep->colOpt['Cod_Item']['width']=55;
$rep->colOpt['Item']['width']=150;
$rep->colOpt['Cantidad']['width']=55;
$rep->colOpt['Proveedor']['width']=170;
$rep->addGrp('Empresa');
    $rep->groups['Empresa']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['Empresa']->textCol='NumComp';
    
$rep->addGrp('NumComp');
    $rep->groups['NumComp']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['NumComp']->textCol='Cod_Item';          // set the column for text at resume line of group

$glProTxt='';
$glVapTxt='';
$glMarTxt='';
$firstComp = true;
$rep->run();
$rep->view($rep->title, $rep->saveFile("SIN_ENL_OC_IB_"));
?>