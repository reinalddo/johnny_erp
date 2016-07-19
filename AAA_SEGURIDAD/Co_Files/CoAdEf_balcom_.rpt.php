<?php
/*
*   Detalle de Tarjas diario
*   Detalle de Tarjas 
*   @date	11/03/09    
*   @author     fah
*   @rev 	fah 02/06/09	Correccion de Query, para agrupar correctamente los datos
*/
ob_start("ob_gzhandler");
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");
include("adoConn.inc.php");
require('Smarty.class.php');
include('tohtml.inc.php'); 
class Smarty_AAA extends Smarty {
   function Smarty_AAA()
   {
        $this->Smarty();
        //$this->template_dir = './';
	$this->template_dir = '../templates';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = fGetParam("pSmtDbg",false);
	
   }
}

include ("../../AAA_SEGURIDAD/LibPhp/excelOut.php");



$Tpl = new Smarty_AAA();
$glFlag= fGetParam('pEmpq', false);
$Tpl->debugging =fGetparam("pAppDbg",false);
/*
 ********************************
*/

/*
 *	Define la instruccion sql a ejecutarse. Genera tambien los arreglos con las cabeceras de la tabla, con
 *	agrupamiento de cabecera
 **/
function &fDefineQry($pSql, $ilPeriodo, $ilNivel, $pEsquema){
   global $rsG;
   global $db;
    global $slTipo;
    $aSql=Array();
    $aSql[] ="DROP TABLE IF EXISTS tmp_auxiliares";
    $aSql[] ="drop table  if exists tmp_balcomp";

    $aSql[] ="CREATE TEMPORARY TABLE tmp_auxiliares
                SELECT
                        ucase( rpad(concat( left(per_Apellidos, 18), ' ', left(ifnull(per_Nombres,' '), 15)), " . (($slTipo =='COM')?20:45) . ", '-') )as tmp_descripcion,
                        per_codauxiliar as tmp_codauxiliar
                FROM conpersonas";
    $aSql[] ="INSERT INTO tmp_auxiliares
                SELECT  ucase( rpad(concat( left(act_descripcion, 18), ' ', left(ifnull(act_descripcion1,' '), 15))," . (($slTipo =='COM')?20:45) . ", '-') )as tmp_descripcion,
                        act_codauxiliar as tmp_codauxiliar
                FROM conactivos";
                
    $aSql[] ="CREATE INDEX i_tmpaux on tmp_auxiliares(tmp_codauxiliar)";
/**
*               Valores de cuentas ascendentes
**/
    $aSql[] ="CREATE  TABLE  tmp_balcomp
	            SELECT
                    red_codcuenta as cuenta ,
                    red_Ascendent,
                    cc.cue_padre as cue_padre ,
                    cc.cue_id as cue_id,
                    red_codcuenta  AS det_codcuenta,
                    000000 as det_idauxiliar,
                    cc.cue_tipmovim as cue_tipmovim,
                    sum(round(det_valdebito,2) - round(det_valcredito,2) -0000000000.00) as salan,
                    0000000000.00 as valdb, 0000000000.00 as valcr,
                    sum(round(det_valdebito,2)  - round(det_valcredito,2)) as saldo
 	            FROM concomprobantes, condetalle, concuentas, conredcuentas, concuentas cc
	            WHERE com_numperiodo < ". $ilPeriodo . " AND
                    com_estProceso = 5 AND
	                det_regnumero = com_regnumero AND
                    concuentas.cue_codcuenta = det_codcuenta AND
	                red_cueid = concuentas.cue_id AND cc.cue_id = red_ascendent
                    AND red_codcuenta
                GROUP BY 1,2,3,4, 5,6
                ORDER BY 1,2" ;

	$aSql[] = "CREATE INDEX i_tmpsal on tmp_balcomp(cuenta, red_ascendent)";
/**
*       Cuentas de movimiento
**/
    $aSql[] ="INSERT INTO tmp_balcomp
              SELECT det_codcuenta as cuenta ,
                    9 as red_Ascendent,
                    cue_id as cue_padre,
                    0  as cue_id,
                    det_codcuenta,
                    det_idauxiliar,
                    1 as cue_tipmovim,
                    sum(round(det_valdebito,2) - round(det_valcredito,2)) as salan,
                    0000000000.00 as valdb, 0000000000.00 as valcr,
                    sum(round(det_valdebito,2)  - round(det_valcredito,2)) as saldo
                FROM concomprobantes JOIN condetalle on det_regnumero = com_regnumero
                    JOIN concuentas ON cue_codcuenta = det_codcuenta
                    LEFT JOIN tmp_auxiliares ON tmp_codauxiliar = det_idauxiliar
                WHERE com_numperiodo < ". $ilPeriodo . "  AND com_estProceso = 5 AND
                    det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND det_idauxiliar <> 0
                GROUP BY 1,2,3,4, 5,6,7
                HAVING saldo <> 0 AND salan <> 0
                ORDER BY 1,2";
/**
*       Cuentas de movimiento
**/
    $aSql[] ="INSERT INTO tmp_balcomp
	            SELECT
                    red_codcuenta as cuenta ,
                    red_Ascendent,
                    cc.cue_padre as cue_padre ,
                    cc.cue_id as cue_id ,
                  	red_codcuenta  AS det_codcuenta,
                    00000 as det_idauxiliar,
                    cc.cue_tipmovim as cue_tipmovim,
                    sum(0000000000.00) as salan,
                    sum(round(det_valdebito,2))  as valdb ,
                    sum(round(det_valcredito,2)) as valcr,
                    sum(round(det_valdebito,2) - round(det_valcredito,2)) as saldo
                FROM concomprobantes, condetalle, concuentas, conredcuentas, concuentas cc
                WHERE com_numperiodo = ". $ilPeriodo . " AND com_estProceso = 5 AND
                    det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND
                    red_cueid = concuentas.cue_id AND cc.cue_id = red_ascendent AND red_codcuenta AND
                    (det_valdebito <> 0 or det_valcredito <> 0)
                GROUP BY 1,2,3,4, 5,6, 7
                ORDER BY 1,2";
/**
*       Cuentas de movimiento del periodo
**/

    $aSql[] ="INSERT INTO tmp_balcomp
               SELECT det_codcuenta as cuenta , 9 as red_Ascendent, cue_id as cue_padre,
                    00000  as cue_id,
                    det_codcuenta, det_idauxiliar,
              	    1 as cue_tipmovim,
                    sum(0000000000.00) as salan,
                    sum(round(det_valdebito,2))  as valdb ,
                    sum(round(det_valcredito,2)) as valcr,
                    sum(round(det_valdebito,2)  - round(det_valcredito,2)) as saldo
                FROM concomprobantes JOIN condetalle on det_regnumero = com_regnumero
                     JOIN concuentas ON cue_codcuenta = det_codcuenta
                     LEFT JOIN tmp_auxiliares ON tmp_codauxiliar = det_idauxiliar
	            WHERE com_numperiodo = ". $ilPeriodo . "  AND com_estProceso = 5 AND
	                  det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND det_idauxiliar <> 0 and
	                  (det_valdebito <> 0 or det_valcredito <> 0)
             GROUP BY 1,2,3,4,5,6 
             ORDER BY 1,7";

    
    //$slSaldoCondicion = (($slTipo == 'COM') ? "  " : "HAVING (0 <> sum(valdb) - sum(valcr)   )") ;
    $slSaldoCondicion = (($slTipo == 'COM') ? "  " : "HAVING (0 <> SAB   )") ;
    //$slSaldoCondicion = "HAVING (0 <> SAB   )" ;
    $slEsquema = (($pEsquema) ? ' AND det_codcuenta ' . $pEsquema : ' ');
//echo $slEsquema;
    $aSql[] ="SELECT
                    left(cuenta,1) AS GRU,
                    cuenta AS CTA,
                    cue_posicion AS NIV,
                    concat( if(det_idauxiliar=0,cuenta, concat('       .', det_idauxiliar)) )AS CUE,
                    left (concat( repeat(' ', cue_posicion),
                        if (det_idauxiliar = 0 ,
                            cue_descripcion,
                            concat('     ', left(ifnull(per_apellidos,''),25),
                                    left(ifnull(act_descripcion,''),25), ' ',
                                    left(ifnull(per_nombres,''),20) ,
                                    left(ifnull(act_descripcion1, ''),20)
                    ) ) ), ". (($slTipo =='COM')?50:70) . ") AS DES,
                    SUM(round(salan,2)) AS SAN,
                    SUM(round(valcr,2) + 00000000000.00) AS VCR,
                    SUM(round(valdb,2) + 00000000000.00) AS VDB ,
                    SUM(round(valdb-valcr,2) + 00000000000.00) AS SPE,
                    SUM(round(saldo,2) + 00000000000.00) AS SAB,
                    SUM(round(saldo,2) + 00000000000.00) AS SNT,
		    per_FecFinal AS PERI
                FROM tmp_balcomp join concuentas on cue_codcuenta = det_codcuenta " .
                         " left join conpersonas on per_codauxiliar = det_idauxiliar
                          left join conactivos on act_codauxiliar = det_idauxiliar
			  join conperiodos on per_NumPeriodo = ". $ilPeriodo." AND per_Aplicacion= 'CO'
                WHERE cue_posicion + if(det_idauxiliar>0,1,0)  <= ". $ilNivel .$slEsquema ." 
                GROUP BY 1,2,3,4,5
		".  $slSaldoCondicion .
		"ORDER by cuenta, det_idauxiliar,3, 5 ";
    $rsG= fSQL($db, $aSql);
    return $rsG;	
}
/**
 *		Procesamiento
*/

include ("../../AAA_SEGURIDAD/LibPhp/pie.php");

/*							Recibir Parametros de Filtrado */

$ilPeriodo  =fGetParam('pPer', 1);
$igAcumula  =fGetParam('pAcu', 1); // Indicador de acumulacion de saldos
$ilNivel    =fGetParam('pNiv', 6);
$slEsquema  =fGetParam('pEsq', '');
$slTipo     =fGetParam('p','GEN');
/*							Armar Condicion SQL*/

set_time_limit (0) ;
$db->debug=fGetParam('pAdoDbg', 0);
$rs = fDefineQry($db, $ilPeriodo, $ilNivel, $slEsquema);
$tplFile = 'CoAdEf_balcom_.tpl';
$Tpl->assign("gsNumCols", 6);
$Tpl->assign("gsEmpresa", $_SESSION["g_empr"]);
$Tpl->assign("acumulado", $igAcumula);
$gsSubt= " " . fGetParam("pCond", "-");
$Tpl->assign("gsSubTitul", $gsSubt );
$Tpl->assign("gsNumCols", 7);
$aDet =& SmartyArray($rs);
$Tpl->assign("agData", $aDet);

$Tpl->display($tplFile);

?>
