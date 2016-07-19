<?php
/*
*   Lista de precios por Contenedor, marca
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
   global $rsH, $rsG, $gsCond, $Tpl, $db, $pEmb;
   set_time_limit (0) ;
   $rsG = NULL;
   $rsH = NULL; 

// Grupos de Cabecera
   $slSqlHg = "SELECT distinct txp_contenedor, txp_producto, tmp_idgr, count(*) as tmp_cols
            FROM (SELECT distinct txp_contenedor, txp_producto, txp_Marca, caj_abreviatura,
                     caj_Descripcion, 
                     txp_codproducto as tmp_idgr
            FROM v_opetarjexpand join liqcajas on caj_codcaja = txp_codcaja
                  join opecontenedores on cnt_serial = txp_contenedor
            WHERE  " . $gsCond . "
            ORDER  by txp_contenedor, txp_producto, txp_Marca, caj_abreviatura ) tmp_001
            group by 1,2
            order by 1";
        
   $rsG = $db->Execute($slSqlHg);
   //rs2html($rsG);
   $rsG->MoveFirst();
   $agNumCols=1;
   while ($r = $rsG->fetchRow()){
      $agCabeGru[$r['txp_contenedor']][$r['txp_producto']]['long'] = $r['tmp_cols'];
      $agNumCols +=  $r['tmp_cols'];
    }
   $agNumCols+=2;
   $agNumC2= round($agNumCols/2, 0);
   $agNumC3= $agNumCols - $agNumC2;
// SubGrupos de Cabecera
   $slSqlSg ="SELECT distinct txp_contenedor, txp_producto, txp_Marca,
               concat(txp_codproducto, '_', txp_codmarca) as tmp_idsg, 
               count(*) as tmp_cols
            FROM (SELECT distinct txp_contenedor, txp_producto, txp_Marca, caj_abreviatura,
                        caj_Descripcion, 
                        txp_codProducto, txp_codmarca,
                        concat(txp_codproducto, '_', txp_codmarca, '_', txp_codcaja) as tmp_idsg
                  FROM v_opetarjexpand join liqcajas on caj_codcaja = txp_codcaja
                        join opecontenedores on cnt_serial = txp_contenedor
                  WHERE  " . $gsCond . "
                  ORDER  by txp_contenedor, txp_producto, txp_Marca, caj_abreviatura ) tmp_001
            group by 1,2,3,4
            order by 1,2 " ;
   $rsSg = $db->Execute($slSqlSg);
//   rs2html($rsSg);
   $rsSg->MoveFirst();
   while ($r = $rsSg->fetchRow()){
      $agCabeSGr[$r['txp_contenedor']][$r['txp_Marca']]['long'] = $r['tmp_cols'];
    }

// Titulos de cabecera
   $slSqlH = "SELECT distinct txp_contenedor, txp_producto, txp_Marca, 
		  concat(left(caj_Descripcion, 6), ' ', substring(caj_descripcion,7,12)) as tmp_colNomb,
                  concat(txp_codproducto, '_', txp_codmarca, '_', txp_codcaja) as tmp_colid
               FROM v_opetarjexpand join liqcajas on caj_codcaja = txp_codcaja
                     join opecontenedores on cnt_serial = txp_contenedor
               WHERE  " . $gsCond . "
               ORDER  by txp_contenedor, txp_producto, txp_Marca, 3 ";


   $rsH = $db->Execute($slSqlH);
//   rs2html($rsH);

   $rsH->MoveFirst();
   $slColDefs = "";
   while ($r = $rsH->fetchRow()){
      $agNombres[$r['txp_Contenedor']][$r['tmp_colid']] = $r['tmp_colNomb']; // Cabecera de una columna
      $slColDefs .= ", SUM(if(concat(txp_codproducto, '_', txp_CodMarca, '_', txp_CodCaja) = '" . $r['tmp_colid'] ."', txp_CantNeta, 0)) as " . $r['tmp_colid'] ;
    }
//print_r($agNombres);
;
   $rsC = $db->execute("SELECT * FROM v_opecontexp WHERE cnt_Embarque = " . $pEmb);
   $rsC->MoveFirst();
   while ($r = $rsC->fetchRow()){
      $agConten[$r['cnt_Serial']] = $r;
    }
    
   $sql= "select
            txp_RefOperativa,
            concat(per_Apellidos, ' ' , per_Nombres) as txt_Naviera,
            cnt_Consignatario,
            txp_Producto, txp_Marca, caj_Descripcion,
            sum(txp_cantneta) as tmp_Suma
            from v_opetarjexpand join liqcajas on caj_codcaja = txp_codcaja
            join opecontenedores on cnt_embarque = txp_refOperativa and cnt_serial = txp_contenedor
            join conpersonas on per_codauxiliar = cnt_naviera
            WHERE  " . $gsCond . "
            group by 1,2 ,3,4, 5,6
            ORDER BY 1,2,3,4,5,6
            ";
   $agResEmp= array();
   $agResMar= array();
   $agResPro= array();
   $rsR = $db->execute($sql);
   $rsR->MoveFirst();
   $gfSuma = 0;
   while ($r = $rsR->fetchRow()){
      $agResEmp['total'][$r['txp_Producto']][$r['txp_Marca']][$r['caj_Descripcion']] = $r['tmp_Suma'];
      $agResEmp[$r['txt_Naviera']][$r['txp_Producto']][$r['txp_Marca']][$r['caj_Descripcion']] = $r['tmp_Suma'];
      if (!isset($agResMar[$r['txp_Marca']])) $agResMar[$r['txp_Marca']] = $r['tmp_Suma'];
      else       $agResMar[$r['txp_Marca']] += $r['tmp_Suma'];
      if (!isset($agResPro[$r['txp_Producto']])) $agResPro[$r['txp_Producto']] = $r['tmp_Suma'];
      else       $agResPro[$r['txp_Producto']] += $r['tmp_Suma'];
      $gfSuma += $r['tmp_Suma'];
    }

   
   //$Tpl->assign("asHoy", date("F j, Y, g:i a"));
   $Tpl->assign("asHoy", date("d /M /y, g:i a"));
   $Tpl->assign("pSem", $pSem);
   $Tpl->assign("agNumCols", $agNumCols)  ;
   $Tpl->assign("agNumC2", $agNumC2)  ;
   $Tpl->assign("agNumC3", $agNumC3)  ;
   $Tpl->assign("agCabeGru", $agCabeGru); // Grupos de Cabecera
   $Tpl->assign("agCabeSGr", $agCabeSGr); // sub Gruposa de cabecera
   $Tpl->assign("agNombres", $agNombres);
   $Tpl->assign("agAbrevia", $agAbrevia);
   $Tpl->assign("agNombCol", $agNombCol);
   $Tpl->assign("agConten", $agConten);
   $Tpl->assign("agResEmp", $agResEmp);
   $Tpl->assign("agResMar", $agResMar);
   $Tpl->assign("agResPro", $agResPro);
   $Tpl->assign("gfSuma", $gfSuma);
   
   $sql = "SELECT txp_nombbuque,
                  txt_Naviera,
                  cnt_Consignatario,
                  txt_Consignatario,
                  cnt_destiFinal, txt_DestiFinal,
                  txp_contenedor,
                  txp_producto,
                  txp_productor ,
                  vcc_codigosemp,
                  0 as tmp_linea". $slColDefs . ",
                  sum(txp_CantNeta) as tmp_SumCantidad
         FROM v_opetarjexpand
         join v_opecodigosempcont  on  vcc_semana = txp_semana
               AND vcc_refoperativa = txp_refoperativa and vcc_embarcador = txp_embarcador  and vcc_Contenedor = txp_Contenedor
         join v_opecontexp ON cnt_serial = txp_contenedor and cnt_Embarque = txp_refOperativa
         WHERE  " . $gsCond . "
         GROUP BY 1,2,3,4,5,6,7,8,9,10,11
         ORDER BY txp_nombbuque, txt_Naviera, txt_Consignatario, txt_DestiFinal, txp_contenedor, txp_producto, txp_Productor " ;
   $rs = $db->Execute($sql );
   return $rs;
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
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pCont != false )? " txp_contenedor  = '" . $pCont ."'" :" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pDesti != false )? " cnt_destino  = '" . $pDesti ."'" :" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pConsig != false )? " cnt_consignatario  = '" . $pConsig ."'" :" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pDestiFi != false )? " cnt_destifinal  = '" . $pDesti ."'" :" true ");

$rs = fDefineQry($db, $slQry );

//									Cabecera de Columnas
//obsafe_print_r($rsH);
//obsafe_print_r($agNombres);

$tplFile = 'OpTrTr_restarjcontmar.tpl';
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
