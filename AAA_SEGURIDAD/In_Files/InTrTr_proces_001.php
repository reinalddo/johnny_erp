<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>
<head>
       <title>PROCESO!</title>
</head>
<body>

<?php
//error_reporting (E_ALL);
// ASIGNACION DE SEMANA DE LIQUIDACION 
//
include ("../LibPhp/LibInc.php");   // para produccion
include_once("GenUti.inc.php");
include_once("../LibPhp/ConTranLib.php");
include_once("../LibPhp/ConTasas.php");
$gbTrans	= false;
    $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
    $db = &ADONewConnection('mysql');
    $db->autoCommit = true;
    $db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
    $db->debug=fGetParam('pAdoDbg', 0);
//  $db->SetFetchMode(ADODB_FETCH_BOTH);
    $trSql = " drop table if exists tmp_liqui ";
    $rs = $db->execute($trSql);
	$trSql = " create  table tmp_liqui
				select com_codreceptor, com_refoperat
					from concomprobantes
					where com_tipocomp = 'LI' "; 
    $rs = $db->execute($trSql);
    $trSql = "SELECT * from tmp_liqui order by 2,1" ;
    $rs = $db->execute($trSql);
    if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum);
    $rs->MoveFirst();
//    echo "<table>";
    while (!$rs->EOF) {
		$dat = $rs->FetchNextObject(false);
	    $trSql = "  UPDATE concomprobantes set com_numproceso = " . $dat->com_refoperat . 
				 "  WHERE com_tipocomp in ('EP', 'DV') AND com_codreceptor = " . 
				 			$dat->com_codreceptor .  " AND com_refoperat <= " . $dat->com_refoperat . " and com_numproceso <=0 ";
//	    $db->execute($trSql);
//	    echo "<tr><td>$dat->com_codreceptor</td><td>$dat->com_refoperat</td><td>$trSql</td></tr>";
		echo $trSql . " ; <br>";
    }
    echo "</table>";
?>
</body>
</html>

