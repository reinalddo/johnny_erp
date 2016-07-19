<?php
/*    Reporte - Cuadro de Cuentas por Pagar
     @rev	esl	07/12/2010	excluir documentos de bitacora que fueron devueltos al cliente
 */

ob_start("ob_gzhandler");
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

include("../LibPhp/excelOut.php");

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$glFlag= fGetParam('pEmpq', false);

// parametro para el query general -Fechas
$pQry = fGetParam('pQryCom','');

$subtitulo = fGetParam('pCond','');
$subtitulo="REPORTE DE INCONSISTENCIAS DE COMPROBANTES";
$Tpl->assign("subtitulo",$subtitulo);

/*CONSULTAR SI EXISTEN REGISTROS PARA MOSTRAR*/
$sSql = "SELECT c.com_RegNumero AS reg  /* CONSULTAR COMPROBANTES DESCUADRADOS */ 
	 FROM concomprobantes c
	 JOIN condetalle d ON d.det_RegNumero = c.com_RegNumero
	 WHERE 1= 1 ";
$sSql .= ($pQry ? " and ". $pQry  : "  " );
$sSql .= " GROUP BY c.com_RegNumero
	   HAVING SUM(d.det_ValDebito - d.det_ValCredito) != 0 "; 
$sSql .= " UNION  /*  COMPROBANTES CON PERIODO INCORRECTO */ 
	 SELECT c.com_RegNumero AS reg 
	 FROM concomprobantes c
	 JOIN condetalle d ON d.det_RegNumero = c.com_RegNumero
	 JOIN conperiodos p ON p.per_Aplicacion = 'CO' AND c.com_FecContab BETWEEN p.per_FecInicial AND p.per_FecFinal
	 WHERE p.per_NumPeriodo != c.com_NumPeriodo ";
$sSql .= ($pQry ? " and ". $pQry  : "  " );
$sSql .= "GROUP BY c.com_RegNumero";
$sSql .= " UNION /*  COMPROBANTES PENDIENTES */
	 SELECT c.com_RegNumero AS reg 
	 FROM concomprobantes c
	 JOIN condetalle d ON d.det_RegNumero = c.com_RegNumero
	 WHERE c.com_EstProceso = -1 ";
$sSql .= ($pQry ? " and ". $pQry  : "  " );
$sSql .= "GROUP BY c.com_RegNumero"; 

$rs = $db->execute($sSql . $slFrom);


if($rs->EOF){
   fErrorPage('','NO SE GENERARON CUENTAS POR PAGAR', true,false);
}else{
    
    /* CONSULTAR COMPROBANTES DESCUADRADOS */
   $sSql = "SELECT c.com_TipoComp AS tip, c.com_NumComp AS cmp, c.com_RegNumero AS reg, DATE_FORMAT(c.com_FecContab,'%Y-%m-%d') AS fecha,
		   SUM(d.det_ValDebito) AS deb, SUM(d.det_ValCredito) AS cre, SUM(d.det_ValDebito)- SUM(d.det_ValCredito) AS sal 
	    FROM concomprobantes c
	    JOIN condetalle d ON d.det_RegNumero = c.com_RegNumero
	    WHERE 1= 1 ";
   $sSql .= ($pQry ? " and ". $pQry  : "  " );
   $sSql .= " GROUP BY c.com_RegNumero
	      HAVING SUM(d.det_ValDebito - d.det_ValCredito) != 0 order by 3,1, 2"; 
   $rs1 = $db->execute($sSql . $slFrom);
   if($rs1->EOF){
      $sSql = "SELECT '-' AS tip, '-' AS cmp, '-' AS reg,'-' AS fecha, 0 AS deb, 0 AS cre, 0 AS sal "; 
      $rs1 = $db->execute($sSql . $slFrom);
   } 
   $rs1->MoveFirst();
   $aDet =& SmartyArray($rs1);
   $Tpl->assign("agData", $aDet);
    
    
   /*  COMPROBANTES CON PERIODO INCORRECTO */
   $sSql = "SELECT c.com_TipoComp AS tip, c.com_NumComp AS cmp, c.com_RegNumero AS reg,DATE_FORMAT(c.com_FecContab,'%Y-%m-%d') AS fecha,
		   p.per_NumPeriodo AS periodo, c.com_NumPeriodo AS periodoCmp
	    FROM concomprobantes c
	    JOIN condetalle d ON d.det_RegNumero = c.com_RegNumero
	    JOIN conperiodos p ON p.per_Aplicacion = 'CO' AND c.com_FecContab BETWEEN p.per_FecInicial AND p.per_FecFinal
	    WHERE p.per_NumPeriodo != c.com_NumPeriodo ";
   $sSql .= ($pQry ? " and ". $pQry  : "  " );
   $sSql .= "GROUP BY c.com_RegNumero order by 3,1, 2"; 
   $rs2 = $db->execute($sSql . $slFrom);
   if($rs2->EOF){
      $sSql = "SELECT '-' AS tip, '-' AS cmp, '-' AS reg, '-' AS fecha, '-' AS periodo, '-' AS periodoCmp "; 
      $rs2 = $db->execute($sSql . $slFrom);
   }
   $rs2->MoveFirst();
   $aDet =& SmartyArray($rs2);
   $Tpl->assign("agData2", $aDet);
    


   /*  COMPROBANTES PENDIENTES */
   $sSql = "SELECT c.com_TipoComp AS tip, c.com_NumComp AS cmp, c.com_RegNumero AS reg,DATE_FORMAT(c.com_FecContab,'%Y-%m-%d') AS fecha
	    FROM concomprobantes c
	    JOIN condetalle d ON d.det_RegNumero = c.com_RegNumero
	    WHERE c.com_EstProceso = -1 ";
   $sSql .= ($pQry ? " and ". $pQry  : "  " );
   $sSql .= "GROUP BY c.com_RegNumero order by 3,1, 2"; 
   $rs3 = $db->execute($sSql . $slFrom);
   if($rs3->EOF){
      $sSql = "SELECT '-' AS tip, '-' AS cmp, '-' AS reg, '-' AS fecha"; 
      $rs3 = $db->execute($sSql . $slFrom);
   }
   $rs3->MoveFirst();
   $aDet =& SmartyArray($rs3);
   $Tpl->assign("agData3", $aDet);
   
   
   

    
   $slPiePag = $_SESSION["g_user"] . ", " . date("%d %M %y");
   $Tpl->assign("slPiePag", $slPiePag);
   if (!$Tpl->is_cached('../Co_Files/CoTrTr_InconsistenciasComp.tpl')) {
          }
          $Tpl->display('../Co_Files/CoTrTr_InconsistenciasComp.tpl');
}
?>