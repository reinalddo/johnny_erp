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
   $slSqlHg = "SELECT distinct txp_catorden, txp_CatProducto, txp_producto, tmp_idgr, count(*) as tmp_cols
            FROM (SELECT distinct txp_catorden, txp_CatProducto, txp_producto, txp_Marca, caj_abreviatura,
                     caj_Descripcion, 
                     txp_codproducto as tmp_idgr
            FROM v_opetarjexpand join liqcajas on caj_codcaja = txp_codcaja
            WHERE  " . $gsCond . "
            ORDER  by txp_producto, txp_Marca,3 ) tmp_001
            group by 1,2,3,4
            order by 1";
        
   $rsG = $db->Execute($slSqlHg);
   //rs2html($rsG);
   $rsG->MoveFirst();
   $agNumCols=1;
   $agColGru=array();               // Numero de columnas por cada supergrupo (Tipo de fruta)
   while ($r = $rsG->fetchRow()){
      $agCabeGru[$r['txp_catorden']][$r['txp_producto']]['long'] = $r['tmp_cols'] * 2;
      $agNumCols +=  $r['tmp_cols'] * 2;
      if (!isset($agColGru[$r['txp_catorden']])) $agColGru[$r['txp_catorden']] = 0; // inicializar 
      $agColGru[$r['txp_catorden']] += $r['tmp_cols'] * 2;
      $agCatNom[$r['txp_catorden']] = $r['txp_CatProducto'];
    }
   $agNumCols+=2;
   $agNumC2= round($agNumCols/2, 0);
   $agNumC3= $agNumCols - $agNumC2;
// SubGrupos de Cabecera
   $slSqlSg ="SELECT distinct txp_catorden, txp_producto, txp_Marca, concat(txp_codproducto, '_', txp_codmarca) as tmp_idsg, 
               count(*) as tmp_cols
            FROM (SELECT distinct txp_catorden, txp_producto, txp_Marca, caj_abreviatura,
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
      $agSubGru[$r['txp_catorden']][$r['txp_producto']]['long'] = $r['tmp_cols'] * 2;
    }

// Titulos de cabecera
   $slSqlH = "SELECT distinct txp_catorden as txp_catorden, txp_codproducto, txp_producto, txp_Marca, 
		  concat(left(caj_Descripcion, 6), ' ', substring(caj_descripcion,7,12)) as tmp_colNomb,
                  concat(txp_catorden, '_', txp_codproducto, '_', txp_codmarca, '_', txp_codcaja) as tmp_colid
               FROM v_opetarjexpand join liqcajas on caj_codcaja = txp_codcaja
               WHERE  " . $gsCond . "
               ORDER  by txp_producto, txp_Marca, 3 ";


   $rsH = $db->Execute($slSqlH);
   //rs2html($rsH);

   $rsH->MoveFirst();
   $slColDefs = "";
   while ($r = $rsH->fetchRow()){
      $agNombTip[$r['txp_CodProducto']] = $r['txp_CodProducto'];
      $agNombres[$r['txp_catorden']][$r['tmp_colid']] = $r['tmp_colNomb']; // Cabecera de una columna
      $slColDefs .= ", SUM(if(concat(txp_catorden, '_', txp_codproducto, '_', txp_CodMarca, '_', txp_CodCaja) = '" . $r['tmp_colid'] ."', txp_CantNeta, 0)) as cnt_" . $r['tmp_colid'] ;
      $slColDefs .= ", MAX(if(concat(txp_catorden, '_', txp_codproducto, '_', txp_CodMarca,'_', txp_CodCaja) = '" . $r['tmp_colid'] ."', txp_PrecUnit, 0)) as pun_" . $r['tmp_colid'] ;
    }
;
/*  Resumen de Flete a Pagar*/
   $slSqlHg = "SELECT *
            FROM  opefleteproduct
            WHERE  fle_refoperat = " . fGetParam("pEmb", -1) . " and fle_Cantidad > 0
            ORDER  by fle_codproductor, fle_codproducto ";
        
   $rsG = $db->Execute($slSqlHg);
   $rsG->MoveFirst();
   $agFlete=array();
   while ($r = $rsG->fetchRow()){
      $agFlete[$r['fle_CodProductor']][$r['fle_CodProducto']] = $r['fle_Cantidad'];      
      //$agFlete[$r['fle_RefOperat'] . "_" . $r['fle_CodProductor'] . "_" . $r['fle_CodProducto']] = $r['fle_Cantidad'];
    }
/* ----------------------------*/

$sql = "
      SELECT 
	 txp_nombbuque as txp_nombbuque, 
	 txp_catorden, ifnull(txp_NombZona,'-') as tmp_zona, 
	 txp_embarcador as txp_embarcador,
	 txp_productor as txp_productor,
         txp_IndFlete  as txp_indflete,
	 tmp_linea 	 ". $slColDefs . ",
         sum(txp_CantNeta) as tmp_SumCantidad,
         sum(txp_CantNeta * txp_precunit) as tmp_SumValor
      FROM (
	 SELECT txp_nombbuque as txp_nombbuque, 
	 txp_catorden, txp_NombZona,
	 txp_embarcador,
	 txp_productor as txp_productor, 
	 if(concat(txp_embarcador, '_', txp_catorden, '_', txp_codproducto, '_',txp_CodMarca, '_', txp_CodCaja) = @p, @a:=@a+1, @a:=0) as tmp_linea,
	 txp_codproducto, txp_CodMarca, txp_CodCaja, 
	 @p:= concat(txp_embarcador, '_', txp_catorden, '_', txp_codproducto, '_',txp_CodMarca, '_', txp_CodCaja) ,
	 txp_PrecUnit,
	 txp_CantNeta,
         txp_indFlete
	 FROM (
	    SELECT txp_nombbuque as txp_nombbuque, 
	       txp_catorden, ifnull(txp_NombZona,'--') as txp_NombZona,
	       txp_embarcador, txp_productor,
	       txp_codproducto, txp_CodMarca, txp_CodCaja, 
	       txp_precunit,
               txp_indFlete,
	       sum(txp_CantNeta) AS txp_CantNeta
	       FROM v_opetarjexpand 
	       WHERE  " . $gsCond . " 
	       group BY 1,2,3,4,5,6,7,8,9,10
	       ORDER BY txp_nombbuque, txp_catorden, txp_NombZona, txp_productor, txp_codproducto, txp_CodMarca,  txp_CodCaja
	    ) tmp_zz
	 group BY 1,2,3,4,5,6,7,8,9,10,11,12,13
	 ORDER BY txp_nombbuque, txp_catorden, txp_NombZona, txp_Productor, tmp_linea, txp_codproducto, txp_CodMarca, txp_CodCaja
	 ) tmp_z02
      group BY 1,2,3,4,5,6,7
      ORDER BY txp_nombbuque, txp_catorden, tmp_zona, txp_Productor, txp_indflete,tmp_linea
"
;

   //$Tpl->assign("asHoy", date("F j, Y, g:i a"));
   $Tpl->assign("asHoy", date("d /M /y, g:i a"));
   $Tpl->assign("pSem", $pSem);
   $Tpl->assign("agNumCols", $agNumCols)  ;
   $Tpl->assign("agNumC2", $agNumC2)  ;
   $Tpl->assign("agNumC3", $agNumC3)  ;
   $Tpl->assign("agCabeGru", $agCabeGru);
   $Tpl->assign("agNombres", $agNombres);
   $Tpl->assign("agNombTip", $agNombTip);
   $Tpl->assign("agAbrevia", $agAbrevia);
   $Tpl->assign("agNombCol", $agNombCol);
   $Tpl->assign("agColGru", $agColGru);
   $Tpl->assign("agCatNom", $agCatNom);
   $Tpl->assign("agFlete", $agFlete);

   $db->Execute('set @a=0');
   $db->Execute('set @p="@@"');
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
