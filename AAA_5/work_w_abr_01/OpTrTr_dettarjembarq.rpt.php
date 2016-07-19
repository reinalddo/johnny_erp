<?php
/*    Reporte de tarjas, detalle General por Puerto, Vapor, Marca. Formato HTML
 *    @param   integer  pSem     Numero de semana a procesar
 *    @param   integer  pEmb     Numero de Embarque
 *    @param   string   PMarca   Marca
 *
 */
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
include_once("adoConn.inc.php");
$db->debug=fGetparam("pAdoDbg",false);
require('Smarty.class.php');
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
$Tpl->debugging =fGetparam("pAppDbg",false);;
$glFlag= fGetParam('pEmpq', false);

$pAnio = fGetParam('pAnio', false);
$pSem = fGetParam('pSem', false);
$pEmb = fGetParam('pEmb', false);
$pCont = fGetParam('pCont', false);
$pDesti = fGetParam('pDest', false);
$pConsig = fGetParam('pCons', false);
//echo "ccc" . ($pCont == -9999 || $pCont == ' TODOS');
if ($pCont == -9999 || $pCont == ' TODOS') $pCont = false;
if ($pEmb == -9999 || $pEmb == ' TODOS') $pEmb = false;
if ($pCont == -9999 || $pCont == ' TODOS') $pCont = false;
if ($pDesti == -9999 || $pDesti == ' TODOS') $pDesti = false;
if ($pConsig == -9999 || $pConsig == ' TODOS') $pConsig = false;
//$gsCond = ($pAnio != false) ?  " emb_anooperacion   = " . $pAnio :"";
echo "conten: " . $pCont . "/" .strlen($pCont);
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pSem != false) ? " tac_Semana  = " . $pSem:" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pEmb != false) ? " tac_RefOperativa  = " . $pEmb:" true ");
$gsCond .= ($pCont) ? (strlen($gsCond )>0 ? " AND " : "") . " tac_contenedor  = '" . $pCont ."'" : "" ;

if ($pSem>0 && $pAnio >0) {
   $gsCond = "WHERE emb_anooperacion   = " . fGetParam('pAnio') .
             " AND tac_semana     = " . fGetParam('pSem') .
               (($pCont)? " AND tac_contenedor = '" . $pCont . "'" : "" ) .
             " ORDER BY 1 ";

   switch($glFlag){
      case 1:                                                     // Cortes a nivel de Marca
         $gsCampo = "tad_codcaja";
         $sSql= "SELECT distinct  ucase(caj_descripcion) as text,
               tad_codcaja as cod
            FROM liqembarques
               left join liqtarjacabec on tac_refoperativa = emb_refoperativa
               LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
               left join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_CodMarca
               left join liqcajas 	on caj_codcaja = tad_codCaja " . $gsCond;
      case false:                                              // Cortes a nivel de Codigo de caja
         $gsCampo = "tad_codmarca";
         $sSql =
            "SELECT distinct  ucase(left(par_descripcion,10)) as text,
               tad_codmarca as cod
            FROM liqembarques
               left join liqtarjacabec on tac_refoperativa = emb_refoperativa
               LEFT JOIN liqtarjadetal on tad_numtarja = tar_numtarja
               left join genparametros on par_clave = 'IMARCA' and par_secuencia = tad_CodMarca " . $gsCond;
         break;
   }
   $rs = $db->execute($sSql);
   $rs->MoveFirst();
   while ($r = $rs->fetchRow()){
      $agCabeceras[] = $r['text'];
      $agMarcas[] = $r['cod'];
      $agCols[] = "c_" . $r['cod'];
      $agSumas[$r['cod']] = 0;
    }
   $aCabeceras =& SmartyArray($rs);

   $Tpl->assign("agCabeceras", $agCabeceras);
   $Tpl->assign("agMarcas", $agMarcas);
   $Tpl->assign("agCols", $agCols);
   $Tpl->assign("agSumas", $agSumas);
   $sSql=
      "SELECT  
         ucase(concat(n.per_Apellidos, ' ',n.per_nombres)) as txt_naviera,
         concat(buq_descripcion, ' ', month(emb_feczarpe), '/',year(emb_feczarpe)) as txt_embarque,
         ifnull(cnt_serial, tac_contenedor) as txt_contenedor,
         concat(act_descripcion, ' ', act_descripcion1) as txt_producto,
         ucase(concat(p.per_Apellidos, ' ',p.per_nombres)) as txt_productor,
         concat(m.par_descripcion, ' ',ifnull(caj_abreviatura,'')) as txt_marca,
         concat(tad_codmarca, '_', tad_codcaja) as txt_marcemp,
         ifnull(tad_codEmpacador, tac_codEmpaque) as txt_empacador,
         t.par_descripcion as txt_tipo " ;
   
   foreach($agMarcas as $k => $v) {
      $sSql .= ", SUM(IF(" . $gsCampo . " = '" . $v . "', tad_cantrecibida - tad_cantrechazada - tad_cantcaidas, 0))  AS " . "c_" . $v;
   }
   $sSql .= 
         ",SUM(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas) as c_total" .
      " FROM liqtarjacabec
         left join liqembarques    on emb_refoperativa = tac_refoperativa
         left join liqbuques       on emb_codvapor = buq_codbuque
         LEFT JOIN liqtarjadetal   on tad_numtarja = tar_numtarja
         left join genparametros m on m.par_clave = 'IMARCA' and m.par_secuencia = tad_CodMarca
         left join liqcajas 	  on caj_codcaja = tad_codCaja
         LEFT JOIN conpersonas p   on p.per_codauxiliar = tac_Embarcador
         LEFT JOIN conactivos      on act_codauxiliar = tad_codproducto
         LEFT JOIN opecontenedores on cnt_serial = tac_contenedor
         LEFT JOIN conpersonas n   on p.per_codauxiliar = cnt_naviera
         left join genparametros t on t.par_clave = 'OGTICN' and t.par_secuencia = cnt_tipo
      WHERE emb_anooperacion   = " . fGetParam('pAnio') .
         " AND tac_semana     = " . fGetParam('pSem') .
         (($pCont)? " AND tac_contenedor = '" . $pCont . "'" : "" ) .
         " GROUP BY 1,2,3,4,5,6,8,9 
           ORDER BY 1,2,3,4,5,6,8,9 " ;
 
    $rs = $db->execute($sSql . $slFrom);
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
 

	if (!$Tpl->is_cached('OpTrTr_restarjconten.tpl')) {
	}

	$Tpl->display('OpTrTr_restarjconten.tpl');

}
else
	echo "DEFINA LOS PARAMETROS CORRECTOS: Semana y Contenedor"
?>
