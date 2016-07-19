<?php
//BindEvents Method @1-A9BCE0EF
function BindEvents()
{
    global $movim_query;
    global $movim_lista;
    $movim_query->CCSEvents["BeforeShow"] = "movim_query_BeforeShow";
    $movim_lista->concomprobantes_condetall_TotalRecords->CCSEvents["BeforeShow"] = "movim_lista_concomprobantes_condetall_TotalRecords_BeforeShow";
    $movim_lista->CCSEvents["BeforeShow"] = "movim_lista_BeforeShow";
}
//End BindEvents Method

//movim_query_BeforeShow @28-0609A330
function movim_query_BeforeShow()
{
    $movim_query_BeforeShow = true;
//End movim_query_BeforeShow

//Custom Code @134-23AE5E3A
// -------------------------
    global $movim_query;
	if (!(CCGetParam("action","Q") == "Q")) $movim_query->Visible = false;
	
	// -------------------------
//End Custom Code

//Close movim_query_BeforeShow @28-974B0ADB
    return $movim_query_BeforeShow;
}
//End Close movim_query_BeforeShow

//movim_lista_concomprobantes_condetall_TotalRecords_BeforeShow @53-BC1EF4BB
function movim_lista_concomprobantes_condetall_TotalRecords_BeforeShow()
{
    $movim_lista_concomprobantes_condetall_TotalRecords_BeforeShow = true;
//End movim_lista_concomprobantes_condetall_TotalRecords_BeforeShow

//Retrieve number of records @54-2DE6F732
    global $movim_lista;
    $movim_lista->concomprobantes_condetall_TotalRecords->SetValue($movim_lista->ds->RecordsCount);
//End Retrieve number of records

//Close movim_lista_concomprobantes_condetall_TotalRecords_BeforeShow @53-E205328E
    return $movim_lista_concomprobantes_condetall_TotalRecords_BeforeShow;
}
//End Close movim_lista_concomprobantes_condetall_TotalRecords_BeforeShow

//movim_lista_BeforeShow @3-A3D188C5
function movim_lista_BeforeShow()
{
    $movim_lista_BeforeShow = true;
//End movim_lista_BeforeShow

//Custom Code @135-B8F73416
// -------------------------
    global $movim_lista;
	$slAction = CCGetParam("action","L") ;
 	if (($slAction != "L")) $movim_lista->Visible = false;
// -------------------------
//End Custom Code

//Close movim_lista_BeforeShow @3-0AADC9D6
    return $movim_lista_BeforeShow;
}
//End Close movim_lista_BeforeShow
?>
