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
	$this->template_dir = '.';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = fGetParam("pAppDbg",false);
	
   }
}

include ("../../AAA_SEGURIDAD/LibPhp/excelOut.php");
$Tpl = new Smarty_AAA();
$glFlag= fGetParam('pEmpq', false);
/*
 ********************************
*/

/*
 *	Define la instruccion sql a ejecutarse. Genera tambien los arreglos con las cabeceras de la tabla, con
 *	agrupamiento de cabecera
 **/
function &fDefineQry($pSql, $ilPeriodo, $ilNivel, $pEsquema){
   global $rsG, $gaCols ;
   global $db;
    global $slTipo;
    $aSql=Array();
    $aSql[] ="DROP TABLE IF EXISTS tmp_auxiliares";
    $aSql[] ="drop table  if exists tmp_balcomp";

    $aSql[] ="CREATE TEMPORARY TABLE tmp_auxiliares
                SELECT
                        ucase( rpad(concat( left(per_Apellidos, 50), ' ', left(ifnull(per_Nombres,' '), 40)), " . (($slTipo =='COM')?20:45) . ", '-') )as tmp_descripcion,
                        per_codauxiliar as tmp_codauxiliar
                FROM conpersonas";
    $aSql[] ="INSERT INTO tmp_auxiliares
                SELECT  ucase( rpad(concat( left(act_descripcion, 50), ' ', left(ifnull(act_descripcion1,' '), 40))," . (($slTipo =='COM')?20:45) . ", '-') )as tmp_descripcion,
                        act_codauxiliar as tmp_codauxiliar
                FROM conactivos";
                
    $aSql[] ="CREATE INDEX i_tmpaux on tmp_auxiliares(tmp_codauxiliar)";
/**
*               Valores de cuentas ascendentes
**/
    $aSql[] ="CREATE  TABLE  tmp_balcomp
	            SELECT com_numperiodo,
                    red_codcuenta as cuenta ,
                    red_Ascendent,
                    cc.cue_padre as cue_padre ,
                    cc.cue_id as cue_id,
                    red_codcuenta  AS det_codcuenta,
                    000000 as det_idauxiliar,
                    cc.cue_tipmovim as cue_tipmovim,
                    sum(round(det_valdebito,2) - round(det_valcredito,2) -0000000000.00) as salan,
                    sum(round(det_valdebito,2) - round(det_valcredito,2) -0000000000.00)  as valdb,
					0000000000.00 as valcr,
                    sum(round(det_valdebito,2)  - round(det_valcredito,2)) as saldo
 	            FROM concomprobantes, condetalle, concuentas, conredcuentas, concuentas cc
	            WHERE com_numperiodo BETWEEN ". ($ilPeriodo >12 ? $ilPeriodo  - 12 : 0) . " AND " . $ilPeriodo . " AND 
                    com_estProceso = 5 AND
	                det_regnumero = com_regnumero AND
                    concuentas.cue_codcuenta = det_codcuenta AND
	                red_cueid = concuentas.cue_id AND cc.cue_id = red_ascendent
                    AND red_codcuenta
                GROUP BY 1,2,3,4, 5,6,7,8
                ORDER BY 1,2" ;

	$aSql[] = "CREATE INDEX i_tmpsal on tmp_balcomp(cuenta, red_ascendent)";
/**
*       Cuentas de movimiento
**/
/*
    $aSql[] ="INSERT INTO tmp_balcomp
              SELECT com_numperiodo,
					 det_codcuenta as cuenta ,
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
                HAVING saldo <> 0
                ORDER BY 1,2";
*/
/**
*       Cuentas de movimiento
**/
   /* $aSql[] ="INSERT INTO tmp_balcomp
	            SELECT
                    com_numperiodo,
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
                WHERE com_numperiodo <= ". $ilPeriodo . " AND com_estProceso = 5 AND
                    det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND
                    red_cueid = concuentas.cue_id AND cc.cue_id = red_ascendent AND red_codcuenta AND
                    (det_valdebito <> 0 or det_valcredito <> 0)
                GROUP BY 1,2,3,4, 5,6, 7
                ORDER BY 1,2";
   */
/**
*       Cuentas de movimiento del periodo
**/

    $aSql[] ="INSERT INTO tmp_balcomp
               SELECT
		     com_numperiodo,
		     det_codcuenta as cuenta , 9 as red_Ascendent, cue_id as cue_padre,
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
	            WHERE com_numperiodo BETWEEN ". ($ilPeriodo >12 ? $ilPeriodo  - 12 : 0) . " AND " . $ilPeriodo . " AND 
	                  det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND det_idauxiliar <> 0 and
	                  (det_valdebito <> 0 or det_valcredito <> 0)
             GROUP BY 1,2,3,4,5,6,7,8
             ORDER BY 1,7";
/*    $slSqlCols="SELECT
	  concat('SUM(CASE WHEN com_numperiodo = ', per_numperiodo, ' THEN round(valdb - valcr,2) + 00000000000.00 ELSE 000000000.00 END) AS `', 
		  date_format(per_FecFinal,'%d-%b-%y'), '`'
		  ) AS sqlTxt
	  FROM conperiodos
	  WHERE per_aplicacion = 'CO' AND per_numperiodo between " . ($ilPeriodo >12 ? $ilPeriodo  - 12 : 0) . " AND " . $ilPeriodo ;
    $alPivot=$db->getCol($slSqlCols);*/

   $slSqlCols2="SELECT
	  date_format(per_FecFinal,'%d-%b-%y') AS col,
	  concat('SUM(CASE WHEN com_numperiodo = ', per_numperiodo, ' THEN round(valdb - valcr,2) + 00000000000.00 ELSE 000000000.00 END) AS `', 
		  date_format(per_FecFinal,'%d-%b-%y'), '`'
		  ) AS sqlTxt
	  FROM conperiodos
	  WHERE per_aplicacion = 'CO' AND per_numperiodo between " . ($ilPeriodo >12 ? $ilPeriodo  - 12 : 0) . " AND " . $ilPeriodo ;

   $ors=$db->Execute($slSqlCols2);
   $alPv2 = $ors->getAssoc(false);
/*echo "<br><br><br><br>";		
print_r($alPivot);
echo "<br><br><br>2do Pivot:<br>";
print_r($alPv2);
echo "<br><br><br>2do RS:<br>";
print_r($ors);
echo "<br><br><br>Columnas 2<br>";
echo implode(', ', $alPv2);*/

   $gaCols = array_keys($alPv2);
   $alPv2[]= "SALDO FINAL";
//    $slColumns = implode(', ', $alPivot);
   $slColumns = implode(', ', $alPv2);

   $slSaldoCondicion = (($slTipo == 'COM') ? "  " : "HAVING sum(valdb - valcr) <> 0 ") ;
   $slEsquema = (($pEsquema) ? ' AND det_codcuenta ' . $pEsquema : ' ');
/*
 		left (concat( repeat('&nbsp;&nbsp;&nbsp;', cue_posicion),
			 if (det_idauxiliar = 0 ,
				 cue_descripcion,
				 concat('&nbsp;&nbsp;&nbsp;',
						 left(ifnull(per_apellidos,''),50),
						 left(ifnull(act_descripcion,''),50), ' ',
						 left(ifnull(per_nombres,''),50) ,
						 left(ifnull(act_descripcion1, ''),80)
				 )
			      )
		     ), 100) AS DESCRIPCION, " . $slColumns ."

*/
//echo $slEsquema;
    $aSql[] =
	"SELECT
	    det_codcuenta as CUENTA, 
		concat( if(det_idauxiliar=0,'', concat(' .', det_idauxiliar)) )AS AUXILIAR, 
		concat( repeat('&nbsp;&nbsp;&nbsp;', cue_posicion),
			 if (det_idauxiliar = 0 ,
				 cue_descripcion,
				 concat('&nbsp;&nbsp;&nbsp;',
						 left(ifnull(per_apellidos,''),50),
						 left(ifnull(act_descripcion,''),50), ' ',
						 left(ifnull(per_nombres,''),50) ,
						 left(ifnull(act_descripcion1, ''),80)
				 )
			      )
		  )AS DESCRIPCION, " . $slColumns ."
		 ,SUM(round(saldo,2) + 00000000000.00) AS 'ACUMULADO',
		 per_FecFinal AS PERIODO
	  FROM tmp_balcomp join concuentas on cue_codcuenta = det_codcuenta " .
		 " left join conpersonas on per_codauxiliar = det_idauxiliar
		  left join conactivos on act_codauxiliar = det_idauxiliar
	  JOIN  conperiodos on  per_Aplicacion= 'CO' AND per_numperiodo = com_numperiodo  AND per_NumPeriodo <= ". $ilPeriodo." 
	  WHERE cue_posicion + if(det_idauxiliar>0,1,0)  <= ". $ilNivel . 
		 $slEsquema ."
	  GROUP BY 1,2 "
		  . $slSaldoCondicion .
	  "ORDER by cuenta, det_idauxiliar";
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
$slTipo     =fGetParam('p','COM');
/*							Armar Condicion SQL*/
$gaCols 	=array();
set_time_limit (0) ;
$db->debug=fGetParam('pAdoDbg', 0);
$rs = fDefineQry($db, $ilPeriodo, $ilNivel, $slEsquema);
$tplFile = 'CoAdEf_balmultiperhtml.tpl';
$gaCols[]="ACUMULADO";
$Tpl->assign("gsNumCols", count($gaCols) + 4);
$Tpl->assign("gaColumnas", $gaCols);
$Tpl->assign("gsEmpresa", $_SESSION["g_empr"]);
$Tpl->assign("acumulado", $igAcumula);
$gsSubt= " " . fGetParam("pCond", "-");
$Tpl->assign("gsSubTitul", $gsSubt );
//$Tpl->assign("gsNumCols", 7);
$aDet =& SmartyArray($rs);
$Tpl->assign("agData", $aDet);

$Tpl->display($tplFile);

?>
