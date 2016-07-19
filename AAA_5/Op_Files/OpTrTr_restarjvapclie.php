<?php
/*
*   Resumen de Tarjas por vapor, en columans de producto y Marca
*   Tabla pivoteada de cantidades embarcadas, generadas en columnas por producto y marca
*   @created    Ene/22/08
*   @author     fah
*   @
*/

/*header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
*/
ob_start("ob_gzhandler");
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");
include("adoConn.inc.php");
include "pivottable.inc.php";
require('Smarty.class.php');
include('tohtml.inc.php'); 
if(fGetParam('pOut', false) == "X" )header("Content-Type:  application/vnd.ms-excel");
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
$Tpl->debugging =fGetparam("pAppDbg",false);
/*
 ********************************
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
$pConsig = fGetParam('pClie', false);
$pDestFi = fGetParam('pDestFi', false);
if ($pCont == -9999 || $pCont == ' TODOS') $pCont = false;
if ($pEmb == -9999 || $pEmb == ' TODOS') $pEmb = false;
if ($pCont == -9999 || $pCont == ' TODOS') $pCont = false;
if ($pDesti == -9999 || $pDesti == ' TODOS') $pDesti = false;
if ($pConsig == -9999 || $pConsig == ' TODOS') $pConsig = false;
if ($pDestFi == -9999 || $pDestFi == ' TODOS') $pDestFi = false;
//$gsCond = ($pAnio != false) ?  " emb_anooperacion   = " . $pAnio :"";
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pSem != false) ? " txp_Semana  = " . $pSem:" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pEmb != false) ? " txp_RefOperativa  = " . $pEmb:" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pCont != false)? " txp_contenedor  = '" . $pCont ."'" :" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pConsig != false)? " cnt_Consignatario  = '" . $pConsig ."'" :" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pDestFi != false)? " cnt_DestiFinal  = '" . $pDestFi ."'" :" true ");

/*
 *	Define la instruccion sql a ejecutarse. Genera tambien los arreglos con las cabeceras de la tabla, con
 *	agrupamiento de cabecera
 **/
function &fDefineQry(&$db, $pQry=false){
   global $rsH, $rsG, $gsCond, $Tpl;
   set_time_limit (0) ;
   $rsG = NULL;
   $rsH = NULL; 

// Grupos de Cabecera
   $slSqlH ="SELECT txp_producto, count(*) as tmp_cols
            FROM (SELECT distinct txp_producto, txp_marca, txp_codcaja, caj_Descripcion,
                  concat(txp_codproducto, '_', txp_codmarca, '_', txp_codcaja) as tmp_colid
               FROM v_opetarjexpand join liqcajas on caj_codcaja = txp_codcaja
					 join v_opecontexp on cnt_embarque = txp_refoperativa and cnt_serial = txp_contenedor
					 join v_opecodigosemp on vce_semana = txp_semana and vce_refoperativa = txp_refoperativa and vce_embarcador = txp_embarcador
               WHERE  " . $gsCond . 
               " ORDER  by 1  ) tmp_001
            group by 1 ORDER BY 1" ;
   $rsG = $db->Execute($slSqlH);
   //rs2html($rsG);
   $rsG->MoveFirst();
   $agNumCols=3;
   while ($r = $rsG->fetchRow()){
      $agCabeGru[$r['txp_producto']]['long'] = $r['tmp_cols'];
      $agNumCols +=  $r['tmp_cols'];
    }

// Subgrupos de cabe
   $slSqlH = "SELECT txp_producto, txp_Marca, count(*) as tmp_cols
            FROM (SELECT distinct txp_producto, txp_marca, txp_codcaja, caj_Descripcion,
                  concat(txp_codproducto, '_', txp_codmarca) as tmp_colid
               FROM v_opetarjexpand join liqcajas on caj_codcaja = txp_codcaja
					 join v_opecontexp on cnt_embarque = txp_refoperativa and cnt_serial = txp_contenedor			   
               WHERE  " . $gsCond . 
               " ORDER  by 1,2   ) tmp_001
            group by 1,2 ORDER BY 1,2" ;
   $rsSg = $db->Execute($slSqlH);
   $j=array();
   $rsSg->MoveFirst();
   while ($r = $rsSg->fetchRow()){
      $j['nomb'] = $r['txp_Marca'];
	  $j['long'] = $r['tmp_cols'];
	  $agCabeSgr[]=$j;
    }

// Titulos de cabecera
   $slSqlH = "SELECT distinct txp_producto, txp_marca, txp_codcaja, caj_Descripcion,
                  concat(txp_codproducto, '_', txp_codmarca, '_', txp_codcaja) as tmp_colid
               FROM v_opetarjexpand join liqcajas on caj_codcaja = txp_codcaja
					 join v_opecontexp on cnt_embarque = txp_refoperativa and cnt_serial = txp_contenedor			   
			   WHERE  " . $gsCond . 
			   " ORDER  by 1,2,4";
   $rsH = $db->Execute($slSqlH);
   //rs2html($rsH);

   $rsH->MoveFirst();
   $slColDefs = "";
   while ($r = $rsH->fetchRow()){
      $agNombres[] = $r['caj_Descripcion'];
      $agAbrevia[] = $r['txp_Marca'];
      $agNombCol[] = $r['tmp_colid'];
      $slColDefs .= ", SUM(if(concat(txp_codproducto, '_', txp_CodMarca, '_', txp_CodCaja) = '" . $r['tmp_colid'] ."', txp_CantNeta, 0)) as " . $r['tmp_colid'] ;
    }

;
   $sql = "SELECT txp_nombbuque as txp_nombbuque,
                  left(txt_Consignatario, 25) as txt_Consignatario,
                  ifnull(txp_NombZona,'-') as tmp_zona,
                  txp_productor  as txp_productor, vce_codigosemp,
                  0 as tmp_linea". $slColDefs . ",
                  sum(txp_CantNeta) as tmp_SumCantidad,
                  sum(txp_CantNeta * txp_precunit) as tmp_SumValor
         FROM v_opetarjexpand
					 join v_opecontexp on cnt_embarque = txp_refoperativa and cnt_serial = txp_contenedor
					 join v_opecodigosemp on vce_semana = txp_semana and vce_refoperativa =txp_refoperativa and
							  vce_embarcador = txp_embarcador
         WHERE  " . $gsCond . "
         GROUP BY 1,2,3,4,5 
         ORDER BY txp_nombbuque, txt_Consignatario, tmp_zona, txp_Productor " ;

   $Tpl->assign("agNumCols", ($agNumCols ) +1);
   $Tpl->assign("agCabeGru", $agCabeGru);
   $Tpl->assign("agCabeSgr", $agCabeSgr);
   $Tpl->assign("agNombres", $agNombres);
   $Tpl->assign("agAbrevia", $agAbrevia);
   $Tpl->assign("agNombCol", $agNombCol);
   $Tpl->assign("pSem", $pSem);

   return $db->Execute($sql );
}
	
/**
 *		Procesamiento
*/

$rs = fDefineQry($db, $slQry );

//									Cabecera de Columnas
//obsafe_print_r($rsH);
//obsafe_print_r($agNombres);

$tplFile = 'OpTrTr_restarjvapclie.tpl';
//rs2html($rs);
$rs->MoveFirst();
$aDet =& SmartyArray($rs);
//obsafe_print_r($aDet);
$Tpl->assign("agData", $aDet);
$Tpl->assign("pSem", $pSem);
//if (!$Tpl->is_cached($tplFile)) {
//}
$Tpl->display($tplFile);
?>
