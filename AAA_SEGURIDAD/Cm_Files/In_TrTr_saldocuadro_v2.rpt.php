<?php
ob_start("ob_gzhandler");
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");
include("adoConn.inc.php");
include("GenCifras.php");
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
if (fGetparam("pExcel",false)){
   header("Content-Type:  application/vnd.ms-excel");
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}

include ("../../AAA_SEGURIDAD/LibPhp/excelOut.php");
$Tpl = new Smarty_AAA();

$glFlag= fGetParam('pEmpq', false);
$Tpl->debugging =fGetparam("pAppDbg",false);
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/

function &fDefineQry(&$db){
   global $giPerio;
    global $gdDesde;
    global $gdHasta;
    global $gsHeader;
    //$slConI = " com_feccontab < '" . $gdDesde . "'"; //         Condicion Inicial
    //$slConD = " com_feccontab >= '" . $gdDesde . "'";//         Condicion 'Durante'
    //$slConF = " com_feccontab <= '" . $gdHasta . "'";//         Condicion Final2
//      Definicion de query para x-ref
    $slSql = "SELECT DISTINCT com_emisor AS 'BOD',
                left(concat(per_Apellidos, ' ', per_Nombres),15) as 'DES',
                concat('SUM(IF (com_emisor = ', per_codauxiliar, ', det_cantequivale * pro_signo,0)) AS B',
                       per_codauxiliar) AS TXT,
                concat('B', per_codauxiliar) as CLV
            FROM	invprocesos JOIN
            	concomprobantes ON pro_codproceso = 1 AND com_tipocomp = cla_tipotransacc JOIN
            	conpersonas ON per_codauxiliar = com_emisor JOIN
            	invdetalle on det_regnumero = com_regnumero
            WHERE com_feccontab between '" . $gdDesde . "' and '" . $gdHasta. "' " .
            "ORDER BY 1,3
        ";
    $rs0 = $db->Execute($slSql);
    $slPivot = "";
    $gsHeader = '$' . "rep->colHead = array('ITE' => 'COD.' ,
                        'DES'  => 'I T E M ',
                        'UNI'  => 'U.',";
    while ($rec = $rs0->FetchRow()){
        if (strlen($slPivot)>0) {
            $slPivot  .= ", ";
            $gsHeader .= ", ";
        }
        $slPivot  .= $rec['TXT'];
        $gsHeader .= "'" . $rec['CLV'] . "'=>'". $rec['DES'] ."'";
    }
    $gsHeader .= ", 'SAC'=>'SALDO ACTUAL', 'VAC'=>'COSTO ', 'PUN'=>'COSTO UNITARIO');";
 //echo $slPivot . " <br>";
  //echo $gsHeader. " <br>";
//

   $slSql = "SELECT act_grupo AS 'GRU',
            	det_coditem AS 'ITE',
                concat(act_descripcion, ' ', act_descripcion1) as 'DES' ,
                uni_abreviatura as 'UNI',
                " . $slPivot . ",
            	sum(det_cantequivale * pro_signo)  as SAC,
            	sum(det_cosTotal * pro_signo)      as VAC,
            	sum(det_cosTotal * pro_signo) /	sum(det_cantequivale * pro_signo)  as PUN
            FROM	invprocesos JOIN
            	concomprobantes ON pro_codproceso = 1 AND com_tipocomp = cla_tipotransacc
            	JOIN invdetalle ON det_regnumero = com_regnumero
            	JOIN conactivos ON act_codauxiliar = det_coditem
            	JOIN genunmedida ON uni_codunidad= act_unimedida
            WHERE com_feccontab between '" . $gdDesde . "' and '" . $gdHasta. "' " .
	    	"  AND (det_cantequivale <> 0 OR det_cosTotal <> 0)
	    GROUP BY 1,2,3,4
            HAVING SAC<>0  OR VAC <>0
            ORDER BY 1,3
        ";
 //echo $slSql;
    $rsLiq = $db->Execute($slSql);
    IF(!$rsLiq) fErrorPage('','NO SE GENERARON LOS DETALLES DE INVENTARIO', true,false);
    return $rsLiq;
}


$db =& fConexion();
$db->debug=fGetParam("pAdoDbg", 0);
set_time_limit (0) ;

$gdDesde=false;
$gdHasta= false;
$gdDesde = fGetParam('pFecI', false);
$gdHasta = fGetParam('pFecF', false);

$rs = fDefineQry($db);

$tplFile = 'InTrTr_saldocuadro_v2.tpl';
$Tpl->assign("gsNumCols", 6);
$Tpl->assign("gsEmpresa", $_SESSION["g_empr"]);
$Tpl->assign("gsDesde", $gdDesde);
$Tpl->assign("gsHasta", $gdHasta);
$Tpl->assign("gsPivot", $slPivot);
$gsSubt= " " . fGetParam("pCond", "-");
$Tpl->assign("gsSubTitul", $gsSubt );
$Tpl->assign("gsNumCols", 7);
$aDet =& SmartyArray($rs);
$Tpl->assign("agData", $aDet);
$Tpl->display($tplFile);

?>