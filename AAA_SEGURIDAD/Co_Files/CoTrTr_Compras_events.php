<?php
//BindEvents Method @1-1B3D13F0
function BindEvents()
{
    global $invcompras;
    $invcompras->invcompras_TotalRecords->CCSEvents["BeforeShow"] = "invcompras_invcompras_TotalRecords_BeforeShow";
}
//End BindEvents Method

//invcompras_invcompras_TotalRecords_BeforeShow @8-63D227BC
function invcompras_invcompras_TotalRecords_BeforeShow()
{
    $invcompras_invcompras_TotalRecords_BeforeShow = true;
//End invcompras_invcompras_TotalRecords_BeforeShow

//Retrieve number of records @9-E7278EFF
    global $invcompras;
    $invcompras->invcompras_TotalRecords->SetValue($invcompras->ds->RecordsCount);
//End Retrieve number of records

//Close invcompras_invcompras_TotalRecords_BeforeShow @8-707544E1
    return $invcompras_invcompras_TotalRecords_BeforeShow;
}
//End Close invcompras_invcompras_TotalRecords_BeforeShow


?>
