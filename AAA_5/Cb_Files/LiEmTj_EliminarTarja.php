<?php
include_once("General.inc.php");
define("RelativePath", "..");
include_once("adodb.inc.php");
include_once("GenUti.inc.php");
$gbTrans	= false;
$db = Null;
$cla=null;
$olEsq=null;
$db = &ADONewConnection('mysql');
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->autoRollback = true;
$db->debug = fGetParam("pAdoDbg",0);
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);

$Tarja = fGetParam('numTarja', '0') ;

$Qry="Delete from liqtarjacabec where tar_NumTarja=".$Tarja;
$db->execute($Qry);

$Qry="Delete from liqtarjadetal where tad_NumTarja=".$Tarja;
$db->execute($Qry);

if ($db){
	$olResp->success = true;
	$olResp->message="";
	$olResp->records=$db->Affected_Rows();
	$olResp->lastId =$db->Insert_ID();
//	echo $olResp->lastId;
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
