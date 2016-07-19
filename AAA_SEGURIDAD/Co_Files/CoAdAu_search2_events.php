<?php
include ("GenUti.inc.php");
	$DBdatos->DB = DBTYPE;
    $DBdatos->DBDatabase = DBNAME;
    $DBdatos->DBHost = DBSRVR;
    $DBdatos->DBUser = DBUSER;
    $DBdatos->DBPassword = DBPASS;
  
//BindEvents Method @1-4F3A4114
function BindEvents()
{
    global $auxquery;
    global $auxlist;
    $auxquery->CCSEvents["BeforeShow"] = "auxquery_BeforeShow";
    $auxlist->conpersonas_per_concatego_TotalRecords->CCSEvents["BeforeShow"] = "auxlist_conpersonas_per_concatego_TotalRecords_BeforeShow";
}
//End BindEvents Method
include_once("../LibPhp/SegLib.php");

//auxquery_BeforeShow @30-ACCB1D7A
function auxquery_BeforeShow()
{
    $auxquery_BeforeShow = true;
//End auxquery_BeforeShow

//Custom Code @59-18A84FEF
// -------------------------
    global $auxquery;
 	if(!CCGetParam("pMensj"))  $Auxquery->pMensj->Visible = false;
// -------------------------
//End Custom Code

//Close auxquery_BeforeShow @30-9A833D94
    return $auxquery_BeforeShow;
}
//End Close auxquery_BeforeShow

//auxlist_conpersonas_per_concatego_TotalRecords_BeforeShow @34-F40C9C07
function auxlist_conpersonas_per_concatego_TotalRecords_BeforeShow()
{
    $auxlist_conpersonas_per_concatego_TotalRecords_BeforeShow = true;
//End auxlist_conpersonas_per_concatego_TotalRecords_BeforeShow

//Retrieve number of records @35-F0E9DFF6
    global $auxlist;
    $auxlist->conpersonas_per_concatego_TotalRecords->SetValue($auxlist->ds->RecordsCount);
//End Retrieve number of records

//Close auxlist_conpersonas_per_concatego_TotalRecords_BeforeShow @34-1C5A003E
    return $auxlist_conpersonas_per_concatego_TotalRecords_BeforeShow;
}
//End Close auxlist_conpersonas_per_concatego_TotalRecords_BeforeShow

?>
