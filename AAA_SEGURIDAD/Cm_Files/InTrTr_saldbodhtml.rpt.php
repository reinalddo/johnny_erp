<?php

ob_start("ob_gzhandler");
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
        $this->template_dir = './';
	//$this->template_dir = '../templates';
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

function &fDefineQry(&$db){
    global $giPerio;
    global $gdDesde;
    global $gdHasta;
    global $iCtoFlag;
    global $giGeneral;
    global $giTipPro;
    $dlFecIni = nz(fDBValor($db, 'genparametros', "par_valor1", "par_clave = 'ININIC' "),'2004-12-31'); // @fah230108: fecha inicial de procesamiento de inventario
    $slCondDat = fGetParam("pQryCom", false);
    if (!$slCondDat){
        $slBodega = fGetParam("com_Emisor", false);
        $slTipCom = fGetParam("com_TipoComp", false);
        $slItem = fGetParam("det_CodItem", false);
        $slItem = $slItem ? ' det_coditem = ' .$slItem : '';
        $slCondDat  = (($slBodega)? " com_Emisor = " . $slBodega :"");
        if ($slItem ) $slCondDat .= (($slCondDat)? " AND " : "" ) . $slItem ;
        if ($slTipCom) $slCondDat .= (($slCondDat)? " AND  " : "" ) . " com_Tipocomp = " . $slTipCom ;
    }
    if (strlen($slCondDat) > 1) $slCondDat .= " AND " ;
    $slConI = " com_feccontab < '" . $gdDesde . "'"; //         Condicion Inicial
    $slConD = " com_feccontab >= '" . $gdDesde . "'";//         Condicion 'Durante'
    $slConF = " com_feccontab <= '" . $gdHasta . "'";//         Condicion Final2;
    $ilLong=($iCtoFlag)?18:40;
   $alSql[] = "CREATE TEMPORARY TABLE tmp_invmovs
                   SELECT com_emisor as 'BOD',
                act_grupo AS 'GRU',
	        par_descripcion AS 'GRD',
	        det_coditem AS 'ITE',
                com_feccontab as 'FEC',
                pro_orden AS 'ORD',
                concat(com_tipocomp, '-', com_numcomp) as 'COM',

                concat(left(act_descripcion,18), ' ',left(act_descripcion1,10)) AS 'DES',
            	left(ifnull(com_receptor,left(com_concepto," . $ilLong . "))," .$ilLong . " ) as 'REC',
                uni_abreviatura as 'UNI',
            	if (pro_signo = 1, det_cosTotal , 000000000.00) / if (pro_signo = 1, det_cantequivale , 000000000.00)     as SAN,
            	000000000.00        as VAN,
            	if (pro_signo = 1, det_cantequivale , 000000000.00)  as CIN,
            	if (pro_signo = 1, det_cosTotal , 000000000.00)      as VIN,
            	if (pro_signo = -1 , det_cantequivale , 000000000.00) as CEG,
            	if (pro_signo = -1 , det_cosTotal , 000000000.00)    as VEG,
            	det_cantequivale * pro_signo  as SAC,
            	det_cosTotal * pro_signo      as VAC,
            	(det_cosTotal * pro_signo) /(det_cantequivale * pro_signo)  as PUN,
		(det_cosTotal * pro_signo)/(det_cantequivale * pro_signo) as VAV
            FROM	invprocesos JOIN
            	concomprobantes ON pro_codproceso = " . $giTipPro . " AND com_tipocomp = cla_tipotransacc
            	JOIN invdetalle ON det_regnumero = com_regnumero
            	JOIN conactivos ON act_codauxiliar = det_coditem
            	JOIN genunmedida ON uni_codunidad= act_unimedida
                JOIN genparametros ON par_clave='ACTGRU' AND par_secuencia=act_grupo
            WHERE " . $slCondDat . " com_feccontab BETWEEN '" . $gdDesde . "' AND '" . $gdHasta . "'
                  AND (det_cantequivale <> 0 OR det_cosTotal <> 0)
                  AND com_feccontab >= '2004-12-31'
	    GROUP BY 1,2,3,4,5,6,7,8
        ";
//            ORDER BY 1,5,2,3
   if (!$giGeneral)   //        Kardex general no quiebra por bodega
        $alSql[] = "create index I_MOVS ON tmp_invmovs(BOD, ITE, GRU, FEC)";
   else
        $alSql[] = "create index I_MOVS ON tmp_invmovs(ITE, GRU, FEC)";
   $alSql[] = "INSERT INTO tmp_invmovs
             SELECT com_emisor as 'BOD',
                act_grupo AS 'GRU',
                par_descripcion AS 'GRD',
		det_coditem AS 'ITE',
               '" .
                $gdDesde . "' AS 'FEC',
                0 AS 'ORD',
                ' ' as 'COM',

                concat(act_descripcion, ' ',act_descripcion1) AS 'DES',
            	'SALDO ANTERIOR ' as 'REC',
                uni_abreviatura as 'UNI',
            	if(SUM(det_cantequivale * pro_signo) <>0, SUM(det_cosTotal * pro_signo) / SUM(det_cantequivale * pro_signo),0)        as SAN,
            	000000000.00            as VAN,
            	000000000.00  as CIN,
            	000000000.00  as VIN,
            	000000000.00  as CEG,
            	000000000.00  as VEG,
            	SUM(det_cantequivale * pro_signo)  as SAC,
            	SUM(det_cosTotal * pro_signo)      as VAC,
            	SUM(det_cosTotal * pro_signo) /	SUM(det_cantequivale * pro_signo)  as PUN,
		SUM(det_cosTotal * pro_signo)/ SUM(det_cantequivale * pro_signo) AS VAV
		FROM invprocesos JOIN
            	concomprobantes ON pro_codproceso =  " . $giTipPro . "  AND com_tipocomp = cla_tipotransacc
            	JOIN invdetalle ON det_regnumero = com_regnumero
            	JOIN conactivos ON act_codauxiliar = det_coditem
            	JOIN genunmedida ON uni_codunidad= act_unimedida
                JOIN genparametros ON par_clave='ACTGRU' AND par_secuencia=act_grupo
            WHERE  " . $slCondDat . $slConI . "  AND (det_cantequivale <> 0 OR det_cosTotal <> 0) AND com_feccontab >= '" . $dlFecIni . "'
            GROUP BY 1,2,3,4,5,6,7,8
            " ;
//             ORDER BY 1,5,2,3
    if (!$giGeneral ){   //                      El kardex general no quiebra por bodega
        $alSql[] = "SELECT * FROM tmp_invmovs
                ORDER BY BOD,DES";
    }
    else{
        $alSql[] = "SELECT * FROM tmp_invmovs
        ORDER BY BOD,DES";
    }

    $rs= fSQL($db, $alSql);
    if (!$rs) die("NO SE EJECUTo LA CONSULTA: " . $alSql[0]);
    return $rs;
}

include("../../AAA_SEGURIDAD/LibPhp/pie.php");

$gdDesde=false;
$gdHasta= false;
$giGeneral = fGetParam('pGral',  false);
 $giPerio   = fGetParam('pPerio', false);    // Un periodo
  $giPerIn   = fGetParam('pPerIn', false);    // Periodo Inicial
 $giPerFi   = fGetParam('pPerFi', false);    // Periodo Final
$iCtoFlag  = fGetParam('pCosto', false);
$gdDesde   = fGetParam('pFecI', false);
$gdHasta   = fGetParam('pFecF', false);
$giTipPro  = fGetParam("pTipPro", 1);	// Codigo del proceso a ejecutar
$gaCols 	=array();
if (!$giPerIn && !$gdDesde) { //                              Si viene como parametro un periodo
    $pe = fDBValor($db, 'conperiodos', 'per_fecInicial, per_FecFinal', "per_aplicacion = 'IN' AND per_numPeriodo = " . $giPerio);
    list ($gdDesde, $gdHasta) = $pe;
}
elseif (!$giPerFi && !$gdHasta) { //                                        Si viene como parametro dos periodos
    $pe = fDBValor($db, 'conperiodos', 'min(per_fecInicial), max(per_FecFinal)', "per_aplicacion = 'IN' AND per_numPeriodo BETWEEN  " . $giPerIn . " AND " . $giPerFi);
    list ($gdDesde, $gdHasta) = $pe;
}
if ($giPerFi && !$gdHasta) { //                                        Si viene como parametro dos periodos
    $pe = fDBValor($db, 'conperiodos', 'min(per_fecInicial), max(per_FecFinal)', "per_aplicacion = 'IN' AND per_numPeriodo BETWEEN  " . $giPerIn . " AND " . $giPerFi);
    list ($gdDesde, $gdHasta) = $pe;
}

$db =& fConexion();
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$rs = fDefineQry($db);
$fgSumCant=0;
$fgSumCant1=0;
$fgBandera=0;
$Tpl->assign("fgSumCant", 0);
$Tpl->assign("fgSumCant1", 0);
$Tpl->assign("fgBandera", "Inicio");
$tplFile = 'InTrTr_saldbodhtml.tpl';
$Tpl->assign("gsNumCols", count($gaCols) + 4);
$Tpl->assign("gaColumnas", $gaCols);

$Tpl->assign("gsEmpresa",$_SESSION["g_empr"]);
$gsSubt= " " . fGetParam("pCond", "-");
$Tpl->assign("gsSubTitul", $gsSubt );
$Tpl->assign("gsNumCols", 7);
$Tpl->assign("gsDesde", $gdDesde);
$Tpl->assign("gsHasta", $gdHasta);
$aDet =& SmartyArray($rs);
$Tpl->assign("agData", $aDet);
$Tpl->display($tplFile);
?>
