<?php
/**
 *   @rev  esl	10/02/2011	Parametrizar base para inventario.
 */
error_reporting(E_ALL);
include("../LibPhp/ezPdfReport.php");
include("../LibPhp/GenCifras.php");
include("../LibPhp/ConTasas.php");
include("adodb.inc.php");

function fDefineQry(&$db,$pQry=false){
    
    // ***********************************************************
    //@rev	esl	10/02/2011	Parametrizar base para inventario.
    $baseInv = "09_inventario.";    
    $sSql = "SELECT par_Valor1 FROM genparametros WHERE par_Clave = 'IDATO' AND par_Secuencia = 1";	    
    $rs = $db->execute($sSql);
    $r = $rs->fetchRow();
    
    $baseInv = $r['par_Valor1'];
    // ***********************************************************

    $condicion= fGetParam('cond', 0);
    $slSql = "  SELECT ie.enl_CodEmpresa AS Empresa,CONCAT(c.com_TipoComp,'-',c.com_NumComp) AS NumCompOC,
                        ROUND(ic.det_ValTotal) as TotalOC,
                        ROUND(ic.det_CantEquivale) AS CantidadOC,c.com_Receptor AS Proveedor,
                        CONCAT(ci.com_TipoComp,'-',ci.com_NumComp) AS NumCompIB,
                        CONCAT(cp.per_Apellidos,' ',cp.per_Nombres) AS Bodega,ci.com_FecTrans as FechaTrans,i.det_CodItem AS Cod_Item, act.act_Descripcion AS Item,ROUND(ic.det_ValUnitario,6) as ValorUni,ROUND(i.det_CantEquivale) AS CantidadIB,
                        ROUND(ic.det_ValUnitario,6) * ROUND(i.det_CantEquivale) as SubTotal
                        FROM ".$baseInv.".invenlace ie
                        JOIN concomprobantes c ON ie.enl_RegNumero=c.com_RegNumero AND c.com_TipoComp='OC'
                        JOIN invdetalle ic ON ic.det_RegNUmero=c.com_RegNumero AND ie.enl_Secuencia=ic.det_Secuencia
                        JOIN ".$baseInv.".concomprobantes ci ON ie.enl_RegNumero2=ci.com_RegNumero AND ci.com_TipoComp='IB'
                        JOIN ".$baseInv.".invdetalle i ON i.det_RegNUmero=ci.com_RegNumero AND ie.enl_Secuencia2=i.det_Secuencia
                        JOIN ".$baseInv.".conpersonas cp ON cp.per_CodAuxiliar=ci.com_emisor
                        JOIN ".$baseInv.".conactivos act ON i.det_CodItem=act.act_CodAuxiliar AND ie.enl_CodEmpresa= '". $_SESSION['g_dbase'] ."' ".$condicion;
    $slSql .= " ORDER  BY NumCompOC DESC,Cod_Item DESC,NumCompIB DESC";
    $rsLiq = $db->Execute($slSql);
    IF(!$rsLiq) fErrorPage('','NO SE GENERARON LOS DETALLES DEL REPORTE', true,false);
    return $rsLiq;
}

function before_header(&$rpt, &$hdr){
    $ilTxtSize=10;  //
    $ilLeading=0;  //
    include_once ("RptHeader.inc.php");

}
function before_group_Empresa (&$rpt, &$group) {
    global $db;
    global $ilPag;
    global $gfValor;
    global $firstComp;
    if (!$firstComp)  $rpt->pdf->NewPage(); // Salto de pagna antes de cada comprobante FECHAING
    $firstComp=false;
    $rpt->pdf->y= $rpt->pdf->ez['pageHeight'] - 20;
    //list($slText, $slTxtRec) = fDbvalor($db,  'genclasetran', 'cla_descripcion, cla_txtReceptor', " cla_tipocomp = '" .$group->lastRec['TIPO']. "' " );
    $rpt->pdf->leftMargin = $rpt->leftBorder;
    //$slText= fDbvalor($db,  'concomprobantes', 'com_concepto', " com_tipocomp = '" .$group->lastRec['TIPO']. "' AND com_numcomp = " . $group->lastRec['COMPR'] );
    //$rpt->putTextWrap($rpt->leftBorder, $rpt->pdf->y, 400, 10, 'CONCEPTO : ');
    //$rpt->putTextWrap($rpt->leftBorder+80, $rpt->pdf->y, 400, 10, $slText);
    $rpt->pdf->y -=10;
//    $group->resume[0]['resume_text'] = 'SUBTOT. ' . $group->currValue . ": ";/
    $rpt->rptOpt['onlyHeader']=true;
    $rpt->rptOpt['showHeadings']=2;
    $rpt->rptOpt['showLines']=3;
    $alBlankLine=array();
    $alBlankline[]['COM']=' ';
//    $rpt->rptOpt['xPos']=$rpt->pdf->ez['leftMargin'];
    $rpt->pdf->ezTable($alBlankLine,$rpt->colHead,'', $rpt->rptOpt);//    computes de table header height
    $rpt->rptOpt['onlyHeader']=false;
    $rpt->rptOpt['showHeadings']=0;
    $rpt->rptOpt['showLines']=0;
    $rpt->pdf->y +=8;
}
function before_group_NumCompOC (&$rpt, &$group) {
    global $db;
    global $nom;
    $rpt->pdf->y -=15;
    $slText =' ' .$group->lastRec['NumCompOC'];
    $nom=$slText;
    $rpt->putTextWrap($rpt->leftBorder, $rpt->pdf->y, 400, 9, $slText);
}

function after_group_NumCompOC (&$rpt, &$group) {
    $ilAlto=0;
    $ilX = 300;
    $flW = 80;
    $flL = 175;
    $rpt->pdf->ezSetDy(-15);
    $y=$rpt->pdf->y;
    $flValor = ($group->sums['SubTotal'] ) ;
    $slLabel= "TOTAL ENLAZADOS (IB) :";
    $flDy = $rpt->putTextAndLabel($ilX, $rpt->pdf->y,number_format($flValor, 4, ',', '.'), $slLabel, $flW, $flL,0,0,0,'right');
    $rpt->pdf->y -=15;
}
function after_group_Cod_Item (&$rpt, &$group) {
    $ilAlto=0;
    $ilX = 205;
    $flW = 139;
    $flL = 175;
    $rpt->pdf->ezSetDy(-15);
    $y=$rpt->pdf->y;
    $flValor = ($group->sums['SubTotal'] ) ;
    $slLabel= "SUBTOTAL ENLAZADOS (IB) :";
    $flDy = $rpt->putTextAndLabel($ilX, $rpt->pdf->y,number_format($flValor, 4, ',', '.'), $slLabel, $flW, $flL,0,0,0,'right');
    $rpt->pdf->y -=15;
    
}

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$slQry   = fGetParam('pQryCom', false);
$db =& fConexion();
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Helvetica';
$rep = new ezPdfReport($rs, array(21.5,27.8), "landscape", $slFontName, 8);
$rep->title="";
//$rep->subTitle="-";
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(1,1.5,0.3,0.5);
$rep->colHead = array(
                        'NumCompIB' => "IB",
						"Bodega" => "ZONA",
                        'FechaTrans' => 'FECHA',
                        'Cod_Item'  => "COD. TIEM",
                        'Item'  => "DESCRIPCION",
                        'ValorUni' => "VALOR UNI.",
                        'CantidadIB'  => "CANTIDAD",
                        'SubTotal' => "SUBTOTAL",
                        'Proveedor'  => "PROVEEDOR",
                        );
$rep->rptOpt = array('fontSize'=>8, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);
$rep->printColHead=false;
$rep->printFooter=false;
$rep->setDefaultColPro('format', "9:2:,:."); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type

$rep->columns['Cod_Item']->type='C';
$rep->columns['CantidadIB']->type='C';


$rep->setDefaultColOpt('justification', 'center');


$rep->setDefaultColOpt('width', 55);
$rep->colOpt['NumCompIB'] ['width']=60;
$rep->colOpt['Bodega'] ['width']=80;
$rep->colOpt['FechaTrans']['width']=55;
$rep->colOpt['Cod_Item']['width']=55;
$rep->colOpt['Item']['width']=100;
$rep->colOpt['CantidadIB']['width']=55;
$rep->colOpt['SubTotal']['width']=55;
$rep->colOpt['Proveedor']['width']=80;
$rep->columns['ValorUni']->format="9:6:,:.";
$rep->columns['SubTotal']->format="9:3:,:.";

$rep->addGrp('Empresa');
    $rep->groups['Empresa']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['Empresa']->textCol='NumCompOC';
    
$rep->addGrp('NumCompOC');
    $rep->groups['NumCompOC']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['NumCompOC']->textCol='Cod_Item';          // set the column for text at resume line of group

$rep->addGrp('Cod_Item');
    $rep->groups['Cod_Item']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['Cod_Item']->textCol='SubTotal';          // set the column for text at resume line of group

$glProTxt='';
$glVapTxt='';
$glMarTxt='';
$firstComp = true;
$rep->run();
$rep->view($rep->title, $rep->saveFile("ENL_OC_IB_"));
?>
