<?php
/*
*   Resumen de Tarjas por vapor, destino y Consignatario. 
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
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pDesti != false)? " cnt_Destino  = '" . $pDesti ."'" :" true ");
;
/*
 *	Define la instruccion sql a ejecutarse. Genera tambien los arreglos con las cabeceras de la tabla, con
 *	agrupamiento de cabecera
 **/
function fTraeArray($pSql, $pIdx){
   global $db;
   $rs = $db->Execute($pSql);
   //rs2html($rsG);
   $rs->MoveFirst();
   $agTmp= array();
   $m=array();
   while ($r = $rs->fetchRow()){
	  $m['nomb'] = $r[$pIdx];
	  $m['long'] = $r['tmp_cols'];
	  $agTmp[] = $m;
    }
	return $agTmp;
}
function &fDefineQry(&$db, $pQry=false){
   global $rsH, $rsG, $gsCond, $Tpl;
   set_time_limit (0) ;
   $rsG = NULL;
   $rsH = NULL; 
   $gsSqlCabe = "SELECT distinct txt_Destino, txt_Consignatario, txp_producto, 
					  cnt_consignatario, txp_marca, txp_codcaja, caj_Descripcion, 
					  concat(txp_codproducto, '_', txp_codmarca, '_', txp_codcaja) as tmp_colid, 
					  txp_codproducto, txp_codMarca
					  FROM v_opetarjexpand join liqcajas on caj_codcaja = txp_codcaja 
					  join v_opecontexp on cnt_embarque = txp_refoperativa and cnt_serial = txp_contenedor 
					  WHERE  " . $gsCond .  "
					  AND true 
					  order by txt_DEstino, txt_consignatario, txp_producto, txp_marca, caj_descripcion ";

/*   
   SELECT distinct txt_Destino, txt_Consignatario, txp_producto, txp_marca, txp_codcaja, caj_Descripcion,
                  concat(txp_codproducto, '_', txp_codmarca, '_', txp_codcaja) as tmp_colid,
				  txp_codproducto, txp_CodCaja, txp_codMarca, txp_consignatario
				  FROM v_opetarjexpand 
				  join liqcajas on caj_codcaja = txp_codcaja
				  join v_opecontexp on cnt_embarque = txp_refoperativa and cnt_serial = txp_contenedor
				  WHERE  " . $gsCond .  "
			   order by txt_DEstino, txt_consignatario, txp_producto, txp_marca, caj_descripcion ";
*/
// Grupos de Cabecera: Consignatario 
   $slSqlH ="SELECT left(txt_Consignatario,15) as tmp_consig, cnt_consignatario as tmp_colid, count(*) as tmp_cols FROM (" . $gsSqlCabe . ") tmp_001
            group by 1 ORDER BY 1" ;
   $agClien=fTraeArray($slSqlH, 'tmp_consig');

// Grupos de Cabecera: Consignatario - producto
   $slSqlH ="SELECT left(txt_Consignatario,15) as tmp_consig,
			   txp_Producto,
			   concat(cnt_consignatario, '_', txp_codproducto) as tmp_colid,
			   count(*) as tmp_cols
			   FROM (" . $gsSqlCabe . ") tmp_001
            group by 1,2,3 ORDER BY 1,2" ;
   $agProduc=fTraeArray($slSqlH, 'txp_Producto');

// Grupos de Cabecera: Consignatario - producto - Marca
   $slSqlH ="SELECT left(txt_Consignatario,15),
			txp_Producto,
			txp_Marca,
			concat(cnt_consignatario, '_', txp_codproducto, '_', txp_codmarca) as tmp_colid,
			count(*) as tmp_cols FROM (" . $gsSqlCabe . ") tmp_001
            group by 1,2,3,4 ORDER BY 1,2,3,4" ;
   $agMarcas=fTraeArray($slSqlH, 'txp_Marca');

// Grupos de Cabecera: Consignatario - producto - Marca-Empaque
   $slSqlH ="SELECT left(txt_Consignatario,15),
			txp_Producto,
			txp_Marca,
			caj_Descripcion,
			concat(cnt_consignatario, '_', txp_codproducto, '_', txp_codmarca, '_', txp_codcaja) as tmp_colid,
			count(*) as tmp_cols FROM (" . $gsSqlCabe . ") tmp_001
            group by 1,2,3,4,5 ORDER BY 1,2,3,4,5" ;
   $agEmpaques=array();
   $rs = $db->Execute($slSqlH);
   //rs2html($rsG);
   $rs->MoveFirst();
   $m=array();
   $ilNum =3;
   while ($r = $rs->fetchRow()){
	  $m['id'] = $r['tmp_colid'];
	  $m['nomb'] = $r['caj_Descripcion'];
	  $m['long'] = $r['tmp_cols'];
	  $slColDefs .= ", IF(concat(cnt_consignatario, '_', txp_codproducto, '_', txp_codmarca, '_', txp_codcaja) ='" .
				    $r['tmp_colid'] . "', txp_cantneta, 0) as " . $r['tmp_colid'] ;
	  $agEmpaques[] = $m;
	  $ilNum +=$r['tmp_cols'];
    }

   $sql = "SELECT txp_nombbuque as txp_nombbuque,
				  txt_Destino,
                  left(txt_Consignatario, 25) as txt_Consignatario,
                  txp_productor  as txp_productor, vce_codigosemp,
                  0 as tmp_linea". $slColDefs . ",
                  sum(txp_CantNeta) as tmp_SumCantidad
         FROM v_opetarjexpand
					 join v_opecontexp on cnt_embarque = txp_refoperativa and cnt_serial = txp_contenedor
					 join v_opecodigosemp on vce_semana = txp_semana and vce_refoperativa =txp_refoperativa and
					 vce_embarcador = txp_embarcador
         WHERE  " . $gsCond . "
         GROUP BY 1,2,3,4,5 
         ORDER BY txp_nombbuque, txt_Consignatario, txp_productor " ;

   $Tpl->assign("agNumCols", ($ilNum ));
   $Tpl->assign("agClien", $agClien);
   $Tpl->assign("agProduc", $agProduc);
   $Tpl->assign("agMarcas", $agMarcas);
   $Tpl->assign("agEmpaques", $agEmpaques);
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

$tplFile = 'OpTrTr_restarjdestmarc.tpl';
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
