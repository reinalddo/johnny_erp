<?php
/*
*   Lista de precios por productos, empaque
*   Tabla pivoteada de cantidades embarcadas, generadas en columnas por producto y marca
*   @created    Ene/22/08
*   @author     fah
*   @
*/
ob_start("ob_gzhandler");
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");
include("adoConn.inc.php");
include "pivottable.inc.php";
require('Smarty.class.php');
include('tohtml.inc.php'); 
if (fGetParam("pOut",false) == "X") header("Content-Type:  application/vnd.ms-excel");
/*header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
*/
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
$Tpl = new Smarty_AAA();
$glFlag= fGetParam('pEmpq', false);
$Tpl->debugging =fGetParam("pAppDbg",false);
/*
 ********************************
*/


/*
 *	Define la instruccion sql a ejecutarse. Genera tambien los arreglos con las cabeceras de la tabla, con
 *	agrupamiento de cabecera
 **/
function &fDefineQry(&$db, $pQry=false){
   global $rsH, $rsG, $gsCond, $Tpl, $db;
   set_time_limit (0) ;
   $rsG = NULL;
   $rsH = NULL; 

// Grupos de Cabecera
   $slSqlHg = "SELECT distinct txp_producto, tmp_idgr, count(*) as tmp_cols
            FROM (SELECT distinct txp_producto, txp_Marca, caj_abreviatura,
                     caj_Descripcion, 
                     txp_codproducto as tmp_idgr
            FROM v_opetarjexpand join liqcajas on caj_codcaja = txp_codcaja
            WHERE  " . $gsCond . "
            ORDER  by txp_producto, txp_Marca,3 ) tmp_001
            group by 1,2
            order by 1";
        
   $rsG = $db->Execute($slSqlHg);
   //rs2html($rsG);
   $rsG->MoveFirst();
   $agNumCols=1;
   while ($r = $rsG->fetchRow()){
      $agCabeGru[$r['txp_producto']]['long'] = $r['tmp_cols'] * 2;
      $agNumCols +=  $r['tmp_cols'] * 2;
    }
   $agNumCols+=2;
   $agNumC2= round($agNumCols/2, 0);
   $agNumC3= $agNumCols - $agNumC2;
// SubGrupos de Cabecera
   $slSqlSg ="SELECT distinct txp_producto, txp_Marca, concat(txp_codproducto, '_', txp_codmarca) as tmp_idsg, 
               count(*) as tmp_cols
            FROM (SELECT distinct txp_producto, txp_Marca, caj_abreviatura,
                        caj_Descripcion, 
                        txp_codProducto, txp_codmarca,
                        concat(txp_codproducto, '_', txp_codmarca, '_', txp_codcaja) as tmp_idsg
                  FROM v_opetarjexpand join liqcajas on caj_codcaja = txp_codcaja
                  WHERE  " . $gsCond . "
                  ORDER  by txp_producto, txp_Marca, caj_abreviatura ) tmp_001
            group by 1,2
            order by 1,2 " ;
   $rsSg = $db->Execute($slSqlSg);
   //rs2html($rsG);
   $rsSg->MoveFirst();
   while ($r = $rsSg->fetchRow()){
      $agSubGru[$r['txp_producto']]['long'] = $r['tmp_cols'] * 2;
    }

// Titulos de cabecera
   $slSqlH = "SELECT distinct txp_producto, txp_Marca, 
		  concat(left(caj_Descripcion, 6), ' ', substring(caj_descripcion,7,12)) as tmp_colNomb,
                  concat(txp_codproducto, '_', txp_codmarca, '_', txp_codcaja) as tmp_colid
               FROM v_opetarjexpand join liqcajas on caj_codcaja = txp_codcaja
               WHERE  " . $gsCond . "
               ORDER  by txp_producto, txp_Marca, 3 ";


   $rsH = $db->Execute($slSqlH);
   //rs2html($rsH);

   $rsH->MoveFirst();
   $slColDefs = "";
   while ($r = $rsH->fetchRow()){
      $agNombres[$r['tmp_colid']] = $r['tmp_colNomb']; // Cabecera de una columna
      $slColDefs .= ", SUM(if(concat(txp_codproducto, '_', txp_CodMarca, '_', txp_CodCaja) = '" . $r['tmp_colid'] ."', txp_CantNeta, 0)) as cnt_" . $r['tmp_colid'] ;
      $slColDefs .= ", MAX(if(concat(txp_codproducto, '_', txp_CodMarca,'_', txp_CodCaja) = '" . $r['tmp_colid'] ."', txp_PrecUnit, 0)) as pun_" . $r['tmp_colid'] ;
    }

;
   $sql = "SELECT txp_nombbuque as txp_nombbuque, 
                  ifnull(txp_NombZona,'-') as tmp_zona,
                  txp_productor  as txp_productor,
                  0 as tmp_linea". $slColDefs . ",
                  sum(txp_CantNeta) as tmp_SumCantidad,
                  sum(txp_CantNeta * txp_precunit) as tmp_SumValor
         FROM v_opetarjexpand
         WHERE  " . $gsCond . "
         GROUP BY 1,2,3,4
         ORDER BY txp_nombbuque, tmp_zona, txp_Productor " ;
   //$Tpl->assign("asHoy", date("F j, Y, g:i a"));
   $Tpl->assign("asHoy", date("d /M /y, g:i a"));
   $Tpl->assign("pSem", $pSem);
   $Tpl->assign("agNumCols", $agNumCols)  ;
   $Tpl->assign("agNumC2", $agNumC2)  ;
   $Tpl->assign("agNumC3", $agNumC3)  ;
   $Tpl->assign("agCabeGru", $agCabeGru);
   $Tpl->assign("agNombres", $agNombres);
   $Tpl->assign("agAbrevia", $agAbrevia);
   $Tpl->assign("agNombCol", $agNombCol);

   return $db->Execute($sql );
}
	
/**
 *		Procesamiento
*/
$f1 = fopen("../css/report.css", "r");
$style_pr = fread ($f1, filesize("../css/report.css"));
$f2 = fopen("../css/AAA_basico.css", "r");
$style_sc = fread ($f2, filesize("../css/AAA_basico.css"));
$gsCond="";
$pAnio = fGetParam('pAnio', false);
$pSem = fGetParam('pSem', false);
$pEmb = fGetParam('pEmb', false);
$pCont = fGetParam('pCont', false);
$pDesti = fGetParam('pDest', false);
$pConsig = fGetParam('pCons', false);
if ($pCont == -9999 || $pCont == ' TODOS') $pCont = false;
if ($pEmb == -9999 || $pEmb == ' TODOS') $pEmb = false;
if ($pCont == -9999 || $pCont == ' TODOS') $pCont = false;
if ($pDesti == -9999 || $pDesti == ' TODOS') $pDesti = false;
if ($pConsig == -9999 || $pConsig == ' TODOS') $pConsig = false;
//$gsCond = ($pAnio != false) ?  " emb_anooperacion   = " . $pAnio :"";
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pSem != false) ? " txp_Semana  = " . $pSem:" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pEmb != false) ? " txp_RefOperativa  = " . $pEmb:" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pCont != false )? " txp_contenedor  = '" . $pCont ."'" :" true ");

$rs = fDefineQry($db, $slQry );

//									Cabecera de Columnas
//obsafe_print_r($rsH);
//obsafe_print_r($agNombres);

$tplFile = 'OpTrTr_restarjlistpre_emp.tpl';
//rs2html($rs);
$rs->MoveFirst();
$aDet =& SmartyArray($rs);
//obsafe_print_r($aDet);
$Tpl->assign("pSem", $pSem);
$Tpl->assign("style_sc", $style_sc);
$Tpl->assign("style_pr", $style_pr);
$Tpl->assign("agData", $aDet);
//if (!$Tpl->is_cached($tplFile)) {
//}
$Tpl->display($tplFile);
?>
