<?php
/*
*   Resumen de Tarjas diario, Horizontal
*   Tabla pivoteada de cantidades embarcadas, generadas en columnas por marca
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
        $this->template_dir = './';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = false;
   }
}

/*if (fGetparam("pExcel",false)){
   header("Content-Type:  application/vnd.ms-excel");
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}*/
include "../LibPhp/excelOut.php";
$Tpl = new Smarty_AAA();
$glFlag= fGetParam('pEmpq', false);
$Tpl->debugging =fGetparam("pAppDbg",false);
/*
 ********************************
*/

if (fGetParam('pSem')) {
   $pCont = fGetParam('pCont', false);
   $gsCond = "emb_anooperacion   = " . fGetParam('pAnio') .
             " AND tac_semana     = " . fGetParam('pSem') .
			 " AND tac_RefOperativa = " . fGetParam('pEmb') .
               (($pCont)? " AND tac_contenedor = '" . $pCont . "'" : "" );
}
/*
 *	Define la instruccion sql a ejecutarse. Genera tambien los arreglos con las cabeceras de la tabla, con
 *	agrupamiento de cabecera
 **/
function &fDefineQry(&$db, $pQry=false){
   global $rsH, $rsG;
   
   $db->Execute("SET lc_time_names = 'es_EC'");
   /*$ilSem=urldecode(fGetParam("pSem",0)); comentado por marco valle*/
   $ilSem=urldecode(fGetParam("tac_Semana",0));
   $ilEmb=urldecode(fGetParam("pEmb",0));
   if ($ilSem <= 0) die("DEBE DEFINIR UNA SEMANA");
   $slCond = 'tac_semana = ' . $ilSem . ($ilEmb? '  AND tac_refoperativa = ' . $ilEmb : "");
/*// Grupos de Cabecera
   $slSqlH ="SELECT tac_fecha, tmp_fec,    count(*) as tmp_cols
		   FROM (SELECT distinct UPPER(date_format(tac_fecha, '%a %b %d')) tmp_fec, tac_fecha,
			      par_descripcion
				  FROM liqtarjacabec 
						join liqtarjadetal on tad_numtarja = tar_numtarja
						join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_codmarca
				  WHERE  "  . $slCond .
				  " ORDER by 1,2	
				  ) tmp_01
		   GROUP by 1
		   ORDER by tac_fecha " ;
   $rsG = $db->Execute($slSqlH);
*/ 
// Titulos de cabecera
   $slSqlH = "SELECT distinct par_descripcion as tmp_descr,
			   par_valor2  tmp_abrev
			   FROM liqtarjacabec 
				   join liqtarjadetal on tad_numtarja = tar_numtarja
				   join genparametros on par_clave ='IMARCA' and par_secuencia = tad_codmarca
			   WHERE  " . $slCond . 
			   " ORDER by  par_descripcion";
   $rsH = $db->Execute($slSqlH);
;

   $sql = PivotTableSQL(
        $db,                                      # adodb connection
        'liqtarjacabec 
		join liqtarjadetal on tad_numtarja = tar_numtarja
		join conpersonas on per_codauxiliar = tac_embarcador
		join conactivos  on act_codauxiliar = tad_codproducto
		join genparametros on par_clave ="IMARCA" and par_secuencia = tad_codmarca
		join v_opevapores  on vva_refoperativa = tac_refoperativa
		join v_opecodigosemp  on  vce_semana = tac_semana AND vce_refoperativa = tac_refoperativa and vce_embarcador = tac_embarcador',   # tables
		"vva_descripcion as txt_vapor,
		 concat(act_descripcion, ' ', act_descripcion1) as txt_producto,
		 tac_Bodega, tac_Piso",                             # rows (multiple fields allowed)
        "UPPER(par_Descripcion)",                            # column to pivot on
        $slCond ,
		'(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas)' 						# data to process
   );
   $sql = str_replace('as "Sum (tad_cantrecibida - tad_cantrechazada - tad_cantcaidas)"', 'AS sumCant', $sql  );
   return $db->Execute($sql . " ORDER BY txt_vapor, txt_producto, tac_bodega, tac_piso");
}
	
/**
 *		Procesamiento
*/

set_time_limit (0) ;
$rsG = NULL;
$rsH = NULL;
$rs = fDefineQry($db, $slQry );
//									Grupos de Cabecera
//									Cabecera de Columnas
$rsH->MoveFirst();
$agNumCols = 1;
while ($r = $rsH->fetchRow()){
   $agNombres[] = $r['tmp_descr'];
   $agAbrevia[] = $r['tmp_abrev'];
   $agNombCol[] = $r['tmp_nomcol'];
   $agNumCols += 1;
 }
$Tpl->assign("agNumCols", $agNumCols +2);
$Tpl->assign("agNombres", $agNombres);
$Tpl->assign("agAbrevia", $agAbrevia);
$Tpl->assign("agNombCol", $agNombCol);
$Tpl->assign("usuario", $_SESSION['g_user']);
setlocale(LC_ALL, 'es_EC');

$Tpl->assign("fecha", date("F j, Y, g:i a"));
/*$Tpl->assign("pSem", fGetParam('pSem')); comentado por marco Valle*/
$Tpl->assign("pSem", fGetParam('tac_Semana'));


$tplFile = 'LiEmTj_restarjcargoplan.tpl';
$aDet =& SmartyArray($rs);
$Tpl->assign("agData", $aDet);
//if (!$Tpl->is_cached($tplFile)) {
//}
$Tpl->display($tplFile);

?>
