<?php
include_once("General.inc.php");
define("RelativePath", "..");
include_once("adodb.inc.php");
include_once("GenUti.inc.php");
include_once("MisRuc.php");
$gbTrans	= false;
$db = Null;
$cla=null;
$olEsq=null;
$db = &ADONewConnection('mysql');
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->autoRollback = true;
$db->debug = fGetParam("pAdoDbg",0);
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);

function fValidar(){
    global $db, $cla, $olEsq;
    $pSeman = fGetParam('pSem', '0') ;
    $pCor = fGetParam('pCor', '0') ;
    $trSql = "SELECT *
		FROM opecondiccorte
		WHERE cco_Semana='".$pSeman."' AND cco_Id='".$pCor."'";

    //echo $trSql;
    $alInfo=$db->GetRow($trSql);//toda la informacion del anexo
    //$alInfo["ban_PxmoChq"]=$ilNumChq;
    return array("info" => $alInfo );
    
}

function fValidarSemana(){
	 global $db, $cla, $olEsq;
     $pSeman = fGetParam('pSem', '0') ;
     $trSql = "Select 'Periodo Abierto' as msg
     		   from conperiodos
     		   where per_Aplicacion='LI' and per_Estado='1' and per_Semana=".$pSeman."
     		   union select '-'";
 
     $alInfo=$db->GetRow($trSql);
	 return array("info" => $alInfo );
}

function fValidarLiquida(){
	global $db, $cla, $olEsq;
	$Tarja = fGetParam('Tarja', '0') ;
	$trSql =   "SELECT IFNULL(SUM(tad_LiqNumero+tad_LiqProceso),0) AS contador
				FROM liqtarjadetal
				WHERE tad_NumTarja=".$Tarja;
	 $alInfo=$db->GetRow($trSql);
	 return array("info" => $alInfo );
}
/**
*
**/
/********************************************************************************************************
 *					Inicio de Proceso
 *
 ********************************************************************************************************/
$cla = NULL;
$alPar = NULL;

$opc = fGetParam('pOpc',1);
if (1 == $opc)
    $ogSal = fValidar();
elseif (2 == $opc)
    $ogSal = fValidarSemana();
elseif (3 == $opc)
    $ogSal = fValidarLiquida();
print(json_encode($ogSal));
?>
