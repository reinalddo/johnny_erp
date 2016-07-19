<?php
include_once("General.inc.php");
define("RelativePath", "..");
include_once("adodb.inc.php");
include_once("GenUti.inc.php");
include_once("LiLiPr_func.inc.php");

$gbTrans	= false;
$db = Null;
$cla=null;
$olEsq=null;
$inv= '09_inventario';
$db = &ADONewConnection('mysql');
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->autoRollback = true;
$db->debug = fGetParam("pAdoDbg",0);
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);

$OC = fGetParam('pOc','0') ;
$Sql="update ".$inv.".invdetalle
	  set det_CosTotal=0,det_cosunitario=0
	  where det_RegNUmero IN (select enl_RegNumero2 as det_RegNUmero from ".$inv.".invenlace where enl_RegNumero IN (".$OC."))";
if ($db->Execute($Sql)){
	  $Sql="update ".$inv.".concomprobantes
	  set com_EstOperacion=-1,com_EstProceso=5
	  where com_TipoComp='IB' and com_RegNUmero IN (select enl_RegNumero2 as con_RegNUmero from ".$inv.".invenlace where enl_RegNumero IN (".$OC."))";
		  if ($db->Execute($Sql)){
		   	   			$Sql="DELETE FROM ".$inv.".invenlace where enl_CodEmpresa='". $_SESSION['g_dbase'] ."' and enl_RegNumero IN (".$OC.")";
				   	   	if ($db->Execute($Sql)){
				   	   	$olResp->success = true;
						$olResp->message="";
						$olResp->records=$db->Affected_Rows();
						$olResp->lastId =$db->Insert_ID();
				   	   	}
					  else {
					    $olResp->success = false;
					    //$olResp->metaData= $db->MetaColumns($pTabla);
					    $olResp->records=0;
						$olResp->lastId =0;
						$olResp->error  = 'Error';
						$olResp->message = $db->ErrorMsg();
						$olResp->sql  = $Qry;
					}
			} 
			else {
		    $olResp->success = false;
		    //$olResp->metaData= $db->MetaColumns($pTabla);
		    $olResp->records=0;
			$olResp->lastId =0;
			$olResp->error  = 'Error';
			$olResp->message = $db->ErrorMsg();
			$olResp->sql  = $Qry;
		}
	} 
	else {
	    $olResp->success = false;
	    //$olResp->metaData= $db->MetaColumns($pTabla);
	    $olResp->records=0;
		$olResp->lastId =0;
		$olResp->error  = 'Error';
		$olResp->message = $db->ErrorMsg();
		$olResp->sql  = $Qry;
	}
print(json_encode($olResp));
?>