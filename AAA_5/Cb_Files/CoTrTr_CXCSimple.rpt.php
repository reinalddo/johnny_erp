<?php
/*    Reporte - Cuadro de Cuentas por Pagar
 */
// ob_start("ob_gzhandler");
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
        $this->template_dir = 'template';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = false;
   }
}

include("../LibPhp/excelOut.php");

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$glFlag= fGetParam('pEmpq', false);
// parametro para el query general
$pQry = fGetParam('pQryCom','');

// Parametros individuales para el query
$pCue_CodCuenta = fGetParam('Cue_CodCuenta',false);
$pidProvFact = fGetParam('idProvFact','');
$pcom_FecContab = fGetParam('com_Fec_Contab',false);

$subtitulo = fGetParam('pCond','');
$subtitulo="ESTADO DE CUENTA AL ";
$Tpl->assign("subtitulo",$subtitulo);
$Tpl->assign("pcom_FecContab",$pcom_FecContab);


/* para tener el saldo por cliente y dias de vencimiento*/
$sSql = "SELECT	 
            det_idauxiliar as det_idauxiliar, 
            concat(per_Apellidos, ' ', per_Nombres) as txt_nombre,
            case 
		  when datediff(CURDATE(),com_FecContab) <= 15 then '15'
		  when datediff(CURDATE(),com_FecContab) BETWEEN 16 and 30 then '30'
		  when datediff(CURDATE(),com_FecContab) BETWEEN 31 and 60 then '60'
		  when datediff(CURDATE(),com_FecContab) BETWEEN 61 and 90 then '90'
		  else '91'
	    end as Ndias,
	    sum(det_ValDebito) as det_ValDebito,
            sum(det_valdebito - det_ValCredito) as txt_valor
        FROM concomprobantes join condetalle on det_regnumero = com_regnumero
            JOIN conpersonas on per_codauxiliar = det_idauxiliar
            JOIN concuentas  on cue_codcuenta = det_codcuenta
            LEFT JOIN v_genvariables on iab_objetoid = det_idauxiliar and iab_variableid = 11
        WHERE det_codcuenta in (
						   SELECT Cue_CodCuenta FROM concuentas
						   WHERE Cue_CodCuenta LIKE CONCAT((SELECT par_Valor1
										    FROM genparametros
										    WHERE par_Clave = 'CUCXC'), '%')
						   ) ";

$sSql .= ($pCue_CodCuenta ? " and det_codcuenta = ". $pCue_CodCuenta  : "  " );
$sSql .= ($pcom_FecContab ? " and com_FecContab <= ". $pcom_FecContab  : "  " );
$sSql .= ($pidProvFact ? " and det_idauxiliar = ". $pidProvFact  : "  " );
$sSql .= " GROUP BY det_numcheque,
		    det_Codcuenta,
	            det_idauxiliar
	    HAVING sum(det_valdebito - det_ValCredito) <> 0";
$sSql .= " ORDER BY 1,2";

$rs = $db->execute($sSql . $slFrom);
while ($r = $rs->fetchRow()){
       $agValor[$r['det_idauxiliar']] += $r['det_ValDebito'];
       $agSaldos[$r['det_idauxiliar']] += $r['txt_valor'];
       $agSal[$r['det_idauxiliar']][$r['Ndias']] += $r['txt_valor'];
    }

/*para consultar los detalles*/
$sSql = "SELECT	    com_TipoComp,
	    com_NumComp,
	    com_FecContab,
	    det_numcheque as det_numdocum,
            det_Codcuenta,
	    cue_Descripcion,
            det_idauxiliar as det_idauxiliar, 
            concat(per_Apellidos, ' ', per_Nombres) as txt_nombre,
            ifnull(iab_valorTex, concat(per_Apellidos, ' ', per_Nombres)) as txt_Beneficiario,
	    com_Concepto,
	    sum(det_ValDebito) as det_ValDebito,
	    case 
		  when datediff(CURDATE(),com_FecContab) <= 15 then 'de 1 a 15 dias'
		  when datediff(CURDATE(),com_FecContab) BETWEEN 16 and 30 then 'de 16 a 30 dias'
		  when datediff(CURDATE(),com_FecContab) BETWEEN 31 and 60 then 'de 31 a 60 dias'
		  when datediff(CURDATE(),com_FecContab) BETWEEN 61 and 90 then 'de 61 a 90 dias'
		  else 'mas de 90 dias'
	    end as dias,
	    case 
		  when datediff(CURDATE(),com_FecContab) <= 15 then '15'
		  when datediff(CURDATE(),com_FecContab) BETWEEN 16 and 30 then '30'
		  when datediff(CURDATE(),com_FecContab) BETWEEN 31 and 60 then '60'
		  when datediff(CURDATE(),com_FecContab) BETWEEN 61 and 90 then '90'
		  else '91'
	    end as Ndias,
            sum(det_valdebito - det_ValCredito) as txt_valor,
	    concat(com_usuario, ', ',com_FecDigita) as usuario
        FROM concomprobantes join condetalle on det_regnumero = com_regnumero
            JOIN conpersonas on per_codauxiliar = det_idauxiliar
            JOIN concuentas  on cue_codcuenta = det_codcuenta
            LEFT JOIN v_genvariables on iab_objetoid = det_idauxiliar and iab_variableid = 11
	 WHERE det_codcuenta in (   SELECT Cue_CodCuenta FROM concuentas
				    WHERE Cue_CodCuenta LIKE CONCAT((SELECT par_Valor1
								     FROM genparametros
								     WHERE par_Clave = 'CUCXC'), '%')
   			   ) ";
$sSql .= ($pCue_CodCuenta ? " and det_codcuenta = ". $pCue_CodCuenta  : "  " );
$sSql .= ($pcom_FecContab ? " and com_FecContab <= ". $pcom_FecContab  : "  " );
$sSql .= ($pidProvFact ? " and det_idauxiliar = ". $pidProvFact  : "  " );
$sSql .= " GROUP BY det_numdocum,
		    det_Codcuenta,
	            cue_Descripcion,
                    det_idauxiliar, 
                    txt_nombre
	   HAVING sum(det_valdebito - det_ValCredito) <> 0";
$sSql .= " ORDER BY txt_nombre,dias, com_FecContab";

$rs = $db->execute($sSql . $slFrom);
    
if($rs->EOF){
    fErrorPage('','NO SE GENERARON CUENTAS POR PAGAR', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agSaldos", $agSaldos);
    $Tpl->assign("agSal", $agSal);
    $Tpl->assign("agValor", $agValor);
    $Tpl->assign("agData", $aDet);
    $slPiePag = $_SESSION["g_user"] . ", " . date("%d %M %y");
    $Tpl->assign("slPiePag", $slPiePag);
    if (!$Tpl->is_cached('CoTrTr_CXCSimple.tpl')) {
            }
            $Tpl->display('CoTrTr_CXCSimple.tpl');
}
?>