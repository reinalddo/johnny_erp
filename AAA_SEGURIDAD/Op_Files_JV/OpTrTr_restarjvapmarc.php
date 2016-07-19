<?php
/*
*   Resumen de Tarjas por vapor, en columans de producto y Marca
*   Tabla pivoteada de cantidades embarcadas, generadas en columnas por producto y marca
*   @created    Ene/22/08
*   @author     fah
*   @
*/
/*
header("Content-Type:  application/vnd.ms-excel");
header("Expires: 0");
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
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pSem != false) ? " tac_Semana  = " . $pSem:" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pEmb != false) ? " tac_RefOperativa  = " . $pEmb:" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pCont != false )? " tac_contenedor  = '" . $pCont ."'" :" true ");

/*
 *	Define la instruccion sql a ejecutarse. Genera tambien los arreglos con las cabeceras de la tabla, con
 *	agrupamiento de cabecera
 **/
function &fDefineQry(&$db, $pQry=false){
   global $rsH, $rsG, $gsCond;
   
   $db->Execute("SET lc_time_names = 'es_EC'");
   $ilSem=urldecode(fGetParam("pSem",0));
   $ilEmb=urldecode(fGetParam("pEmb",0));
   if ($ilSem <= 0) die("DEBE DEFINIR UNA SEMANA");
// Grupos de Cabecera
   $slSqlH ="SELECT tmp_grupcol, act_descripcion,    count(*) as tmp_cols
		   FROM (SELECT distinct act_codauxiliar as tmp_grupcol, act_descripcion, par_descripcion 
				  FROM liqtarjacabec 
					  join liqtarjadetal on tad_numtarja = tar_numtarja
					  join liqembarques  on emb_refoperativa = tac_refoperativa
					  join conactivos on act_codauxiliar = tad_codproducto
					  join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_codmarca
				  WHERE "  . $gsCond .
				  " ORDER by 1,2	
				  ) tmp_01
		   GROUP by 2,1
		   ORDER by 2" ;
   //echo $slSqlH;
   $rsG = $db->Execute($slSqlH);

// Titulos de cabecera
   $slSqlH = "SELECT distinct concat(act_codauxiliar, '-', par_secuencia) as tmp_colid, act_descripcion,
					 concat(left(par_descripcion ,5) , ' ', substring(par_descripcion,6,5), ' ', ifnull(substring(par_descripcion,11,5),''))  as par_descripcion
				  FROM liqtarjacabec 
					  join liqtarjadetal on tad_numtarja = tar_numtarja
					  join conactivos on act_codauxiliar = tad_codproducto
					  join liqembarques  on emb_refoperativa = tac_refoperativa
					  join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_codmarca
				  WHERE  " . $gsCond . 
			   " ORDER  by 1,2";
   $rsH = $db->Execute($slSqlH);
  
;

   $sql = PivotTableSQL(
        $db,                                      # adodb connection
		"liqtarjacabec 
		join liqtarjadetal on tad_numtarja = tar_numtarja
		join conpersonas on per_codauxiliar = tac_embarcador 
		join conactivos on act_codauxiliar = tad_codproducto 
		join v_opevapores on vva_refoperativa = tac_refoperativa 
		join v_opecodigosemp on vce_semana = tac_semana AND vce_refoperativa = tac_refoperativa 
			and vce_embarcador = tac_embarcador 
		join genparametros ma on ma.par_clave ='IMARCA' and ma.par_secuencia = tad_codmarca 
		left join genparametros zo on zo.par_clave ='LSZON' and zo.par_secuencia = tac_zona ",   # tables
		"vva_descripcion as txt_vapor,
		 concat(act_descripcion, ' ', act_descripcion1) as txt_producto,
		 zo.par_descripcion as txt_zona,
		 left(UPPER(concat(per_apellidos, ' ', per_nombres)),32) AS txt_productor,
		 vce_codigosemp as txt_codigos",                             # rows (multiple fields allowed)
        "concat(act_codauxiliar,'-', ma.par_secuencia)",                            # column to pivot on
        $gsCond ,
		'(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas)' 						# data to process
   );
   $sql = str_replace('as "Sum (tad_cantrecibida - tad_cantrechazada - tad_cantcaidas)"', 'AS sumCant', $sql  );
   return $db->Execute($sql . " ORDER BY txt_vapor, txt_zona, txt_productor");
}
	
/**
 *		Procesamiento
*/

set_time_limit (0) ;
$rsG = NULL;
$rsH = NULL;
$rs = fDefineQry($db, $slQry );
//									Grupos de Cabecera
//rs2html($rsG);
//rs2html($rsH);

$rsG->MoveFirst();
$agNumCols=2;
while ($r = $rsG->fetchRow()){
   $agCabeGru[$r['act_descripcion']]['long'] = $r['tmp_cols'];
   $agNumCols +=  $r['tmp_cols'];
 }
//									Cabecera de Columnas
$rsH->MoveFirst();

while ($r = $rsH->fetchRow()){
   $agNombres[] = $r['par_descripcion'];
   $agAbrevia[] = $r['par_descripcion'];
   $agNombCol[] = $r['tmp_colid'];
 }
//obsafe_print_r($rsH);
//obsafe_print_r($agNombres);

$Tpl->assign("agNumCols", $agNumCols +1);
$Tpl->assign("agCabeGru", $agCabeGru);
$Tpl->assign("agNombres", $agNombres);
$Tpl->assign("agAbrevia", $agAbrevia);
$Tpl->assign("agNombCol", $agNombCol);
$Tpl->assign("pSem", $pSem);

$tplFile = 'OpTrTr_restarjvapmarc.tpl';
$aDet =& SmartyArray($rs);
$Tpl->assign("agData", $aDet);
//if (!$Tpl->is_cached($tplFile)) {
//}
$Tpl->display($tplFile);
?>
