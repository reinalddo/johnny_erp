<?php
//BindEvents Method @1-CABF686C
function BindEvents()
{
    global $CoAdTi_list;
    $CoAdTi_list->genclasetran_TotalRecords->CCSEvents["BeforeShow"] = "CoAdTi_list_genclasetran_TotalRecords_BeforeShow";
}
//End BindEvents Method

//CoAdTi_list_genclasetran_TotalRecords_BeforeShow @101-6DA4E6EB
function CoAdTi_list_genclasetran_TotalRecords_BeforeShow()
{
    $CoAdTi_list_genclasetran_TotalRecords_BeforeShow = true;
//End CoAdTi_list_genclasetran_TotalRecords_BeforeShow

//Retrieve number of records @102-1C1398D3
    global $CoAdTi_list;
    $CoAdTi_list->genclasetran_TotalRecords->SetValue($CoAdTi_list->ds->RecordsCount);
//End Retrieve number of records

//Close CoAdTi_list_genclasetran_TotalRecords_BeforeShow @101-8065DDEF
    return $CoAdTi_list_genclasetran_TotalRecords_BeforeShow;
}
//End Close CoAdTi_list_genclasetran_TotalRecords_BeforeShow
?>
