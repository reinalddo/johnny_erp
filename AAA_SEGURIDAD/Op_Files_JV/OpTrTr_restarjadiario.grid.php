<?
/*
*   Resumen de Tarjas diario, Horizontal
*   Tabla pivoteada de cantidades embarcadas, generadas en columnas por marca
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
include('tohtml.inc.php'); 
/*
 *	if(tad_codempacador ='', tad_observaciones, tad_codempacador)
 **/
// date_format(tac_fecha, '%a %b %d') as pru, 
function &fDefineQry(&$db, $pQry=false){
	$db->Execute("SET lc_time_names = 'es_EC'");
	$slCond = 'tac_semana = ' . fGetParam("pSem",0) . '  AND tac_refoperativa = ' . fGetParam("pEmb", 0);
    $sql = PivotTableSQL(
        $db,                                      # adodb connection
        'liqtarjacabec 
		join liqtarjadetal on tad_numtarja = tar_numtarja
		join conpersonas on per_codauxiliar = tac_embarcador
		join conactivos  on act_codauxiliar = tad_codproducto
		join genparametros on par_clave ="IMARCA" and par_secuencia = tad_codmarca
		join v_opecodigosemp  on  vce_semana = tac_semana AND vce_refoperativa = tac_refoperativa and vce_embarcador = tac_embarcador',   # tables
		"vce_codigosemp as CODIGO,
		 concat(act_descripcion, ' ', act_descripcion1) as PRODUCTO,
		 tac_refoperativa as VAPOR,
		 concat(per_apellidos, ' ', per_nombres) AS NOMBRE",                             # rows (multiple fields allowed)
        "concat(date_format(tac_fecha, '%m%d'),par_valor2)",                            # column to pivot on
        $slCond ,
		'(tad_cantrecibida - tad_cantrechazada - tad_cantcaidas)' 						# data to process
		
    );
	return $db->Execute($sql);
}
	
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry );
rs2html($rs);
?>